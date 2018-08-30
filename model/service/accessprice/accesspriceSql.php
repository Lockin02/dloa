<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 9:52:49
 * @version 1.0
 * @description:零配件价格表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productId ,c.productCode ,c.productName ,c.warranty ,c.pattern ,c.unitName ,c.lowPrice ,c.highPrice ,c.strartDate ,c.endDate ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_service_access_price c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "warranty",
   		"sql" => " and c.warranty=# "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "lowPrice",
   		"sql" => " and c.lowPrice=# "
   	  ),
   array(
   		"name" => "highPrice",
   		"sql" => " and c.highPrice=# "
   	  ),
   array(
   		"name" => "strartDate",
   		"sql" => " and c.strartDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
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