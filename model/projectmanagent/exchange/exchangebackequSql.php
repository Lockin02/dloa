<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:换货退货物料清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.exchangeId ,c.exchangeObjCode ,c.contractId ,c.contractequId ,c.productName ," .
         		"c.productId ,c.productCode ,c.productModel ,c.number,c.price,c.money ,c.executedNum ,c.remark ,c.issuedBackNum " .
         		" from oa_contract_exchange_backequ c where 1=1 ",
         //从表编辑用到的sql
         "select_edit"=>"select c.*,(con.executedNum-if(con.backNum is null,0,con.backNum)) as maxNum " .
         		"from oa_contract_exchange_backequ c left join oa_contract_equ con on c.contractequId=con.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
	array(
		"name" => "equIdArr",
		"sql" => " and c.Id in(arr) "
	  ),
   array(
   		"name" => "exchangeId",
   		"sql" => " and c.exchangeId=# "
   	  ),
   array(
   		"name" => "exchangeObjCode",
   		"sql" => " and c.exchangeObjCode=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractequId",
   		"sql" => " and c.contractequId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "backNum",
   		"sql" => " and c.backNum=# "
   	  ),
   array(
   		"name" => "hasBackNum",
   		"sql" => " and c.hasBackNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>