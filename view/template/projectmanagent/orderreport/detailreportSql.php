<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
if($beginDT == "" && $endDT == ""){
	$beginDT = date("Y") . "-" . date("m") . "-01";//�¿�ʼ����
	$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, date("m"), date("y") ); //������ж�����
	$endDT = date("Y") . "-" . date("m") . "-" . $endYearMonthNum;//�½�������
}else{
	$beginDT = $_GET['beginDT'];
    $endDT = $_GET['endDT'];
}

$orderType = $_GET['orderType'];
$area = $_GET['area'];
$principal = $_GET['principal'];
$customerName = $_GET['customerName'];
$customerType = $_GET['customerType'];
$complete = $_GET['complete'];
$limit = "ͳ�Ʒ�Χ:";
//��ѯ����
$time = " and createTime between '$beginDT' and '$endDT'";
if($orderType !=''){
	  	$orderTypeArr = explode(",",$orderType);
        foreach($orderTypeArr as $k => $v){
                  $type .= "'$v',";
        }
        $type = rtrim($type,",");
		$orderTypeSql = " and orderType in($type)";
		$limit .= "  ��ͬ���ͣ�".$orderType."��";
}else { $orderTypeSql = "";}
if($area != ''){
	    $areaSql = " and areaName = '$area'";
        $limit .= "  ��ͬ��������".$area."��";
}else { $areaSql = "";}
if($principal !=''){
	    $principalSql = " and prinvipalName = '$principal'";
	    $limit .= "  ��ͬ�����ˣ�.$principal.��";
}else{  $principalSql = "";}
if($customerName != ''){
	    $customerNameSql = " and customerName = '.$customerName.'";
	    $limit .= "  �ͻ����ƣ�$customerName��";
}else{  $customerNameSql = "";}
if($customerType != ''){
	    $customerTypeSql = " and customerTypeCode = '.$customerName.'";
	    $limit .= "  �ͻ����ͣ�$customerName��";
}else{  $customerTypeSql = "";}

if($complete == "��"){
	$completeSql = " and state = '4'";
	$limit .= "  �Ƿ��깤���ǣ�";
}else if($complete == "��"){
	$completeSql = " and state <> 4 ";
	$limit .= "  �Ƿ��깤����";
}else{ $completeSql = ""; }

$whereSql = $orderTypeSql.$areaSql.$principalSql.$customerNameSql.$customerTypeSql.$completeSql.$time;

if($limit = "ͳ�Ʒ�Χ:"){
	$limit = "ͳ�Ʒ�Χ: ȫ��";
}
//echo $whereSql;
$QuerySQL = <<<QuerySQL
select * from (
select "$limit" as lim,"$beginDT" as beginDT,"$endDT" as endDT,o.orderId,o.Type,o.orderType,o.createTime,o.orderTempCode,o.orderCode,o.customerType,o.customerName,o.orderNatureName,o.orderTempMoney,o.orderMoney,
   o.completeY,o.completeN,o.state,o.prinvipalName,o.areaPrincipal,o.areaName,
i.invoiceMoney,i.incomeMoney,
c.allCarryMoney
 from
(
select  o.id as orderId,"oa_sale_order" as Type,"���ۺ�ͬ" as orderType,o.createTime as createTime,o.orderTempCode as orderTempCode,o.orderCode as orderCode,(select dataName from oa_system_datadict where dataCode = o.customerType) as customerType,o.customerType as customerTypeCode,o.customerName as customerName,o.orderNatureName as orderNatureName,o.orderTempMoney as orderTempMoney,o.orderMoney as orderMoney,
   (case o.state when '4' then '��'  else '' end) as completeY,(case o.state when '4' then ''  else '��' end) as completeN,o.state as state,o.prinvipalName as prinvipalName,o.areaPrincipal as areaPrincipal,o.areaName as areaName from oa_sale_order o where ExaStatus = '���' and isTemp = 0

   union all

   select  s.id as orderId,"oa_sale_service" as Type,"�����ͬ" as orderType,s.createTime as createTime,s.orderTempCode as orderTempCode,s.orderCode as orderCode,(select dataName from oa_system_datadict where dataCode = s.customerType) as customerType,s.customerType as customerTypeCode,s.cusName as customerName,s.orderNatureName as orderNatureName,s.orderTempMoney as orderTempMoney,s.orderMoney as orderMoney,
   (case s.state when '4' then '��'  else '' end) as completeY,(case s.state when '4' then ''  else '��' end) as completeN,s.state as state,s.orderPrincipal as prinvipalName,s.areaPrincipal as areaPrincipal,s.areaName as areaName from oa_sale_service s where ExaStatus = '���' and isTemp = 0

   union all

   select l.id as orderId,"oa_sale_lease" as Type,"���޺�ͬ" as orderType,l.createTime as createTime,l.orderTempCode as orderTempCode,l.orderCode as orderCode,(select dataName from oa_system_datadict where dataCode = l.customerType) as customerType,l.customerType as customerTypeCode,l.tenant as customerName,l.orderNatureName as orderNatureName,l.orderTempMoney as orderTempMoney,l.orderMoney as orderMoney,
   (case l.state when '4' then '��'  else '' end) as completeY,(case l.state when '4' then ''  else '��' end) as completeN,l.state as state,l.hiresName as prinvipalName,l.areaPrincipal as areaPrincipal,l.areaName as areaName from oa_sale_lease l where ExaStatus = '���' and isTemp = 0

   union all

   select r.id as orderId,"oa_sale_rdproject" as Type,"�з���ͬ" as orderType,r.createTime as createTime,r.orderTempCode as orderTempCode,r.orderCode as orderCode,(select dataName from oa_system_datadict where dataCode = r.customerType) as customerTyp,r.customerType as customerTypeCode,r.cusName as customerName,r.orderNatureName as orderNatureName,r.orderTempMoney as orderTempMoney,r.orderMoney as orderMoney,
   (case r.state when '4' then '��'  else '' end) as completeY,(case r.state when '4' then ''  else '��' end) as completeN,r.state as state,r.orderPrincipal as prinvipalName,r.areaPrincipal as areaPrincipal,r.areaName as areaName from oa_sale_rdproject r where ExaStatus = '���' and isTemp = 0
) o
	left join
		financeview_is_03_sumorder i on i.objId = o.orderId and CONVERT(i.orderObjType  USING GBK)= CONVERT(o.Type USING GBK)
	left join
		(
		select
			c.saleId ,c.saleType ,
			sum(round(( i.subCost * carryRate /100 ),2)) as allCarryMoney
		from
			oa_finance_carriedforward c left join
			(
			select
				(select SUM(oi.subCost) as subCost from oa_stock_outstock_item `oi` where oi.mainId=c.id GROUP by oi.mainId )as subCost,
					c.id ,c.docCode ,c.docType ,c.isRed ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.customerName
				from oa_stock_outstock c where 1=1 and c.docStatus = 'YSH' and c.docType = 'CKSALES'
			) i on c.outStockId = i.id where 1=1
		group by
			c.saleId,c.saleType
		) c on o.orderId = c.saleId and CONVERT(c.saleType  USING GBK)= CONVERT(o.Type USING GBK)
)sale where 1=1 $whereSql

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
