<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:39:50
 * @version 1.0
 * @description:导师考核表----考核明细 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.tutorassessId ,c.appraisal ,c.coefficient ,c.scaleA ,c.scaleB ,c.scaleC ,c.scaleD ,c.scaleE ,c.selfgraded ,c.superiorgraded ,c.staffgraded ,c.assistantgraded ,c.hrgraded  from oa_hr_tutor_tutorassess_info c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "tutorassessId",
   		"sql" => " and c.tutorassessId=# "
   	  ),
   array(
   		"name" => "appraisal",
   		"sql" => " and c.appraisal=# "
   	  ),
   array(
   		"name" => "coefficient",
   		"sql" => " and c.coefficient=# "
   	  ),
   array(
   		"name" => "scaleA",
   		"sql" => " and c.scaleA=# "
   	  ),
   array(
   		"name" => "scaleB",
   		"sql" => " and c.scaleB=# "
   	  ),
   array(
   		"name" => "scaleC",
   		"sql" => " and c.scaleC=# "
   	  ),
   array(
   		"name" => "scaleD",
   		"sql" => " and c.scaleD=# "
   	  ),
   array(
   		"name" => "scaleE",
   		"sql" => " and c.scaleE=# "
   	  ),
   array(
   		"name" => "selfgraded",
   		"sql" => " and c.selfgraded=# "
   	  ),
   array(
   		"name" => "superiorgraded",
   		"sql" => " and c.superiorgraded=# "
   	  ),
   array(
   		"name" => "staffgraded",
   		"sql" => " and c.staffgraded=# "
   	  ),
   array(
   		"name" => "assistantgraded",
   		"sql" => " and c.assistantgraded=# "
   	  ),
   array(
   		"name" => "hrgraded",
   		"sql" => " and c.hrgraded=# "
   	  )
)
?>