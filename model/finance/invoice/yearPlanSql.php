<?php
/**
 * @author Show
 * @Date 2011��3��4�� ������ 10:07:57
 * @version 1.0
 * @description:����Ʊ��ȼ�¼�� sql�����ļ�
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