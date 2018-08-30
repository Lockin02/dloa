<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$searchCondition="";
if( isset($_GET['assetTypeName'])&&$_GET['assetTypeName']!=''){
$assetTypeName= $_GET['assetTypeName'] ;//资产类别
$searchCondition.=" and assetTypeName ='".$assetTypeName."'";
}
if( isset($_GET['useStatusCode'])&&$_GET['useStatusCode']!=''){
$useStatusCode= $_GET['useStatusCode'] ;//资产类别
$searchCondition.=" and useStatusCode ='".$useStatusCode."'";
}
if( isset($_GET['orgId'])&&$_GET['orgId']!=''){
$orgId= $_GET['orgId'] ;//资产类别
$searchCondition.=" and orgId in(".$orgId.")";
}
if( isset($_GET['useOrgId'])&&$_GET['useOrgId']!=''){
$useOrgId= $_GET['useOrgId'] ;//资产类别
$searchCondition.=" and useOrgId ='".$useOrgId."'";
}
if( isset($_GET['userId'])&&$_GET['userId']!=''){
$userId= $_GET['userId'] ;//资产类别
$searchCondition.=" and userId ='".$userId."'";
}
if( isset($_GET['useStatusCode'])&&$_GET['useStatusCode']!=''){
$useStatusCode= $_GET['useStatusCode'] ;//资产类别
$searchCondition.=" and useStatusCode ='".$useStatusCode."'";
}

$QuerySQL = <<<QuerySQL
select * from oa_asset_card where isTemp='0' $searchCondition;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>

