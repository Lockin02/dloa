<?php
$sql_arr = array(
	"select_default" => "select ".
			"c.id,c.objCode,c.systemCode,c.busiCode,c.parentCode,c.parentId,c.name,c.mobile1,c.mobile2,".
			"c.remarks,c.fax,c.plane,c.email,c.defaultContact,c.updateTime,c.updateName,c.updateId,c.createTime,c.createName,c.createid ".
			"from oa_supp_cont_temp c where 1=1",
);

$condition_arr = array (
	array (
		"name" => "name",
		"sql" => "and c.name like  CONCAT('%',#,'%') "
	),
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "parentId",
		"sql" => "and c.parentId=#"
	),
	array(
		"name" => "updateName",
		"sql" => "and c.updateName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "defaultContact",
		"sql" => "and c.defaultContact like CONCAT('%',#,'%')"
	),
	array(
		"name" => "createName",
		"sql" => "and c.createName like CONCAT('%',#,'%')"
	)
);
?>
