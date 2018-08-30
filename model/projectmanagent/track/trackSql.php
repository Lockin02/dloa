<?php
/**
 * @author Administrator
 * @Date 2011年3月3日 11:28:42
 * @version 1.0
 * @description:跟踪记录 sql配置文件 跟踪记录
 */

$sql_arr = array (
         "select_default"=>"select c.id ,c.cluesId ,c.cluesCode ,c.cluesName ,c.chanceId ,c.chanceName ,c.chanceCode ,c.trackId ,c.trackName ,c.trackDate ,c.trackType ,c.linkmanName ,c.trackPurpose ,c.customerFocus ,c.result ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId,c.problem,c.followPlan  from oa_sale_track c where 1=1 ",
		 "select_default2"=>"select c.trackName ,c.trackDate ,c.trackType ,c.linkmanName ,c.trackPurpose ,c.customerFocus ,c.result ,c.problem ,c.followPlan,c.chanceCode,c.chanceName from oa_sale_track c where 1=1 "

		
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "cluesId",
   		"sql" => " and c.cluesId=# "
   	  ),
   array(
   		"name" => "cluesCode",
   		"sql" => " and c.cluesCode=# "
   	  ),
   array(
   		"name" => "cluesName",
   		"sql" => " and c.cluesName=# "
   	  ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName=# "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode=# "
   	  ),
   array(
   		"name" => "trackId",
   		"sql" => " and c.trackId=# "
   	  ),
   array(
   		"name" => "trackName",
   		"sql" => " and c.trackName=# "
   	  ),
   array(
   		"name" => "trackDate",
   		"sql" => " and c.trackDate=# "
   	  ),
   array(
   		"name" => "trackType",
   		"sql" => " and c.trackType=# "
   	  ),
   array(
   		"name" => "linkmanName",
   		"sql" => " and c.linkmanName=# "
   	  ),
   array(
   		"name" => "trackPurpose",
   		"sql" => " and c.trackPurpose=# "
   	  ),
   array(
   		"name" => "customerFocus",
   		"sql" => " and c.customerFocus=# "
   	  ),
   array(
   		"name" => "result",
   		"sql" => " and c.result=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  )
)
?>