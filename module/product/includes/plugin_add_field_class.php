<?php
class AddField
{
	var $db;
	var $tpl;
	var $page;
	var $module;
	
	function AddField($module)
	{
		global $db;
		global $config;
		$this -> db     = & $db;
		$this->module=$module;
	}
	
	//$id:要编辑的ID（为0时表示增加）,$catid:产品类别ID列表, $table_name扩展表名
	function addfieldinput($id,$table_name,$frant=NULL)
	{
		global $config;$list = array();
		if(empty($table_name))
			return;
			
		if($id*1>"0")
		{
			$this->db->query("SHOW TABLES LIKE '".$table_name."'");
			if($this->db->num_rows()==1)
			{
				$sql="select * from $table_name where info_id='$id' and info_type='".$this->module."'";
				$this->db->query($sql);
				$de=$this->db->fetchRow();
			}
			else
				return;
		}
		$tn=explode('_',$table_name);
		$catid=$tn[2];
		$sql = "select * from ".PROPERTYVALUE." where property_id ='$catid' and statu=1";
		
		$this->db->query($sql);
		$res=$this->db->getRows();
		$j=1;
		foreach($res as $v)
		{
			$t=array();
			$t['j']=$j;
			$t["fieldName"]=$v["field"];//字段名称
			$t["fieldDisplay"]=$v["field_name"];//字段描述
			$t["color_img"]=$de["color_img"];//
			$t["is_buy_item"]=$v["is_buy_item"];//
			if($id*1>"0"&&!empty($de[$v['field']]))
				$t["defaultValue"]=$de[$v['field']];//编辑状态
			else
				$t["defaultValue"]=$v['default_value'];//默认值
			$t["displayType"]=$v["display_type"];//显示类型
			$t["catItem"]=$v["item"];//列表值
			
			if($frant)
			{
				if($t['is_buy_item']==1)
				{
					$list['d'][]=$this->front_li($t);
					$j++;
				}
				else
					$list['s'][]=$this->front_li($t);
			}
			else
			{
				if($v['is_buy_item']==1)
					$list['d'][]=$this->addtabletr1($t);
				else
					$list['s'][]=$this->addtabletr($t);
			}
		}
		return $list;
	}
	
	function front_li($ob)
	{
		$type = $ob["displayType"];
		$htmlstr="";
		$returnstr ="<dl id='$ob[fieldName]'><dt>".$ob["fieldDisplay"].":</dt>";
		$value=$ob["defaultValue"];
		$nc_type='';
		switch($type)
		{
			case "1":
				$htmlstr="$value";		
				break;
			case "2":
				$htmlstr="$value";
				break;
			case "3"://下拉框
				$numarray = explode(",",$ob["catItem"]);
				for($ii=0;$ii<count($numarray);$ii++){
					$itemvalue = explode("|",$numarray[$ii]);//.split('|');
					if($itemvalue[0]==$value){
						$selectnum=$itemvalue[1];
					}
				}
				$htmlstr=$selectnum;				
				break;
			case "4"://单选框
				$selectnum = "";
				$numarray = explode(",",$ob["catItem"]);//value.split(',');
				for($ii=0;$ii<count($numarray);$ii++){
					$itemvalue = explode("|",$numarray[$ii]);//.split('|');
					if($itemvalue[0]==$value){
						$selectnum=$itemvalue[1];
					}
				}
				$htmlstr=$selectnum;
				break;
			
			case "5"://多选框 <input name="asd2" type="checkbox" class="checkbox" id="asd2" checked>
				$selectnum = "";
				$numarray = explode(",",$ob["catItem"]);//value.split(',');
				
				if($ob['fieldName']=='color')
				{
					$img = explode(",",$ob["color_img"]);
					foreach($img as $val)
					{
						$pic = explode("|",$val);
						$color_img[$pic[0]]=$pic[1];
					}
					$chk="chk1";
				}
				else
				{
					$chk="chk2";	
				}
				for($ii=0;$ii<count($numarray);$ii++)
				{
					$str=$str1="";
					$itemvalue = explode("|",$numarray[$ii]);//.split('|');				
					$nv=explode(",",$value);	
					if($ob['is_buy_item']==1)
					{
						if(in_array($itemvalue[0],$nv))
						{
							$cimg="''";
							if($ob['fieldName']=='color' and array_key_exists($itemvalue[0],$color_img))
							{
								$cimg="'".$color_img[$itemvalue[0]]."'";
							}
							
							$selectnum.='<li title='.$itemvalue[1].'><input style="display:none;"  type="radio" value="'.$itemvalue[1].'" name="field['.$ob[fieldName].']" '.$str.' id="f'.$ob[fieldName].$ii.'"><label onclick="'.$chk.'(\''.$ob[fieldName].'\',\''.$ii.'\','.$cimg.');selectSpec(\''.$ob['j'].'\',\''.$itemvalue[0].'\');" '.$str1.' name="radio'.$ob[fieldName].'" for="f'.$ob[fieldName].$ii.'" id="radio'.$ob[fieldName].$ii.'"><span>'.$itemvalue[1].'</span><i></i></label></li>';
						}
					}
					else
					{
						if($value==$itemvalue[0])
							$selectnum.=$itemvalue[1];
					}
				}
				$j++;
				
				if($ob['is_buy_item']==1)
				{
					$nc_type='nc_type="property"';
				}
				$htmlstr=$selectnum;
				break;
		}
		return $returnstr."<dd><ul ".$nc_type.">".$htmlstr."</ul></dd></dl>";
	}
	
