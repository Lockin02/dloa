<?php
/**
 * @author Show
 * @Date 2011年5月8日 星期日 13:55:05
 * @version 1.0
 * @description:付款申请(新) sql配置文件
 */
$sql_arr = array (
	'select_default'=>"select c.id ,c.formNo ,c.formDate,c.isAdvPay,c.payDate ,c.supplierName ,c.sourceType,c.payFor,
			if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney) as payMoney ,
			if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney) as payedMoney ,
			if(c.payFor = 'FKLX-03' ,-c.payMoneyCur,c.payMoneyCur) as payMoneyCur ,
			c.status ,c.deptName,c.salesman,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,c.updateName ,
			c.updateTime,c.printCount,c.feeDeptName,c.feeDeptId,c.bank,c.account,c.actPayDate,c.exaId,c.exaCode,
			c.ExaUser,c.ExaUserId,c.ExaContent,c.shareStatus,c.period,
			if(c.payFor = 'FKLX-03' ,-c.shareMoney,c.shareMoney) as shareMoney,
			c.lastPrintTime,c.isInvoice,c.comments,c.isPay,c.instruction,c.formBelong,c.formBelongName,c.businessBelong,
			c.businessBelongName,c.auditDate,c.remark,c.payForBusiness,c.currency,c.currencyCode,c.rate,d.pchMoney
		from oa_finance_payablesapply c left join (select payapplyId, purchaseMoney as pchMoney from oa_finance_payablesapply_detail group by payapplyId) d on c.id = d.payapplyId where 1",
	'select_excel'=>"select
			c.id ,c.formNo ,c.formDate,c.isAdvPay,c.payDate ,c.supplierName ,c.sourceType,ds.dataName as sourceTypeCN,
			c.payFor,dp.dataName as payForCN,c.auditDate,c.period,
			if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney) as payMoney ,
			if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney) as payedMoney ,
			d.pchMoney,c.status ,dsa.dataName as statusCN ,c.deptName,c.salesman,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,
			c.updateName ,c.remark, c.updateTime,c.printCount,c.feeDeptName,c.feeDeptId,c.bank,c.account,c.actPayDate,
			c.exaId,c.exaCode,c.ExaUser,c.ExaContent, c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from
			oa_finance_payablesapply c
			left join
			(select dataName,dataCode from oa_system_datadict where parentCode = 'YFRK' ) ds on c.sourceType = ds.dataCode
			left join
			(select dataName,dataCode from oa_system_datadict where parentCode = 'FKLX' ) dp on c.payFor = dp.dataCode
			left join
			(select dataName,dataCode from oa_system_datadict where parentCode = 'FKSQD' ) dsa on c.status = dsa.dataCode
			left join
	        (select payapplyId, purchaseMoney as pchMoney from oa_finance_payablesapply_detail group by payapplyId) d on c.id = d.payapplyId
		where 1=1 ",
	'select_excel2'=>"select
			c.id ,c.formNo ,c.formDate,c.supplierName ,c.remark,c.bank,c.account,c.deptName,c.salesman,
			if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney) as payMoney ,
			if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney) as payedMoney,
			if(c.payFor = 'FKLX-03' ,-c.payMoneyCur,c.payMoneyCur) as payMoneyCur,d.pchMoney,c.currency,c.rate
		from
			oa_finance_payablesapply c
	    left join
	        (select payapplyId, purchaseMoney as pchMoney from oa_finance_payablesapply_detail group by payapplyId) d on c.id = d.payapplyId
		where 1=1 ",
	'count_all' => "select sum(if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney)) as payMoney,
		sum(if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney)) as payedMoney,
		sum(if(c.payFor = 'FKLX-03' ,-c.shareMoney,c.shareMoney)) as shareMoney,
		sum(if(c.payFor = 'FKLX-03' ,-c.payMoneyCur,c.payMoneyCur)) as payMoneyCur,
		sum(d.pchMoney) as pchMoney
		from oa_finance_payablesapply c
		left join
	        (select payapplyId, purchaseMoney as pchMoney from oa_finance_payablesapply_detail group by payapplyId) d on c.id = d.payapplyId
		where 1",
	'count_allnew' => "select sum(if(c.currencyCode = 'CNY',0,if(c.payFor = 'FKLX-03' ,-c.payMoney,c.payMoney))) as payMoney,
		sum(if(c.payFor = 'FKLX-03' ,-c.payedMoney,c.payedMoney)) as payedMoney,
		sum(if(c.payFor = 'FKLX-03' ,-c.shareMoney,c.shareMoney)) as shareMoney,
		sum(if(c.currencyCode <> 'CNY',0,if(c.payFor = 'FKLX-03' ,-c.payMoneyCur,c.payMoneyCur))) as payMoneyCur,
		sum(d.pchMoney) as pchMoney
		from oa_finance_payablesapply c
		left join
	        (select payapplyId, purchaseMoney as pchMoney from oa_finance_payablesapply_detail group by payapplyId) d on c.id = d.payapplyId
		where 1",
	'select_auditing' =>
		"select w.task,p.ID as id, u.USER_NAME as UserName, c.id as applyId,c.formNo,c.supplierName,c.formDate,
		c.payMoney,c.payType,
		c.createName,c.ExaStatus,c.payCondition,c.status,c.createTime
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_finance_payablesapply c
		where
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName, c.id as applyId,c.formNo,c.supplierName,c.formDate,
		c.payMoney,c.payType,
		c.createName,c.ExaStatus,c.payCondition,c.status,c.createTime
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_finance_payablesapply c where
		p.Flag='1' and
		w.Pid =c.id ",
	'sum_payedmoney' => "select c.payMoney , sum(p.amount) as thisPayedMoney from oa_finance_payablesapply c left join " .
			"oa_finance_payables p on c.id = p.payApplyId ",
	'select_history' => "select
			c.id , c.formNo ,c.formDate,c.payFor,c.supplierName ,
			sum(if(c.payFor = 'FKLX-03' ,-d.money,d.money)) as payMoney ,
			sum(if(c.status = 'FKSQD-03',if(c.payFor = 'FKLX-03' ,-d.money,d.money),0)) as payedMoney ,
			sum(if(c.payFor = 'FKLX-03' ,-d.money,d.money)) as money,
			c.status ,c.deptName,c.createId,c.payDate,c.actPayDate,c.period,
			c.deptId,c.salesman,c.salesmanId,c.bank,c.ExaStatus ,c.ExaDT ,c.createName ,c.createTime ,
			c.updateName ,c.updateTime,d.objCode, d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'select_historycount' => "select
			sum(if(c.payFor = 'FKLX-03' ,-d.money,d.money)) as payMoney ,
			sum(if(c.status = 'FKSQD-03',if(c.payFor = 'FKLX-03' ,-d.money,d.money),0)) as payedMoney ,
			sum(if(c.payFor = 'FKLX-03' ,-d.money,d.money)) as money
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId  ",
	'sum_list' => "select c.id,if(sum(d.money) is null ,0,sum(if(c.payFor = 'FKLX-03',-d.money,d.money) )) as payed " .
		"from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId",
	'sum_listAll' => "select c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money) ) as payed " .
		"from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId",
	'select_detail' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,
			c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
			c.deptId,c.salesman,c.salesmanId,c.bank,d.money,d.objCode,
			d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName,c.formBelong,c.formBelongName,
			c.businessBelong,c.businessBelongName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'select_detailcount' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,
			c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
			c.deptId,c.salesman,c.salesmanId,c.bank,sum(d.money) as money,d.objCode,c.sourceType,
			d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId ",
	'select_detailcountNew' => "select c.id ,c.id as payApplyId,c.formNo as payApplyNo,CURDATE() as formDate,
			c.payFor as formType,c.supplierName ,c.payMoney as amount,c.deptName,
			c.deptId,c.salesman,c.salesmanId,c.bank,d.money as money,d.objCode,c.sourceType,c.feeDeptName,c.feeDeptId,
			c.isEntrust,c.place,c.rate,c.currency,c.currencyCode,
			d.objId,d.objType,c.account,c.payType,c.remark,c.supplierId,c.supplierName,d.expand1,d.expand2,d.expand3,
			d.productNo,d.productName,d.number,d.payDesc,
			c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from oa_finance_payablesapply c left join oa_finance_payablesapply_detail d on c.id = d.payapplyId "
);

