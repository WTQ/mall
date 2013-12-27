<?php
include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");
include_once("$config[webroot]/includes/admin_class.php");
//================================================================
$admin=new admin();
$pro=new pro();

if(empty($_GET['catid'])&&empty($_GET['edit']))
{	
	//$cat=$admin->getCatName(PCAT);
	$sql="select * from ".PCAT." where catid<9999 order by nums asc";
		$db->query($sql);
		$cat=$db->getRows();
	//------------------------------
	$get_user_common_cat=$admin->get_user_common_cat(0);
}
else
{
	//-------------------------------------
	$submit=$_POST['submit'];
	if($submit=="submit")
	{	
		$admin=new admin();
		$re=$pro->add_pro();
		msg("module.php?m=product&s=prolist.php&statu=1");
	}
	//-------------------------------------޸
	if($submit=="edit")
	{
		$admin=new admin();
		$re=$pro->edit_pro();
		unset($_GET['id']);
		$_GET['s']='prolist.php';
		msg("module.php?".implode('&',convert($_GET)));
	}
	//------------------------------------
	$cre=explode('|',$config['credit']);
	foreach($cre as $key=>$v)
	{
		$nkey=pow(2,$key);
		$credit[$nkey]=$v;
	}	
	if(!empty($_GET['edit']))
	{	
		$de=$pro->pro_detail($_GET['edit']);
		$de['credit']=explode_mi($de['credit'],$credit);
		$pactidlist=$de['catid'];
		if(!empty($de['tcatid']))
			$pactidlist.=",".$de['tcatid'];
		if(!empty($de['scatid']))
			$pactidlist.=",".$de['scatid'];	
		if(!empty($de['sscatid']))
			$pactidlist.=",".$de['sscatid'];
		$brand=$pro->get_brand($pactidlist,$de['brand']);
		//------------------------------
		
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		$addfield = new AddField('product');
		$extfiled=$addfield->addfieldinput($_GET['edit'],$de['ext_table']);
		$firstvalue=$extfiled;
	}
	//--------------------------------
	if(empty($_GET['edit']))
	{
		$pactidlist=!empty($_GET['catid'])?$_GET['catid']:NULL;
		if(!empty($_GET['tcatid']))
			$pactidlist.= ",".$_GET['tcatid'];
		if(!empty($_GET['scatid']))
			$pactidlist.=",".$_GET['scatid'];
		if(!empty($_GET['sscatid']))
			$pactidlist.=",".$_GET['sscatid'];
	}
	$pro->add_user_common_cat($pactidlist);
	$typenames=$pro->getProTypeName($pactidlist);
	$ptype=explode('|',$config['ptype']);
	$validTime=explode('|',$config['validTime']);
	$prov=GetDistrict();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('odetail');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
 <style>
        *{font-family:Arial, Helvetica, sans-serif;}
        body {
            font-size:12px;
            font:Arial, Helvetica, sans-serif;
        }
        #searchCatlist {
            list-style-type:none;
            padding:0;
            margin:0;
            text-align:left;
        }
        #searchCatlist a {
            background:#DDDDDD;
            line-height:23px;
            margin:1px 0px;
            padding:0px 30px 0px 20px;
            color:blue;
            display:block;
            text-decoration:none;
            font-size:12px;
        }
        #searchCatlist a:hover
        {
            text-decoration:underline;
            color:#ff7300;
        }
        .togg_tab
        {
            background:#DEEBFE;
            line-height:30px;
            overflow:hidden;
            width:242px;
            margin:0 auto;
            text-align:center;
        }
        .togg_tab a
        {
            display:block;
            float:left;
            color:#FF7300;
            text-decoration:none;
            text-align:center;
            width:120px;
            border-left:1px solid #FFFFFF;
            font-size:13px;
            word-wrap: break-word;
            word-break:breqk-all;
            font-weight:bold;
        }
        .togg_tab a:hover,.togg_tab .curr
        {
            color:#FFFFFF;
            background:#CC0000;
        }
		#pic_view{ 
		    display:inline;
			list-style:none;
		}
		#pic_view li{
			height: 90px;
			width: 64px;
			cursor: pointer;
			float: left;
			text-align: center;
			margin-left: 8px;
			display:block; 
			overflow:hidden;
		}
		#pic_view .wb{
			border: 1px solid #CCC;
			height:60px;
			width:60px;
			cursor:pointer;
			float:left;
			text-align: center;
		
			display:block;
		    overflow:hidden;
		}	
		
		#pic_view .add_pic_btn{
			border: 1px solid #CCC;
			height: 60px;
			width: 60px;
			line-height: 60px;
			cursor: pointer;
			float: left;
			text-align: center;
			margin-left: 8px;
			display:block;
		}
		.hidden{ display:none}
        </style>
        <script src="<?php echo $config['weburl']; ?>/script/my_lightbox.js" language="javascript"></script>
        <script src="<?php echo $config['weburl']; ?>/script/jquery-1.4.4.min.js" type=text/javascript></script>
