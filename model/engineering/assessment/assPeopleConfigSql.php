<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array(
		'assPeoCon' => 'select c.id,c.levelId,c.indexId,c.name,c.weight from oa_esm_people_config c where 1=1',
		'assConfigInfo' => "select c.id,c.levelId,c.indexId,c.name,c.weight from oa_esm_people_config c where  1=1 "
	);
$condition_arr = array(
	array(
		"name" => "levelId",
		"sql" => " and c.levelId=# "
	),
	array(
		"name" => "indexId",
		"sql" => " and c.indexId =#"
	)
);
?>
