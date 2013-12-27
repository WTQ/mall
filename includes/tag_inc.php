<?php
/**
 *　删除文章时处理标签
 */
function del_tag($tid,$type)
{
	global $db;
	//将ＴＡＧ表总数减一
	$sql="update ".TAGS." set total=total-1 where tagname in (select tagname from ".CTAGS." where tid='$tid' and type='$type')";
	$db->query($sql);
	//从关系表中删除
	$sql="delete from ".CTAGS." where tid='$tid' and type='$type'";
	$db->query($sql);
}
/**
 *获取某个内容的tag
 */
function get_tags($tid,$type)
{
	global $db;
	$re=array();
	$sql="select * from ".CTAGS." where tid='$tid' and type='$type'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		$tag[]=$v['tagname'];
	}
	if(isset($tag))
	{
		$str=implode(",",$tag);
		return $str;
	}
}
/**
 * $type 1,产品，2产品,3咨询,4展会,5视频,6插件工程
 * 
 */
function add_tags($tags,$type,$tid)
{
	global $db;
	$tags=str_replace("，",",",$tags);
	$tagarray = array_unique(explode(',', $tags));
	$tagcount = 0;
	foreach($tagarray as $v)
	{
		$v = trim($v);
		if(!empty($v))
		{
			$ky=csubstr($v,0,20,NULL);
			$db->query("SELECT closed FROM ".TAGS." WHERE tagname='$v'");
			if($db->num_rows())
			{	//如果标签已存在，热度即加一
				$db->query("UPDATE ".TAGS." SET total=total+1 WHERE tagname='$v'");
				$sql="select * from ".CTAGS." where tagname='$v' and tid='$tid' and type='$type'";
				$db->query($sql);
				if(!$db->num_rows())
					$tagstatus = 0;//关连不存在，关连表即将加入关系
				else
					$tagstatus = 1;	
			}
			else
			{
				$db->query("INSERT INTO ".TAGS." (tagname, closed, total,statu) VALUES ('$ky', 0, 1,0)");
				$tagstatus = 0;//关连不存在，关连表即将加入关系
			}
			if(!$tagstatus)
			{
				$db->query("INSERT ".CTAGS." (tagname, tid,`type`) VALUES ('$ky', $tid,'$type')");
			}
			$tagcount++;
			if($tagcount > 5)
			{
				unset($tagarray);
				break;
			}
		}
	}
}
/**
 *编辑时用到的TAGS
 */
function edit_tags($tags,$type,$tid)
{

	global $db;
	//清空整理标签
	//$threadtagary库中已存在的标签
	$sql="select tagname from ".CTAGS." where tid='$tid' and type='$type' ";
	$db->query($sql);
	$threadtagary=$db->getRows();
	$old_tags=array();
	foreach($threadtagary as $v)
	{
		$old_tags[]=$v['tagname'];
	}
	$tags=str_replace("，",",",$tags);
	$tagarray = array_unique(explode(',', $tags));
	$tagcount = 0;
	foreach($tagarray as $v)
	{
		$v = addslashes(trim($v));	
		
		if(!empty($v)&&!in_array($v, $old_tags))
		{
			$db->query("SELECT closed FROM ".TAGS." WHERE tagname='$v'");
			if($db->num_rows())
			{	//如果标签已存在，热度即加一
				$db->query("UPDATE ".TAGS." SET total=total+1 WHERE tagname='$v'");
				$sql="select * from ".CTAGS." where tagname='$v' and tid='$tid' and type='$type'";
				$db->query($sql);
				if(!$db->num_rows())
					$tagstatus = 0;//关连不存在，关连表即将加入关系
				else
					$tagstatus = 1;	
			}
			else
			{
				$db->query("INSERT INTO ".TAGS." (tagname, closed, total,statu) VALUES ('$v', 0, 1,0)");
				$tagstatus = 0;//关连不存在，关连表即将加入关系
			}
			if(!$tagstatus)
			{
				$db->query("INSERT ".CTAGS." (tagname, tid,`type`) VALUES ('$v', $tid,'$type')");
			}
			$tagcount++;
			if($tagcount > 5)
			{
				unset($tagarray);
				break;
			}
		}
	}
	
	
	foreach($old_tags as $tagname)
	{
		if(!in_array($tagname, $tagarray))
		{	//如果库中的老标其它贴子在用就减一，如果没其它贴子在用就删除，最后再清空一下．
			$tagname=addslashes($tagname);
			$db->query("SELECT count(*) as num FROM ".CTAGS." WHERE tagname='$tagname' AND tid!='$tid'");
			if($db->num_rows()) 
				$db->query("UPDATE ".TAGS." SET total=total-1 WHERE tagname='$tagname'");
			else
				$db->query("DELETE FROM ".TAGS." WHERE tagname='$tagname'");
			$db->query("DELETE FROM ".CTAGS." WHERE tagname='$tagname' AND tid='$tid' AND `type`='$type'");
		}
	}
}
?>