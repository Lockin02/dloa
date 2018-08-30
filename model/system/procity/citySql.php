<?php

/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  $sql_arr = array (
	"select_procity_typeparent" => "select c.id,c.provinceId,t.provinceName,c.provinceCode,c.cityName,c.sequence,c.cityCode from oa_system_city_info c left join oa_system_province_info t on(t.id=c.provinceId)  where 1=1 ",
	"select_city" => "select c.id,c.provinceId,t.provinceName,c.provinceCode,c.cityName,c.sequence,c.cityCode
		from
			oa_system_city_info c
	   			left join
			oa_system_province_info t on t.id=c.provinceId
	    where c.id<>-1 and 1=1 ",
	"select_editgrid" => "select c.cityName as name,c.id as value from oa_system_city_info c "
	);

$condition_arr = array (
	array (
		"name" => "cityName",
		"sql" => "and c.cityName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sequence",
		"sql" => "and c.sequence like CONCAT('%',#,'%')"
	),
	array (
		"name" => "cityCode",
		"sql" => "and c.cityCode  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "provinceId",
		"sql" => "and c.provinceId =# "
	),
	array (
		"name" => "provinceIds",
		"sql" => "and c.provinceId in(#) "
	),
	array (
		"name" => "parentId",
		"sql" => "and t.id=# or t.parentId =# )"
	),
	array (
		"name" => "stockId",
		"sql" => "and c.stockId=# "
	),
	array (
		"name" => "cityNameEq",
		"sql" => "and c.cityName=# "
	),
	array (
		"name" => "cityCodeEq",
		"sql" => "and c.cityCode=#"
	),
	array (
		"name" => "tProvinceName",
		"sql" => "and t.provinceName =#"
	)
)
?>
