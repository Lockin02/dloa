<?php
/**
 * @author huangzf
 * @Date 2010��12��7�� 19:09:47
 * @version 1.0
 * @description:Ա���ܱ����˽��������Ϣ sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.weekLogId ,c.assLevel ,c.score ,c.reviews  from oa_esm_ass_week c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "weekLogId",
   		"sql" => " and c.weekLogId=# "
   	  ),
   array(
   		"name" => "assLevel",
   		"sql" => " and c.assLevel=# "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  ),
   array(
   		"name" => "reviews",
   		"sql" => " and c.reviews=# "
   	  )
)
?>