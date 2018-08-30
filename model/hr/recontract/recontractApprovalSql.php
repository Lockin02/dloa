<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select d.Item,c.mark,c.id ,c.userName,c.isFlag ,c.userNo ,c.userAccount ,c.conNo ,c.conName ,c.conType ,c.conTypeName ,c.conState ,c.conStateName ,c.beginDate ,c.jobName ,c.jobId ,c.closeDate ,c.conNum ,c.conNumName ,c.conContent ,c.recorderName ,c.recorderId ,c.recordDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.userAccount,c.isFlagName   from hr_recontract_approval c  LEFT JOIN flow_step d ON c.flow_step_id=d.ID where 1=1 ",
         "select_appList"=>"select a.User,b.start,a.Flag,a.Result,a.ID as id,
							c.id as pid,c.conTypeName,c.conName,c.comeinDate,c.jobName,c.deptName,c.companyName,c.userName,
							c.userNo,d.conContent,d.conStateName,d.conNumName,d.closeDate,d.beginDate,c.statusId,
							c.ocloseDate,c.obeginDate,c.oconNumName,c.oconStateName 
							from hr_recontract c,wf_task b ,flow_step_partent a,hr_recontract_approval d 
							where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=c.id  AND d.recontractId=c.id 
							and b.task=a.Wf_task_ID   and a.Flag='0' and a.Result=''  and  find_in_set('".$_SESSION['USER_ID']."',a.User)
							and  d.id IN(SELECT MAX(id) from hr_recontract_approval WHERE  recontractId=c.id) and b.finish is null and c.statusId not in (9)",
		"select_appListPost"=>"select b.start,a.Flag,a.Result,a.ID as id,
								c.id as pid,c.conTypeName,c.conName,c.comeinDate,c.jobName,c.deptName,c.companyName,c.userName,
								c.userNo,c.conContent,c.conStateName,c.conNumName,c.closeDate,c.beginDate,c.statusId,c.ocloseDate,c.obeginDate,c.oconNumName,c.oconStateName 
								from hr_recontract c,wf_task b ,flow_step_partent a,hr_recontract_approval d 
								where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=c.id  AND d.recontractId=c.id 
								and b.task=a.Wf_task_ID  AND d.fspId=c.ID    and a.Flag='1' and a.Result='1' and  find_in_set('".$_SESSION['USER_ID']."',a.User)",
		"select_appListAll"=>"SELECT  start,Flag,Result,ID as id,id as pid,conTypeName,conName,comeinDate,jobName,deptName,companyName,userName,
						              userNo,conContent,conStateName,conNumName,closeDate,beginDate,statusId,ocloseDate,obeginDate,oconNumName,oconStateName 
							 from((
							    SELECT b.start,a.Flag,a.Result,a.ID as id,
									c.id as pid,c.conTypeName,c.conName,c.comeinDate,c.jobName,c.deptName,
									c.companyName,c.userName,c.userNo,c.conContent,c.conStateName,c.conNumName,c.closeDate,c.beginDate,
									c.statusId,c.ocloseDate,c.obeginDate,c.oconNumName,c.oconStateName
									FROM hr_recontract as  c,wf_task  as b ,flow_step_partent as  a,hr_recontract_approval as d 
									WHERE b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=c.id  AND d.recontractId=c.id 
									and b.task=a.Wf_task_ID  AND d.fspId=a.ID    and a.Flag='1' and a.Result='1' and  find_in_set('".$_SESSION['USER_ID']."',a.User)
							)UNION ALL(
								SELECT b.start,a.Flag,a.Result,a.ID as id,
									c.id as pid,c.conTypeName,c.conName,c.comeinDate,c.jobName,c.deptName,
									c.companyName,c.userName,c.userNo,d.conContent,d.conStateName,d.conNumName,d.closeDate,d.beginDate,
									c.statusId,c.ocloseDate,c.obeginDate,c.oconNumName,c.oconStateName
									FROM hr_recontract as  c,wf_task as  b ,flow_step_partent as a,hr_recontract_approval as d 
									WHERE b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=c.id  AND d.recontractId=c.id 
									and b.task=a.Wf_task_ID   and a.Flag='0' and a.Result=''  and  find_in_set('".$_SESSION['USER_ID']."',a.User)
									and  d.id IN(SELECT MAX(id) from hr_recontract_approval WHERE  recontractId=c.id)
							)
						   ) as c where 1=1 ",
         "select_StaffList"=>"select c.id,c.userName,c.statusId,c.userNo ,c.userAccount ,c.companyName,c.deptName,c.comeinDate,
								     c.oconStateName ,c.obeginDate ,c.jobName ,c.jobId ,c.ocloseDate ,c.oconNum ,c.oconNumName ,
								     c.beginDate,c.closeDate,c.conStateName,c.conNumName ,c.conContent,c.staffFlag  
								from hr_recontract c    
								where 1=1  and  find_in_set('".$_SESSION['USER_ID']."',c.userAccount)  and  c.statusId in (3,4,5,6,7,8) ",
        /*"select_StaffList"=>"select a.id,a.userName,a.statusId,a.userNo ,a.userAccount ,a.companyName,a.deptName,a.comeinDate,
								     a.oconStateName ,a.obeginDate ,a.jobName ,a.jobId ,a.ocloseDate ,a.oconNum ,a.oconNumName ,
								     c.beginDate,c.closeDate,c.conStateName,c.conNumName ,a.conContent  
								from hr_recontract a , hr_recontract_approval c  
								where 1=1   AND a.id=c.recontractId   and  find_in_set('".$_SESSION['USER_ID']."',a.userAccount)
 								AND c.id IN (SELECT MAX(ID) FROM hr_recontract_approval WHERE a.id=recontractId )",*/
        																				
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=#"
        ),
   array(
   		"name" => "recontractId",
   		"sql" => " and c.recontractId=#"
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "conNo",
   		"sql" => " and c.conNo like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "conNoEq",
   		"sql" => " and c.conNo=# "
   	  ),
   array(
   		"name" => "conName",
   		"sql" => " and c.conName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "conType",
   		"sql" => " and c.conType=# "
   	  ),
   array(
   		"name" => "conTypeName",
   		"sql" => " and c.conTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "conState",
   		"sql" => " and c.conState=# "
   	  ),
   array(
   		"name" => "conStateName",
   		"sql" => " and c.conStateName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName=# "
   	  ),
   array(
   		"name" => "jobId",
   		"sql" => " and c.jobId=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "conNum",
   		"sql" => " and c.conNum=# "
   	  ),
   array(
   		"name" => "conNumName",
   		"sql" => " and c.conNumName=# "
   	  ),
   array(
   		"name" => "conContent",
   		"sql" => " and c.conContent=# "
   	  ),
   array(
   		"name" => "recorderName",
   		"sql" => " and c.recorderName=# "
   	  ),
   array(
   		"name" => "recorderId",
   		"sql" => " and c.recorderId=# "
   	  ),
   array(
   		"name" => "recordDate",
   		"sql" => " and c.recordDate=# "
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
   	  )
)
?>