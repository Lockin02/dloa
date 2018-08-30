<?php
/**
 * @author LiuBo
 * @Date 2011年3月3日 19:08:54
 * @version 1.0
 * @description:订单主表 sql配置文件 项目主表
 */
$sql_arr = array (
     "select_default"=>"select c.id,c.sign,c.orderstate,c.orderCode,c.orderTempCode,c.rateOfGross,c.gross,c.orderName ,c.orderMoney,c.orderTempMoney,c.invoiceType ,c.deliveryDate,c.prinvipalId ,c.prinvipalName ,c.customerId ,c.customerName ,c.customerType ,c.address ,c.state ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ," .
     		           "c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.saleman,c.signIn,c.signName,c.signNameId,c.signDate,c.signDetail,c.signRemark,c.isTemp,c.originalId,c.orderNature,c.orderNatureName,c.areaName,c.areaCode,c.areaPrincipal,c.areaPrincipalId,c.remark,c.rate,c.currency,c.orderTempMoneyCur,c.orderMoneyCur,c.isBecome," .
     		           "c.shipCondition,c.objCode,c.orderProvince,c.orderProvinceId,c.orderCity,c.orderCityId  from oa_sale_order c where c.isTemp=0 and 1=1 ",

     "select_sales"=>"select e.id,c.sign,c.orderstate,e.projectId,e.projectCode,e.contractNumber,e.contractId, s.contNumber,s.contName,s.customerName,s.customerContNum,s.signIns.principalName,s.id as contId from oa_sale_relateinfo e inner join oa_contract_sales s on e.contractId=s.id where 1=1",
     "select_shipments"=>"
			select c.createName ,
				c.number,c.objCode,c.customerName,c.isTemp,c.sign,c.orderstate,c.parentOrder,c.orderCode,c.orderTempCode,c.orderName,
				c.state,c.ExaStatus,c.DeliveryStatus,c.tablename,c.id,c.orgid,c.ExaDT,c.isBecome,c.shipCondition,c.countNum,c.issuedShipNum,c.deliveryDate,
				case when c.countNum<=0 then 'YXD'
					when c.countNum>0 and c.issuedShipNum<=0 then 'WXD'
					when c.countNum>0 and c.issuedShipNum>0 then 'BFXD'
				 end as issuedStatus
			from
				(
				 select
					o.createName ,o.objCode,o.customerName,o.isTemp,o.sign,o.orderstate,o.parentOrder,o.orderCode,o.orderTempCode,o.orderName,o.state,
					o.ExaStatus,o.DeliveryStatus,o.tablename,o.id,o.orgid,o.ExaDT,o.isBecome,o.shipCondition,o.deliveryDate,
					e.countNum,e.number,e.issuedShipNum
				from
					shipments_oa_order o inner join

				(select
					e.orderId,sum(if(e.number-e.issuedShipNum>0,1,0)) as countNum,sum(1) as number,sum(issuedShipNum)  as issuedShipNum
				from
					oa_shipment_equ_view e
				where
					 e.isTemp=0 and e.isDel=0
				group by e.orderId
				having  number>0 ) e
					on o.id =  e.orderId
				) c where 1=1 ",
     "select_orderinfo"=>"select
                            c.isTemp,c.sign,c.badMoney,c.orderstate,c.parentOrder,c.orderCode,c.orderTempCode,c.orderName,c.ExaStatus,c.state,c.tablename,c.id,c.orgid,c.createTime,c.areaName,c.areaCode,c.createId,c.areaPrincipalId,c.areaPrincipal,c.prinvipalId,c.customerName,c.customerId,c.ExaDT,c.prinvipalName,c.customerProvince,c.orderProvince,c.orderCity,c.customerType,c.isBecome,c.shipCondition,c.signIn,c.objCode,c.orderNatureName,c.orderNature,c.signinType,
	                       	c.serviceconfirmMoneyAll,c.financeconfirmMoneyAll,c.deductMoney,if(c.serviceconfirmMoneyAll is null or c.serviceconfirmMoneyAll='' or c.serviceconfirmMoneyAll='0.00','1','2') as grossA,
	                       c.orderTempMoney,c.orderMoney,i.invoiceMoney,if((c.orderMoney='' or c.orderMoney='0.00' or c.orderMoney is null),c.orderTempMoney-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney)),c.orderMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney))) as surplusInvoiceMoney,
	                       n.incomeMoney,((if(c.orderMoney is null or c.orderMoney='',0,c.orderMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney))) as surOrderMoney,((if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney))) as surincomeMoney,
	                       i.hardMoney,i.softMoney,i.serviceMoney,i.repairMoney,u.USER_ID,u.USER_NAME,u.DEPT_ID,u.jobs_id,
	                       pro.budgetAll,pro.budgetOutsourcing,pro.feeFieldCount,pro.feeOutsourcing,pro.feeAll,c.ExaDTOne,
                        	if(c.serviceconfirmMoneyAll is null or c.serviceconfirmMoneyAll='' or c.serviceconfirmMoneyAll='0.00','-',c.gross) as gross1,
                            if(c.serviceconfirmMoneyAll is null or c.serviceconfirmMoneyAll='' or c.serviceconfirmMoneyAll='0.00','-',c.rateOfGross) as rateOfGross1,c.projectProcess,c.processMoney,
					       if(c.signinType='service' or c.state != 4,'-',c.completeDate) as completeDate,
					       if(c.signinType='service' or c.state != 4,'-',c.completeDate),c.DeliveryStatus,
					       if(c.signinType='service' ,'-',if(c.state=4 or c.state=3,TO_DAYS(c.completeDate)-TO_DAYS(if(c.ExaDTOne is null or c.c.ExaDTOne = '' or c.ExaDTOne='0000-00-00',c.createTime,c.ExaDTOne)),TO_DAYS(now())-TO_DAYS(if(c.ExaDTOne is null or c.ExaDTOne = '' or c.ExaDTOne='0000-00-00',c.createTime,c.c.ExaDTOne)))) as exeDate,
					       c.invoiceDifference,c.AffirmincomeDifference,
					       if(c.signinType='service',(if(c.processMoney is null,'0.00',c.processMoney)-if(i.invoiceMoney is null,'0.00',i.invoiceMoney))/if(c.c.processMoney is null,'0.00',c.processMoney),'-'),
					       if(c.signinType='service',(if(c.processMoney is null,'0.00',c.processMoney)-if(i.invoiceMoney is null,'0.00',i.invoiceMoney))/if(c.c.processMoney is null,'0.00',c.processMoney),'-') as invoiceDifferenceTemp,
					       if(c.invoiceDifference is null or c.invoiceDifference='' or c.invoiceDifference='0.00',1,2) as invoiceDifferenceA,
                           if(c.AffirmincomeDifference is null or c.AffirmincomeDifference='' or c.AffirmincomeDifference='0.00',1,2) as AffirmincomeDifferenceA
					        from view_oa_order c
							left join
							(
							select
							i.objId,i.objType,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney,
							sum(if(i.isRed = 0,softMoney,-i.softMoney)) as softMoney,sum(if(i.isRed = 0,i.hardMoney,-i.hardMoney)) as hardMoney,
							sum(if(i.isRed = 0,i.repairMoney,-i.repairMoney)) as repairMoney,sum(if(i.isRed = 0,i.serviceMoney,-i.serviceMoney)) as serviceMoney
							 from financeview_invoice i where i.objId <> 0 group by i.objId,i.objType
							) i on c.orgId = i.objId and c.tablename = i.objType
							left join
							(
							select
							c.objId,c.objType,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
							from financeView_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId <> 0 group by c.objId,c.objType
							) n on c.orgId = n.objId and c.tablename = n.objType
							left join
							user u
							on c.prinvipalId = u.USER_ID
							                            left join
							                             (
							                              select
							c.rObjCode,sum(c.budgetAll) as budgetAll,
							sum(c.budgetOutsourcing) as budgetOutsourcing,
							  sum(if(l.feeFieldCount is null ,0,l.feeFieldCount)+if(c.feePayables is null,0,c.feePayables)) as feeFieldCount,
							sum(c.feeOutsourcing) as feeOutsourcing,
							sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + c.feeOther + c.feeOutsourcing) as feeAll,
							round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
							from
							oa_esm_project c LEFT JOIN (
							SELECT
							sum(l.Amount) AS feeFieldCount, l.projectNo
							FROM
							cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
							) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
							where c.rObjCode <> '' and c.rObjCode is not null
							GROUP BY c.rObjCode
                              )pro on if(c.objCode is null or c.objCode  <> '' ,c.objCode = pro.rObjCode,1!=1) where 1=1

							",
	 "select_orderinfo_sumMoney"=>"
							select 'allMoney' as id,sum(orderMoney) as orderMoney,sum(if(orderMoney is null or orderMoney = '',orderTempMoney,0)) as orderTempMoney,sum(invoiceMoney) as invoiceMoney,sum(n.incomeMoney) as incomeMoney,sum(softMoney) as softMoney,sum(hardMoney) as hardMoney,sum(repairMoney) as repairMoney,sum(serviceMoney) as serviceMoney,
									sum(if((c.orderMoney='' or c.orderMoney='0.00' or c.orderMoney is null),c.orderTempMoney-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney)),c.orderMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney)))) as surplusInvoiceMoney,
                                    sum(badMoney) as badMoney,sum(i.invoiceMoney) as invoiceMoney,sum(n.incomeMoney) as incomeMoney,sum(c.serviceconfirmMoneyAll) as serviceconfirmMoneyAll,sum(c.financeconfirmMoneyAll) as financeconfirmMoneyAll,
	                                sum(c.deductMoney) as deductMoney,sum(c.processMoney) as processMoney,
                                    sum(((if(c.orderMoney is null or c.orderMoney='',0,c.orderMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney)))) as surOrderMoney,
                                    sum(((if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney)))) as surincomeMoney,
                                   	sum(pro.budgetAll) as budgetAll,sum(pro.budgetOutsourcing) as budgetOutsourcing,sum(pro.feeFieldCount) as feeFieldCount,sum(pro.feeOutsourcing) as feeOutsourcing,sum(feeAll) as feeAll
										from
							view_oa_order c
						    left join
							(
							select
							i.objId,i.objType,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney,
							sum(if(i.isRed = 0,softMoney,-i.softMoney)) as softMoney,sum(if(i.isRed = 0,i.hardMoney,-i.hardMoney)) as hardMoney,
							sum(if(i.isRed = 0,i.repairMoney,-i.repairMoney)) as repairMoney,sum(if(i.isRed = 0,i.serviceMoney,-i.serviceMoney)) as serviceMoney
							 from financeview_invoice i where i.objId <> 0 group by i.objId,i.objType
							) i on c.orgId = i.objId and c.tablename = i.objType
							left join
							(
							select
							c.objId,c.objType,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
							from financeView_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId <> 0 group by c.objId,c.objType
							) n on c.orgId = n.objId and c.tablename = n.objType
							left join
							user u
							on c.prinvipalId = u.USER_ID
                            left join
                             (
                             	select
									c.rObjCode,sum(c.budgetAll) as budgetAll,
									sum(c.budgetOutsourcing) as budgetOutsourcing,
								  sum(if(l.feeFieldCount is null ,0,l.feeFieldCount)+if(c.feePayables is null,0,c.feePayables)) as feeFieldCount,
									sum(c.feeOutsourcing) as feeOutsourcing,
									sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + c.feeOther + c.feeOutsourcing) as feeAll,
									round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
								from
									oa_esm_project c LEFT JOIN (
										SELECT
											sum(l.Amount) AS feeFieldCount, l.projectNo
										FROM
											cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
									) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
									where c.rObjCode <> '' and c.rObjCode is not null
								GROUP BY c.rObjCode
                              )pro on if(c.objCode is null or c.objCode  <> '' ,c.objCode = pro.rObjCode,1!=1)  where 1=1
							",
	"select_orderinfo_orderMoney"=>"
							select 'allMoney' as id,sum(orderMoney) as orderMoney,sum(invoiceMoney) as invoiceMoney,sum(incomeMoney) as incomeMoney,sum(softMoney) as softMoney,sum(hardMoney) as hardMoney,sum(repairMoney) as repairMoney,sum(serviceMoney) as serviceMoney,
							sum(if((c.orderMoney='' or c.orderMoney='0.00' or c.orderMoney is null),c.orderTempMoney-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney)),c.orderMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney)))) as surplusInvoiceMoney,
                                    sum(badMoney) as badMoney,sum(i.invoiceMoney) as invoiceMoney,sum(n.incomeMoney) as incomeMoney,sum(c.serviceconfirmMoneyAll) as serviceconfirmMoneyAll,sum(c.financeconfirmMoneyAll) as financeconfirmMoneyAll,
                                    sum((c.serviceconfirmMoney +if(f.serviceconfirmMoney is null or f.serviceconfirmMoney='',0,f.serviceconfirmMoney) )) as serviceconfirmMoney,
	                                sum((c.financeconfirmMoney +if(f.financeconfirmMoney is null or f.financeconfirmMoney='',0,f.financeconfirmMoney))) as financeconfirmMoney,
	                                sum((c.deductMoney +if(f.deductMoney is null or f.deductMoney='',0,f.deductMoney))) as deductMoney,sum(c.processMoney) as processMoney,
                                    sum(((if(c.orderMoney is null or c.orderMoney='',0,c.orderMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney)))) as surOrderMoney,
                                    sum(((if(i.invoiceMoney is null or i.invoiceMoney='',0,i.invoiceMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(n.incomeMoney is null or n.incomeMoney='',0,n.incomeMoney)))) as surincomeMoney,
                                   	sum(pro.budgetAll) as budgetAll,sum(pro.budgetOutsourcing) as budgetOutsourcing,sum(pro.feeFieldCount) as feeFieldCount,sum(pro.feeOutsourcing) as feeOutsourcing,sum(feeAll) as feeAll
									 from
							view_oa_order c
							left join (
							   SELECT
							   contractId,
							   contractType,
								sum(if(moneyType = 'serviceconfirmMoney',moneyNum,0)) as  serviceconfirmMoney,
								sum(if(moneyType = 'financeconfirmMoney',moneyNum,0)) as  financeconfirmMoney,
							  sum(if(moneyType = 'deductMoney',moneyNum,0)) as  deductMoney
							FROM
							   oa_contract_finalceMoney
							WHERE
								isUse = 0
							group by contractId,contractType
							)f  on c.orgid = f.contractId and c.tablename=f.contractType
							left join
							(
							select
							i.objId,i.objType,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney,
							sum(if(i.isRed = 0,softMoney,-i.softMoney)) as softMoney,sum(if(i.isRed = 0,i.hardMoney,-i.hardMoney)) as hardMoney,
							sum(if(i.isRed = 0,i.repairMoney,-i.repairMoney)) as repairMoney,sum(if(i.isRed = 0,i.serviceMoney,-i.serviceMoney)) as serviceMoney
							 from financeview_invoice i where i.objId <> 0 group by i.objId,i.objType
							) i on c.orgId = i.objId and c.tablename = i.objType
							left join
							(
							select
							c.objId,c.objType,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
							from financeView_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId <> 0 group by c.objId,c.objType
							) n on c.orgId = n.objId and c.tablename = n.objType left join
							user u
							on c.prinvipalId = u.USER_ID
							left join
                             (
                             	select
									c.rObjCode,sum(c.budgetAll) as budgetAll,
									sum(c.budgetOutsourcing) as budgetOutsourcing,
								  sum(if(l.feeFieldCount is null ,0,l.feeFieldCount)+if(c.feePayables is null,0,c.feePayables)) as feeFieldCount,
									sum(c.feeOutsourcing) as feeOutsourcing,
									sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + c.feeOther + c.feeOutsourcing) as feeAll,
									round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
								from
									oa_esm_project c LEFT JOIN (
										SELECT
											sum(l.Amount) AS feeFieldCount, l.projectNo
										FROM
											cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
									) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
									where c.rObjCode <> '' and c.rObjCode is not null
								GROUP BY c.rObjCode
                              )pro on if(c.objCode is null or c.objCode  <> '' ,c.objCode = pro.rObjCode,1!=1) where 1=1
							",
	"select_orderinfo_orderTempMoney"=>"
							select 'allMoney' as id,sum(if(orderMoney is null or orderMoney = '',orderTempMoney,0)) as orderTempMoney
							 		from
							view_oa_order c left join
							(
							select
							i.objId,i.objType,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney,
							sum(if(i.isRed = 0,softMoney,-i.softMoney)) as softMoney,sum(if(i.isRed = 0,i.hardMoney,-i.hardMoney)) as hardMoney,
							sum(if(i.isRed = 0,i.repairMoney,-i.repairMoney)) as repairMoney,sum(if(i.isRed = 0,i.serviceMoney,-i.serviceMoney)) as serviceMoney
							 from financeview_invoice i where i.objId <> 0 group by i.objId,i.objType
							) i on c.orgId = i.objId and c.tablename = i.objType
							left join
							(
							select
							c.objId,c.objType,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
							from financeView_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId <> 0 group by c.objId,c.objType
							) n on c.orgId = n.objId and c.tablename = n.objType left join
							user u
							on c.prinvipalId = u.USER_ID
                             left join
                             (
                             	select
									c.rObjCode,sum(c.budgetAll) as budgetAll,
									sum(c.budgetOutsourcing) as budgetOutsourcing,
								  sum(if(l.feeFieldCount is null ,0,l.feeFieldCount)) as feeFieldCount,
									sum(c.feeOutsourcing) as feeOutsourcing,
									sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + c.feeOther + c.feeOutsourcing) as feeAll,
									round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
								from
									oa_esm_project c LEFT JOIN (
										SELECT
											sum(l.Amount) AS feeFieldCount, l.projectNo
										FROM
											cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
									) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
									where c.rObjCode <> '' and c.rObjCode is not null
								GROUP BY c.rObjCode
                              )pro on if(c.objCode is null or c.objCode  <> '' ,c.objCode = pro.rObjCode,1!=1) where 1=1
							",
     "select_zsporder"=>"select * from view_oa_order c where 1=1  ",
     "select_signIn"=>"select * from signIn_oa_order c where 1=1 ",
     "financeview_orderinvoice"=>"select * from financeview_orderinvoice c where 1=1 ",
     "select_customizelist"=>"select c.orgId,c.orderId,c.orderName,c.productCode,c.productName,c.productModel,c.productType,c.number,c.unitName,c.price,c.money,c.productLine,c.isSell,c.projArraDT,c.remark,c.tablename,c.id,v.orderCode,v.orderTempCode,v.ExaStatus from customizelist_oa_order c ,view_oa_order v where c.tablename=v.tablename and c.orderId=v.orgid",
     "select_myorderinfo"=>"select * from myorderinfo_oa_order c where 1=1",

	 "auditing" =>
		"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as orderId,c.orderCode,c.orderName,c.sign,c.orderstate,c.orderTempCode,c.orderNature,c.orderNatureName,
		c.deliveryDate,c.prinvipalName,c.signIn,
		c.customerName,c.customerType,c.customerProvince,c.state,
		c.ExaStatus,c.ExaDT,c.isTemp
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_order c
		 where c.isTemp=0 and
		p.Flag='0' and
		w.Pid =c.id and" .
		" w.examines <> 'no'" ,
	"audited" =>
		"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as orderId,c.orderCode,c.orderName,c.sign,c.orderstate,c.orderTempCode,c.orderNature,c.orderNatureName,
		c.deliveryDate,c.prinvipalName,c.signIn,
		c.customerName,c.customerType,c.customerProvince,c.state,
		c.ExaStatus,c.ExaDT,c.isTemp,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_order c
		 where c.isTemp=0 and
		p.Flag='1' and
		w.Pid =c.id ",
		/************************变更审批流**************************/

	 "change_auditing" =>
		"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as orderId,c.orderCode,c.orderName,c.sign,c.orderstate,c.orderTempCode,
		c.deliveryDate,c.prinvipalName,c.signIn,
		c.customerName,c.customerType,c.customerProvince,c.state,
		c.ExaStatus,c.ExaDT,c.isTemp
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_order c
		 where c.isTemp=1 and
		p.Flag='0' and
		w.Pid =c.id and" .
		" w.examines <> 'no'" ,
	"change_audited" =>
		"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as orderId,c.orderCode,c.orderName,c.sign,c.orderstate,c.orderTempCode,
		c.deliveryDate,c.prinvipalName,c.signIn,
		c.customerName,c.customerType,c.customerProvince,c.state,
		c.ExaStatus,c.ExaDT,c.isTemp
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_order c
		 where c.isTemp=1 and
		p.Flag='1' and
		w.Pid =c.id ",
	"all_auditing" => "select
		  w.task,
		  w.name AS ExaName,
		  w.code as ExaObj,
		  p.ID as id,
		  c.orgid AS orderId,
		  c.orderCode,
		  c.orderTempCode,
		  c.orderName,
		  c.sign,
		  c.ExaStatus,
		  c.ExaDT,
		  c.prinvipalName,
		  c.areaName,
		  c.areaCode,
		  c.customerName,
		  c.isTemp
		from
		  flow_step_partent p
		  LEFT OUTER JOIN wf_task w ON (p.wf_task_id = w.task),
		  orderview_all c
		where
		  p.Flag = 0 AND
		  w.Pid = c.orgid AND
		  w.code = c.tablename AND
		  w.examines <> 'no' ",
	"all_audited" => "select
		  w.task,
		  w.name AS ExaName,
		  w.code as ExaObj,
		  p.ID as id,
		  c.orgid AS orderId,
		  c.orderCode,
		  c.orderTempCode,
		  c.orderName,
		  c.sign,
		  c.ExaStatus,
		  c.ExaDT,
		  c.prinvipalName,
		  c.areaName,
		  c.areaCode,
		  c.customerName,
		  c.isTemp
		from
		  flow_step_partent p
		  LEFT OUTER JOIN wf_task w ON (p.wf_task_id = w.task),
		  orderview_all c
		where
		  p.Flag = 1 AND
		  w.Pid = c.orgid AND
		  w.code = c.tablename"

);

