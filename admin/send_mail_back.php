<?php
@set_time_limit(0);
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//============================================
if (empty($_GET["mailcontent"]))
{
	echo "mail content is null ! unknow error";
	exit;
}
else
	$mcontent=urldecode($_GET["mailcontent"]);
if (empty($_GET["mailtitle"]))
{ 
	echo "mail title is null ! unknow error";
	exit;
}
else
  $mtitle=urldecode($_GET["mailtitle"]);
if (!isset($_GET["limit"]))
{ 
	echo "limit is null ! unkonw error";
    exit;
}
else
	$limits=$_GET["limit"];
	
if (!empty($_GET["sqlw"]))
   $sqlw=str_replace("\\","",urldecode($_GET["sqlw"]));
else
   $sqlw=NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</HEAD>
<body>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
  <div class="bigboxbody">
    <?php
		$sql="select a.user,a.lastLoginTime,a.email,a.name as contact,b.company from ".ALLUSER." a left join ".USER." b on  
		a.userid=b.userid where a.email<>'' $sqlw order by a.userid desc limit ".$limits.",5";
		$limits=$limits+5;
	    $db->query($sql);
	    $re=$db->getRows();
        if($db->num_rows()>0)
        {
		    $ntime=time();           
            foreach($re as $v)
			{
				$mcontent=stripslashes($mcontent);
				$mcontent=str_replace('[username]',$v['user'],$mcontent);
				$mcontent=str_replace('[company]',$v['company'],$mcontent);
				$mcontent=str_replace('[lastlogintime]',date("Y-m-d H:i:s",$v['lastLoginTime']),$mcontent);
				$bday=ceil((time()-$v['lastLoginTime'])/86400);
				$mcontent=str_replace('[betweenday]',$bday,$mcontent);
				send_mail($v["email"],$v["contact"],$v["contact"].$mtitle,$mcontent);
				echo $v["contact"]."&nbsp;&nbsp; ".lang_show('mailsendto').$v["email"]."&nbsp;&nbsp; ".lang_show('sendok')."<br>";
				$i++;
			}
			 if($i==6)
				 echo "<script>window.location=\"send_mail_back.php?limit=".$limits."&sqlw=".urlencode($sqlw)."&mailcontent=".
				 urlencode($mcontent)."&mailtitle=".urlencode($mtitle)."\";</script>";
			 else
			 {
				 $t=$limits+$i;
				 echo lang_show('sendtotal').$t.lang_show('senddot');
				//操作记录入库
                 $sql="select a.user,a.lastLoginTime,a.email,a.name as contact,b.company from ".ALLUSER." a, ".USER." b where 
				 a.userid=b.userid and a.email<>'' $sqlw ";
                 $db->query($sql);
		         $re=$db->getRows();
		         $mrecord='';
	             foreach($re as $v)
	             {
                      $mrecord.=$v['contact']." TO ".$v['email'].",";
		         }
		         $sql="insert into ".MAILREC." (sendmailname,sendtime,sendmailrecord) values
				  ('".$_SESSION["ADMIN_USER"]."','".date("Y-m-d H:i:s")."','".$mrecord."')";
                 $db->query($sql);
			}
		 }
		 else
		 {
             $sql="select count(*) as totalsend from ".ALLUSER." a, ".USER." b where a.userid=b.userid and a.email<>'' $sqlw ";
             $db->query($sql);
			 $re=$db->fetchRow();
			 echo lang_show('sendtotal').$re['totalsend'].lang_show('senddot');
			  //操作记录入库
             $sql="select a.user,a.lastLoginTime,a.email,a.name as contact,b.company from ".ALLUSER." a, ".USER." b where
			  a.userid=b.userid and a.email<>'' $sqlw ";
             $db->query($sql);
		     $re=$db->getRows();
		     $mrecord='';
	         foreach($re as $v)
	         {
                $mrecord.=$v['contact']." TO ".$v['email'].",";
		     }
		     $sql="insert into ".MAILREC." (sendmailname,sendtime,sendmailrecord) values
			  ('".$_SESSION["ADMIN_USER"]."','".date("Y-m-d H:i:s")."','".$mrecord."')";
              $db->query($sql);
		 }
  ?>
  </div>
</div>
</body>
</html>