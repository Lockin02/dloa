<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if($_GET['ids']){
	$condition .= ' and a.id in(' .$_GET['ids'] . ')' ;
	unset($_GET['sourceType']);
}
if($_GET['objId']){
	$condition .= ' and a.purchaseId in(' .$_GET['objId'] . ')' ;
}
if($_GET['sourceType']){
//	$condition .= ' and d.objType = "'.$_GET['sourceType'].'"';
}
$QuerySQL = <<<QuerySQL
select a.id as id,a.`arrivalCode`,
		a.`arrivalType`,a.`purchaseCode`,a.`purchaseId`,
       a.`supplierName`,a.`purchManName`,a.`stockName`,
		a.`arrivalDate`,a.`purchMode`,
        ae.`sequence`, ae.`productName`, ae.`pattem`, ae.`batchNum`,
        ae.`units`, ae.`arrivalNum`, ae.`storageNum`, ae.`month`
	from oa_purchase_arrival_info  a LEFT join oa_purchase_arrival_equ ae on(a.id=ae.arrivalId)
    	where 1=1 $condition
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
