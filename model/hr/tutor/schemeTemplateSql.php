<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:39:28
 * @version 1.0
 * @description:导师考核表模板 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.appraisal ,c.coefficient ,c.scaleA ,c.scaleB ,c.scaleC ,c.scaleD ,c.scaleE ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_tutor_schemeTemplate c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "appraisal",
   		"sql" => " and c.appraisal=# "
   	  ),
   array(
   		"name" => "coefficient",
   		"sql" => " and c.coefficient=# "
   	  ),
   array(
   		"name" => "scaleA",
   		"sql" => " and c.scaleA=# "
   	  ),
   array(
   		"name" => "scaleB",
   		"sql" => " and c.scaleB=# "
   	  ),
   array(
   		"name" => "scaleC",
   		"sql" => " and c.scaleC=# "
   	  ),
   array(
   		"name" => "scaleD",
   		"sql" => " and c.scaleD=# "
   	  ),
   array(
   		"name" => "scaleE",
   		"sql" => " and c.scaleE=# "
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
   		"name" => "sysCompanyName",
   		"sql" => " and c.sysCompanyName=# "
   	  ),
   array(
   		"name" => "sysCompanyId",
   		"sql" => " and c.sysCompanyId=# "
   	  )
)
?>