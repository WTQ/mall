<?php
class msg
{
	var $db;
	var $tpl;
	var $page;
	function msg()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	//取得用户的邮件总数,返回二维数组，$re[0][0]为未读邮件，$re[1][0]为已经读邮件
	function getMailNum()
	{
		global $db,$buid;
		$sql="SELECT count(*) as num,iflook  FROM ".FEEDBACK." where touserid='$buid' and (msgtype='1' or msgtype='3') group by iflook order by iflook desc";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			if(!$v['iflook'])
				$type='new';
			if($v['iflook']=='1')
				$type='read';
			if($v['iflook']=='2')
				$type='del';
			if($v['iflook']=='3')
				$type='del';
			$ms[$type]=$v['num'];
		}
		if(empty($ms['new']))
			$ms['new']=0;
		if(empty($ms['read']))
			$ms['read']=0;
		return $ms;
	}
	//iflook 0=new,1=look,2=del,3=replay
	//contype 1=pro,2=info,3=compamy
	function mail_list($type='inbox')
	{
		global $buid;;
		if($type=='inbox')
			$sql="SELECT * FROM ".FEEDBACK." WHERE touserid='$buid' and (msgtype=1 or msgtype=3) and (iflook is null or iflook='1' or iflook='3') ORDER BY date DESC";
		if($type=='outbox')
			$sql="SELECT * FROM ".FEEDBACK." WHERE fromuserid='$buid' and msgtype=2 and (iflook is null or iflook='1') ORDER BY date DESC";
		if($type=='savebox')
			$sql="SELECT * FROM ".FEEDBACK." WHERE touserid='$buid' and is_save='1' ORDER BY date DESC";
		if($type=='delbox')
			$sql="SELECT * FROM ".FEEDBACK." WHERE touserid='$buid' and iflook='2' ORDER BY date DESC";
		//=====================
	  	$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",20";
		//=====================
		$this->db->query($sql);
		$list=$this->db->getRows();
		foreach($list as $key=>$v)
		{    
		   if($v["fromuserid"]==$buid&&!empty($v['touserid']))
		   {	
			   $sql="select name,user from ".ALLUSER." where userid=".$v['touserid'];
			   $this->db->query($sql);
			   $fc=$this->db->fetchRow();
			   $v["fromname"]=$fc['user'];
			   if($fc['name'])
			   	 $v["fromname"].="[$fc[name]]";
		   }
		   if($v["fromuserid"]!=$buid&&!empty($v['fromuserid']))
		   {
			   $sql="select name,user from ".ALLUSER." where userid=".$v['fromuserid'];
			   $this->db->query($sql);
			   $fc=$this->db->fetchRow();
			   $v["fromname"]=$fc['user'];
			   if($fc['name'])
			   	 $v["fromname"].="[$fc[name]]";
		   }
		   $list[$key]=$v;
		}
		$re["page"]=$page->prompt();
		$re["list"]=$list;
		return $re;
	}
	function remove_mail($id=NULL)
	{	
		global $buid,$admin;
		if(isset($_POST["deid"])&&!empty($_POST['remove']))
		{
			for($i=0;$i<count($_POST["deid"]);$i++)
			{
				$id=$_POST["deid"][$i];
				$sql="delete from ".FEEDBACK." where id='$id' and (touserid='$buid' or fromuserid='$buid')";
				$this->db->query($sql);
			}
		}
		if(!empty($id))
		{
			$sql="delete from ".FEEDBACK." where id='$id' and (touserid='$buid' or fromuserid='$buid')";
			$this->db->query($sql);
			$admin->msg("main.php?m=message&s=admin_message_list_delbox");
		}
	}
	//恢复邮件
	function recover_mail($id=NULL)
	{
		global $admin;
		if(isset($_POST["deid"])&&!empty($_POST['recover']))
		{
			for($i=0;$i<count($_POST["deid"]);$i++)
			{
				$id=$_POST["deid"][$i];
				$sql="update  ".FEEDBACK." set iflook=1 where id=$id";
				$this->db->query($sql);
				unset($sql);
			}
		}
		if(!empty($id))
		{
			$sql="update  ".FEEDBACK." set iflook=1 where id=$id";
			$this->db->query($sql);
			$admin->msg("main.php?m=message&s=admin_message_list_inbox");
		}
	}
	//删除邮件功能
	function del_mail($id=NULL)
	{
		if(empty($id))
		{
			for($i=0;$i<count($_POST["deid"]);$i++)
			{
				$id=$_POST["deid"][$i];
				$sql="update  ".FEEDBACK." set iflook=2 where id=$id";
				$this->db->query($sql);
				unset($sql);
			}
		}
		else
		{
			$sql="update  ".FEEDBACK." set iflook=2 where id=$id";
			$this->db->query($sql);
		}
	}
	//=======================================保存邮件功能
	function save_mail($save_id=NULL)
	{
		global $admin;
		if(isset($_POST["deid"])&&!empty($_POST['save']))
		{
			for($i=0;$i<count($_POST["deid"]);$i++)
			{
				$id=$_POST["deid"][$i];
				$sql="update  ".FEEDBACK." set is_save=1 where id='$id'";
				$this->db->query($sql);
				unset($sql);
			}
			$admin->msg('main.php?m=message&s=admin_message_list_savebox');
		}
		if(!empty($save_id))
		{
			$sql="update  ".FEEDBACK." set is_save=1 where id='$save_id'";
			$this->db->query($sql);
			$admin->msg('main.php?m=message&s=admin_message_list_savebox');
		}
	}
	//========================================邮件详情
	function mail_det($id)
	{
		global $buid;
		$sql="select *,NULL as about from ".FEEDBACK." where id='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if($re['iflook']<1)
		{
			$sql="update ".FEEDBACK." SET iflook=1  WHERE id='$id'";
			$this->db->query($sql);
		}
		
		if($re["fromuserid"]&&$re['msgtype']==1)
		{//收件箱
			$sql="select * from ".ALLUSER." where userid='".$re['fromuserid']."'";
			$this->db->query($sql);
			$re["fromInfo"]=$this->db->fetchRow();
		}
		if($re["touserid"]&&$re['msgtype']==2)
		{//发件箱
			$sql="select * from ".ALLUSER." where userid='".$re['touserid']."'";
			$this->db->query($sql);
			$re["fromInfo"]=$this->db->fetchRow();
		}
		if($re['contype']==1&&$re['tid'])
		{
			if(substr($re['tid'],strlen($re['tid'])-1,1)==',')
				$re['tid']=$re['tid']."0";
			$sql="select * from ".PRO." where id in ( $re[tid] ) and userid='$buid'";
			$this->db->query($sql);
			$pro=$this->db->getRows();
			foreach($pro as $v)
			{
				$re["about"].="<a href='?m=product&s=detail&id=$v[id]' target='_blank'>
				<img width=50 height=40 src='uploadfile/comimg/small/$v[id].jpg'><br>$v[pname]</a>";
			}
		}
		if($re['contype']==2&&$re['tid'])
		{
			$sql="select * from ".PRO." where id='$re[tid]' and userid='$buid'";
			$this->db->query($sql);
			$pro=$this->db->fetchRow();
			$re["about"]="<a href='?m=product&s=detail&id=$pro[id]' target='_blank'>$pro[title]</a>";
		}
		$re['edit_con']='<br><br><br><br><br>//======================================================='.$re['con'];
		return $re;
	}
	
	//发送电子邮件给客户
	function send_email_for_inquery($ar=array())
	{	
		global $config;
		if(!empty($ar))
		{
			$sql="select user,email from ".ALLUSER." where userid='$ar[toid]'";
			$this->db->query($sql);
			$user=$this->db->fetchRow();
			if(!empty($user['email']))
			{
				$mail_temp=get_mail_template('sendmsg');
				$con=$mail_temp['message'];
				$subject=$mail_temp['subject'];
				$con=str_replace("[username]",$user["user"],$con);
				$con=str_replace("[content]",$ar["con"],$con);
				send_mail($user["email"],$user["user"],$ar["sub"],$con);
				unset($con);
			}
		}
	}
	//-------------会员中心发邮件---------------------------
	function friend_msg_batch_send()
	{	
		global $buid,$admin;
		if(!empty($_POST['senduser'])&&!empty($_POST['msgcon']))
		{
			$date=date("Y-m-d H:i:s");
			$sear=explode(';',$_POST['senduser']);
			$sear1=array_unique($sear);
			$sear1=implode("','",$sear1);
			$sql="select user,email,userid from ".ALLUSER." where user in ('".$sear1."0')";
			$this->db->query($sql);
			$re=$this->db->getRows();
			foreach($re as $v)
			{
				$sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES 
				('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','1')";
				$this->db->query($sql);
						
				$sql="insert into ".FEEDBACK." (touserid,fromuserid,fromInfo,sub,con,date,msgtype) VALUES 
				('$v[userid]','$buid','Business Friends Message','$_POST[msgtitle]','$_POST[msgcon]','$date','2')";
				$this->db->query($sql);
				//-----------------如果是回复邮件标记为已回复
				$this->db->query("UPDATE ".FEEDBACK." set iflook='3' where id='$_GET[id]'");
				
				if(!empty($_POST['semail'])&&$v["email"])
				{
					send_mail($v["email"],$v["name"],$_POST['msgtitle'],$_POST['msgcon']);
				}
			}
			$admin->msg("main.php?m=message&s=admin_message_list_inbox");//发送成功
		}
		else
			$admin->msg("main.php?m=message&s=admin_message_sed&msgsend=error");
	}

}
?>
