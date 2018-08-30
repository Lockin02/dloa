<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'base_list' => 'select f.id ,w.weekTitle,w.depName,w.updateTime,w.id as weekId from oa_rd_worklog_focus f  left join oa_rd_worklog_week w on f.befocusId = w.id where 1=1 '
);

$condition_arr = array (
	array(
		"name" => "isUsing",
		"sql" => " and f.isUsing = #"
	),
	array(
		"name" => "focuser",
		"sql" => " and f.createId = #"
	)
);
?>
