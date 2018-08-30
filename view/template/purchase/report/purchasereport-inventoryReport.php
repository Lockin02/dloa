<?php
include '../../../../webreport/data/mysql_GenXmlData.php';


////全部条件
$allCondition = "";
////开始日期
//$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
//$allCondition .= " and date_format(i.auditDate,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";
//
////结束日期
//$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
//$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
//$allCondition .= " and date_format(i.auditDate,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";
//
//
////供应商
//if(!empty($_GET['suppId'])){
//	$allCondition .= " and i.supplierId = ".$_GET['suppId']."";
//}

//物料信息
if(!empty($_GET['productId'])){
	$allCondition .= " and productId = '".$_GET['productId']."'";
}

$QuerySQL = <<<QuerySQL
select productCode,productName,sub.supplierName,sub.auditDate,kc.actNum,sub.price,(sub.price*kc.actNum) as subPrice
from (
select sum(actNum) as actNum,productCode,productName,productId from oa_stock_inventory_info  
		where stockId in(1,4,5) $allCondition  GROUP BY productCode,productName
)kc
 LEFT JOIN (
			select max(auditDate) as auditDate,max(i.supplierName) as supplierName,ii.productId,max(ii.price) as price   
	from oa_stock_instock_item  ii inner join oa_stock_instock i ON(i.id=ii.mainId) group by ii.productId
)sub on(sub.productId=kc.productId) where kc.actNum<>0 
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
