<?php
/**
 * @author Show
 * @Date 2011年12月5日 星期一 10:19:51
 * @version 1.0
 * @description:其他合同 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.orderCode ,c.objCode ,c.orderName ,c.signCompanyName ,c.proName ,c.proCode ,
            c.address ,c.phone ,c.linkman ,c.signDate ,c.orderMoney ,c.principalName ,c.principalId ,
			c.deptName ,c.deptId ,c.fundType ,c.fundTypeName,c.fundCondition ,c.description ,c.ExaStatus ,c.ExaDT ,
			c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.stampType ,c.isNeedStamp ,
			c.isStamp ,c.status,date_format(c.createTime,'%Y-%m-%d') as createDate,c.signedStatus,c.signedDate,
			c.signedMan,c.signedManId,c.isNeedPayapply,c.feeDeptId,c.feeDeptName,
			c.uninvoiceMoney,c.returnMoney,c.remark,c.formBelong,
			c.formBelongName,c.businessBelong,c.businessBelongName,
			c.initInvotherMoney,c.initInvoiceMoney,c.initIncomeMoney,c.initPayMoney,c.returnMoney,c.currency,c.payForBusiness,c.payForBusinessName,c.isNeedRelativeContract,c.hasRelativeContract,c.relativeContract,c.relativeContractId,c.chanceCode,c.prefBidDate,c.contractCode,c.projectPrefEndDate,c.delayPayDays,c.isBankbackLetter,c.backLetterEndDate,c.prefPayDate
		from oa_sale_other c where c.isTemp = 0 ",
	"select_financeInfo" => "select
			c.id ,c.orderCode ,c.objCode ,c.orderName ,c.signCompanyName ,c.proName ,c.address ,c.phone ,c.linkman ,
			c.signDate ,c.orderMoney ,c.principalName ,c.deptName ,c.fundType ,c.ExaStatus ,c.ExaDT ,
			c.createName ,c.updateId ,c.updateName ,c.updateTime ,c.stampType ,c.isNeedStamp ,c.isStamp ,c.status,
			date_format(c.createTime,'%Y-%m-%d') as createDate,c.fundTypeName,c.signedStatus,c.signedDate,c.signedMan,
			c.signedManId,c.remark,c.currency,
			if(iapp.invoiceMoney is null,0,iapp.invoiceMoney) + c.initInvoiceMoney as applyInvoice,
			if(invo.invotherMoney is null ,0,invo.invotherMoney) + c.initInvotherMoney as invotherMoney,
			if(i.invoiceMoney is null,0,i.invoiceMoney) + c.initInvoiceMoney as invoiceMoney,
			if(a.incomeMoney is null,0,a.incomeMoney) + c.initIncomeMoney as incomeMoney,
			if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney  as payedMoney,
			if(pa.payApplyMoney is null,0,pa.payApplyMoney) +c.initPayMoney as payApplyMoney,
			if(invo.invotherMoney is null ,0,invo.invotherMoney) as countInvotherMoney,
			if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) +c.initInvotherMoney as confirmInvotherMoney,
			if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
			  (if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) +c.initInvotherMoney)
			  - c.returnMoney - c.uninvoiceMoney  as needInvotherMoney,
			if(p.payedMoney is null,0,p.payedMoney) as countPayMoney,
			if(pa.payApplyMoney is null,0,pa.payApplyMoney) as countPayApplyMoney,
			c.uninvoiceMoney,c.initInvotherMoney,c.initInvoiceMoney,c.initIncomeMoney,c.initPayMoney,
			c.deptId,c.deptName,c.feeDeptId,c.feeDeptName,c.isNeedPayapply,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.returnMoney,c.closeReason,
			c.payForBusiness,c.payForBusinessName,c.isNeedRelativeContract,c.hasRelativeContract,c.relativeContract,c.relativeContractId,c.chanceCode,c.prefBidDate,c.contractCode,c.projectPrefEndDate,c.delayPayDays,c.isBankbackLetter,c.backLetterEndDate,c.prefPayDate
		from
			oa_sale_other c
			left join
				(select i.objId,sum(i.invoiceMoney) as invoiceMoney from oa_finance_invoiceapply i where i.objId <> 0 and i.objType = 'KPRK-09' group by i.objId) iapp on c.id = iapp.objId
			left join
				(select i.objId,sum(if(i.isRed = 0,i.invoiceMoney,-i.invoiceMoney)) as invoiceMoney from oa_finance_invoice i where i.objType = 'KPRK-09' group by i.objId) i on c.id = i.objId
			left join
				(select a.objId,sum(a.money) as incomeMoney from oa_finance_income_allot a where a.objType = 'KPRK-09' group by a.objId ) a on c.id = a.objId
			left join
				(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
			left join
				(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
			left join
				(
                    select i.objId,
                        SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                        SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
                    from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
                    where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
                ) invo
                on c.id = invo.objId
		where c.isTemp = 0",
	"count_list" => "select
			sum(c.orderMoney) as orderMoney,sum(c.returnMoney) as returnMoney,
			sum(if(iapp.invoiceMoney is null,0,iapp.invoiceMoney)) as applyInvoice,
			sum(if(invo.invotherMoney is null ,0,invo.invotherMoney)) + sum(c.initInvotherMoney) as invotherMoney,
			sum(if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney)) + sum(c.initInvotherMoney) as confirmInvotherMoney,
			sum(if(p.payedMoney is null,0,p.payedMoney)) + sum(c.initPayMoney) -
			  sum(if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney)) - sum(c.initInvotherMoney)
			  - SUM(c.returnMoney) - SUM(c.uninvoiceMoney) as needInvotherMoney,
			sum(if(i.invoiceMoney is null,0,i.invoiceMoney)) + sum(c.initInvoiceMoney) as invoiceMoney,
			sum(if(a.incomeMoney is null,0,a.incomeMoney)) + sum(c.initIncomeMoney) as incomeMoney,
			sum(if(p.payedMoney is null,0,p.payedMoney)) + sum(c.initPayMoney) as payedMoney,
			sum(if(pa.payApplyMoney is null,0,pa.payApplyMoney)) +sum(c.initPayMoney) as payApplyMoney,
			sum(if(c.uninvoiceMoney is null,0,c.uninvoiceMoney)) as uninvoiceMoney,c.currency
		from
			oa_sale_other c
			left join
				(select i.objId,sum(i.invoiceMoney) as invoiceMoney from oa_finance_invoiceapply i where i.objId <> 0 and i.objType = 'KPRK-09' group by i.objId) iapp on c.id = iapp.objId
			left join
				(select i.objId,sum(if(i.isRed = 0,i.invoiceMoney,-i.invoiceMoney)) as invoiceMoney from oa_finance_invoice i where i.objType = 'KPRK-09' group by i.objId) i on c.id = i.objId
			left join
				(select a.objId,sum(a.money) as incomeMoney from oa_finance_income_allot a where a.objType = 'KPRK-09' group by a.objId ) a on c.id = a.objId
			left join
				(select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-02' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
			left join
				(select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-02' group by p.objId) p on c.id = p.objId
			left join
				(
                    select i.objId,
                        SUM(IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount))) AS invotherMoney,
                        SUM(IF(c.ExaStatus = 1,IF(c.isRed = 0, if(i.allCount = 0,i.amount,i.allCount), -if(i.allCount = 0,i.amount,i.allCount)),0)) AS confirmInvotherMoney
                    from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
                    where i.objId <>0 and i.objType = 'YFQTYD02' group by i.objId
                ) invo
                on c.id = invo.objId
		where c.isTemp = 0",
    "slt_costChangeRecord" => "select * from oa_sale_costchange_record c where 1=1 ",
    "slt_costChangeRecordCount" => "select sum(c.contractMoney) as contractMoney, sum(c.invoiceMoney) as invoiceMoney,
        sum(c.incomeMoney) as incomeMoney, sum(c.uninvoiceMoney) as uninvoiceMoney, sum(c.canUninvoiceMoney) as canUninvoiceMoney, sum(if(c.isRed = 1,-c.costAmount,c.costAmount)) as costAmount
        from oa_sale_costchange_record c where 1=1 ",
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "docCode",
        "sql" => " and c.orderCode like CONCAT('%',#,'%') "
	),
    array (
		"name" => "orderCodeSearch",
        "sql" => " and c.orderCode like CONCAT('%',#,'%') "
	),
    array (
		"name" => "orderCode",
		"sql" => " and c.orderCode=# "
	),
	array (
		"name" => "orderCodeSearch",
		"sql" => " and c.orderCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "orderCodeEq",
		"sql" => " and c.orderCode=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objCodeSearch",
		"sql" => " and c.objCode  like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "orderName",
		"sql" => " and c.orderName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "signCompanyName",
		"sql" => " and c.signCompanyName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "signCompanyNameEq",
		"sql" => " and c.signCompanyName =# "
	),
	array (
		"name" => "proName",
		"sql" => " and c.proName=# "
	),
	array (
		"name" => "proCode",
		"sql" => " and c.proCode=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array (
		"name" => "linkman",
		"sql" => " and c.linkman=# "
	),
	array (
		"name" => "signDate",
		"sql" => " and c.signDate=# "
	),
	array (
		"name" => "createDate",
		"sql" => " and date_format(c.createTime,'%Y%m%d') = date_format(#,'%Y%m%d')"
	),
	array (
		"name" => "orderMoney",
		"sql" => " and c.orderMoney=# "
	),
	array (
		"name" => "principalName",
		"sql" => " and c.principalName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "principalId",
		"sql" => " and c.principalId=# "
	),
	array (
		"name" => "principalIdAndCreateId",
		"sql" => " and (c.principalId=# or c.createId = #)"
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptsIn",
		"sql" => " and (c.deptId in(arr) or c.feeDeptId in(arr))"
	),
	array (
		"name" => "fundType",
		"sql" => " and c.fundType=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "isNeedStamp",
		"sql" => " and c.isNeedStamp=# "
	),
	array (
		"name" => "stampType",
		"sql" => " and c.stampType=# "
	),
	array (
		"name" => "isStamp",
		"sql" => " and c.isNeedStamp=# "
	),
	array (
		"name" => "statuses",
		"sql" => " and c.status in(arr) "
	),
	array (
		"name" => "status",
		"sql" => "and c.status=#"
	),
	array (
		"name" => "signedStatus",
		"sql" => " and c.signedStatus =# "
	),
	array (
		"name" => "uninvoiceMoney",
		"sql" => " and c.uninvoiceMoney =# "
	),
	array (
		"name" => "closeReason",
		"sql" => " and c.closeReason =# "
	),
    array (
		"name" => "payForBusiness",
		"sql" => " and c.payForBusiness =# "
	),
    array (
		"name" => "payForBusinessArr",
		"sql" => " and c.payForBusiness in(arr) "
	),
    array (
		"name" => "payForBusinessName",
		"sql" => " and c.payForBusinessName =# "
	),
    // 返款记录与不开票记录用
    array (
		"name" => "costChangeobjId",
		"sql" => " and c.objId =# "
	),
    array (
		"name" => "costChangeType",
		"sql" => " and c.costType =# "
	),
	/*********** 比较部分 ***********/
	array (
		"name" => "larger",
		"sql" => " and if(c.fundType = 'KXXZC',0,if(c.fundType = 'KXXZB',if(p.payedMoney is null,0,p.payedMoney) > if(invo.invotherMoney is null ,0,invo.invotherMoney),if(a.incomeMoney is null,0,a.incomeMoney) > if(i.invoiceMoney is null,0,i.invoiceMoney)))"
	),
	array (
		"name" => "largerEqu",
		"sql" => " and if(c.fundType = 'KXXZC',0,if(c.fundType = 'KXXZB',if(p.payedMoney is null,0,p.payedMoney) >= if(invo.invotherMoney is null ,0,invo.invotherMoney),if(a.incomeMoney is null,0,a.incomeMoney) >= if(i.invoiceMoney is null,0,i.invoiceMoney)))"
	),
	array (
		"name" => "equ",
		"sql" => " and if(c.fundType = 'KXXZC',0,if(c.fundType = 'KXXZB',if(p.payedMoney is null,0,p.payedMoney) = if(invo.invotherMoney is null ,0,invo.invotherMoney),if(a.incomeMoney is null,0,a.incomeMoney) = if(i.invoiceMoney is null,0,i.invoiceMoney)))"
	),
	array (
		"name" => "lessEqu",
		"sql" => " and if(c.fundType = 'KXXZC',0,if(c.fundType = 'KXXZB',if(p.payedMoney is null,0,p.payedMoney) <= if(invo.invotherMoney is null ,0,invo.invotherMoney),if(a.incomeMoney is null,0,a.incomeMoney) <= if(i.invoiceMoney is null,0,i.invoiceMoney)))"
	),
	array (
		"name" => "less",
		"sql" => " and if(c.fundType = 'KXXZC',0,if(c.fundType = 'KXXZB',if(p.payedMoney is null,0,p.payedMoney) < if(invo.invotherMoney is null ,0,invo.invotherMoney),if(a.incomeMoney is null,0,a.incomeMoney) < if(i.invoiceMoney is null,0,i.invoiceMoney)))"
	)
);