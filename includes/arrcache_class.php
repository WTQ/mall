<?php
class ArrCache
{
	var $ob_length;//前面缓冲的数据量
	var $path;//缓存文件存放路径
	var $fileName;//缓存文件名
	var $contents;//缓存内容
	var $cached;//是否缓存
	var $ext;//缓存文件扩展名
	
	function ArrCache($path = '/cache', $ext = 'html')
	{
		ob_start();
		$this->ext = $ext;
		$this->path = $path;
	}
	function begin($parameter = array(), $time = 60)
	{
		$this->fileName = $this->fileName($parameter);
		if(file_exists($this->fileName)&& time() - filemtime($this->fileName) <= $time  )
		{
			echo file_get_contents($this->fileName);
			$this->cached = true;
			return true;
		}
		else
		{
			$this->cached = false;
			return false;
		}
	}
	function end($replace = array())
	{	
		$contents = ob_get_contents();
		if($this->cached==false&&strlen($contents)>0)
		{
			ob_end_clean();
			file_put_contents($this->fileName,$contents);
			echo $contents;
		}
	}
	
	function str_begin($parameter = array(), $time = 60)
	{
		$this->fileName = $this->fileName($parameter);
		if(file_exists($this->fileName) && time() - filemtime($this->fileName) <= $time)
		{
			$str=file_get_contents($this->fileName);
			$this->cached = true;
			return $str;
		}
		else
		{
			$this->cached = false;
			return NULL;
		}
		
	}
	function str_end($contents)
	{
		if(!$this->cached&&strlen($contents)>0)
		{
			$fp=@fopen($this->fileName, "w");
			@fwrite($fp, $contents);
			@fclose($fp);
		}
	}
	
	function fileName($parameter)
	{
		$array  = array();
		$array1 = array(' ', '+', '/', '\\', ':', '*', '?', '"', '<', '>', '|', '=', '-');
		$array2 = array('+0', '+1', '+2', '+3', '+4', '+5', '+6', '+7', '+8', '+9', '+a', '+b', '+c');
		
		if (is_array($parameter))
		{
			unset($parameter['userid']);
			foreach ($parameter as $key => $value)
			{
				$array[] = $key.'='.str_replace($array1, $array2, $value);
			}
		}
		$string = join($array, '');
		$file=end($fn=explode('/',$_SERVER['PHP_SELF']));
		$filename = $this->path.'/'.$file.$string.'.'.$this->ext;//die($this->path);
		return $filename;
	}
}
?>