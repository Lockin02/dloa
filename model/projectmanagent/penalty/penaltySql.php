<?php
$sql_arr = array (
         'select_default'=>'select c.id,c.parentId,c.name,c.code,c.cacheDate,c.intervalDate,c.money,c.limitMoney,c.limitType,if(parentId=-1,1,0) as isParent,c.costType,c.multiple from oa_system_penalty c where 1=1'
);

$condition_arr = array (
	array(
	        'name' => 'id',
	        'sql' => 'and c.id =#'
	        ),
	        array(
	        'name' => 'parentId',
	        'sql' => 'and c.parentId =#'
	        ),
	        array(
	        'name' => 'name',
	        'sql' => "and c.name like CONCAT('%',#,'%')"
	        ),
	        array(
	        'name' => 'code',
	        'sql' => "and c.code like CONCAT('%',#,'%')"
	        ),
	        array(
	        'name' => 'cacheDate',
	        'sql' => 'and c.cacheDate =#'
	        ),
	        array(
	        'name' => 'intervalDate',
	        'sql' => 'and c.intervalDate =#'
	        ),
	        array(
	        'name' => 'money',
	        'sql' => 'and c.money =#'
	        ),
	        array(
	        'name' => 'limitMoney',
	        'sql' => 'and c.limitMoney =#'
	        ),
	        array(
	        'name'=>'nParentId',
	        'sql'=>' and c.parentId != #'
	        ),
	        array(
	        'name'=>'codeEq',
	        'sql'=>' and c.code = #'
	        )
 );