	function addtabletr($ob)
	{
		$type = $ob["displayType"];
		$htmlstr="";
		$returnstr ="<tr><td align=\"right\">".$ob["fieldDisplay"]."</td><td>";
		$value=$ob["defaultValue"];
		
		
		switch($type)
		{
		case "1"://单行文本(text)
			$htmlstr="<input class='text' type=\"text\" name=\"".$ob["fieldName"]."\" id=\"".$ob["fieldName"]."\"  value=\"".$ob["defaultValue"]."\" >";		
		break;
		
		case "2":
			$htmlstr="<textarea class='textarea' name=\"".$ob["fieldName"]."\" id=\"".$ob["fieldName"]."\" cols=\"70\" rows=\"15\">".$ob["defaultValue"]."</textarea>";
		break;
		
		
		case "3"://下拉框
			$selectnum = "<select class='select' name=\"".$ob["fieldName"]."\">";
			$numarray = explode(",",$ob["catItem"]);
			for($ii=0;$ii<count($numarray);$ii++){
				$itemvalue = explode("|",$numarray[$ii]);//.split('|');
				if($itemvalue[0]==$value){
					$selectnum .="<option value=\"".$itemvalue[0]."\" selected>".$itemvalue[1]."</option>";
				}
				else{
					$selectnum.="<option value=\"".$itemvalue[0]."\">".$itemvalue[1]."</option>";
				}
			}
			$selectnum .= "</select>";
			$htmlstr=$selectnum;				
		break;
		
		
		case "4"://单选框
			$selectnum = "";
			$numarray = explode(",",$ob["catItem"]);//value.split(',');
			for($ii=0;$ii<count($numarray);$ii++){
				$itemvalue = explode("|",$numarray[$ii]);//.split('|');
				if($itemvalue[0]==$value){
					$selectnum.="<input class='radio' type=\"radio\" name=\"".$ob["fieldName"]."\" id=\"".$ob["fieldName"]."\" value=\"".$itemvalue[0]."\" checked>".$itemvalue[1];
				}
				else{
					$selectnum.="<input class='radio' type=\"radio\" name=\"".$ob["fieldName"]."\" id=\"".$ob["fieldName"]."\" value=\"".$itemvalue[0]."\" >".$itemvalue[1];
				}
			}
			$htmlstr=$selectnum;
			break;
		
		case "5"://多选框 <input name="asd2" type="checkbox" class="checkbox" id="asd2" checked>
			$selectnum = "";
			$numarray = explode(",",$ob["catItem"]);//value.split(',');
			for($ii=0;$ii<count($numarray);$ii++)
			{
				$itemvalue = explode("|",$numarray[$ii]);//.split('|');	
				$nv=explode(",",$value);			
				if(in_array($itemvalue[0],$nv))
					$str="checked";
				else
					$str='';
			
				$selectnum.="<input class='checkbox' $str type=\"checkbox\" name=\"".$ob["fieldName"]."[]\" id=\"".$ob["fieldName"]."[]\" value=\"".$itemvalue[0]."\" >".$itemvalue[1]."&nbsp;";
			}
			
			$htmlstr=$selectnum.$color;
			break;
		}
			
		return $returnstr.$htmlstr."</td></tr>";
	}
	
