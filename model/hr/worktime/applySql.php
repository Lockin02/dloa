<?php
/**
 * @author Administrator
 * @Date 2014年3月12日 星期三 21:25:18
 * @version 1.0
 * @description:法定节假日申请表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.applyCode ,c.userAccount ,c.userNo ,c.userName ,c.belongDeptName ,c.belongDeptId ,c.deptName ,c.deptId ,c.deptNameS ,c.deptSId ,c.deptNameT ,c.deptTId ,c.deptNameF ,c.deptFId ,c.jobName ,c.jobId ,c.applyDate ,c.workBegin ,c.beginIdentify ,c.workEnd ,c.endIdentify ,c.dayNo ,c.workContent ,c.workProvince ,c.workProvinceId ,c.projectManager ,c.projectManagerId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.wageLevelCode ,c.wageLevelName ,c.changeTimeReason ,d.holiday
	from oa_hr_worktime_apply c
	left join (
		SELECT e.parentId ,group_concat(cast(CONCAT(e.holiday,'|',e.holidayInfo) AS CHAR) ORDER BY e.holiday ASC) AS holiday FROM oa_hr_worktime_applyequ e GROUP BY e.parentId
	)d on c.id=d.parentId
	where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array(
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array(
		"name" => "applyCode",
		"sql" => " and c.applyCode=# "
	),
	array(
		"name" => "applyCodeS",
		"sql" => " and c.applyCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userNoS",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName =# "
	),
	array(
		"name" => "userNameS",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "deptNameS",
		"sql" => " and c.deptNameS LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptSId",
		"sql" => " and c.deptSId=# "
	),
	array(
		"name" => "deptNameT",
		"sql" => " and c.deptNameT LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptTId",
		"sql" => " and c.deptTId=# "
	),
	array(
		"name" => "deptNameF",
		"sql" => " and c.deptNameF LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptFId",
		"sql" => " and c.deptFId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "workBegin",
		"sql" => " and c.workBegin LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "beginIdentify",
		"sql" => " and c.beginIdentify=# "
	),
	array(
		"name" => "workEnd",
		"sql" => " and c.workEnd LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "endIdentify",
		"sql" => " and c.endIdentify=# "
	),
	array(
		"name" => "dayNo",
		"sql" => " and c.dayNo=# "
	),
	array(
		"name" => "workContent",
		"sql" => " and c.workContent=# "
	),
	array(
		"name" => "workProvince",
		"sql" => " and c.workProvince=# "
	),
	array(
		"name" => "workProvinceId",
		"sql" => " and c.workProvinceId=# "
	),
	array(
		"name" => "projectManager",
		"sql" => " and c.projectManager=# "
	),
	array(
		"name" => "projectManagerId",
		"sql" => " and c.projectManagerId=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
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
		"sql" => " and c.updateTime=# "
	),
	array(
		"name" => "workBeginYear",
		"sql" => " and d.holiday LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "workBeginMonth",
		"sql" => " and d.holiday LIKE BINARY CONCAT('%-',#,'-%') "
	),
	array(
		"name" => "holiday",
		"sql" => " and d.holiday LIKE BINARY CONCAT('%',#,'%') "
	)
)
?>