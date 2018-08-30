<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$year = $_GET['year']; //期初年份
$month = $_GET['month']; //期初月份
$fullYearMonth = $year . str_pad($month, 2, 0, STR_PAD_LEFT);
$endYear = $_GET['endYear']; //结束年份
$endMonth = $_GET['endMonth']; //结束月份
$fullMonth = str_pad($endMonth, 2, 0, STR_PAD_LEFT);
$fullEndYearMonth = $endYear . str_pad($endMonth, 2, 0, STR_PAD_LEFT);
$nextYear = $_GET['nextYear']; //期末年份
$nextMonth = $_GET['nextMonth']; //期末月份
$productNo = isset($_GET['productNo']) ? $_GET['productNo'] : "";  //物料编码
$k3Code = isset($_GET['k3Code']) ? $_GET['k3Code'] : "";  //k3编码
$stockId = isset($_GET['stockId']) ? $_GET['stockId'] : "";  //仓库id

//物料编码
if ($productNo) {
    $condition .= " and total.productNo = '$productNo'";
}
//k3编码
if ($k3Code) {
    $condition .= " and total.k3Code = '$k3Code'";
}
//取指定仓库的数据,需要算上调拨单的记录
if ($stockId) {
    $condition .= " and total.stockId = " . $stockId;
    $allocation = "
		UNION ALL
			SELECT
				DATE_FORMAT(c.auditDate, '%Y.%m') AS periodNo,
				cd.exportStockId AS stockId,
				cd.exportStockName AS stockName,
				cd.productName,
				cd.pattern AS productModel,
				cd.productCode AS productNo,
				f.ext2 AS k3Code,
				cd.unitName,
				cd.batchNum AS batchNo,
				c.auditDate AS docDate,
				c.docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				0 AS actInAmount,
				cd.allocatNum AS outNum,
				0 AS outPrice,
				cd.subCost AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				'' AS redBlue,
				c.remark
			FROM
				oa_stock_allocation c
			LEFT JOIN oa_stock_allocation_item cd ON c.id = cd.mainId
			LEFT JOIN oa_stock_product_info f ON f.id = cd.productId
			WHERE
				c.docStatus = 'YSH'
				AND DATE_FORMAT(c.auditDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
		UNION ALL
			SELECT
				DATE_FORMAT(c.auditDate, '%Y.%m') AS periodNo,
				cd.importStockId AS stockId,
				cd.importStockName AS stockName,
				cd.productName,
				cd.pattern AS productModel,
				cd.productCode AS productNo,
				f.ext2 AS k3Code,
				cd.unitName,
				cd.batchNum AS batchNo,
				c.auditDate AS docDate,
				c.docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				cd.allocatNum AS inNum,
				0 AS inPrice,
				cd.subCost AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				'' AS redBlue,
				c.remark
			FROM
				oa_stock_allocation c
			LEFT JOIN oa_stock_allocation_item cd ON c.id = cd.mainId
			LEFT JOIN oa_stock_product_info f ON f.id = cd.productId
			WHERE
				c.docStatus = 'YSH'
				AND DATE_FORMAT(c.auditDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth ";
}

$QuerySQL = <<<QuerySQL
SELECT
	total.periodNo,
	total.stockId,
	total.stockName,
	total.productName,
	total.productModel,
	total.productNo,
	total.k3Code,
	total.unitName,
	total.batchNo,
	total.docDate,
	total.docCode,
	total.clearingNum,
	total.balanceAmount/total.clearingNum AS price,
	total.balanceAmount,
	total.inNum,
	total.actInAmount/total.inNum AS inPrice,
	total.actInAmount,
	total.outNum,
	total.actOutAmount/total.outNum AS outPrice,
	total.actOutAmount,
	total.clearingNumNext,
	total.balanceAmountNext/total.clearingNumNext AS priceNext,
	total.balanceAmountNext,
	total.redBlue,
	total.remark
FROM
	(
		SELECT
			DATE_FORMAT(c.thisDate, '%Y.%m') AS periodNo,
			c.stockId,
			c.stockName,
			c.productName,
			c.productModel,
			c.productNo,
			c.k3Code,
			c.units AS unitName,
			c.batchNo,
			c.thisDate AS docDate,
			'' AS docCode,
			c.clearingNum,
			c.price,
			c.balanceAmount,
			0 AS inNum,
			0 AS inPrice,
			0 AS actInAmount,
			0 AS outNum,
			0 AS outPrice,
			0 AS actOutAmount,
			0 AS clearingNumNext,
			0 AS priceNext,
			0 AS balanceAmountNext,
			0 as sortNo,
			'' AS redBlue,
			'' AS remark
		FROM
			oa_finance_stockbalance c
		WHERE
			c.thisYear = $year
			AND c.thisMonth = $month
		UNION ALL
			SELECT
				DATE_FORMAT(c.auditDate, '%Y.%m') AS periodNo,
				i.stockId,
				i.stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
				i.unitName,
				i.batchNum AS batchNo,
				c.auditDate AS docDate,
				c.docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				0 AS actInAmount,
				IF (c.isRed = 0,i.actOutNum,-i.actOutNum) AS outNum,
				0 AS outPrice,
				IF (c.isRed = 0,i.subCost,-i.subCost) AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				IF(c.isRed = 0, '蓝','红') AS redBlue,
				c.remark
		FROM
			oa_stock_outstock c
		LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND DATE_FORMAT(c.auditDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
		UNION ALL
			SELECT
				DATE_FORMAT(c.auditDate, '%Y.%m') AS periodNo,
				4 AS stockId,
				'包装物仓库' AS stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
				'' AS unitName,
				'' AS batchNo,
				c.auditDate AS docDate,
				c.docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				0 AS actInAmount,
				IF (c.isRed = 0,i.outstockNum,-i.outstockNum) AS outNum,
				0 AS outPrice,
				IF (c.isRed = 0,i.price,-i.price) AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				IF(c.isRed = 0, '蓝','红') AS redBlue,
				c.remark
		FROM
			oa_stock_outstock c
		LEFT JOIN oa_stock_stockout_extraitem i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND DATE_FORMAT(c.auditDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
		UNION ALL
			SELECT
				DATE_FORMAT(c.auditDate, '%Y.%m') AS periodNo,
				i.inStockId AS stockId,
				i.inStockName AS stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
				i.unitName,
				i.batchNum AS batchNo,
				c.auditDate AS docDate,
				c.docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				IF (c.isRed = 0,i.actNum,-i.actNum) AS inNum,
				0 AS inPrice,
				IF (c.isRed = 0,i.subPrice, -i.subPrice) AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				IF(c.isRed = 0, '蓝','红') AS redBlue,
				c.remark
		FROM
			oa_stock_instock c
		LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND DATE_FORMAT(c.auditDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
		UNION ALL
			SELECT
				DATE_FORMAT(c.thisDate, '%Y.%m') AS periodNo,
				c.stockId,
				c.stockName,
				c.productName,
				c.productModel,
				c.productNo,
				c.k3Code,
				c.units AS unitName,
				c.batchNo,
				last_day('$endYear-$fullMonth-01') AS docDate,
				'' AS docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				0 AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				c.clearingNum AS clearingNumNext,
				c.price AS priceNext,
				c.balanceAmount AS balanceAmountNext,
				99 as sortNo,
				'' AS redBlue,
				'' AS remark
			FROM
				oa_finance_stockbalance c
			WHERE
				c.thisYear = $nextYear
			AND c.thisMonth = $nextMonth
		UNION ALL
			SELECT
				DATE_FORMAT(c.formDate, '%Y.%m') AS periodNo,
				c.stockId,
				c.stockName,
				d.productName,
				d.productModel,
				d.productNo,
				f.ext2 AS k3Code,
				f.unitName,
				d.batchNo,
				c.formDate AS docDate,
				c.formNo AS docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				IF(c.formType = 'CBTZ-01',d.money,0) AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				IF(c.formType = 'CBTZ-01',0,d.money) AS actOutAmount,
				0 AS clearingNumNext,
				0 AS priceNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				'' AS redBlue,
				'' AS remark
			FROM
				oa_finance_costajust c
			LEFT JOIN oa_finance_costajust_detail d ON d.costajustId = c.id
			LEFT JOIN oa_stock_product_info f ON f.id = d.productId
			WHERE
				DATE_FORMAT(c.formDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
        UNION ALL
            SELECT
				DATE_FORMAT(c.formDate, '%Y.%m') AS periodNo,
				d.stockId,
				d.stockName,
				d.productName,
				f.pattern AS productModel,
				d.productNo,
				f.ext2 AS k3Code,
				f.unitName,
				'' as batchNo,
				c.formDate AS docDate,
				c.adjustCode AS docCode,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				allDiffer AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				0 AS priceNext,
				0 AS clearingNumNext,
				0 AS balanceAmountNext,
				1 as sortNo,
				'' AS redBlue,
				'' AS remark
			FROM oa_finance_adjustment_detail d INNER JOIN oa_finance_adjustment c ON c.id = d.adjustId
			LEFT JOIN oa_stock_product_info f ON f.id = d.productId
            WHERE
				DATE_FORMAT(c.formDate, '%Y%m') BETWEEN $fullYearMonth AND $fullEndYearMonth
		$allocation
	) total
WHERE
	total.stockId <> 0
AND total.productNo IS NOT NULL
$condition
ORDER BY 
	total.docDate,total.sortNo,total.docCode;
QuerySQL;
// file_put_contents("d:sql.log", $QuerySQL);
GenAttrXmlData($QuerySQL, false);