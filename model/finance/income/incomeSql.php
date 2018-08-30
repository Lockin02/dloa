<?php
$sql_arr = array (
	"select_income" => "select c.id,c.incomeNo,c.inFormNum,c.incomeUnitId,c.incomeUnitType,
		c.incomeUnitName,c.incomeDate,if(c.formType = 'YFLX-TKD',-c.incomeMoney,c.incomeMoney) as incomeMoney,
		c.incomeType,c.sectionType,c.status,c.province,
		c.createName,c.createTime,c.updateName,c.updateTime,c.isSended,c.remark,c.contractUnitName,
		c.contractUnitId,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.isAdjust
		from oa_finance_income c where 1=1",
	"count_all" => "select
		sum(if(c.formType = 'YFLX-TKD',-c.incomeMoney,c.incomeMoney)) as incomeMoney
		from oa_finance_income c where 1=1 ",
	"select_incomeAllot" => "select c.id,c.incomeNo,c.inFormNum,
		c.incomeUnitName,c.incomeDate,c.incomeMoney,c.incomeType,c.sectionType,c.remark,c.status,c.province,
		c.createName,c.createTime,c.updateName,c.updateTime,c.isSended
		from oa_finance_income c where c.sectionType<>'DKLX-FHK' and 1=1 ",
	"select_excelout" => "select
				c.id,c.incomeUnitName,c.incomeDate,c.incomeMoney,
				c.objId,c.objCode,c.objType,c.status,d.dataName as statusCN,c.createName,c.remark,
				c.contractUnitName,c.businessBelongName,c.province,c.isAdjust
			from
			(select
				c.id,c.incomeUnitName,c.incomeDate,if(formType = 'YFLX-TKD',
				if(a.objCode is null,-c.incomeMoney,-a.money),if(a.objCode is null,c.incomeMoney,a.money)) as incomeMoney,
				a.objId,a.objCode,a.objType,c.status,c.createName,c.remark,c.contractUnitName,c.businessBelongName,
				c.formBelong,c.formBelongName,c.businessBelong,c.province,c.isAdjust,c.incomeNo,
				c.inFormNum,c.isSended
			from oa_finance_income c left join oa_finance_income_allot a on c.id = a.incomeId where 1=1
			union
			select
				c.id,c.incomeUnitName,c.incomeDate,if(formType = 'YFLX-TKD',-allotAble,allotAble) as incomeMoney,
				null as objId,null as objCode,null as objType,c.status,c.createName,c.remark,c.contractUnitName,
				c.businessBelongName,c.formBelong,c.formBelongName,c.businessBelong,c.province,c.isAdjust,c.incomeNo,
				c.inFormNum,c.isSended
			from oa_finance_income c where c.status = 'DKZT-BFFP'
			) c
			left join
			oa_system_datadict d
			on c.status = d.dataCode where 1=1
		",
	"select_detail" => "select
		i.id,i.incomeId,i.allotDate,if(c.formType = 'YFLX-TKD', -i.money,i.money) as money,c.formType,c.createTime,c.incomeNo,c.createName,
		c.inFormNum,c.incomeUnitName,c.incomeDate,c.incomeType,c.incomeMoney,c.status,c.remark
		from oa_finance_income c left join oa_finance_income_allot i on i.incomeId = c.id where 1=1"
);

$condition_arr = array (
	array (
		"name" => "createId",
		"sql" => "and c.createId =#"
	),
	array (
		"name" => "status",
		"sql" => "and c.status =#"
	),
	array (
		"name" => "incomeNo",
		"sql" => "and c.incomeNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "inFormNum",
		"sql" => "and c.inFormNum like CONCAT('%',#,'%')"
	),
	array(
		"name" => "formType",
		"sql" => "and c.formType =# "
	),
    array(//客户名称,用于快速搜索
   		"name" => "incomeUnitName",
   		"sql" => " and c.incomeUnitName like CONCAT('%',#,'%') "
    ),
	array (
		"name" => "incomeUnitId",
		"sql" => "and c.incomeUnitId = #"
	),
	array (
		"name" => "contractUnitId",
		"sql" => "and c.contractUnitId = #"
	),
	array (
		"name" => "contractUnitName",
		"sql" => "and c.contractUnitName = #"
	),
    array(//客户省份快速搜索
   		"name" => "province",
   		"sql" => " and c.province like CONCAT('%',#,'%') "
    ),
	array(
		"name" => "isSended",
		"sql" => "and c.isSended =# "
	),
	array(
		"name" => "incomeMoney",
		"sql" => "and c.incomeMoney =# "
	),
	array(
		"name" => "incomeUnitType",
		"sql" => "and c.incomeUnitType =# "
	),
	array(
		"name" => "incomeDateSearch",
		"sql" => "and c.incomeDate like BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "incomeDate",
		"sql" => "and c.incomeDate = # "
	),
	array(
		"name" => "beginYearMonth",
		"sql" => "and date_format(c.incomeDate,'%Y%m') >= # "
	),
	array(
		"name" => "endYearMonth",
		"sql" => "and date_format(c.incomeDate,'%Y%m') <= # "
	),
	array (
		"name" => "objCode",
		"sql" => " and  c.id in(select i.incomeId from oa_finance_income_allot i where i.objCode like CONCAT('%',#,'%')) "
	),
	array (
		"name" => "objIdArr",
		"sql" => " and i.objId in(arr)"
	)
);