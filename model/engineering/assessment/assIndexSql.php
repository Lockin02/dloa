<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  $sql_arr = array (

	"select_assIndextree" => "select c.id,c.name,c.sortNo,c.detail from oa_esm_ass_index c where  1=1 "

	);
$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "name",
		"sql" => " and c.name like CONCAT('%',#,'%') "
	)
)
?>
