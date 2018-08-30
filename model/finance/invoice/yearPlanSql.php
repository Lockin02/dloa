<?php
/**
 * @author Show
 * @Date 2011年3月4日 星期五 10:07:57
 * @version 1.0
 * @description:财务开票额度记录表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.year ,c.salesOne ,c.salesTwo ,c.salesThree ,c.salesFour ,c.salesAll ,c.serviceOne ,c.serviceTwo ,c.serviceThree ,c.serviceFour ,c.serviceAll from oa_finance_invoice_yearPlan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "year",
   		"sql" => " and c.year=# "
   	  )
)
?>