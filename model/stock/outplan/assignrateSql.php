<?php
/**
 * @author Administrator
 * @Date 2012��11��18�� 14:40:16
 * @version 1.0
 * @description:����ȷ�Ͻ��ȱ�ע sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.relDocType ,c.relDocId ,c.rObjCode ,c.keyword ,c.remark ,c.createTime ,c.createName ,c.createId ,c.updateId ,c.updateName ,c.updateTime  from oa_assignment_rate c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "relDocType",
   		"sql" => " and c.relDocType=# "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode=# "
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
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>