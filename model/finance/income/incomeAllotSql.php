<?php
$sql_arr = array (
	"select_incomeallot" => "select c.id,c.objCode,c.objId,c.objType,c.incomeId,c.money,allotDate,
        c.rObjCode,c.moneyCurrency
	    from oa_finance_income_allot c where 1=1 ",
	"select_allotinobj" => "select
		c.id,c.incomeId,c.allotDate,if(i.formType = 'YFLX-TKD', -c.money,c.money) as money,i.formType,
		i.createTime,i.incomeNo,i.createName,
		i.inFormNum,i.incomeUnitName,i.incomeDate,i.incomeType
		from oa_finance_income_allot c left join oa_finance_income i on c.incomeId = i.id where 1=1",
	"select_income" => "select sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney
	    from oa_finance_income_allot c left join oa_finance_income i on c.incomeId = i.id where 1=1"
);
$condition_arr = array (
	array (
		"name" => "createId",
		"sql" => "and c.createId =#"
	),
	array (
		"name" => "incomeId",
		"sql" => "and c.incomeId =#"
	),
	array (
		"name" => "status",
		"sql" => "and c.status =#"
	),
	array (
		"name" => "objCode",
		"sql" => "and c.objCode=# "
	),
	array (
		"name" => "objId",
		"sql" => "and c.objId=# "
	),
	array (
		"name" => "objType",
		"sql" => "and c.objType=# "
	),
	array (
		"name" => "objTypes",
		"sql" => "and c.objType in(arr) "
	)
);