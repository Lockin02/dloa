<?php
/**
 * @author Show
 * @Date 2012��3��6�� ���ڶ� 14:02:58
 * @version 1.0
 * @description:������ѡ���趨�� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.userId ,c.selectedCode  from oa_system_wf_selectsetting c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "selectedCode",
   		"sql" => " and c.selectedCode=# "
   	  )
)
?>