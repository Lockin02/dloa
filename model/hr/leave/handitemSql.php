<?php
/**
 * @author Administrator
 * @Date 2013��4��24�� ������ 16:12:32
 * @version 1.0
 * @description:��ְ���������嵥 sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.recipientName ,c.recipientId ,c.handContent  from oa_hr_leave_handitem c where 1=1 "
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
   		"name" => "recipientName",
   		"sql" => " and c.recipientName=# "
   	  ),
   array(
   		"name" => "recipientId",
   		"sql" => " and c.recipientId=# "
   	  ),
   array(
   		"name" => "handContent",
   		"sql" => " and c.handContent=# "
   	  )
)
?>