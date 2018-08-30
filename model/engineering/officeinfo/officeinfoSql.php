<?php
/**
 * Created on 2010-11-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"select_officeinfo" => "select c.id,c.officeName,c.managerCode,c.managerName ,c.rangeName,c.rangeId,
			c.remark,c.mainManager,c.mainManagerId,c.feeDeptName,c.feeDeptId,c.formBelong,c.formBelongName,
			c.businessBelong,c.businessBelongName,c.head,c.productLine,c.headId,c.productLineName,c.assistant,
			c.assistantId,c.module,c.moduleName,c.state
		from oa_esm_office_baseinfo c where 1"
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id =#"
		),
	array (
		"name" => "officeName",
		"sql" => "and c.officeName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "managerName",
		"sql" => "and c.managerName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "mainManagerSearch",
		"sql" => "and c.mainManager like CONCAT('%',#,'%')"
	),
	array (
		"name" => "officeNameEq",
		"sql" => "and c.officeName=#"
	),
	array (
		"name" => "managerId",
		"sql" => "and c.managerId = #"
	),
	array (
		"name" => "mainManagerId",
		"sql" => "and c.mainManagerId = #"
	),
	array (
		"name" => "rangeName",
		"sql" => "and c.rangeName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "mainManagerFind",
		"sql" => " and (find_in_set(#,c.mainManagerId) > 0 )"
	),
	array(
		"name" => "rangeCodeFind",
		"sql" => " and (find_in_set(#,c.rangeCode) > 0)"
	),
	array(
		"name" => "rangeIdFind",
		"sql" => " and (find_in_set(#,c.rangeId) > 0)"
	),
	array(
		"name" => "businessBelong",
		"sql" => "and c.businessBelong = #"
	),
	array(
		"name" => "head",
		"sql" => "and c.head = #"
	),
	array(
		"name" => "productLine",
		"sql" => "and c.productLine = #"
	),
	array(
		"name" => "productLineArr",
		"sql" => "and c.productLine in(arr)"
	),
	array(
		"name" => "findMainAndHead",
		"sql" => "and ((find_in_set(#,c.mainManagerId) > 0 ) or (find_in_set(#,c.headId) > 0 )) "
	),
	array (
		"name" => "productLineNameSch",
		"sql" => " and c.productLineName like concat('%',#,'%') "
	),
	array(
		"name" => "module",
		"sql" => " and c.module = # "
	),
	array(
		"name" => "moduleName",
		"sql" => " and c.moduleName = # "
	),
	array(
		"name" => "state",
		"sql" => " and c.state = # "
	)
);