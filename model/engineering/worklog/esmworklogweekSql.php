<?php

/**
 * @author Administrator
 * @Date 2010年12月5日 10:02:49
 * @version 1.0
 * @description:工作日志周报 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.weekTimes ,c.weekTitle ,c.subStatus,c.weekBeginDate ,c.weekEndDate ,c.rankCode ,c.directlyId ,c.directlyName ,c.existence ,c.improvement ,c.subStatus,c.isAttention ,c.depId ,c.depName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.assessmentId ,c.assessmentName  from oa_esm_worklog_week c where 1=1 ",
	"exist_weeklog" => "select c.id  from oa_esm_worklog_week c where 1=1",
	"office_list" => "select  c.id ,c.weekTimes ,c.weekTitle ,c.subStatus,c.weekBeginDate ,c.weekEndDate ,c.rankCode ,c.directlyId ,c.directlyName ,c.existence ,c.improvement ,c.subStatus,c.isAttention ,c.depId ,c.depName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.assessmentId ,c.assessmentName  from oa_esm_worklog_week c left join oa_esm_personal_baseinfo m on c.createId  = m.userCode where 1=1",
	"project_list" => "select w.id ,w.weekTimes ,w.weekTitle ,w.subStatus,w.weekBeginDate ,w.weekEndDate ,w.rankCode ,w.directlyId ,w.directlyName ,w.existence ,w.improvement ,w.subStatus,w.isAttention ,w.depId ,w.depName ,w.createId ,w.createName ,w.createTime ,w.updateId ,w.updateName ,w.updateTime ,w.assessmentId ,w.assessmentName from oa_esm_worklog_week w where w.id in (select c.weekId from oa_esm_worklog c  right join oa_esm_worklog_proinfo p  on(c.id = p.workLogId)  where 1=1 ",
	"worklog_excel" => "select c.weekTimes,c.depName,c.createName, m.assLevel ,f.officeName from oa_esm_worklog_week c left join oa_esm_ass_week m on c.id = m.weekLogId  left join oa_esm_personal_baseinfo p on(p.userCode=c.createId)   left join oa_esm_office_baseinfo f on(f.id=p.officeId)  where 1=1 and c.subStatus='ZBYKH' "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	), array (
		"name" => "weekTimes",
		"sql" => " and w.weekTimes like CONCAT('%',#,'%') "
	),
//	array (
//		"name" => "weekTimes",
//		"sql" => " and c.weekTimes=# "
//	),
	array (
		"name" => "weekTitle",
		"sql" => " and c.weekTitle like CONCAT('%',#,'%') "
	),
//	array (
//		"name" => "weekTitle",
//		"sql" => " and c.weekTitle=# "
//	),
	array (
		"name" => "weekBeginDate",
		"sql" => " and c.weekBeginDate=# "
	),
	array (
		"name" => "weekEndDate",
		"sql" => " and c.weekEndDate=# "
	),
	array (
		"name" => "subStatus",
		"sql" => " and c.subStatus in(arr) "
	),

	array (
			"name" => "startDate", //查询是否存在的开始时间
	"sql" => "and (c.weekBeginDate <= # ) "
	),
	array (
			"name" => "endDate", //查询是否存在的结束时间
	"sql" => "and (c.weekEndDate >= #  ) "
	),
	array (
		"name" => "rankCode",
		"sql" => " and c.rankCode=# "
	),
	array (
		"name" => "directlyId",
		"sql" => " and c.directlyId=# "
	),
	array (
		"name" => "directlyName",
		"sql" => " and c.directlyName=# "
	),
	array (
		"name" => "existence",
		"sql" => " and c.existence=# "
	),
	array (
		"name" => "improvement",
		"sql" => " and c.improvement=# "
	),
	array (
		"name" => "isAttention",
		"sql" => " and c.isAttention=# "
	),
	array (
		"name" => "depId",
		"sql" => " and c.depId=# "
	),
	array (
		"name" => "depName",
		"sql" => " and c.depName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "userCode",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "assessmentId",
		"sql" => " and c.assessmentId=# "
	),
	array (
		"name" => "assessmentName",
		"sql" => " and c.assessmentName=# "
	),
	array (
		"name" => "officeId",
		"sql" => " and m.officeId = # "
	),
	array (
		"name" => "id",
		"sql" => " and m.id = # "
	),
	array (
		"name" => "id",
		"sql" => " and w.id = # "
	),
	array (
		"name" => "workLogId",
		"sql" => " and p.workLogId = # "
	),
	array (
		"name" => "proId",
		"sql" => " and p.proId = # "
	)
)
?>