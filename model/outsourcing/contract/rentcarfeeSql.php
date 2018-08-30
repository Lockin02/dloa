<?php
/**
 * @author Michael
 * @Date 2014年9月29日 19:22:05
 * @version 1.0
 * @description:租车合同附加费用 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.contractId ,c.orderCode ,c.feeName ,c.feeAmount ,c.remark ,c.isTemp ,c.originalId ,c.isDel  from oa_contract_rentcarfee c where 1=1 ",

	//根据车牌号查找相关的合同附加费用
	"select_car"=>"SELECT c.id ,c.contractId ,c.orderCode ,c.feeName ,c.feeAmount ,c.remark ,c.isTemp ,c.originalId ,c.isDel FROM oa_contract_rentcarfee c
		LEFT JOIN oa_contract_rentcar r ON r.id = c.contractId AND r.isTemp = 0
		LEFT JOIN oa_contract_vehicle v ON v.contractId = c.contractId AND v.isTemp = 0
		WHERE c.isTemp = 0 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array(
		"name" => "orderCode",
		"sql" => " and c.orderCode=# "
	),
	array(
		"name" => "feeName",
		"sql" => " and c.feeName=# "
	),
	array(
		"name" => "feeAmount",
		"sql" => " and c.feeAmount=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
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
		"name" => "useCarDate",
		"sql" => " and (r.contractStartDate<=# and r.contractEndDate>=#) "
	),
	array(
		"name" => "carNum",
		"sql" => " and v.carNumber=# "
	)
)
?>