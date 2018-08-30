<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";

$year = $_GET['thisYear'];
//公用sql段
$publicSql = " sum(IF(MONTH(c.ExaDTOne) = 01, 1, 0)) AS January,
			   sum(IF(MONTH(c.ExaDTOne) = 02, 1, 0)) AS February,
			   sum(IF(MONTH(c.ExaDTOne) = 03, 1, 0)) AS March,
			   sum(IF(MONTH(c.ExaDTOne) = 04, 1, 0)) AS April,
			   sum(IF(MONTH(c.ExaDTOne) = 05, 1, 0)) AS May,
			   sum(IF(MONTH(c.ExaDTOne) = 06, 1, 0)) AS June,
			   sum(IF(MONTH(c.ExaDTOne) = 07, 1, 0)) AS July,
			   sum(IF(MONTH(c.ExaDTOne) = 08, 1, 0)) AS August,
			   sum(IF(MONTH(c.ExaDTOne) = 09, 1, 0)) AS September,
			   sum(IF(MONTH(c.ExaDTOne) = 10, 1, 0)) AS October,
			   sum(IF(MONTH(c.ExaDTOne) = 11, 1, 0)) AS November,
			   sum(IF(MONTH(c.ExaDTOne) = 12, 1, 0)) AS December ";
//累计执行中合同数
$addUpSql = " sum(IF(MONTH(c.ExaDTOne) = 01, 1, 0)) AS January,
			  IF(month(NOW()) < 2,0,sum(IF(MONTH(c.ExaDTOne) in (01,02), 1, 0)) ) AS February,
			  IF(month(NOW()) < 3,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03), 1, 0)) ) AS March,
			  IF(month(NOW()) < 4,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04), 1, 0)) ) AS April,
			  IF(month(NOW()) < 5,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05), 1, 0)) ) AS May,
			  IF(month(NOW()) < 6,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06), 1, 0))) AS June,
			  IF(month(NOW()) < 7,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07), 1, 0))) AS July,
			  IF(month(NOW()) < 8,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07,08), 1, 0))) AS August,
			  IF(month(NOW()) < 9,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07,08,09), 1, 0)) ) AS September,
			  IF(month(NOW()) < 10,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07,08,09,10), 1, 0))) AS October,
			  IF(month(NOW()) < 11,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07,08,09,10,11), 1, 0))) AS November,
			  IF(month(NOW()) < 12,0,sum(IF(MONTH(c.ExaDTOne) in (01,02,03,04,05,06,07,08,09,10,11,12), 1, 0))) AS December ";
$publicSqlEmpty = " '' AS January,
			   '' AS February,
			   '' AS March,
			   '' AS April,
			   '' AS May,
			   '' AS June,
			   '' AS July,
			   '' AS August,
			   '' AS September,
			   '' AS October,
			   '' AS November,
			   '' AS December ";

$QuerySQL = <<<QuerySQL
 select '新增合同数' as exe,$publicSql from oa_contract_contract c where year(c.ExaDTOne) = $year
 union
 select '执行完成合同数' as exe,$publicSql from oa_contract_contract c where year(c.ExaDTOne) = $year and c.state in (3,4)
 union
 select '累计执行中合同数' as exe,$addUpSql from oa_contract_contract c where year(c.ExaDTOne) = $year and c.state=2
 union
  select '按时执行合同数' as exe,$publicSqlEmpty from oa_contract_contract c where year(c.ExaDTOne) = $year
 union
  select '未按时执行合同数' as exe,$publicSqlEmpty from oa_contract_contract c where year(c.ExaDTOne) = $year

QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
