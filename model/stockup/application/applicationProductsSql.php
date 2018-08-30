<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货明细表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productId ,c.productName ,c.remark ,c.listNo ,c.productNum ,c.productConfig ,c.exDeliveryDate ,c.appDeptId ,c.appDeptName ,c.appUserId ,c.appUserName  from oa_stockup_application_products c where 1=1 ",
         "pageItem"=>"select c.id ,c.productId ,c.productName,c.productCode ,c.remark ,c.listNo ,c.productNum ,c.productConfig ,c.exDeliveryDate ,c.appDeptId ,c.appDeptName ,c.appUserId ,c.appUserName  from oa_stockup_application_products c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "listId",
   		"sql" => " and c.listId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "listNo",
   		"sql" => " and c.listNo=# "
   	  ),
   array(
   		"name" => "productNum",
   		"sql" => " and c.productNum=# "
   	  ),
   array(
   		"name" => "productConfig",
   		"sql" => " and c.productConfig=# "
   	  ),
   array(
   		"name" => "exDeliveryDate",
   		"sql" => " and c.exDeliveryDate=# "
   	  ),
   array(
   		"name" => "appDeptId",
   		"sql" => " and c.appDeptId=# "
   	  ),
   array(
   		"name" => "appDeptName",
   		"sql" => " and c.appDeptName=# "
   	  ),
   array(
   		"name" => "appUserId",
   		"sql" => " and c.appUserId=# "
   	  ),
   array(
   		"name" => "appUserName",
   		"sql" => " and c.appUserName=# "
   	  )
)
?>