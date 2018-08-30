<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if($_GET['ids']){
	$condition .= ' and i.id in(' .$_GET['ids'] . ')' ;
	unset($_GET['sourceType']);
}
if($_GET['objId']){
	$condition .= ' and i.relDocId in(' .$_GET['objId'] . ')' ;
}
if($_GET['sourceType']){
	$condition .= ' and i.relDocType = "'.$_GET['sourceType'].'"';
}
$QuerySQL = <<<QuerySQL
select i.id as id,i.`docCode`,
		i.`auditDate`,i.`supplierName`,
        i.`relDocType`,i.`relDocCode`,i.relDocId,
		i.`contractCode`,i.`contractName`,
        i.`purOrderCode`,i.`purchaserName`,i.`auditerName`,
        ii.`productCode`,ii.`productName`,ii.`pattern`,ii.`actNum` as proNum,
        ii.price,ii.subPrice,
        ii.`unHookNumber`,ii.`unHookAmount`
	from oa_stock_instock  i LEFT join oa_stock_instock_item ii on(i.id=ii.mainId)
    	where i.docType='RKPURCHASE' $condition
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
