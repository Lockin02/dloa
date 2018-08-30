<?php
/**
 * @author Administrator
 * @Date 2013年10月9日 9:41:37
 * @version 1.0
 * @description:生产能力表明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.total,c.id ,c.produceId ,c.needNum ,c.proTime ,c.testTime ,c.packageTime ,c.proType  from oa_report_produce_info c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "produceId",
   		"sql" => " and c.produceId=# "
   	  ),
   array(
   		"name" => "needNum",
   		"sql" => " and c.needNum=# "
   	  ),
   array(
   		"name" => "proTime",
   		"sql" => " and c.proTime=# "
   	  ),
   array(
   		"name" => "testTime",
   		"sql" => " and c.testTime=# "
   	  ),
   array(
   		"name" => "packageTime",
   		"sql" => " and c.packageTime=# "
   	  ),
   array(
   		"name" => "proType",
   		"sql" => " and c.proType=# "
   	  )
)
?>