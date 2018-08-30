<?php
/**
 * @author Show
 * @Date 2011年5月9日 星期一 19:44:55
 * @version 1.0
 * @description:服务项目表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formNo ,c.objId ,c.objCode ,c.objName ,c.technicians,c.salesman ,c.status ,c.trainDate ,c.customerName ,c.cusLinkman ,c.cusLinkPhone ,c.createName ,c.createTime  from oa_ts_task c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
        ),
   array(
   		"name" => "formNo",
   		"sql" => " and c.formNo=# "
   	  ),
   array(
   		"name" => "formNoSearch",
   		"sql" => " and c.formNo like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   	  ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "objName",
   		"sql" => " and c.objName=# "
   	  ),
   array(
   		"name" => "salesmanId",
   		"sql" => " and c.salesmanId=# "
   	  ),
   array(
   		"name" => "trainDate",
   		"sql" => " and c.trainDate=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "statusIn",
   		"sql" => " and c.status in(arr) "
   	  )
)
?>