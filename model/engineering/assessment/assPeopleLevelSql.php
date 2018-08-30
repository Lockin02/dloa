<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
    $sql_arr = array (
	"asspeopleLevelInfo" => "select c.id ,c.levelName,c.createName,c.createId, c.createTime, c.auditName, c.auditId, c.ratio from oa_esm_people_level c  where  1=1 "
	);
$condition_arr = array(
	array(
		"name" => "levelName",
		"sql" => " and c.levelName like CONCAT('%',#,'%') "
	),array(
		"name" => "levelName",
		"sql" => " and c.levelName =#"
	),array(
		"name" => "createName", //ËÑË÷×Ö¶Î£¬ÆÀ¹À·½°¸Ãû³Æ
		"sql" => " and c.createName like CONCAT('%',#,'%') "
	)
);
?>
