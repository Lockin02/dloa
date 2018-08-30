<?php
/**
 * @author Administrator
 * @Date 2012年6月28日 星期四 16:55:01
 * @version 1.0
 * @description:供应商评估任务 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.assessType ,c.assessTypeName ,c.assesYear,c.assesQuarter,c.suppId ,c.suppName ,c.formDate ,hopeDate,c.taskDate ,c.suppLinkName ,c.suppTel ,c.mainProduct ,c.suppSource ,c.assesManId ,c.assesManName ,c.totalNum ,c.state ,c.suppGrade ,c.purchManId ,c.purchManName ,c.approvalRemark ,c.closeManId ,c.closeManName ,c.closeDate ,c.clsoeReason ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_supp_suppasses_task c where 1=1 ",

         "select_taskinfo"=>"select c.id ,c.formCode ,c.assessType ,c.assessTypeName ,c.suppId ,c.suppName ,c.formDate ,hopeDate,c.taskDate  ,c.assesManId ,c.assesManName ,c.totalNum ,c.state ,c.suppGrade ,c.purchManId ,c.purchManName ,c.approvalRemark   from oa_supp_suppasses_task c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "assessType",
   		"sql" => " and c.assessType=# "
   	  ),
   array(
   		"name" => "assessTypeName",
   		"sql" => " and c.assessTypeName=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
   	  ),
   array(
   		"name" => "formDateSear",
   		"sql" => " and c.formDate LIKE BINARY CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "taskDate",
   		"sql" => " and c.taskDate=# "
   	  ),
   array(
   		"name" => "hopeDate",
   		"sql" => " and c.hopeDate=# "
   	  ),
   array(
   		"name" => "taskDateSear",
   		"sql" => " and c.taskDate LIKE BINARY  CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "suppLinkName",
   		"sql" => " and c.suppLinkName=# "
   	  ),
   array(
   		"name" => "suppTel",
   		"sql" => " and c.suppTel=# "
   	  ),
   array(
   		"name" => "mainProduct",
   		"sql" => " and c.mainProduct=# "
   	  ),
   array(
   		"name" => "suppSource",
   		"sql" => " and c.suppSource=# "
   	  ),
   array(
   		"name" => "assesManId",
   		"sql" => " and c.assesManId=# "
   	  ),
   array(
   		"name" => "assesManName",
   		"sql" => " and c.assesManName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "totalNum",
   		"sql" => " and c.totalNum=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "stateArr",
   		"sql" => " and c.state in(arr) "
   	  ),
   array(
   		"name" => "suppGrade",
   		"sql" => " and c.suppGrade=# "
   	  ),
   array(
   		"name" => "purchManId",
   		"sql" => " and c.purchManId=# "
   	  ),
   array(
   		"name" => "purchManName",
   		"sql" => " and c.purchManName=# "
   	  ),
   array(
   		"name" => "approvalRemark",
   		"sql" => " and c.approvalRemark=# "
   	  ),
   array(
   		"name" => "closeManId",
   		"sql" => " and c.closeManId=# "
   	  ),
   array(
   		"name" => "closeManName",
   		"sql" => " and c.closeManName=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "clsoeReason",
   		"sql" => " and c.clsoeReason=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
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
	array (
		"name" => "year",
		"sql" => "and YEAR(c.formDate) = #"
	),
	array (
		"name" => "beginDate",
		"sql" => "and date_format(formDate,'%Y-%m') >= #"
	),
	array (
		"name" => "endDate",
		"sql" => "and date_format(formDate,'%Y-%m') <= #"
	),
    array(
        "name" => "assesYear",
        "sql" => " and c.assesYear=# "
    ),
    array(
        "name" => "assesQuarter",
        "sql" => " and c.assesQuarter=# "
    )
)
?>