	function addtabletr1($ob)
	{
		$returnstr['name'] =$ob["fieldDisplay"];
		$returnstr['field'] =$ob["fieldName"];
		$returnstr['color_img']='0';
		$value=$ob["defaultValue"];
		
		$selectnum = "";
		
		$numarray = explode(",",$ob["catItem"]);//value.split(',');
		for($ii=0;$ii<count($numarray);$ii++)
		{
			$itemvalue = explode("|",$numarray[$ii]);//.split('|');	
			$nv=explode(",",$value);
			if(in_array($itemvalue[0],$nv))
			{
				$str="checked";
				$returnstr['color'][$itemvalue[0]]['checked']='1';
			}
			else
			{
				$str='';
				$returnstr['color'][$itemvalue[0]]['checked']='0';
			}
			$selectnum.="<li><span nc_type='chk'><input class='checkbox' $str type=\"checkbox\" name=\"".$ob["fieldName"]."[]\" value=\"".$itemvalue[0]."\" id=\"".$ob["fieldName"]."\" nc_type=\"".$itemvalue[1]."\" ></span><span>".$itemvalue[1]."</span></li>";
			
			
			if($ob["fieldName"]=='color')
			{
				$returnstr['color'][$itemvalue[0]]['name']=$itemvalue[1];
				if(!empty($ob["color_img"]))
				{
					$arr=explode(",",$ob["color_img"]);	
					
					foreach($arr as $val)
					{
						$values=explode("|",$val);	
						if($values[0]==$itemvalue[0])
						{
							$returnstr['color'][$itemvalue[0]]['img']=$values[1];
							$returnstr['color_img']='1';
						}
					}
				}
			}
		}
		$returnstr['item']=$selectnum;
		
		return $returnstr;
		
	}
	
	function echoforeach($len,$sign)
	{
		if($len < $sign){
		
			$abc.= "for (var i_".$len."=0; i_".$len." < property_checked[".$len."].length; i_".$len."++){ td_".(intval($len)+1)." = property_checked[".$len."][i_".$len."];";
			$len++;
			$abc.=$this->echoforeach($len,$sign);
		}
		else{
			$abc.= "var spec_bunch = 'i_';";
			
			for($i=0; $i< $len; $i++){
					$abc.= "spec_bunch += td_".($i+1)."[1];";
			}
			for($i=0; $i< $len; $i++){
					$abc.= "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value][".$i."]['+td_".($i+1)."[1]+']\" value='+td_".($i+1)."[0]+' />'+td_".($i+1)."[0]+'</td>';";
			}
			
			$abc.= "str +='<td><input class=\"text\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" nc_type=\"'+spec_bunch+'|price\" value=\"\" /></td><td><input class=\"text\" type=\"text\" name=\"spec['+spec_bunch+'][stock]\" data_type=\"stock\" nc_type=\"'+spec_bunch+'|stock\" value=\"\" /></td><td><input class=\"text\" type=\"text\" name=\"spec['+spec_bunch+'][sku]\" nc_type=\"'+spec_bunch+'|sku\" value=\"\" /></td></tr>';";
			for($i=0; $i< $len; $i++){
				$abc.= "}";
			}
		}
		return $abc;
	}

	function delete_con($infoid,$table_name)
	{
		global $config;
		$this->db->query("SHOW TABLES LIKE '".$table_name."'");
		if($this->db->num_rows()==1)
		{
			$sql="delete from $table_name where infoid='$infoid' and infotype='".$this->module."'";
			$this->db->query($sql);
		}
	}
	
	//将数据写入表中
	function update_con($infoid,$table_name)
	{	
		global $config;	
		$tn=explode('_',$table_name);
		$catid=$tn[2];
		
		$sql= "select id,field,display_type from ".PROPERTYVALUE." where property_id='$catid' and statu=1";
		$this->db->query($sql);
		$re=$this->db->getRows();
		if(empty($re))
			return NULL;
			
		$filed[]='info_id';
		$filed[]='info_type';
		
		$values[]=$infoid;
		$values[]="'".$this->module."'";
		
		$filed[]='color_img';
		if(!empty($_POST['color_img']))
		{
			foreach($_POST['color_img'] as $key=>$val)
			{
				if($val)
				$color_img_arr[]="$key|$val";	
			}
			
			$color_img=@implode(',',$color_img_arr);
		}
		if(!empty($color_img)){
			$values[]="'".$color_img."'";
		}
		else{
			$values[]="''";
		}
		
		foreach($re as $v)
		{
			$filed[]=$v['field'];
			
			if($v['display_type']=="5")
				$values[] = "'".implode(",",$_POST[$v['field']])."'";
			else
				$values[] =	"'".$_POST[$v['field']]."'";
		}
		$sql="select * from $table_name where info_id='$infoid' and info_type='".$this->module."'";
		$this->db->query($sql);
		if($this->db->num_rows())
		{
			$sql="update $table_name set id=id ";
			foreach($filed as $key=>$v)
			{
				$sql.=",$v=$values[$key]";
			}
			$sql.=" where info_id='$infoid' and info_type='".$this->module."'";
			$this->db->query($sql);
		}
		else
		{
			$sql = "insert into ".$table_name;
			$sql.="(".implode(",",$filed).")";
			$sql.=" values ";
			$sql.="(".implode(',',$values).")";
			$this->db->query($sql);
		}
	}
	
}
?>
