<?php
/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

	// HTTP headers for no cache etc
	header('Content-type: text/plain; charset=UTF-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	if(empty($_FILES))
	{exit;};
	
    
    define(www_path,'localhost');//网站路径
    define(news_url,'localhost');//网站地址
    $is_gbk=0;//gbk系统填1；
    $s_w=$_GET['w_s'];
    $s_h=$_GET['h_s'];

/*
	require(www_path."/e/class/connect.php");
	require(www_path."/e/class/db_sql.php");
    require(www_path."/e/class/functions.php");
	require(www_path."/e/data/dbcache/class.php");
*/
   // $link=db_connect();
	//$empire=new mysqlquery();
    //$lur=is_login();
  //  $logininid=$lur['userid'];
   // $loginin=$lur['username'];

	$targetDir = www_path . DIRECTORY_SEPARATOR . "d" .DIRECTORY_SEPARATOR."file";
    
	$classid=$_GET['classid'];
    $filepass=$_GET['filepass'];
    $classpath=$class_r[$classid][classpath];
    
    $class_path_array=explode("/",$classpath);
    $last_dir=date('Y-m-j');
    $class_path_array[]=$last_dir;
	foreach ($class_path_array as $key=>$val)
	{
		$path='';
		for($i=0;$i<=$key;$i++)
		{
		$path.=DIRECTORY_SEPARATOR.$class_path_array[$i];
		}
		$path=$targetDir.$path;
		if(!file_exists($path))
		{
			if(!mkdir($path,0777,true)) throw new Exception("mkdir() trace [time:".date('Y-m-d H:i:s', time())."] [script:".__FILE__."] [dir:".$path."] [umask:".umask()."]");
		}
	}
	
    $targetDir=$path;
    
    $cleanupTargetDir = false; // Remove old files
	$maxFileAge = 60 * 60; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Uncomment this one to fake upload time
	// usleep(5000);

	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
	$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

	// Clean the fileName for security reasons
	$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

	// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);

		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
			$count++;

		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}

	// Create target dir
	if (!file_exists($targetDir))
		@mkdir($targetDir);

	// Remove old temp files
	if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// Remove temp files if they are older than the max age
			if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
				@unlink($filePath);
		}

		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');

	// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];

	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// Open temp file
			$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}
    $ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);
    $smallname=$fileName_a."_small".'.jpg';

    
    makethumb($targetDir . DIRECTORY_SEPARATOR . $fileName,$targetDir . DIRECTORY_SEPARATOR . $smallname,$s_w,$s_h);
    $file=$_FILES['file'];
    $ext = strrpos($file['name'], '.');
	$fileName_a = substr($file['name'], 0, $ext);
	$fileName_b = substr($file['name'], $ext);
    $small_name=$fileName_a."_s".$fileName_b;
    $filetime=date("Y-m-d H:i:s");
    $sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath) values('$fileName',$file[size],'$loginin','$last_dir','$filetime',$classid,'$file[name]',1,$filepass,$filepass,0,'');");
    $b_id=$empire->lastid();
    $sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath) values('$smallname',$file[size],'$loginin','$last_dir','$filetime',$classid,'$small_name',1,$filepass,$filepass,0,'');");
    $s_id=$empire->lastid();
    // Return JSON-RPC response
    if($is_gbk)
    {
    $file[name]=iconv('utf-8','gb2312',$file[name]);
    }
	die('{"jsonrpc" : "2.0", "result" : null, "b_id" : "'.$b_id.'","bigpic":"'.news_url.'/d/file/'.$classpath.'/'.$last_dir.'/'.$fileName.'","s_id":"'.$s_id.'","smallpic":"'.news_url.'/d/file/'.$classpath.'/'.$last_dir.'/'.$smallname.'","filename":"'.$file[name].'"}');




// 源文件格式：gif,jpg,jpe,jpeg,png 
// 目的文件格式：jpg 
// 参数说明: 
// $srcFile 源文件 
// $dstFile 目标文件 
// $dstW 目标图象宽度 
// $dstH 目标图象高度 
function makethumb($srcFile,$dstFile,$dstW,$dstH) { 
 $data = GetImageSize($srcFile,$info); 
 switch ($data[2]){ 
  case 1: 
   $im = @ImageCreateFromGIF($srcFile); 
   break; 
  case 2: 
   $im = @imagecreatefromjpeg($srcFile); 
   break; 
 case 3: 
   $im = @ImageCreateFromPNG($srcFile); 
   break; 
 } 
 $srcW=ImageSX($im); 
 $srcH=ImageSY($im); 
 $dstX=0; 
 $dstY=0; 
/*
 if ($srcW*$dstH>$srcH*$dstW) { 
  $fdstH=round($srcH*$dstW/$srcW); 
  $dstY=floor(($dstH-$fdstH)/2); 
  $fdstW=$dstW; 
 }else{ 
  $fdstW=round($srcW*$dstH/$srcH); 
  $dstX=floor(($dstW-$fdstW)/2); 
  $fdstH=$dstH; 
 } 
*/
 $bili=$srcW/$srcH;
 if($srcW * $srcH >= $dstW * $dstH){
  if($srcW >$srcH){
   $fdstW=$dstW;
   $fdstH=ceil($fdstW / $bili);
  }else{
   $fdstH=$dstH;
   $fdstW=ceil($fdstH * $bili);
  }
 }else{
  if($srcW <= $dstW && $srcH <= $dstH){
   $fdstW=$srcW;
   $fdstH=$srcH;
  }elseif($srcW >= $dstW){
   $fdstW=$dstW;
   $fdstH=ceil($fdstW / $bili);
  }else{
   $fdstH=$dstH;
   $fdstW=ceil($fdstH * $bili);
  }
 }
 //echo $fdstW,':',$fdstH;
 //$ni=imagecreatetruecolor($dstW,$dstH); 
 $ni=imagecreatetruecolor($fdstW,$fdstH); 
 $dstX=($dstX<0)?0:$dstX; 
 $dstY=($dstX<0)?0:$dstY; 
 $dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX; 
 $dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY; 

 $black = ImageColorAllocate($ni, 255,255,255);//填充的背景色:黑色 
 imagefilledrectangle($ni,0,0,$dstW,$dstH,$black); 
 //imagecopyresampled($ni,$im,$dstX,$dstY,0,0,$fdstW,$fdstH,$srcW,$srcH); 
 imagecopyresampled($ni,$im,0,0,0,0,$fdstW,$fdstH,$srcW,$srcH); 
 ImageJpeg($ni,$dstFile);//图片直接输出二个参数去掉用header()mine类型 
}
?>