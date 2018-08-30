<?php
/**
 * @author Administrator
 * @Date 2013年7月25日 10:08:56
 * @version 1.0
 * @description:合同收款计划 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.incomMoney,c.Tday,c.paymenttermInfo,c.conType,c.planInvoiceMoney,c.dayNum,c.deductMoney,c.id ,c.contractCode ,c.contractId ,c.contractName ,c.paymenttermId ,c.paymentterm ,
			c.paymentPer ,c.money ,c.payDT ,c.pTypeName ,c.pType ,c.collectionTerms ,c.isOver ,c.overDT ,c.changeTips ,c.isTemp ,
			c.originalId ,c.isDel ,c.remark,c.invoiceMoney,c.isfinance,c.schedulePer
		from oa_contract_receiptplan c where 1=1 ",
	"select_list" => "SELECT c.paymenttermInfo,c.conType,c.planInvoiceMoney,c.dayNum,c.isfinance,c.dateCode,c.days,if(c.Tday is not null or c.Tday !='0000-00-00',DATE_ADD(c.Tday,INTERVAL 4 MONTH),'') as dealDay,
			c.id,c.contractId,c.paymenttermId,c.paymentterm,c.paymentPer,c.money,c.payDT,c.pTypeName,c.pType,c.collectionTerms,
			c.isOver,c.overDT,c.changeTips,c.isTemp,c.originalId,c.isDel,c.remark,con.contractCode,con.contractName,c.Tday,
			c.incomMoney,c.isCom,c.deductMoney,c.invoiceMoney,con.badMoney
		from
			oa_contract_receiptplan c inner join oa_contract_contract con on c.contractId = con.id
		where 1=1"
);

$condition_arr = array (
    array (
		"name" => "prinvipalId",
		"sql" => " and con.prinvipalId=# "
	),
	array (
		"name" => "conType",
		"sql" => " and c.conType=# "
	),
	array (
		"name" => "paymenttermInfo",
		"sql" => " and c.paymenttermInfo=# "
	),
	array (
		"name" => "planInvoiceMoney",
		"sql" => " and c.planInvoiceMoney=# "
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "conContractCodeSearch",
		"sql" => " and con.contractCode like concat('%',#,'%')"
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractIdArr",
		"sql" => " and c.contractId in(arr) "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "conContractNameSearch",
		"sql" => " and con.contractName like concat('%',#,'%')"
	),
	array (
		"name" => "paymenttermId",
		"sql" => " and c.paymenttermId=# "
	),
	array (
		"name" => "paymentterm",
		"sql" => " and c.paymentterm=# "
	),
	array (
		"name" => "paymentPer",
		"sql" => " and c.paymentPer=# "
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "payDT",
		"sql" => " and c.payDT=# "
	),
	array (
		"name" => "pTypeName",
		"sql" => " and c.pTypeName=# "
	),
	array (
		"name" => "pType",
		"sql" => " and c.pType=# "
	),
	array (
		"name" => "collectionTerms",
		"sql" => " and c.collectionTerms=# "
	),
	array (
		"name" => "isOver",
		"sql" => " and c.isOver=# "
	),
	array (
		"name" => "overDT",
		"sql" => " and c.overDT=# "
	),
	array (
		"name" => "changeTips",
		"sql" => " and c.changeTips=# "
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array (
		"name" => "originalId",
		"sql" => " and c.originalId=# "
	),
	array (
		"name" => "isDel",
		"sql" => " and c.isDel=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "isCom",
		"sql" => " and c.isCom=# "
	),
	array (
		"name" => "isfinance",
		"sql" => " and c.isfinance=# "
	),
	array(
	    "name" => "mySearchCondition",
	    "sql" =>  "$"
	)
)
?>