<?php
class sns
{
	var $db;
	var $tpl;
	var $page;
	function sns()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function add_sns($type='')
	{
		global $buid,$config;
		
		$sql="select logo,user from ".ALLUSER."  WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$content_str = '';
		
		$original_id = '0';
		$original_member_id = '0';
		
		$member_id = $buid;
		$member_name = $re['user'];
		$member_img = $re['logo']?$re['logo']:"image/default/user_admin/default_user_portrait.gif";
	
		$create_time  = time();
		$status  = '0';
		$privacy = 0;
		$comment_count  = 0;
		$copy_count  = 0;
	
		if($type=='shareshop')
		{

			$sql="select a.userid,a.logo,company,sellerpoints,shop_collect from ".USER." a left join ".ALLUSER." b on a.userid=b.userid  WHERE a.userid='$_POST[choosestoreid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			
			$sql="select * from ".POINTS." order by id";
			$this ->db ->query($sql);
			$se=$this ->db->getRows();
			foreach($se as $k=>$v)
			{
				$ar=explode('|',$v['points']);
				if($de['sellerpoints']<=$ar[1] and $de['sellerpoints']>=$ar[0])
				{
					$de["sellerpointsimg"]=$v['img'];
				}
			}
			
			$store_info['logo'] = $de['logo']?$de['logo']:"image/default/user_admin/default_logo.gif";
			$store_info['url'] = $config['weburl']."/shop.php?uid=".$de['userid'];
			$title = $_POST['comment']?$_POST['comment']:"这家店不错，分享给你！希望你也喜欢哦~";
			$content_str .= "<div class=\"fd-media\"><div class=\"fd-goods-wrap\"><div class=\"fd-container\"><div class=\"resize-box\">
							<div class=\"goodsimg\"><a target=\"_blank\" href=\"".$store_info['url']."\"><img height=\"160\" width=\"160\" src=\"".$store_info['logo']."\"></a></div>
							<div class=\"goodsinfo\">
								<dl>
									<dt>店铺名称：<a target=\"_blank\" href=\"".$store_info['url']."\">".$de['company']."</a></dt>
									<dd>店铺星级：<img  align=\"absmiddle\" title=\"".$de['sellerpoints']."个卖家信用积分\" src=\"".$config['weburl']."/image/points/".$de['sellerpointsimg']."\" /></dd>
									<dd class=\"hot-number\">收藏数(".$de['shop_collect'].")</dd>
									<dd><a class=\"fd-view-detail\" target=\"_blank\" href=\"".$store_info['url']."\">查看详情</a></dd>
								</dl>
							  </div>
						 </div></div></div></div>";
		}
		elseif($type=='sharegoods')
		{
			
			$sql="select pid,pname,image,price,collectnum from ".SPROINFO."  WHERE pid='$_POST[choosestoreid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			
			$store_info['logo'] = $de['image']?"".$de['image']:"image/default/nopic.gif";
			$store_info['url'] = $config['weburl']."/?m=product&s=detail&id=".$de['pid'];
			
			$content_str .= "<div class=\"fd-media\"><div class=\"fd-goods-wrap\"><div class=\"fd-container\"><div class=\"resize-box\">
							<div class=\"goodsimg\"><a target=\"_blank\" href=\"".$store_info['url']."\"><img height=\"160\" width=\"160\" src=\"".$store_info['logo']."\"></a></div>
							<div class=\"goodsinfo\">
								<dl>
									<dt><a target=\"_blank\" href=\"".$store_info['url']."\">".$de['pname']."</a></dt>
									<dd>价  格：<span class=\"rmb\">".$config['money']."</span><span class=\"current-price\">".$de['price']."</span></dd>
									<dd class=\"hot-number\">收藏数(".$de['collectnum'].")</dd>
									<dd><a class=\"fd-view-detail\" target=\"_blank\" href=\"".$store_info['url']."\">查看详情</a></dd>
								</dl>
							  </div>
						 </div></div></div></div>";
			$title = $_POST['comment']?$_POST['comment']:" 刚淘到个商品，很赞哦！分享给大家了！";
			
			$this->db->query("update ".SPRO." set isshare='1' where pid='".$_POST['choosestoreid']."'");	
		}
		elseif($type=='forward')
		{
			
			$sql="select content,member_id,member_name,title,original_id,original_member_id from ".SNS." WHERE id='$_POST[forwardid]'";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
			
			$original_id = $de['original_id']?$de['original_id']:$_POST['forwardid'];
			$original_member_id = $de['original_member_id']?$de['original_member_id']:$de['uid'];
			$content_str = '';
			$title = $_POST['forwardcontent']?$_POST['forwardcontent']." 转发":"转发";
			
			$this->db->query("update ".SNS." set copy_count=copy_count+1 where id='".$_POST['forwardid']."'");
		}
		else
		{
			$title = $_POST['content'];
		}
		
		$content = $content_str;
		$sql="insert into ".SNS." (original_id,original_member_id,original_status,member_id,member_name,member_img,title,content,create_time,status,privacy,comment_count,copy_count,original_comment_count,original_copy_count) VALUES ('$original_id','$original_member_id','0','$member_id','$member_name','$member_img','$title','$content','$create_time','$status ','$privacy','$comment_count','$copy_count','0','0')";
		$this->db->query($sql);
		
	}
	
	
	function del_sns($id)
	{
		$this->db->query("delete from ".SNS." where id='$id'");
		$this->db->query("update ".SNS." set original_status=1 where original_id='$id'");
	}
	
