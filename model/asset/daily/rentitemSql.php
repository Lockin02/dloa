<?php
/**
 * @author Administrator
 * @deprecated version - 2011-11-28
 * @version 1.0
 * @description:资产租赁清单 sql配置文件
 */
$sql_arr = array (
         "select_rentitem"=>"select c.id ,c.rentId ,c.sequence ,c.assetId ,c.assetCode ,c.assetName ,c.buyDate ," .
         		"c.spec ,c.unit ,c.origina,c.rentValue,c.remark,c.createName ,c.createId ,c.createTime ,c.updateName ," .
         		"c.updateId ,c.updateTime  from oa_asset_rentitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "rentId",
   		"sql" => " and c.rentId=# "
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
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   	   array(
   		"name" => "origina",
   		"sql" => " and c.origina=# "
   	  ),
   	   array(
   		"name" => "rentValue",
   		"sql" => " and c.rentValue=# "
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