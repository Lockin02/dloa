<?php
/**
 * @author Administrator
 * @Date 2012-10-30 10:32:46
 * @version 1.0
 * @description:设备配置 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.budgetTypeId ,c.budgetTypeName ,c.equId ,c.equCode ,c.equName ,c.equRemark ,c.name ,c.info ,c.price ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_equ_baseinfo_deploy c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "budgetTypeId",
   		"sql" => " and c.budgetTypeId=# "
   	  ),
   array(
   		"name" => "budgetTypeName",
   		"sql" => " and c.budgetTypeName=# "
   	  ),
   array(
   		"name" => "equId",
   		"sql" => " and c.equId=# "
   	  ),
   array(
   		"name" => "equCode",
   		"sql" => " and c.equCode=# "
   	  ),
   array(
   		"name" => "equName",
   		"sql" => " and c.equName=# "
   	  ),
   array(
   		"name" => "equRemark",
   		"sql" => " and c.equRemark=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "info",
   		"sql" => " and c.info=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
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