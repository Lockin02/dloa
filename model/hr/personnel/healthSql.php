<?php
/**
 * @author Administrator
 * @Date 2012年6月22日 星期五 11:09:55
 * @version 1.0
 * @description:人事管理-基础信息-健康信息 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptNameS ,c.deptIdS ,c.deptNameT ,c.deptIdT ,c.hospital ,c.checkDate ,c.checkResult ,c.remark ,c.hospitalOpinion ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_hr_personnel_health c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptNameS",
		"sql" => " and c.deptNameS=# "
	),
	array(
		"name" => "deptIdS",
		"sql" => " and c.deptIdS=# "
	),
	array(
		"name" => "deptNameT",
		"sql" => " and c.deptNameT=# "
	),
	array(
		"name" => "deptIdT",
		"sql" => " and c.deptIdT=# "
	),
	array(
		"name" => "hospital",
		"sql" => " and c.hospital LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "hospitalSearch",
		"sql" => " and c.hospital LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "checkDate",
		"sql" => " and c.checkDate=# "
	),
	array(
		"name" => "checkResult",
		"sql" => " and c.checkResult LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hospitalOpinion",
		"sql" => " and c.hospitalOpinion=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.checkDate >= BINARY # "
	),
	array(
		"name" => "endDate",
		"sql" => " and c.checkDate <= BINARY # "
	)
)
?>