<?php
class lang
{
	var $db;
	var $tpl;
	public $gen_files = array(
		'admin' => '_LANG_ADMIN',
		'user_admin' => '_LANG_USER_ADMIN',
		'user_space' => '_LANG_USER_SPACE',
		'front' => '_LANG_FRONT',
		'payment'=>'_LANG_PAYMENT'
	);

	function lang()
	{
		//global $config;
	}

	//-----------------生成全局语言文件
	function save_generate_files($new_lang,$type,$code)
	{
		global $config;
		if($new_lang=='')
			return;
		foreach($new_lang as $key=>$val)
		{
			if($val=='')
				unset($new_lang[$key]);
			else
			{
				if(strpos($val,'array')>-1){	
					if(get_magic_quotes_gpc())
						$val = stripslashes($val);
					@eval('$new_lang[$key]='.$val.';');
				}
				else
					@eval('$new_lang["$key"]="$val";');
			}
		}

		$new_lang = var_export($new_lang,TRUE);
		$lang_head = ' if(!isset($lang))'."\n".'	$lang=array();'."\n";
		$gen_files =$this->gen_files;
		$var_name = '$'.$gen_files[$type];
		$lang_head .= " global $var_name; \n";
		$content ="<?php \n".$lang_head." $var_name = $new_lang; \n".'  $lang = array_merge($lang, '.$var_name.'); '."\n?>";
		unset( $new_lang );
		
		$fp=fopen($config['webroot']."/lang/$code/$type.php",'w');
		fwrite($fp,$content,strlen($content));//将内容写入文件．
		fclose($fp);
		unset( $content );
	}

	//-----------------生成模块语言文件 ($mod:模块名称；$new_lang:写入的翻译数组；$code:翻译种类)
	function save_module_files($new_lang,$mod,$code)
	{
		global $config;
		if($new_lang=='')
			return;
		foreach($new_lang as $key=>$val)
		{
			if($val=='')
				unset($new_lang[$key]);
			else
			{
				if(strpos($val,'array')>-1){
					if(get_magic_quotes_gpc())
						$val = stripslashes($val);
					@eval('$new_lang[$key]='.$val.';');
				}else
					@eval('$new_lang["$key"]="$val";');
			}
		}
		$new_lang = stripslashes(var_export($new_lang,TRUE));
		$lang_head = ' if(!isset($lang))'."\n".'	$lang=array();'."\n";
		$mod_list = $this->module_files();
		$var_name = '$'.$mod_list[$mod];
		unset( $mod_list );
		$lang_head .= " global $var_name; \n";

		$content ="<?php \n".$lang_head." $var_name = $new_lang; \n".'  $lang = array_merge($lang, '.$var_name.'); '."\n?>";
		unset( $new_lang );

		$fp=fopen( $config['webroot']."/module/$mod/lang/$code.php",'w' );
		fwrite($fp,$content,strlen($content));//将内容写入文件．
		fclose($fp);
		unset( $content );
	}

	//-------------------模块
	function module_files()
	{
		global $config;
		$mod_dir=$config['webroot'].'/module/';
		$mod_handle = opendir($mod_dir); 
		while($file_name = readdir($mod_handle))
		{ 
			if($file_name!="."&&$file_name!=".."&&$file_name!=".svn"&&is_dir($mod_dir.'/'.$file_name.'/lang/'))
			{
				$modules["$file_name"] = '_LANG_MOD_'.strtoupper($file_name);
		   }
		}
		return $modules;
	}

	//-------------------导出语言文件
	function to_export( $code,$type )
	{   
		ob_start();
		global $config;
		if($type=='modules')
		{
			$all_modules = $this->module_files();
			foreach ( $all_modules as $file=>$var )
			{
				if(file_exists($config['webroot']."/module/$file/lang/$code.php"))
				{
					include_once($config['webroot']."/module/$file/lang/$code.php");
					eval( 'global $'.'$var'.';' );
					eval( '$gz_lang["module"]["$file"] = $'.'$var'.';' );
				}
			}
		}
		else
		{
			foreach ($this->gen_files as $file=>$var )
			{
				if($type==$file)
				{
					include_once($config['webroot']."/lang/$code/$file.php");
					eval( 'global $'.'$var'.';' );
					eval( '$gz_lang["generate"]["$file"] = $'.'$var'.';' );
				}
			}
		}
	
		$file_name = $type==''?$code:$type.'.'.$code;
		$content = var_export($gz_lang,TRUE);
		//$content = gzencode($content,9,FORCE_GZIP);//压缩文件内容
		if (ini_get('zlib.output_compression'))
				  ini_set('zlib.output_compression', 'Off');
		
		header("Pragma:public");
		header("Expires:0");
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Cache-Control: private",false); //
		header('Content-Type: application/octet-stream;charset=utf8');
		header("Content-Disposition:attachment;filename=".$file_name.'.gzip');
		header("Tips: Build Gzip File (Gzip)");
		header("Content-Transfer-Encoding: binary");
		header ('Content-Length: ' . strlen($content));
		ob_clean();
		echo stripslashes($content);die();
	} 
	
	//----------------导入语言文件
	function transla_import( $data,$code )
	{
		global $config;
		@eval("\$_TR=$data;" );

		if($_TR!=null)
		{
			//###
			if(isset($_TR['generate']))
			{
				foreach ($this->gen_files as $file=>$var ){
					if(isset($_TR['generate']["$file"])){
							$tr_file = $config['webroot']."/lang/$code/$file.php";
							$this->save_generate_files( $tr_file,$_TR['generate']["$file"],"$file" );
					}
				}
			}
			if(isset($_TR['module']))
			{
				//----------MODULE语言文件导入
				$all_modules = $this->module_files();
				foreach ( $all_modules as $file=>$var )
				{
					if(isset($_TR['module']["$file"])){
							$tr_file = $config['webroot']."/module/$file/lang/$code.php";
							if(file_exists($tr_file))
								mk_dirs($tr_file);
							$this->save_generate_files( $tr_file,$_TR['module']["$file"],"$file" );
					}
				}
			}
			//###
		}
	}

}
?>