<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$productName = $_GET['productName'];
if (!empty ($productName)) {
	$where = "where equ.productName = '" . $productName . "' and num>0";
} else {
	$where = "";
}
$QuerySQL =<<<QuerySQL
select
   equ.tablename,equ.orderOrgid,equ.productName,equ.productNo,equ.productId,(equ.number-equ.executedNum) as number,sum(equ.number-equ.executedNum) as numberAll,
   equ.issuedPurNum,equ.issuedProNum,(equ.number-equ.executedNum) as num,
   spi.exeNum,
   o.contractName,
   if(equ.orderCode is null or equ.orderCode='',o.contractCode,equ.orderCode) as orderCode,
   o.deliveryDate
from
   oa_shipequ_view equ
LEFT JOIN oa_stock_inventory_info spi ON (equ.productId = spi.productId)
LEFT JOIN oa_contract_contract o on (	o.id = equ.orderOrgid AND  equ.tablename = 'oa_contract_contract' AND o.isTemp=0 AND o.ExaStatus IN ('完成', '变更审批中') and o.state=2)
LEFT JOIN oa_borrow_borrow b on (	b.id = equ.orderOrgid AND equ.tablename = 'oa_borrow_borrow' AND o.isTemp=0 AND o.ExaStatus IN ('完成', '变更审批中'))
$where
group by
	equ.productId,
  	o.id
order by
    equ.productName desc
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
