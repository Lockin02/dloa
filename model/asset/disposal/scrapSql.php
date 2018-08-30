<?php
/**
 * 资产报废sql
 *@linzx
 */
$sql_arr = array (
	"select_scrap" => "select c.id,c.billNo,c.scrapDate,c.deptId,c.proposerId,
			c.deptName,c.proposer,c.scrapNum,c.reason,c.amount,
			c.salvage,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId,
			c.createTime,c.updateName,c.updateId,c.updateTime,c.scrapType,
			c.scrapDeal,c.payerId,c.payer,c.hasAccount,c.financeStatus,c.netValue
			from oa_asset_scrap c where 1=1"
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
		"name" => "scrapDate",
		"sql" => "and c.scrapDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "proposer",
		"sql" => "and c.proposer like CONCAT('%',#,'%')"
	),
	array (
		"name" => "scrapNum",
		"sql" => "and c.scrapNum like CONCAT('%',#,'%')"
	),
	array (
		"name" => "reason",
		"sql" => "and c.reason like CONCAT('%',#,'%')"
	),array (
		"name" => "amount",
		"sql" => "and c.amount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "salvage",
		"sql" => "and c.salvage like CONCAT('%',#,'%')"
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
	),
	array (
		"name" => "scrapType",
		"sql" => "and c.scrapType = #"
	),
	array (
		"name" => "scrapDeal",
		"sql" => "and c.scrapDeal = #"
	),
	array (
		"name" => "payerId",
		"sql" => "and c.payerId = #"
	),
	array (
		"name" => "payer",
		"sql" => "and c.payer = #"
	),
	array (
		"name" => "hasAccount",
		"sql" => "and c.hasAccount = #"
	),
	array (
		"name" => "financeStatus",
		"sql" => "and c.financeStatus = #"
	),
	array (
		"name" => "netValue",
		"sql" => "and c.netValue = #"
	),
   array(//自定义条件
		"name" => "statusCondition",
		"sql" => "$"
	)
);
?>
