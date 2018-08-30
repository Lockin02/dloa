<?php
/**
 * ×Ê²úÒÅÊ§sql
 *@linzx
 */
$sql_arr = array (
	"select_keep" => "select c.id,c.billNo,c.loseDate,c.applicatId,c.deptId,c.applicat,c.loseNum,c.loseAmount," .
			"c.deptName,c.realAmount,c.reason,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId," .
			"c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_lose c where 1=1"
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
		"name" => "loseDate",
		"sql" => "and c.loseDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "applicatId",
		"sql" => "and c.applicatId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "applicat",
		"sql" => "and c.applicat like CONCAT('%',#,'%')"
	),
	array (
		"name" => "loseNum",
		"sql" => "and c.loseNum like CONCAT('%',#,'%')"
	),
	array (
		"name" => "loseAmount",
		"sql" => "and c.loseAmount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "realAmount",
		"sql" => "and c.realAmount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "reason",
		"sql" => "and c.reason   like CONCAT('%',#,'%')"
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
		"name" => "createId",
		"sql" => "and c.createId = #"
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
