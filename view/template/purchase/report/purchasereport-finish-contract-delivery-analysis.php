<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = " 1=1 ";
$year = $_GET['searchYear'];

$publcSelect = " COUNT(c.id) AS allnum,
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
		SUM(IF(MONTH(c.ExaDTOne) = 12, 1, 0)) AS December ";

$publicFrom = " oa_contract_contract c
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
		date_format(c.ExaDTOne, '%Y') = $year
		AND	(l.isTemp = 0 OR l.isTemp IS NULL)
		AND c.isTemp = 0
		AND c.state IN ('2', '3', '4', '7')
		AND c.DeliveryStatus IN ('YFH', 'TZFH')
		AND c.ExaStatus IN ('完成')
		AND (
			c.ExaDTOne IS NOT NULL
			AND c.ExaDTOne <> '0000-00-00'
			AND c.ExaDTOne <> ''
		)";
$QuerySQL = <<<QuerySQL
	SELECT
		ELT(

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) <= 7,
				1,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 7
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 15,
				2,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 15
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 30,
				3,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 30
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 45,
				4,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 45,
				5,
				0
			)
			)
			)
			)
			),
			'T≤7天',
			'7天<T≤15天',
			'15天<T≤30天',
			'30天<T≤45天',
			'T>45天'
		) AS deliveryCycle,
		COUNT(c.id) AS allnum,
		SUM(

			IF (MONTH(c.ExaDTOne) = 01, 1, 0)
		) AS January,
		SUM(

			IF (MONTH(c.ExaDTOne) = 02, 1, 0)
		) AS February,
		SUM(

			IF (MONTH(c.ExaDTOne) = 03, 1, 0)
		) AS March,
		SUM(

			IF (MONTH(c.ExaDTOne) = 04, 1, 0)
		) AS April,
		SUM(

			IF (MONTH(c.ExaDTOne) = 05, 1, 0)
		) AS May,
		SUM(

			IF (MONTH(c.ExaDTOne) = 06, 1, 0)
		) AS June,
		SUM(

			IF (MONTH(c.ExaDTOne) = 07, 1, 0)
		) AS July,
		SUM(

			IF (MONTH(c.ExaDTOne) = 08, 1, 0)
		) AS August,
		SUM(

			IF (MONTH(c.ExaDTOne) = 09, 1, 0)
		) AS September,
		SUM(

			IF (MONTH(c.ExaDTOne) = 10, 1, 0)
		) AS October,
		SUM(

			IF (MONTH(c.ExaDTOne) = 11, 1, 0)
		) AS November,
		SUM(

			IF (MONTH(c.ExaDTOne) = 12, 1, 0)
		) AS December
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
		date_format(c.ExaDTOne, '%Y') = $year
	AND (l.isTemp = 0 OR l.isTemp IS NULL)
	AND c.isTemp = 0
	AND c.state IN ('2', '3', '4', '7')
	AND c.DeliveryStatus IN ('YFH', 'TZFH')
	AND c.ExaStatus IN ('完成')
	AND (
		c.ExaDTOne IS NOT NULL
		AND c.ExaDTOne <> '0000-00-00'
		AND c.ExaDTOne <> ''
	)
	AND DATEDIFF(o.auditDate, c.ExaDTOne) > 0
	GROUP BY
		ELT(

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) <= 7,
				1,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 7
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 15,
				2,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 15
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 30,
				3,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 30
				AND DATEDIFF(o.auditDate, c.ExaDTOne) <= 45,
				4,

			IF (
				DATEDIFF(o.auditDate, c.ExaDTOne) > 45,
				5,
				0
			)
			)
			)
			)
			),
			'1 T≤7天',
			'2 7天<T≤15天',
			'3 15天<T≤30天',
			'4 30天<T≤45天',
			'5 T>45天'
		);
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
