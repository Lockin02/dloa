<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php

$startDate=$_GET ['startDate'].' 00:00:00';
$endDate=$_GET ['endDate'].' 23:59:59';

$QuerySQL = <<<QuerySQL
			call select_purchase_suppsub('$startDate','$endDate');     
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
