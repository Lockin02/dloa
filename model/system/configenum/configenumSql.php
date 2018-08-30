<?php
/**
 * @author Administrator
 * @Date 2012年7月19日 星期四 10:43:44
 * @version 1.0
 * @description:系统管理配置枚举 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.configEnum1 ,c.configEnum2 ,c.configEnum3 ,c.configEnum4 ,c.configEnum5 ,c.configEnum6 ,c.configEnum7 ,c.configEnum8 ,c.configEnum9 ,c.configEnum10 ,c.configEnumName  from oa_system_configenum c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "configEnum1",
   		"sql" => " and c.configEnum1=# "
   	  ),
   array(
   		"name" => "configEnum2",
   		"sql" => " and c.configEnum2=# "
   	  ),
   array(
   		"name" => "configEnum3",
   		"sql" => " and c.configEnum3=# "
   	  ),
   array(
   		"name" => "configEnum4",
   		"sql" => " and c.configEnum4=# "
   	  ),
   array(
   		"name" => "configEnum5",
   		"sql" => " and c.configEnum5=# "
   	  ),
   array(
   		"name" => "configEnum6",
   		"sql" => " and c.configEnum6=# "
   	  ),
   array(
   		"name" => "configEnum7",
   		"sql" => " and c.configEnum7=# "
   	  ),
   array(
   		"name" => "configEnum8",
   		"sql" => " and c.configEnum8=# "
   	  ),
   array(
   		"name" => "configEnum9",
   		"sql" => " and c.configEnum9=# "
   	  ),
   array(
   		"name" => "configEnum10",
   		"sql" => " and c.configEnum10=# "
   	  ),
   array(
   		"name" => "configEnumName",
   		"sql" => " and c.configEnumName=# "
   	  )
)
?>