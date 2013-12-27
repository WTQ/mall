<?php
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("../includes/global.php");include_once("auth.php");
//===============================================
$type=isset($_POST["type"])?$_POST["type"]:NULL;
$newCatId=isset($_POST["newCatId"])?$_POST["newCatId"]:NULL;
$id=isset($_POST["id"])?$_POST["id"]:NULL;
//===============================================
switch ($type){
	case "pro":
		{
			$db->query("select cat from ".PCAT." where catid= '$newCatId'");
			$newCat = $db->fetchRow();
			if($newCat['cat']){
				$sql="update ".PRO." SET catid='$newCatId' where id='$id'";
				$db->query($sql);
			}
			echo $newCat['cat'];
			break;
		}
	case "album":
		{
			if(isset($newCatId)){
				$sql="update ".CUSTOM_CAT." SET tj='$newCatId' where id='$id'";
				$db->query($sql);
			}
			echo $newCatId;
			break;
		}

}
?>