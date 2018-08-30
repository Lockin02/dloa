<?php
/**
 *
 */
$sql_arr = array (
	"select_default" => "select *
		from oa_sale_other_mailLog c where c.isTemp = 0 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	)
);