$condition_arr = array (
    array(
   		"name" => "id",
   		"sql" => " and c.id=# "
    ),
    array(
   		"name" => "ids",
   		"sql" => " and c.id in(arr) "
    ),
    array(
   		"name" => "formNo",
   		"sql" => " and c.formNo=# "
    ),
    array(
   		"name" => "formNoSearch",
   		"sql" => " and c.formNo  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
    ),
    array(
   		"name" => "shareStatus",
   		"sql" => " and c.shareStatus = #"
    ),
    array(
   		"name" => "payDateEnd",
   		"sql" => " and (c.payDate <= # or c.payDate is null or c.payDate = '0000-00-00' )"
    ),
    array(
   		"name" => "formDateBegin",
   		"sql" => " and c.actPayDate >= #"
    ),
    array(
   		"name" => "formDateEnd",
   		"sql" => " and c.actPayDate <= # "
    ),
    array(
   		"name" => "payMoney",
   		"sql" => " and c.payMoney =# "
    ),
    array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
    ),
    array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
    ),
    array(
   		"name" => "deptIds",
   		"sql" => " and c.deptId in(arr) "
    ),
    array(
   		"name" => "salesmanId",
   		"sql" => " and c.salesmanId=# "
    ),
    array(
   		"name" => "salesmanSearch",
   		"sql" => " and c.salesman like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "deptNameSearch",
   		"sql" => " and c.deptName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "feeDeptNameSearch",
   		"sql" => " and c.feeDeptName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "payFor",
   		"sql" => " and c.payFor=# "
    ),
    array(
   		"name" => "payForArr",
   		"sql" => " and c.payFor in(arr) "
    ),
    array(
   		"name" => "noPayFor",
   		"sql" => " and c.payFor <> # "
    ),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
    array(
   		"name" => "noStatus",
   		"sql" => " and c.status <> # "
    ),
    array(
        "name" => "noStatusArr",
        "sql" => " and c.status not in(arr)"
    ),
    array(
   		"name" => "statusArr",
   		"sql" => " and c.status in(arr)"
    ),
    array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
    ),
    array(
   		"name" => "noExaStatus",
   		"sql" => " and c.ExaStatus <> # "
    ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
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
		"name" => "dObjId",//从表对象编号
		"sql"=>" and d.objId = # "
	),
	array(
		"name" => "dObjIds",//从表对象编号
		"sql"=>" and d.objId in(arr)"
	),
	array(
		"name" => "dObjType",//从表对象编号
		"sql"=>" and d.objType= # "
	),
	array(
		"name" => "dProductId",//从表对象编号
		"sql"=>" and d.ProductId= # "
	),
    array(
        "name" => "dExpand1",//从表扩展字段1
        "sql"=>" and d.expand1= # "
    ),
	array(//关联编号
		"name"=>"objCodeSearch",
		"sql" => " and  c.id in(select i.payapplyId from oa_finance_payablesapply_detail i where i.objCode like CONCAT('%',#,'%')) "
	),
	array(
		"name" => "sourceTypePurchase",
   		"sql" => " and (c.sourceType = 'YFRK-01' or c.sourceType = 'YFRK-04' or sourceType is null) "
	),
	array(
		"name" => "sourceType",
   		"sql" => " and c.sourceType = #"
	),
	array(
		"name" => "exaId",
   		"sql" => " and c.exaId = #"
	),
	array(
		"name" => "exaCode",
   		"sql" => " and c.exaCode = #"
	),
	array(
		"name" => "payFor",
   		"sql" => " and c.payFor = #"
	),
	array(
   		"name" => "isInvoice",
   		"sql" => " and c.isInvoice=# "
    ),
	array(
   		"name" => "comments",
   		"sql" => " and c.comments=# "
    ),
	array(
   		"name" => "isEntrust",
   		"sql" => " and c.isEntrust=# "
    ),
    array(
        "name" => "period",
        "sql" => " and c.period=# "
    )
);