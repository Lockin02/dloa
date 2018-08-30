<?php
/*
 * Created on 2010-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'base_list' => 'select c.id,c.weekTitle,c.weekBeginDate,c.weekEndDate,c.depName,c.createName,c.updateTime from oa_rd_worklog_summary c where 1=1 ',
	'checkIsSet' => "select c.id from oa_rd_worklog_summary c where 1=1 and now('Y-m-d') = c.logDate ",
	'getSummary' => 'select c.id , c.logDate ,c.description ,c.problem from oa_rd_worklog_summary c where 1=1 '
);

$condition_arr = array (
	array (
		"name" => "user_id",//´´½¨ÈË
		"sql" => "and c.createId = # "
	),
	array (
		"name" => "startDate",//
		"sql" => "and (c.logDate > # or c.logDate = # ) "
	),
	array (
		"name" => "endDate",//
		"sql" => "and (c.logDate < # or c.logDate = # )"
	),
	array(
		"name" => "weekId",
		"sql" => " and c.weekId = # "
	)
);
?>