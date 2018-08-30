<?php
/**
 * @author liub
 * @Date 2012-04-06 11:21:59
 * @version 1.0
 * @description:退货申请单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.qualityState,c.objCode,c.returnCode ,c.contractObjCode ,c.contractId ,c.contractCode ,c.contractName ,c.saleUserId ,c.saleUserName ,c.reason ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT, c.instockStatus  from oa_contract_return c LEFT JOIN oa_contract_contract oc on c.contractId = oc.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "returnCode",
   		"sql" => " and c.returnCode like CONCAT('%',#,'%') "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "contractObjCode",
   		"sql" => " and c.contractObjCode=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "saleUserId",
   		"sql" => " and c.saleUserId=# "
   	  ),
   array(
   		"name" => "saleUserName",
   		"sql" => " and c.saleUserName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "reason",
   		"sql" => " and c.reason=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
	array (//退货单编号重复验证
		"name" => "returnCodeEq",
		"sql" => " and c.returnCode=# "
	),
	array (//入库状态
		"name" => "instockStatusArr",
		"sql" => " and c.instockStatus in(arr) "
	),
	array (
		"name" => "qualityState",
		"sql" => " and c.qualityState=# "
	),
    array(
        "name" => "contractAreaCode",
        "sql" => " and oc.areaCode in(arr) "
    ),
    array (
        "name" => "createIdSql",
        "sql" => "$"
    )
)
?>