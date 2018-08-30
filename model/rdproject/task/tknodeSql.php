<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务前置任务sql
 *
 */
 $sql_arr = array (
	"select_default" => "select c.id,c.nodeName,c.parentId,c.parentName,c.lft,c.rgt,c.charegeName,c.charegeId,c.remark,c.projectId,c.projectName,c.planId,c.planName from oa_rd_task_node c where 1=1 ",
	"select_gridinfo"=>"select c.id,c.nodeName as name,c.parentId,c.parentName,c.lft,c.rgt,c.charegeName as chargeName,c.charegeId as chargeId,c.remark,c.projectId,c.projectName,c.planId,c.planName,0 as leaf  from oa_rd_task_node c where 1=1 ",
	"select_treeinfo"=>"select c.id,c.nodeName as text,c.parentId,c.parentName,c.lft,c.rgt,c.charegeName,c.charegeId,c.remark,c.projectId,c.projectName,c.planId,c.planName,case (c.rgt-c.lft) when 1 then 1 else 0 end as leaf  from oa_rd_task_node c where 1=1 ",
	"select_planTask"=>"select c.* from oa_plan_task_view c where 1=1 "

);


$condition_arr = array (
	array (
		"name" => "parentId",
		"sql" => "and c.parentId=#"
	),
	array(
		"name"=>"planId",
		"sql"=>" and c.planId=#"
	),
	array(
		"name"=>"belongNodeId",
		"sql"=>" and c.belongNodeId=#"
	),
	array(
		"name"=>"ajaxNodeName",
		"sql"=>"and c.nodeName=#")
);
?>
