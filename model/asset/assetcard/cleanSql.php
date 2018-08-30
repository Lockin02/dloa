<?php
$sql_arr = array (
	"select" => "select c.id,c.cleanDate,c.cleanFee,c.explanation," .
			"c.salvageFee,c.period,c.years,c.assetId,c.assetName,c.assetCode,c.businessType,c.businessId,c.businessNo,c.assetType,c.changeWay,c.ExaStatus,c.ExaDT,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_clean c where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and c.id = #"
    ),
    array (
		"name" => "assetType",
		"sql" => "and c.assetType like CONCAT('%',#,'%')"
	),
   array (
		"name" => "changeWay",
		"sql" => "and c.changeWay like CONCAT('%',#,'%')"
	),
	array (
		"name" => "cleanDate",
		"sql" => "and c.cleanDate like CONCAT('%',#,'%')"
	),
   array (
		"name" => "businessNo",
		"sql" => "and c.businessNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "cleanFee",
		"sql" => "and c.cleanFee like CONCAT('%',#,'%')"
	),
	array (
		"name" => "explanation",
		"sql" => "and c.explanation like CONCAT('%',#,'%')"
	),
	array (
		"name" => "salvageFee",
		"sql" => "and c.salvageFee like CONCAT('%',#,'%')"
	),
	array (
		"name" => "period",
		"sql" => "and c.period like CONCAT('%',#,'%')"
	),
	array (
		"name" => "years",
		"sql" => "and c.years like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetId",
		"sql" => "and c.assetId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetCode",
		"sql" => "and c.assetCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetName",
		"sql" => "and c.assetName like CONCAT('%',#,'%')"
	),array (
		"name" => "businessType",
		"sql" => "and c.businessType like CONCAT('%',#,'%')"
	),
	array (
		"name" => "businessId",
		"sql" => "and c.businessId like CONCAT('%',#,'%')"
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
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "ExaDT",
		"sql" => "and c.ExaDT = #"
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
