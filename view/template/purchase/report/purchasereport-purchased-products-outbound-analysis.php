<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//条件
$condition = "";
$year = trim($_GET['searchYear']);
if ($year != '') {
	$condition .= " AND (date_format(d.auditDate, '%Y') = $year OR date_format(f.ExaDT, '%Y') = $year) ";
}

$budgetTypeName = trim($_GET['budgetTypeName']);
if ($budgetTypeName != '') {
	$condition .= " AND c.budgetTypeName LIKE '%$budgetTypeName%' ";
}

$QuerySQL = <<<QuerySQL
	SELECT
		c.id,
		c.linkequNames,
		c.pattern,
		c.dataType,
		c.linkequType,
		GROUP_CONCAT(c.cids) AS cids,
		c.budgetTypeName,
		c.brand,
		c.equId,
		c.equCode,
		c.equName,
		c.stockNum,
		IF (d.outNum > 0, d.outNum, 0) AS outNum,
		IF (d.January > 0, d.January, 0) AS oJanuary,
		IF (d.February > 0, d.February, 0) AS oFebruary,
		IF (d.March > 0, d.March, 0) AS oMarch,
		IF (d.April > 0, d.April, 0) AS oApril,
		IF (d.May > 0, d.May, 0) AS oMay,
		IF (d.June > 0, d.June, 0) AS oJune,
		IF (d.July > 0, d.July, 0) AS oJuly,
		IF (d.August > 0, d.August, 0) AS oAugust,
		IF (d.September > 0, d.September, 0) AS oSeptember,
		IF (d.October > 0, d.October, 0) AS oOctober,
		IF (d.November > 0, d.November, 0) AS oNovember,
		IF (d.December > 0, d.December, 0) AS oDecember,
		d.auditDate,

		IF (f.amountAll > 0, f.amountAll, 0) AS amountAll,
		IF (f.January > 0, f.January, 0) AS fJanuary,
		IF (f.February > 0, f.February, 0) AS fFebruary,
		IF (f.March > 0, f.March, 0) AS fMarch,
		IF (f.April > 0, f.April, 0) AS fApril,
		IF (f.May > 0, f.May, 0) AS fMay,
		IF (f.June > 0, f.June, 0) AS fJune,
		IF (f.July > 0, f.July, 0) AS fJuly,
		IF (f.August > 0, f.August, 0) AS fAugust,
		IF (f.September > 0, f.September, 0) AS fSeptember,
		IF (f.October > 0, f.October, 0) AS fOctober,
		IF (f.November > 0, f.November, 0) AS fNovember,
		IF (f.December > 0, f.December, 0) AS fDecember,
		f.ExaDT
	FROM
		(
			SELECT
				c.*, e.needNumC,
				0 AS actNum,
				e.cids,
				linkequStr
			FROM
				oa_report_stockinfo c
			LEFT JOIN (
				SELECT
					GROUP_CONCAT(CAST(id AS CHAR)) AS linkequStr,
					proTypeId
				FROM
					oa_stock_product_info
				GROUP BY
					proTypeId
			) i ON i.proTypeId = c.linkequIds
			LEFT JOIN (
				SELECT
					GROUP_CONCAT(CAST(contractId AS CHAR)) AS cids,
					productId,
					sum(number - executedNum + backNum) AS needNumC
				FROM
					oa_contract_equ ea
				LEFT JOIN oa_contract_contract ca ON ea.contractId = ca.id
				WHERE
					ca.contractType != 'HTLX-ZLHT'
				AND ca.isTemp = 0
				AND ea.isTemp = 0
				AND ea.isDel = 0
				AND ea.number - ea.executedNum + backNum > 0
				AND ea.productId != 0
				AND ca.state = 2
				AND ca.ExaStatus = '完成'
				GROUP BY
					ea.productId
			) e ON c.equId = e.productId
			OR FIND_IN_SET(
				e.productId,
			IF (
				c.linkequType = 0,
				c.linkequIds,
				linkequStr
			)
		)
	) c
	LEFT JOIN (
		SELECT
			o.contractId,
			oi.productId,
			o.auditDate,
			SUM(oi.actOutNum) AS outNum,
			SUM(

				IF (
					MONTH (o.auditDate) = 01,
					oi.actOutNum,
					0
				)
			) AS January,
			SUM(

				IF (
					MONTH (o.auditDate) = 02,
					oi.actOutNum,
					0
				)
			) AS February,
			SUM(

				IF (
					MONTH (o.auditDate) = 03,
					oi.actOutNum,
					0
				)
			) AS March,
			SUM(

				IF (
					MONTH (o.auditDate) = 04,
					oi.actOutNum,
					0
				)
			) AS April,
			SUM(

				IF (
					MONTH (o.auditDate) = 05,
					oi.actOutNum,
					0
				)
			) AS May,
			SUM(

				IF (
					MONTH (o.auditDate) = 06,
					oi.actOutNum,
					0
				)
			) AS June,
			SUM(

				IF (
					MONTH (o.auditDate) = 07,
					oi.actOutNum,
					0
				)
			) AS July,
			SUM(

				IF (
					MONTH (o.auditDate) = 08,
					oi.actOutNum,
					0
				)
			) AS August,
			SUM(

				IF (
					MONTH (o.auditDate) = 09,
					oi.actOutNum,
					0
				)
			) AS September,
			SUM(

				IF (
					MONTH (o.auditDate) = 10,
					oi.actOutNum,
					0
				)
			) AS October,
			SUM(

				IF (
					MONTH (o.auditDate) = 11,
					oi.actOutNum,
					0
				)
			) AS November,
			SUM(

				IF (
					MONTH (o.auditDate) = 12,
					oi.actOutNum,
					0
				)
			) AS December
		FROM
			oa_stock_outstock o
		LEFT JOIN oa_stock_outstock_item oi ON o.id = oi.mainId
		WHERE
			o.docType = 'CKSALES'
		AND o.isRed = 0
		AND o.docStatus = 'YSH'
		GROUP BY
			oi.productId
	) d ON c.equId = d.productId
	LEFT JOIN (
		SELECT
			a.productId,
			b.ExaDT,
			SUM(a.amountAll) AS amountAll,
			SUM(

				IF (
					MONTH (b.ExaDT) = 01,
					a.amountAll,
					0
				)
			) AS January,
			SUM(

				IF (
					MONTH (b.ExaDT) = 02,
					a.amountAll,
					0
				)
			) AS February,
			SUM(

				IF (
					MONTH (b.ExaDT) = 03,
					a.amountAll,
					0
				)
			) AS March,
			SUM(

				IF (
					MONTH (b.ExaDT) = 04,
					a.amountAll,
					0
				)
			) AS April,
			SUM(

				IF (
					MONTH (b.ExaDT) = 05,
					a.amountAll,
					0
				)
			) AS May,
			SUM(

				IF (
					MONTH (b.ExaDT) = 06,
					a.amountAll,
					0
				)
			) AS June,
			SUM(

				IF (
					MONTH (b.ExaDT) = 07,
					a.amountAll,
					0
				)
			) AS July,
			SUM(

				IF (
					MONTH (b.ExaDT) = 08,
					a.amountAll,
					0
				)
			) AS August,
			SUM(

				IF (
					MONTH (b.ExaDT) = 09,
					a.amountAll,
					0
				)
			) AS September,
			SUM(

				IF (
					MONTH (b.ExaDT) = 10,
					a.amountAll,
					0
				)
			) AS October,
			SUM(

				IF (
					MONTH (b.ExaDT) = 11,
					a.amountAll,
					0
				)
			) AS November,
			SUM(

				IF (
					MONTH (b.ExaDT) = 12,
					a.amountAll,
					0
				)
			) AS December
		FROM
			oa_purch_apply_equ a
		LEFT JOIN oa_purch_apply_basic b ON b.id = a.basicId
		WHERE
			b.isTemp = 0
		AND (
			(
				b.state IN (4, 7)
				AND b.ExaStatus = '完成'
			)
			OR (b.state IN(5, 8, 10))
		)
		AND a.purchType IN (
			'oa_sale_order',
			'oa_sale_lease',
			'oa_sale_service',
			'oa_sale_rdproject',
			'HTLX-XSHT',
			'HTLX-ZLHT',
			'HTLX-FWHT',
			'HTLX-YFHT'
		)
		GROUP BY
			a.productId
	) f ON f.productId = c.equId
	WHERE
		1 = 1
		$condition
	GROUP BY
		id,c.equId
	ORDER BY
		budgetTypeName
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
