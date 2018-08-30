<?php
$sql_arr = array(
	"select_default" => "select ".
			" c.id,c.objCode,c.systemCode,c.busiCode,c.parentCode,c.parentId,c.typeCode,c.typeName,c.opinionCode,c.opinionVal,c.opinion ".
			" from oa_supp_asse_temp c where 1=1 ",
	"sql_examine" => "select w.task,p.ID,c.suppName,c.busiCode,c.products,c.fax,c.address,c.ExaStatus,c.createTime from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_supp_asse_temp c " .
			" where w.Pid =c.id and w.examines <> 'no' ",
	"select_asse" => "select c.typeCode,c.typeName,c.opinion from oa_supp_asse_temp c where 1=1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "parentId",
		"sql" => "and c.parentId=#"
	),
	array(
		"name" => "parentCode",
		"sql" => "and c.parentCode=#"
	),
	array(
		"name" => "typeName",
		"sql" => "and c.typeName=#"
	),
	array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
	array(
		"name" => "seaSuppName",//搜索条件-合同名称
		"sql"=>" and c.suppName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "typeCode",	//供应商评价
		"sql" => "and c.typeCode = #"
	),


);
?>
