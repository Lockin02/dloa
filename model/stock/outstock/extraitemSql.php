<?php
/**
 * @author Administrator
 * @Date 2011��5��22�� 17:29:17
 * @version 1.0
 * @description:���ⵥ���϶��������嵥 sql�����ļ� ������Ʒ�İ�װ�Ӳ����Ʒ��Ӧ�����
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.extraType ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.outstockNum ,c.price ,c.remark  from oa_stock_stockout_extraitem c where 1=1 "
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
   		"name" => "extraType",
   		"sql" => " and c.extraType=# "
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
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "outstockNum",
   		"sql" => " and c.outstockNum=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>