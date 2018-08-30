<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$noInvoiceAmount =0;
$QuerySQL = <<<QuerySQL
SELECT
	MONTH (TIMESTAMP(c.docDate)) AS MONTH,
	SUM(c.saleAmount) AS saleTotalAmount,
        receiveAmount,
        (SUM(c.saleAmount) - receiveAmount) AS unpaidAmount,
        invoiceAmount,
        (SUM(c.saleAmount) - invoiceAmount) AS unbilledAmount,
        $noInvoiceAmount AS noInvoiceAmount        
FROM oa_service_accessorder c
LEFT JOIN ( SELECT i.objId, sum( IF ( i.isRed = 0, i.invoiceMoney ,- i.invoiceMoney )) AS invoiceAmount FROM oa_finance_invoice i GROUP BY i.objId ) i ON c.id = i.objId
LEFT JOIN ( SELECT a.objId, sum(a.money) AS receiveAmount FROM oa_finance_income_allot a GROUP BY a.objId ) a ON c.id = a.objId
WHERE c.docDate LIKE "$year%" GROUP BY month
QuerySQL;
//echo  $QuerySQL;
GenAttrXmlData($QuerySQL, false); 
?>