<script type="text/javascript" src="<?php echo $config['weburl']; ?>/script/district.js" ></script>
<script language="javascript">
var weburl="<?php echo $config['weburl']; ?>";
</script>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php if(empty($_GET['catid'])&&empty($_GET['edit'])){ ?>选择类型 <?php }else{ ?>发布产品<?php } ?> </div>
  <div class="bigboxbody">
<?php if(empty($_GET['catid'])&&empty($_GET['edit'])){ ?>    

<script language="javascript">
var currTab = 0;
function toggCategory(e,i)
{
	e.className='curr';
	i==1?document.getElementById('cate_t_2').className='':document.getElementById('cate_t_1').className='';
	if(i==currTab)
		return;
	cateInit();
	currTab = i;
	getHTML('','tcatid');
	if(i==1)
	{
		document.getElementById('sys_category').style.display='none';	
		document.getElementById('sear_category').style.display='';		
	}
	else
	{
		document.getElementById('sear_category').style.display='none';	
		document.getElementById('sys_category').style.display='';		
	}	
}
	
</script>
        
        <form method="get" action="" id="cate_search">
         
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
          
          
                  <tr height="35px;">
            <td bgcolor="#FFFFFF" valign="top" align="center">
            <select style="width:460px;" onchange="getid(this.value);" <?php if (!$get_user_common_cat): ?>disabled="disabled"<?php endif; ?>>
                <option style="font-weight:bold;" value="">选择常用类别</option>
                <?php 
				foreach ($get_user_common_cat as $num => $list){?>   
			      <?php if ($list){ ?>
			<option value="<?php echo $num; ?>"><?php echo $list; ?></option>
							<?php } 
				} ?>
              </select></td>
          </tr>
                  <tr>
                    <td bgcolor="#FFFFFF" valign="top">
                      <select style="width:130px; height:308px;" name="catid" size="25" id="catid" onchange="getHTML(this.value,'tcatid')">
                        <option style="font-weight:bold;" value="">请选择类别</option>
                        <?php foreach ($cat as $list){?>  
                        <option value="<?php echo $list['catid']; ?>" ><?php echo $list['cat']; ?></option>
                        <?php }; ?>
                      </select>

                      <select style="width:130px; height:308px;" name="tcatid" size="25" id="tcatid" onChange="getHTML(this.value,'scatid')">
                      </select>
                      
                      <select style="width:130px; height:308px;"  name="scatid" size="25" id="scatid"  onChange="getHTML(this.value,'sscatid')">
                      </select>
                      
                      <select style="width:130px; height:308px;"  onchange="allCatList()" name="sscatid" id="sscatid" size="25">
                      </select>
                      </td>
                  </tr>
          
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF" style="padding-top:10px;">
                <span style="font-size:12px; color:#666666;">您当前选择的类别是：<label id='cruu_cate_text'></label>&nbsp;&nbsp;</span><input id="submit" onclick="chooseComplete()" type="submit" value="<?php echo $lang['submit']; ?>" class="btn"/></td>
            </tr>
          </table>
          <input type="hidden" id="cat" value="" />
          <input type="hidden" id="tcat" value="" />
          <input type="hidden" id="scat" value="" />
          <input type="hidden" id="category_id" name="category_id" value="" />
          <input type="hidden" name="m" value="product" />
          <input type="hidden" name="s" value="add_product.php" />
        </form>
        <script type="text/javascript">
        window.onload=function()
        {
            document.getElementById('scatid').style.visibility="hidden";
            document.getElementById('sscatid').style.visibility="hidden";
            document.getElementById('tcatid').style.visibility="hidden";
            cateInit();
            if(document.getElementById('key_word').value!='')
            {	
                chooseCategory(document.getElementById('key_word').value);
                toggCategory(document.getElementById('cate_t_1'),1);
            }
            else
            {
                toggCategory(document.getElementById('cate_t_2'),2);
            }
        }
        function chooseComplete()
        {
            if(document.getElementById('category_id').value==''){alert('<?php echo $lang['product_category']; ?>');return false;}
        }
        function cateInit()
        {
            document.getElementById('category_id').value='';
            document.getElementById('cruu_cate_text').innerHTML ='';
            document.getElementById('submit').disabled = true;
        }
        function getText(ob)
        {
            return ob.selectedIndex>0?ob.options[ob.selectedIndex].text:'';
        }
        function getCatList()
        {
             var catList = document.getElementById('cat').value;
             document.getElementById('category_id').value = document.getElementById('catid').value;
             if(document.getElementById('tcat').value!=''){
                catList += '>'+document.getElementById('tcat').value;
                document.getElementById('category_id').value = document.getElementById('tcatid').value;
             }
             if(document.getElementById('scat').value!=''){
                catList += '>'+document.getElementById('scat').value;
                document.getElementById('category_id').value = document.getElementById('scatid').value; 
             }
            return catList;
        }
        function allCatList()
        {
            var catList = getCatList();
            if(catList!=''&&document.getElementById('sscatid').value!=''){
                catList += '>'+getText(document.getElementById('sscatid'));
                document.getElementById('category_id').value = document.getElementById('sscatid').value; 
            }
            document.getElementById('cruu_cate_text').title = catList;
            document.getElementById('cruu_cate_text').innerHTML = catList.length>20?catList.substr(0,20):catList;
        }
        function getid(v)
        {	
            id=v.split(',');
            var sscatid=id[3]*1;
            var scatid=id[2]*1;
            var tcatid=id[1]*1;
            var catid=id[0]*1;
            for(i=0;i<document.getElementById('catid').options.length;i++)
            {
                if(document.getElementById('catid').options[i].value==catid)
                {
                    document.getElementById('catid').options[i].selected = true;
                    document.getElementById('cat').value = document.getElementById('catid').options[i].text;
                }
            }
            getHTML(catid,'tcatid',sscatid,scatid,tcatid);
        }
        
        function getHTML(v,ob,sscatid,scatid,tcatid)
        {	
            if(ob=='tcatid'){
                document.getElementById('scatid').options.length=0;
                document.getElementById('sscatid').options.length=0;
                document.getElementById('scatid').style.visibility="hidden";
                document.getElementById('sscatid').style.visibility="hidden";
                document.getElementById('cat').value = getText(document.getElementById('catid'));//
                document.getElementById('tcat').value = '';
                document.getElementById('scat').value = '';
            }
            if(ob=='scatid'){
                document.getElementById('sscatid').options.length=0;
                document.getElementById('sscatid').style.visibility="hidden";
                document.getElementById('tcat').value = getText(document.getElementById('tcatid'));//
                document.getElementById('scat').value= '';
            }
            if(ob=='sscatid')
                document.getElementById('scat').value= getText(document.getElementById('scatid'));//
            var url = '<?php echo $config['weburl']; ?>/ajax_back_end.php';
            var sj = new Date();
            var pars = 'shuiji=' + sj+'&cattype=pro&pcatid='+v;
            $.post(url, pars,showResponse);
            function showResponse(originalRequest)
            {
                if(originalRequest=='')
                    return false;
                    
                var tempStr = 'var MyMe = ' + originalRequest; 
                var i=1;var j=0;
                eval(tempStr);
                for(var s in MyMe)
                {
                    ++j;
                }
                if(j>0)
                    document.getElementById(ob).style.visibility="visible";
                else
                    document.getElementById(ob).style.visibility="hidden";
                document.getElementById(ob).options.length =j+1;
                document.getElementById(ob).options[0].value = '';
                document.getElementById(ob).options[0].text = '<?php echo $lang['select_sub_categories']; ?>';
                document.getElementById(ob).options[0].selected = true;
                for(var k in MyMe)
                {
                    var cityId=MyMe[k][0];
                    var ciytName=MyMe[k][1];
                    document.getElementById(ob).options[i].value = cityId;
                    document.getElementById(ob).options[i].text = ciytName;
                    
                    if(cityId==scatid||cityId==tcatid||cityId==sscatid)
                    {
                        document.getElementById(ob).options[i].selected = true;
                        scityid=cityId;
                    }
                    i++;
            　	}
			    try
				{
					if(ob=='tcatid')
						getHTML(scityid,'scatid',sscatid,scatid,tcatid);
					if(ob=='scatid')
						getHTML(scityid,'sscatid',sscatid,scatid,tcatid);
				}
				catch(e)
				{
					
				}
             }
             var catList = getCatList();
             document.getElementById('cruu_cate_text').title = catList;
             document.getElementById('cruu_cate_text').innerHTML = catList.length>20?catList.substr(0,20):catList;
             if(document.getElementById('catid').value=='')
                cateInit();
             else
                document.getElementById('submit').disabled = false;
        　}
        function thirdCata(k,catList)
        {
            document.getElementById('cruu_cate_text').title = catList;
            document.getElementById('cruu_cate_text').innerHTML = catList.length>20?catList.substr(0,20):catList;
            document.getElementById('category_id').value = k;
            document.getElementById('submit').disabled = false;
        }
        function chooseCategory(key)
        {
            if(key=='')
            {
                alert('请填写关键字');	
                return;
            }
            var url = '<?php echo $config['weburl']; ?>/cate_show_ajax.php?oper=ajax&call=search_cate';
            var sj = new Date();
            var pars = 'shuiji=' + sj+'&key_word='+key;
            $.post(url, pars,showResponse);
            function showResponse(originalRequest)
            {
                var tempStr = 'var MyMe = ' + originalRequest; 
                eval(tempStr);
                var str='';
                for(var k in MyMe)
                {
                    var catName=MyMe[k];
                    str += "<a onclick=\"thirdCata('"+k+"','"+catName+"');\" href=\"javascript:void(0)\">"+catName+"("+k+")</a>";str += "<a onclick=\"thirdCata('"+k+"','"+catName+"');\" href=\"javascript:void(0)\">"+catName+"("+k+")</a>";
                }
                if(str!=''){
                    document.getElementById('re_null').style.display ='none';
                    document.getElementById('searchCatlist').innerHTML = str;
                    document.getElementById('re_list').style.display ='';
                }else{
                    document.getElementById('re_list').style.display ='none';
                    document.getElementById('re_null').style.display ='';
                    document.getElementById('re_null').innerHTML = '<?php echo $lang['sear_no_result']; ?>';
                }
            }
        }
		
        </script>


<?php 
}
else
{ 
?>


<script language="javascript" src="<?php echo $config['weburl']; ?>/script/Calendar.js"></script>
<script language="javascript">
	var cdr = new Calendar("cdr");
	document.write(cdr);
	cdr.showMoreDay = true;
	
	function add_button()
	{
		$("#tr1").hide();
		$("#tr2").hide();
		$("#tr3").hide();
		$("#div").hide();
		$("#div1").show();
	}
	<?php if(!$de['setmeal']) { ?>
	var typei=1;
	<?php }else{?>
	var typei=<?php echo count($de['setmeal']) ;?>;
	<?php } ?>	
	function add_tr(type)
	{
		typei++;
		if($('#define_table'+type).css('display') == 'none')
			$('#define_table'+type).show();
		else
		{	
			var tr1="<tr id='tr"+typei+type+"'>";
			var tr2=$('#tr0'+type).html().replaceAll('tr0','tr'+typei);
			tr2=tr2.replaceAll('none','');	
			var tr3="</tr>";
			$('#define_table'+type).append(tr1+tr2+tr3);
			$("#"+type+"_city"+typei).html('');
			$("#add"+type+"_city"+typei).val('');
		}
	}
	
	function remove_tr(row,table)
	{
		if(row=='tr0')
		{
			$('#define_table'+table).hide();
			$('#add'+table+'_price_tr0').val();
		}
		else
			$('#'+row+table).remove();
	}
	String.prototype.replaceAll = function (str1,str2)
	{
		var str    = this;     
		var result   = str.replace(eval("/"+str1+"/gi"),str2);
		return result;
	}
	function del(row,table,id)
	{
		remove_tr(row,table);
		var url = '../module/product/ajax_update.php';
		var sj = new Date();
		v='rec='+id+'&action=admin_product';
		var pars = 'shuiji=' + sj+'&'+v;
		$.get(url, pars,showResponse);
		function showResponse(originalRequest)
		{
			var tempStr = originalRequest;
		}
	}
</script>
<script src="<?php echo $config['weburl']; ?>/script/jquery-1.4.4.min.js" type=text/javascript></script>
<form method="post" action="" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="6" cellspacing="1" class="admin_table">
<tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['category']; ?>
<font color="red"> *</font> </td>
  <td bgcolor="#FFFFFF">
  <span id="cate_show"><?php echo $typenames; ?>
</span>&nbsp;
  <?php if ($de){ ?>
	  <input name="catid" id="catid" type="hidden" value="<?php echo $de['catid']; ?>
" />
	  <input name="tcatid" id="tcatid" type="hidden" value="<?php echo $de['tcatid']; ?>
" />
	  <input name="scatid" id="scatid" type="hidden" value="<?php echo $de['scatid']; ?>
" />
	  <input name="sscatid" id="sscatid" type="hidden" value="<?php echo $de['sscatid']; ?>
" />
  <?php }else{ ?>
	  <input name="catid" id="catid" type="hidden" value="<?php echo $_GET['catid']; ?>
" />
	  <input name="tcatid" id="tcatid" type="hidden" value="<?php echo $_GET['tcatid']; ?>
" />
	  <input name="scatid" id="scatid" type="hidden" value="<?php echo $_GET['scatid']; ?>
" />
	  <input name="sscatid" id="sscatid" type="hidden" value="<?php echo $_GET['sscatid']; ?>
" />
  <?php }; ?>
  </td>
</tr>

<tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['name']; ?>
<font color="red"> *</font> </td>
  <td bgcolor="#FFFFFF">
  <input maxlength="100" name="title" type="text" id="title"  class="text" value="<?php echo $de['pname']; ?>
" /> </td>
</tr>
 <tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['keyword']; ?>
</td>
  <td bgcolor="#FFFFFF">
  <input name="keywords" id="key_word" type="text" value="<?php echo $de['keywords']; ?>
" class="text" /><br>
  <?php echo $lang['key_des']; ?>

  </td>
</tr>
<?php if(!$de['setmeal']) { ?>
<tr id="tr1">
<td align="left" bgcolor="#FFFFFF"><?php echo $lang['market_price']; ?>(<?php echo $config['money']; ?>)</td>
<td bgcolor="#FFFFFF" style="font-weight:normal">
<input type="text" name="market_price" id="market_price" class="ltext"  maxlength="10" value="<?php echo 
$de['market_price']; ?>" />       
</td>
</tr>
<tr id="tr2">
<td align="left" bgcolor="#FFFFFF"><?php echo $lang['price']; ?>(<?php echo $config['money']; ?>)<font color="red"> *</font></td>
<td bgcolor="#FFFFFF" style="font-weight:normal">
<input type="text" name="price" id="price"  class="ltext" maxlength="10" value="<?php echo $de['price']; ?>
" />        
</td>
</tr>
<?php } ?>
<td bgcolor="#FFFFFF">规格</td>
<td bgcolor="#FFFFFF" style="font-weight:normal">
	<?php if(!$de['setmeal']) { ?>
    <div id="div">
    	<a class="add_btn" href="javascript:add_button();">开启规格</a>
	</div>
    <?php } ?>
    <div id="div1" <?php if(!$de['setmeal']) { ?> style="display:none"  <?php } ?> >
    	<table id="define_table_type">
        	<tr>
            	<td align="center">套餐</td>
            	<td align="center">价格</td>
            	<td align="center">库存</td>
            	<td align="center">货号</td>
            	<td align="center">操作</td>
            </tr>
            <?php  
			if($de['setmeal']) {
			foreach($de['setmeal'] as $key=>$v){ ?>
            <tr id="tr<?php echo $key+1;?>_type">
                <td><input type="text" name="setmeal1[]" style="width:100px" value="<?php echo $v['setmeal']; ?>" /></td>
                <td><input type="text" name="setmeal_p1[]" style="width:100px" value="<?php echo $v['price']; ?>" /></td>
                <td><input type="text" name="stock1[]" style="width:100px" value="<?php echo $v['stock']; ?>" /></td>
                <td><input type="text" name="sku1[]" value="<?php echo $v['sku']; ?>" /></td>
                <td><a href="javascript:del('tr<?php echo $key+1;?>','_type','<?php echo $v['id']; ?>')"><?php echo $lang['delete']; ?></a></td>
                <input type="hidden" name="sid[]" value="<?php echo $v['id']; ?>" />
            </tr>
            <?php } }?>
            <tr id="tr0_type">
            	<td><input type="text" name="setmeal[]" style="width:100px" value="" /></td>
            	<td><input type="text" name="setmeal_p[]" style="width:100px" value="" /></td>
            	<td><input type="text" name="stock[]" style="width:100px" value="" /></td>
            	<td><input type="text" name="sku[]" value="" /></td>
            	<td><a style="display:none" href="javascript:remove_tr('tr0','_type');"><?php echo $lang['delete']; ?></a></td>
             </tr>
        </table>
        <a href="javascript:add_tr('_type');">添加新的规格属性</a>
    </div>
</td>
</tr>
<tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['brand']; ?>
</td>
  <td bgcolor="#FFFFFF">
	<span id="brand"><input type="text" class="text" value="<?php echo $de['brand']; ?>" name="brand" maxlength="30"></span>
  </td>
</tr>

<tr>
  <td bgcolor="#FFFFFF">库存位置<font color="red"> *</font></td>
  <td bgcolor="#FFFFFF"  width="84%">
<input type="hidden" name="t" id="t" value="<?php echo $de['area']; ?>" />
        <input type="hidden" name="province" id="id_1" value="<?php echo $de['province']; ?>" />
        <input type="hidden" name="city" id="id_2" value="<?php echo $de['city']; ?>" />
        <input type="hidden" name="area" id="id_2" value="<?php echo $de['areaid']; ?>" />
        <?php if($de['area']) { ?>
        <div id="d_1"><?php echo $de['area']; ?>&nbsp;&nbsp;<a href="javascript:sd();">编辑</a></div>
        <?php } ?>
        
        <div id="d_2"  <?php if($de['area']) { ?>class="hidden"<?php } ?>>
        <select id="select_1" onChange="selClass(this);">
          <option value="">--请选择--</option>
          <?php echo $prov; ?>
        </select>
        <select id="select_2" onChange="selClass(this);" class="hidden"></select>
        <select id="select_3" onChange="selClass(this);" class="hidden"></select>
        </div>
 </td>
</tr>
  <?php if(!$de['setmeal']) { ?>
<tr id="tr3">
  <td bgcolor="#FFFFFF"><?php echo $lang['amount']; ?>
<font color="red"> *</font></td>
  <td bgcolor="#FFFFFF" >
   <input maxlength="7"  name="amount" type="text" class="text" value="<?php echo $de['amount']; ?>
"/>
  </td>
</tr>
<?php } ?>


<tr>
  <td bgcolor="#FFFFFF">购买获取积分<font color="red"> *</font></td>
  <td bgcolor="#FFFFFF" >
   <input maxlength="7"  name="point" type="text" class="text" value="<?php echo $de['point']; ?>
"/>
  </td>
</tr>
   <tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['picture']; ?><font color="red"> *</font> </td>
  <td height="95" bgcolor="#FFFFFF">
      <input name="dele_pic" id="dele_pic" type="hidden" value="" />
	  <ul id="pic_view">
	  <?php 
	   if(count($de['pic'])){ ?>
	  <?php  foreach ($de['pic'] as $list){?>
	  <li _pic_id="<?php echo $list; ?>" _pic_statu=1><div class="wb"><img src="<?php echo $config['weburl'];?>/<?php echo $list; ?>" height="50"></div>
      [<a href="javascript:void(0);" onclick="remove_pic('<?php echo $list; ?>');"><?php echo $lang['delete']; ?></a>]
      </li>
	  <?php }
	  } ?>
      <li class="add_pic_btn" onclick="alertWin('<?php echo $lang['add_pic']; ?>','',260,130,'<?php echo $config['weburl'];?>/upload.php?re_pic=1');"><?php echo $lang['add_pic']; ?></li>
	</ul>
	<div style="clear:both;" id="overlay"></div>
	<input name="pic" id="pic" type="hidden" value="" />
	</td>
</tr>       
<tr>
  <td bgcolor="#FFFFFF"><?php echo $lang['introduction']; ?><font color="red"> *</font> </td>
  <td bgcolor="#FFFFFF">
    <script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
    <script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="detail"]', {
            resizeType : 1,
            allowPreviewEmoticons : false,
            allowImageUpload : false,
            langType :'<?php echo $config['language']; ?>',
        });
    });
    </script>
    <textarea name="detail" style="width:90%; height:400px;"><?php echo $de["detail1"] ?></textarea>
