<?php
//c.groupName as text
$sql_arr = array (
	"select_default" => "select " .
		"c.id,c.parentId,c.parentName,c.projectType,c.lft,c.rgt,c.projectId,c.roleName,c.roleName as text,
		c.isTemplate,c.notes,if((c.rgt-c.lft)=1,1,0) as leaf " .
		"from oa_rd_team_role c where id!=-1",
	"roles_member" => "select c.id,mr.memberId,c.parentId,c.projectType,c.lft,c.rgt,c.projectId,c.roleName from oa_rd_team_role c".
			" left join oa_rd_team_member_role mr on mr.roleId=c.id",
	"role_perm" =>"select p.permCode from oa_rd_team_role_perm p left join oa_rd_team_role  r on r.id=p.roleId left join oa_rd_team_member_role mr on".
				" mr.roleId=r.id"//获取某成员在某个项目中的权限集合
);

$condition_arr = array (
	array(
		"name" => "projectId",
		"sql" => "and c.projectId=#"
	)
	,array (
		"name" => "roleName",
		"sql" => "and c.roleName like CONCAT('%',#,'%')"
	)
	,array (
		"name" => "projectType",
		"sql" => "and c.projectType =#"
	),
	array (
		"name" => "isTemplate",
		"sql" => "and c.isTemplate =#"
	),
	array (
		"name" => "parentId",
		"sql" => "and c.parentId =# "
	),
	array (
		"name" => "children",
		"sql" => "and ($)"
	),
	array (
		"name" => "memberIds",
		"sql" => "and mr.memberId in(arr)"
	),
	array(
		"name" => "rprojectId",
		"sql" => "and r.projectId=#"
	),
	array (
		"name" => "memberId",
		"sql" => "and mr.memberId = #"
	)
);
?>

