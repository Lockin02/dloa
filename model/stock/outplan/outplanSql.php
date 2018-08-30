<?php

/**
 * @author Administrator
 * @Date 2011年5月5日 21:20:43
 * @version 1.0
 * @description:发货通知单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.contractType,c.contractTypeName,c.reterStart,c.dongleRate,c.id ,c.planCode ,c.docType ,c.docId ,c.docCode ,c.docName ,c.week ,c.planIssuedDate ,c.stockId ,c.stockCode ," .
			"c.stockName ,c.type ,c.purConcern ,c.shipConcern ,c.issuedStatus ,c.docStatus ,c.shipPlanDate ,c.isShipped ,c.isOnTime ," .
			"c.delayType ,c.delayTypeCode ,c.delayDetailCode ,c.delayDetail ,c.delayReason ,c.ext1 ,c.ext2 ,c.ext3 ,c.customerName,c.customerId,c.createTime ,c.createName ,c.createId ," .
			"c.updateName ,c.updateTime ,c.updateId ,c.status,c.changeTips ,c.rObjCode ,c.isWarranty ,c.isWarrantyName ,c.isNeedConfirm from oa_stock_outplan c where 1=1 ",
	"select_allocation" => "select c.contractType,c.contractTypeName,c.reterStart,b.createName,b.createId,b.createSection,b.createSectionId,c.id ,c.planCode ,c.docType ,c.docId ," .
			"c.docCode ,c.docName ,c.week ,c.planIssuedDate ,c.stockId ,c.stockCode ,c.stockName ,c.type ," .
			"c.purConcern ,c.shipConcern ,c.issuedStatus ,c.docStatus ,c.shipPlanDate ,c.isShipped ,c.isOnTime ," .
			"c.delayType ,c.delayReason ,c.ext1 ,c.ext2 ,c.ext3 ,c.customerName,c.customerId,c.createTime ," .
			"c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.status,c.changeTips,c.rObjCode,c.isNeedConfirm from oa_stock_outplan c " .
			"left join oa_borrow_borrow b on c.docId=b.id where 1=1 and c.docType='oa_borrow_borrow' and (b.limits='客户')",
	"select_plan" => "select c.contractType,c.contractTypeName,c.reterStart,c.dongleRate,c.id ,c.planCode ,c.docType ,c.docId ,c.docCode ,c.docName ,c.week ,c.planIssuedDate ,c.stockId ,
	         		c.stockCode ,c.stockName ,c.type ,c.purConcern ,c.shipConcern ,c.issuedStatus ,c.docStatus ,c.shipPlanDate ,c.isShipped ,c.isOnTime ,
	         		c.delayType ,c.delayReason  ,c.customerName,c.customerId,c.createTime ,c.createName ,c.createId ,c.updateName ,
	         		c.updateTime ,c.updateId ,c.isTemp,c.status,c.docApplicant,c.docApplicantId,c.rObjCode ,c.isNeedConfirm,
					case c.docType
						when 'oa_sale_order' then (select distinct a.deliveryDate from oa_sale_order a where a.id=c.docId)
						when 'oa_sale_service' then (select distinct b.timeLimit from oa_sale_service b where b.id=c.docId)
						when 'oa_sale_lease' then (select distinct c.beginTime from oa_sale_lease c where c.id=c.docId)
						when 'oa_sale_rdproject' then (select distinct d.timeLimit from oa_sale_rdproject d where d.id=c.docId)
						when 'oa_borrow_borrow' then (select distinct e.deliveryDate from oa_borrow_borrow e where e.id=c.docId)
						when 'oa_present_present' then (select distinct e.deliveryDate from oa_present_present e where e.id=c.docId)
					else '' end as deliveryDate,c.changeTips from oa_stock_outplan c where 1=1 ",
		//         "select_equ"=>"select c.productId as id,c.productId,c.productNo,c.productName,sum(number) as number,sum(executedNum) as executedNum,sum(onWayNum) as onWayNum from contship_by_product c where 1=1 and c.ExaStatus='完成' and c.isShipments<>'否'",
	"select_equ" => "select c.productId as id,c.productId,c.productNo,c.productName,sum(number) as number,sum(executedNum) as executedNum,sum(onWayNum) as onWayNum,(select ifnull( sum(i.exeNum),0) as exeNum from oa_stock_inventory_info i
	 where  i.productId=c.productId and i.stockId=(select s.salesStockId from oa_stock_syteminfo s) ) as inventoryNum from contship_by_product c where 1=1 and c.ExaStatus='完成' and c.isShipments<>'否' ",
	"select_cont" => "select c.orgid,c.orderCode,c.orderTempCode,c.tablename,c.number,c.onWayNum,c.executedNum from contship_by_product c where 1=1 and c.ExaStatus='完成'",
	"select_contequ" => "select c.id,c.tablename,c.isDel,c.productId,c.unitName,c.productNo,c.productName,c.orderOrgid,c.orderId,c.orderCode,c.orderName,c.number,c.issuedShipNum,c.issuedPurNum,c.issuedProNum,c.executedNum from oa_shipequ_view c where 1=1 and c.number-c.issuedShipNum>0",
	"select_confirmList" => "select c.id,c.docId ,c.docCode,c.docName,c.planCode,c.shipPlanDate,c.overTimeReason,d.deliveryDate from oa_stock_outplan c left join oa_contract_contract d on c.docId = d.id "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "dongleRate",
		"sql" => " and c.dongleRate=#"
	),
	array (
		"name" => "planCode",
		"sql" => " and c.planCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array (
		"name" => "docTypeArr",
		"sql" => " and c.docType in(arr) "
	),
	array (
		"name" => "productNo",
		"sql" => " and c.productNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "docId",
		"sql" => " and c.docId=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "docName",
		"sql" => " and c.docName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "week",
		"sql" => " and c.week=# "
	),
	array (
		"name" => "planIssuedDate",
		"sql" => " and c.planIssuedDate=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "type",
		"sql" => " and c.type=# "
	),
	array (
		"name" => "purConcern",
		"sql" => " and c.purConcern=# "
	),
	array (
		"name" => "shipConcern",
		"sql" => " and c.shipConcern=# "
	),
	array (
		"name" => "issuedStatus",
		"sql" => " and c.issuedStatus=# "
	),
	array (
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array (
		"name" => "ndocStatus",
		"sql" => " and c.docStatus!=# "
	),
	array (
		"name" => "docStatusArr",
		"sql" => " and c.docStatus in(arr) "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "shipPlanDate",
		"sql" => " and c.shipPlanDate=# "
	),
	array (
		"name" => "isShipped",
		"sql" => " and c.isShipped=# "
	),
	array (
		"name" => "isOnTime",
		"sql" => " and c.isOnTime=# "
	),
	array (
		"name" => "delayTypeCode",
		"sql" => " and c.delayTypeCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "delayType",
		"sql" => " and c.delayType like CONCAT('%',#,'%') "
	),
	array (
		"name" => "delayDetailCode",
		"sql" => " and c.delayDetailCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "delayDetail",
		"sql" => " and c.delayDetail like CONCAT('%',#,'%') "
	),
	array (
		"name" => "delayReason",
		"sql" => " and c.delayReason=# "
	),
	array (
		"name" => "ext1",
		"sql" => " and c.ext1=# "
	),
	array (
		"name" => "ext2",
		"sql" => " and c.ext2=# "
	),
	array (
		"name" => "ext3",
		"sql" => " and c.ext3=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "createId1",
		"sql" => " and ( issuedStatus=1 or c.createId=# )"
	),
	/**************合同设备********************/
	array (
		"name" => "conttblname",
		"sql" => " and ( tablename=# )"
	),
	array (
		"name" => "orderOrgId",
		"sql" => " and ( orderOrgId=# )"
	),
	array (
		"name" => "contproId",
		"sql" => " and productId not in(arr) "
	),
	array (
		"name" => "changeTips",
		"sql" => " and c.changeTips =#"
	),
	array (
		"name" => "rObjCode",
		"sql" => " and c.rObjCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "isNeedConfirm",
		"sql" => " and c.isNeedConfirm =# "
	)
)
?>