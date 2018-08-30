<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = "";
if (!empty($_GET['contractCode'])) {
	$contractCode = trim($_GET['contractCode']);
	$condition .= " AND c.contractCode LIKE '%$contractCode%' ";
}


$QuerySQL = <<<QuerySQL
	SELECT
		c.ExaDTOne,
		c.contractCode,
		c.contractName,
		e.productName,
		IF (
			c.shipCondition = 0,
			'交付中',
			'待通知'
		) AS contractState
	FROM
		oa_contract_contract c
	INNER JOIN (
		SELECT
			e.contractId,
			sum(1) AS number,
			e.productName
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
	)
	WHERE
		(l.isTemp = 0 OR l.isTemp IS NULL)
	AND c.isTemp = 0
	AND c.ExaStatus IN ('完成')
	AND c.state IN ('2', '4')
	AND c.shipCondition IN ('0', '1')
	AND c.DeliveryStatus IN ('WFH', 'BFFH')
	AND l.ExaStatus IN ('完成')
	AND (
		c.ExaDTOne IS NOT NULL
		AND c.ExaDTOne <> '0000-00-00'
		AND c.ExaDTOne <> ''
	)
	AND datediff(NOW(), c.ExaDTOne) > 30
	$condition
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
