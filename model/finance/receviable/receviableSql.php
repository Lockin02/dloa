<?php
$sql_arr = array (
	"select_receviable" => "select c.id,c.contNumber,c.contName,c.money,c.customerName,c.customerType,c.province,c.temporaryNo,
		c.customerId,i.invoiceMoney,a.incomeMoney,(i.invoiceMoney - a.incomeMoney) as remainMoney ,round((a.incomeMoney/c.money),3)*100 as percentage," .
		"round((i.invoiceMoney/c.money),3)*100 as percentageInv,c.contStatus  from oa_contract_sales  c left join
		(select r.objCode,sum(i.invoiceMoney) as invoiceMoney from oa_finance_invoice i inner join oa_finance_invoice_relate r on i.id = r.invoiceId group by r.objCode,r.objType) as i
		on c.contNumber = i.objCode left join
		(select i.exObjCode,sum(money)  as incomeMoney from oa_finance_income_allot a inner join oa_finance_process_income i on a.id = i.allotId group by i.exObjCode) as a
		on c.contNumber = a.exObjCode  where c.isUsing = 1  ",
	"default" => "select i.id,i.objCode,i.objType,sum(i.invoiceMoney) as allInvoiceMoney from oa_finance_invoice i group by i.objCode,i.objType ",
	'detail_union' => "select db.*
		from
		(select
		i.id as objId,i.invoiceCode as objCode , 'invoice' as formType , i.invoiceTime as formDate ,i.invoiceUnitId as customerId ," .
		"i.invoiceUnitName as customerName ,i.deptName as deptName ,i.deptId as deptId,i.salesman as salesman ,i.salesmanId as salesmanId ," .
		"i.invoiceMoney as amount ,i.subjects as subjects ,null as trueReceive,concat('invoice',i.id) as id
		from oa_finance_invoice i
		union
		select
		c.id as objId, c.incomeNo as objCode ,c.formType as formType ,c.incomeDate as formDate ,c.incomeUnitId as customerId ," .
		"c.incomeUnitName as customerName ,c.deptName as deptName ,c.deptId as deptId,c.salesman as salesman ,c.salesmanId as salesmanId ," .
		"null as amount ,c.cashsubject as subjects ,if( c.formType = 'YFLX-TKD' , -c.incomeMoney,c.incomeMoney) as trueReceive,concat(c.formType,c.id) as id
		from oa_finance_income c ) db where 1=1",
	'sum_union' => "select sum(db.amount) as amount,sum(db.trueReceive) as trueReceive, sum(db.balance ) as balance
		from
		(select
		i.invoiceTime as formDate ,i.invoiceUnitId as customerId ," .
		"i.deptId as deptId,i.salesmanId as salesmanId ," .
		"i.invoiceMoney as amount ,null as trueReceive,concat('invoice',i.id) as id," .
		"i.invoiceMoney as balance
		from oa_finance_invoice i
		union
		select
		c.incomeDate as formDate ,c.incomeUnitId as customerId ," .
		"c.deptId as deptId,c.salesmanId as salesmanId ," .
		"null as amount ,if( c.formType = 'YFLX-TKD' , -c.incomeMoney,c.incomeMoney) as trueReceive,concat(c.formType,c.id) as id," .
		"if( c.formType = 'YFLX-TKD' , c.incomeMoney, - c.incomeMoney) as balance
		from oa_finance_income c ) db where 1=1",
    'incomeAnalysis' => "select
            c.id,c.incomeNo,c.inFormNum,
            c.incomeUnitName,c.incomeDate,if( c.formType = 'YFLX-TKD' , -c.incomeMoney,c.incomeMoney) as incomeMoney,c.incomeType,c.sectionType,c.province,c.formType,c.status,
            c.createName,c.createTime,c.updateName,c.updateTime,c.isSended,if( c.formType = 'YFLX-TKD' , -c.allotAble,c.allotAble) as allotAble,
            a.objId,a.objCode,a.objType,if( c.formType = 'YFLX-TKD' , - a.money, a.money) as money
        from oa_finance_income_allot a right join oa_finance_income c on c.id = a.incomeId",
    'incomeAnalysis2' => "select
						o.id,
						o.orgid,o.orderCode,o.orderTempCode,
						o.orderName,
						o.objType,
						o.prinvipalId,
						o.prinvipalName,
						o.areaName,
						o.areaPrincipal,
						o.areaPrincipalId,
						o.sign,o.createTime,o.customerProvince,
						year(o.createTime) as createYear,
						month(o.createTime) as createMonth,
						if(o.signDate != '0000-00-00',o.signDate,null) as signDate,
						o.detail,
						o.allProNum,
						o.customerId,
						o.customerName,
						o.customerType,
						d.dataName as customerTypeName,
						if(o.sign = '是',o.orderMoney,o.orderTempMoney) AS thisOrderMoney,
						i.incomeMoney,
						i.invoiceMoney,
						(if(o.sign = '是',o.orderMoney,o.orderTempMoney) - i.invoiceMoney) as unInvoiceMoney,
						(if(o.sign = '是',o.orderMoney,o.orderTempMoney) - i.incomeMoney) as unIncomeMoney,
						round((i.incomeMoney/if(o.sign = '是',o.orderMoney,o.orderTempMoney)*100),2) as completed,
						i.psType,
						i.incomeDates,
						i.invoiceDates,
						i.invoiceType,
						userArea.thisAreaName

						from
						contractview_all o left join financeview_is_03_sumorder i on o.orgId = i.objId and o.objType = i.orderObjType
						left join (SELECT
						u.USER_ID,
						a.name as thisAreaName
						from user u left join area a on u.area = a.id ) userArea on o.prinvipalId = userArea.USER_ID
						left join oa_system_datadict d on o.customerType = d.dataCode where o.ExaStatus = '完成' and 1=1"
		);
