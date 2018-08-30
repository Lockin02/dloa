<?php
$sql_arr = array (
"select_personnel" => "select c.id,c.userName,c.officeName,c.locationName,c.officeId,c.userCode,c.sex,c.positionCode,c.userLevel,c.levelId,c.originPlace,c.attendStatus,c.aptitudeNum,c.checkDate,c.currentProName,c.proEndDate,c.conYear,c.leaderName,c.leaderCode,c.nproId,c.nproName,c.ncityId,c.ncityName from oa_esm_personal_baseinfo c where 1=1",
"select_simple"=>"select c.leaderCode,c.leaderName,c.userLevel from oa_esm_personal_baseinfo c where 1=1 ",
"in_user" => "select c.USER_ID,c.USER_NAME,c.EMAIL,c.SEX from user c where 1=1 ",
"office_list" => "select c.id,c.userName,c.officeName,c.currentProName,c.proEndDate,c.locationName,c.attendStatus from oa_esm_personal_baseinfo c inner join oa_esm_office_range r on c.officeId = r.officeId where 1=1"
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and like = #"
	),
	array (
		"name" => "userName",
		"sql" => "and c.userName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "officeName",
		"sql" => "and c.officeName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "locationName",
		"sql" => "and c.locationName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "officeId",
		"sql" => "and c.officeId = #"
	),
	array (
		"name" => "userCode",
		"sql" => "and c.userCode = #"
	),
	array(
		"name" => "userBatch",
		"sql" => "and c.USER_ID in(arr)"
	),
	array(
		"name" => "rprocode",
		"sql" => " and  r.proCode = # "
	),
	array(
		"name" => "projY",
		"sql" => " and c.currentProName is not null and c.currentProName <> '' "
	),
	array(
		"name" => "projN",
		"sql" => " and (currentProName is null or currentProName = '') "
	)
);
?>