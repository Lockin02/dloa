<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"base_list" => "select c.id,c.userName,c.userId,c.purviewKey,c.projectId,c.planId,c.planOwner from oa_rd_plan_purview c where 1=1 ",
	"countMember" => "select count(0) as num from  oa_rd_team_member c where 1=1 ",
	"onlyUser_list" => "select c.memberName as userName,c.memberId as userId from oa_rd_team_member c where c.isUsing = '1'",
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
		"name" => "planId",
		"sql" => " and c.planId = # "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName = # "
	)
//	,
//	array(
//		"name" => "userId",
//		"sql" => " and c.userId = # "
//	)
);
?>
