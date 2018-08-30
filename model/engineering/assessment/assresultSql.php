<?php
/**
 * @author huangzf
 * @Date 2010年12月9日 20:09:03
 * @version 1.0
 * @description:考勤指标结果记录表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.indicatorId ,c.indicatorsName ,c.score ,c.weekResultId  from oa_esm_ass_result c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "indicatorId",
   		"sql" => " and c.indicatorId=# "
   	  ),
   array(
   		"name" => "indicatorsName",
   		"sql" => " and c.indicatorsName=# "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  ),
   array(
   		"name" => "weekResultId",
   		"sql" => " and c.weekResultId=# "
   	  )
)
?>