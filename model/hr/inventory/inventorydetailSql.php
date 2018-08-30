<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.inventoryId ,c.personId ,c.userNo,c.userAccount" .
         		",c.userName,c.sex,c.companyId,c.companyName,c.deptName,c.deptId,c.position" .
         		",c.entryDate,c.inventoryDate  from oa_hr_inventory_inventorydetail c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "inventoryId",
		"sql" => " and c.inventoryId=# "
	)
)
?>
