<?php
/**
 * @author Administrator
 * @Date 2014年2月11日 10:18:16
 * @version 1.0
 * @description:开票类型 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.invoiceCode ,c.invoiceValue  from oa_contract_invoiceType c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "invoiceCode",
   		"sql" => " and c.invoiceCode=# "
   	  ),
   array(
   		"name" => "invoiceValue",
   		"sql" => " and c.invoiceValue=# "
   	  )
)
?>