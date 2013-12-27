<?php
/**
 * Auther:Brad zhang
 * Powered by:B2Bbuilder
 * Copyright:http://www.b2b-builder.com
 * Des:database class
 */
class dba {
	
	var $Link_ID;
	var $Query_ID;
	var $Record;
	var $Row;
	var $Auto_free   = 0;
	var $sql_flag=NULL;
	var $memcache_obj=NULL;
	var $is_cache=NULL;
	var $cache_valid=FALSE;
	
	function dba($h,$u,$p,$db,$port=NULL)
	{
		$port=empty($port)?"3306":$port;
		
		$this->Link_ID=mysql_connect("$h:$port",$u,$p) or die("Can not connect mysql server."); 
		if($this->version() > '4.1')
		{
			$serverset = "character_set_connection='utf8', character_set_results='utf8', character_set_client=binary";
			$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
			$serverset && mysql_query("SET $serverset", $this->Link_ID);
			mysql_query("SET NAMES 'utf8'");
		}
		mysql_select_db($db,$this->Link_ID) or die("Can not select database");
	}
	
	function next_record()
	{
		$this->Record = @mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
		$this->Row += 1;
		$stat = is_array($this->Record);
		if (!$stat && $this->Auto_free)
		{
			mysql_free_result($this->Query_ID);
			$this->Query_ID = 0;
		}
		return $stat;
	}
	
	function fetchField($name)
	{
		if($this->Query_ID!="")
		{
			$re=mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
			return $re[$name];
		}
		else
			return NULL;
	}
	
	function num_rows()
	{
		if($this->Query_ID!="")
			return mysql_num_rows($this->Query_ID);
	}
	
	function f($Name)
	{
		return $this->Record[$Name];
	}
	
	function lastid()
	{
		return ($id = mysql_insert_id($this->Link_ID)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}
	
	function version()
	{
		if(empty($this->version))
		{
			$this->version = mysql_get_server_info($this->Link_ID);
			return $this->version;
		}
	}
	function insert($tbname, $row)
	{
		$sqlfield=NULL;$sqlvalue=NULL;
		foreach ($row as $key=>$value)
		{
			$sqlfield .='`'.$key."`,";
			$sqlvalue .= $value.",";
		}
		$sql = "INSERT INTO `".$tbname."`(".substr($sqlfield, 0, -1).") VALUES (".substr($sqlvalue, 0, -1).")";   
		return $this->query($sql);
	}
	function row_update($tbname, $row, $where)
	{
		$sqlud='';
		foreach ($row as $key=>$value) {
			$sqlud .= "`".$key."`= $value,";
		}
		$sql = "UPDATE `".$tbname."` SET ".substr($sqlud, 0, -1)." WHERE ".$where;
		return $this->query($sql);
	}
	function num_fields()
	{
		if($this->Query_ID!="")
			return mysql_num_fields($this->Query_ID);
	}
	function fetch_row()
	{
		if($this->Query_ID!="")
			return mysql_fetch_row($this->Query_ID);
	}
	function close()
	{
		mysql_close($this->Link_ID);
	}
	//---------------------------
	function fetchRow()
	{ 	
		if(!$this->is_cache)
		{
			$re=@mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
			if($this->cache_valid)
				memcache_set($this->memcache_obj, $this->sql_flag, $re,MEMCACHE_COMPRESSED);
			return $re;
		}
		else
		{
			return $this->is_cache;
		}
	}
	//----------------------------
	function query($sql)
	{	
		if($this->cache_valid)
		{
			if(empty($this->memcache_obj))
				$this->memcache_obj = memcache_connect('localhost', 11211);//做好连接
			$this->sql_flag=md5($sql);
			$this->is_cache=memcache_get($this->memcache_obj,$this->sql_flag);
			if(!$this->is_cache)
			{	
				//mysql_query("update test set num=num+1");
				$this->Query_ID=mysql_query($sql) or die($sql.mysql_error()) ;
				return $this->Query_ID;
			}
		}
		else
		{
			$this->Query_ID=mysql_query($sql) or die($sql.mysql_error()) ;
			return $this->Query_ID;
		}
	}
	//--------------------------
	function getRows()
	{ 
		
		if(!$this->is_cache)
		{	
			$re=array();
			if($this->Query_ID!="")
			{
				while($k=mysql_fetch_array($this->Query_ID,MYSQL_ASSOC))
				{
					$re[]=$k;
				}
			}
			if($this->cache_valid)
				memcache_set($this->memcache_obj, $this->sql_flag, $re,MEMCACHE_COMPRESSED);
			return $re;
		}
		else
			return $this->is_cache;
	}

}
?>