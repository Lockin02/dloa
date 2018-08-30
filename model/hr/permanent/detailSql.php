<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:45
 * @version 1.0
 * @description:员工转正考核明细表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.standardId ,c.standard ,c.standardType ,c.selfScore ,c.otherScore ,leaderScore,standardProportion,c.standardContent ,c.standardPoint ,c.comment ,c.status,c.standarScore  from oa_hr_permanent_detail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "standardId",
   		"sql" => " and c.standardId=# "
   	  ),
   array(
   		"name" => "standardType",
   		"sql" => " and c.standardType=# "
   	  ),
   array(
   		"name" => "selfScore",
   		"sql" => " and c.selfScore=# "
   	  ),
   array(
   		"name" => "otherScore",
   		"sql" => " and c.otherScore=# "
   	  ),
   array(
   		"name" => "standardContent",
   		"sql" => " and c.standardContent=# "
   	  ),
   array(
   		"name" => "standardPoint",
   		"sql" => " and c.standardPoint=# "
   	  ),
   array(
   		"name" => "comment",
   		"sql" => " and c.comment=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "standard",
   		"sql" => " and c.standard=# "
   	  ),
   array(
   		"name" => "standarScore",
   		"sql" => " and c.standarScore=# "
   	  )
)
?>