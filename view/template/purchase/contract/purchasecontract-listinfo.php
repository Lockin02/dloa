<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginDate=$_GET['beginDate'];
$logic=$_GET['logic'];
$field=$_GET['field'];
$relation=$_GET['relation'];
$values=$_GET['values'];
$condition="";
//当期订单
if(!empty($beginDate)){
//	$condition.= ' and c.createTime >= "'.$beginDate.'" ';
}
//高级查询
if(!empty($logic)){
	$logicArr=explode(',',$logic);
	$fieldArr=explode(',',$field);
	$relationArr=explode(',',$relation);
	$valuesArr=explode(',',$values);
	$fieldstr="";
	$relationstr="";
	$i=0;
	foreach($logicArr as $key=>$val){
		if(!empty($val)){
			if($fieldArr[$key]=="purchType"){//判断是否根据采购类型进行搜索
				if($relationArr[$key]=="equal"){
					switch($valuesArr[$key]){
						case "stock";$relationstr=" (p.purchType='oa_borrow_borrow') or (p.purchType='oa_present_present') or (p.purchType='stock') ";break;
						case "assets";$relationstr=" (p.purchType='assets') or (p.purchType='oa_asset_purchase_apply') ";break;
						case "oa_sale_order";$relationstr=" (p.purchType='oa_sale_order') or (p.purchType='HTLX-XSHT') ";break;
						case "oa_sale_lease";$relationstr=" (p.purchType='oa_sale_lease') or (p.purchType='HTLX-ZLHT') ";break;
						case "oa_sale_service";$relationstr=" (p.purchType='oa_sale_service') or (p.purchType='HTLX-FWHT') ";break;
						case "oa_sale_rdproject";$relationstr=" (p.purchType='oa_sale_rdproject') or (p.purchType='HTLX-YFHT') ";break;
						default: $relationstr=" p.purchType ='".$valuesArr[$key]."'";break;
					}
				}else{
					switch($valuesArr[$key]){
						case "stock";$relationstr=" (p.purchType!='oa_borrow_borrow') and (p.purchType!='oa_present_present') and (p.purchType!='stock') ";break;
						case "assets";$relationstr=" (p.purchType!='assets') and (p.purchType!='oa_asset_purchase_apply') ";break;
						case "oa_sale_order";$relationstr=" (p.purchType!='oa_sale_order') and (p.purchType!='HTLX-XSHT') ";break;
						case "oa_sale_lease";$relationstr=" (p.purchType!='oa_sale_lease') and (p.purchType!='HTLX-ZLHT') ";break;
						case "oa_sale_service";$relationstr=" (p.purchType!='oa_sale_service') and (p.purchType!='HTLX-FWHT') ";break;
						case "oa_sale_rdproject";$relationstr=" (p.purchType!='oa_sale_rdproject') and (p.purchType!='HTLX-YFHT') ";break;
						default: $relationstr=" p.purchType !='".$valuesArr[$key]."'";break;
					}
				}
			}else{
				switch($fieldArr[$key]){//判断查询字段
					case "suppName":$fieldstr="c.suppName ";break;
					case "createTime":$fieldstr="date_format(c.createTime,'%Y-%m-%d') ";break;
					case "hwapplyNumb":$fieldstr="c.hwapplyNumb ";break;
					case "productNumb":$fieldstr="p.productNumb ";break;
					case "productName":$fieldstr="p.productName ";break;
					case "sendName":$fieldstr="c.sendName ";break;
					case "moneyAll":$fieldstr="c.allMoney ";break;
					case "batchNumb":$fieldstr="p.batchNumb ";break;
					case "sourceNumb":$fieldstr="p.sourceNumb ";break;
	//				case "purchType":$fieldstr="p.purchType ";break;
					default: $fieldstr=" ";break;
				}
				switch($relationArr[$key]){//判断比较关系
					case "equal":$relationstr=" ='".$valuesArr[$key]."'";break;
					case "notequal":$relationstr=" !='".$valuesArr[$key]."'";break;
					case "greater":$relationstr=" >'".$valuesArr[$key]."'";break;
					case "less":$relationstr=" < '".$valuesArr[$key]."'";break;
					case "in":$relationstr=" like BINARY CONCAT('%','".$valuesArr[$key]."','%')";break;
					case "notin":$relationstr=" not like BINARY CONCAT('%','".$valuesArr[$key]."','%')";break;
				}
			}
			$condition .=" ".$val." ( ".$fieldstr.$relationstr.") ";
		}
		$i++;
	}
}
$QuerySQL = <<<QuerySQL
select c.id,c.suppId,c.suppName,date_format(c.createTime,'%Y-%m-%d') as createTime,c.hwapplyNumb,c.allMoney,p.id as Pid,p.productId,p.productNumb,p.productName,p.batchNumb,p.dateHope,
p.pattem,p.units,d.USER_NAME as Leader_name,c.sendUserId,c.sendName,c.createName,cast(p.amountAll as decimal(10,0)) as amountAll,max(stock.auditDate) as auditDate,p.price,p.taxRate,
cast((p.price*p.amountAll) as decimal(20,2)) as noTaxMoney,p.amountIssued,p.applyPrice,p.moneyAll,cast((p.moneyAll-p.price*p.amountAll) as decimal(20,2)) as tax,
concat(c.paymentConditionName,' ',c.payRatio) as payment,c.suppAddress,c.suppTel,max(sc.name) as name,max(sc.fax) as fax,if(fpd.money is null,0,fpd.money) as payedMoney,max(ae.arrivalDate) as arrivalDate,
fpd.payFormDate,tte.sendTime as taskTime,tte.planTime,
case p.purchType
	when 'oa_sale_order'  then '销售合同采购'
	when 'oa_sale_lease'  then '租赁合同采购'
	when 'oa_sale_service'  then '服务合同采购'
	when 'oa_sale_rdproject'  then '研发合同采购'
	when 'HTLX-XSHT'  then '销售合同采购'
	when 'HTLX-ZLHT'  then '租赁合同采购'
	when 'HTLX-FWHT'  then '服务合同采购'
	when 'HTLX-YFHT'  then '研发合同采购'
	when 'oa_borrow_borrow'  then '补库采购'
	when 'oa_present_present'  then '补库采购'
	when 'stock'  then '补库采购'
	when 'assets'  then '资产采购'
	when 'rdproject'  then '研发采购'
	when 'produce'  then '生产采购'
	when 'oa_asset_purchase_apply'  then '资产采购'
	else '' end
