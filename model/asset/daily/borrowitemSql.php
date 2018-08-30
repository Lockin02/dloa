<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:38:28
 * @version 1.0
 * @description:资产借用清单 sql配置文件
 */
$sql_arr = array (
         "select_borrowitem"=>"select c.id ,c.borrowId ,c.sequence ,c.assetId ,c.assetCode " .
         		",c.assetName ,c.buyDate ,c.spec ,c.estimateDay,c.alreadyDay,c.residueYears " .
         		",c.remark,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId " .
         		",c.updateTime,c.isReturn,c.productId,c.productName  from oa_asset_borrowitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
        ),
	array (
		"name" => "productName",
		"sql" => "and c.productName like CONCAT('%',#,'%')"
	),
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "isReturn",
   		"sql" => " and c.isReturn=# "
        ),
   array(
   		"name" => "borrowId",
   		"sql" => " and c.borrowId=# "
   	  ),
   array(
   		"name" => "sequence",
   		"sql" => " and c.sequence=# "
   	  ),
   array(
   		"name" => "beyongAssetId",
   		"sql" => " and c.assetId not in(arr) "
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
   		"name" => "residueYears",
   		"sql" => " and c.residueYears=# "
   	  ),
   array(
   		"name" => "estimateDay",
   		"sql" => " and c.estimateDay=# "
   	  ),
   array(
   		"name" => "alreadyDay",
   		"sql" => " and c.alreadyDay=# "
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