<?php
/**
 * @author Show
 * @Date 2012年5月28日 星期一 13:38:56
 * @version 1.0
 * @description:人员调用记录 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.applyDate ,c.userNo ,c.userAccount ,c.userName ,c.entryDate ,c.transferType ,c.transferTypeName ,c.transferDate ,c.preUnitTypeName ,c.preUnitTypeId ,c.preUnitName ,c.preUnitId ,c.preUseAreaName ,c.preUseAreaId , c.preDeptCode, c.preDeptName , c.preDeptId , c.preBelongDeptCode , c.preBelongDeptName , c.preBelongDeptId ,c.preDeptNameS ,c.preDeptIdS ,c.preDeptNameT ,c.preDeptIdT ,c.preDeptIdF ,c.preDeptNameF ,c.preJobName ,c.preJobId ,c.prePersonClass ,c.prePersonClassCode ,c.afterUnitTypeName ,c.afterUnitTypeId ,c.afterUnitName ,c.afterUnitId ,c.afterUseAreaName ,c.afterUseAreaId, c.afterDeptCode, c.afterDeptName , c.afterDeptId , c.afterBelongDeptCode , c.afterBelongDeptName , c.afterBelongDeptId ,c.afterDeptNameS ,c.afterDeptIdS ,c.afterDeptNameT ,c.afterDeptIdT ,c.afterDeptNameF ,c.afterDeptIdF ,c.afterJobName ,c.afterJobId ,c.afterPersonClass ,c.afterPersonClassCode ,c.reason ,c.remark ,c.managerName ,c.managerId ,c.ExaStatus ,c.ExaDT ,c.employeeOpinion ,c.employeeRemark ,c.reportDate ,c.handoverRemark ,c.handoverDate ,c.deptLeadName ,c.deptLeadId ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.seniority ,c.isCompanyChange ,c.isDeptChange ,c.isJobChange ,c.isAreaChange ,c.isClassChange from oa_hr_personnel_transfer c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "formCode",
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "transferType",
		"sql" => " and c.transferType=# "
	),
	array(
		"name" => "transferTypeName",
		"sql" => " and c.transferTypeName=# "
	),
	array(
		"name" => "transferDate",
		"sql" => " and c.transferDate=# "
	),
	array(
		"name" => "preUnitTypeName",
		"sql" => " and c.preUnitTypeName=# "
	),
	array(
		"name" => "preUnitTypeId",
		"sql" => " and c.preUnitTypeId=# "
	),
	array(
		"name" => "preUnitName",
		"sql" => " and c.preUnitName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "preUnitId",
		"sql" => " and c.preUnitId=# "
	),
	array(
		"name" => "preDeptName",
		"sql" => " and (c.preDeptNameS LIKE CONCAT('%',#,'%') or c.preDeptNameT LIKE CONCAT('%',#,'%') ) "
	),
	array(
		"name" => "preDeptNameS",
		"sql" => " and c.preDeptNameS LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "preDeptIdS",
		"sql" => " and c.preDeptIdS=# "
	),
	array(
		"name" => "preDeptNameT",
		"sql" => " and c.preDeptNameT LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "preDeptIdT",
		"sql" => " and c.preDeptIdT=# "
	),
	array(
		"name" => "preDeptNameF",
		"sql" => " and c.preDeptNameF LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "preDeptIdF",
		"sql" => " and c.preDeptIdF=# "
	),
	array(
		"name" => "preJobName",
		"sql" => " and c.preJobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "preJobId",
		"sql" => " and c.preJobId=# "
	),
	array(
		"name" => "prePersonClass",
		"sql" => " and c.prePersonClass LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "prePersonClassCode",
		"sql" => " and c.prePersonClassCode=# "
	),
	array(
		"name" => "afterUnitTypeName",
		"sql" => " and c.afterUnitTypeName=# "
	),
	array(
		"name" => "afterUnitTypeId",
		"sql" => " and c.afterUnitTypeId=# "
	),
	array(
		"name" => "afterUnitName",
		"sql" => " and c.afterUnitName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "afterUnitId",
		"sql" => " and c.afterUnitId=# "
	),
	array(
		"name" => "afterDeptName",
		"sql" => " and (c.afterDeptNameS LIKE CONCAT('%',#,'%') or c.afterDeptNameT LIKE CONCAT('%',#,'%') ) "
	),
	array(
		"name" => "afterDeptNameS",
		"sql" => " and c.afterDeptNameS LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "afterDeptIdS",
		"sql" => " and c.afterDeptIdS=# "
	),
	array(
		"name" => "afterDeptNameT",
		"sql" => " and c.afterDeptNameT LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "afterDeptIdT",
		"sql" => " and c.afterDeptIdT=# "
	),
	array(
		"name" => "afterDeptNameF",
		"sql" => " and c.afterDeptNameF LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "afterDeptIdF",
		"sql" => " and c.afterDeptIdF=# "
	),
	array(
		"name" => "afterJobName",
		"sql" => " and c.afterJobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "afterJobId",
		"sql" => " and c.afterJobId=# "
	),
	array(
		"name" => "afterPersonClass",
		"sql" => " and c.afterPersonClass LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "afterPersonClassCode",
		"sql" => " and c.afterPersonClassCode=# "
	),
	array(
		"name" => "reason",
		"sql" => " and c.reason LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "managerName",
		"sql" => " and c.managerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr)"
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status in(arr) "
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
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array(
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array(
		"name" => "employeeOpinion",
		"sql" => " and c.employeeOpinion=# "
	),
	array(
		"name" => "isCompanyChange",
		"sql" => " and c.isCompanyChange=# "
	),
	array(
		"name" => "isDeptChange",
		"sql" => " and c.isDeptChange=# "
	),
	array(
		"name" => "isJobChange",
		"sql" => " and c.isJobChange=# "
	),
	array(
		"name" => "isAreaChange",
		"sql" => " and c.isAreaChange=# "
	),
	array(
		"name" => "isClassChange",
		"sql" => " and c.isClassChange=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and  (c.preDeptIdS=# or c.preDeptIdT=#) "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.applyDate >= BINARY #"
	),
	array(
		"name" => "endDate",
		"sql" => " and c.applyDate <= BINARY #"
	),
	array(
		"name" => "preUseAreaName",
		"sql" => " and c.preUseAreaName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "afterUseAreaName",
		"sql" => " and c.afterUseAreaName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "preBelongDeptId",
		"sql" => " and c.preBelongDeptId = #"
	),
	array(
		"name" => "afterBelongDeptId",
		"sql" => " and c.afterBelongDeptId = #"
	),
	array(
		"name" => "preBelongDeptName",
		"sql" => " and c.preBelongDeptName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "afterBelongDeptName",
		"sql" => " and c.afterBelongDeptName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "listId",
		"sql" => " and ((c.managerId=#) or (c.userAccount=#))"
	)
)
?>