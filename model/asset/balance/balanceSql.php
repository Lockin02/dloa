<?php
/**
 * грЖюsql
 * @author fengxw
 */
$sql_arr = array (
	"select_balance" => "select c.id,c.initDepr,c.depr,c.deprRate,c.deprRemain,c.deprShould,c.workLoad,c.period,c.years,c.assetId,c.deprTime,c.localNetValue,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,c.origina,c.salvage,c.estimateDay from oa_asset_balance c where  1=1 ",
	"select_balance_assetcard" => "select c.id,c.initDepr,c.depr,c.deprRate,c.deprRemain,c.deprShould,c.workLoad,c.period,c.years,c.assetId,c.deprTime,c.origina,c.salvage,c.estimateDay,c.localNetValue,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,ac.useOrgName,ac.assetCode,ac.assetName from oa_asset_balance c  left join oa_asset_card ac on c.assetId=ac.id"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "initDepr",
   		"sql" => " and c.initDepr=# "
        ),
    array(
   		"name" => "depr",
   		"sql" => " and c.depr =#"
   	  ),
   array(
   		"name" => "deprRate",
   		"sql" => " and c.deprRate=# "
   	  ),
   array(
   		"name" => "deprRemain",
   		"sql" => " and c.deprRemain=# "
   	  ),
   array(
   		"name" => "deprShould",
   		"sql" => " and c.deprShould=# "
   	  ),
   array(
   		"name" => "workLoad",
   		"sql" => " and c.workLoad=# "
   	  ),
    array(
   		"name" => "period",
   		"sql" => " and c.period=# "
   	  ),
   array(
   		"name" => "years",
   		"sql" => " and c.years=# "
   	  ),
   array(
   		"name" => "assetId",
   		"sql" => " and c.assetId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
    array(
   		"name" => "createTime",
   		"sql" => " and c.createTime like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "updateName",
   		"sql" => " and c.updateName like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
    array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "deprTime",
   		"sql" => " and c.deprTime=# "
   	  ),
    array(
   		"name" => "origina",
   		"sql" => " and c.origina=# "
   	  ),
    array(
   		"name" => "salvage",
   		"sql" => " and c.salvage=# "
   	  ),
    array(
   		"name" => "estimateDay",
   		"sql" => " and c.estimateDay=# "
   	  ),
    array(
   		"name" => "localNetValue",
   		"sql" => " and c.localNetValue=# "
   	  ),
	array(
		"name" => "assetName",
		"sql" => "and c.assetId in(select id from oa_asset_card where assetName like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "assetCode",
		"sql" => "and c.assetId in(select id from oa_asset_card where assetCode like CONCAT('%',#,'%'))"
	)
)
?>