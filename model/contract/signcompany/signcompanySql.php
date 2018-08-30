<?php

/**
 * @author Show
 * @Date 2012年2月21日 星期二 15:37:22
 * @version 1.0
 * @description:签约公司 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.signCompanyName ,c.proName ,c.proCode ,c.linkman ,c.phone ,c.address ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_sale_signcompany c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "signCompanyName",
		"sql" => " and c.signCompanyName = #"
	),
	array (
		"name" => "signCompanyNameSearch",
		"sql" => " and c.signCompanyName like concat('%',#,'%')"
	),
	array (
		"name" => "proName",
		"sql" => " and c.proName=# "
	),
	array (
		"name" => "proCode",
		"sql" => " and c.proCode=# "
	),
	array (
		"name" => "linkman",
		"sql" => " and c.linkman=# "
	),
	array (
		"name" => "linkmanSearch",
		"sql" => " and c.linkman like concat('%',#,'%')"
	),
	array (
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>