	function get_sns()
	{
		global $buid,$config;
		
		$sql="select fuid from ".FRIEND." where uid=$buid order by addtime desc";
		$this->db->query($sql);
		$re=$this->db->getRows();
		
		$myfriend=$buid;
		
		foreach($re as $val)
		{
			$myfriend.=','.$val['fuid'];
		}
		
		$sql="select a.* , b.member_id as ouid , b.member_name as ouser , b.title as otitle, b.content as ocontent from ".SNS." a left join ".SNS." b on a.original_id= b.id where a.member_id in ($myfriend) order by a.create_time desc";
		
		$str="";
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=10;
		
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
		
		if($_GET['page']-1>0)
			$p=$_GET['page']-1;
		else
			$p='0';
			
		$page->firstRow=$p*$page->listRows;
        $sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($re as $val)
		{
			if($val['original_id'])
			{
				$con="<div class=\"quote-wrap\">";
				if($val['original_status']==1)
				{
					$con.="原文已删除";
					
					$fd_forward="<span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_del\" href=\"javascript:void(0);\">删除</a></span>";
				}
				else
				{
					$con.=" <div class=\"fd-text\"><p class=\"fd-title\"><a target=\"_blank\" href=\"home.php?uid=".$val['ouid']."\">".$val['ouser']."</a> : <span>".$val['otitle']."</span></p></div>".$val['ocontent'];
					$fd_forward="<span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_forwardbtn\" href=\"javascript:void(0);\">转发</a></span><span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_del\" href=\"javascript:void(0);\">删除</a></span>";
				}
				$con.="</div>";
			}
			else
			{
				$con=$val['content'];
				$fd_forward="<span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_forwardbtn\" href=\"javascript:void(0);\">转发</a></span><span><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_del\" href=\"javascript:void(0);\">删除</a></span>";
			}
			$str.="<div class=\"fd-item\"><div class=\"fd-avatar\"><a target=\"_blank\" href=\"shop.php?uid=".$val['member_id']."\"><img width=\"60\" height=\"60\" src=\"".$val['member_img']."\" > </a></div><div class=\"fd-wrap\"><div class=\"fd-text\"><p class=\"fd-title\"><a target=\"_blank\" href=\"home.php?uid=".$val['member_id']."\">".$val['member_name']."</a> : <span>".$val['title']."</span></p></div>".$con."<div class=\"fd-extra\">
            <span class=\"fd-time\">".date('Y-m-d',$val['create_time'])."</span>
            <span class=\"fd-action\">".$fd_forward."</span>
        </div> </div>
</div>";//<div class=\"more-action\"><span><p><i></i><a data-param=\"{&quot;bid&quot;:&quot;".$val['id']."&quot;}\" nc_type=\"fd_del\" href=\"javascript:void(0);\">删除</a></p></span></div>
		}
		
		if(($_GET['page']+1)<= ceil($page->totalRows/$page->listRows))
		{
			$str.="<div id=more></div>";
		}
		return $str;
	}
}
?>
