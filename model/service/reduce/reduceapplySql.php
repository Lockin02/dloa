<?php
/**
 * @author Administrator
 * @Date 2011年12月3日 10:32:24
 * @version 1.0
 * @description:维修费用减免申请单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.applyId ,c.applyCode ,c.customerId ,c.customerName ,c.adress ,c.applyUserName ,c.applyUserCode ,c.subCost ,c.subReduceCost ,c.ExaStatus ,c.ExaDT ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_service_reduce_apply c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyCode",
   		"sql" => " and c.applyCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "applyUserName",
   		"sql" => " and c.applyUserName=# "
   	  ),
   array(
   		"name" => "applyUserCode",
   		"sql" => " and c.applyUserCode=# "
   	  ),
   array(
   		"name" => "subCost",
   		"sql" => " and c.subCost=# "
   	  ),
   array(
   		"name" => "subReduceCost",
   		"sql" => " and c.subReduceCost=# "
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
   	  ),
  array(
   		"name" => "productCode",
   		"sql" => " and c.id in( select mainId from oa_service_reduce_item  where productCode like CONCAT('%',#,'%'))   "
   	  ),
  array(
   		"name" => "productName",
   		"sql" => " and c.id in( select mainId from oa_service_reduce_item  where productName like CONCAT('%',#,'%'))   "
   	  )
)
?>