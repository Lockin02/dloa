<?php
/**
 * @author Administrator
 * @Date 2012年6月3日 9:48:22
 * @version 1.0
 * @description:员工盘点表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,p.companyName ,c.userNo ,c.userAccount ,c.userName ,c.companyType ,c.deptNameS ,c.deptIdS ,c.deptNameT ,c.deptIdT ,c.position ,c.entryDate ,c.inventoryDate ,c.alternative ,c.matching ,c.critical ,c.isCore ,c.recruitment ,c.performance ,c.examine ,c.preEliminated ,c.remark ,c.adjust ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.isCritical ,c.workQuality ,c.workEfficiency ,c.workZeal
	from oa_hr_personnel_inventory c
	left join oa_hr_personnel p on c.userName=p.userName
	where 1=1 "
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
		"name" => "userNoM",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userNameM",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "companyType",
		"sql" => " and c.companyType=# "
	),
	array(
		"name" => "companyNameM",
		"sql" => " and p.companyName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptIdArr",
		"sql" => " and (c.deptIdS in(arr) or c.deptIdT in(arr)) "
	),
	array(
		"name" => "deptName",
		"sql" => " and (c.deptNameS LIKE CONCAT('%',#,'%') or c.deptNameT LIKE CONCAT('%',#,'%')) "
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
		"name" => "positionSearch",
		"sql" => " and c.position LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "position",
		"sql" => " and c.position=# "
	),
	array(
		"name" => "entryDateBegin",
		"sql" => " and c.entryDate >= BINARY # "
	),
	array(
		"name" => "entryDateEnd",
		"sql" => " and c.entryDate <= BINARY # "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate=# "
	),
	array(
		"name" => "inventoryDateBegin",
		"sql" => " and c.inventoryDate >= BINARY # "
	),
	array(
		"name" => "inventoryDateEnd",
		"sql" => " and c.inventoryDate <= BINARY # "
	),
	array(
		"name" => "inventoryDate",
		"sql" => " and c.inventoryDate=# "
	),
	array(
		"name" => "alternative",
		"sql" => " and c.alternative=# "
	),
	array(
		"name" => "matching",
		"sql" => " and c.matching=# "
	),
	array(
		"name" => "critical",
		"sql" => " and c.critical=# "
	),
	array(
		"name" => "isCore",
		"sql" => " and c.isCore=# "
	),
	array(
		"name" => "recruitment",
		"sql" => " and c.recruitment=# "
	),
	array(
		"name" => "performance",
		"sql" => " and c.performance=# "
	),
	array(
		"name" => "examine",
		"sql" => " and c.examine=# "
	),
	array(
		"name" => "preEliminated",
		"sql" => " and c.preEliminated=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "adjust",
		"sql" => " and c.adjust=# "
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
	)
)
?>