<?php
/**
 * @author Show
 * @Date 2012年3月6日 星期二 14:02:58
 * @version 1.0
 * @description:工作流选择设定表 sql配置文件 
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