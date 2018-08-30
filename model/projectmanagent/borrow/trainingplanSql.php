<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 15:19:15
 * @version 1.0
 * @description:借用申请培训计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.borrowId ,c.borrowCode ,c.beginDT ,c.endDT ,c.traNum ,c.adress ,c.content ,c.trainer ,c.isOver ,c.endDT  from oa_borrow_trainingplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "borrowId",
   		"sql" => " and c.borrowId=# "
   	  ),
   array(
   		"name" => "borrowCode",
   		"sql" => " and c.borrowCode=# "
   	  ),
   array(
   		"name" => "beginDT",
   		"sql" => " and c.beginDT=# "
   	  ),
   array(
   		"name" => "endDT",
   		"sql" => " and c.endDT=# "
   	  ),
   array(
   		"name" => "traNum",
   		"sql" => " and c.traNum=# "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  ),
   array(
   		"name" => "trainer",
   		"sql" => " and c.trainer=# "
   	  ),
   array(
   		"name" => "isOver",
   		"sql" => " and c.isOver=# "
   	  ),
   array(
   		"name" => "overDT",
   		"sql" => " and c.overDT=# "
   	  )
)
?>