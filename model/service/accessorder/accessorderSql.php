<?php
/**
 * @author huangzf
 * @Date 2011年11月27日 15:50:15
 * @version 1.0
 * @description:零配件订单 sql配置文件 
 */
$sql_arr = array (
        "select_default"=>"select c.id ,c.docCode ,c.docDate ,c.customerId ,c.customerName ,c.contactUserId ,c.contactUserName ,c.telephone ,c.adress ,c.chargeUserCode ,c.chargeUserName ,c.ExaStatus ,c.ExaDT ,c.saleAmount ,c.docStatus ,c.areaLeaderCode ,c.areaLeaderName ,c.areaId ,c.areaName ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.isBill,c.deliveryCondition  from oa_service_accessorder c where 1=1 ",
		"select_list"=>"select c.id ,c.docCode ,c.docDate ,c.customerId ,c.customerName ,c.contactUserId ,c.contactUserName ,
						c.telephone ,c.adress ,c.chargeUserCode ,c.chargeUserName ,c.ExaStatus ,c.ExaDT ,c.saleAmount ,c.docStatus ,
						c.areaLeaderCode ,c.areaLeaderName ,c.areaId ,c.areaName ,c.remark ,
						c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.isBill,c.deliveryCondition,ifnull(s.invoiceMoney,0) as  invoiceMoney 
						from oa_service_accessorder c 
						left JOIN
						(
							select i.objId,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney 
													 from financeview_invoice i where i.objId <> 0 and i.objType = 'oa_service_accessorder' group by i.objId,i.objType	
						)s on(s.objId=c.id)
						where 1=1",
		"select_notincome"=>"select o.docCode,o.docDate,o.customerName,o.telephone,o.contactUserName,o.chargeUserName,o.saleAmount,
										o.remark,ifnull(s.incomeMoney,0) as incomeMoney from oa_service_accessorder o  
									  			left join (select c.objId,c.objType,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
														from financeView_income_allot c left join oa_finance_income i on c.incomeId = i.id 
															where c.objId <> 0 and c.objType = 'oa_service_accessorder' group by c.objId,c.objType)s on(s.objId=o.id)
							where (o.saleAmount-ifnull(s.incomeMoney,0))>0"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode=# "
   	  ),
   array(
   		"name" => "docDate",
   		"sql" => " and c.docDate=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contactUserId",
   		"sql" => " and c.contactUserId=# "
   	  ),
   array(
   		"name" => "contactUserName",
   		"sql" => " and c.contactUserName=# "
   	  ),
   array(
   		"name" => "telephone",
   		"sql" => " and c.telephone=# "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "chargeUserCode",
   		"sql" => " and c.chargeUserCode=# "
   	  ),
   array(
   		"name" => "chargeUserName",
   		"sql" => " and c.chargeUserName=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "auditerUserCode",
   		"sql" => " and c.auditerUserCode=# "
   	  ),
   array(
   		"name" => "auditerUserName",
   		"sql" => " and c.auditerUserName=# "
   	  ),
   array(
   		"name" => "saleAmount",
   		"sql" => " and c.saleAmount=# "
   	  ),
   array(
   		"name" => "docStatus",
   		"sql" => " and c.docStatus=# "
   	  ),
   array(
   		"name" => "areaLeaderCode",
   		"sql" => " and c.areaLeaderCode=# "
   	  ),
   array(
   		"name" => "areaLeaderName",
   		"sql" => " and c.areaLeaderName=# "
   	  ),
   array(
   		"name" => "areaId",
   		"sql" => " and c.areaId=# "
   	  ),
   array(
   		"name" => "areaName",
   		"sql" => " and c.areaName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.id in(select oi.mainId from oa_service_accessorder_item oi where oi.productCode like CONCAT('%',#,'%'))"
   	  ),
  array(
   		"name" => "productName",
   		"sql" => " and c.id in(select oi.mainId from oa_service_accessorder_item oi where oi.productName like CONCAT('%',#,'%'))"
   	  )
)
?>