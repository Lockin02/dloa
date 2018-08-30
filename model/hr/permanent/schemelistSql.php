<?php
/**
 * @author jianjungki
 * @Date 2012年8月6日 15:23:48
 * @version 1.0
 * @description:员工考核项目细则 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.schemeCode ,c.schemeName ,c.standardId ,c.standardCode ,c.standard ,c.standardProportion ,c.standardContent ,c.standardPoint,c.standarScore  from oa_hr_permanent_schemelist c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "schemeCode",
   		"sql" => " and c.schemeCode=# "
   	  ),
   array(
   		"name" => "schemeName",
   		"sql" => " and c.schemeName=# "
   	  ),
   array(
   		"name" => "standardId",
   		"sql" => " and c.standardId=# "
   	  ),
   array(
   		"name" => "standardCode",
   		"sql" => " and c.standardCode=# "
   	  ),
   array(
   		"name" => "standard",
   		"sql" => " and c.standard=# "
   	  ),
   array(
   		"name" => "standardProportion",
   		"sql" => " and c.standardProportion=# "
   	  ),
   array(
   		"name" => "standardContent",
   		"sql" => " and c.standardContent=# "
   	  ),
   array(
   		"name" => "standardPoint",
   		"sql" => " and c.standardPoint=# "
   	  )
)
?>