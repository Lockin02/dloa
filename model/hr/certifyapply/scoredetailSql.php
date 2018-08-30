<?php
/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:13
 * @version 1.0
 * @description:任职资格评委打分表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.assessId ,c.cdetailId ,c.scoreId ,c.moduleId ,c.moduleName ,c.detailName ,c.detailId ,c.managerName ,c.managerId ,c.weights ,c.score  from oa_hr_certifyapplyassess_scoredetail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "assessId",
   		"sql" => " and c.assessId=# "
   	  ),
   array(
   		"name" => "cdetailId",
   		"sql" => " and c.cdetailId=# "
   	  ),
   array(
   		"name" => "scoreId",
   		"sql" => " and c.scoreId=# "
   	  ),
   array(
   		"name" => "moduleId",
   		"sql" => " and c.moduleId=# "
   	  ),
   array(
   		"name" => "moduleName",
   		"sql" => " and c.moduleName=# "
   	  ),
   array(
   		"name" => "detailName",
   		"sql" => " and c.detailName=# "
   	  ),
   array(
   		"name" => "detailId",
   		"sql" => " and c.detailId=# "
   	  ),
   array(
   		"name" => "managerName",
   		"sql" => " and c.managerName=# "
   	  ),
   array(
   		"name" => "managerId",
   		"sql" => " and c.managerId=# "
   	  ),
   array(
   		"name" => "weights",
   		"sql" => " and c.weights=# "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  )
)
?>