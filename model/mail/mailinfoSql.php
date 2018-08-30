<?php
$sql_arr = array (
	"select_mail" => "select c.id,c.docType,c.docId,c.docCode,c.mailNo,
		c.receiver,c.tel,c.mailType,c.mailMoney,c.mailTime,c.mailMan,c.salesman,c.salesmanId,
		c.mailStatus,c.createName,c.createTime,c.customerName,c.logisticsName,c.signDate,
		c.updateName,c.status
		from oa_mail_info c where 1=1 "
	,"select_order" => "select s.id,c.mailNo,c.docType,c.mailTime,c.logisticsName,c.receiver,c.mailMan,c.mailType,c.mailStatus,c.address from oa_stock_ship s right join oa_mail_info c on s.id=c.docId where 1=1"
);
$condition_arr = array (
	array (
		"name" => "docType",
		"sql" => "and c.docType =#"
	),
	array (
		"name" => "docId",
		"sql" => "and c.docId =#"
	),
	array (
		"name" => "docCode",
		"sql" => "and c.docCode =#"
	),
	array (
		"name" => "mailApplyId",
		"sql" => "and c.mailApplyId =#"
	),
	array (
		"name" => "mailStatus",
		"sql" => "and c.mailStatus =#"
	),
	array (
		"name" => "mailNo",
		"sql" => "and c.mailNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "customerName",
		"sql" => "and c.customerName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "mailMan",
		"sql" => "and c.mailMan like CONCAT('%',#,'%')"
	),
	array (
		"name" => "receiver",
		"sql" => "and c.receiver like CONCAT('%',#,'%')"
	),
	array (
		"name" => "mailType",
		"sql" => "and c.mailType like CONCAT('%',#,'%')"
	),
	array (
		"name" => "mailStatus",
		"sql" => "and c.mailStatus =#"
	),
	array (
		"name" => "status",
		"sql" => "and c.status =#"
	),
	array (
		"name" => "applyType",
		"sql" => "and (c.mailApplyId in(select id from oa_mail_apply where applyType =#))"
	),
	array (
		"name" => "docCodeSearch",
		"sql" => " and c.docCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "invoiceIds",
		"sql" => " or (c.docType='YJSQDLX-FPYJ' and c.docId in(arr))"
	),
	array (
		"name" => "outplanIds",
		"sql" => " or (c.docType='YJSQDLX-FHYJ' and c.docId in(arr))"
	),
	array(
		"name" => "beginDate",
		"sql" => " and (DATE_FORMAT(c.mailTime,'%Y%m') - #) >= 0"
	),
    array(
		"name" => "endDate",
		"sql" => " and (DATE_FORMAT(c.mailTime,'%Y%m') - #) <= 0"
	)
);
?>
