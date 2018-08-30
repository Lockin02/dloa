<?php
/**
 * @author Administrator
 * @Date 2012-08-01 10:34:58
 * @version 1.0
 * @description:商机联系人信息表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.chanceCode ,c.chanceName ,c.customerId ,c.linkmanId ,c.linkmanName ,c.telephone ,c.QQ ,c.section ,c.post ,c.officeTel ,c.mobileTel ,c.Email ,c.remark ,c.roleCode ,c.roleName ,c.isKeyMan  from oa_sale_chance_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode=# "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "linkmanId",
   		"sql" => " and c.linkmanId=# "
   	  ),
   array(
   		"name" => "linkmanName",
   		"sql" => " and c.linkmanName=# "
   	  ),
   array(
   		"name" => "telephone",
   		"sql" => " and c.telephone=# "
   	  ),
   array(
   		"name" => "QQ",
   		"sql" => " and c.QQ=# "
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
   		"name" => "mobileTel",
   		"sql" => " and c.mobileTel=# "
   	  ),
   array(
   		"name" => "Email",
   		"sql" => " and c.Email=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "roleCode",
   		"sql" => " and c.roleCode=# "
   	  ),
   array(
   		"name" => "roleName",
   		"sql" => " and c.roleName=# "
   	  ),
   array(
   		"name" => "isKeyMan",
   		"sql" => " and c.isKeyMan=# "
   	  )
)
?>