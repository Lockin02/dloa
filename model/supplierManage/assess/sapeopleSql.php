<?php
$sql_arr = array(
	"select_default" => "select " .
				" c.id,c.assesId,c.asseserId,c.asseserName,c.assDept,c.assDeptId,c.status " .
			" from oa_supp_asses_people c " .
				" where 1=1",
	"select_MyAss" => " select " .
				"c.id,c.assesId,c.asseserId,c.asseserName,c.assDept,c.assDeptId,c.status," .
				"a.id as aid,a.assesName as aassesName,a.createName as acreateName,a.assesType as aassesType,a.beginDate as abeginDate,a.endDate as aendDate,a.createTime as acreateTime,a.status as astatus " .
			" from oa_supp_assessment a inner join oa_supp_asses_normpeo c on c.assesId=a.id " .
			" where 1=1 ",
	"select_MyAss2" => " select " .
				"c.id,c.assesId,c.asseserId,c.asseserName,c.assDept,c.assDeptId,c.status," .
				"a.id as aid,a.assesName as aassesName,a.createName as acreateName,a.assesType as aassesType,a.beginDate as abeginDate,a.endDate as aendDate,a.createTime as acreateTime,a.status as astatus " .
			" from oa_supp_assessment a inner join oa_supp_asses_people c on c.assesId=a.id " .
			" where 1=1 ",
	"select_Supp" => " select " .
				"c.id,c.assesId,c.suppPjId,c.suppId,c.suppName,c.peopleId,c.asseserId,c.asseserName, " .
				"s.id as ssuppId,s.suppName as ssuppName,s.suppLevel as ssuppLevel " .
			" from oa_supp_asses_supppeo c inner join oa_supp_lib s on c.suppId=s.id " .
			" where 1=1 "
);


$condition_arr = array(
	array(
		"name" => "assesId", //方案Id
		"sql" => " and c.assesId=# "
	),
	array(
		"name" => "assesStatusArr", //方案状态
		"sql" => " and a.status in(arr) "
	),
	array(
		"name" => "asseserId",  //人员Id
		"sql" => "and c.asseserId=#"
	),
	array(
		"name" => "statusArr", //状态
		"sql" => " and c.status in(arr) "
	),

	array(
		"name" => "assesNameSeach", //搜索字段，评估方案名称
		"sql" => " and a.assesName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "createNameSeach", //搜索字段，创建人名称
		"sql" => " and a.createName like CONCAT('%',#,'%') "
	)
);
?>