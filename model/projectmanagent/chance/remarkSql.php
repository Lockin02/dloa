<?php
/**
 * @author Administrator
 * @Date 2013��1��30�� 11:02:43
 * @version 1.0
 * @description:�̻���ͨ����Ϣ sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.createName ,c.createId ,c.createTime ,c.content  from oa_chance_remark c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
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
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  )
)
?>