<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:38:28
 * @version 1.0
 * @description 资产调拨清单 sql配置文件
 */
$sql_arr = array (
         "select_allocationitem"=>"select c.id ,c.allocateID ,c.sequence ,c.assetId ,c.assetCode ,c.assetName ," .
         		"c.englishName,c.buyDate ,c.spec ,c.estimateDay,c.alreadyDay,c.monthDepr,c.depreciation,c.salvage,c.origina," .
         		"c.beforeUse,c.afterUse,c.beforePlace,c.afterPlace,c.remark,c.createName ,c.createId ,c.createTime ," .
         		"c.updateName ,c.updateId ,c.updateTime ,c.useStatus from oa_asset_allocationitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "allocateID",
   		"sql" => " and c.allocateID=# "
   	  ),
   array(
   		"name" => "sequence",
   		"sql" => " and c.sequence=# "
   	  ),
   array(
   		"name" => "assetId",
   		"sql" => " and c.assetId=# "
   	  ),
   array(
   		"name" => "assetCode",
   		"sql" => " and c.assetCode=# "
   	  ),
   array(
   		"name" => "assetName",
   		"sql" => " and c.assetName=# "
   	  ), array(
   		"name" => "englishName",
   		"sql" => " and c.englishName=# "
   	  ),
   array(
   		"name" => "buyDate",
   		"sql" => " and c.buyDate=# "
   	  ),
   array(
   		"name" => "spec",
   		"sql" => " and c.spec=# "
   	  ),
   array(
   		"name" => "estimateDay",
   		"sql" => " and c.estimateDay=# "
   	  ),array(
   		"name" => "alreadyDay",
   		"sql" => " and c.alreadyDay=# "
        ),
   array(
   		"name" => "monthDepr",
   		"sql" => " and c.monthDepr=# "
   	  ),
   array(
   		"name" => "depreciation",
   		"sql" => " and c.depreciation=# "
   	  ),
   array(
   		"name" => "salvage",
   		"sql" => " and c.salvage=# "
   	  ),
	array(
		"name" => "origina",
		"sql" => " and c.origina=# "
	  ),
   array(
   		"name" => "beforeUse",
   		"sql" => " and c.beforeUse=# "
   	  ),
   array(
   		"name" => "afterUse",
   		"sql" => " and c.afterUse=# "
   	  ), array(
   		"name" => "beforePlace",
   		"sql" => " and c.beforePlace=# "
   	  ),
   array(
   		"name" => "afterPlace",
   		"sql" => " and c.afterPlace=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ), array(
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