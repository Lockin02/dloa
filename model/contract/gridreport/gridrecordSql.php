<?php
/**
 * @author Michael
 * @Date 2014��11��28�� 17:30:26
 * @version 1.0
 * @description:���ѡ��¼�� sql�����ļ�
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userId ,c.recordCode ,c.colName ,c.colValue  from oa_system_gridrecord c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userId",
		"sql" => " and c.userId=# "
	),
	array(
		"name" => "recordCode",
		"sql" => " and c.recordCode=# "
	),
	array(
		"name" => "colName",
		"sql" => " and c.colName=# "
	),
	array(
		"name" => "colValue",
		"sql" => " and c.colValue=# "
	)
)
?>