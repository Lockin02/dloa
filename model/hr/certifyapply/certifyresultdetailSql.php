<?php

/**
 * @author Show
 * @Date 2012年8月29日 星期三 21:14:34
 * @version 1.0
 * @description:任职资格认证评价结果审批表明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.resultId ,c.applyId ,c.assessId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,
			c.deptId ,c.jobId ,c.jobName ,c.entryDate ,c.highEducationName ,c.graduationDate ,c.nowDirectionName ,c.nowDirection ,
			c.nowLevel ,c.nowLevelName ,c.nowGrade ,c.nowGradeName ,c.score ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,
			c.baseGradeName ,c.finalLevel ,c.finalLevelName ,c.finalGrade ,c.finalGradeName ,c.remark
		from oa_hr_certifyresult_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "resultId",
		"sql" => " and c.resultId=# "
	),
	array (
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array (
		"name" => "assessId",
		"sql" => " and c.assessId=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array (
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array (
		"name" => "entryDate",
		"sql" => " and c.entryDate=# "
	),
	array (
		"name" => "highEducationName",
		"sql" => " and c.highEducationName=# "
	),
	array (
		"name" => "graduationDate",
		"sql" => " and c.graduationDate=# "
	),
	array (
		"name" => "nowDirectionName",
		"sql" => " and c.nowDirectionName=# "
	),
	array (
		"name" => "nowDirection",
		"sql" => " and c.nowDirection=# "
	),
	array (
		"name" => "nowLevel",
		"sql" => " and c.nowLevel=# "
	),
	array (
		"name" => "nowLevelName",
		"sql" => " and c.nowLevelName=# "
	),
	array (
		"name" => "nowGrade",
		"sql" => " and c.nowGrade=# "
	),
	array (
		"name" => "nowGradeName",
		"sql" => " and c.nowGradeName=# "
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	),
	array (
		"name" => "baseLevel",
		"sql" => " and c.baseLevel=# "
	),
	array (
		"name" => "baseLevelName",
		"sql" => " and c.baseLevelName=# "
	),
	array (
		"name" => "baseGrade",
		"sql" => " and c.baseGrade=# "
	),
	array (
		"name" => "baseGradeName",
		"sql" => " and c.baseGradeName=# "
	),
	array (
		"name" => "finalLevel",
		"sql" => " and c.finalLevel=# "
	),
	array (
		"name" => "finalLevelName",
		"sql" => " and c.finalLevelName=# "
	),
	array (
		"name" => "finalGrade",
		"sql" => " and c.finalGrade=# "
	),
	array (
		"name" => "finalGradeName",
		"sql" => " and c.finalGradeName=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>