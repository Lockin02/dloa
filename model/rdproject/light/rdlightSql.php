<?php

$sql_arr = array(
	"select_lightInfo" => "select c.id, c.lightcol, c.Max ,c.Min from oa_rd_light c where 1=1"
	);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "lightcol",
		"sql" => "and c.lightcol like CONCAT('%',#,'%')"
	),
	array(
		"name" => "Max",
		"sql" => "and c.Max=#"
	),
	array(
		"name" => "Min",
		"sql" => "and c.Min=#"
	)
);
?>
