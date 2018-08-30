<?php
$sql_arr = array(
	"select" => "select 'dept' as icon,'dept' as type,c.Code,c.DEPT_ID as id,c.Depart_x,c.levelflag,c.PARENT_ID,
	c.DEPT_NAME,c.DEPT_NAME as name,c.Leader_id,c.Leader_name,c.TEL_NO,c.FAX_NO,c.DEPT_NO,c.DEPT_FUNC,c.Dflag,
	c.pdeptid,c.pdeptname,c.comCode,
	(select if(count(d.DEPT_ID)>1,1,0) from department d where d.Depart_x like CONCAT(c.Depart_x,'%') and d.comCode=c.comCode) as hasChildren
	from department c where 1=1 ",

	"selectForUser" => "select 'dept' as icon,'dept' as type,c.DEPT_ID as id,c.Code,c.Depart_x,c.levelflag,c.PARENT_ID, c.DEPT_NAME,c.DEPT_NAME as name,c.Leader_id,c.Leader_name,c.TEL_NO,c.FAX_NO,c.DEPT_NO,c.DEPT_FUNC,c.Dflag,c.comCode,
	(select if(count(d.DEPT_ID)>1,1,0) from department d where d.Depart_x like CONCAT(c.Depart_x,'%') and d.comCode=c.comCode) as hasChildren,
	(select if(count(u.USER_ID)>0,1,0) from user u where u.DEPT_ID =c.DEPT_ID) as hasUser
	from department c where 1=1 ",

	"select_com" => "select 'dept' as icon,'dept' as type ,c.nameCN as DEPT_NAME ,c.comCode from company c where 1=1 "

);
$condition_arr = array(
	array(
		"name" => "cid",
		"sql" => "and c.DEPT_ID =#"
	),
	array(
		"name" => "deptIds",
		"sql" => "and c.DEPT_ID in ($)"
	),
	array(
		"name" => "deptFilter",
		"sql" => "and ( c.DEPT_ID in ($) or c.PARENT_ID in($) )"
	),
	array(
		"name" => "deptName",
		"sql" => "and c.DEPT_NAME like CONCAT('%',#,'%')"
	),
	array(
		"name" => "Depart_x",
		"sql" => "and c.Depart_x like CONCAT(#,'%')"
	),
	array(
		"name" => "Dflag",
		"sql" => "and c.Dflag=#"
	),
	array(
		"name" => "DelFlag",
		"sql" => "and c.DelFlag=#"
	),
	array(
		"name" => "comCode",
		"sql" => "and c.comCode=#"
	),
	array(
		"name" => "DeptLead",
		"sql" => "and find_in_set(#,c.Leader_id) or find_in_set(#,c.MajorId) or find_in_set(#,c.ViceManager) "
	)
);