<?php
/**
 * @author Administrator
 * @Date 2011��9��2�� 11:34:47
 * @version 1.0
 * @description:�����ƻ��ʼĽ����� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mailmanId ,c.mailmanName  from oa_stock_outplan_mail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mailmanId",
   		"sql" => " and c.mailmanId=# "
   	  ),
   array(
   		"name" => "mailmanName",
   		"sql" => " and c.mailmanName=# "
   	  )
)
?>