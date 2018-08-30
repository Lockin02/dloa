<?php
/**
 *
 * 质检物料序列号台账 sql配置文件
 * @author chenrf
 *
 */
$sql_arr = array (
	"select_default" => "select * from oa_produce_quality_serialno c where 1=1 ",
	"select_count" => "select group_concat(c.sequence separator '\n') as sequences,count(*) as num from oa_produce_quality_serialno c where 1=1 ",

);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		'name' => 'productId',
		'sql' => ' and c.productId = #'
	),
	array (
		'name' => 'relDocId',
		'sql' => ' and c.relDocId= # '
	),
	array (
		'name' => 'relDocIds',
		'sql' => ' and c.relDocId in(arr) '
	),
	array (
		'name' => 'relDocType',
		'sql' => ' and c.relDocType= # '
	),
	array (
		'name' => 'sequence',
		'sql' => ' and c.sequence= # '
	)
);