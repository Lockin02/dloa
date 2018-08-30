<?php
/**
 * @author Administrator
 * @Date 2011年7月22日 9:41:21
 * @version 1.0
 * @description:oa_system_region sql配置文件 大区---负责人表
 */
$sql_arr = array(
	"select_default" => "select c.tomailId,c.tomailName,c.expand,c.id ,c.isStart,c.areaName ,c.areaCode ,c.areaPrincipal ,c.areaPrincipalId ,c.province,c.remark,c.areaSalesman,c.areaSalesmanId,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.provinceManager,c.departmentLeader,c.departmentDirector,c.module,c.moduleName from oa_system_region c where 1=1 ",
	"select_principal" => "select c.areaPrincipal ,c.areaPrincipalId as id from oa_system_region c"
);

$condition_arr = array(
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=#"
	),
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "areaName",
		"sql" => " and c.areaName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "isStart",
		"sql" => " and c.isStart=# "
	),
	array(
		"name" => "areaCode",
		"sql" => " and c.areaCode=# "
	),
	array(
		"name" => "areaPrincipal",
		"sql" => " and c.areaPrincipal like CONCAT('%',#,'%')"
	),
	array(
		"name" => "areaPrincipalId",
		"sql" => " and c.areaPrincipalId=# "
	),
	array(
		"name" => "areaSalesmanId",
		"sql" => " and c.areaSalesmanId like CONCAT('%',#,'%')"
	),
	array(
		"name" => "areaSales",
		"sql" => " and (c.areaPrincipalId=# or  ( find_in_set( # , c.areaSalesmanId ) > 0 ))"
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "province",
		"sql" => " and ( find_in_set( # , c.province ) > 0 )  "
	),
	array(
		"name" => "provinceManager",
		"sql" => " and c.provinceManager=# "
	),
	array(
		"name" => "departmentLeader",
		"sql" => " and c.departmentLeader=# "
	),
	array(
		"name" => "departmentDirector",
		"sql" => " and c.departmentDirector=# "
	),
	array( //费用预算模块获取区域时会用到该过滤
		"name" => "isBudgetArea",
		"sql" => " and c.areaName NOT LIKE '%结算%' "
	),
	array(
		"name" => "module",
		"sql" => " and c.module = # "
	),
	array(
		"name" => "moduleName",
		"sql" => " and c.moduleName = # "
	)
);