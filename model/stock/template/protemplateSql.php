<?php
/**
 * @author Show
 * @Date 2013��8��2�� ������ 14:41:22
 * @version 1.0
 * @description:����ģ�����ñ� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.templateName ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_product_template c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "templateName",
   		"sql" => " and c.templateName like CONCAT('%',#,'%') "
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