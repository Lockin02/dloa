<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$year = $_GET['year']; //��ʼ���
$month = str_pad($_GET['month'], 2, "0", STR_PAD_LEFT); //��ʼ�·�
$nextYear = $_GET['nextYear']; //�������
$nextMonth = str_pad($_GET['nextMonth'], 2, "0", STR_PAD_LEFT); //�����·�
$productNo = isset($_GET['productNo']) ? $_GET['productNo'] : "";  //���ϱ���

$startPeriod = $year.$month; // ��ʼ����
$overPeriod = $nextYear.$nextMonth; // ��������

if ($month == 12) {
    $nextStartPeriod = $year + 1 . '01';
} else {
    $tMonth = $_GET['month'] + 1;
    $nextStartPeriod = $year . str_pad($tMonth, 2, "0", STR_PAD_LEFT);
}

if ($nextMonth == 12) {
    $nextOverPeriod = $nextYear + 1 . '01';
} else {
    $tNextMonth = $_GET['nextMonth'] + 1;
    $nextOverPeriod = $nextYear . str_pad($tNextMonth, 2, "0", STR_PAD_LEFT);
}

$productName = isset($_GET['productName']) ? $_GET['productName'] : "";  //���ϱ���
$k3Code = isset($_GET['k3Code']) ? $_GET['k3Code'] : "";  //k3����
$isStock = isset($_GET['isStock']) ? $_GET['isStock'] : "";  //��ʾ�ֿ�

//���ϱ���
if($productNo){
	$condition.=" and total.productNo = '$productNo'";
}

//��������
if($productName){
    $condition.=" and total.productName like BINARY CONCAT('%','".$productName."','%')";
}
//k3����
if($k3Code){
	$condition.=" and total.k3Code = '$k3Code'";
}
//��ʾ�ֿ�,��Ҫ���ϵ������ļ�¼
if($isStock){
	$groupBy.=",total.stockId";
	$orderBy.="total.stockId,";
	$allocation = "
		UNION ALL
			SELECT
				CONCAT(YEAR(c.auditDate),'.', MONTH(c.auditDate)) AS periodNo,
                YEAR(c.auditDate) as periodYear,
                MONTH(c.auditDate) AS periodMonth,
				cd.exportStockId AS stockId,
				cd.exportStockName AS stockName,
				cd.productName,
				cd.pattern AS productModel,
				cd.productCode AS productNo,
				f.ext2 AS k3Code,
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
				0 AS balanceAmountNext
			FROM
				oa_stock_allocation c
			LEFT JOIN oa_stock_allocation_item cd ON c.id = cd.mainId
			LEFT JOIN oa_stock_product_info f ON f.id = cd.productId
			WHERE
				c.docStatus = 'YSH'
			AND DATE_FORMAT(c.auditDate,'%Y%m') >= $startPeriod
		    AND DATE_FORMAT(c.auditDate,'%Y%m') <= $overPeriod
		UNION ALL
			SELECT
				CONCAT(YEAR(c.auditDate),'.', MONTH(c.auditDate)) AS periodNo,
                YEAR(c.auditDate) as periodYear,
                MONTH(c.auditDate) AS periodMonth,
				cd.importStockId AS stockId,
				cd.importStockName AS stockName,
				cd.productName,
				cd.pattern AS productModel,
				cd.productCode AS productNo,
				f.ext2 AS k3Code,
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
				0 AS balanceAmountNext
			FROM
				oa_stock_allocation c
			LEFT JOIN oa_stock_allocation_item cd ON c.id = cd.mainId
			LEFT JOIN oa_stock_product_info f ON f.id = cd.productId
			WHERE
				c.docStatus = 'YSH'
			AND DATE_FORMAT(c.auditDate,'%Y%m') >= $startPeriod
		    AND DATE_FORMAT(c.auditDate,'%Y%m') <= $overPeriod
		    ";
}

$QuerySQL = <<<QuerySQL
SELECT
	total.periodNo,
	total.periodYear,
	total.periodMonth,
	total.stockId,
	total.stockName,
	total.productName,
	total.productModel,
	total.productNo,
	total.k3Code,
	SUM(total.clearingNum) AS clearingNum,
	SUM(total.balanceAmount)/SUM(total.clearingNum) AS price,
	FORMAT(SUM(total.balanceAmount),2) AS balanceAmount,
	SUM(total.inNum) AS inNum,
	SUM(total.actInAmount)/SUM(total.inNum) AS inPrice,
	FORMAT(SUM(total.actInAmount),2) AS actInAmount,
	SUM(total.outNum) AS outNum,
	SUM(total.actOutAmount)/SUM(total.outNum) AS outPrice,
	FORMAT(SUM(total.actOutAmount),2) AS actOutAmount,
	SUM(total.clearingNumNext) AS clearingNumNext,
	SUM(total.balanceAmountNext)/SUM(total.clearingNumNext) AS priceNext,
	FORMAT(SUM(total.balanceAmountNext),2) AS balanceAmountNext
