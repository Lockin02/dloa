<?php
/**
 * @author phz
 * @Date 2014年1月20日 星期一 10:37:38
 * @version 1.0
 * @description:工单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.approvalId ,c.approvalCode ,c.suppId ,c.suppName ,c.suppCode ,c.projectId ,c.projectName ,c.projectCode ,c.provinceId ,c.province ,c.suppType ,c.suppTypeName ,c.natureCode ,c.natureName ,c.number ,c.projectManager ,c.projectManagerId ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.ExaStatus ,c.ExaDT  from oa_outsourcing_workorder_order c where 1=1 ",
		//外包工单导出查询
		 "select_orderOut"=>"select c.id ,c.approvalCode,c.suppName ,c.suppCode ,c.projectName ,c.projectCode ,c.province ,c.suppTypeName ,c.natureName ,c.projectManager ,c.createName ,c.createTime ,c.ExaStatus ,c.ExaDT from oa_outsourcing_workorder_order c where 1=1 "	
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
   		"name" => "approvalId",
   		"sql" => " and c.approvalId=# "
   	  ),
   array(
   		"name" => "approvalCode",
   		"sql" => " and c.approvalCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "suppCodeE",
   		"sql" => " and c.suppCode =# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "provinceId",
   		"sql" => " and c.provinceId=# "
   	  ),
   array(
   		"name" => "province",
   		"sql" => " and c.province=# "
   	  ),
   array(
   		"name" => "suppType",
   		"sql" => " and c.suppType=# "
   	  ),
   array(
   		"name" => "suppTypeName",
   		"sql" => " and c.suppTypeName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "natureCode",
   		"sql" => " and c.natureCode=# "
   	  ),
   array(
   		"name" => "natureName",
   		"sql" => " and c.natureName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "projectManager",
   		"sql" => " and c.projectManager like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "projectManagerId",
   		"sql" => " and c.projectManagerId=# "
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
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  )
)
?>