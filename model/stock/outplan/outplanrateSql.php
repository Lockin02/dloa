<?php
/**
 * @author Administrator
 * @Date 2012��2��20�� 14:00:37
 * @version 1.0
 * @description:�����ƻ����ȱ�ע sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.updateTime,c.updateId,c.updateName,c.id ,c.planId ,c.keyword ,c.remark,c.createName,c.createId,c.createTime  from oa_stock_outplan_rate c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "planIdArr",
   		"sql" => " and c.planId in(arr) "
   	  ),
   array(
   		"name" => "planId",
   		"sql" => " and c.planId=# "
   	  ),
   array(
   		"name" => "keyword",
   		"sql" => " and c.keyword=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   	  )
)
?>