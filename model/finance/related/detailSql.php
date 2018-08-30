<?php
/**
 * @author Show
 * @Date 2010年12月29日 星期三 20:07:33
 * @version 1.0
 * @description:发表勾稽记录表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.relatedId ,c.productId ,c.productCode ,c.productModel ,c.number ,c.amount ,c.hookNumber ,c.hookAmount ,c.unHookNumber ,c.unHookAmount ,c.formDate ,c.supplierId ,c.supplierName ,c.purType ,c.property ,c.unit ,c.isAcount ,c.hookObjCode ,c.hookObj ,c.hookId ,c.hookDate  from oa_finance_related_detail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "relatedId",
   		"sql" => " and c.relatedId=# "
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
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount=# "
   	  ),
   array(
   		"name" => "hookNumber",
   		"sql" => " and c.hookNumber=# "
   	  ),
   array(
   		"name" => "hookAmount",
   		"sql" => " and c.hookAmount=# "
   	  ),
   array(
   		"name" => "unHookNumber",
   		"sql" => " and c.unHookNumber=# "
   	  ),
   array(
   		"name" => "unHookAmount",
   		"sql" => " and c.unHookAmount=# "
   	  ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
   	  ),
   array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName=# "
   	  ),
   array(
   		"name" => "purType",
   		"sql" => " and c.purType=# "
   	  ),
   array(
   		"name" => "property",
   		"sql" => " and c.property=# "
   	  ),
   array(
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   array(
   		"name" => "isAcount",
   		"sql" => " and c.isAcount=# "
   	  ),
   array(
   		"name" => "hookObjCode",
   		"sql" => " and c.hookObjCode=# "
   	  ),
   array(
   		"name" => "hookObj",
   		"sql" => " and c.hookObj=# "
   	  ),
   array(
   		"name" => "hookId",
   		"sql" => " and c.hookId=# "
   	  ),
   array(
   		"name" => "hookDate",
   		"sql" => " and c.hookDate=# "
   	  )
)
?>