<?php
//ini_set('display_errors','On');
$jpname=$_GET["pname"].".jpg";
//========================================================
if($_GET["type"]=="delproimg")
{
	@unlink("uploadfile/comimg/small/".$jpname);
	@unlink("uploadfile/comimg/big/".$jpname);
}
elseif($_GET["type"]=="pro")
{
	$str=file_get_contents($_GET['url']."/uploadfile/comimg/big/".$jpname);
	$pic = "uploadfile/comimg/big/".$_GET["pname"];
	echo $fp=fopen($pic,"w");
	fwrite($fp,$str,strlen($str));
	fclose($fp);
	//-------
	makethumb($pic , "uploadfile/comimg/small/".$jpname , 100 , 100);
	rename($pic,$pic.".jpg");
}
//==========================================================
elseif($_GET["type"]=="delcomimg")
{
	@unlink("uploadfile/userimg/".$_GET["pname"]);
}
elseif($_GET["type"]=="movecom")
{
	$url="http://jixie315.com.fb6a.8-host.com/uploadfile/userimg/".$jpname;
	$str=file_get_contents($url);
	$pic = "uploadfile/userimg/".$_GET["pname"];
	echo $fp=fopen($pic,"w");
	fwrite($fp,$str,strlen($str));
	fclose($fp);
	rename($pic,$pic.".jpg");
}
//=========================================================
elseif($_GET["type"]=="album")
{
	$str=file_get_contents("http://jixie315.com.fb6a.8-host.com/uploadfile/zsimg/".$jpname);
	$pic = "uploadfile/zsimg/".$_GET["pname"];
	echo $fp=fopen($pic,"w+");
	fwrite($fp,$str,strlen($str));
	fclose($fp);
	//-------
	makethumb($pic , "uploadfile/zsimg/size_small/".$jpname , 100 , 100);
	rename($pic,$pic.".jpg");
}
elseif($_GET["type"]=="delalbum")
{
	@unlink("uploadfile/zsimg/size_small/".$jpname);
	@unlink("uploadfile/zsimg/".$jpname);
}
function makethumb($srcFile,$dstFile,$dstW,$dstH)
{ 
  $quality=90; 
  $data = @GetImageSize($srcFile); 
  switch ($data[2]) { 
    case 1: 
      $im = ImageCreateFromGIF($srcFile); 
   break; 
    case 2: 
      $im = imagecreatefromjpeg($srcFile); 
      break; 
    case 3: 
      $im = ImageCreateFromPNG($srcFile); 
      break; 
  } 
  $srcW=@ImageSX($im); 
  $srcH=@ImageSY($im); 
  if(($srcW<=$dstW)&&($srcH<=$dstH)){
    $dstX=$srcW;
 $dstY=$srcH;
  }
  if(($srcW>=$dstW)&&($srcH<=$dstH)){
    $dstX=$dstW;
 $dstY=floor($srcH/($srcW/$dstW));
  }
  if(($srcW<=$dstW)&&($srcH>=$dstH)){
 $dstY=$dstH;
 $dstX=floor($srcW/($srcH/$dstH));
  }
  if(($srcW>$dstW)&&($srcH>$dstH)){
   if(($srcW/$dstW)>($srcH/$dstH)){
   $dstX=$dstW;
      $dstY=floor($srcH/($srcW/$dstW)); 
     
   }else{
      $dstY=$dstH;
      $dstX=floor($srcW/($srcH/$dstH));
   }
  }
     $ni=@imageCreateTrueColor($dstX,$dstY); 
     @ImageCopyResampled($ni,$im,0,0,0,0,$dstX,$dstY,$srcW,$srcH); 
     @ImageJpeg($ni,$dstFile,$quality); 
     @imagedestroy($im); 
     @imagedestroy($ni); 
}
?>