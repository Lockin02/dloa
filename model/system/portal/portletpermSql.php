<?php
$sql_arr = array (
	"select_default" => "select c.id ,c.portletId ,p.portletName," .
	"c.roleId ,c.roleName,c.userId,c.userName," .
	"c.updateId ,c.updateName,c.createId,c.createName,c.createTime " .
	"from oa_portal_portlet_perm c left join oa_portal_portlet p on " .
	" p.id=c.portletId where 1=1 ",
	"select_perm" => "select c.portletId as id ,p.portletName," .
	"p.url,p.width,p.height,p.remark,p.typeId,p.typeName ".
	"from oa_portal_portlet_perm c inner join oa_portal_portlet p on " .
	" p.id=c.portletId where 1=1 ",
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "portletId",
		"sql" => " and c.portletId=# "
	),
	array (
		"name" => "portletName",
		"sql" => "and p.portletName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "roleId",
		"sql" => " and c.roleId=# "
	),
	array (
		"name" => "userId",
		"sql" => " and c.userId=# "
	)
);
?>