as purchType,
case p.purchType
	when 'oa_sale_order'  then  p.sourceNumb
	when  'oa_sale_lease'  then  p.sourceNumb
	when 'oa_sale_service'  then  p.sourceNumb
	when 'oa_sale_rdproject'  then  p.sourceNumb
	when 'HTLX-XSHT'  then  p.sourceNumb
	when 'HTLX-ZLHT'  then  p.sourceNumb
	when 'HTLX-FWHT'  then  p.sourceNumb
	when 'HTLX-YFHT'  then  p.sourceNumb
	else '' end
as sourceNumb
from oa_purch_apply_equ p
 left join oa_purch_apply_basic c  on c.id=p.basicId
  left join oa_purchase_arrival_equ ae on p.id=ae.contractId
		left join
			(select te.id,t.sendTime,plan.sendTime as planTime from oa_purch_task_equ te left join oa_purch_task_basic t on te.basicId=t.id left join oa_purch_plan_basic plan on plan.id=te.planId )tte on tte.id=p.taskEquId
     left join
     (select pd.expand1, pd.advancesId, max(formDate) as payFormDate,sum(if(fp.formType = 'CWYF-03',-pd.money,pd.money)) as money, fp.id from
      oa_finance_payables_detail pd left join oa_finance_payables fp on
       pd.advancesId = fp.id where pd.objType='YFRK-01'  group by pd.expand1) fpd on fpd.expand1 = p.id
left join  oa_supp_cont sc on sc.parentId=c.suppId
left join  oa_stock_instock  stock on (stock.purOrderId=c.id)
left join user u on c.sendUserId=u.USER_ID
left join area a  on u.area = a.id left join(select d.DEPT_ID,u.USER_NAME,
substring_index(if (d.MajorId is null or d.MajorId = '', d.ViceManager, d.MajorId), ',', 1)  as USER_ID from department d
left join user u on substring_index(if(d.MajorId is null or d.MajorId = '' ,d.ViceManager,d.MajorId),',',1) = u.USER_ID) d
on u.DEPT_ID = d.DEPT_ID
where c.isTemp =0  and p.amountAll>0 and ((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 8,10)))  $condition
group by p.id
order by c.suppName, createTime asc
QuerySQL;
//echo $QuerySQL;
file_put_contents("d:sql.log", $condition);
set_time_limit(0);
GenAttrXmlData($QuerySQL, false);