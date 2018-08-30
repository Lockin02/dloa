<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.detailId ,c.attrId ,c.attrName,c.attrVal" .
         		"  from oa_hr_inventory_inventory c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "detailId",
		"sql" => " and c.detailId=# "
	)
)
?>
