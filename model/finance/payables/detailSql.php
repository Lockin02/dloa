<?php

/**
 * @author Show
 * @Date 2011年5月6日 星期五 16:22:12
 * @version 1.0
 * @description:明细表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.advancesId ,c.money ,c.cashsubject ,c.objId ,c.objCode ,c.objType ,c.orgFormType ,c.orgFormNo,c.payContent,c.expand1,c.expand2,c.expand3  from oa_finance_payables_detail c where 1=1 ",
	"select_count" => "select c.id ,c.advancesId ,sum(if(p.formType = 'CWYF-03',-c.money,c.money)) as money ,c.cashsubject ,c.objId ,c.objCode ,c.objType ,c.orgFormType ,c.orgFormNo,c.payContent,c.expand1,c.expand2,c.expand3 from oa_finance_payables_detail c left join  oa_finance_payables p on c.advancesId = p.id where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "advancesId",
		"sql" => " and c.advancesId=# "
	),
	array (
		"name" => "relatedId",
		"sql" => " and c.advancesId=# or belongId = #"
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "cashsubject",
		"sql" => " and c.cashsubject=# "
	),
	array (
		"name" => "objId",
		"sql" => " and c.objId=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
	array (
		"name" => "formType",
		"sql" => " and c.formType=# "
	),
	array (
		"name" => "formNo",
		"sql" => " and c.formNo=# "
	),
	array (
		"name" => "expand1",
		"sql" => " and c.expand1=# "
	)
)
?>