<?php
/**
 * 资产验收明细sql
 * @author fengxw
 */
$sql_arr = array (
	"select_receiveItem" => "select c.id,c.receiveId,c.assetId,c.assetCode,c.assetName,c.supply,c.spec,c.checkAmount,c.price,c.amount,c.remark,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime," .
			"c.applyId,c.applyCode,c.applyEquId,c.contractEquId,c.deploy,c.cardNum,c.cardStatus,e.requireinId,e.requireinCode,c.requireinItemId,c.brand from oa_asset_receiveItem c left join oa_asset_receive e on e.id=c.receiveId where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "receiveId",
   		"sql" => " and c.receiveId=# "
   	  ),
   array(
   		"name" => "assetName",
   		"sql" => " and c.assetName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "supply",
   		"sql" => " and c.supply like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "spec",
   		"sql" => " and c.spec like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "checkAmount",
   		"sql" => " and c.checkAmount=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
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
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyCode",
   		"sql" => " and c.applyCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "applyEquId",
   		"sql" => " and c.applyEquId=# "
   	  ),
   array(
   		"name" => "contractEquId",
   		"sql" => " and c.contractEquId=# "
   	  ),
	array(
		"name" => "contractEquId",
		"sql" => " and c.contractEquId=# "
	),
	array(
		"name" => "deploy",
		"sql" => " and c.deploy=# "
	),
	array(
		"name" => "cardStatus",
		"sql" => " and c.cardStatus=# "
	)
)
?>