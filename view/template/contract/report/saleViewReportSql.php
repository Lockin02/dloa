<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$whereSql =  "and date_format(ExaDTOne,'%Y') = '$year'";
$CwhereSql =  "and date_format(predictContractDate,'%Y') = '$year'";

$QuerySQL = <<<QuerySQL

SELECT
   '合同' as viewType,
   date_format(c.ExaDTOne,'%Y') as year,
   DATE_FORMAT(c.ExaDTOne,'%Y-%m-%d') as createTime,QUARTER (c.ExaDTOne) AS quarter,
   concat(date_format(c.ExaDTOne, '%m'),"月") AS month,
   c.contractProvince as province,c.areaPrincipal,c.contractCode as Code,c.customerTypeName,c.customerName,c.contractTypeName as contractType,c.contractName,
   (select GROUP_CONCAT(p.conProductName) from oa_contract_product p where p.contractId = c.id) as productName,
   (SELECT pi.conProductName FROM oa_contract_product pi where pi.contractId = c.id ORDER BY money DESC LIMIT 1) as maxPro,
   (SELECT pi.number FROM oa_contract_product pi where pi.contractId = c.id ORDER BY money DESC LIMIT 1) as maxProNum,
   (SELECT pi.money FROM oa_contract_product pi where pi.contractId = c.id ORDER BY money DESC LIMIT 1) as maxProMoney,
    contractMoney as money,remark,c.contractSigner as createName
FROM
   oa_contract_contract c
   left join user u on c.areaPrincipalId=u.USER_ID
   where 1=1 and (c.state in (2,3,4,5,6,7)) and c.areaPrincipal != ''  and c.isTemp = '0' and (u.DEPT_ID = '37' or (c.areaCode = '19' and c.customerType = '510')) $whereSql

UNION ALL

SELECT
   '商机' as viewType,
   date_format(t.predictContractDate,'%Y') as year,
   DATE_FORMAT(t.predictContractDate,'%Y-%m-%d') as createTime,QUARTER(t.predictContractDate) as quarter,
   concat(date_format(t.predictContractDate,'%m'),"月") as month,
   t.Province as province,t.areaPrincipal,t.chanceCode as Code,
   t.customerTypeName,t.customerName,t.chanceTypeName AS contractType,t.chanceName as contractName,
   (select GROUP_CONCAT(g.goodsName) from oa_sale_chance_goods g where g.chanceId = t.id) as productName,
   (SELECT gi.goodsName FROM oa_sale_chance_goods gi where gi.chanceId = t.id ORDER BY money DESC LIMIT 1) as maxPro,
   (SELECT gi.number FROM oa_sale_chance_goods gi where gi.chanceId = t.id ORDER BY money DESC LIMIT 1) as maxProNum,
   (SELECT gi.money FROM oa_sale_chance_goods gi where gi.chanceId = t.id ORDER BY money DESC LIMIT 1) as maxProMoney,
   t.chanceMoney AS money,t.progress as remark,t.createName
FROM oa_sale_chance t
   left join user u on t.areaPrincipalId=u.USER_ID
 where 1=1 and (t.winRate='100' or t.winRate='80') and  t.status in (0,3,5)  and u.DEPT_ID = '37' $CwhereSql ORDER BY quarter,viewType

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
