<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 11:23:19
 * @version 1.0
 * @description:���˵ȼ����ñ� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.upperLimit ,c.lowerLimit  from oa_esm_ass_level c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "upperLimit",
   		"sql" => " and c.upperLimit=# "
   	  ),
   array(
   		"name" => "lowerLimit",
   		"sql" => " and c.lowerLimit=# "
   	  )
)
?>