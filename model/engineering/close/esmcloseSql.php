<?php
/**
 * @author show
 * @Date 2015年2月6日 10:37:03
 * @version 1.0
 * @description:项目关闭申请 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.applyId ,c.applyName ,
			c.content ,c.applyDate ,c.ExaStatus ,c.ExaDT ,c.status ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime
		from oa_esm_project_close c where 1 ",
	"select_confirm" => "select c.id,c.projectId,c.projectCode,c.projectName,c.applyId,c.applyName,
			c.content,c.applyDate,c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,
			c.updateId,c.updateName,c.updateTime,
			d.id AS detailId,d.status,r.confirmId,r.confirmName,r.ruleName,r.content
		from
			oa_esm_project_close c LEFT JOIN
			oa_esm_project_close_detail d ON c.projectId = d.projectId LEFT JOIN
			oa_esm_close_rule r ON d.ruleId = r.id
		where 1 AND r.isCustom = 1"
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array(
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array(
		"name" => "applyName",
		"sql" => " and c.applyName=# "
	),
	array(
		"name" => "content",
		"sql" => " and c.content=# "
	),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "dStatus",
		"sql" => " and d.status=# "
	)
);