FROM
	(
		SELECT
            CONCAT(YEAR(c.thisDate),'.', MONTH(c.thisDate)) AS periodNo,
            c.thisYear AS periodYear,
            MONTH(c.thisDate) AS periodMonth,
			c.stockId,
			c.stockName,
			c.productName,
			c.productModel,
			c.productNo,
			c.k3Code,
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
			0 AS balanceAmountNext
		FROM
			oa_finance_stockbalance c
		WHERE
			(DATE_FORMAT(c.thisDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
		UNION ALL
		SELECT
            CONCAT(YEAR(ADDDATE(c.thisDate, INTERVAL -1 MONTH)),'.', MONTH(ADDDATE(c.thisDate, INTERVAL -1 MONTH))) AS periodNo,
            YEAR(ADDDATE(c.thisDate, INTERVAL -1 MONTH)) AS periodYear,
            MONTH(ADDDATE(c.thisDate, INTERVAL -1 MONTH)) AS periodMonth,
			c.stockId,
			c.stockName,
			c.productName,
			c.productModel,
			c.productNo,
			c.k3Code,
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
			c.balanceAmount AS balanceAmountNext
		FROM
			oa_finance_stockbalance c
		WHERE
			(DATE_FORMAT(c.thisDate,'%Y%m') BETWEEN $nextStartPeriod AND $nextOverPeriod)
		UNION ALL
			SELECT
				CONCAT(YEAR(c.auditDate),'.', MONTH(c.auditDate)) AS periodNo,
                YEAR(c.auditDate) AS periodYear,
                MONTH(c.auditDate) AS periodMonth,
				i.stockId,
				i.stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
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
				0 AS balanceAmountNext
		FROM
			oa_stock_outstock c
		LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND (DATE_FORMAT(c.auditDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
		UNION ALL
			SELECT
				CONCAT(YEAR(c.auditDate),'.', MONTH(c.auditDate))  AS periodNo,
                YEAR(c.auditDate) AS periodYear,
                MONTH(c.auditDate) AS periodMonth,
				4 AS stockId,
				'��װ��ֿ�' AS stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
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
				0 AS balanceAmountNext
		FROM
			oa_stock_outstock c
		LEFT JOIN oa_stock_stockout_extraitem i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND (DATE_FORMAT(c.auditDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
		UNION ALL
			SELECT
				CONCAT(YEAR(c.auditDate),'.', MONTH(c.auditDate))  AS periodNo,
                YEAR(c.auditDate) AS periodYear,
                MONTH(c.auditDate) AS periodMonth,
				i.inStockId AS stockId,
				i.inStockName AS stockName,
				i.productName,
				i.pattern AS productModel,
				i.productCode AS productNo,
				f.ext2 AS k3Code,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				IF (c.isRed = 0,i.actNum,-i.actNum) AS inNum,
				0 AS inPrice,
				IF(
					i.isUnhookCal = 1,
					IF(c.isRed = 0,i.unhookCalAmount, -i.unhookCalAmount),
					IF(c.isRed = 0,i.subPrice, -i.subPrice)
				) AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				0 AS clearingNumNext,
				0 AS balanceAmountNext
		FROM
			oa_stock_instock c
		LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
		LEFT JOIN oa_stock_product_info f ON f.id = i.productId
		WHERE
			c.docStatus = 'YSH'
			AND (DATE_FORMAT(c.auditDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
		UNION ALL
			SELECT
				CONCAT(YEAR(c.formDate),'.', MONTH(c.formDate))  AS periodNo,
                YEAR(c.formDate) AS periodYear,
                MONTH(c.formDate) AS periodMonth,
				c.stockId,
				c.stockName,
				d.productName,
				d.productModel,
				d.productNo,
				f.ext2 AS k3Code,
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
				0 AS balanceAmountNext
			FROM
				oa_finance_costajust c
			LEFT JOIN oa_finance_costajust_detail d ON d.costajustId = c.id
			LEFT JOIN oa_stock_product_info f ON f.id = d.productId
			WHERE
                (DATE_FORMAT(c.formDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
        UNION ALL
            SELECT
                CONCAT(YEAR(c.formDate),'.', MONTH(c.formDate)) AS periodNo,
                YEAR(c.formDate) AS periodYear,
                MONTH(c.formDate) AS periodMonth,
				d.stockId,
				d.stockName,
				d.productName,
				f.pattern AS productModel,
				d.productNo,
				f.ext2 AS k3Code,
				0 AS clearingNum,
				0 AS price,
				0 AS balanceAmount,
				0 AS inNum,
				0 AS inPrice,
				allDiffer AS actInAmount,
				0 AS outNum,
				0 AS outPrice,
				0 AS actOutAmount,
				0 AS clearingNumNext,
				0 AS balanceAmountNext FROM oa_finance_adjustment_detail d INNER JOIN oa_finance_adjustment c ON c.id = d.adjustId
			LEFT JOIN oa_stock_product_info f ON f.id = d.productId
            WHERE
                (DATE_FORMAT(c.formDate,'%Y%m') BETWEEN $startPeriod AND $overPeriod)
		$allocation
	) total
WHERE
	total.stockId <> 0
AND total.productNo IS NOT NULL
$condition
GROUP BY
	total.periodNo,total.productNo
	$groupBy
ORDER BY
	total.periodNo,$orderBy total.productNo;
QuerySQL;
file_put_contents("d:sql.log", $QuerySQL);
GenAttrXmlData ( $QuerySQL, false );