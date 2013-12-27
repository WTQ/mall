<?php
	if(empty($_GET['m'])||empty($_GET['s'])||empty($_GET['id']))
		die('forbiden;');

	$sql="select a.buyer_id,a.name,a.pic,b.userid,b.user,b.id,b.price from ".ORPRO." a left join ".PRO." b on a.pid=b.id where order_id='$_GET[id]'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("pro",$re);
	
	if($_POST['submit']=="submit")
	{
		$sql="select user,userid from ".ALLUSER." where userid='$_GET[uid]' ";
		$db->query($sql);
		$de=$db->fetchRow();
		
		foreach($re as $k=>$v)
		{
			$db->query("update ".ALLUSER." set buyerpoints=buyerpoints+". $_POST["g".$k]." where userid='".$v['buyer_id']."'");	
			
			$sql="INSERT ".PCOMMENT."(userid,user,fromid,pid,puid,pname,price,con,uptime,goodbad) VALUES ('$de[userid]','$de[user]','$v[buyer_id]','$v[id]','$v[userid]','$v[name]','$v[price]','".$_POST["comment_text".$k]."','".time()."','". $_POST["g".$k]."')";
			$db->query($sql);	
		
		}
		
		$db->query("update ".ORDER." set seller_comment=1 where order_id='".$_GET['id']."'");
		msg("main.php?m=product&s=admin_sellorder");
	}
	$tpl->assign("current","product");
	include_once("footer.php");
	//=============================================
	$out=tplfetch("ucomment.htm",NULL);
	
?>
