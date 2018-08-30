<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = " 1=1 ";
if (!empty($_GET['contractCode'])) {
	$contractCode = trim($_GET['contractCode']);
	$condition .= " AND c.contractCode LIKE '%$contractCode%' ";
}

if (!empty($_GET['ExaDTOneStart'])) {
	$ExaDTOne = trim($_GET['ExaDTOneStart']);
	$condition .= " AND DATE_FORMAT(c.ExaDTOne, '%Y-%m') >= '$ExaDTOneStart' ";
}

if (!empty($_GET['ExaDTOneEnd'])) {
	$ExaDTOne = trim($_GET['ExaDTOneEnd']);
	$condition .= " AND DATE_FORMAT(c.ExaDTOne, '%Y-%m') <= '$ExaDTOneEnd' ";
}


$QuerySQL = <<<QuerySQL
	SELECT
		c.ExaDTOne,
		c.contractCode,
		c.contractName,
		o.auditDate,
		datediff(o.auditDate, c.ExaDTOne) AS dayDiff
	FROM
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
	)
	LEFT JOIN (
		SELECT
			MAX(auditDate) AS auditDate,
			contractId
		FROM
			oa_stock_outstock
		WHERE
			docStatus = 'YSH'
		AND relDocType = 'XSCKFHJH'
		GROUP BY
			contractId
	) o ON c.id = o.contractId
	WHERE
	$condition
	AND	(l.isTemp = 0 OR l.isTemp IS NULL)
	AND c.isTemp = 0
	AND c.state IN ('2', '3', '4', '7')
	AND c.DeliveryStatus IN ('YFH', 'TZFH')
	AND c.ExaStatus IN ('完成')
	AND (
		c.ExaDTOne IS NOT NULL
		AND c.ExaDTOne <> '0000-00-00'
		AND c.ExaDTOne <> ''
	)
	ORDER BY
		c.ExaDTOne DESC;
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
