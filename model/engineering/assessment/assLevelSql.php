<?php
/**
 * @author huangf
 * @Date 2010��12��1�� 17:16:52
 * @version 1.0
 * @description:���˵ȼ����ñ� oa_esm_ass_leve sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.score  from oa_esm_ass_leve c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
//   array(
//   		"name" => "name",
//   		"sql" => " and c.name=# "
//   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  ),
   	  array(
   	  	"name" => "levelName",
   	  	"sql" => " and c.levelName = #"
   	  )
)
?>