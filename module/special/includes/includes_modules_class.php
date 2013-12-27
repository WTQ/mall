<?php
class includes_modules
{
	var $db;
	var $tpl;
	var $page;
	function includes_modules()
	{
		global $db,$tpl,$config;
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		$this -> tpl->template_dir = "$config[webroot]/module/$_GET[m]/special_template/special_modules_template/";
		$this -> tpl->assign("config",$config);
	}
	function get_modules()
	{
		$m[]='article';
		$m[]='news_list';
		$m[]='picture';
		$m[]='pro_list';
		$m[]='video_plyaer';
		$m[]='special_list';
		return $m;
	}
	function special_list($va)
	{
		global $config;
		$va=$this->stripsla(unserialize($va));
		$sql="select * from ".SPE." where 1 order by `order` asc limit 0,$va[nums]";//ר
		$this->db->query($sql);
		$va['list']=$this->db->getRows();
		$this->tpl->assign("de",$va);
		if(!empty($va['template']))
			return $this->tpl->fetch($va['template'],md5($va['title']));
	}
	function picture($va)
	{
		global $config;
		$va=$this->stripsla(unserialize($va));
		if(!empty($va['pic']))
			return "<img width='$va[width]' height='$va[height]' src='$config[weburl]/uploadfile/modules/$va[pic]' alt='$va[title]'>";
	}
	function news_list($va)
	{
		global $config;
		$va=$this->stripsla(unserialize($va));
		if($va['type']==1)
			$sql="select * from ".NEWSD." where ispass=1 order by nid desc limit 0,$va[nums]";
		if($va['type']==2)
			$sql="select * from ".NEWSD." where ispass=1 and tj=1 order by nid desc limit 0,$va[nums]";
		if($va['type']==3)
			$sql="select * from ".NEWSD." where ispass=1 and nid in ($va[nums]) order by nid desc";
		if($va['type']==4)
			$sql=$va['sql'];
		
		$this->db->query($sql);
		$va['list']=$this->db->getRows();
		foreach($va['list'] as $key=>$val)
		{
			if(empty($val['titleurl']))
				$va['list'][$key]['url']=$config["weburl"].'/?m=news&s=newsd&id='.$val['nid'];
			else
				$va['list'][$key]['url']=$val['titleurl'];
		}
		
		$this->tpl->assign("de",$va);
		if(!empty($va['template']))
			return $this->tpl->fetch($va['template'],md5($va['title']));
	}
	function pro_list($va)
	{
		$va=$this->stripsla(unserialize($va));
		if($va['type']==1)
			$sql="select * from ".PRO." where 1 order by id desc limit 0,$va[nums]";
		if($va['type']==2)
			$sql="select * from ".PRO." where 1 and statu=1 order by id desc limit 0,$va[nums]";
		if($va['type']==3)
			$sql="select * from ".PRO." where 1 and id in ($va[nums]) order by id desc";
		if($va['type']==4)
			$sql=$va['sql'];
		
		$this->db->query($sql);
		$va['list']=$this->db->getRows();
		
		$this->tpl->assign("de",$va);
		if(!empty($va['template']))
			return $this->tpl->fetch($va['template'],md5($va['title']));
	}
	
	function article($va)
	{
		$va=$this->stripsla(unserialize($va));
		$this->tpl->assign("de",$va);
		if(!empty($va['template']))
			return $this->tpl->fetch($va['template'],md5($va['title']));
	}
	
	function video_plyaer($va)
	{
		$va=$this->stripsla(unserialize($va));
		$va['video']='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" height="'.$va['height'].'" 
				width="'.$va['width'].'">
				<param name="movie" value="'.$va['videourl'].'" />
				<param name="quality" value="high" />
				<param name="allowScriptAccess" value="always" />
				<param name="allowFullScreen" value="true" />
				<embed src="'.$va['videourl'].'" allowScriptAccess="always" quality="high" 
				 type="application/x-shockwave-flash" allowfullscreen="true"
				 height="'.$va['height'].'" width="'.$va['width'].'" />
				</object>';
		$this->tpl->assign("de",$va);
		if(!empty($va['template']))
			return $this->tpl->fetch($va['template']);
	}
	function stripsla($filter)
	{
		if(is_array($filter))
		{
			foreach($filter as $key=>$v)
			{
				$filter[$key]=stripslashes($v);
			}
			return $filter;
		}
	}
}

?>