<?php
/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息 sql配置文件
 */
$sql_arr = array (
         
         "select_default"=>"select * 
							from hr_recontract c where 1=1 ",
		"select_defaults"=>"select c.id ,c.userName, c.userNo, c.statusId,c.ExaStatus,c.userAccount ,c.conNo ,c.conName ,c.conType ,
						       c.conTypeName ,c.conState ,c.conStateName ,c.oconState ,c.oconStateName ,c.obeginDate ,c.beginDate ,
						       c.jobName ,c.jobId ,c.ocloseDate,c.closeDate ,c.conNum ,c.conNumName ,c.oconNum ,c.oconNumName,c.conContent ,
						       c.recorderName ,c.recorderId ,c.recordDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,
						       c.updateTime ,c.companyName ,c.CompanyId,c.deptName,c.comeinDate,c.signCompanyName,c.oconNumsName,oconNums
						       c.aconState ,c.aconStateName,c.aconNum ,c.aconNumName,c.aisFlag ,c.aisFlagName,c.isPaperContract,
						       c.pconNumName,c.pconStateName ,c.pisFlag,c.pisFlagName,c.conNumName,c.conStateName ,c.isFlagName,c.isFlag,c.repaAddress 
							from hr_recontract c where 1=1 ",					
		"select_userinfo"=>"SELECT IF(a.Company='贝讯','bx',a.Company) AS companyId,i.NameCN AS companyName,b.DEPT_NAME AS deptName,a.DEPT_ID AS deptId,c.`name`
	      						AS jobName ,a.jobs_id AS jobId ,e.COME_DATE AS comeinDate,DATE_FORMAT(e.ContFlagB,'%Y-%m-%d') AS beginDate 
								,DATE_FORMAT(e.ContFlagE,'%Y-%m-%d') AS closeDate 
							FROM  user as  a ,department as b ,user_jobs  as c , hrms as e ,branch_info i 
							WHERE  a.DEPT_ID=b.DEPT_ID  AND  a.jobs_id=c.id   AND  a.USER_ID=e.USER_ID AND IF(a.Company='贝讯','bx',a.Company)=i.NamePT   ",
		 "select_Arbitra1"=>"SELECT  a.*
							FROM hr_recontract_approval a LEFT JOIN hr_recontract b ON  (b.id=a.recontractId)
							WHERE  1=1 ",
		 "select_Arbitra"=>" select b.Item,a.*
							 from hr_recontract_approval a  
							 LEFT JOIN flow_step b ON a.flow_step_id=b.ID where 1=1 and a.mark<>'ST'",						
		 "select_AppInfo"=>"select c.ID as fspId,b.task,d.item
							from hr_recontract a,wf_task b ,flow_step_partent c, flow_step d
							where b.`name`='合同续签'  AND b.`code`='hr_recontract' and b.Pid=a.id
							and b.task=c.Wf_task_ID  and  find_in_set('".$_SESSION['USER_ID']."',c.User)
                            and d.id=c.StepID  "	
			);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
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
   		"name" => "sltArbitra_id",
   		"sql" => " and a.recontractId=# "
   	  ),
   array(
   		"name" => "user_id",
   		"sql" => " and a.user_id=# "
   	  ),
   array(
   		"name" => "sltAppInfo_id",
   		"sql" => " and a.id=# "
   	  ),
   array(
   		"name" => "sltFsp_id",
   		"sql" => " and c.id=# "
   	  ),
   array(
   		"name" => "statusId",
   		"sql" => " and c.statusId=# "
   	  ),
   array(
   		"name" => "isPaperContract",
   		"sql" => " and c.isPaperContract=#"
   	  ),
   array(
   		"name" => "year",
   		"sql" => " and DATE_FORMAT(c.recordDate,'%Y')=#"
   	  ),
   array(
   		"name" => "month",
   		"sql" => " and DATE_FORMAT(c.recordDate,'%m')=#"
   	  ),
   array(
   		"name" => "yearMonth",
   		"sql" => " and DATE_FORMAT(c.recordDate,'%Y-%m')=#"
   	  )
)
?>