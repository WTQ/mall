<?php
//==========================================
if($_POST["step"]=="del")
{
	$db->query("select catid,pic from ".PRO." where id='$_POST[deid]'");
	$re=$db->fetchRow();
	
	if($re['pic']!=''){
		$pic = explode(',',$re['pic']);
		foreach($pic as $i){
			@unlink($config['webroot'].'/uploadfile/product/'."$i");
			@unlink($config['webroot'].'/uploadfile/product/'.substr($i,0,strrpos($i,'/')+1).'big_'.substr($i,strrpos($i,'/')+1));
		}
	}

	$db->query("delete from ".PRO." where id='$_POST[deid]'");
	$db->query("delete from ".PRODETAIL." where proid='$_POST[deid]'");
	echo 1;//ajax删除成功
	die;
}
if(!empty($_GET["submit"])&&$_GET["submit"]==lang_show('delete'))
{
	if(isset($_GET["de"]) && is_array($_GET["de"])&&$_GET['statu']=='-1')
	{

		foreach($_GET['de'] as $v)
		{
			$db->query("select catid,pic from ".PRO." where id='$v'");
			$re=$db->fetchRow();
			
			if($re['pic']!=''){
				$pic = explode(',',$re['pic']);
				foreach($pic as $i){
					@unlink($config['webroot'].'/uploadfile/product/'."$i");
					@unlink($config['webroot'].'/uploadfile/product/'.substr($i,0,strrpos($i,'/')+1).'big_'.substr($i,strrpos($i,'/')+1));
				}
			}
		}
		$id=implode(",",$_GET["de"]);
		$sql="delete from ".PRO." where id in ($id)";
		$db->query($sql);
		$db->query("delete from ".PRODETAIL." where proid in($id)");
	}
	else
	{
		$id=implode(",",$_GET["de"]);
		$sql="update ".PRO." set statu='-1'  where id in ($id)";
	}
}
$sql="select *from ".PCAT." where catid<9999 order by nums asc";
$db->query($sql);
$proCatList=$db->getRows();
$_GET['statu']=isset($_GET['statu'])?$_GET['statu']:1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../script/my_lightbox.js"></script>
<script type="text/javascript" src="main.js"></script>
<script type="text/javascript" src="../script/Calendar.js"></script>
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"> <span class="cbox <?php if($_GET['statu']==1){?>on<?php } ?>"><a href="module.php?m=product&s=prolist.php&statu=1">在售产品</a></span> <span class="cbox <?php if($_GET['statu']=='-2'){?>on<?php } ?>"><a href="module.php?m=product&s=prolist.php&statu=-2">下架产品</a></span> <span class="cbox <?php if($_GET['statu']==0){?>on<?php } ?>"><a href="module.php?m=product&s=prolist.php&statu=0">待审核</a></span> <span class="cbox <?php if($_GET['statu']=='-1'){?>on<?php } ?>"><a href="module.php?m=product&s=prolist.php&statu=-1">回收站</a></span> </div>
  <div class="bigboxbody">
    <form method="get" action="">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%">所属分类</td>
          <td colspan="2">
		  <select class="select" name="typeid">
              <option value="">所有分类</option>
         <?php
		function list_downcat($catid)
		{
			global $db;
			$re=array();
			$sql="select * from ".PCAT." WHERE catid<9999 $ssql order by nums asc,char_index asc";
			$db->query($sql);
			$re=$db->getRows();
			foreach($re as $key=>$v)
			{
				if($v['catid']==$catid)
					echo "<option value='".$v["catid"]."' selected><strong>| _".$v["cat"]."</strong></option>";
				else
					echo "<option value='".$v["catid"]."'><strong>| _".$v["cat"]."</strong></option>";
				$s=$v['catid']."00";
				$b=$v['catid']."99";
				$sql="select * from ".PCAT." where  catid>$s and catid<$b $ssql order by nums asc,char_index asc";
				$db->query($sql);
				$sre=$db->getRows();
				foreach($sre as $skey=>$sv)
				{
					if($sv['catid']==$catid)
						  echo "<option value='".$sv["catid"]."' selected>| _ | _".$sv["cat"]."</option>";
					  else
						  echo "<option value='".$sv["catid"]."'>| _ | _".$sv["cat"]."</option>";
					$s=$sv["catid"]."00";
					$b=$sv["catid"]."99";
					$sql="select * from ".PCAT." where catid>$s and catid<$b $ssql order by nums asc,char_index asc";
					$db->query($sql);
					$srea=$db->getRows();
					foreach($srea as $sskey=>$svv)
					{
						 if($svv['catid']==$catid)
							  echo "<option value='".$sv["catid"]."' selected>| _ | _ | _".$sv["cat"]."</option>";
						  else
							  echo "<option value='".$sv["catid"]."'>| _ | _ | _".$sv["cat"]."</option>";
					}
				}
			}	
		}
	  	 list_downcat($_GET['typeid']);
      ?>
      </select>
          </td>
        </tr>
        <tr>
          <td width="10%"><?php echo lang_show('keyword');?></td>
          <td colspan="2"><input  type="hidden" name="statu" value="<?php  echo $_GET['statu'];?>"/>
            <?php
            $type=array(
                '1'=>lang_show('product_name'),
                '2'=>lang_show('keyword'),
                '3'=>lang_show('supplier')
            );
        ?>
            <select class="lselect" name="type">
              <?php
			  foreach($type as $key=>$v)
			  {
			  ?>
					  <option value="<?php echo $key;?>" <?php if(!empty($_GET['type'])&&$_GET['type']==$key)echo "selected";?>> <?php echo $v;?> </option>
					  <?php
			   } 
			  ?>
            </select>
            --
            <input class="rtext" name="key" type="text" value="<?php if(isset($_GET["key"]))echo $_GET["key"];?>" size="35" />
            <input name="m" type="hidden" value="product" />
            <input name="s" type="hidden" value="prolist.php" /></td>
        </tr>
        <tr>
          <td width="10%">更新时间</td>
          <td width="29%">
		  <script language="javascript">
			 var c = new Calendar("c"); 
			 document.write(c);
			</script>
            <input class="ltext" type="text"  onfocus="c.show(this)" name="s_time" value="<?php  echo $_GET['s_time'];?>"/>
            --
            <input class="rtext" name="e_time" type="text" onfocus="c.show(this)" value="<?php  echo $_GET['e_time'];?>"/>
          </td>
          <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>"></td>
        </tr>
      </table>
    </form>
    <form action="" method="get">
      <input name="m" type="hidden" value="product">
      <input name="s" type="hidden" value="prolist.php">
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr class="theader">
          <td width="26" ><?php
			if(!empty($_GET['firstRow']))
			{
			?>
            <input name="firstRow" type="hidden" value="<?php echo $_GET['firstRow'];?>">
            <?php
			}
			if(!empty($_GET['firstRow']))
			{
			?>
            <input name="totalRows" type="hidden" value="<?php echo $_GET['totalRows'];?>">
            <?php } ?>
            <input onClick="do_select()" type="checkbox" class="checkbox" name="checkbox" value="checkbox">
          </td>
          <td width="50"></td>
          <td width="*"><?php echo lang_show('product_name');?> </td>
          <td width="100"><?php echo lang_show('tcatid');?></td>
          <td width="150"><?php echo lang_show('supplier');?> </td>
          <td width="123" align="center"><?php echo lang_show('update_date');?></td>
          <td width="64" align="center"><?php echo lang_show('jingjia_ranking');?></td>
          <td width="50" align="center"><?php echo lang_show('recommend');?></td>
          <td width="50" align="center"><?php echo lang_show('manager');?></td>
        </tr>
        <?php
	$psql=NULL;
	if(!empty($_GET["key"]))
	{
		$_GET["key"]=trim($_GET["key"]);
		if($_GET['type']==1)
			$psql=" and a.pname like '%$_GET[key]%' ";
		elseif($_GET['type']==2)
			$psql=" and b.keywords='$_GET[key]' ";
		else
			$psql=" AND b.company like '%$_GET[key]%' ";
	}
	if(isset($_GET['typeid'])&&$_GET['typeid']!='')
		$psql.=" AND a.catid like '$_GET[typeid]%' ";
	if(isset($_GET['statu'])&&$_GET['statu']!='')
	{
		if($_GET['statu']==1)
			$psql.=" AND a.statu>=$_GET[statu] ";
		else
			$psql.=" AND a.statu=$_GET[statu] ";
	}
	if(isset($_GET["order_price"]))
		$psql.=" AND a.rank>0";
	
	if(!empty($_SESSION["province"]))
		$psql.=" and b.province='$_SESSION[province]'";
	if(!empty($_SESSION["city"]))
		$psql.=" and b.city='$_SESSION[city]'";
	 
	if(!empty($_GET['s_time']))
	    $psql.=" and a.uptime>='$_GET[s_time]'";
	if(!empty($_GET['e_time']))
		$psql.=" and a.uptime<='$_GET[e_time]'";
		
	$sql="SELECT  a.*,b.company FROM ".PRO." a  left join ".USER." b on a.userid=b.userid
		  WHERE 1 $psql order by a.uptime desc";
	//====================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$pages = $page->prompt();
	//=====================
	$db->query($sql);
	$re=$db->getRows();
	$coun_num=$db->num_rows();
	for($i=0;$i<$coun_num;$i++)
	{
		$pic=explode(",",$re[$i]['pic']);
	?>
        <tr id="plist_<?php echo $re[$i]['id']; ?>" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td><input name="de[]" type="checkbox" class="checkbox" id="de" value="<?php echo $re[$i]['id']; ?>" /></td>
          <td><?php
			
				echo "<img height=50 src='".$re[$i]['pic']."' >";
		  ?></td>
          <td><b><?php echo $config['money'].$re[$i]["price"]; ?></b><br /><a href="<?php echo $config['weburl'];?>/?m=product&s=detail&id=<?php echo $re[$i]['id'];?>" target="_blank"><?php echo $re[$i]["pname"];?></a></td>
          <td align="left"><?php
		   	$sql="select cat from ".PCAT." where catid='".$re[$i]['catid']."'";
			$db->query($sql);
			$proCat = $db->fetchRow();
			echo $proCat['cat'];
			?>
          </td>
          <td><?php echo $re[$i]["company"]; ?></td>
          <td align="center" ><?php echo $re[$i]["uptime"]; ?></td>
          <td align="center" ><?php echo $re[$i]["rank"]; ?></td>
          <td align="center" ><?php 
			if(readauditing($re[$i]['id'],'p'))
			{
			?>
            <img onmouseover="document.getElementById('note<? echo $re[$i]['id']; ?>').style.display='block'" onmouseout="document.getElementById('note<? echo $re[$i]['id']; ?>').style.display='none'" src="../image/admin/note.gif" align="absmiddle" />
            <div class="note" id="note<? echo $re[$i]['id']; ?>" style="display:none"> <?php echo readauditing($re[$i]['id'],'p'); ?> </div>
            <?php 
           		 } 
            ?>
            <a href="javascript:alertWin('<?php echo lang_show('pass');?>','',355,232,'auditing.php?t=p&id=<?php echo $re[$i]['id']; ?>&statu=<?php echo $re[$i]['statu']; ?>');">
            <?php $status=array('-1'=>lang_show('npass'),'0'=>lang_show('wpass'),'1'=>lang_show('pass'),'2'=>lang_show('rc')); $key=$re[$i]['statu'];echo $status[$key]; ?>
            </a> </td>
          <td align="center"><?php  if($re[$i]["userid"]!=''){?>
            <a href="?id=<?php echo $re[$i]["id"]."&".implode('&',convert($_GET)).'&m=product&s=cpmod.php'; ?>"><?php echo $editimg;?></a>
            <?php }else{ ?>
            <a href="<?php echo $config['weburl'];?>/admin/module.php?edit=<?php echo $re[$i]["id"]."&".implode('&',convert($_GET));?>&m=product&s=add_product.php"   title="<?php echo lang_show('edit');?>"><?php echo $editimg;?></a>
            <?php } ?>
            <a href="javascript:del_pro('<?php echo $re[$i]["id"];?>');"><?php echo $delimg;?></a> </td>
          <?php 
    	}
		?>
        </tr>
        <tr>
          <td colspan="3" align="left">
		  <input class="btn" type="submit" name="submit" value="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');">
                  <input class="btn" type="button" name="submit" value="<?php echo lang_show('audit');?>" onClick="alertWin('<?php echo lang_show('audit');?>','',355,232,'<?php echo $config['weburl']?>/admin/auditing.php?t=p&id=&statu=<?php echo "&".implode('&',convert($_GET)); ?>');">
		  <td style="text-align:right" colspan="6">
		  <div class="page"><?php echo $pages?></div>
		  </td>
    </form>
  </div>
</div>
</body>
</html>
<script>
function del_pro(id)
{
	if(confirm('<?php echo lang_show('are_you_sure');?>'))
	{
		    var url = 'module.php?m=product&s=prolist.php';
            var sj = new Date();
            var pars = 'shuiji=' + sj+"&step=del&deid="+id;
            $.post(url, pars,
            function (originalRequest)
				{
					if(originalRequest==1)
						$('#plist_'+id).remove();
				}
			);
	}
}
</script>