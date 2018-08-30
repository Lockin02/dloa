<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginDate=$_GET['beginDate'];
// $logic=$_GET['logic'];
// $field=$_GET['field'];
// $relation=$_GET['relation'];
// $values=$_GET['values'];
$condition="";
//当期订单
if(!empty($beginDate)){
	$condition.= ' and p.planEndDate >= "'.$beginDate.'" ';
}
// //高级查询
// if(!empty($logic)){
// 	$logicArr=explode(',',$logic);
// 	$fieldArr=explode(',',$field);
// 	$relationArr=explode(',',$relation);
// 	$valuesArr=explode(',',$values);
// 	$fieldstr="";
// 	$relationstr="";
// 	$i=0;
// 	foreach($logicArr as $key=>$val){
// 		if(!empty($val)){
// 			if($fieldArr[$key]=="purchType"){//判断是否根据采购类型进行搜索
// 				if($relationArr[$key]=="equal"){
// 					switch($valuesArr[$key]){
// 						case "stock";$relationstr=" (p.purchType='oa_borrow_borrow') or (p.purchType='oa_present_present') or (p.purchType='stock') ";break;
// 						case "assets";$relationstr=" (p.purchType='assets') or (p.purchType='oa_asset_purchase_apply') ";break;
// 						case "oa_sale_order";$relationstr=" (p.purchType='oa_sale_order') or (p.purchType='HTLX-XSHT') ";break;
// 						case "oa_sale_lease";$relationstr=" (p.purchType='oa_sale_lease') or (p.purchType='HTLX-ZLHT') ";break;
// 						case "oa_sale_service";$relationstr=" (p.purchType='oa_sale_service') or (p.purchType='HTLX-FWHT') ";break;
// 						case "oa_sale_rdproject";$relationstr=" (p.purchType='oa_sale_rdproject') or (p.purchType='HTLX-YFHT') ";break;
// 						default: $relationstr=" p.purchType ='".$valuesArr[$key]."'";break;
// 					}
// 				}else{
// 					switch($valuesArr[$key]){
// 						case "stock";$relationstr=" (p.purchType!='oa_borrow_borrow') and (p.purchType!='oa_present_present') and (p.purchType!='stock') ";break;
// 						case "assets";$relationstr=" (p.purchType!='assets') and (p.purchType!='oa_asset_purchase_apply') ";break;
// 						case "oa_sale_order";$relationstr=" (p.purchType!='oa_sale_order') and (p.purchType!='HTLX-XSHT') ";break;
// 						case "oa_sale_lease";$relationstr=" (p.purchType!='oa_sale_lease') and (p.purchType!='HTLX-ZLHT') ";break;
// 						case "oa_sale_service";$relationstr=" (p.purchType!='oa_sale_service') and (p.purchType!='HTLX-FWHT') ";break;
// 						case "oa_sale_rdproject";$relationstr=" (p.purchType!='oa_sale_rdproject') and (p.purchType!='HTLX-YFHT') ";break;
// 						default: $relationstr=" p.purchType !='".$valuesArr[$key]."'";break;
// 					}
// 				}
// 			}else{
// 				switch($fieldArr[$key]){//判断查询字段
// 					case "suppName":$fieldstr="c.suppName ";break;
// 					case "createTime":$fieldstr="date_format(c.createTime,'%Y-%m-%d') ";break;
// 					case "hwapplyNumb":$fieldstr="c.hwapplyNumb ";break;
// 					case "productNumb":$fieldstr="p.productNumb ";break;
// 					case "productName":$fieldstr="p.productName ";break;
// 					case "sendName":$fieldstr="c.sendName ";break;
// 					case "moneyAll":$fieldstr="c.allMoney ";break;
// 					case "batchNumb":$fieldstr="p.batchNumb ";break;
// 					case "sourceNumb":$fieldstr="p.sourceNumb ";break;
// 	//				case "purchType":$fieldstr="p.purchType ";break;
// 					default: $fieldstr=" ";break;
// 				}
// 				switch($relationArr[$key]){//判断比较关系
// 					case "equal":$relationstr=" ='".$valuesArr[$key]."'";break;
// 					case "notequal":$relationstr=" !='".$valuesArr[$key]."'";break;
// 					case "greater":$relationstr=" >'".$valuesArr[$key]."'";break;
// 					case "less":$relationstr=" < '".$valuesArr[$key]."'";break;
// 					case "in":$relationstr=" like CONCAT('%','".$valuesArr[$key]."','%')";break;
// 					case "notin":$relationstr=" not like CONCAT('%','".$valuesArr[$key]."','%')";break;
// 				}
// 			}
// 			$condition .=" ".$val." ( ".$fieldstr.$relationstr.") ";
// 		}
// 		$i++;
// 	}
// }
$QuerySQL = <<<QuerySQL
SELECT
	p.weekly,
	p.planStartDate,
	p.planEndDate,
	a.applyDate,
	p.docCode,
	p.relDocType,
	p.customerName,
	p.relDocCode,
	p.productCode,
	p.productName,
	p.pattern,
	p.unitName,
	t.taskNum,
	p.planNum,
	p.stockNum,
	i.planEndDate as hopeDate,
	p.deliveryDate,
	CASE p.relDocTypeCode
		when 'HTLX-XSHT'  then  o.deliveryDate
		when 'HTLX-ZLHT'  then  o.deliveryDate
		when 'HTLX-FWHT'  then  o.deliveryDate
		when 'HTLX-YFHT'  then  o.deliveryDate
		else '' end
	as orderDelDate
FROM
	oa_produce_produceplan p
LEFT JOIN oa_produce_produceapply a
on p.applyDocId=a.id
LEFT JOIN oa_produce_produceapply_item i
on p.applyDocItemId=i.id
LEFT JOIN oa_produce_producetask t
on p.taskId=t.id
LEFT JOIN oa_contract_contract o ON o.id=p.relDocId
where 1=1  $condition
ORDER BY DATE_FORMAT(p.planEndDate,'%Y-%m'),p.weekly,p.planEndDate,p.productCode asc
QuerySQL;
//echo $QuerySQL;
set_time_limit(0);
GenAttrXmlData($QuerySQL, false);
?>
