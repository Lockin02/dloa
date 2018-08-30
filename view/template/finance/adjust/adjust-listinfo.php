<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if(!empty($_GET['formDateBegin'])){
	$condition .= ' and c.formDate >= "'.$_GET['formDateBegin'].'" ';
}
if(!empty($_GET['formDateEnd'])){
	$condition .= ' and c.formDate <= "'.$_GET['formDateEnd'].'" ';
}
if(!empty($_GET['supplierName'])){
	$condition .= ' and c.supplierName like "%'.$_GET['supplierName'].'%" ';
}
if(!empty($_GET['productNo'])){
	$condition .= ' and d.productNo = "'.$_GET['productNo'].'" ';
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select
    c.id ,c.adjustCode ,c.supplierName ,c.supplierId ,c.relatedId ,c.status ,
    c.formDate ,c.amount ,c.createId ,c.createName ,c.createTime,
        d.productNo,d.productName,d.price,d.allDiffer,d.cost,d.differ,d.stockName,d.number
from
    oa_finance_adjustment c
        left join
        oa_finance_adjustment_detail d
            on c.id = d.adjustId
where 1=1
$condition
order by c.formDate desc
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
