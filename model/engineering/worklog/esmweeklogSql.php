<?php

/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:00:57
 * @version 1.0
 * @description:周报(oa_esm_weeklog) sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.weekTitle ,c.weekTimes ,c.weekBeginDate ,c.weekEndDate ,c.depId ,c.depName ,c.assessmentId ,
			c.assessmentName ,c.subStatus ,c.rankCode ,c.directlyId ,c.directlyName ,c.existence ,c.improvement ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.exaResults,c.exaDate,c.rsScore,c.rsLevel
		from oa_esm_weeklog c where 1=1 ",
	//excel导出
	"select_excelOut" => 'SELECT p.projectCode, p.projectName,c.createName,c.weekTimes,c.weekBeginDate,c.weekEndDate,c.exaDate,c.rsScore,
			CASE c.subStatus
				WHEN "WTJ" THEN "未提交"
				WHEN "YTJ" THEN "已提交"
				WHEN "BTG" THEN "不通过"
				ELSE "已确认" END  as subStatus
		from oa_esm_weeklog c LEFT JOIN (SELECT c.id,c.weekid,c.projectId,c.projectCode,c.projectName FROM oa_esm_worklog c GROUP BY c.projectId,c.weekId ORDER BY NULL
		) p ON c.id=p.weekId where 1=1'
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "weekTitle",
		"sql" => " and c.weekTitle=# "
	),
	array(
		"name" => "weekTimes",
		"sql" => " and c.weekTimes =#"
	),
	array(
		"name" => "weekBeginDate",
		"sql" => " and c.weekBeginDate=# "
	),
	array(
		"name" => "weekEndDate",
		"sql" => " and c.weekEndDate=# "
	),
	array(
		"name" => "depId",
		"sql" => " and c.depId=# "
	),
	array(
		"name" => "deptIds",
		"sql" => " and c.depId in(arr) "
	),
	array(
		"name" => "depName",
		"sql" => " and c.depName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "assessmentId",
		"sql" => " and c.assessmentId=# "
	),
	array(
		"name" => "assessmentName",
		"sql" => " and c.assessmentName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "subStatus",
		"sql" => " and c.subStatus=# "
	),
	array(
		"name" => "rankCode",
		"sql" => " and c.rankCode=# "
	),
	array(
		"name" => "directlyId",
		"sql" => " and c.directlyId=# "
	),
	array(
		"name" => "directlyName",
		"sql" => " and c.directlyName=# "
	),
	array(
		"name" => "existence",
		"sql" => " and c.existence=# "
	),
	array(
		"name" => "improvement",
		"sql" => " and c.improvement=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createIdArr",
		"sql" => " and c.createId in(arr)"
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName like BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime like BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "worklogDate",
		"sql" => " and (c.weekBeginDate <= BINARY # and c.weekEndDate >= BINARY #) "
	),
	/********************excel导出条件***************************/
	array(
		"name" => "projectCode",
		"sql" => " and  p.projectCode =#"
	),
	array(
		"name" => "projectName",
		"sql" => " and  p.projectName =# "
	),
	array(
		"name" => "weekBeginDate_d",
		"sql" => " and  c.weekBeginDate >=# "
	),
	array(
		"name" => "weekEndDate_d",
		"sql" => " and  c.weekEndDate <=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and  p.projectId = # "
	)
);