<?php
	
	define("NEWS",$config['table_pre']."newscon");
	define("NEWSDETAIL",$config['table_pre']."news_detail");

	$db->query("delete from ".NEWSDATA);
	$db->query("delete from ".NEWSD);
	
	$sql="select * from ".NEWS." a left join ".NEWSDETAIL." b on a.newsid=b.id";
	$db->query($sql);
	$de=$db->getRows();

	foreach($de as $val)
	{
		if(!empty($val['pic']) and $val['pic']!='NULL')
		{
			$ispic=1;
			$source = $config['webroot']."/uploadfile/newsimg/sizea/".$val['pic'];
			$save_directory = $config['webroot']."/uploadfile/news";
			@copy($source, $save_directory."/big/".$val['pic']);
			@copy($source, $save_directory."/".$val['pic']);
		}
		else
		{
			$ispic=0;	
		}
		
		$con=$val['body'];
		$str = explode('<p>',$con);
		foreach($str as $i=>$k)
		{
			$v=trim(strip_tags($k));
			if(!empty($v))
			{
				$small = addslashes(trim(str_replace ("&nbsp;","",$v)));
				break;
			}
		}
		if(empty($val[subtitle]))
			$val[subtitle]=$val[title];
		$time=strtotime($val['uptime']);
		$sql="INSERT ".NEWSD."(classid,title,ftitle,keyboard,titleurl,isrec,istop,ispass,onclick,titlefont,uid,uptime,smalltext,writer,source,titlepic,ispic,isgid,ispl,userfen,lastedittime) VALUES ('$val[catid]','$val[subtitle]','$val[title]','$val[keywords]','','0','0','1','$val[readCount]','','0','$time','$small','$val[auther]','$val[source]','$val[pic]','$ispic','1','0','0','$time')";	
		
		$db->query($sql);
		$id=$db->lastid();
		$val['body']=addslashes($val['body']);
		$sql="INSERT INTO ".NEWSDATA." (nid,con) values ('$id','$val[body]')";
		$re=$db->query($sql);
	}
	
	
	
?>