<?php
/**
 * @author Show
 * @Date 2013年12月11日 星期三 9:38:06
 * @version 1.0
 * @description:物料协议价信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productId ,c.productCode ,c.productName ,c.remark ,c.protocolType ,c.protocolTypeCode ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_purchase_material c where 1=1 "
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
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "protocolType",
   		"sql" => " and c.protocolType=# "
   	  ),
   array(
   		"name" => "protocolTypeCode",
   		"sql" => " and c.protocolTypeCode=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%') "
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