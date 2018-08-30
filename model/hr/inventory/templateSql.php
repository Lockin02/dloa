<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.templateName ,c.isUse ,c.remark from oa_hr_inventory_inventory c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "templateId",
		"sql" => " and c.templateId=# "
	)
)
?>
