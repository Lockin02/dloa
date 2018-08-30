<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$whereSql =  "and date_format(createTime,'%Y') = '$year'";
$CwhereSql =  "and date_format(predictContractDate,'%Y') = '$year'";

$QuerySQL = <<<QuerySQL

SELECT
   '合同' as viewType,
   date_format(c.createTime,'%Y') as year,
   DATE_FORMAT(c.createTime,'%Y-%m-%d') as createTime,QUARTER (c.createTime) AS quarter,
   concat(date_format(c.createTime, '%m'),"月") AS month,
   c.contractProvince as province,c.areaPrincipal,c.contractCode as Code,c.customerTypeName,c.customerName,c.contractTypeName as contractType,
   (select GROUP_CONCAT(p.conProductName) from oa_contract_product p where p.contractId = c.id) as productName,
   (SELECT concat(pi.conProductName,"(￥",pi.price,")") FROM oa_contract_product pi where pi.contractId = c.id ORDER BY price DESC LIMIT 1) as maxPro,
    contractMoney as money,remark,c.contractSigner as createName
FROM
   oa_contract_contract c
   left join user u on c.areaPrincipalId=u.USER_ID
   where 1=1 and (c.winRate='100%' or c.winRate='80%' or c.state='3')  and c.areaPrincipal != ''  and c.isTemp = '0' and (u.DEPT_ID = '37' or c.areaCode = '19') $whereSql

UNION ALL

SELECT
   '商机' as viewType,
   date_format(t.predictContractDate,'%Y') as year,
   DATE_FORMAT(t.predictContractDate,'%Y-%m-%d') as createTime,QUARTER(t.predictContractDate) as quarter,
   concat(date_format(t.predictContractDate,'%m'),"月") as month,
   t.Province as province,t.areaPrincipal,t.chanceCode as Code,
   t.customerTypeName,t.customerName,t.chanceTypeName AS contractType,
   (select GROUP_CONCAT(g.goodsName) from oa_sale_chance_goods g where g.chanceId = t.id) as productName,
   (SELECT concat(gi.goodsName,"(￥",gi.price,")") FROM oa_sale_chance_goods gi where gi.chanceId = t.id ORDER BY price DESC LIMIT 1) as maxPro,
   t.chanceMoney AS money,t.progress as remark,t.createName
FROM oa_sale_chance t
   left join user u on t.areaPrincipalId=u.USER_ID
 where 1=1 and (t.winRate='100' or t.winRate='80') and  t.status in (0,3,5)  and u.DEPT_ID = '37' $CwhereSql ORDER BY quarter,viewType

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
