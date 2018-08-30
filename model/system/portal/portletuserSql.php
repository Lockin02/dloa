<?php
$sql_arr = array (
	"select_default" => "select c.id,c.portletId ,c.portletName ,c.userId ,c.userName,c.portletOrder," .
	"c.updateId ,c.updateName,c.createId,c.createName,c.createTime," .
	"p.portletName,p.url,p.height,p.width " .
	"from oa_portal_portlet_user c left join oa_portal_portlet p" .
	" on c.portletId=p.id where 1=1 ",
	"select_perm"=>"select c.id,c.portletId ,c.portletName ,c.userId ,c.userName,c.portletOrder," .
	"c.updateId ,c.updateName,c.createId,c.createName,c.createTime," .
	"p.portletName,p.url,p.height,p.width " .
	"from oa_portal_portlet_perm m inner join oa_portal_portlet_user c on m.portletId=c.portletId" .
	" inner join oa_portal_portlet p" .
	" on m.portletId=p.id where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.portletId in($) "
	),
	array (
		"name" => "portletId",
		"sql" => " and c.portletId=# "
	),
	array (
		"name" => "userId",
		"sql" => " and c.userId=# "
	),
	array (
		"name" => "muserId",
		"sql" => " and m.userId=# "
	),
	array (
		"name" => "portletName",
		"sql" => "and c.portletName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "userName",
		"sql" => "and c.userName like CONCAT('%',#,'%')"
	)
);
?>