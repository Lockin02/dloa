<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:22
 * @version 1.0
 * @description:��Ŀָ��ѡ�� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.detailId ,c.optionName ,c.score  from oa_esm_project_assoptions c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "detailId",
		"sql" => " and c.detailId=# "
	),
	array (
		"name" => "optionName",
		"sql" => " and c.optionName=# "
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	)
)
?>