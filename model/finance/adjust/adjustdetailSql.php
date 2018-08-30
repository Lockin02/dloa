<?php
/**
 * @author Show
 * @Date 2011年1月13日 星期四 17:22:31
 * @version 1.0
 * @description:补差单条目 sql配置文件
 */
$sql_arr = array (
     "select_default"=>"select c.id ,c.adjustId ,c.productId ,c.productNo ,c.productName ,c.productModel ,c.cost ,c.price ,c.differ ,c.allDiffer ,c.number  from oa_finance_adjustment_detail c where 1=1 ",
     "productadjust" => "select d.id,d.productId,sum(d.number) as allNumber,sum(d.allDiffer) as allDiffer from oa_finance_adjustment_detail d left join oa_finance_adjustment a on d.adjustId = a.id "

);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "adjustId",
   		"sql" => " and c.adjustId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "cost",
   		"sql" => " and c.cost=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "differ",
   		"sql" => " and c.differ=# "
   	  ),
   array(
   		"name" => "allDiffer",
   		"sql" => " and c.allDiffer=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "astatus",
   		"sql" => " and a.status=# "
   	  )
)
?>