$condition_arr = array (
    array(
        "name" => 'signinType',
        "sql" => "and c.signinType =#"
    ),
	array(
        "name" => 'mySearchCondition',
        "sql" => "$"
    ),
	array(
        "name" => 'DEPT_ID',
        "sql" => "and u.DEPT_ID in(arr)"
    ),
    array(
        "name" => 'shipCondition',
        "sql" => "and c.shipCondition = #"
    ),
    array(
        "name" => 'createTime',
        "sql" => "and c.createTime like CONCAT('%',#,'%')"
    ),
    array(
        "name" => 'customerProvince',
        "sql" => "and c.customerProvince like CONCAT('%',#,'%')"
    ),
    array(
        "name" => 'customerType',
        "sql" => "and c.customerType like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "areaName",
        "sql" => "and c.areaName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "areaNameArr",
        "sql" => "and c.areaName in(arr)"
    ),
    array(
        "name" => "orderstate",
        "sql" => "and c.orderstate like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "appId",
        "sql" => "and c.prinvipalId = #"
    ),
    array(
        "name" => "prinvipalName",
        "sql" => "and c.prinvipalName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "areaPrincipal",
        "sql" => "and c.areaPrincipal like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "signIn",
        "sql" => "and c.signIn like CONCAT('%',#,'%')"
    ),
    array(//已使用
        "name" => "sign",
        "sql" => "and c.sign = #"
    ),
    array(
        "name" => "state",
        "sql" => " and c.state=#"
        ),
    array(
        "name" => "states",
        "sql" => " and c.state in(arr)"
        ),
    array(
        "name" => "DeliveryStatus",
        "sql" => " and c.DeliveryStatus=#"
        ),
    array(
        "name" => "DeliveryStatusArr",
        "sql" => " and c.DeliveryStatus in(arr)"
        ),
    array(
        "name" => "DeliveryStatus1",
        "sql" => " and c.DeliveryStatus in(arr)"
        ),
    array(
        "name" => "DeliveryStatus2",
        "sql" => " and c.DeliveryStatus in(arr)"
        ),
    array(
        "name" => "issuedStatus",
        "sql" => " and c.issuedStatus=#"
        ),
    array(
        "name" => "tablename",
        "sql" => " and c.tablename=#"
        ),
    array(
        "name" => "tablenames",
        "sql" => " and c.tablename in(arr)"
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "cluesName",
   		"sql" => " and c.cluesName=# "
   	  ),
   array(
   		"name" => "ajaxOrderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "OrajaxOrderCode",
   		"sql" => " or c.orderCode=# "
   	  ),
   array(
   		"name" => "OrajaxOrderTempCode",
   		"sql" => " or c.orderTempCode=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "ajaxOrderTempCode",
   		"sql" => " and c.orderTempCode=# "
   	  ),
    array(
   		"name" => "orderTempCode",
   		"sql" => " and c.orderTempCode=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName like CONCAT('%',#,'%') "
   	  ),
   array(
        "name" => "orderNatureArr",
        "sql" => "and c.orderNature in(arr)"
   ),
   array(//编号模糊匹配
        "name" => "orderCodeOrTempSearch",
        "sql" => " and (c.orderCode like CONCAT('%',#,'%')  or c.orderTempCode like CONCAT('%',#,'%'))"
      ),
   array(//编号模糊匹配 +1
        "name" => "orderCodeOrTempSearchV",
        "sql" => " and (v.orderCode like CONCAT('%',#,'%')  or v.orderTempCode like CONCAT('%',#,'%'))"
      ),
   array(
   		"name" => "orderMoney",
   		"sql" => " and c.orderMoney=# "
   	  ),
   array(
   		"name" => "deliveryDate",
   		"sql" => " and c.deliveryDate=# "
   	  ),
   array(
   		"name" => "prinvipalId",
   		"sql" => " and c.prinvipalId=# "
   	  ),
    array(
   		"name" => "executeId",
   		"sql" => " and c.executeId=# "
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
   		"name" => "customerType",
   		"sql" => " and c.customerType=# "
   	  ),
   array(
   		"name" => "customerTypes",
   		"sql" => " and c.customerType in(arr) "
   	  ),
   array(
   		"name" => "customerProvince",
   		"sql" => " and c.customerProvince=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "orstate",
   		"sql" => " or c.state=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus =# "
   	  ),
   array(
   		"name" => "orderProvince",
   		"sql" => " and c.orderProvince =# "
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaStatusV",
   		"sql" => " and v.ExaStatus =# "
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
        "name" => "DeliveryStatus",
        "sql" => "and c.DeliveryStatus=#"
      ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
  array(
   		"name" => "isBecome",
   		"sql" => " and c.isBecome=# "
   	  ),
   array(
   		"name" => "saleman",
   		"sql" => " and c.saleman=# "
   	  ),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//流程名称
		"sql"=>" and w.name in (#) "
	),
	array(
		"name" => "workFlowCode",//流程名称
		"sql"=>" and w.code = # "
	),
	array(
   		"name" => "projectId",
   		"sql" => " and e.projectId=# "
   	),
	array(//无正式合同号
   		"name" => "orderCodeNull",
   		"sql" => " and (c.orderCode is null or c.orderCode = '') "
   	),
   	array(//省份过滤
		"name" => "districts",
		"sql" => " and c.district in(arr)"
   	),
   	/******高级搜索******/
   	array(// - 开始日期
		"name" => "beginDate",
		"sql" => " and c.createTime >= # "
	),
   array(// - 结束日期
		"name" => "endDate",
		"sql" => " and c.createTime <= # "
	),
	array(//区域过滤
		"name" => "areaCode",
		"sql" => " and c.areaCode in(arr)"
   	),
	//合同信息--部门过滤
    array(
        "name" => 'department',
        "sql" => "$"
    ),
    array(
        "name" => 'deptmentMoney',
        "sql" => "$"
    ),
     array(
        "name" => 'limitOrderMoney',
        "sql" => "$"
    ),
     array(
        "name" => 'limitOrderTempMoney',
        "sql" => "$"
    ),
     array(
        "name" => 'ajaxCodeChecking',
        "sql" => "$"
    ),
   	array(//区域权限过滤 － 此权限从区域管理模块获取
		"name" => "areaCodeFromRegion",
		"sql" => " and c.areaCode in(arr)"
   	),
   array(
   		"name" => "customer",
   		"sql" => " and c.customerName like CONCAT('%',#,'%')  "
   	  ),
   	 array(
   		"name" => "objCode",
   		"sql" => " and c.objCode like CONCAT('%',#,'%')"
   	  ),
   	 array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%')"
   	  )
//   array(// 合同类型
//        "name" => "orderType",
//        "sql" => "and c.tablename=#"
//   ),
//   array(//合同状态
//        "name" => "orderState",
//        "sql" => "and c.state=#"
//   )
)
?>