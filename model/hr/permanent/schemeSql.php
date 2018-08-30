<?php
/**
 * @author jianjungki
 * @Date 2012年8月3日 11:01:18
 * @version 1.0
 * @description:员工考核方案 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.formDate ,c.schemeCode ,c.schemeTypeCode ,c.schemeTypeName ,c.schemeName ,c.schemeTotal ,c.schemePass ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_hr_permanent_scheme c where 1=1 "
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
   		"name" => "schemeCodeSearch",
   		"sql" => " and c.schemeCode like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "schemeTypeCode",
   		"sql" => " and c.schemeTypeCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "schemeTypeName",
   		"sql" => " and c.schemeTypeName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "schemeName",
   		"sql" => " and c.schemeName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "schemeTotal",
   		"sql" => " and c.schemeTotal=# "
   	  ),
   array(
   		"name" => "schemePass",
   		"sql" => " and c.schemePass=# "
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
   array(
   		"name" => "schemeCodeEq",
   		"sql" => " and c.schemeCode=#"
   	  ),
   array(
   		"name" => "schemeNameEq",
   		"sql" => " and c.schemeName=# "
   	  ),
   array(
   		"name" => "schemeTypeCodeEq",
   		"sql" => " and c.schemeTypeCode=# "
   	  ),
   array(
   		"name" => "schemeTypeNameSearch",
   		"sql" => " and c.schemeTypeName=# "
   	  )
)
?>