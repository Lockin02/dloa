<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'default_list' => 'select c.id,c.focusName,c.focusId from oa_rd_personfocus c ',
	'focusWeeklog' => 'select c.id ,w.weekTitle,w.depName,w.updateTime,w.id as weekId from oa_rd_personfocus c  left join oa_rd_worklog_week w on c.focusId = w.createId where 1=1 ',
	'focus_person' => 'select c.id,c.focusName,c.focusId,max(w.updateTime) as updateTime,w.depName from oa_rd_personfocus c left join oa_rd_worklog_week w on c.focusId = w.createId '
);

$condition_arr = array (
	array(
		"name" => "isUsing",
		"sql" => " and c.isUsing = #"
	),
	array(
		"name" => "focuser",
		"sql" => " and c.createId = #"
	),
	array(
		"name" => "largeUpdate",
		"sql" => " and w.updateTime >= c.updateTime "
	)
);
?>
