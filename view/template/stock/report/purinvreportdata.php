<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET ['beginMonth'], $_GET ['beginYear']); //������ж�����
$monthBeginDate = $_GET ['beginYear'] . "-" . $_GET ['beginMonth'] . "-1"; //�¿�ʼ����
$endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_GET ['endMonth'], $_GET ['endYear']); //������ж�����
$monthEndDate = $_GET ['endYear'] . "-" . $_GET ['endMonth'] . "-" . $endYearMonthNum; //�½�������
$productId = $_GET ['productId'];
$supplierId = $_GET ['supplierId'];

$condition = " and t.hookDate BETWEEN '$monthBeginDate' and '$monthEndDate' ";

if (!empty ($productId)) {
    $condition .= " and t.productId =$productId ";
}
if (!empty ($supplierId)) {
    $condition .= " and t.supplierId =$supplierId ";
}
//echo $condition;


$QuerySQL = <<<QuerySQL
select * from (
SELECT
	i.objNo as fpNum,
	i.supplierId,
	i.supplierName,
	i.departments,
	i.departmentsId,
	i.salesman,
	i.salesmanId,
	d.id,
	d.invPurid,
	d.objId,
	d.objCode,
	d.objType,
	d.productId,
	d.productNo,
	d.productName,
	d.productModel,
	d.number,
	d.price,
	d.assessment,
	d.allCount as taxPrice,
	ist.auditerName,
	ist.auditDate,
	CASE i. STATUS
	WHEN '0' THEN
		'δ����'
	WHEN '1' THEN
		'�ѹ���'
	END AS hookStatus,
	 CASE i.ExaStatus
	WHEN '0' THEN
		'δ���'
	WHEN '1' THEN
		'�����'
	END AS ExaStatus,
	hd.hookDate,pi.ext2 AS k3Code
FROM
	oa_finance_invpurchase_detail d
LEFT JOIN oa_finance_invpurchase i ON d.invPurid = i.id
LEFT JOIN oa_stock_instock ist ON ist.id = d.objId
LEFT JOIN (
		select hookId,hookMainId,hookDate from oa_finance_related_detail where hookObj = 'invpurchase' 
) hd ON (hd.hookMainId = d.invPurid and hd.hookId = d.id)
LEFT JOIN (
	SELECT
		purchType,
		basicId
	FROM
		oa_purch_apply_equ
	GROUP BY
		basicId
) pe ON pe.basicId = i.purcontid
LEFT JOIN oa_stock_product_info pi ON d.productId = pi.id
where (
	(
		(
			pe.purchType <> 'oa_asset_purchase_apply'
			OR pe.purchType IS NULL
		)
	)
)
order by d.id)t where 1=1 $condition order by t.id;
QuerySQL;

//echo $QuerySQL;exit();
GenAttrXmlData($QuerySQL, false);