<?php
/**
 * @author suxc
 * @Date 2011年8月12日 14:52:51
 * @version 1.0
 * @description:线索联系人信息表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.cluesId ,c.cluesCode ,c.cluesName ,c.customerId ,c.linkmanId ,c.linkmanName ,c.section ,c.post ,c.officeTel ,c.mobileTel ,c.email ,c.roleCode ,c.roleName ,c.isKeyMan  from oa_sale_clues_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "cluesId",
   		"sql" => " and c.cluesId=# "
   	  ),
   array(
   		"name" => "cluesCode",
   		"sql" => " and c.cluesCode=# "
   	  ),
   array(
   		"name" => "cluesName",
   		"sql" => " and c.cluesName=# "
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
   		"name" => "email",
   		"sql" => " and c.email=# "
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