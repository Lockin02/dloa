<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$QuerySQL = "select * from oa_stock_inventory_info";
GenAttrXmlData($QuerySQL, false);
?>
