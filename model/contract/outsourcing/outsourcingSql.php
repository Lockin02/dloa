<?php
/**
 * @author Show
 * @Date 2011年12月3日 星期六 10:29:00
 * @version 1.0
 * @description:外包合同 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.orderCode ,c.objCode ,c.orderName ,c.signCompanyName ,c.signCompanyId ,c.proName ,c.proCode ,c.address ,c.phone ,c.linkman ,c.orderMoney ,c.signDate ,c.principalName ,
			c.principalId ,c.deptId ,c.deptName ,c.outsourceType ,c.outsourceTypeName ,c.projectCode ,c.projectId ,c.payCondition ,c.description ,c.ExaDT ,c.ExaStatus ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.stampType ,c.isNeedStamp,date_format(c.createTime,'%Y-%m-%d') as createDate,c.isNeedPayapply,
			c.beginDate,c.endDate,c.outsourcing,c.outsourcingName,c.payType,c.payTypeName,c.outContractCode,c.projectType,c.projectTypeName,c.projectName,c.signedStatus,c.signedDate,c.signedMan,c.signedManId,c.remark,c.initPayMoney,c.initInvoiceMoney,c.addInvoiceCount,c.formBelong,c.formBelongName,
			c.businessBelong,c.businessBelongName from oa_sale_outsourcing c where c.isTemp = 0 ",
	"select_info" => "select
			c.id ,c.orderCode ,c.objCode ,c.orderName ,c.signCompanyName ,c.signCompanyId ,c.proName ,c.proCode ,c.address ,c.phone ,c.linkman ,c.orderMoney ,
			c.signDate ,c.principalName ,c.principalId ,c.deptId ,c.deptName ,c.outsourceType ,c.outsourceTypeName,c.projectCode ,c.projectId ,c.payCondition ,c.description ,c.ExaDT ,c.status,
			c.ExaStatus ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.initPayMoney,c.initInvoiceMoney,
			if(p.money is null,0,p.money) as orgApplyedMoney,
			if(p.money is null,0,p.money) + c.initPayMoney as applyedMoney,
			if(pp.money is null,0,pp.money) + c.initPayMoney as payedMoney ,
			if(pp.money is null,0,pp.money) as orgPayedMoney,
			if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney as allCount,
			if(d.allCount is null,0,d.allCount) as orgAllCount,
			date_format(c.createTime,'%Y-%m-%d') as createDate,c.isNeedPayapply,c.signedStatus,c.signedDate,c.signedMan,c.signedManId,c.remark,c.stampType ,c.isNeedStamp ,c.isStamp,
			c.beginDate,c.endDate,c.outsourcing,c.outsourcingName,c.payType,c.payTypeName,c.payType,c.outContractCode,c.projectType,c.projectTypeName,c.projectName,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.closeReason
		from
			oa_sale_outsourcing c
			left join
			(
				select p.objId,sum(p.money) as money from oa_finance_payablesapply_detail p left join oa_finance_payablesapply pa on p.payapplyId = pa.id where pa.ExaStatus != '打回' and pa.status not in('FKSQD-04','FKSQD-05') and p.objType = 'YFRK-03' group by p.objId,p.objType
			) p on c.id = p.objId
			left join
			(
				select p.objId,sum(if(pa.formType = 'CWYF-03',-p.money,p.money)) as money from oa_finance_payables_detail p left join oa_finance_payables pa on p.advancesId = pa.id where p.objType = 'YFRK-03' group by p.objId,p.objType
			) pp on c.id = pp.objId
			left join
			(
				select d.objId,
				    sum(if(i.isRed = 0,if(d.allCount = 0,d.amount ,d.allCount ),if(d.allCount = 0,-d.amount ,-d.allCount ))) as allCount
                from oa_finance_invother i left join oa_finance_invother_detail d on i.id = d.mainId
                where d.objId <> 0 and d.objType = 'YFQTYD01' group by  d.objId,d.objType
			)d on c.id = d.objId
		where c.isTemp = 0",
	"count_list" => "select
			sum(c.orderMoney) as orderMoney ,
			sum(c.initPayMoney) as initPayMoney ,
			sum(c.initInvoiceMoney) as initInvoiceMoney ,
			sum(if(p.money is null,0,p.money)) + sum(c.initPayMoney) as applyedMoney,
			sum(if(pp.money is null,0,pp.money)) + sum(c.initPayMoney) as payedMoney ,
			sum(if(d.allCount is null,0,d.allCount)) + sum(c.initInvoiceMoney) as allCount,
			sum(if(p.money is null,0,p.money)) as orgApplyedMoney,
			sum(if(pp.money is null,0,pp.money)) as orgPayedMoney ,
			sum(if(d.allCount is null,0,d.allCount)) as orgAllCount
		from
			oa_sale_outsourcing c
			left join
			(
				select p.objId,sum(p.money) as money from oa_finance_payablesapply_detail p left join oa_finance_payablesapply pa on p.payapplyId = pa.id where pa.ExaStatus != '打回' and pa.status not in('FKSQD-04','FKSQD-05') and p.objType = 'YFRK-03' group by p.objId,p.objType
			) p on c.id = p.objId
			left join
			(
				select p.objId,sum(if(pa.formType = 'CWYF-03',-p.money,p.money)) as money from oa_finance_payables_detail p left join oa_finance_payables pa on p.advancesId = pa.id where p.objType = 'YFRK-03' group by p.objId,p.objType
			) pp on c.id = pp.objId
			left join
			(
				select
				    d.objId,
				    sum(if(i.isRed = 0,if(d.allCount = 0,d.amount ,d.allCount ),if(d.allCount = 0,-d.amount ,-d.allCount ))) as allCount
                from oa_finance_invother i left join oa_finance_invother_detail d on i.id = d.mainId
                where d.objId <> 0 and d.objType = 'YFQTYD01' group by  d.objId,d.objType
			)d on c.id = d.objId
		where c.isTemp = 0"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.Id in(arr) "
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
		"name" => "outContractCode",
		"sql" => " and c.outContractCode like CONCAT('%',#,'%')  "
	),
    array (
        "name" => "orderName",
        "sql" => " and c.orderName like CONCAT('%',#,'%')  "
    ),
	array (
		"name" => "signCompanyName",
		"sql" => " and c.signCompanyName like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "signCompanyId",
		"sql" => " and c.signCompanyId=# "
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
		"name" => "orderMoney",
		"sql" => " and c.orderMoney=# "
	),
	array (
		"name" => "signDate",
		"sql" => " and c.signDate=# "
	),
	array (
		"name" => "principalName",
		"sql" => " and c.principalName like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "principalId",
		"sql" => " and c.principalId=# "
	),
	array (
		"name" => "principalIdAndCreateId",
		"sql" => " and (c.principalId=# or createId = #)"
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptIdArr",
		"sql" => " and c.deptId in(arr) "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "outsourceType",
		"sql" => " and c.outsourceType=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
    array (
        "name" => "projectCodeSearch",
        "sql" => " and c.projectCode like CONCAT('%',#,'%')   "
    ),
    array (
        "name" => "projectNameSearch",
        "sql" => " and c.projectName like CONCAT('%',#,'%')   "
    ),
	array (
		"name" => "projectCodeEq",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%')   "
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
		"sql" => " and c.isStamp=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statuses",
		"sql" => " and c.status in(arr) "
	),
	array (
		"name" => "signedStatus",
		"sql" => " and c.signedStatus=# "
	),
	array (
		"name" => "addInvoiceCount",
		"sql" => " and c.addInvoiceCount=# "
	),
	array (
			"name" => "ownCompany",
			"sql" => " and c.ownCompany=# "

	),
	array(
		"name" => "businessBelong",
		"sql" => "and c.businessBelong = #"
	),
	array(
		"name" => "businessBelongName",
		"sql" => "and c.businessBelongName = #"
	),
	array(
		"name" => "formBelong",
		"sql" => "and c.formBelong = #"
	),
	array(
		"name" => "formBelongName",
		"sql" => "and c.formBelongName = #"
	),
	array(
		"name" => "closeReason",
		"sql" => "and c.closeReason = #"
	),

	/*********** 比较部分 ***********/
	array (
		"name" => "larger",
		"sql" => " and if(pp.money is null,0,pp.money) + c.initPayMoney > if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney "
	),
	array (
		"name" => "largerEqu",
		"sql" => " and if(pp.money is null,0,pp.money) + c.initPayMoney >= if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney "
	),
	array (
		"name" => "equ",
		"sql" => " and if(pp.money is null,0,pp.money) + c.initPayMoney = if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney "
	),
	array (
		"name" => "lessEqu",
		"sql" => " and if(pp.money is null,0,pp.money) + c.initPayMoney <= if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney "
	),
	array (
		"name" => "less",
		"sql" => " and if(pp.money is null,0,pp.money) + c.initPayMoney < if(d.allCount is null,0,d.allCount) + c.initInvoiceMoney "
	)
)
?>