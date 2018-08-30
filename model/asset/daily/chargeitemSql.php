<?php
/**
 * ×Ê²úÒÅÊ§sql
 *@linzx
 */
$sql_arr = array (
	"select_keep" => "select c.id,c.allocateID,c.sequence,c.assetId,c.assetCode,c.assetName," .
			"c.buyDate,c.spec,c.alreadyDay,c.estimateDay,c.residueYears,c.remark,c.createName,c.createId," .
			"c.createTime,c.updateName,c.updateId,c.updateTime,c.isReturn,c.productId,c.productName,d.machineCode
			from oa_asset_chargeitem c left join oa_asset_card d on c.assetId = d.id where 1=1"
);
$condition_arr = array (
    array (
        "name" => "productId",
        "sql" => "and c.productId = #"
    ),
	array (
		"name" => "productName",
		"sql" => "and c.productName like CONCAT('%',#,'%')"
	),
    array (
        "name" => "id",
        "sql" => "and c.id = #"
    ),
	array(
   		"name" => "isReturn",
   		"sql" => " and c.isReturn=# "
        ),
	array (
		"name" => "allocateID",
		"sql" => "and c.allocateID like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sequence",
		"sql" => "and c.sequence like CONCAT('%',#,'%')"
	),
	array (
		"name" => "beyongAssetId",
		"sql" => "and c.assetId not in(arr) "
	),
	array (
		"name" => "assetId",
		"sql" => "and c.assetId =# "
	),
	array (
		"name" => "assetCode",
		"sql" => "and c.assetCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetName",
		"sql" => "and c.assetName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "buyDate",
		"sql" => "and c.buyDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "spec",
		"sql" => "and c.spec   like CONCAT('%',#,'%')"
	),
	array (
		"name" => "alreadyDay",
		"sql" => "and c.alreadyDay  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "residueYears",
		"sql" => "and c.residueYears  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "estimateDay",
		"sql" => "and c.estimateDay  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and c.remark = #"
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
