<?php
/**
 * @author Administrator
 * @Date 2012-06-29 10:15:12
 * @version 1.0
 * @description:销售助理操作记录 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractId ,c.handleType ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId  from oa_contract_aidhandle c where 1=1 ",
         "select_gridinfo"=>"select * from (select a.id,a.contractId,a.createTime,a.createName,a.handleType,
                IF(a.handleType = 'KJYYSWJ', b.Code, c.contractCode) AS contractCode,c.contractName, 
                IF(a.handleType = 'KJYYSWJ', b.customerName, c.customerName) AS customerName,
                IF(a.handleType = 'KJYYSWJ', b.salesName, c.prinvipalName) AS prinvipalName,c.areaPrincipal from oa_contract_aidhandle a 
                left join oa_contract_contract c on (a.contractId=c.id and (a.handleType = 'FJSC')) 
                left join oa_borrow_borrow b on (a.contractId=b.id and (a.handleType = 'KJYYSWJ')))c 
                where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "handleType",
   		"sql" => " and c.handleType=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   		"name" => "contractName",
   		"sql" => " and c.contractName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%') "
   	  )
)
?>