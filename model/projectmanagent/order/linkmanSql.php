<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 20:13:37
 * @version 1.0
 * @description:销售合同联系人信息表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderId,c.orderCode ,c.orderName ,c.linkmanId ,c.linkman ,c.section ,c.post ,c.officeTel ,c.telephone ,c.email ,c.remark,c.isTemp,c.originalId  from oa_sale_order_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
   	  ),
   array(
   		"name" => "linkmanId",
   		"sql" => " and c.linkmanId=# "
   	  ),
   array(
   		"name" => "linkman",
   		"sql" => " and c.linkman=# "
   	  ),
   array(
   		"name" => "section",
   		"sql" => " and c.section=# "
   	  ),
   array(
   		"name" => "post",
   		"sql" => " and c.post=# "
   	  ),
   array(
   		"name" => "officeTel",
   		"sql" => " and c.officeTel=# "
   	  ),
   array(
   		"name" => "telephone",
   		"sql" => " and c.telephone=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>