<?php
/**
 * 资产验收sql
 * @author fengxw
 */
$sql_arr = array (
	"select_receive" => "select c.id,c.applyId,c.name,c.code,c.limitYears,c.salvageId,c.salvage,c.deptId,c.deptName,c.amount,c.result,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,
		c.purchaseContractCode,c.purchaseContractId,c.type,c.requireinId,c.requireinCode,b.sendName,r.applyName	
		from oa_asset_receive c left join oa_purch_apply_basic b on c.purchaseContractId = b.id left join oa_asset_requirein r on c.requireinId = r.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
        ),
	array(
   		"name" => "name",
   		"sql" => " and c.name like CONCAT('%',#,'%')"
        ),
    array(
   		"name" => "nameEq",
   		"sql" => " and c.name =#"
   	  ),
   array(
   		"name" => "code",
   		"sql" => " and c.code like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "limitYears",
   		"sql" => " and c.limitYears like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "salvageId",
   		"sql" => " and c.salvageId=# "
   	  ),
   array(
   		"name" => "salvage",
   		"sql" => " and c.salvage like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "result",
   		"sql" => " and c.result like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT like CONCAT('%',#,'%')"
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
		"name" => "deploy",
		"sql" => " and c.deploy like CONCAT('%',#,'%')"
	),
    array(
   		"name" => "type",
   		"sql" => " and c.type=# "
   	),
	//源单编号,目前有采购单编号，采购订单编号，物料转资产编号3种
	array(
		"name" => "relDocCode",
		"sql" => " and (c.code like CONCAT('%',#,'%') or c.purchaseContractCode like CONCAT('%',#,'%') or c.requireinCode like CONCAT('%',#,'%'))"
	)
)
?>
