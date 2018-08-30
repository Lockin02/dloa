<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = "";
//物料信息
if (! empty ( $_GET ['productCode'] )) {
	$condition = $_GET ['productCode'];
}
//echo $condition;
$QuerySQL = <<<QuerySQL
	CALL SELECT_PURCH_INSTOCKDATE('$condition');
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
