<?php

if(!empty($_COOKIE['cartnumt']))
	$num=count(explode('|',$_COOKIE['cartnumt']))-1;
elseif($buid)
{
	$sql="select sum(num) as nums from ".CART." where userid='$buid'";
	$db->query($sql);
	$num=$db->fetchField('nums');
}
$num*=1;
echo "document.write('".$num."')";
?>