<?php
/**
 * @author show
 * @Date 2013年12月27日 11:18:01
 * @version 1.0
 * @description:服务经理 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.provinceId ,c.province ,c.managerId ,c.managerName ,c.formBelong ,
			c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.remark,c.productLine,c.productLineName
		from oa_esm_office_managerinfo c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "noid",
		"sql" => " and c.id<># "
	),
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "provinceId",
		"sql" => " and c.provinceId=# "
	),
	array (
		"name" => "provinceIdArr",
		"sql" => " and c.provinceId in(arr) "
	),
	array (
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array (
		"name" => "provinceSch",
		"sql" => " and c.province like concat('%',#,'%') "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "findManagerId",
		"sql"=>" and  ( find_in_set( # , c.managerId ) > 0 ) "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerNameSch",
		"sql" => " and c.managerName like concat('%',#,'%') "
	),
	array (
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array (
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array (
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array (
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array (
		"name" => "businessBelongNameSch",
		"sql" => " and c.businessBelongName like concat('%',#,'%') "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "productLine",
		"sql" => " and c.productLine=# "
	),
	array (
		"name" => "productLineNameSch",
		"sql" => " and c.productLineName like concat('%',#,'%') "
	) 
);