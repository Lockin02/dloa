<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 16:32:54
 * @version 1.0
 * @description:���ʽ��ӵ��嵥 sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.salaryContent ,c.remark  from oa_hr_leave_salarydocitem c where 1=1 "
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