<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
//$beginDate = $_GET['beginDate'];
$logic = $_GET['logic'];
$field = $_GET['field'];
$relation = $_GET['relation'];
$values = $_GET['values'];
$condition = "";
//当期订单
//if (!empty($beginDate)) {
//    $condition .= ' and c.createTime >= "' . $beginDate . '" ';
//}
//高级查询
if (!empty($logic)) {
    $logicArr = explode(',', $logic);
    $fieldArr = explode(',', $field);
    $relationArr = explode(',', $relation);
    $valuesArr = explode(',', $values);
    $fieldstr = "";
    $relationstr = "";
    $i = 0;
    foreach ($logicArr as $key => $val) {
        if (!empty($val)) {
            if ($fieldArr[$key] == "purchType") {//判断是否根据采购类型进行搜索
                if ($relationArr[$key] == "equal") {
                    switch ($valuesArr[$key]) {
                        case "stock";
                            $relationstr = " (p.purchType='oa_borrow_borrow') or (p.purchType='oa_present_present') or (p.purchType='stock') ";
                            break;
                        case "assets";
                            $relationstr = " (p.purchType='assets') or (p.purchType='oa_asset_purchase_apply') ";
                            break;
                        case "oa_sale_order";
                            $relationstr = " (p.purchType='oa_sale_order') or (p.purchType='HTLX-XSHT') ";
                            break;
                        case "oa_sale_lease";
                            $relationstr = " (p.purchType='oa_sale_lease') or (p.purchType='HTLX-ZLHT') ";
                            break;
                        case "oa_sale_service";
                            $relationstr = " (p.purchType='oa_sale_service') or (p.purchType='HTLX-FWHT') ";
                            break;
                        case "oa_sale_rdproject";
                            $relationstr = " (p.purchType='oa_sale_rdproject') or (p.purchType='HTLX-YFHT') ";
                            break;
                        default:
                            $relationstr = " p.purchType ='" . $valuesArr[$key] . "'";
                            break;
                    }
                } else {
                    switch ($valuesArr[$key]) {
                        case "stock";
                            $relationstr = " (p.purchType!='oa_borrow_borrow') and (p.purchType!='oa_present_present') and (p.purchType!='stock') ";
                            break;
                        case "assets";
                            $relationstr = " (p.purchType!='assets') and (p.purchType!='oa_asset_purchase_apply') ";
                            break;
                        case "oa_sale_order";
                            $relationstr = " (p.purchType!='oa_sale_order') and (p.purchType!='HTLX-XSHT') ";
                            break;
                        case "oa_sale_lease";
                            $relationstr = " (p.purchType!='oa_sale_lease') and (p.purchType!='HTLX-ZLHT') ";
                            break;
                        case "oa_sale_service";
                            $relationstr = " (p.purchType!='oa_sale_service') and (p.purchType!='HTLX-FWHT') ";
                            break;
                        case "oa_sale_rdproject";
                            $relationstr = " (p.purchType!='oa_sale_rdproject') and (p.purchType!='HTLX-YFHT') ";
                            break;
                        default:
                            $relationstr = " p.purchType !='" . $valuesArr[$key] . "'";
                            break;
                    }
                }
            } else {
                switch ($fieldArr[$key]) {//判断查询字段
                    case "suppName":
                        $fieldstr = "c.suppName ";
                        break;
                    case "createTime":
                        $fieldstr = "date_format(c.createTime,'%Y-%m-%d') ";
                        break;
                    case "hwapplyNumb":
                        $fieldstr = "c.hwapplyNumb ";
                        break;
                    case "productNumb":
                        $fieldstr = "p.productNumb ";
                        break;
                    case "productName":
                        $fieldstr = "p.productName ";
                        break;
                    case "sendName":
                        $fieldstr = "c.sendName ";
                        break;
                    case "moneyAll":
                        $fieldstr = "c.allMoney ";
                        break;
                    case "batchNumb":
                        $fieldstr = "p.batchNumb ";
                        break;
                    case "sourceNumb":
                        $fieldstr = "p.sourceNumb ";
                        break;
                    //				case "purchType":$fieldstr="p.purchType ";break;
                    default:
                        $fieldstr = " ";
                        break;
                }
                switch ($relationArr[$key]) {//判断比较关系
                    case "equal":
                        $relationstr = " ='" . $valuesArr[$key] . "'";
                        break;
                    case "notequal":
                        $relationstr = " !='" . $valuesArr[$key] . "'";
                        break;
                    case "greater":
                        $relationstr = " >'" . $valuesArr[$key] . "'";
                        break;
                    case "less":
                        $relationstr = " < '" . $valuesArr[$key] . "'";
                        break;
                    case "in":
                        $relationstr = " like BINARY  CONCAT('%','" . $valuesArr[$key] . "','%')";
                        break;
                    case "notin":
                        $relationstr = " not like BINARY  CONCAT('%','" . $valuesArr[$key] . "','%')";
                        break;
                }
            }
            $condition .= " " . $val . " ( " . $fieldstr . $relationstr . ") ";
        }
        $i++;
    }
}
$QuerySQL = <<<QuerySQL
select t.*,SUM(t.fpSum_one) as fpSum from (
SELECT
	c.id,
	c.suppId,
	c.suppName,
	date_format(c.createTime, '%Y-%m-%d') AS createTime,
	c.hwapplyNumb,
	c.allMoney,
	c.ExaDT,
	p.id AS Pid,
	p.productId,
	p.productNumb,
	p.productName,
	p.batchNumb,
	p.dateIssued,
	p.batchNumb AS threeno,
	p.dateHope,
	p.dateEnd,
	p.pattem,
	p.units,
	c.sendUserId,
	c.sendName,
	c.createName,
	cast(p.amountAll AS DECIMAL(10, 0)) AS amountAll,
	p.price,
	p.taxRate,
	cast(
		(p.price * p.amountAll) AS DECIMAL (20, 2)
	) AS noTaxMoney,
	p.amountIssued,
	p.applyPrice,
	p.moneyAll,
	cast(
		(
			p.moneyAll - p.price * p.amountAll
		) AS DECIMAL (20, 2)
	) AS tax,
	concat(
		c.paymentConditionName,
		' ',
		c.payRatio
	) AS payment,
	c.suppAddress,
	sc.suppTel,
	sc.NAME,
	sc.fax,
	s.busiCode as suppCode,

IF (
	fpd.money IS NULL,
	0,
	fpd.money
) AS payedMoney,
 oae.arrivalDate,
 fpd.payFormDate,
 paa.payAuditDate,
 tte.sendTime AS taskTime,
 tte.planTime,
 tte.dateReceive,
 oae.arrivalNum,
 fip.fpSum_one,
 CASE p.purchType
WHEN 'oa_sale_order' THEN
	'销售合同采购'
WHEN 'oa_sale_lease' THEN
	'租赁合同采购'
WHEN 'oa_sale_service' THEN
	'服务合同采购'
WHEN 'oa_sale_rdproject' THEN
	'研发合同采购'
WHEN 'HTLX-XSHT' THEN
	'销售合同采购'
WHEN 'HTLX-ZLHT' THEN
	'租赁合同采购'
WHEN 'HTLX-FWHT' THEN
	'服务合同采购'
WHEN 'HTLX-YFHT' THEN
	'研发合同采购'
WHEN 'oa_borrow_borrow' THEN
	'补库采购'
WHEN 'oa_present_present' THEN
	'补库采购'
WHEN 'stock' THEN
	'补库采购'
WHEN 'assets' THEN
	'资产采购'
WHEN 'rdproject' THEN
	'研发采购'
WHEN 'produce' THEN
	'生产采购'
WHEN 'oa_asset_purchase_apply' THEN
	'资产采购'
ELSE
	''
END AS purchType,
 CASE p.purchType
WHEN 'oa_sale_order' THEN
	p.sourceNumb
WHEN 'oa_sale_lease' THEN
	p.sourceNumb
WHEN 'oa_sale_service' THEN
	p.sourceNumb
WHEN 'oa_sale_rdproject' THEN
	p.sourceNumb
WHEN 'HTLX-XSHT' THEN
	p.sourceNumb
WHEN 'HTLX-ZLHT' THEN
	p.sourceNumb
WHEN 'HTLX-FWHT' THEN
	p.sourceNumb
WHEN 'HTLX-YFHT' THEN
	p.sourceNumb
ELSE
	''
END AS sourceNumb,
 CASE p.purchType
WHEN 'oa_sale_order' THEN
	p.sourceNumb
WHEN 'oa_sale_lease' THEN
	p.sourceNumb
WHEN 'oa_sale_service' THEN
	p.sourceNumb
WHEN 'oa_sale_rdproject' THEN
	p.sourceNumb
WHEN 'HTLX-XSHT' THEN
	p.sourceNumb
WHEN 'HTLX-ZLHT' THEN
	p.sourceNumb
WHEN 'HTLX-FWHT' THEN
	p.sourceNumb
WHEN 'HTLX-YFHT' THEN
	p.sourceNumb
WHEN 'stock' THEN
	p.sourceNumb
WHEN 'produce' THEN
	p.batchNumb
WHEN 'oa_borrow_borrow' THEN
	p.sourceNumb
WHEN 'oa_present_present' THEN
	p.sourceNumb
ELSE
	''
END AS applySourceNumb,
 max(stock.auditDate) AS auditDate,
 CASE c.paymentCondition
WHEN 'YFK' THEN
	c.ExaDT
WHEN 'MJFK' THEN
	c.ExaDT
WHEN 'MJFK2' THEN
	c.ExaDT
WHEN 'MJFK3' THEN
	c.ExaDT
WHEN 'HDFK' THEN
	date_add(
		max(stock.auditDate),
		INTERVAL 7 DAY
	)
WHEN 'HD15T' THEN
	date_add(
		max(stock.auditDate),
		INTERVAL 15 DAY
	)
WHEN 'YJ' THEN
	date_add(
		max(stock.auditDate),
		INTERVAL 30 DAY
	)
ELSE
	''
END AS orderPayDate
FROM
	oa_purch_apply_equ p
LEFT JOIN (
	SELECT
		sum(ae.arrivalNum) AS arrivalNum,
		ae.contractId,
		max(ae.arrivalDate) AS arrivalDate
	FROM
		oa_purchase_arrival_equ ae
	GROUP BY ae.contractId
) oae ON oae.contractId = p.id
LEFT JOIN oa_purch_apply_basic c ON c.id = p.basicId
LEFT JOIN (
	SELECT
		te.id,
		t.sendTime,
		t.dateReceive,
		plan.sendTime AS planTime
	FROM
		oa_purch_task_equ te
	LEFT JOIN oa_purch_task_basic t ON te.basicId = t.id
	LEFT JOIN oa_purch_plan_basic plan ON plan.id = te.planId
) tte ON tte.id = p.taskEquId
LEFT JOIN (
	SELECT
		pad.expand1,
		max(pa.auditDate) AS payAuditDate
	FROM
		oa_finance_payablesapply_detail pad
	LEFT JOIN oa_finance_payablesapply pa ON pa.id = pad.payapplyId
	WHERE
		pad.objType = 'YFRK-01'
	GROUP BY
		pad.expand1
) paa ON paa.expand1 = p.id
LEFT JOIN (
	SELECT
		pd.expand1,
		pd.advancesId,
		max(formDate) AS payFormDate,
		sum(

			IF (
				fp.formType = 'CWYF-03' ,- pd.money,
				pd.money
			)
		) AS money,
		fp.id
	FROM
		oa_finance_payables_detail pd
	LEFT JOIN oa_finance_payables fp ON pd.advancesId = fp.id
	WHERE
		pd.objType = 'YFRK-01'
	GROUP BY
		pd.expand1
) fpd ON fpd.expand1 = p.id
LEFT JOIN (
	SELECT
		aae.id,
		aae.contractId,
		SUM(fid.allCount) AS fpSum_one
	FROM
		oa_finance_invpurchase_detail fid
	LEFT JOIN oa_finance_invpurchase fi ON fid.invPurId = fi.id
	LEFT JOIN oa_stock_instock_item sti ON sti.id = fid.expand1
	LEFT JOIN oa_purchase_arrival_equ aae ON aae.id = sti.relDocId
	GROUP BY
		fid.expand1
) fip ON fip.contractId = p.id
LEFT JOIN (
	SELECT
		co.parentId,
		GROUP_CONCAT(IF(co.mobile1 <> '',co.mobile1,NULL)) AS suppTel,
		GROUP_CONCAT(IF(co.NAME <> '',co.NAME,NULL)) AS NAME,
		GROUP_CONCAT(IF(co.fax <> '',co.fax,NULL)) AS fax
	FROM
		oa_supp_cont co
	GROUP BY
		co.parentId
)sc ON sc.parentId = c.suppId
left join oa_supp_lib s on s.id=c.suppId
LEFT JOIN oa_stock_instock stock ON (stock.purOrderId = c.id)
WHERE
	c.isTemp = 0
AND p.amountAll > 0
AND (
	(
		c.state IN (4, 7)
		AND c.ExaStatus = '完成'
	)
	OR (c.state IN(5, 8, 10))
)
$condition
GROUP BY
	p.id,fip.id
)t
group by t.Pid
ORDER BY
	t.suppName,
	t.createTime ASC;
QuerySQL;
// echo $QuerySQL;
file_put_contents("e:sql.log", $QuerySQL);
set_time_limit(0);
GenAttrXmlData($QuerySQL, false);
