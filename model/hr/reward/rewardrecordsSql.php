<?php

/**
 * @author Show
 * @Date 2012年5月24日 星期四 10:00:14
 * @version 1.0
 * @description:薪资信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName  ,c.deptNameS ,c.deptIdS,c.deptNameT ,c.deptIdT ,c.jobName ,c.jobId ,
			c.rewardPeriod ,c.rewardDate,c.actRewardDate ,c.workDays ,c.leaveDays ,c.sickDays ,c.basicWage ,c.provident ,c.socialSecurity ,
			c.projectBonus ,c.specialBonus ,c.otherBonus ,c.mealSubsidies ,c.transportSubsidies ,c.otherSubsidies ,c.sickDeduction ,
			c.leaveDeduction ,c.specialDeduction ,c.preTaxWage ,c.afterTaxWage ,c.taxes ,c.remark ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId
		from oa_hr_reward_records c where 1=1 ",
	"count_all" => "select sum(c.workDays) as workDays, sum(c.leaveDays) as leaveDays ,sum(c.sickDays) as sickDays ,sum(c.basicWage) as basicWage,
			sum(c.provident) as provident , sum(c.socialSecurity) as socialSecurity ,sum(c.projectBonus ) as projectBonus ,
			sum(c.specialBonus) as specialBonus , sum(c.otherBonus) as otherBonus ,sum(c.mealSubsidies) as mealSubsidies ,
			sum(c.transportSubsidies) as transportSubsidies,sum(c.otherSubsidies) as otherSubsidies ,sum(c.sickDeduction) as sickDeduction ,
			sum(c.leaveDeduction) as leaveDeduction ,sum(c.specialDeduction) as specialDeduction,sum(c.preTaxWage) as preTaxWage,
			sum(c.afterTaxWage) as afterTaxWage ,sum(c.taxes) as taxes
		from oa_hr_reward_records c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userNoM",
		"sql" => " and c.userNo like CONCAT('%',#,'%')"
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
		"name" => "userNameM",
		"sql" => " and c.userName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptName",
		"sql" => " and (c.deptNameS like CONCAT('%',#,'%') or c.deptNameT like CONCAT('%',#,'%') ) "
	),
	array (
		"name" => "deptNameS",
		"sql" => " and c.deptNameS=# "
	),
	array (
		"name" => "deptIdS",
		"sql" => " and c.deptIdS=# "
	),
	array (
		"name" => "deptNameT",
		"sql" => " and c.deptNameT=# "
	),
	array (
		"name" => "deptIdT",
		"sql" => " and c.deptIdT=# "
	),
	array (
		"name" => "deptIdArr",
		"sql" => " and ( c.deptIdT in(arr) or c.deptIdS in(arr) )"
	),
	array (
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array (
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array (
		"name" => "rewardPeriod",
		"sql" => " and c.rewardPeriod=# "
	),
	array (
		"name" => "rewardPeriodSearch",
		"sql" => " and (date_format(c.rewardDate,'%Y%m') = # or c.rewardPeriod = # )"
	),
	array (
		"name" => "rewardDate",
		"sql" => " and c.rewardDate=# "
	),
	array (
		"name" => "workDays",
		"sql" => " and c.workDays=# "
	),
	array (
		"name" => "leaveDays",
		"sql" => " and c.leaveDays=# "
	),
	array (
		"name" => "sickDays",
		"sql" => " and c.sickDays=# "
	),
	array (
		"name" => "basicWage",
		"sql" => " and c.basicWage=# "
	),
	array (
		"name" => "provident",
		"sql" => " and c.provident=# "
	),
	array (
		"name" => "socialSecurity",
		"sql" => " and c.socialSecurity=# "
	),
	array (
		"name" => "projectBonus",
		"sql" => " and c.projectBonus=# "
	),
	array (
		"name" => "specialBonus",
		"sql" => " and c.specialBonus=# "
	),
	array (
		"name" => "otherBonus",
		"sql" => " and c.otherBonus=# "
	),
	array (
		"name" => "mealSubsidies",
		"sql" => " and c.mealSubsidies=# "
	),
	array (
		"name" => "transportSubsidies",
		"sql" => " and c.transportSubsidies=# "
	),
	array (
		"name" => "otherSubsidies",
		"sql" => " and c.otherSubsidies=# "
	),
	array (
		"name" => "sickDeduction",
		"sql" => " and c.sickDeduction=# "
	),
	array (
		"name" => "leaveDeduction",
		"sql" => " and c.leaveDeduction=# "
	),
	array (
		"name" => "specialDeduction",
		"sql" => " and c.specialDeduction=# "
	),
	array (
		"name" => "preTaxWage",
		"sql" => " and c.preTaxWage=# "
	),
	array (
		"name" => "afterTaxWage",
		"sql" => " and c.afterTaxWage=# "
	),
	array (
		"name" => "taxes",
		"sql" => " and c.taxes=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
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
	)
)
?>