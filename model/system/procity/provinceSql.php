<?php

/*
 * Created on 2010-7-17
 *	省份信息SQL
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"select_province" => "select c.id,c.sequence,c.countryId,y.countryName,c.provinceName,c.provinceCode,
			c.esmManager,c.esmManagerId,c.remark
		from oa_system_province_info c left join oa_system_country_info y on y.id=c.countryId
		where c.id<>-1 and 1=1 ",
	"select_mylist2" => "select c.id,c.provinceName as name,c.countryId,y.countryName,c.provinceCode as code,c.esmManager,c.esmManagerId,
			if((c.leaf)=1,0,1) as isParent,c.parentId
		from
			oa_system_province_info c
	   			left join
			oa_system_country_info y on y.id=c.countryId
	    where c.id<>-1 ",
	"select_provinceandmanager" => "select
			c.id,c.provinceName as name,
			c.provinceName,c.provinceCode as code,if((c.leaf)=1,0,1) as isParent,c.parentId
		from
			oa_system_province_info c
	  where c.id<>-1",
	"select_forSelectName" => "select c.provinceName as text ,c.provinceCode as value,c.id,c.esmManager,c.esmManagerId from oa_system_province_info c ",
	"select_editgrid" => "select c.provinceName as name,c.id as value from oa_system_province_info c "
);
$condition_arr = array (
	array (
		"name" => "provinceName",
		"sql" => "and c.provinceName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "parentId",
		"sql" => "and c.parentId =#"
	),
	array (
		"name" => "parentName",
		"sql" => "and c.parentName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "provinceCode",
		"sql" => "and c.provinceCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "countryId",
		"sql" => "and c.countryId =#"
	),
	array (
		"name" => "provinceNameEq",
		"sql" => "and c.provinceName=#"
	),
	array (
		"name" => "provinceCodeEq",
		"sql" => "and c.provinceCode=#"
	),
    array (
        "name" => "provinceCodeFlit",
        "sql" => "and c.provinceCode not in(arr) "
    ),
	array (
		"name" => "esmManagerIdArr",
		"sql" => "and c.esmManagerId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "esmManagerIdFind",
		"sql" => "and (find_in_set(#,c.esmManagerId) > 0 )"
	)
)
?>