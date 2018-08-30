<?php
//c.groupName as text
$sql_arr = array (
	"select_default" => "select " .
		"c.id,c.parentId,c.parentName,c.lft,c.rgt,c.groupName,c.groupName as name,c.groupName as text,c.simpleName,c.groupCode," .
		"c.managerId,c.managerName,c.assistantId,c.assistantName,c.depId,c.depName,c.description," .
		"c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,if((c.rgt-c.lft)=1,1,0) as leaf " .
	"from oa_rd_group c where 1=1"

	,"select_readAll" => "select " .
		"c.id,c.parentId,c.turnout,c.needWorking,c.parentName,c.lft,c.rgt,c.groupName,c.groupName as name,c.groupName as text,c.simpleName,c.groupCode," .
		"c.managerId,c.managerName,c.assistantId,c.assistantName,c.depId,c.depName,c.description," .
		"c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,if((c.rgt-c.lft)=1,1,0) as leaf " .
	"from oa_rd_group c where id!=-1"

	,"select_page" => "select " .
		"c.id,c.parentId,c.parentName,c.lft,c.rgt,c.groupName,c.groupName as name,c.groupName as text,c.simpleName,c.groupCode," .
		"c.managerId,c.managerName,c.assistantId,c.assistantName,c.depId,c.depName," .
		"c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,if((c.rgt-c.lft)=1,1,0) as leaf " .
	"from oa_rd_group c where 1=1"
	,"select_parent" => "select c.id,c.groupName,c.groupCode from oa_rd_group c where id!=-1"
);

$condition_arr = array (
	array(
		"name" => "rgid",
		"sql" => "and c.id=#"
	)
	,array (
		"name" => "groupName",
		"sql" => "and c.groupName like CONCAT('%',#,'%')"
	)
	,array (
		"name" => "ajaxGroupName",//为了ajax判断groupName
		"sql" => "and c.groupName =# "
	)
	,array (
		"name" => "parentId", //父节点Id
		"sql" => "and c.parentId = # "
	)
	,array (
		"name" => "pjTree", //父节点Id
		"sql" => " and $ "
	)




	,array (
		"name" => "groupSn",
		"sql" => "and c.groupSn like CONCAT('%',#,'%')"
	),
	array (
		"name" => "nqParentId",
		"sql" => "and c.parentId != #"
	),
	array (
		"name" => "inPIds",
		"sql" => "and c.parentId in(arr)"
	),
	array (
		"name" => "parentCode",
		"sql" => "and c.parentCode = #"
	),
	array (
		"name" => "parentCodes",
		"sql" => "and c.parentCode in(arr)"
	),
	array (
		"name" => "ecode",
		"sql" => "and c.dataCode = #"
	),
	array (
		"name" => "children",
		"sql" => "and ($)"
	)
);
?>

