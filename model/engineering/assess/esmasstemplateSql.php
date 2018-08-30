<?php

/**
 * @author Show
 * @Date 2012年11月27日 星期二 19:45:15
 * @version 1.0
 * @description:考核模板表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.name ,c.remark ,c.score ,c.indexIds ,c.indexNames ,c.needIndexIds ,c.needIndexNames ,
			c.baseScore,c.needScore  from oa_esm_ass_template c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "name",
		"sql" => " and c.name=# "
	),
	array (
		"name" => "nameSearch",
		"sql" => " and c.name like concat('%',#,'%') "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "remarkSearch",
		"sql" => " and c.remark like concat('%',#,'%') "
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	),
	array (
		"name" => "indexIds",
		"sql" => " and c.indexIds=# "
	),
	array (
		"name" => "indexNames",
		"sql" => " and c.indexNames=# "
	),
	array (
		"name" => "needIndexIds",
		"sql" => " and c.needIndexIds=# "
	),
	array (
		"name" => "needIndexNames",
		"sql" => " and c.needIndexNames=# "
	)
)
?>