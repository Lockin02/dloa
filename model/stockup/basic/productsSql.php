<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:05:02
 * @version 1.0
 * @description:������Ʒ��Ϣ�� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productName ,c.remark ,c.isClose ,c.productCode ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_stockup_products c where 1=1 ",
         "pageSelect"=>"select c.id ,c.productName ,c.remark,c.productCode  from oa_stockup_products c where 1=1 and c.isClose=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like concat('%',#,'%')  "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isClose",
   		"sql" => " and c.isClose=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
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