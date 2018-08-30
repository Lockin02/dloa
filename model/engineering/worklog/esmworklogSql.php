<?php

/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:01:27
 * @version 1.0
 * @description:工作日志(oa_esm_worklog) sql配置文件 每日进展
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.weekId ,c.executionDate ,c.projectId ,c.projectCode ,c.projectName ,c.province ,c.provinceId ,
			c.city,c.cityId,c.workloadDay ,c.description ,c.planEndDate ,c.workStatus ,c.problem ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime,c.activityId,c.activityName,c.workProcess,c.description,c.costMoney,c.country,
			c.countryId,c.status,concat(c.province,c.city) as provinceCity,c.confirmStatus,c.confirmMoney,c.confirmStatus,
			c.confirmName,c.confirmId,c.confirmDate,c.workloadUnitName,c.backMoney,c.workloadDay as workloadAndUnit,
			if(c.confirmStatus = 1,round((c.costMoney - c.confirmMoney - c.backMoney),2),0) as unconfirmMoney,c.inWorkRate,
			c.thisActivityProcess,c.thisProjectProcess,c.feedBack,c.assessResult,c.assessResultName,round(c.inWorkRate/100,2) as inWorkRateOne,
			c.workCoefficient,c.processCoefficient,c.deptName,c.deptId,round(c.workCoefficient/c.inWorkRate*100,2) as assessScore
		from oa_esm_worklog c where 1=1 ",
	"count_list" => "select
			sum(c.workloadDay) as workloadDay ,count(*) as workDay,round(sum(1*inWorkRate/100),2) as inWorkDay,
			min(executionDate) as actBeginDate,sum(costMoney) as costMoney,sum(inWorkRate) as inWorkRate,
			sum(c.thisProjectProcess) as thisProjectProcess
		from oa_esm_worklog c where 1=1 ",
	"getListProcess" => "select c.activityId,
			sum(c.thisActivityProcess) as thisActivityProcess,
			sum(c.thisProjectProcess) as thisProjectProcess
		from oa_esm_worklog c where 1=1 ",
	"index_project" => "select count(*) as worklogNum,c.createId,c.projectId,c.weekId,max(c.createTime) from oa_esm_worklog c where 1 ",
	"search_json" => "select
			count(*) as dataNum,c.createId,c.createName,c.projectCode,c.projectId,c.projectName,round(sum(c.inWorkRate/100),2) as inWorkRate,sum(c.costMoney) as costMoney,
			sum(c.thisProjectProcess) as thisProjectProcess,round(sum(c.inWorkRate/100),2) as inWorkRateOne,
			sum(c.workCoefficient) as workCoefficient,sum(c.processCoefficient) as processCoefficient,c.workloadUnitName,
			sum(c.thisActivityProcess) as thisActivityProcess,c.activityName,max(c.executionDate) as maxDate,min(c.executionDate) as minDate,
			sum(c.workloadDay) as workloadDay,sum(c.inWorkRate) as inWorkRateProcess,c.activityId,sum(round(c.workCoefficient/c.inWorkRate*100,2)) as assessScore
		from oa_esm_worklog c where 1",
	"count_json" => "select
			round(sum(c.inWorkRate/100),2) as inWorkRate,sum(c.costMoney) as costMoney,
			sum(c.thisProjectProcess) as thisProjectProcess,round(sum(c.inWorkRate/100),2) as inWorkRateOne,
			sum(c.workCoefficient) as workCoefficient,sum(c.processCoefficient) as processCoefficient
		from oa_esm_worklog c where 1",
	"select_weekstatus" => "select
			date_format(c.executionDate,'%Y%w') as yearWeek,c.activityId,c.activityName,sum(c.workloadDay) as workloadDay,sum(c.inWorkRate) as inWorkRate
		from
			oa_esm_worklog c where 1"
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "noId",
		"sql" => " and c.Id<># "
	),
	array(
		"name" => "weekId",
		"sql" => " and c.weekId=# "
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
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "activityId",
		"sql" => " and c.activityId=# "
	),
	array(
		"name" => "activityIds",
		"sql" => " and c.activityId in(arr) "
	),
	array(
		"name" => "activityName",
		"sql" => " and c.activityName = #"
	),
	array(
		"name" => "activityNameSearch",
		"sql" => " and c.activityName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "executionDateSearch",
		"sql" => " and c.executionDate like BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "executionDate",
		"sql" => " and c.executionDate = BINARY #"
	),
	array(
		"name" => "biggerDate",
		"sql" => " and c.executionDate > BINARY #"
	),
	array(
		"name" => "workloadDay",
		"sql" => " and c.workloadDay=# "
	),
	array(
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array(
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array(
		"name" => "workStatus",
		"sql" => " and c.workStatus=# "
	),
	array(
		"name" => "workStatusArr",
		"sql" => " and c.workStatus in(arr)"
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "confirmStatus",
		"sql" => " and c.confirmStatus=# "
	),
	array(
		"name" => "costMoneyNotEqu",
		"sql" => " and c.costMoney <> # "
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
		"name" => "userIds",
		"sql" => " and c.createId in(arr) "
	),
	array(
		"name" => "beginDateThan",
		"sql" => " and c.executionDate >= # "
	),
	array(
		"name" => "endDateThan",
		"sql" => " and c.executionDate <= # "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createNameSearch",
		"sql" => " and c.createName  like CONCAT('%',#,'%') "
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
		"name" => "provinceCitySearch",
		"sql" => " and concat(c.province,c.city) like CONCAT('%',#,'%') "
	),
	array(
		"name" => "assessResults",
		"sql" => " and c.assessResult in(arr) "
	),
	array(
		"name" => "assessResultsNo",
		"sql" => " and c.assessResult not in(arr) "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId in(arr) "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName = # "
	),
	array(
		"name" => "deptNameSearch",
		"sql" => " and c.deptName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.createId =# "
	),
	array(
		"name" => "beginDateThan",
		"sql" => " and c.executionDate >=# "
	),
	array(
		"name" => "endDateThan",
		"sql" => " and c.executionDate <=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode =# "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.createId =# "
	),
	array(//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.executionDate >= #"
	),
	array(
		"name" => "endDate",
		"sql" => " and c.executionDate <= #"
	)
);