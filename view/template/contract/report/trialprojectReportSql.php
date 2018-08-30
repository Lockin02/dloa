<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$whereSql =  " where 1=1 and date_format(beginDate,'%Y') = '$year'";

$QuerySQL = <<<QuerySQL

SELECT
   date_format(beginDate,'%Y') as year,
   beginDate,
   QUARTER (beginDate) AS QUARTER,
   concat(date_format(beginDate, '%m'),"ÔÂ") AS MONTH,
   TIMESTAMPDIFF(DAY, begindate, closeDate) as Cycle,
   planSignDate,projectCode,province,areaPrincipal,customerTypeName,customerName,projectDescribe,planContractMoney,
   budgetMoney,affirmMoney,createName
FROM
   oa_trialproject_trialproject $whereSql
ORDER BY QUARTER

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
