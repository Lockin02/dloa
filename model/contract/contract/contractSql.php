<?php

/**
 * @author LiuB
 * @Date 2012年3月8日 10:30:28
 * @version 1.0
 * @description:合同主表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select " .
			"if(now() > DATE_ADD(c.ExaDTOne,INTERVAL 3 MONTH) and c.signStatus !=1,1,0) as isExceed," .//签收是否超期
			"c.isRenewed,c.productLineStr,c.exeDeptStr,c.trialprojectCost,c.trialprojectCostAll,c.trialprojectId,c.trialprojectName,c.trialprojectCode,c.isAcquiringDate,c.isAcquiring,c.id ,c.exgross,c.isSubApp,c.isSubAppChange,c.costEstimates,c.signContractType,c.signContractTypeName," .
			"c.isEngConfirm,c.engConfirm,c.engConfirmName,c.engConfirmId,c.engConfirmDate,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName," .
			"c.isSaleConfirm,c.saleConfirm,saleConfirmName,c.saleConfirmId,c.saleConfirmDate," .
			"c.isRdproConfirm,c.rdproConfirm,rdproConfirmName,c.rdproConfirmId,c.rdproConfirmDate," .
			"c.isRelConfirm,c.relConfrim,relConfirmName,c.relConfrimId,c.relConfirmDate," .
			"c.lastInvoiceDate,c.spaceRentalMoney,c.equRentalMoney,c.appNameStr,c.incomeAccounting,c.goodsTypeStr,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.winRate,c.signDate ,c.chanceName ,c.chanceId ,c.contractType,c.advance,c.delivery,c.progresspayment,c.progresspaymentterm,c.initialpayment,c.finalpayment,c.otherpaymentterm,c.otherpayment,c.Maintenance ," .
			"c.signSubject,c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,c.parentId ,c.customerContNum ,c.customerName ,c.customerId ," .
			"c.customerTypeName ,c.customerType ,c.customerProvince ,c.address ,c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId " .
			",c.contractCity ,c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,c.invoiceType " .
			",c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney,c.softMoney,c.hardMoney,c.serviceMoney,c.repairMoney ,c.deliveryDate ,c.standardDate ,c.beginDate ,c.endDate ,c.contractInputName " .
			",c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne " .
			",c.ExaDT ,c.closeName ,c.closeId ,c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus " .
			",c.signName ,c.signNameId ,c.signDetail ,c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,c.areaName ,c.areaPrincipal ,(c.contractMoney-(if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney)) - c.uninvoiceMoney) as surplusInvoiceMoney," .
			"c.areaPrincipalId ,c.areaCode ,c.remark ,c.currency ,c.contractTempMoneyCur ,c.contractMoneyCur ,c.rate ,c.isBecome ,c.shipCondition," .
			"c.customTypeId,c.customTypeName,c.warnDate,c.makeStatus ,c.dealStatus ,c.contractState ,c.state,c.contractSigner,c.contractSignerId,date_format(c.createTime,'%Y-%m-%d') as createDate," .
			"c.isNeedStamp,c.stampType,c.isStamp,c.isNeedRestamp,c.projectProcess,c.projectRate,c.projectStatus,c.serviceconfirmMoney,c.financeconfirmMoney,c.deductMoney,c.badMoney,c.uninvoiceMoney,c.invoiceValue,c.invoiceCode,if(c.checkStatus is null or c.checkStatus = ' ','未录入',c.checkStatus) as checkStatus,c.checkName,c.checkDate,c.checkRemarks,c.fcheckStatus,c.fcheckName,c.fcheckDate,c.fcheckRemarks
			,c.outGoodsRemind,c.outGoodsReason,c.signPush,c.signRemind,c.differentReason,c.planReason,c.planConfirm,if(c.signStatus = 1,1,0) as isSigned,if( CURDATE() > date_add(c.ExaDTOne, interval 30 day),1,0) as isArchiveOutDate,
			if(c.DeliveryStatus = 'YFH',1,0) as isDelivered,c.contractTypeName,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr,c.checkFile,c.paperContract,
			if(find_in_set('1',(SELECT group_concat(if(d.docStatus <> 'YWC' AND d.shipPlanDate < CURDATE(),1,0)) AS planStatusStr FROM oa_stock_outplan d WHERE docType = 'oa_contract_contract' AND docId = c.id GROUP BY docId,docType)),1,0) as isPlanOutDate,c.partAContractCode,c.partAContractName,c.paperSignTime
					from oa_contract_contract c where 1=1 ",

    "select_gridinfo" =>"
        select c.uninvoiceMoney,c.invoiceValue,c.contractTypeName,c.outstockDate,c.isRenewed,c.productLineStr,c.exeDeptStr,c.trialprojectCost,c.trialprojectCostAll,c.trialprojectId,c.trialprojectName,c.trialprojectCode,c.isAcquiringDate,c.isAcquiring,r.areaPrincipal as AreaLeaderNow,c.id ,c.exgross,c.isSubApp,c.isSubAppChange,c.costEstimates,c.signContractType,c.signContractTypeName," .
        	"c.isEngConfirm,c.engConfirm,c.engConfirmName,c.engConfirmId,c.engConfirmDate,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName," .
        	"c.isEngConfirm,c.engConfirm,c.engConfirmName,c.engConfirmId,c.engConfirmDate,c.costEstimatesTax," .
			"c.isSaleConfirm,c.saleConfirm,saleConfirmName,c.saleConfirmId,c.saleConfirmDate," .
			"c.isRdproConfirm,c.rdproConfirm,rdproConfirmName,c.rdproConfirmId,c.rdproConfirmDate," .
			"c.isRelConfirm,c.relConfrim,relConfirmName,c.relConfrimId,c.relConfirmDate," .
        	"c.finalDate,c.preliminaryDate,c.shouldInvoiceDate,c.lastInvoiceDate,c.spaceRentalMoney,c.equRentalMoney,c.appNameStr,c.incomeAccounting,c.goodsTypeStr,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.winRate,c.signDate ,c.chanceName ,c.chanceId ,c.contractType,c.advance,c.delivery,c.progresspayment,c.progresspaymentterm,c.initialpayment,c.finalpayment,c.otherpaymentterm,c.otherpayment,c.Maintenance ,
			c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,c.parentId ,c.customerContNum ,c.customerName ,c.customerId ,
			c.signSubject,c.customerTypeName ,c.customerType ,c.customerProvince ,c.address ,c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId
			,c.contractCity ,c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,c.invoiceType
			,c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney,c.softMoney,c.hardMoney,c.serviceMoney,c.repairMoney ,c.deliveryDate ,c.standardDate ,c.beginDate ,c.endDate ,c.contractInputName
			,c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne
			,c.ExaDT ,c.closeName ,c.closeId ,c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus
			,c.signName ,c.signNameId ,c.signDetail ,c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,c.areaName ,c.areaPrincipal ,c.badMoney,
			c.areaPrincipalId ,c.areaCode ,c.remark ,c.currency ,c.contractTempMoneyCur ,c.contractMoneyCur ,c.rate ,c.isBecome ,c.shipCondition,
			c.makeStatus ,c.dealStatus ,c.contractState ,c.state,c.contractSigner,c.contractSignerId,date_format(c.createTime,'%Y-%m-%d') as createDate,
			c.customTypeId,c.customTypeName,c.warnDate,c.isNeedStamp,c.stampType,c.isStamp,c.isNeedRestamp,c.projectRate,c.projectStatus,(if(c.invoiceCode like '%HTBKP%',0,c.contractMoney-(if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))  - c.uninvoiceMoney)) as surplusInvoiceMoney,
			(c.contractMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney))) as surOrderMoney,
			((if(c.invoiceCode like '%HTBKP%',if(c.contractMoney is null or c.contractMoney='',0,c.contractMoney),if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney)))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney))) as surincomeMoney,
			c.deductMoney,c.projectProcessAll,c.processMoney,c.gross,c.rateOfGross,c.serviceconfirmMoneyAll,c.financeconfirmMoneyAll,c.deliveryCostsAll,c.uninvoiceMoney,
			c.chanceCode,c.chanceName,c.invoiceCode,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr,c.checkFile,c.paperContract,
			date_format(c.ExaDTOne,'%Y') as ExaYear,date_format(c.ExaDTOne,'%m') as ExaMonth,quarter(c.ExaDTOne) as ExaQuarter,c.comPoint,c.conProgress,c.invoiceProgress,c.incomeProgress,
			c.proj_budgetAll,c.proj_curIncome,c.proj_feeAll,c.proj_conProgress,c.proj_gross,c.proj_rateOfGross,c.proj_comPoint,c.proj_icomeMoney,c.proj_incomeProgress,c.proj_invoiceProgress,c.productCheck,c.projectCheck,c.invoiceCheck,c.invoiceTrueCheck,
			i.dsEnergyCharge,i.dsWaterRateMoney,i.houseRentalFee,i.installationCost,c.partAContractCode,c.partAContractName,c.paperSignTime
		from oa_contract_contract c
			left join oa_system_region r on c.areaCode = r.id
			left join (
				select objId,
				sum(if(isRed = 1,(-otherMoney),otherMoney)) as otherMoney,
				sum(if(isRed = 1,(-dsEnergyCharge),dsEnergyCharge)) as dsEnergyCharge,
				sum(if(isRed = 1,(-dsWaterRateMoney),dsWaterRateMoney)) as dsWaterRateMoney,
				sum(if(isRed = 1,(-houseRentalFee),houseRentalFee)) as houseRentalFee,
				sum(if(isRed = 1,(-installationCost),installationCost)) as installationCost
				from oa_finance_invoice where objType = 'KPRK-12' group by objId
			)i on i.objId = c.id
			where 1=1",
    "select_shipments" => "select c.outstockDate,c.isSell,c.isSellchange,c.reterStart,c.id ,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.signDate ,c.chanceName ,
			c.chanceId ,c.contractType ,c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,
			c.customTypeId,c.customTypeName,c.warnDate,c.parentId ,c.customerContNum ,c.customerName ,c.customerId ,c.customerTypeName ,c.customerType ,c.customerProvince ,
			c.address ,c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId ,c.contractCity ,
			c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,
			c.invoiceType ,c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney ,c.deliveryDate ,c.standardDate ,
			c.beginDate ,c.endDate ,c.contractInputName ,c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,
			c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne ,c.ExaDT ,c.closeName ,c.closeId ,
			c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus ,
			c.signName ,c.signNameId ,c.signDetail ,c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,
			c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.remark ,c.currency ,c.contractTempMoneyCur ,
			c.contractMoneyCur ,c.rate ,c.isBecome ,c.shipCondition ,c.makeStatus ,c.dealStatus ,c.contractState ,c.state,
			c.contractSigner,c.contractSignerId ,l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne,c.uninvoiceMoney,
			l.id as linkId,c.isMeetProduction,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr from
			 oa_contract_contract c INNER JOIN
(select
					e.contractId,sum(1) as number
				from
					oa_contract_equ e
				where
					 e.isTemp=0 and e.isDel=0
				group by e.contractId
				having  number>0 ) e
					on c.id =  e.contractId
left join oa_contract_equ_link l on (c.id=l.contractId and l.contractType='oa_contract_contract')
			 		where 1=1
 and (l.isTemp=0 or l.isTemp is NULL) and c.isTemp=0 ",

    "select_assignment" => "select c.outstockDate,c.isSell,c.isSellchange,c.reterStart,c.id ,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.signDate ,c.chanceName ,c.isSubAppChange,
			c.chanceId ,c.contractType ,c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,
			c.customTypeId,c.customTypeName,c.warnDate,c.parentId ,c.customerContNum ,c.customerName ,c.customerId ,c.customerTypeName ,c.customerType ,c.customerProvince ,
			c.address ,c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId ,c.contractCity ,
			c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,
			c.invoiceType ,c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney ,c.deliveryDate ,c.standardDate ,
			c.beginDate ,c.endDate ,c.contractInputName ,c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,
			c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne ,c.ExaDT ,c.closeName ,c.closeId ,
			c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus ,
			c.signName ,c.signNameId ,c.signDetail ,c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,
			c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.remark ,c.currency ,c.contractTempMoneyCur ,
			c.contractMoneyCur ,c.rate ,c.isBecome ,c.shipCondition ,c.makeStatus ,c.dealStatus ,c.contractState ,c.state,
			c.contractSigner,c.contractSignerId ,l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne,c.uninvoiceMoney,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr,
			l.id as linkId from
			 oa_contract_contract c left join oa_contract_equ_link l on (c.id=l.contractId and l.contractType='oa_contract_contract')
			 		where 1=1  and (l.isTemp=0 or l.isTemp is NULL) and c.isTemp=0 ",

	"select_contractInfo_sumMoney" =>"
		select
			sum(c.contractTempMoney) as contractTempMoney,sum(c.contractMoney) as contractMoney,sum(c.invoiceMoney) as invoiceMoney,sum(c.incomeMoney) as incomeMoney,sum(c.uninvoiceMoney) as uninvoiceMoney,
			sum(c.deductMoney) as deductMoney,sum(c.invoiceMoney) as invoiceMoney,sum(c.badMoney) as badMoney,sum(c.invoiceApplyMoney) as invoiceApplyMoney,sum(c.softMoney) as softMoney,sum(c.hardMoney) as hardMoney,sum(c.serviceMoney) as serviceMoney,sum(c.repairMoney) as repairMoney,
			sum(pro.budgetAll) as budgetAll,sum(pro.budgetOutsourcing) as budgetOutsourcing,sum(pro.feeFieldCount) as feeFieldCount,sum(pro.feeOutsourcing) as feeOutsourcing,(sum(if(pro.feeAll is null or pro.feeAll='',0,pro.feeAll)) + sum(if(c.deliveryCostsAll is null or c.deliveryCostsAll='',0,c.deliveryCostsAll))) as feeAll,
			sum(((if(c.invoiceCode like '%HTBKP%',if(c.contractMoney is null or c.contractMoney='',0,c.contractMoney),if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney)))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney)))) as surincomeMoney,
			sum(if(c.invoiceCode like '%HTBKP%',0,(c.contractMoney-(if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney))-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))  - c.uninvoiceMoney))) as surplusInvoiceMoney,
			sum((c.contractMoney-(if(c.deductMoney is null or c.deductMoney='',0,c.deductMoney))-(if(c.badMoney is null or c.badMoney='',0,c.badMoney))-(if(c.incomeMoney is null or c.incomeMoney='',0,c.incomeMoney)))) as surOrderMoney,
			sum(c.processMoney) as processMoney,sum(c.gross) as gross,sum(c.serviceconfirmMoneyAll) as serviceconfirmMoneyAll,sum(c.financeconfirmMoneyAll) as financeconfirmMoneyAll
		from
			oa_contract_contract c left join
			(
				select
					c.contractId,sum(c.budgetAll) as budgetAll,
					sum(c.budgetOutsourcing) as budgetOutsourcing,
					sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + if(op.money is null ,0,op.money)) as feeFieldCount,
					sum(c.feeOutsourcing) as feeOutsourcing,
					sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + if(op.money is null ,0,op.money) + c.feeOther + c.feeOutsourcing) as feeAll,
					round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
				from
					oa_esm_project c LEFT JOIN (
						SELECT
							sum(l.Amount) AS feeFieldCount, l.projectNo
						FROM
							cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
					) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
					left join (
						select
							p.expand3,sum(p.money) as money
						from
							oa_finance_payablesapply_detail p
						left join oa_finance_payablesapply pa on p.payapplyId = pa.id
						where
							p.expand1 = '工程项目' and pa.ExaStatus != '打回' and pa. status not in ('FKSQD-04', 'FKSQD-05')
							and p.objType = 'YFRK-02'
						group by
							p.expand3
					) op on c.id = op.expand3
				where c.contractType = 'GCXMYD-01'
				GROUP BY c.contractId
			)pro on c.id = pro.contractId where 1=1"
	,"select_equ" => "select * from (select
						ce.productId as id,
						ce.productId,
						ce.productCode,
						ce.productCode as productNo,
						ce.productName,
						sum(ce.number) as number,
						sum(ce.executedNum) as executedNum,
						sum(ce.onWayNum) as onWayNum
					from
						oa_contract_equ ce right join oa_contract_contract c
							on ( c.id=ce.contractId )
					where
						1 = 1 and c.dealStatus in(1,3) and c.contractType != 'HTLX-ZLHT'
					and c.ExaStatus = '完成' and c.isTemp=0 and c.DeliveryStatus != 'TZFH' and ce.isTemp=0 and ce.isDel=0 group by productId )ce where 1=1 and ce.number-ce.executedNum>0 "
	,"select_cont" => "select
						c.id,
						c.contractCode,
						c.contractName,
						c.contractType,
						ce.number,
						ce.onWayNum,
						ce.executedNum
					from
						oa_contract_contract c left join oa_contract_equ ce
							on ( c.id=ce.contractId )
					where
						1 = 1
					and (ce.number-ce.executedNum>0) and c.dealStatus in(1,3) and c.contractType != 'HTLX-ZLHT'
					and c.ExaStatus = '完成' and c.isTemp=0 and c.DeliveryStatus != 'TZFH' and ce.isTemp=0 and ce.isDel=0 "
    ,"select_financialTday" => "select r.paymenttermInfo,r.conType,r.dayNum,r.comDate,r.periodName,r.deductMoney,r.planInvoiceMoney,
                    if(r.Tday is null or r.Tday = '',0,1) as isConfirm,r.changeRemark,
					r.id,c.id as contractId,c.contractCode,r.deductMoney,r.paymenttermId,r.paymentterm,r.paymentPer,r.money,r.payDT,r.remark,
					r.Tday,r.incomMoney,r.isCom,r.isTemp,r.isDel,c.contractMoney,r.invoiceMoney,r.TdayPush,r.invoiceRemind,r.invoiceReason,r.incomeRemind,r.incomeReason,r.invoiceDate,
    				if((CURDATE() > r.Tday and r.invoiceMoney < r.money) or (CURDATE() > date_add(r.Tday, interval 30 day) and r.incomMoney < r.money),1,0) as isMoneyOutDate,
    				if(r.invoiceMoney < r.money or r.incomMoney < r.money,0,1) as isFinishMoney
    				,0 as isDelivered ,0 as isPlanOutDate,c.contractName,c.completeDate,r.schedulePer,c.ExaDtOne
					 from oa_contract_receiptplan r
					left join oa_contract_contract c on r.contractId=c.id
					where r.isTemp = '0' and r.isfinance='0' and c.ExaStatus='完成' ".CONTOOLIDS_C."",
        "select_financialTday_new" => "select c.id as contractId,c.contractCode,c.contractMoney,sum(r.incomMoney) as incomMoney,sum(r.invoiceMoney) as invoiceMoney,c.customerName,r.schedulePer
        from  oa_contract_contract c
        left join oa_contract_receiptplan r on r.contractId=c.id
        where r.isTemp = '0' and r.isfinance='0' and c.ExaStatus='完成'  ".CONTOOLIDS_C."",
	"select_buildList" => "select  ou.shipPlanDate,esm.planEndDate,esm.actEndDate,c.outstockDate,
	                if(if(ou.shipPlanDate is null,0,ou.shipPlanDate)>if(esm.planEndDate is null,0,esm.planEndDate),
                     if(c.outstockDate is not null, if(if(ou.shipPlanDate is null,0,ou.shipPlanDate)>=c.outstockDate,'是','否'), if(if(ou.shipPlanDate is null,0,ou.shipPlanDate)>=now(),'是','否')),
                     if(if(esm.planEndDate is null,0,esm.planEndDate) is not null, if(if(esm.planEndDate is null,0,esm.planEndDate)>=esm.actEndDate,'是','否'), if(if(esm.planEndDate is null,0,esm.planEndDate)>=now(),'是','否'))
                            ) as isExceed,
	                c.isRenewed,c.productLineStr,c.exeDeptStr,c.trialprojectCost,c.trialprojectCostAll,c.trialprojectId,c.trialprojectName,c.trialprojectCode,c.isAcquiringDate,c.isAcquiring,c.id ,c.exgross,c.isSubApp,c.isSubAppChange,c.costEstimates,c.signContractType,c.signContractTypeName,
                    c.isEngConfirm,c.engConfirm,c.engConfirmName,c.engConfirmId,c.engConfirmDate,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,
                    c.isSaleConfirm,c.saleConfirm,saleConfirmName,c.saleConfirmId,c.saleConfirmDate,
                    c.isRdproConfirm,c.rdproConfirm,rdproConfirmName,c.rdproConfirmId,c.rdproConfirmDate,
                    c.isRelConfirm,c.relConfrim,relConfirmName,c.relConfrimId,c.relConfirmDate,
                    c.lastInvoiceDate,c.spaceRentalMoney,c.equRentalMoney,c.appNameStr,c.incomeAccounting,c.goodsTypeStr,c.objCode,c.isTemp,c.prinvipalDeptId,c.prinvipalDept ,c.sign ,c.winRate,c.signDate ,c.chanceName ,c.chanceId ,c.contractType,c.advance,c.delivery,c.progresspayment,c.progresspaymentterm,c.initialpayment,c.finalpayment,c.otherpaymentterm,c.otherpayment,c.Maintenance ,
                    c.signSubject,c.paymentterm, c.contractCode ,c.oldContractCode ,c.contractName ,c.parentName ,c.parentId ,c.customerContNum ,c.customerName ,c.customerId ,
                    c.customerTypeName ,c.customerType ,c.customerProvince ,c.address ,c.contractCountry ,c.contractCountryId ,c.contractProvince ,c.contractProvinceId
                    ,c.contractCity ,c.contractCityId ,c.prinvipalName ,c.prinvipalId ,c.contractTempMoney ,c.contractMoney ,c.invoiceTypeName ,c.invoiceType
                    ,c.invoiceApplyMoney ,c.invoiceMoney ,c.incomeMoney ,c.costMoney,c.softMoney,c.hardMoney,c.serviceMoney,c.repairMoney ,c.deliveryDate ,c.standardDate ,c.beginDate ,c.endDate ,c.contractInputName
                    ,c.contractInputId ,c.enteringDate ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDTOne
                    ,c.ExaDT ,c.closeName ,c.closeId ,c.closeTime ,c.closeType ,c.closeRegard ,c.DeliveryStatus ,c.warrantyClause ,c.afterService ,c.signStatus
                    ,c.signName ,c.signNameId ,c.signDetail ,c.signRemark ,c.becomeNum ,c.contractNature ,c.contractNatureName ,c.areaName ,c.areaPrincipal ,(c.contractMoney-(if(c.invoiceMoney is null or c.invoiceMoney='',0,c.invoiceMoney)) - c.uninvoiceMoney) as surplusInvoiceMoney,
					c.customTypeId,c.customTypeName,c.warnDate,c.makeStatus ,c.dealStatus ,c.contractState ,c.state,c.contractSigner,c.contractSignerId,date_format(c.createTime,'%Y-%m-%d') as createDate,
					c.isNeedStamp,c.stampType,c.isStamp,c.isNeedRestamp,c.projectProcess,c.projectRate,c.projectStatus,c.serviceconfirmMoney,c.financeconfirmMoney,c.deductMoney,c.badMoney,c.uninvoiceMoney,c.invoiceValue,c.invoiceCode,if(c.checkStatus is null or c.checkStatus = ' ','未录入',c.checkStatus) as checkStatus
					,c.outGoodsRemind,c.outGoodsReason,c.signPush,c.signRemind,c.differentReason,c.planReason,c.planConfirm,if(c.signStatus = 1,1,0) as isSigned,if( CURDATE() > date_add(c.ExaDTOne, interval 30 day),1,0) as isArchiveOutDate,
					if(c.DeliveryStatus = 'YFH',1,0) as isDelivered,c.module,c.moduleName,c.isFrame,c.newProLineStr,c.newExeDeptStr,c.xfProLineStr,
					if(find_in_set('1',(SELECT group_concat(if(d.docStatus <> 'YWC' AND d.shipPlanDate < CURDATE(),1,0)) AS planStatusStr FROM oa_stock_outplan d WHERE docType = 'oa_contract_contract' AND docId = c.id GROUP BY docId,docType)),1,0) as isPlanOutDate,
					if(re.reNum is null,0,re.reNum) as reNum,
				    if(ch.chNum is null,0,ch.chNum) as chNum
						from oa_contract_contract c
				    left join ( select count(id)as reNum,contractId from oa_contract_receiptplan GROUP BY contractId )re on c.id=re.contractId
				    left join ( select count(id)as chNum,contractId from oa_contract_check GROUP BY contractId )ch on c.id=ch.contractId
					left join
                    (
                    select max(shipPlanDate) as shipPlanDate,docId  from oa_stock_outplan where docType='oa_contract_contract' GROUP BY docId
                    )ou on c.id=ou.docId
                    left JOIN
                    (
                    select max(planEndDate) as planEndDate,max(if(actEndDate is null and status='GCXMZT03',planEndDate,actEndDate)) as actEndDate,contractId from oa_esm_project where contractType='GCXMYD-01' GROUP BY contractId
                    )esm on c.id=esm.contractId
							where 1=1 ".CONTOOLIDS_C." ",
	"select_basicList" => "select c.id,c.ExaDTOne,c.contractCode,c.contractName,FORMAT(c.contractMoney,2) as contractMoney,DATE_FORMAT(c.createTime, '%Y-%m-%d') as createTime,c.standardDate,FORMAT(c.saleCost,2) as saleCost,c.exgross,c.rateOfGross,
	    				c.isAcquiringDate,c.signinDate,DATE_FORMAT(co.costAppDate, '%Y-%m-%d') as costAppDate,ou.nn as shipTimes,ou.shipPlanDate,tt.ad as shipDate
					from oa_contract_contract c
					left join (select contractId,max(costAppDate) as costAppDate from oa_contract_cost group by contractId) co on c.id = co.contractId
					left join (select max(shipPlanDate) as shipPlanDate,docId,count(id) as nn from oa_stock_outplan where docType = 'oa_contract_contract'
						group by docId) ou on c.id = ou.docId
					left join (select c.id as cid,o.auditDate as ad from oa_contract_contract c left join oa_contract_equ e on e.contractId = c.id
						left join (select max(auditDate) as auditDate,contractId from oa_stock_outstock where docStatus = 'YSH' and (relDocType = 'XSCKFHJH')
							group by contractId) o on c.id = o.contractId where c.isTemp = 0 and e.isTemp = 0 and e.isDel = 0
						and (c.DeliveryStatus = 'YFH' or c.DeliveryStatus = 'TZFH') group by c.contractCode) tt on c.id = tt.cid
					where c.isTemp = 0 and ExaDTOne is not null",

	"select_tdayDataList" => "SELECT c.id,DATE_FORMAT(c.ExaDTOne,'%Y') as year,c.ExaDTOne,c.businessBelongName as signSubjectName,c.contractTypeName,c.contractNatureName,c.contractCode,c.customerName,'-' as editInfo,
					  c.customerTypeName,c.contractName,r.payInfo,ce.clauseInfo,(c.contractMoney-c.deductMoney-c.badMoney) as signContractMoney,
					 (c.contractMoney-c.deductMoney-c.badMoney-c.incomeMoney) as unIncomeMoney,(c.contractMoney-c.deductMoney-c.badMoney-c.invoiceMoney) as uninvoiceMoney,
					 c.contractMoney,c.DeliveryStatus,'-' as planInvoiceDate,c.contractProvince,c.contractCity,c.areaName,c.prinvipalName,c.outstockDate,'-' as relDate,r.payRemark,r.tday
					@FROM oa_contract_contract c
					  left join (select GROUP_CONCAT(paymenttermInfo SEPARATOR ';') as payInfo,GROUP_CONCAT(remark SEPARATOR ';') as payRemark,GROUP_CONCAT(Tday) as tday,contractId from oa_contract_receiptplan GROUP BY contractId) r on c.id=r.contractId
					  left join (select GROUP_CONCAT(clauseInfo SEPARATOR ';') as clauseInfo,contractId from oa_contract_check GROUP BY contractId) ce on c.id=ce.contractId
					WHERE c.isTemp = 0 and c.ExaDTOne is not null and ".TLIST_WHERE,
	"select_tdayInitList" => "select
					 r.id,c.id as contractId,DATE_FORMAT(c.ExaDTOne,'%Y') as year,c.ExaDTOne,c.businessBelongName as signSubjectName,c.contractTypeName,c.contractNatureName,c.contractCode,
					 c.customerTypeName,c.customerName,c.contractName,r.paymenttermInfo,r.Tday,r.paymentPer,(r.money-r.incomMoney-r.deductMoney) as unIncomeMoney,(r.money-r.invoiceMoney-r.deductMoney) as unInvMoney,
					 (c.contractMoney-c.deductMoney-c.badMoney) as cMoney,(c.contractMoney-c.incomeMoney-c.badMoney-c.deductMoney) as unCincMoney,
					 c.contractProvince,c.contractCity,c.areaName,c.prinvipalName,r.remark
					from oa_contract_receiptplan r
					left join oa_contract_contract c on r.contractId=c.id
					where c.isTemp=0 and r.Tday is not null and r.Tday <> '' and r.Tday <> '0000-00-00' 
						 and r.isfinance = 0 and ".TLIST_WHERE,
    "select_roportIncomeList" => "select  c.id,c.formBelongName,c.areaName,sum(c.incomeMoney) as incomeMoney,GROUP_CONCAT(c.id) as idStr from oa_contract_contract c where c.isTemp=0 and c.ExaDTOne is not null and c.areaName is not null and c.areaName !='' and c.formBelongName is not null and c.formBelongName != '' and".TLIST_WHERE,
    "select_gridinfoForYswj" => "select * from (SELECT c.id,c.contractCode,c.contractName,c.customerName,c.prinvipalName,c.areaPrincipal,'oa_contract_contract' as type FROM oa_contract_contract c ".
                        " WHERE c.state IN (0,1,2,3,4,5,6,7 ) AND isTemp = 0 UNION ALL".
                        " SELECT CONCAT( 'K', b.id ) AS id,b.CODE AS contractCode,'' AS contractName,".
                            "b.customerName,b.salesName AS prinvipalName,'' AS areaPrincipal,'oa_borrow_borrow' as type".
                        " FROM oa_borrow_borrow b".
                        " WHERE b.limits = '客户' AND b.ExaStatus = '完成') c".
                        " WHERE 1 = 1 "
);

$condition_arr = array (
   array(
       "name" => "contractTypeName",
       "sql" => " and c.contractTypeName=#"
   ),
   array (
	    "name" => "ids",
	    "sql" => "and c.id in(arr)"
	),
   array(
   		"name" => "data",
   		"sql" => "$"
   	  ),
/******高级搜索******/
    array(
        "name" => "businessBelong",
        "sql" => " and c.businessBelong=#"
    ),
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
    array(
        "name" => "DeliveryStatusArr",
        "sql" => " and c.DeliveryStatus in(arr)"
        ),
    array(
        "name" => "contractNatureArr",
        "sql" => "and c.contractNature in(arr)"
   ),
    array(
        "name" => "areaNameArr",
        "sql" => "and c.areaName in(arr)"
    ),
    array(
        "name" => "isSell",
        "sql" => " and c.isSell=#"
    ),
    array(
        "name" => "trialprojectCost",
        "sql" => " and c.trialprojectCost=#"
    ),
    array(
        "name" => "trialprojectCostAll",
        "sql" => " and c.trialprojectCostAll=#"
    ),
    array(
        "name" => "isSellSql",
        "sql" => "$"
    ),
    array(
        "name" => "isEngConfirmStr",
        "sql" => " and (c.isEngConfirm=# or c.isRdproConfirm=#)"
    ),
    array(
        "name" => "isSaleConfirm",
        "sql" => " and c.isSaleConfirm=#"
    ),
    array(
        "name" => "isRdproConfirm",
        "sql" => " and c.isRdproConfirm=#"
    ),
    array(
        "name" => "isRelConfirm",
        "sql" => " and c.isRelConfirm=#"
    ),
    array(
        "name" => "engConfirmStr",
        "sql" => " and (c.engConfirm=#)"
    ),
    array(
        "name" => "engConfirmCost",
        "sql" => " and if(FIND_IN_SET('1', costState) or c.engConfirm=1  or c.rdproConfirm=1,1,0)=#"
    ),
    array(
        "name" => "saleConfirm",
        "sql" => " and c.saleConfirm=#"
    ),
    array(
        "name" => "rdproConfirm",
        "sql" => " and c.rdproConfirm=#"
    ),
    array(
        "name" => "relConfrim",
        "sql" => " and c.relConfrim=#"
    ),
    array(
        "name" => "isSubApp",
        "sql" => " and c.isSubApp=#"
    ),
    array(
        "name" => "isSubAppChange",
        "sql" => " and c.isSubAppChange=#"
    ),
    array(
        "name" => "incomeAccounting",
        "sql" => " and c.incomeAccounting=#"
    ),
	array (
		"name" => "lExaStatusArr",
		"sql" => " and l.ExaStatus in(arr) "
	),
	array (
		"name" => "lExaStatus",
		"sql" => " and l.ExaStatus like CONCAT('%',#,'%') "
	),
	array (
		"name" => "reterStart",
		"sql" => " and c.reterStart=# "
	),
	array (
		"name" => "customTypeId",
		"sql" => " and c.customTypeId=# "
	),
	array (
		"name" => "customTypeName",
		"sql" => " and c.customTypeName=# "
	),
	array (
		"name" => "warnDate",
		"sql" => " and c.warnDate=# "
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "prinvipalDeptId",
		"sql" => " and c.prinvipalDeptId=# "
	),
	array (
		"name" => "prinvipalDept",
		"sql" => " and c.prinvipalDept=# "
	),
	array (
		"name" => "sign",
		"sql" => " and c.sign=# "
	),
	array (
		"name" => "signDate",
		"sql" => " and c.signDate=# "
	),
	array (
		"name" => "chanceName",
		"sql" => " and c.chanceName=# "
	),
	array (
		"name" => "chanceId",
		"sql" => " and c.chanceId=# "
	),
	array (
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array (//合同类型 - 多选
		"name" => "contractTypeArr",
		"sql" => " and c.contractType in(arr) "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode  like CONCAT('%',#,'%') "
	),
	array(
	    "name" => "trialprojectCode",
	    "sql" => " and c.trialprojectCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "contractCodeAll",
		"sql" => " and c.contractCode=#"
	),
	array (
		"name" => "oldContractCode",
		"sql" => " and c.oldContractCode=# "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "contractNameAll",
		"sql" => " and c.contractName=#"
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "customerContNum",
		"sql" => " and c.customerContNum=# "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "customerNameNotIn",
		"sql" => " and c.customerName not in(arr)"
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "customerTypeName",
		"sql" => " and c.customerTypeName=# "
	),
	array (
		"name" => "customerTypeNameNotIn",
		"sql" => " and c.customerTypeName not in(arr)"
	),
	array (
		"name" => "customerTypeNotIn",
		"sql" => " and c.customerType not in(arr)"
	),
    array (
		"name" => "customerType",
		"sql" => " and c.customerType=# "
	),
	array (
		"name" => "customerProvince",
		"sql" => " and c.customerProvince=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "contractCountry",
		"sql" => " and c.contractCountry=# "
	),
	array (
		"name" => "contractCountryId",
		"sql" => " and c.contractCountryId=# "
	),
	array (
		"name" => "contractProvince",
		"sql" => " and c.contractProvince like CONCAT('%',#,'%') "
	),
	array (
		"name" => "contractProvinceArr",
		"sql" => " and c.contractProvince in(arr) "
	),
	array (
		"name" => "contractProvinceId",
		"sql" => " and c.contractProvinceId=# "
	),
	array (
		"name" => "contractCity",
		"sql" => " and c.contractCity like CONCAT('%',#,'%') "
	),
	array (
		"name" => "contractCityId",
		"sql" => " and c.contractCityId=# "
	),
	array (
		"name" => "prinvipalName",
		"sql" => " and c.prinvipalName=# "
	),
	array (
		"name" => "prinvipalId",
		"sql" => " and c.prinvipalId=# "
	),
    array (
        "name" => "prinvipalOrCreateId",
        "sql" => " and (c.prinvipalId=# or c.createId=# )"
    ),
	array (
		"name" => "prinvipalIdNotIn",
		"sql" => " and c.prinvipalId not in(arr)"
	),
	array (
		"name" => "contractTempMoney",
		"sql" => " and c.contractTempMoney=# "
	),
	array (
		"name" => "contractMoney",
		"sql" => " and c.contractMoney=# "
	),
	array (
		"name" => "invoiceTypeName",
		"sql" => " and c.invoiceTypeName=# "
	),
	array (
		"name" => "invoiceType",
		"sql" => " and c.invoiceType=# "
	),
	array (
		"name" => "invoiceApplyMoney",
		"sql" => " and c.invoiceApplyMoney=# "
	),
	array (
		"name" => "invoiceMoney",
		"sql" => " and c.invoiceMoney=# "
	),
	array (
		"name" => "incomeMoney",
		"sql" => " and c.incomeMoney=# "
	),
	array (
		"name" => "costMoney",
		"sql" => " and c.costMoney=# "
	),
	array (
		"name" => "deliveryDate",
		"sql" => " and c.deliveryDate=# "
	),
	array (
		"name" => "standardDate",
		"sql" => " and c.standardDate=# "
	),
	array (
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array (
		"name" => "endDate",
		"sql" => " and c.endDate=# "
	),
	array (
		"name" => "contractInputName",
		"sql" => " and c.contractInputName=# "
	),
	array (
		"name" => "contractInputId",
		"sql" => " and c.contractInputId=# "
	),
	array (
		"name" => "enteringDate",
		"sql" => " and c.enteringDate=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaYear",
		"sql" => " and date_format(c.ExaDTOne,'%Y')=# "
	),
	array (
		"name" => "ExaMonth",
		"sql" => " and date_format(c.ExaDTOne,'%m')=# "
	),
	array (
		"name" => "ExaYearMonth",
		"sql" => " and date_format(c.ExaDTOne,'%Y-%m')=# "
	),
	array (
		"name" => "ExaQuarter",
		"sql" => " and quarter(c.ExaDTOne)=# "
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
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
	    "name" => "ExaStatusArr",
	    "sql" => "and c.ExaStatus in(arr)"
	),
	array (
	    "name" => "ExaStatusSql",
	    "sql" => "$"
	),
	array (
	    "name" => "areaCodeSql",
	    "sql" => "$"
	),
	array (
		"name" => "ExaDTOne",
		"sql" => " and c.ExaDTOne=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "closeName",
		"sql" => " and c.closeName=# "
	),
	array (
		"name" => "closeId",
		"sql" => " and c.closeId=# "
	),
	array (
		"name" => "closeTime",
		"sql" => " and c.closeTime=# "
	),
	array (
		"name" => "closeType",
		"sql" => " and c.closeType=# "
	),
	array (
		"name" => "closeRegard",
		"sql" => " and c.closeRegard=# "
	),
	array (
		"name" => "DeliveryStatus",
		"sql" => " and c.DeliveryStatus=# "
	),
	array (
		"name" => "DeliveryStatusArr",
		"sql" => " and c.DeliveryStatus in(arr) "
	),
	array (
		"name" => "warrantyClause",
		"sql" => " and c.warrantyClause=# "
	),
	array (
		"name" => "afterService",
		"sql" => " and c.afterService=# "
	),
	array (
		"name" => "signStatus",
		"sql" => " and c.signStatus=# "
	),
	array (
		"name" => "signStatusArr",
		"sql" => " and c.signStatus in(arr) "
	),
	array (
		"name" => "signName",
		"sql" => " and c.signName=# "
	),
	array (
		"name" => "signNameId",
		"sql" => " and c.signNameId=# "
	),
	array (
	    "name" => "isRenewed",
	    "sql" => " and c.isRenewed"
	),
	array (
		"name" => "signDetail",
		"sql" => " and c.signDetail=# "
	),
	array (
		"name" => "signRemark",
		"sql" => " and c.signRemark=# "
	),
	array (
		"name" => "becomeNum",
		"sql" => " and c.becomeNum=# "
	),
	array (
		"name" => "contractNature",
		"sql" => " and c.contractNature=# "
	),
	array (
		"name" => "contractNatureName",
		"sql" => " and c.contractNatureName=# "
	),
	array (
		"name" => "areaName",
		"sql" => " and c.areaName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "areaNameNotIn",
		"sql" => " and c.areaName not in(arr)"
	),
	array (
		"name" => "areaCodes",
		"sql" => " and c.areaCode in(arr) "
	),
	array (
		"name" => "areaPrincipal",
		"sql" => " and c.areaPrincipal=# "
	),
	array (
        "name" => "prinvipalOrCreateId",
        "sql" => " and (c.prinvipalId=# or c.createId=# )"
    ),
	array (
		"name" => "areaPrincipalId",
		"sql" => " and c.areaPrincipalId=# "
	),

	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "currency",
		"sql" => " and c.currency=# "
	),
	array (
		"name" => "contractTempMoneyCur",
		"sql" => " and c.contractTempMoneyCur=# "
	),
	array (
		"name" => "contractMoneyCur",
		"sql" => " and c.contractMoneyCur=# "
	),
	array (
		"name" => "rate",
		"sql" => " and c.rate=# "
	),
	array (
		"name" => "isBecome",
		"sql" => " and c.isBecome=# "
	),
	array (
		"name" => "signSubject",
		"sql" => " and c.signSubject=# "
	),
	array (
		"name" => "shipCondition",
		"sql" => " and c.shipCondition=# "
	),
	array (
		"name" => "makeStatusArr",
		"sql" => " and c.makeStatus in(arr) "
	),
	array (
		"name" => "makeStatus",
		"sql" => " and c.makeStatus=# "
	),
	array (
		"name" => "dealStatusArr",
		"sql" => " and c.dealStatus in(arr) "
	),
	array (
		"name" => "dealStatus",
		"sql" => " and c.dealStatus=# "
	),
	array (
		"name" => "contractState",
		"sql" => " and c.contractState=# "
	),
	array (
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array (
		"name" => "states",
		"sql" => " and c.state in(arr) "
	),
	array (
	    "name" => "moneyType",
	    "sql" => "and c.moneyType=#"
	),
	array (//工程项目状态
		"name" => "projectStatus",
		"sql" => " and c.projectStatus = # "
	),
    array(
        "name" => "isNoDeal",
        "sql" => " and ((if(FIND_IN_SET('1', costState) or c.engConfirm=1  or c.rdproConfirm=1,1,0)=0) or (if(FIND_IN_SET('1', costState) or c.engConfirm=1  or c.rdproConfirm=1,1,0)<>0 and c.projectStatus = 0)) "
    ),
	array (
		"name" => "prinvipalDeptIds",
		"sql" => " and c.prinvipalDeptId in(arr) "
	),
	array(
	    "name" => "contractSignerId",
	    "sql"=> "and c.contractSignerId=#"
	),
	array(
	    "name" => "conProductName",
	    "sql" => "and c.id in (select contractId from oa_contract_product where conProductName like CONCAT('%',#,'%'))"
	),
	array(
	    "name" => "conProductName",
	    "sql" => "and c.id in (select contractId from oa_contract_equ where productName like CONCAT('%',#,'%'))"
	),
	array(//根据项目号找合同
	    "name" => "projectCodeAll",
	    "sql" => "and c.id in ( select contractId from oa_esm_project where contractType = 'GCXMYD-01' and projectCode like concat('%',#,'%'))"
	)
	,
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	)
	,
	array (//我的合同过滤
		"name" => "mycontractArr",
		"sql" => " and (c.prinvipalId=# or c.areaPrincipalId=# )"
	)
	,array(
		'name'=>'checkStatus',
		'sql'=>" and c.checkStatus = # "
	)
	,array(
		'name'=>'fcheckStatus',
		'sql'=>" and c.fcheckStatus = # "
	),
	array(
		'name'=>'checkStatusArr',
		'sql'=>" and c.checkStatus in(arr)"
	),
	array (
		"name" => "TdayYear",
		"sql" => " and date_format(r.Tday,'%Y')=# "
	),
	array (
		"name" => "TdayYearMonth",
		"sql" => " and date_format(r.Tday,'%Y-%m')=# "
	)
,
    array (
        "name" => "Tday",
        "sql" => " $ "
    )
	/**********设备汇总表**********/
	,array (
		"name" => "productId",
		"sql" => " and ce.productId=# "
	),array (
		"name" => "productCode",
		"sql" => " and ce.productCode like CONCAT('%',#,'%') "
	),array (
		"name" => "productName",
		"sql" => " and ce.productName like CONCAT('%',#,'%') "
	),
	/***add by chengl***/
	array (
		"name" => "lastAdd",
		"sql" => "  and c.ExaStatus='完成' and TO_DAYS(NOW()) - TO_DAYS(c.createTime) <= 15 "
	),
	array (
		"name" => "lastChange",
		"sql" => "  and c.ExaStatus='完成' and TO_DAYS(NOW()) - TO_DAYS(c.ExaDT) <= 15 and c.becomeNum>0"
	),
	/*******add by chenrf 汇款T日列表************/
	array(
		'name'=>'paymentterm',
		'sql'=>" and r.paymentterm like CONCAT('%',#,'%') "
	),
	array(
		'name'=>'isCom',
		'sql'=>" and r.isCom = # "
	),
	array(
		'name'=>'isDel',
		'sql'=>" and r.isDel = # "
	),
    array (
        "name" => "states_t",
        "sql" => "  and ((c.state IN( '2', '4') and c.isSubAppChange=0) or (c.state=0 and c.isSubApp=0))"
    ),
    array(
        'name'=>'isConfirm',
        'sql'=>" and if(r.Tday is null or r.Tday = '',0,1) = # "
    ),
	array(
		'name'=>'module',
		'sql'=>" and c.module = # "
	),
	array(
		'name'=>'moduleName',
		'sql'=>" and c.moduleName = # "
	),
	array(
		'name'=>'isFrame',
		'sql'=>" and c.isFrame = # "
	),
	array(
		'name'=>'newProLineStr',
		'sql'=>" and c.newProLineStr = # "
	),
	array(
		'name'=>'newExeDeptStr',
		'sql'=>" and c.newExeDeptStr = # "
	),
	array(
		'name'=>'xfProLineStr',
		'sql'=>" and c.xfProLineStr = # "
	),
	array(
		'name'=>'planInvoiceMoney',
		'sql'=>" and c.planInvoiceMoney = # "
	),
	array(
		'name'=>'formBelongName',
		'sql'=>" and c.formBelongName like CONCAT('%',#,'%') "
	),
    // 逾期节点年月过滤
    array(
        'name'=>'overPointDate',
        'sql'=>" and DATE_FORMAT(c.ExaDTOne,'%Y%c') <= # "
    ),
    array(
        'name' => 'defaultAreaCodes',
        'sql'=>" and FIND_IN_SET(c.areaCode,#)>0 "
    ),
	array(
		'name'=>'checkFile',
		'sql'=>" and c.checkFile = # "
	),
	array(
		'name'=>'noSpecilCustomerType',
		'sql'=>" and (c.customerTypeName not like '%子公司%' and c.customerTypeName not like '%母公司%' and c.customerTypeName not like '%内部结算%')"
	)
)
?>