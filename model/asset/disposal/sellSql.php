<?php
/**
 * 资产出售sql
 *@linzx
 */
$sql_arr = array (
	"select_sell" => "select s.id,s.billNo,s.donationDate,s.company,s.sellNum," .
			"s.sellAmount,s.remark,s.ExaStatus,s.ExaDT,s.allocateID,s.scrapitemId,s.createName,s.createId," .
			"s.createTime,s.updateName,s.updateId,s.updateTime,s.seller,s.sellerId,s.deptName,s.deptId,s.sellDate
			from oa_asset_sell s where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and s.id = #"
    ),
    array (
		"name" => "scrapitemId",
		"sql" => "and s.scrapitemId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "seller",
		"sql" => "and s.seller like CONCAT('%',#,'%')"
	),
	array (
		"name" => "allocateID",
		"sql" => "and s.allocateID like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sellerId",
		"sql" => "and s.sellerId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and s.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sellDate",
		"sql" => "and s.sellDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "billNo",
		"sql" => "and s.billNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "donationDate",
		"sql" => "and s.donationDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "company",
		"sql" => "and s.company like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sellNum",
		"sql" => "and s.sellNum like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sellAmount",
		"sql" => "and s.sellAmount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and s.remark like CONCAT('%',#,'%')"
	),
	array (
		"name" => "ExaStatus",
		"sql" => "and s.ExaStatus  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "ExaDT",
		"sql" => "and s.ExaDT = #"
	),
	array (
		"name" => "createName",
		"sql" => "and s.createName in(arr)"
	),
	array (
		"name" => "createTime",
		"sql" => "and s.createTime = #"
	),
	array (
		"name" => "updateName",
		"sql" => "and s.updateName = #"
	),
	array (
		"name" => "updateId",
		"sql" => "and s.updateId = #"
	),
	array (
		"name" => "updateTime",
		"sql" => "and s.updateTime = #"
	)

);
?>
