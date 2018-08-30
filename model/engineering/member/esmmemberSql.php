<?php

/**
 * @author Show
 * @Date 2011年12月20日 星期二 15:23:55
 * @version 1.0
 * @description:项目成员(oa_esm_project_member) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.memberName ,c.memberId ,c.roleName ,
			c.roleId ,c.memberType ,c.status ,c.remark ,c.isManager,c.isCanEdit,c.createId ,c.createName ,c.createTime ,
			c.beginDate,c.endDate,c.feeDay,c.feePeople,c.feePerson,c.activityName,c.activityId,c.personLevel,c.personLevelId,
			c.updateId ,c.updateName ,c.updateTime,c.costMoney,c.confirmMoney,c.unconfirmMoney,c.expenseMoney,c.unexpenseMoney,
			c.backMoney,expensingMoney
		from oa_esm_project_member c where 1=1",
	"select_listcount" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.memberName ,c.memberId ,c.roleName ,
			c.roleId ,c.memberType ,c.status ,c.remark ,c.isManager,c.isCanEdit,c.createId ,c.createName ,c.createTime ,
			c.costMoney,c.confirmMoney,c.unconfirmMoney,c.expenseMoney,c.unexpenseMoney,c.backMoney,expensingMoney,
			c.beginDate,c.endDate,if(c.endDate <>'0000-00-00' and c.endDate is not null,c.endDate,CURRENT_DATE) as endDateCount,
			c.feeDay,c.feePeople,c.feePerson,
			c.activityName,c.activityId,c.personLevel,c.personLevelId,c.price,c.coefficient,
			c.updateId ,c.updateName ,c.updateTime  from oa_esm_project_member c where 1",
	"count_all" => "select
			sum(c.feeDay) as feeDay,
			sum(c.feePeople) as feePeople ,
			sum(c.feePerson) as feePerson,
			count(*) as peopleNumber,sum(c.costMoney) as costMoney,sum(c.confirmMoney) as confirmMoney,
			sum(c.unconfirmMoney) as unconfirmMoney,sum(c.expenseMoney) as expenseMoney,sum(c.unexpenseMoney) as unexpenseMoney,
			sum(c.backMoney) as backMoney,sum(c.expensingMoney) as expensingMoney
		from oa_esm_project_member c where 1 ",
	"select_costMoney" => "select
			c.id ,c.projectId ,c.projectCode ,c.projectName ,c.memberName ,c.memberId ,
			c.costMoney,c.confirmMoney,c.unconfirmMoney,c.expenseMoney,c.unexpenseMoney,c.backMoney,c.expensingMoney,
			p.status,p.statusName
		from
			oa_esm_project_member c
			inner join
			oa_esm_project p
			on c.projectId = p.id
		where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "id",
		"sql" => " and c.id <> # "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "memberName",
		"sql" => " and c.memberName=# "
	),
	array (
		"name" => "memberNameSearch",
   		"sql" => " and c.memberName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "memberId",
		"sql" => " and c.memberId=# "
	),
    array (
        "name" => "memberIdNot",
        "sql" => " and c.memberId<># "
    ),
	array (
		"name" => "memberIdArr",
		"sql" => " and c.memberId in(arr) "
	),
	array (
		"name" => "roleName",
		"sql" => " and c.roleName=# "
	),
	array (
		"name" => "noRoleId",
		"sql" => " and c.roleId <> # "
	),
	array (
		"name" => "roleId",
		"sql" => " and c.roleId=# "
	),
	array (
		"name" => "memberType",
		"sql" => " and c.memberType=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "isManager",
		"sql" => " and c.isManager=# "
	),
	array (
		"name" => "pstatus",
		"sql" => " and p.status=# "
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
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "noEndDate",
		"sql" => " and (c.endDate = '0000-00-00' or c.endDate is null )"
	),
	array (
		"name" => "personLevelSearch",
   		"sql" => " and c.personLevel like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ids",
   		"sql" => " and c.id in(arr) "
	)
)
?>