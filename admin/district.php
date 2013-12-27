<?php
include_once("../includes/global.php");

//======================================
if($_POST['action']=="submit")
{
	if(is_array($_POST['newname']))
	{
		$inserts = array();
		foreach($_POST['newname'] as $key => $value)
		{
			$sort = trim($_POST['newsort'][$key]);
			if(trim($value) !== '')
			{
				$inserts[] = "('$value', '$_POST[id]', '$sort')";
			}
		}
		if($inserts) 
		{
			$sql="insert into ".DISTRICT."(name,pid,sorting) values ".implode(",",$inserts);
			$db->query($sql);
		}
	}
	if(is_array($_POST['name']))
	{
		foreach($_POST['name'] as $key => $value)
		{
			$sort = intval(trim($_POST['sort'][$key]));
			$upid = intval(trim($_POST['upid'][$key]));
			if(trim($value))
			{
				$sql="update ".DISTRICT." set name='$value',pid='$upid',sorting='$sort' where id='$key'";
				$db->query($sql);
			}
		}
	}
	if(!empty($_POST['chk']))
	{	
		$chk=implode(",",$_POST['chk']);
		$sql="delete from ".DISTRICT." where id in ($chk)";
		$db->query($sql);
	}
}
$id=empty($_GET['id'])?"0":$_GET['id'];
$sql="select name,id,sorting,pid from ".DISTRICT." where pid=$id order by sorting,id ";
$db->query($sql);
$de=$db->getRows();
if(empty($de) and $id==0)
{
	@set_time_limit(0);
	if($config['language']=='en')
	{
		
		$dir="../install/district_en/";
	}
	else
	{
		$dir="../install/district_cn/";
	}
	$files = scandir($dir);
	foreach($files as $val)
	{
		if($val!="." and $val!="..")
		{
			$fp=fopen($dir.$val,"r");
			$sql=fread($fp,filesize($dir.$val));
			fclose($fp);
			$ar=explode(";",$sql);
			foreach($ar as $k=>$ve)
			{
				if($k<(count($ar)-1))
				{
					$ve=str_replace("mallbuilder_",$config['table_pre'],$ve);
				
					$qre=$db->query($ve);
				}
			}
		}
	}
	admin_msg("district.php","数据导入成功");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead">省份城市管理</div>
  <div class="bigboxbody">
    <form action="" method="post">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="theader">
            <td width="30"></td>
            <td width="70">排序</td>
            <td width="70">ID</td>
            <td>名称</td>
            <td width="100">上级ID</td>
        	<td width="70">子类总数</td>
        </tr>
        <?php
			foreach($de as $key=>$val)
			{
				$sql="select count(*) as num from ".DISTRICT." where pid='$val[id]'";
				$db->query($sql);
				$count=$db->fetchField('num');
				echo "<tr>";
				echo "<td><input type='checkbox' name='chk[]' value='$val[id]' /> </td>";
				echo "<td><input type='text' name='sort[$val[id]]' class='text' style='width:50px' value='$val[sort]' /></td>";
				echo "<td>$val[id]</td>";
				echo "<td><input type='text' name='name[$val[id]]' class='text' value='$val[name]' /></td>";
				echo "<td><input type='text' name='upid[$val[id]]' class='text' style='width:60px' value='$val[pid]' /></td>";
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href='?id=$val[id]'>$count</a></td>";
				echo "</tr>";
			 }
		?>	
        <tr>
          <td>新增</td>
          <td><input name="newsort[]" type="text" value="0" class="text" style="width:50px;"></td>
          <td>&nbsp;</td>
          <td><input name="newname[]" type="text" class="text"></td>
          <td colspan="20"></td>
        </tr>
        <tr>
          <td colspan="20">
            <input type="hidden" value="submit" name="action" />
            <input type="hidden" value="<?php echo $id; ?>" name="id" />
            <input type="submit" class="btn" class="submit" value="提交" />
            <?php if($id){ ?>
            <input type="button" class="btn" value="返回上级" onClick="window.location='?id=<?php echo $re['pid']; ?>'" />
            <?php } ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
