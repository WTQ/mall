<?PHP
  
	function get_newsdata($nid)
	{
		global $db;
		$sql="select con from ".NEWSDATA." where nid=$nid";	
		$db->query($sql);
		return $db->fetchField('con');
	}
	function get_cat($id)
	{
		global $db;
		$sql="SELECT cat FROM ".NEWSCAT." WHERE catid='$id'";
		$db->query($sql);
		return $db->fetchField('cat');  
	}
  
	function get_newscat()
	{
		global $db;
		$sql="select catid,cat from ".NEWSCAT." where pid=0 order by nums asc";	
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key=>$val)
		{
			$re[$key]['subcat']=get_newscats($val['catid']);
		}
		return $re;
	}
  
	function get_newscats($id)
	{
		global $db;
		$sql="select catid,cat from ".NEWSCAT." where pid=$id and ishome=1";	
		$db->query($sql);
		$re= $db->getRows();
		foreach($re as $key=>$val)
		{
			$re[$key]['subscat']=get_newscats($val['catid']);
		}
		return $re;
	}
  
  function AutoDoPage($con,$size)
  {
	  $tags="[#page#]";
	 
	  if(strlen($con)<$size) 
	 	 return $con;
	  $str = explode('<',$con);
	  $ncon = "";
	  $istable = 0;
	  $con = "";
	  foreach($str as $i=>$k)
	  {
		 if($i==0)
		 { 
			 $ncon .= $str[$i]; continue;
		 }
		 $str[$i] = "<".$str[$i];
		 if(strlen($str[$i])>6){
			  $tname = substr($str[$i],1,5);
			  if(strtolower($tname)=='table') $istable++;
			  else if(strtolower($tname)=='/tabl') $istable--;
			  if($istable>0){ $ncon .= $str[$i]; continue; }
			  else $ncon .= $str[$i];
		 }
		 else{
			  $ncon .= $str[$i];
		 }
		 if(strlen($ncon)>$size){
			  $con .= $ncon.$tags;
			  $ncon = "";
		 }
	  }
		  if($ncon!="") $con .= $ncon;
		return $con;
  }
  
  
	function get_lowerid($classid)
	{
		global $db;
		$sql="select catid from ".NEWSCAT." where pid='$classid' order by nums asc ";
		$db->query($sql);
		$re=$db->getRows();
		if($re)
		{
			foreach ($re as $v)
			{
				$catid.=$v['catid'].',';
			}
			return substr($catid,0,-1);
		}
		else
		{
			return $classid; 
		}
	}
  
  function AutoDoPage1($con,$video,$size='')
  {
	  $video=explode('|',$video);
	  $num=0;
	 
	  $str = explode('<',$con);
	  $ncon = "";
	  $con = "";
	  foreach($str as $i=>$k)
	  {
		 if($i==0)
		 { 
			 $ncon .= $str[$i]; continue;
		 }
		 
		 $tname1=substr($str[$i],0,5);
		 if($tname1=="embed")
		 { 
			preg_match_all('/width\=\"(.*?)\"/i',$str[$i],$match);
			$width=$match[1][0];
			
			preg_match_all('/height\=\"(.*?)\"/i',$str[$i],$match);
			$height=$match[1][0];
			
			$str[$i]='embed width="'.$width.'" allownetworking="internal" allowscriptaccess="never" src="lib/flvplayer.swf" flashvars="file='.$video[$num].'" quality="high" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash">';
			$num++;
			
		 }
				
		 $str[$i] = "<".$str[$i];
		
	  }
	  echo $con;
		 if($ncon!="")
		 {
			 $con .= $ncon;
		 }
		return $con;
  }
  
@session_start();
function list_all_cat($cid=0,$type=NULL)
{
	global $db;
	
	if(!isset($_SESSION['str']))
	{
		$_SESSION['str']=NULL;
	}
	
	$sql="select * from ".NEWSCAT." where pid='$cid' order by nums asc ";
	$db->query($sql);
	$re=$db->getRows();
	foreach ($re as $v)
	{
		$_SESSION['str'].="|__";
		if(empty($type))
		{
			if($v['ishome'])
				$home='<img src=\'../image/default/on.gif\' />';
			else
				$home='<img src=\'../image/default/off.gif\' />';
			echo $_SESSION['str']."<input name='nums[]' type='text' value='$v[nums]' size='5'><input name='updateID[]' type='hidden'  value='$v[catid]'>".$home.$v['cat']." [$v[template]]<a href='?m=$_GET[m]&s=$_GET[s]&edit=$v[catid]&pid=$v[pid]&ishome=$v[ishome]&cat=".urlencode($v['cat'])."'>".lang_show('modify')."</a> | <a href='?m=$_GET[m]&s=$_GET[s]&del=$v[catid]' onClick=\"return confirm('". lang_show('are_you_sure')."');\">".lang_show('delete')."</a><br><br>";
		}
		if($type=="option")
		{
			if(!empty($_GET['catid'])&&is_array($_GET['catid'])&&in_array($v['catid'],$_GET['catid']))
				$str='selected';
			elseif(!empty($_GET['catid'])&&$_GET['catid']==$v['catid'])
				$str='selected';
			else
				$str=NULL;
			echo "<option value='".$v["catid"]."' $str >".$_SESSION['str'].$v["cat"]."</option>";
		}
		if($type=="list")
		{
			if($v['pid']==0)
				echo $_SESSION['str'].'<strong><a href="javascript:setvalue(\''.$v['catid'].'\',\''.$v['cat'].'\')">'.$v['cat']."</a></strong><br><br>";
			else
				echo $_SESSION['str'].'<a href="javascript:setvalue(\''.$v['catid'].'\',\''.$v['cat'].'\')">'.$v['cat']."</a><br><br>";
		}
		
		if(!empty($v['catid']))
		{
			$sql="select * from ".NEWSCAT." where pid='$v[catid]' order by nums asc ";
			$db->query($sql);
			if($db->num_rows())
			{
				list_all_cat($v['catid'],$type);
			}
		}
		$_SESSION['str']=substr($_SESSION['str'],0,strlen($_SESSION['str'])-3);
	}
}
unset($_SESSION['str']);

function get_catid($cid,$leav=0)
{
	$cid=get_catids($cid,$leav);
	return @str_replace(',,',',',implode(',',$cid));
}
function get_catids($cid,$leav=0)
{
	if(empty($cid))
		return NULL;
	global $db;
	if($leav==0)
		$sql="select catid from ".NEWSCAT." where catid in ($cid) ";
	else
		$sql="select catid from ".NEWSCAT." where pid in ($cid) ";
	$db->query($sql);
	$re=$db->getRows();
	foreach ($re as $v)
	{
		$cvid[]=$v['catid'];
		$tmparr=get_catids($v['catid'],1);
		if(count($tmparr))
		{
			foreach ($tmparr as $v)
			{
				$cvid[] = $tmprow;
				$cvid=array_merge($cvid,$tmparr);
			}
		}
	}
	if(count($re))
		return array_unique($cvid);
}
?>