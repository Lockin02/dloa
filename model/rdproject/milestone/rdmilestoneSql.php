<?php
$sql_arr = array (
	//Ĭ��sql���
	"select_default" => "select * from oa_rd_milestone_plan c where 1=1"
	,"select_readCenter" => "select * from oa_rd_milestone_plan c where 1=1"
);

$condition_arr = array (
	//ͨ��Id��ѯ
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	)

	,array(
		"name" => "pjId",
		"sql" => "and c.projectId=#"
	)
);
?>

