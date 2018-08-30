<?php
/**
 * @author Show
 * @Date 2011年5月19日 星期四 19:34:41
 * @version 1.0
 * @description:期初余额表(应付应收) sql配置文件 余额类型 formType
                                    0. 应收
                                    1. 应付
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formType ,c.objectId ,c.objectName ,c.directions ,c.directionsName ,c.needPay ,c.payed ,c.balance ,c.thisYear ,c.thisMonth ,c.thisDate ,c.isUsing  from oa_finance_balance c where 1=1 ",
	'count_list' => "select sum(c.needpay) as needPay , sum(c.payed) as payed , sum(c.balance) as balance from oa_finance_balance c "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
    ),
    array(
   		"name" => "formType",
   		"sql" => " and c.formType=# "
    ),
    array(
   		"name" => "objectId",
   		"sql" => " and c.objectId=# "
    ),
    array(
   		"name" => "objectIds",
   		"sql" => " and c.objectId in(arr) "
    ),
    array(
   		"name" => "directions",
   		"sql" => " and c.directions=# "
    ),
    array(
   		"name" => "thisYear",
   		"sql" => " and c.thisYear <= # "
    ),
    array(
   		"name" => "thisMonth",
   		"sql" => " and c.thisMonth <= # "
	),
    array(
   		"name" => "thisDate",
   		"sql" => " and c.thisDate=# "
    ),
    array(
   		"name" => "isUsing",
   		"sql" => " and c.isUsing=# "
    )
)
?>