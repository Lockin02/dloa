<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 16:32:54
 * @version 1.0
 * @description:工资交接单清单 sql配置文件 
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