<?php
include_once("../includes/tag_inc.php");
include_once("../module/".$_GET['m']."/includes/news_function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<style>
.cat select{ width:45%; margin:10px auto;  font-size:16px;}
.cat select option{ padding:3px 0px;}
.cat select .op1{ padding-left:12px;}
.cat select .op2{ padding-left:22px;}
.cat select .op3{ padding-left:32px;}
.newscat{padding:8px;}
</style>
</HEAD>
<script src="../script/jquery-1.4.4.min.js" type="text/javascript"></script>
<body>
<form method="post" action="module.php?m=news&s=news.php" >
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('news'); ?></div>
	<div class="bigboxbody">
	<table cellpadding="0" cellspacing="0" border="0" class="cat" width="100%">
        <tr>
            <td>
                <select class="newscat" size="18" multiple="multiple" name="type[]" onChange="if(this.value!='') $('#btn').attr('disabled',false);">
                    <?php
                    $str1="disabled='disabled'";
                    foreach(get_newscat() as $key=>$val)
                    {
                    if(empty($val['subcat']))
                    $str='';
                    else
                    $str=$str1;
                    echo "<option $str value='".$val['catid']."'>|-".$val['cat']."</option>";
                    foreach($val['subcat'] as $keys=>$vals)
                    {
                        if(empty($vals['subscat']))
                        $str='';
                        else
                        $str=$str1;
                        echo "<option $str class='op1' value='".$vals['catid']."'>|-".$vals['cat']."</option>";		
                        foreach($vals['subscat'] as $keys=>$list)
                        {
                            if(empty($list['subscat']))
                            $str='';
                            else
                            $str=$str1;
                            echo "<option $str class='op2' value='".$list['catid']."'>|-".$list['cat']."</option>";			
                            foreach($list['subscat'] as $keys=>$lists)
                            {
                                echo "<option class='op3' value='".$lists['catid']."'>|-".$lists['cat']."</option>";
                            }
                        }
                    }
                    }
                    ?> 
              </select>
            </td>
        </tr> 
        <tr>
        	<td><input class="btn" type="submit" id="btn" name="btn" disabled value="<?php echo lang_show('btnsend'); ?>"></td>
        </tr>
	</table>
 </div>
</div>
</form>
</body>
</html>   