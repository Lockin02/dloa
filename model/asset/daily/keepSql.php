<?php
/**
 * ×Ê²úÎ¬±£sql
 *@linzx
 */
$sql_arr = array (
	"select_keep" => "select c.id,c.billNo,c.keepDate,c.keepType,c.deptId,c.keeperId,c.keeper,c.applyDate," .
			"c.deptName,c.keepAmount,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId," .
			"c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_keep c where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and c.id = #"
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
		"sql" => "and c.billNo = #"
	),
	array (
		"name" => "keepDate",
		"sql" => "and c.keepDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "keeperId",
		"sql" => "and c.keeperId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "keeper",
		"sql" => "and c.keeper like CONCAT('%',#,'%')"
	),
	array (
		"name" => "applyDate",
		"sql" => "and c.applyDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "keepType",
		"sql" => "and c.keepType like CONCAT('%',#,'%')"
	),
	array (
		"name" => "keepAmount",
		"sql" => "and c.keepAmount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and c.remark   like CONCAT('%',#,'%')"
	),
	array (
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus  like CONCAT('%',#,'%')"
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
