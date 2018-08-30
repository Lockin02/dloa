<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"SELECT d.Item,c.mark,c.id ,c.userName ,c.userNo ,c.userAccount ,c.conNo ,c.conName ,c.conType ,c.conTypeName ,c.conState ,c.conStateName ,c.beginDate ,c.jobName ,c.jobId ,c.closeDate ,c.conNum ,c.conNumName ,c.conContent ,c.recorderName ,c.recorderId ,c.recordDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.userAccount,c.isFlagName   from hr_recontract_approval c  LEFT JOIN flow_step d ON c.flow_step_id=d.ID where 1=1 ",
         "select_appList"=>"SELECT b.start,c.Flag,c.Result,c.ID as id,a.id as pid,a.conTypeName,a.conName,a.comeinDate,a.jobName,a.deptName,a.companyName,a.userName
						      ,a.userNo,d.conContent,d.conStateName,d.conNumName,d.closeDate,d.beginDate,a.statusId,a.ocloseDate,a.obeginDate,a.oconNumName,a.oconStateName 
							FROM hr_recontract a,wf_task b ,flow_step_partent c,hr_recontract_approval d 
							where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=a.id  AND d.recontractId=a.id 
							and b.task=c.Wf_task_ID   and c.Flag='0' and c.Result=''  and  find_in_set('".$_SESSION['USER_ID']."',c.User)
                      		and  d.id IN(SELECT MAX(id) from hr_recontract_approval WHERE  recontractId=a.id)",
		"select_appListPost"=>"SELECT b.start,c.Flag,c.Result,c.ID as id,a.id as pid,a.conTypeName,a.conName,a.comeinDate,a.jobName,a.deptName,a.companyName,a.userName
						      ,a.userNo,d.conContent,d.conStateName,d.conNumName,d.closeDate,d.beginDate,a.statusId,a.ocloseDate,a.obeginDate,a.oconNumName,a.oconStateName 
							FROM hr_recontract a,wf_task b ,flow_step_partent c,hr_recontract_approval d 
							where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=a.id  AND d.recontractId=a.id 
							and b.task=c.Wf_task_ID  AND d.fspId=c.ID    and c.Flag='1' and c.Result='1' and  find_in_set('".$_SESSION['USER_ID']."',c.User) ",
		"select_appListAll"=>"SELECT b.start,c.Flag,c.Result,c.ID as id,a.id as pid,a.conTypeName,a.conName,a.comeinDate,a.jobName,a.deptName,a.companyName,a.userName
						      ,a.userNo,d.conContent,d.conStateName,d.conNumName,d.closeDate,d.beginDate,a.statusId,a.ocloseDate,a.obeginDate,a.oconNumName,a.oconStateName 
							FROM hr_recontract a,wf_task b ,flow_step_partent c,hr_recontract_approval d 
							where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=a.id  AND d.recontractId=a.id 
							and b.task=c.Wf_task_ID  AND d.fspId=c.ID    and c.Flag='1' and c.Result='1' and  find_in_set('".$_SESSION['USER_ID']."',c.User)",
         "select_StaffList"=>"SELECT a.id,a.userName,a.statusId,a.userNo ,a.userAccount ,a.companyName,a.deptName,a.comeinDate,
								     a.oconStateName ,a.obeginDate ,a.jobName ,a.jobId ,a.ocloseDate ,a.oconNum ,a.oconNumName ,
								     a.beginDate,a.closeDate,a.conStateName,a.conNumName ,a.conContent  
								FROM hr_recontract a   
								WHERE 1=1     and  find_in_set('".$_SESSION['USER_ID']."',a.userAccount)  and  a.statusId in (5,6,7,8) ",
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