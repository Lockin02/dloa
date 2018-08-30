<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:15:29
 * @version 1.0
 * @description:合同联系人信息表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractName ,c.contractCode ," .
         		"c.contractId ,c.linkmanId ,c.linkmanName,c.QQ,c.position,c.section ," .
         		"c.post  ,c.telephone ,c.Email ,c.remark ,c.isTemp ,c.originalId,c.changeTips" .
         		"  from oa_contract_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
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
   		"name" => "telephone",
   		"sql" => " and c.telephone=# "
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
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  )
)
?>