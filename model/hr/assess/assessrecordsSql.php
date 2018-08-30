<?php
/**
 * @author Administrator
 * @Date 2013年4月23日 星期二 16:38:39
 * @version 1.0
 * @description:季度考核信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.tpl_id ,c.name ,c.user_id ,c.quarter ,c.years ,c.count_my_fraction ,c.count_assess_fraction ,c.count_audit_fraction ,c.average_assess_fraction ,c.average_audit_fraction ,c.assess_userid ,c.audit_userid ,c.my_opinion ,c.assess_opinion ,c.audit_opinion ,c.file_path ,c.filename_str ,c.my_status ,c.assess_status ,c.audit_status ,c.email_status ,c.assess_date ,c.audit_date ,c.date ,c.level ,c.deptId ,c.deptName ,c.jobId ,c.jobName ,c.comeInDate ,c.ReguDate ,c.userName ,c.assessName ,c.auditName ,c.tplStyleFlag ,c.asPers ,c.asAudit ,c.asAss ,c.isAss ,c.inFlag ,c.userNo ,c.isEval ,c.pevFraction ,c.pevStatus ," .
         		"c.countFraction ,c.deptRank ,c.deptRankPer,p.deptName,p.deptNameS,p.deptNameT,p.deptNameF " .
         		"from appraisal_performance c left join oa_hr_personnel p on c.userNo=p.userNo where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "tpl_id",
   		"sql" => " and c.tpl_id=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "user_id",
   		"sql" => " and c.user_id=# "
   	  ),
   array(
   		"name" => "quarter",
   		"sql" => " and c.quarter=# "
   	  ),
   array(
   		"name" => "years",
   		"sql" => " and c.years=# "
   	  ),
   array(
   		"name" => "count_my_fraction",
   		"sql" => " and c.count_my_fraction=# "
   	  ),
   array(
   		"name" => "count_assess_fraction",
   		"sql" => " and c.count_assess_fraction=# "
   	  ),
   array(
   		"name" => "count_audit_fraction",
   		"sql" => " and c.count_audit_fraction=# "
   	  ),
   array(
   		"name" => "average_assess_fraction",
   		"sql" => " and c.average_assess_fraction=# "
   	  ),
   array(
   		"name" => "average_audit_fraction",
   		"sql" => " and c.average_audit_fraction=# "
   	  ),
   array(
   		"name" => "assess_userid",
   		"sql" => " and c.assess_userid=# "
   	  ),
   array(
   		"name" => "audit_userid",
   		"sql" => " and c.audit_userid=# "
   	  ),
   array(
   		"name" => "my_opinion",
   		"sql" => " and c.my_opinion=# "
   	  ),
   array(
   		"name" => "assess_opinion",
   		"sql" => " and c.assess_opinion=# "
   	  ),
   array(
   		"name" => "audit_opinion",
   		"sql" => " and c.audit_opinion=# "
   	  ),
   array(
   		"name" => "file_path",
   		"sql" => " and c.file_path=# "
   	  ),
   array(
   		"name" => "filename_str",
   		"sql" => " and c.filename_str=# "
   	  ),
   array(
   		"name" => "my_status",
   		"sql" => " and c.my_status=# "
   	  ),
   array(
   		"name" => "assess_status",
   		"sql" => " and c.assess_status=# "
   	  ),
   array(
   		"name" => "audit_status",
   		"sql" => " and c.audit_status=# "
   	  ),
   array(
   		"name" => "email_status",
   		"sql" => " and c.email_status=# "
   	  ),
   array(
   		"name" => "assess_date",
   		"sql" => " and c.assess_date=# "
   	  ),
   array(
   		"name" => "audit_date",
   		"sql" => " and c.audit_date=# "
   	  ),
   array(
   		"name" => "date",
   		"sql" => " and c.date=# "
   	  ),
   array(
   		"name" => "level",
   		"sql" => " and c.level=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "jobId",
   		"sql" => " and c.jobId=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName=# "
   	  ),
   array(
   		"name" => "comeInDate",
   		"sql" => " and c.comeInDate=# "
   	  ),
   array(
   		"name" => "ReguDate",
   		"sql" => " and c.ReguDate=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "assessName",
   		"sql" => " and c.assessName=# "
   	  ),
   array(
   		"name" => "auditName",
   		"sql" => " and c.auditName=# "
   	  ),
   array(
   		"name" => "tplStyleFlag",
   		"sql" => " and c.tplStyleFlag=# "
   	  ),
   array(
   		"name" => "asPers",
   		"sql" => " and c.asPers=# "
   	  ),
   array(
   		"name" => "asAudit",
   		"sql" => " and c.asAudit=# "
   	  ),
   array(
   		"name" => "asAss",
   		"sql" => " and c.asAss=# "
   	  ),
   array(
   		"name" => "isAss",
   		"sql" => " and c.isAss=# "
   	  ),
   array(
   		"name" => "inFlag",
   		"sql" => " and c.inFlag=# "
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo=# "
   	  ),
   array(
   		"name" => "isEval",
   		"sql" => " and c.isEval=# "
   	  ),
   array(
   		"name" => "pevFraction",
   		"sql" => " and c.pevFraction=# "
   	  ),
   array(
   		"name" => "pevStatus",
   		"sql" => " and c.pevStatus=# "
   	  ),
   array(
   		"name" => "countFraction",
   		"sql" => " and c.countFraction=# "
   	  ),
   array(
   		"name" => "deptRank",
   		"sql" => " and c.deptRank=# "
   	  ),
   array(
   		"name" => "deptRankPer",
   		"sql" => " and c.deptRankPer=# "
   	  ),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptNameS",
		"sql" => " and c.deptNameS like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "deptNameT",
		"sql" => " and c.deptNameT like CONCAT('%',#,'%') "
	)
)
?>