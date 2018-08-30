<?php

/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:43
 * @version 1.0
 * @description:临聘人员库 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.personName ,c.idCardNo ,c.address ,c.country ,c.countryId ,c.province ,c.provinceId ,
			c.city ,c.cityId ,c.phone ,c.specialty ,c.ability ,c.allDays ,c.initDays ,c.sysDays ,c.createId ,c.createName ,
			c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.allMoney
		from oa_esm_tempperson c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
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
		"name" => "idCardNo",
		"sql" => " and c.idCardNo=# "
	),
	array (
		"name" => "idCardNoSearch",
		"sql" => " and c.idCardNo like concat('%',#,'%') "
	),
	array (
		"name" => "idCardNoEq",
		"sql" => " and c.idCardNo=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "country",
		"sql" => " and c.country=# "
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
		"name" => "provinceId",
		"sql" => " and c.provinceId=# "
	),
	array (
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array (
		"name" => "cityId",
		"sql" => " and c.cityId=# "
	),
	array (
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array (
		"name" => "phoneSearch",
		"sql" => " and c.phone like concat('%',#,'%') "
	),
	array (
		"name" => "specialty",
		"sql" => " and c.specialty=# "
	),
	array (
		"name" => "ability",
		"sql" => " and c.ability=# "
	),
	array (
		"name" => "allDays",
		"sql" => " and c.allDays=# "
	),
	array (
		"name" => "initDays",
		"sql" => " and c.initDays=# "
	),
	array (
		"name" => "sysDays",
		"sql" => " and c.sysDays=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>