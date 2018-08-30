<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$QuerySQL = <<<QuerySQL
select o.docCode,
       o.auditDate,
       o.contractName,
       s.customerName,
       oi.productCode,
       oi.`productName`,
       oi.actOutNum,
       oi.cost,
       oi.subCost,
       p.proType,
       oi.remark
from oa_stock_outstock_item oi
     left OUTER join oa_stock_outstock o on oi.`mainId` = o.id
     left outer join oa_stock_product_info p on oi.productId = p.id
     left outer join oa_sale_order s on o.contractId=s.id
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
