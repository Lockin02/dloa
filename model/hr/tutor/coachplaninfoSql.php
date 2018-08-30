<?php
/**
 * @author Administrator
 * @Date 2012-08-23 16:55:05
 * @version 1.0
 * @description:员工辅导计划详细表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.coachplanId ,c.containMonth ,c.fosterGoal ,c.fosterMeasure ,c.reachinfoStu ,c.remarkStu ,c.reachinfoTut ,c.remarkTut  from oa_hr_tutor_coachplan_info c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "coachplanId",
   		"sql" => " and c.coachplanId=# "
   	  ),
   array(
   		"name" => "containMonth",
   		"sql" => " and c.containMonth=# "
   	  ),
   array(
   		"name" => "fosterGoal",
   		"sql" => " and c.fosterGoal=# "
   	  ),
   array(
   		"name" => "fosterMeasure",
   		"sql" => " and c.fosterMeasure=# "
   	  ),
   array(
   		"name" => "reachinfoStu",
   		"sql" => " and c.reachinfoStu=# "
   	  ),
   array(
   		"name" => "remarkStu",
   		"sql" => " and c.remarkStu=# "
   	  ),
   array(
   		"name" => "reachinfoTut",
   		"sql" => " and c.reachinfoTut=# "
   	  ),
   array(
   		"name" => "remarkTut",
   		"sql" => " and c.remarkTut=# "
   	  )
)
?>