<?php
/*
 * Created on 2010-12-4
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

   $sql_arr = array (
	"assConfigInfo" => "select c.id,c.name,c.score,c.parentId from oa_esm_ass_config c where  1=1 "
	);
	$condition_arr = array(
	array(
		"name" => "levelId",
		"sql" => " and c.levelId=# "
	),array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),array(
		"name" => "name",
		"sql" => " and c.name  like CONCAT('%',#,'%') "
	)
)
?>
