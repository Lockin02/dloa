<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:17:39
 * @version 1.0
 * @description:采购订单_供应商主信息 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.suppName ,c.suppId ,c.suppTel ,c.quote ,c.taxRate ,c.arrivalDate ,c.payRatio ,c.paymentConditionName ,c.paymentCondition ,c.remark  from oa_purch_apply_supp c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppTel",
   		"sql" => " and c.suppTel=# "
   	  ),
   array(
   		"name" => "quote",
   		"sql" => " and c.quote=# "
   	  ),
   array(
   		"name" => "taxRate",
   		"sql" => " and c.taxRate=# "
   	  ),
   array(
   		"name" => "arrivalDate",
   		"sql" => " and c.arrivalDate=# "
   	  ),
   array(
   		"name" => "payRatio",
   		"sql" => " and c.payRatio=# "
   	  ),
   array(
   		"name" => "paymentConditionName",
   		"sql" => " and c.paymentConditionName=# "
   	  ),
   array(
   		"name" => "paymentCondition",
   		"sql" => " and c.paymentCondition=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>