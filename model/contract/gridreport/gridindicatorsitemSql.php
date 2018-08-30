<?php
/**
 * @author yxin1
 * @Date 2014年12月2日 14:40:56
 * @version 1.0
 * @description:表格指标表明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.indicatorsName ,c.indicatorsCode ,c.isEnable  from oa_system_gridindicators_item c where 1=1 "
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
   		"name" => "indicatorsName",
   		"sql" => " and c.indicatorsName=# "
   	  ),
   array(
   		"name" => "indicatorsCode",
   		"sql" => " and c.indicatorsCode=# "
   	  ),
   array(
   		"name" => "isEnable",
   		"sql" => " and c.isEnable=# "
   	  )
)
?>