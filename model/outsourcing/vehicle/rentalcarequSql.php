<?php
/**
 * @author Michael
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理从表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.deptName ,c.deptId ,c.suppId ,c.suppCode ,c.suppName ,c.suppAffirm ,c.linkManName ,c.linkManPhone ,c.useCarAmount ,c.certificate ,c.powerSupply ,c.powerSupplyCode ,c.paymentCycle ,c.paymentCycleCode ,c.invoice ,c.invoiceCode ,c.taxPoint ,c.rentalFee ,c.gasolineFee ,c.catering ,c.accommodationFee ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.vehicleModel ,c.vehicleModelCode,c.spotPrice ,c.spotPriceExplain ,c.vehicleMileage ,c.isProvideInvoice ,c.taxationBears ,c.taxationBearsCode from oa_outsourcing_rentalcarequ c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "suppId",
		"sql" => " and c.suppId=# "
	),
	array(
		"name" => "suppCode",
		"sql" => " and c.suppCode=# "
	),
	array(
		"name" => "suppName",
		"sql" => " and c.suppName=# "
	),
	array(
		"name" => "suppAffirm",
		"sql" => " and c.suppAffirm=# "
	),
	array(
		"name" => "linkManName",
		"sql" => " and c.linkManName=# "
	),
	array(
		"name" => "linkManPhone",
		"sql" => " and c.linkManPhone=# "
	),
	array(
		"name" => "useCarAmount",
		"sql" => " and c.useCarAmount=# "
	),
	array(
		"name" => "certificate",
		"sql" => " and c.certificate=# "
	),
	array(
		"name" => "powerSupply",
		"sql" => " and c.powerSupply=# "
	),
	array(
		"name" => "powerSupplyCode",
		"sql" => " and c.powerSupplyCode=# "
	),
	array(
		"name" => "paymentCycle",
		"sql" => " and c.paymentCycle=# "
	),
	array(
		"name" => "paymentCycleCode",
		"sql" => " and c.paymentCycleCode=# "
	),
	array(
		"name" => "invoice",
		"sql" => " and c.invoice=# "
	),
	array(
		"name" => "invoiceCode",
		"sql" => " and c.invoiceCode=# "
	),
	array(
		"name" => "taxPoint",
		"sql" => " and c.taxPoint=# "
	),
	array(
		"name" => "rentalFee",
		"sql" => " and c.rentalFee=# "
	),
	array(
		"name" => "gasolineFee",
		"sql" => " and c.gasolineFee=# "
	),
	array(
		"name" => "catering",
		"sql" => " and c.catering=# "
	),
	array(
		"name" => "accommodationFee",
		"sql" => " and c.accommodationFee=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>