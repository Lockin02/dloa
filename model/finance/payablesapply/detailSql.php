<?php
/**
 * @author Show
 * @Date 2011年5月8日 星期日 13:55:28
 * @version 1.0
 * @description:付款申请详细(新) sql配置文件
 */
$sql_arr = array (
     "select_default"=>"select c.id ,c.payapplyId ,c.quantity ,c.money ,c.cashsubject ,c.objId ,c.objCode ,c.objType ,c.purchaseMoney ,c.payDesc ,c.expand1 ,c.expand2 ,c.expand3 ,c.payedMoney ,c.handInvoiceMoney ,c.orgFormType ,c.orgFormNo ,c.productId ,c.productNo ,c.productName ,c.productModel ,c.unitName ,c.number ,c.price ,c.allAmount  from oa_finance_payablesapply_detail c where 1=1",
     "select_push"=>"select c.id ,c.payapplyId ,sum(c.money) as money,c.cashsubject ,c.objId ,c.objCode ,c.objType,c.purchaseMoney ,c.payDesc ,c.expand1 ,c.expand2 ,c.expand3 ,c.payedMoney ,c.handInvoiceMoney ,c.orgFormType ,c.orgFormNo ,c.productId ,c.productNo ,c.productName ,c.productModel ,c.unitName ,c.number ,c.price ,c.allAmount  from oa_finance_payablesapply_detail c where 1=1",
     "select_sum"=>"select sum(c.money) as money,c.objId ,c.expand1 from oa_finance_payablesapply_detail c inner join oa_finance_payablesapply p on c.payapplyId = p.id where 1=1",
     "select_sumAll"=>"select sum(if(p.payFor = 'FKLX-03', - c.money,c.money)) as money,c.objId ,c.expand1 from oa_finance_payablesapply_detail c inner join oa_finance_payablesapply p on c.payapplyId = p.id where 1=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "payapplyId",
   		"sql" => " and c.payapplyId=# "
   ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   ),
   array(
   		"name" => "objType",
   		"sql" => " and c.objType=# "
   ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   ),
   array(
   		"name" => "pStatus",
   		"sql" => " and p.status=# "
   )
)
?>