$condition_arr = array (
	array (
		"name" => "contNumber",
		"sql" => "and c.contNumber like CONCAT('%',#,'%')"
	),
	array(
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus =#"
	),
	array(
		"name" => "contStatus",
		"sql" => "and c.contStatus in(arr)"
	),
	array (
		"name" => "beginMonth",
		"sql" => "and month(formDate) >= # "
	),
	array (
		"name" => "begin",
		"sql" => "and month(createTime) >= # "
	),
	array (
		"name" => "end",
		"sql" => "and month(createTime) <= # "
	),
	array (//年相等
		"name" => "searchYear",
		"sql" => "and year(createTime) = #"
	),
	array (
		"name" => "endMonth",
		"sql" => "and month(formDate) <= # "
	),
	array (//年相等
		"name" => "year",
		"sql" => "and year(formDate) = #"
	),
	array (//月相等
		"name" => "month",
		"sql" => "and month(formDate) = #"
	),
	array (
		"name" => "customerId",
		"sql" => "and customerId = #"
	),
    array (
        "name" => "incomeUnitId",
        "sql" => "and c.incomeUnitId = #"
    ),
	array (
		"name" => "salesmanId",
		"sql" => "and salesmanId = # "
	),
	array (
		"name" => "deptIds",
		"sql" => "and deptId in(arr)"
	),
	array(
		"name" => "formDate",
		"sql" => " and formDate >= #"
	),
    array(
        "name" => "formType",
        "sql" => " and c.formType = #"
    ),
	array(
		"name" => "formDateMonth",
		"sql" => " and month(formDate) < #"
	),
    array (
        "name" => "beginMonthIncome",
        "sql" => "and month(incomeDate) >= # "
    ),
    array (
        "name" => "endMonthIncome",
        "sql" => "and month(incomeDate) <= # "
    ),
    array (//年相等
        "name" => "yearIncome",
        "sql" => "and year(incomeDate) = #"
    ),
    array (
        "name" => "objId",
        "sql" => "and a.objId = # "
    ),
    array (//分配对象
        "name" => "objType",
        "sql" => "and a.objType = # "
    ),
    array (//到款状态
        "name" => "status",
        "sql" => "and c.status = # "
    ),
    array (
        "name" => "prinvipalId",
        "sql" => "and o.prinvipalId = # "
    ),
    array (
        "name" => "customerType",
        "sql" => "and o.customerType = # "
    ),
    array (
        "name" => "custId",
        "sql" => "and o.customerId = # "
    ),
    array (
        "name" => "areaName",
        "sql" => "and o.areaName = # "
    ),
    array (
        "name" => "customerProvince",
        "sql" => "and o.customerProvince = # "
    ),
    array (
        "name" => "orderType",
        "sql" => "and o.objType = # "
    ),
    array (
        "name" => "objSign",
        "sql" => "and o.sign = # "
    ),
    array (
        "name" => "orderId",
        "sql" => "and o.orgid = # "
    )
);
?>
