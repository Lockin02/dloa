<?php
/**
 * @author Administrator
 * @Date 2012-09-25 14:44:59
 * @version 1.0
 * @description:销售备货 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.stockupCode ,c.type ,c.sourceType ,c.sourceId ,c.state ,c.remark ,c.ExaStatus ,c.ExaDT ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId  from oa_sale_stockup c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "stockupCode",
   		"sql" => " and c.stockupCode=# "
   	  ),
   array(
   		"name" => "type",
   		"sql" => " and c.type=# "
   	  ),
   array(
   		"name" => "sourceType",
   		"sql" => " and c.sourceType=# "
   	  ),
   array(
   		"name" => "sourceId",
   		"sql" => " and c.sourceId=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  )
)
?>