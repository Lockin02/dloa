<?php
/**
 * @author Administrator
 * @Date 2012-08-09 13:56:00
 * @version 1.0
 * @description:离职交接清单 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.leaveId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.companyName ,c.companyId ,c.entryDate ,c.quitDate ,c.quitReson ,c.quitTypeCode ,c.quitTypeName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.ExaStatus ,c.ExaDT ,c.staffAffCon ,c.staffAffDT,c.staffConRemark ,c.handoverCstatus,p.regionName,l.salaryEndDate
		from oa_leave_handover c
		left join oa_hr_leave l on (c.leaveId=l.id)
		left join oa_hr_personnel p on (p.userNo=c.userNO)
		where l.state<>4 ",
	"select_leave"=>"select c.id ,c.leaveId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.companyName ,c.companyId ,c.entryDate ,c.quitDate ,c.quitReson ,c.quitTypeCode ,c.quitTypeName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.ExaStatus ,c.ExaDT ,c.staffAffCon ,c.staffAffDT ,c.staffConRemark ,c.handoverCstatus ,c.regionName ,c.salaryEndDate ,c.isDone
		from (select d.id ,d.leaveId ,d.userNo ,d.userAccount ,d.userName ,d.deptName ,d.deptId ,d.jobName ,d.jobId ,d.companyName ,d.companyId ,d.entryDate ,d.quitDate ,d.quitReson ,d.quitTypeCode ,d.quitTypeName ,d.createName ,d.createId ,d.createTime ,d.updateName ,d.updateId ,d.updateTime ,d.ExaStatus ,d.ExaDT ,d.staffAffCon ,d.staffAffDT ,d.staffConRemark ,d.handoverCstatus ,p.regionName ,l.salaryEndDate ,if(count(j.id)>0,1,0) as isDone from oa_leave_handover d
			left join oa_hr_leave l on (d.leaveId = l.id)
			left join oa_hr_personnel p on (p.userNo = d.userNO)
			left join oa_hr_handover_list j on (d.id = j.handoverId and find_in_set('".$_SESSION['USER_ID']."',j.recipientId) and j.affstate<>'1')
			where l.state<>4 group by d.id
		) c
		where 1=1 "
);


$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "ids",
		"sql" => " and c.id in(arr)"
	),
	array(
		"name" => "leaveId",
		"sql" => " and c.leaveId=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate=# "
	),
	array(
		"name" => "quitDate",
		"sql" => " and c.quitDate=# "
	),
	array(
		"name" => "quitReson",
		"sql" => " and c.quitReson=# "
	),
	array(
		"name" => "quitTypeCode",
		"sql" => " and c.quitTypeCode=# "
	),
	array(
		"name" => "quitTypeName",
		"sql" => " and c.quitTypeName=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array(
		"name" => "isDone",
		"sql" => " and c.isDone=# "
	)
)
?>