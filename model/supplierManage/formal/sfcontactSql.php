<?php
$sql_arr = array(

	'readconInSupp' =>'select c.id,c.parentCode,c.busiCode,c.systemCode,c.parentId,c.name,c.mobile1,c.fax,c.email,c.defaultContact,c.plane,c.position,c.remarks,c.createId,c.createTime,c.createName,c.updateTime,c.updateName,c.updateId from oa_supp_cont c' .
			' where 1=1 '
);
$condition_arr = array (
	array(
		"name"=>"parentId",
		"sql"=>" and c.parentId = # "
	),array(
		"name" => "name", //ËÑË÷×Ö¶Î£¬ÆÀ¹À·½°¸Ãû³Æ
		"sql" => " and c.name like CONCAT('%',#,'%') "
	),array(
		"name" => "nameSeach", //ËÑË÷×Ö¶Î£¬ÆÀ¹À·½°¸Ãû³Æ
		"sql" => " and c.name like CONCAT('%',#,'%') "
	)
);
?>
