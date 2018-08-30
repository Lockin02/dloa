<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"base_list" => "select c.id,c.memberName,c.memberId,c.isInternal from oa_rd_team_member c where 1=1 ",
	"select_joinproject" => "select c.id,c.memberName,c.memberId from oa_rd_team_member c left join oa_rd_project p on c.projectId = p.id where 1=1 ",
	"countMember" => "select count(0) as num from  oa_rd_team_member c where 1=1 ",
	"onlyUser_list" => "select c.memberId as userId,c.memberName as name,'type' from oa_rd_team_member c where c.isUsing = '1'",
);


$condition_arr = array (
	array(
		"name" => "isUsing",
		"sql" => " and c.isUsing = #"
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId = #"
	),
	array(
		"name" => "projectIds",
		"sql" => " and c.projectId in(arr)"
	),
	array(
		"name" => "isInternal",
		"sql" => " and c.isInternal = #"
	),
	array(
		"name" => "isUsing",
		"sql" => " and c.isUsing = #"
	),
	array(
		"name" => "memberId",
		"sql" => " and c.memberId = #"
	)
);
?>
