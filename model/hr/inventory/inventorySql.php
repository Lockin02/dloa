<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.templateId ,c.deptName ,c.deptId,c.inventoryName" .
         		",c.remark  from oa_hr_inventory_inventory c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "templateId",
		"sql" => " and c.templateId=# "
	)
)
?>
