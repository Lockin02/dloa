<?php
$sql_arr = array(
	"select_default" => "select " .
				"c.id,c.assesId,c.normName,c.normCode,c.weight,c.assesCriteria,c.assesDept,c.assesDeptName,c.normTotal,c.normQualified,c.remarks " .
			"from oa_supp_asses_norm c where 1=1 ",
	"select_user" => "select " .
				"c.id,c.assesId,c.normName,c.normCode,c.weight,c.assesCriteria,c.assesDept,c.assesDeptName,c.normTotal,c.normQualified,c.remarks " .
			"from oa_supp_asses_norm c left join oa_supp_asses_normpeo o on c.id=o.normId where 1=1 "
);


$condition_arr = array(
	array(
		"name" => "assesId", //方案Id
		"sql" => " and c.assesId=# "
	),
	array(
		"name" => "asseserId", //人员Id
		"sql" => " and o.asseserId=# "
	),
	array(
		"name" => "id", //Id
		"sql" => " and c.id=# "
	)

);
?>