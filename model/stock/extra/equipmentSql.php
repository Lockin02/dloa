<?php
/**
 * @author huangzf
 * @Date 2012年7月11日 星期三 14:18:58
 * @version 1.0
 * @description:常用设备基本信息 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.equipName ,c.isProduce ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_extra_equipment c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "equipName",
   		"sql" => " and c.equipName like CONCAT('%',# ,'%')"
   	  ),
   array(
   		"name" => "isProduce",
   		"sql" => " and c.isProduce=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>