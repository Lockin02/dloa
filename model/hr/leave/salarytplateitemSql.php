<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 15:29:33
 * @version 1.0
 * @description:�����嵥ģ���嵥 sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.salaryContent ,c.remark  from oa_hr_leave_salarytplateitem c where 1=1 "
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
   		"name" => "salaryContent",
   		"sql" => " and c.salaryContent=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>