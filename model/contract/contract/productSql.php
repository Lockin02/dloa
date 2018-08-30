<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:合同 产品清单 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.conProductName,c.changeTips ,c.tempId,
        c.conProductId ,c.conProductCode ,c.conProductDes ,
        c.contractId ,c.contractCode ,c.contractName ,c.version ,
        c.number ,c.remark ,c.price ,c.unitName ,c.money ,
        c.warrantyPeriod ,c.license ,c.deploy ,c.backNum  ,
        c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ,
        c.issuedPurNum ,c.issuedProNum ,c.proType, c.proTypeId, c.exeDeptName, c.exeDeptId,
        c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.deploy as orgDeploy,
        c.isConfig ,c.isNeedDelivery ,c.onlyProductId ,c.newProLineCode ,c.newProLineName from oa_contract_product c where 1=1 ",
    "select2" => "select c.id ,c.conProductName,c.changeTips ,c.tempId,
        c.conProductId ,c.conProductCode ,c.conProductDes ,
        c.contractId ,c.contractCode ,c.contractName ,c.version ,
        c.number ,c.remark ,c.price ,c.unitName ,c.money ,
        c.warrantyPeriod ,c.license ,c.deploy ,c.backNum  ,
        c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ,
        c.issuedPurNum ,c.issuedProNum ,c.proType, c.proTypeId, c.exeDeptName, c.exeDeptId,
        c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.deploy as orgDeploy,
        c.isConfig ,c.isNeedDelivery ,c.newProLineCode ,c.newProLineName from oa_contract_product c
        left join oa_contract_contract t on t.id=c.contractId where 1=1 "
);


$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "conProductName",
        "sql" => " and c.conProductName=# "
    ),
    array(
        "name" => "conProductId",
        "sql" => " and c.conProductId=# "
    ),
    array(
        "name" => "conProductCode",
        "sql" => " and c.conProductCode=# "
    ),
    array(
        "name" => "conProductDes",
        "sql" => " and c.conProductDes=# "
    ),
    array(
        "name" => "contractId",
        "sql" => " and c.contractId=# "
    ),
    array(
        "name" => "contractCode",
        "sql" => " and c.contractCode=# "
    ),
    array(
        "name" => "contractName",
        "sql" => " and c.contractName=# "
    ),
    array(
        "name" => "version",
        "sql" => " and c.version=# "
    ),
    array(
        "name" => "number",
        "sql" => " and c.number=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
    ),
    array(
        "name" => "price",
        "sql" => " and c.price=# "
    ),
    array(
        "name" => "unitName",
        "sql" => " and c.unitName=# "
    ),
    array(
        "name" => "money",
        "sql" => " and c.money=# "
    ),
    array(
        "name" => "warrantyPeriod",
        "sql" => " and c.warrantyPeriod=# "
    ),
    array(
        "name" => "license",
        "sql" => " and c.license=# "
    ),
    array(
        "name" => "deploy",
        "sql" => " and c.deploy=# "
    ),
    array(
        "name" => "backNum",
        "sql" => " and c.backNum=# "
    ),
    array(
        "name" => "issuedShipNum",
        "sql" => " and c.issuedShipNum=# "
    ),
    array(
        "name" => "executedNum",
        "sql" => " and c.executedNum=# "
    ),
    array(
        "name" => "onWayNum",
        "sql" => " and c.onWayNum=# "
    ),
    array(
        "name" => "purchasedNum",
        "sql" => " and c.purchasedNum=# "
    ),
    array(
        "name" => "issuedPurNum",
        "sql" => " and c.issuedPurNum=# "
    ),
    array(
        "name" => "issuedProNum",
        "sql" => " and c.issuedProNum=# "
    ),
    array(
        "name" => "uniqueCode",
        "sql" => " and c.uniqueCode=# "
    ),
    array(
        "name" => "productLine",
        "sql" => " and c.productLine=# "
    ),
    array(
        "name" => "productLineName",
        "sql" => " and c.productLineName=# "
    ),
    array(
        "name" => "isTemp",
        "sql" => " and c.isTemp=# "
    ),
    array(
        "name" => "originalId",
        "sql" => " and c.originalId=# "
    ),
    array(
        "name" => "isDel",
        "sql" => " and c.isDel=# "
    ),
    array(
        "name" => "isCon",
        "sql" => " and c.isCon=# "
    ),
    array(
        "name" => "isConfig",
        "sql" => " and c.isConfig=# "
    ),
    array(
        "name" => "isNeedDelivery",
        "sql" => " and c.isNeedDelivery=# "
    ),
    array(
        "name" => "ExaStatus",
        "sql" => " and t.ExaStatus=# "
    ),
    array(
        "name" => "newProLineCode",
        "sql" => " and c.newProLineCode=# "
    ),
    array(
        "name" => "newProLineName",
        "sql" => " and c.newProLineName=# "
    ),
	array(
		"name" => "proTypeIdNot",
		"sql" => " and c.proTypeId not in(arr) "
	)
);