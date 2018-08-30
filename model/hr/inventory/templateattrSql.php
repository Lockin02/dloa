<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.templateId ,c.attrId ,c.attrName,c.orderIndex,c.remark from oa_hr_inventory_templateattr c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "templateId",
		"sql" => " and c.templateId=# "
	)
)
?>
