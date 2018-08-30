<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$condition = " and dataType= ".$_GET['dataType']."";
//类型
if(!empty($_GET['budgetTypeName'])){
	$condition .= ' and budgetTypeName like concat("%","'.$_GET['budgetTypeName'].'","%") ';
}
//是否停产
if(isset($_GET['isStop'])){
	$condition .= ' and isStop like concat("%","'.$_GET['isStop'].'","%")';
}
//品牌
if(!empty($_GET['brand'])){
	$condition .= ' and brand like concat("%","'.$_GET['brand'].'","%")';
}
//设备名称
if(!empty($_GET['equName'])){
	$condition .= ' and equName like concat("%","'.$_GET['equName'].'","%") ';
}
// 支持网路
if(!empty($_GET['netWork'])){
	$netWork=explode(",",$_GET['netWork']);
	$netWork=array_filter($netWork);
	foreach($netWork as $key=>$val){
	$condition .= ' and netWork like concat("%","'.$val.'","%") ';
	}
}
//支持软件
if(!empty($_GET['software'])){
	$software=explode(",",$_GET['software']);
	$software=array_filter($software);
	foreach($software as $key=>$val){
	$condition .= ' and software like concat("%","'.$val.'","%") ';
	}
}

$QuerySQL = <<<QuerySQL
select c.id, c.linkequNames,c.pattern,c.dataType,c.linkequType,
GROUP_CONCAT(c.cids) as cids,GROUP_CONCAT(c.bids) as bids,GROUP_CONCAT(c.pids) as pids,
c.budgetTypeName,c.brand,c.equId,c.equCode,c.equName,c.netWork,c.software,
(sum(if(needNumC is null,0,needNumC))+sum(if(needNumB is null,0,needNumB))+sum(if(needNumP is null,0,needNumP))+c.demandNum) as needNum,
(sum(if(actNum is null,0,actNum))+stockNum) as stockNum,
(sum(if(oldactNum is null,0,oldactNum))+oldNum) as oldNum,
if(c.isStop=0,'否','是') as isStop,c.purTime,c.DeliverTime,c.unDeliverTime,c.remark
from
(
SELECT
c.*,e.needNumC,0 as needNumB,0 as needNumP,0 as actNum,0 as oldactNum,e.cids,'' as bids,'' as pids,linkequStr
FROM oa_report_stockinfo c
left join (select GROUP_CONCAT(CAST(id AS char)) as linkequStr,proTypeId from oa_stock_product_info GROUP BY proTypeId) i on i.proTypeId = c.linkequIds
left JOIN
(select GROUP_CONCAT(CAST(contractId AS char)) as cids,productId,sum(number-executedNum+backNum) as needNumC
from oa_contract_equ ea
LEFT JOIN oa_contract_contract ca on ea.contractId = ca.id
where ca.contractType!='HTLX-ZLHT' and ca.isTemp=0 and ea.isTemp=0 and ea.isDel=0 and ea.number-ea.executedNum+backNum > 0 and ea.productId != 0 and ca.state=2 and ca.ExaStatus='完成' group by ea.productId) e
on c.equId = e.productId or FIND_IN_SET(e.productId,if(c.linkequType=0,c.linkequIds,linkequStr))

union all

SELECT
c.*,0 as needNumC,b.needNumB,0 as needNumP,0 as actNum,0 as oldactNum,'' as cids,b.bids,'' as pids,linkequStr
FROM oa_report_stockinfo c
left join (select GROUP_CONCAT(CAST(id AS char)) as linkequStr,proTypeId from oa_stock_product_info GROUP BY proTypeId) i on i.proTypeId = c.linkequIds
left JOIN
(select GROUP_CONCAT(CAST(borrowId AS char)) as bids,productId,sum(number-executedNum) as needNumB from oa_borrow_equ e
left join oa_borrow_borrow b on e.borrowId=b.id
where e.isTemp=0 and e.isDel=0 and e.number-e.executedNum > 0 and e.productId != 0 and b.ExaStatus='完成' and b.limits='客户' and b.DeliveryStatus != 'TZFH' and b.DeliveryStatus != 'YFH' group by productId) b
on c.equId = b.productId or FIND_IN_SET(b.productId,if(c.linkequType=0,c.linkequIds,linkequStr))

union all

SELECT
c.*,0 AS needNumC,0 as needNumB,P.needNumP,0 as actNum,0 as oldactNum,'' as cids,'' as bids,p.pids,linkequStr
FROM oa_report_stockinfo c
left join (select GROUP_CONCAT(CAST(id AS char)) as linkequStr,proTypeId from oa_stock_product_info GROUP BY proTypeId) i on i.proTypeId = c.linkequIds
left JOIN
(select GROUP_CONCAT(CAST(presentId AS char)) as pids,productId,sum(number-executedNum+backNum) as needNumP from oa_present_equ e
left join oa_present_present p on e.presentId=p.id
where e.isTemp=0 and e.isDel=0 and e.number-e.executedNum > 0 and e.productId != 0 and p.ExaStatus='完成' and p.DeliveryStatus != 'TZFH' and p.DeliveryStatus != 'YFH' group by productId) p
on c.equId = p.productId  or FIND_IN_SET(p.productId,if(c.linkequType=0,c.linkequIds,linkequStr))

union all

SELECT
c.*,0 AS needNumC,0 as needNumB,0 as needNumP,o.actNum,o.oldactNum,''as cids,'' as bids,'' as pids,linkequStr
FROM oa_report_stockinfo c
left join (select GROUP_CONCAT(CAST(id AS char)) as linkequStr,proTypeId from oa_stock_product_info GROUP BY proTypeId) i on i.proTypeId = c.linkequIds
left JOIN
(select productId,sum(if(stockId = '1',actNum,0)) as actNum,sum(if(stockId = '2',actNum,0)) as oldactNum from oa_stock_inventory_info c group by productId) o
on c.equId = o.productId  or FIND_IN_SET(o.productId,if(c.linkequType=0,c.linkequIds,linkequStr))
)c  where 1=1   $condition
group by id order by budgetTypeName


QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
