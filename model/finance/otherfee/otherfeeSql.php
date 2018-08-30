<?php
/**
 * @author Show
 * @Date 2013年6月7日 星期五 11:24:39
 * @version 1.0
 * @description:仓存信息表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.accountYear ,c.accountPeriod ,c.summary ,c.subjectName ,c.debit ,c.chanceCode ,c.trialProjectCode ,c.feeDeptName ,c.contractCode ,c.province from oa_finance_otherfee c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "accountYear",
		"sql" => " and c.accountYear=# "
	),
	array (
		"name" => "accountPeriod",
		"sql" => " and c.accountPeriod=# "
	),
	array (
		"name" => "summary",
		"sql" => " and c.summary=# "
	),
	array (
		"name" => "subjectName",
		"sql" => " and c.subjectName=# "
	),
	array (
		"name" => "debit",
		"sql" => " and c.debit=# "
	),
	array (
		"name" => "chanceCode",
		"sql" => " and c.chanceCode=# "
	),
	array (
		"name" => "trialProjectCode",
		"sql" => " and c.trialProjectCode=# "
	),
	array (
		"name" => "feeDeptName",
		"sql" => " and c.feeDeptName=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "province",
		"sql" => " and c.province=# "
	)
)
?>