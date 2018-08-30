<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = " ";
$year = trim($_GET['searchYear']);
if ($year != '') {
	$condition .=  " AND date_format(c.ExaDTOne, '%Y') = $year ";
}

$propertiesName = trim($_GET['propertiesName']);
if ($propertiesName != '') {
	$condition .=  " AND g.propertiesName LIKE '%$propertiesName%' ";
}

$QuerySQL = <<<QuerySQL
	SELECT
		c.ExaDTOne,
		c.contractCode,
		c.contractName,
		c.customerType,
		ELT(
			INTERVAL (
				c.customerType,
				110,
				120,
				130,
				210,
				410,
				509,
				710,
				720,
				730,
				740
			),
			'移动',
			'联通',
			'电信',
			'其他',
			'海外',
			'其他',
			'移动',
			'联通',
			'电信',
			'其他'
		) AS customerName,
		p.conProductName,
		p.conProductId,
		SUM(p.number) AS number,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 01,
				p.number,
				0
			)
		) AS January,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 02,
				p.number,
				0
			)
		) AS February,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 03,
				p.number,
				0
			)
		) AS March,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 04,
				p.number,
				0
			)
		) AS April,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 05,
				p.number,
				0
			)
		) AS May,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 06,
				p.number,
				0
			)
		) AS June,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 07,
				p.number,
				0
			)
		) AS July,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 08,
				p.number,
				0
			)
		) AS August,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 09,
				p.number,
				0
			)
		) AS September,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 10,
				p.number,
				0
			)
		) AS October,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 11,
				p.number,
				0
			)
		) AS November,
		SUM(

			IF (
				MONTH (c.ExaDTOne) = 12,
				p.number,
				0
			)
		) AS December,
		g.propertiesName
	FROM
		oa_contract_contract c
	LEFT JOIN oa_contract_equ e ON e.contractId = c.id
	LEFT JOIN (
		SELECT
			max(auditDate) AS auditDate,
			contractId
		FROM
			oa_stock_outstock
		WHERE
			docStatus = 'YSH'
		AND (relDocType = 'XSCKFHJH')
		GROUP BY
			contractId
	) o ON c.id = o.contractId
	LEFT JOIN (
		SELECT
			i.productId,
			p.propertiesName
		FROM
			oa_goods_properties_item i
		LEFT JOIN oa_goods_properties p ON p.id = i.mainId
		LEFT JOIN oa_goods_base_info n ON n.id = p.mainId
		GROUP BY
			i.productId
	) g ON g.productId = e.productId
	LEFT JOIN oa_contract_product p ON c.id = p.contractId
	WHERE
		c.isTemp = 0
	AND e.isTemp = 0
	AND e.isDel = 0
	AND (
		c.DeliveryStatus = 'YFH'
		OR c.DeliveryStatus = 'TZFH'
	)
	AND (
		c.ExaDTOne IS NOT NULL
		AND c.ExaDTOne <> '0000-00-00'
		AND c.ExaDTOne <> ''
	)
	AND g.propertiesName IS NOT NULL
	$condition
	GROUP BY
		ELT(
			INTERVAL (
				c.customerType,
				110,
				120,
				130,
				210,
				410,
				509,
				710,
				720,
				730,
				740
			),
			'移动',
			'联通',
			'电信',
			'其他',
			'海外',
			'其他',
			'移动',
			'联通',
			'电信',
			'其他'
		),
		g.propertiesName
	ORDER BY
		g.propertiesName;
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
