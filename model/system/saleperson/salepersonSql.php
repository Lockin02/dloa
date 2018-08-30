<?php

/**
 * @author Administrator
 * @Date 2012-12-24 14:48:23
 * @version 1.0
 * @description:销售负责人管理 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.salesAreaName,c.salesAreaId,c.salesManNames,c.salesManIds,c.areaName,c.areaNameId,c.isDirector,c.id ,c.isUse,c.personName ,c.personId ,c.deptName ,c.deptId ,c.country ,c.countryId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.customerType ,
		c.customerTypeName ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.leaderId,c.leaderName,c.exeDeptCode,c.exeDeptName from oa_system_saleperson c where 1=1 ",
	"select_merge" => "select
		c.salesAreaName,c.salesAreaId,c.salesManNames,c.salesManIds,c.id ,GROUP_CONCAT(CAST(c.id AS char)) as ids,c.isDirector,c.personName,c.personId,c.deptName,GROUP_CONCAT(c.country) as country,GROUP_CONCAT(c.province) as province,GROUP_CONCAT(c.city) as city,
		GROUP_CONCAT(c.customerTypeName ) as customerTypeName,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.exeDeptCode,c.exeDeptName
		from oa_system_saleperson c where 1=1",
	"select_person" => "select c.salesAreaName,c.salesAreaId,c.salesManNames,c.salesManIds,c.areaName,c.areaNameId,c.deptName,c.deptId,c.exeDeptCode,c.exeDeptName from oa_system_saleperson c where 1"
);

$condition_arr = array (
	array (
		"name" => "isDirector",
		"sql" => " and c.isDirector=#"
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and id in(arr)"
	),
	array (
		"name" => "personName",
		"sql" => " and c.personName=# "
	),
	array (
		"name" => "personNameSearch",
		"sql" => " and c.personName like concat('%',#,'%') "
	),
	array (
		"name" => "areaName",
		"sql" => " and c.areaName=# "
	),
        array (
		"name" => "saleSearch",
		"sql" => " and ( c.areaNameId=# or c.personId=# )"
	),
	array (
		"name" => "areaNameSearch",
		"sql" => " and c.areaName like concat('%',#,'%') "
	),
	array (
		"name" => "personId",
		"sql" => " and c.personId=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "country",
		"sql" => " and c.country=# "
	),
	array (
		"name" => "countrySearch",
		"sql" => " and c.country like concat('%',#,'%') "
	),
	array (
		"name" => "countryId",
		"sql" => " and c.countryId=# "
	),
	array (
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array (
		"name" => "provinceSearch",
		"sql" => " and c.province like concat('%',#,'%') "
	),
	array (
		"name" => "provinceId",
		"sql" => " and c.provinceId=# "
	),
	array (
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array (
		"name" => "citySearch",
		"sql" => " and c.city like concat('%',#,'%') "
	),
	array (
		"name" => "citys",
		"sql" => " and c.city in(arr) "
	),
	array (
		"name" => "cityId",
		"sql" => " and c.cityId=# "
	),
	array (
		"name" => "customerType",
		"sql" => " and c.customerType=# "
	),
	array (
		"name" => "customerTypeName",
		"sql" => " and c.customerTypeName=# "
	),
	array (
		"name" => "customerTypeName",
		"sql" => " and c.customerTypeName in(arr) "
	),
    array (
		"name" => "exeDeptNameSearch",
		"sql" => " and c.exeDeptName like concat('%',#,'%') "
	),
    array (
        "name" => "businessBelongNameSearch",
        "sql" => " and c.businessBelongName like concat('%',#,'%') "
    ),
	array (
		"name" => "customerTypeSearch",
		"sql" => " and c.customerTypeName like concat('%',#,'%') "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "isUse",
		"sql" => " and c.isUse=# "
	)
)
?>