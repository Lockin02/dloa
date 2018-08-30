<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 星期日 15:16:42
 * @version 1.0
 * @description:导师考核方案 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.formDate ,c.schemeCode ,c.schemeTypeCode ,c.schemeTypeName ,c.schemeName ,c.ExaDT ,c.schemePass ,c.supProportion ,c.hrProportion ,c.deptProportion ,c.tutProportion ,c.stuProportion,c.ExaStatus ,c.schemeTotal ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.remark  from oa_hr_tutor_tutorscheme c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode=# "
   	  ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
   	  ),
   array(
   		"name" => "schemeCode",
   		"sql" => " and c.schemeCode=# "
   	  ),
   array(
   		"name" => "schemeTypeCode",
   		"sql" => " and c.schemeTypeCode=# "
   	  ),
   array(
   		"name" => "schemeTypeName",
   		"sql" => " and c.schemeTypeName=# "
   	  ),
   array(
   		"name" => "schemeName",
   		"sql" => " and c.schemeName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "schemePass",
   		"sql" => " and c.schemePass=# "
   	  ),
   array(
   		"name" => "supProportion",
   		"sql" => " and c.supProportion=# "
   	  ),
   array(
   		"name" => "hrProportion",
   		"sql" => " and c.hrProportion=# "
   	  ),
   array(
   		"name" => "deptProportion",
   		"sql" => " and c.deptProportion=# "
   	  ),
   array(
   		"name" => "tutProportion",
   		"sql" => " and c.tutProportion=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "schemeTotal",
   		"sql" => " and c.schemeTotal=# "
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
   	  )
)
?>