<?php
/**
 * 资产遗失sql
 *@linzx
 */
$sql_arr = array (
	"select_keep" => "select c.agencyCode,c.agencyName,c.isSign,c.signDate,c.requireId,c.requireCode,c.docStatus,c.id,c.billNo,c.chargeDate,c.chargeManId,c.deptId,c.chargeMan," .
			"c.deptName,c.reposeDeptId,c.reposeDept,c.reposeManId,c.reposeMan,c.isSign,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId," .
			"c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_charge c where 1=1"
);
$condition_arr = array (
   array(// 物料名称
		"name" => "productName",
		"sql" => " and  c.id in(select i.allocateID from oa_asset_chargeitem i where i.assetName like CONCAT('%',#,'%')) "
	),
	array(// 物料代码
		"name" => "productCode",
		"sql" => " and  c.id in(select i.allocateID from oa_asset_chargeitem i where i.assetCode like CONCAT('%',#,'%'))"
	),
    array (
        "name" => "agencyCode",
        "sql" => "and c.agencyCode = #"
    ),
	array (
		"name" => "agencyName",
		"sql" => "and c.agencyName CONCAT('%',#,'%')"
	),
    array (
        "name" => "isSign",
        "sql" => "and c.isSign = #"
    ),
    array (
        "name" => "reposeManId",
        "sql" => "and c.reposeManId = #"
    ),
	array (
		"name" => "reposeMan",
		"sql" => "and c.reposeMan CONCAT('%',#,'%')"
	),
    array (
        "name" => "reposeDeptId",
        "sql" => "and c.reposeDeptId = #"
    ),
	array (
		"name" => "reposeDept",
		"sql" => "and c.reposeDept like CONCAT('%',#,'%')"
	),
	array (
		"name" => "requireId",
		"sql" => "and c.requireId=#"
	),
	array (
		"name" => "requireCode",
		"sql" => "and c.requireCode like CONCAT('%',#,'%')"
	),
    array (
        "name" => "id",
        "sql" => "and c.id = #"
    ),
	array (
		"name" => "unDocStatus",
		"sql" => "and c.docStatus<>#"
	),
	array (
		"name" => "docStatus",
		"sql" => "and c.docStatus=#"
	),
	array (
		"name" => "isSign",
		"sql" => "and c.isSign like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptName",
		"sql" => "and c.deptName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "billNo",
		"sql" => "and c.billNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "billNoEq",
		"sql" => "and c.billNo =#"
	),
	array (
		"name" => "chargeDate",
		"sql" => "and c.chargeDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "chargeManId",
		"sql" => "and c.chargeManId=#"
	),
	array (
		"name" => "chargeMan",
		"sql" => "and c.chargeMan like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and c.remark   like CONCAT('%',#,'%')"
	),
	array (
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus=#"
	),
	array (
		"name" => "ExaDT",
		"sql" => "and c.ExaDT = #"
	),
	array (
		"name" => "createName",
		"sql" => "and c.createName in(arr)"
	),
	array (
		"name" => "createId",
		"sql" => "and c.createId = #"
	),
	array (
		"name" => "userId",
		"sql" => "and (c.createId = # or c.chargeManId = #)"
	),
	array (
		"name" => "createTime",
		"sql" => "and c.createTime = #"
	),
	array (
		"name" => "updateName",
		"sql" => "and c.updateName = #"
	),
	array (
		"name" => "updateId",
		"sql" => "and c.updateId = #"
	),
	array (
		"name" => "updateTime",
		"sql" => "and c.updateTime = #"
	)

);
?>
