<?php
class home
{
	var $db;
	var $tpl;
	var $page;
	function home()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}


	function Member($uid)
	{	
		if(is_numeric($uid))
		{
			$sql="select userid,name,sex,area,logo,buyerpoints from ".ALLUSER." WHERE userid='$uid'";
			$this ->db ->query($sql);
			$member=$this ->db->fetchRow();
			if($member)
			{	
				$sql="select * from ".POINTS." order by id";
				$this ->db ->query($sql);
				$re=$this ->db->getRows();
				foreach($re as $k=>$v)
				{
					$ar=explode('|',$v['points']);
					if($member['buyerpoints']<=$ar[1] and $member['buyerpoints']>=$ar[0])
					{
						$member["buyerpointsimg"]=$v['img'];
					}
				}
				return $member;
			}
			else
			{	
				msg($config['weburl'].'/404.php');
			}
		}
		else
		{	
			msg($config['weburl'].'/404.php');
		}
	}
	function AllCount($uid)
	{
		$sql="select count(*) as count from ".READREC."  where tid='$uid' and type='3'";
		$this->db->query($sql);
		$count['v']=$this->db->fetchField('count');
		$sql="select count(*) as count from ".FRIEND."  where uid='$uid'";
		$this->db->query($sql);
		$count['g']=$this->db->fetchField('count');
		$sql="select count(*) as count from ".FRIEND."  where fuid='$uid'";
		$this->db->query($sql);
		$count['f']=$this->db->fetchField('count');
		return $count;
	}
	
	//访客
	function Visitors($uid)
	{
		$sql="select a.time,b.user,b.logo,b.userid from ".READREC." a , ".ALLUSER." b where a.userid=b.userid and a.tid='$uid' and a.type='3' limit 0,9";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	//分享的宝贝
	function ShareGoods($uid)
	{	
		$sql="select a.pid,image,pname,content,collectnum from ".SPRO." a left join ".SPROINFO." b on a.pid = b.pid where uid=$uid and isshare=1 order by a.addtime desc limit 0,3";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	//分享的宝贝
	function ShareGoodsList($uid)
	{
		global $config;
		$sql="select a.id,a.pid,a.commentcount,b.image,b.pname,b.price,b.collectnum,c.sell_amount from ".SPRO." a left join ".SPROINFO." b on a.pid = b.pid left join ".PRO." c on a.pid=c.id where a.uid=$uid and isshare=1 order by a.addtime desc";
		
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=16;
		
		if (!$page->__get('totalRows')){
			$this->db->query("select count(id) as num from ".SPRO." where uid=$uid and isshare=1");
			$num=$this->db->fetchRow();
			$page->totalRows = $num['num'];
		}
        $sql .= "  limit ".$page->firstRow.",16";
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	//新鲜事
	function Blog($uid,$limit=NULL)
	{
		if(!empty($limit))
		{
			$str=" limit 0, $limit"	;
		}
		$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.content as ocontent from ".SNS." a left join ".SNS." b on a.original_id= b.id where a.member_id ='$uid' order by a.create_time desc $str";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	
	function UserId()
	{
		$sql="select userid from ".ALLUSER." where user='".$_COOKIE['USER']."'";
		$this->db->query($sql);
		return $this->db->fetchField('userid');
	}
	
	function Friend($uid,$fuid)
	{
		$sql="select id from ".FRIEND." where uid='$uid' and fuid='$fuid'";
		$this->db->query($sql);
		return $this->db->fetchField('id');
	}
	
	function Fan($uid,$fuid)
	{
		$sql="select id from ".FRIEND." where fuid='$uid' and uid='$fuid'";
		$this->db->query($sql);
		return $this->db->fetchField('id');
	}
	
}
?>
