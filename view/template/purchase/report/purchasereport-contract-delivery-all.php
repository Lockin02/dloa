<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = "";

$year = $_GET['searchYear'];
//公用sql段
$publicSql = " COUNT(c.id) AS allnum,
	SUM(IF(MONTH(c.ExaDTOne) = 01, 1, 0)) AS January,
	SUM(IF(MONTH(c.ExaDTOne) = 02, 1, 0)) AS February,
	SUM(IF(MONTH(c.ExaDTOne) = 03, 1, 0)) AS March,
	SUM(IF(MONTH(c.ExaDTOne) = 04, 1, 0)) AS April,
	SUM(IF(MONTH(c.ExaDTOne) = 05, 1, 0)) AS May,
	SUM(IF(MONTH(c.ExaDTOne) = 06, 1, 0)) AS June,
	SUM(IF(MONTH(c.ExaDTOne) = 07, 1, 0)) AS July,
	SUM(IF(MONTH(c.ExaDTOne) = 08, 1, 0)) AS August,
	SUM(IF(MONTH(c.ExaDTOne) = 09, 1, 0)) AS September,
	SUM(IF(MONTH(c.ExaDTOne) = 10, 1, 0)) AS October,
	SUM(IF(MONTH(c.ExaDTOne) = 11, 1, 0)) AS November,
	SUM(IF(MONTH(c.ExaDTOne) = 12, 1, 0)) AS December,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 01, CAST(c.id AS CHAR), NULL)) AS January_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 02, CAST(c.id AS CHAR), NULL)) AS February_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 03, CAST(c.id AS CHAR), NULL)) AS March_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 04, CAST(c.id AS CHAR), NULL)) AS April_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 05, CAST(c.id AS CHAR), NULL)) AS May_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 06, CAST(c.id AS CHAR), NULL)) AS June_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 07, CAST(c.id AS CHAR), NULL)) AS July_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 08, CAST(c.id AS CHAR), NULL)) AS August_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 09, CAST(c.id AS CHAR), NULL)) AS September_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 10, CAST(c.id AS CHAR), NULL)) AS October_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 11, CAST(c.id AS CHAR), NULL)) AS November_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 12, CAST(c.id AS CHAR), NULL)) AS December_id ";
$fromSql = " FROM
		oa_contract_contract c
	INNER JOIN (
		SELECT
			e.contractId,
			sum(1) AS number
		FROM
			oa_contract_equ e
		WHERE
			e.isTemp = 0
		AND e.isDel = 0
		GROUP BY
			e.contractId
		HAVING
			number > 0
	) e ON c.id = e.contractId
	LEFT JOIN oa_contract_equ_link l ON (
		c.id = l.contractId
		AND l.contractType = 'oa_contract_contract'
	)";
$conditionSql = " WHERE
		(l.isTemp = 0 OR l.isTemp IS NULL)
	AND c.isTemp = 0
	AND c.ExaStatus IN ('完成')
	AND date_format(c.ExaDTOne, '%Y') = $year";

$QuerySQL = <<<QuerySQL
 SELECT '本月累计未完成的交付合同数量' AS projectName ,$publicSql $fromSql $conditionSql
	AND c.state IN ('2','4')
	AND c.shipCondition IN ('0','1')
	AND c.DeliveryStatus IN ('WFH','BFFH')
	AND l.ExaStatus IN ('完成')
	GROUP BY date_format(c.ExaDTOne, '%Y')
 UNION
 SELECT '本月新增加的合同数量' AS projectName ,$publicSql $fromSql $conditionSql
	GROUP BY date_format(c.ExaDTOne, '%Y')
 UNION
 SELECT '本月完成交付的合同数量' AS projectName ,$publicSql $fromSql $conditionSql
	AND c.state IN ('2','3','4','7')
	AND c.DeliveryStatus IN ('YFH','TZFH')
	GROUP BY date_format(c.ExaDTOne, '%Y')
 UNION
 SELECT '本月完成交付的合同金额' AS projectName ,FORMAT(SUM(c.contractMoney) ,2) AS allnum,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 01, c.contractMoney, 0)) ,2) AS January,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 02, c.contractMoney, 0)) ,2) AS February,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 03, c.contractMoney, 0)) ,2) AS March,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 04, c.contractMoney, 0)) ,2) AS April,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 05, c.contractMoney, 0)) ,2) AS May,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 06, c.contractMoney, 0)) ,2) AS June,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 07, c.contractMoney, 0)) ,2) AS July,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 08, c.contractMoney, 0)) ,2) AS August,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 09, c.contractMoney, 0)) ,2) AS September,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 10, c.contractMoney, 0)) ,2) AS October,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 11, c.contractMoney, 0)) ,2) AS November,
	FORMAT(SUM(IF(MONTH(c.ExaDTOne) = 12, c.contractMoney, 0)) ,2) AS December,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 01, CAST(c.id AS CHAR), NULL)) AS January_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 02, CAST(c.id AS CHAR), NULL)) AS February_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 03, CAST(c.id AS CHAR), NULL)) AS March_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 04, CAST(c.id AS CHAR), NULL)) AS April_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 05, CAST(c.id AS CHAR), NULL)) AS May_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 06, CAST(c.id AS CHAR), NULL)) AS June_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 07, CAST(c.id AS CHAR), NULL)) AS July_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 08, CAST(c.id AS CHAR), NULL)) AS August_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 09, CAST(c.id AS CHAR), NULL)) AS September_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 10, CAST(c.id AS CHAR), NULL)) AS October_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 11, CAST(c.id AS CHAR), NULL)) AS November_id,
	GROUP_CONCAT(IF(MONTH(c.ExaDTOne) = 12, CAST(c.id AS CHAR), NULL)) AS December_id
	$fromSql $conditionSql
	AND c.state IN ('2','3','4','7')
	AND c.DeliveryStatus IN ('YFH','TZFH')
	GROUP BY date_format(c.ExaDTOne, '%Y')
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