</td>
</tr>

<tr>
  <td bgcolor="#FFFFFF">&nbsp;</td>
  <td bgcolor="#FFFFFF">
  <input type="submit" value="<?php echo $lang['submit']; ?>
" class="btn" onClick="return check_value();"/>
  <input name="submit" type="hidden" id="submit" value="<?php if ($de['id']){ ?>edit<?php }else{ ?>submit<?php }; ?>" />
  <input name="editID" type="hidden" id="editID" value="<?php echo $de['id']; ?>
" />
  </td>
</tr>			
</table>
</form>
<script>
//--------------------------
var city='<?php echo $de['city']; ?>
';
var province='<?php echo $de['province']; ?>
';
function getHTML(v)
{	
	var ob="city";
	var url = '<?php echo $config['weburl']; ?>/ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&prov_id='+v;
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		var tempStr = 'var MyMe = ' + originalRequest; 
		var i=0;var j=0;
		eval(tempStr);
		for(var s in MyMe)
		{
			++j;
		}
		document.getElementById(ob).options.length =j+1;
		for(var k in MyMe)
		{
			var cityId=MyMe[k][0];
			var ciytName=MyMe[k][1];
			document.getElementById(ob).options[k].value = cityId;
			document.getElementById(ob).options[k].text = ciytName;
			if(city!=''&&city==ciytName)
				document.getElementById(ob).options[k].selected = true;
	　	}
	 }
　}
<?php if ($de['province']){ ?>
getHTML('<?php echo $de['province']; ?>');
<?php }; ?>
//==========================================
function check_value()
{
	if(!document.getElementById('title').value)
	{
		alert('<?php echo $lang['notice_title']; ?>
');
		document.getElementById('title').focus();
		return false;
	}
	if(document.getElementById('catid').value=='')
	{
		alert('<?php echo $lang['product_category']; ?>
');
		return false;
	}
	if(document.getElementById('price').value)
	{
		var str = document.getElementById('price').value;
		if(str.length>10||!str.match(/^(:?(:?\d+.\d+)|(:?\d+))$/))
		{
			alert('产品价格错误');
			document.getElementById('price').focus();
			return false;
		}
	}
	else
	{
		if($('#tr2').css('display') != 'none')
		{
			alert('请填写产品价格');
			document.getElementById('price').focus();
			return false;
		}
	}
   
}
//=========================================
var arr = new Array()
function load_pic(v)
{
	if(v!=''&&!arr.inArray(v))
	{	
		arr.push(v);
		$("#pic_view li").last().remove();
		var pic = document.createElement('li');
		pic.setAttribute('_pic_statu',0);
		pic.setAttribute('_pic_id',v);
		pic.innerHTML = '<div class="wb"><img src="<?php echo $config['weburl'];?>/thumb/'+v+'.jpg" height="50"></div>['+'<a href="javascript:void(0)" onclick="cannel_pic(\''+v+'\')"><?php echo $lang['delete']; ?>
</a>]';
		 document.getElementById('pic_view').appendChild(pic);
		 $("#pic_view").append("<li class=\"add_pic_btn\" onclick=\"alertWin('<?php echo $lang['add_pic']; ?>','',260,130,'<?php echo $config['weburl'];?>/upload.php?re_pic=1');\"><?php echo $lang['add_pic']; ?></li>");
		 	
	}
	document.getElementById('pic').value=arr.join();
	close_win();
}
Array.prototype.inArray = function(valeur) 
{
	for(var i in this) 
	{ 
		if (this[i] === valeur) 
			return true; 
	}
	return false;
}
Array.prototype.remove=function(dx)
{
	for(var i=0,n=0;i<this.length;i++)
	{
		if(this[i]!=dx)
		{
			this[n++]=this[i]
		}
	}
	this.length-=1
}
function cannel_pic(v)
{
	arr.remove(v);
	var pBoxLi = document.getElementById('pic_view').getElementsByTagName('li');
	for(var i=0;i<pBoxLi.length;i++){
		if(pBoxLi[i].getAttribute('_pic_statu')==0&&pBoxLi[i].getAttribute('_pic_id')==v){
			document.getElementById('pic_view').removeChild(pBoxLi[i]);
			document.getElementById('pic').value = arr.join();
			return;
		}
	}	
}
function remove_pic(id){
	document.getElementById('dele_pic').value=document.getElementById('dele_pic').value+','+id;
	var pBoxLi = document.getElementById('pic_view').getElementsByTagName('li');
	for(var i=0;i<pBoxLi.length;i++){
		if(pBoxLi[i].getAttribute('_pic_statu')==1&&pBoxLi[i].getAttribute('_pic_id')==id){
			document.getElementById('pic_view').removeChild(pBoxLi[i]);
			return;
		}
	}	
}
</script>

<?php  } ?>
  </div>
</div>
</body>
</html>