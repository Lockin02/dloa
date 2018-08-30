<?php
include ( '../../../config.php' );
include ( '../../../includes/Mysql.class.php');
$msql = new mysql();

$value = isset( $_POST['val'] )? $_POST['val'] : "" ;

//$value = "Å·Ñô";

if( $value !="" ){
	$sql = " select USER_ID as id,USER_NAME as name from user where USER_NAME like '%$value%' ";
}else{
	$sql = " select USER_ID as id,USER_NAME as name from user ";
}

$arr = $msql->getArray ( $sql );
//echo "<pre>";
//print_r($arr);

foreach ($arr as $key=>$val) {
	//$arr[$key]['name'] = urlencode(iconv('gb2312','utf-8',$val['name']));
	$arr[$key]['name'] = iconv('gb2312','utf-8',$val['name']);
}
echo json_encode($arr);






















?>