<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$searchCondition="";
if( isset($_GET['assetTypeName'])&&$_GET['assetTypeName']!=''){
$assetTypeName= $_GET['assetTypeName'] ;//�ʲ����
$searchCondition=" and assetTypeName ='".$assetTypeName."'";
}

$QuerySQL = <<<QuerySQL
select * from oa_asset_card where isTemp='0' and useStatusName='����' $searchCondition;
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>

