<?php

/**
 * @author Show
 * @Date 2012年5月31日 星期四 17:39:42
 * @version 1.0
 * @description:任职资格信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.entryDate,c.jobName,c.deptId ,c.applyDate ,c.nowLevelName,c.nowGradeName,c.careerDirection ,c.careerDirectionName ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,c.baseGradeName ,c.ExaStatus ,c.ExaDT ,c.baseScore ,c.baseResult ,c.finalResult ,c.finalScore ,c.finalCareer ,c.finalCareerName ,c.finalLevel ,c.finalLevelName ,c.finalTitle ,c.finalTitleName ,c.finalGrade ,c.finalGradeName ,c.finalDate ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.backReason ,c.certifyDirectionName ,c.remark  from oa_hr_personnel_certifyapply c where 1=1 ",
	'select_excelOut'=>"select c.userNo ,c.userName ,c.deptName ,c.jobName ,c.nowLevelName ,c.nowGradeName ,c.entryDate ,c.workExperience ,c.applyDate ,c.careerDirectionName ,c.baseLevelName ,c.baseGradeName ,
		case c.status
			when '1' then '审批中'
			when '2' then '认证表待生成'
			when '3' then '认证准备中'
			when '4' then '认证待审批'
			when '5' then '认证审批中'
			when '6' then '认证待答辩'
			when '7' then '认证结果审核中'
			when '8' then '认证失败'
			when '10' then '认证已审核'
			when '11' then '完成'
			when '12' then '打回'
			else '' end
		as state ,
		if(c.baseScore=0,'',c.baseScore) as baseScore,
			case c.baseResult
			when '1' then '通过'
			when '0' then '不通过'
			else '' end
		as baseResult,
		case c.finalResult
			when '1' then '通过'
			when '0' then '不通过'
			else '' end
		as finalResult,
		if(c.finalScore=0,'',c.finalScore) as finalScore ,
		c.finalCareerName ,c.finalLevelName ,c.finalGradeName ,c.finalTitleName ,
		if(c.finalDate='0000-00-00','',c.finalDate) as finalDate ,
			c.certifyDirectionName ,c.backReason
	from oa_hr_personnel_certifyapply c where c.status!=0 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=#"
	),
	array (
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "userAccountSearch",
		"sql" => " and c.userAccount LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "certifyDirection",
		"sql" => " and c.certifyDirectionName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')   "
	),
	array (
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
	array (
		"name" => "applyDateSearch",
		"sql" => " and c.applyDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array (
		"name" => "careerDirection",
		"sql" => " and c.careerDirection=# "
	),
	array (
		"name" => "careerDirectionNameSearch",
		"sql" => " and c.careerDirectionName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "careerDirectionName",
		"sql" => " and c.careerDirectionName=# "
	),
	array (
		"name" => "baseLevel",
		"sql" => " and c.baseLevel=# "
	),
	array (
		"name" => "baseLevelName",
		"sql" => " and c.baseLevelName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "baseGrade",
		"sql" => " and c.baseGrade=# "
	),
	array (
		"name" => "baseGradeName",
		"sql" => " and c.baseGradeName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaStatusSearch",
		"sql" => " and c.ExaStatus LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "baseScore",
		"sql" => " and c.baseScore=# "
	),
	array (
		"name" => "baseResult",
		"sql" => " and c.baseResult=# "
	),
	array (
		"name" => "finalResult",
		"sql" => " and c.finalResult=# "
	),
	array (
		"name" => "finalScore",
		"sql" => " and c.finalScore=# "
	),
	array (
		"name" => "finalCareer",
		"sql" => " and c.finalCareer=# "
	),
	array (
		"name" => "finalCareerName",
		"sql" => " and c.finalCareerName=# "
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
		"name" => "finalTitle",
		"sql" => " and c.finalTitle=# "
	),
	array (
		"name" => "finalTitleName",
		"sql" => " and c.finalTitleName=# "
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
		"name" => "finalDate",
		"sql" => " and c.finalDate=# "
	),
	array (
		"name" => "finalDateSearch",
		"sql" => " and c.finalDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
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
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array(
		'name'=>'applyDateBegin',
		'sql'=>' and applyDate >= BINARY #'
	),
	array(
		'name'=>'applyDateEnd',
		'sql'=>' and applyDate <= BINARY #'
	),
	array(
		'name'=>'finalDateBegin',
		'sql'=>' and finalDate >= BINARY #'
	),
	array(
		'name'=>'finalDateEnd',
		'sql'=>' and finalDate <= BINARY #'
	),
	array (
		"name" => "baseScoreStart",
		"sql" => " and c.baseScore >= # "
	),
	array (
		"name" => "baseScoreEnd",
		"sql" => " and c.baseScore <= # "
	),
	array (
		"name" => "finalScoreStart",
		"sql" => " and c.finalScore >= # "
	),
	array (
		"name" => "finalScorEnde",
		"sql" => " and c.finalScore <= # "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%') "
	)
)
?>