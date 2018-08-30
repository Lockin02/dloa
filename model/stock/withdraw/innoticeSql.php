<?php
/**
 * @author Administrator
 * @Date 2012年11月20日 11:41:36
 * @version 1.0
 * @description:入库通知单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.noticeCode ,c.drawId ,c.drawCode ,c.rObjCode ,c.docType ,c.docId ,c.docCode ,c.docStatus ,c.customerId ,c.customerName ,c.address ,c.linkman ,c.mobil ,c.postCode ,c.remark ,c.consigneeId ,c.consignee ,c.auditmanId ,c.auditman ,c.receiveDate ,c.mailCode ,c.isSign ,c.signman ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.signDate ,c.ext1 ,c.ext2 ,c.ext3  from oa_stock_innotice c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "noticeCode",
		"sql" => " and c.noticeCode=# "
	),
	array (
		"name" => "drawCodeSearch",
		"sql" => " and c.drawCode like concat('%',#,'%') "
	),
	array (
		"name" => "consigneeSearch",
		"sql" => " and c.consignee like concat('%',#,'%') "
	),
	array (
		"name" => "docCodeSearch",
		"sql" => " and c.docCode like concat('%',#,'%') "
	),
	array (
		"name" => "noticeCodeSearch",
		"sql" => " and c.noticeCode like concat('%',#,'%') "
	),
	array (
		"name" => "drawId",
		"sql" => " and c.drawId=# "
	),
	array (
		"name" => "drawCode",
		"sql" => " and c.drawCode=# "
	),
	array (
		"name" => "rObjCode",
		"sql" => " and c.rObjCode=# "
	),
	array (
		"name" => "docType",
		"sql" => " and c.docType=# "
	),
	array (
		"name" => "docId",
		"sql" => " and c.docId=# "
	),
	array (
		"name" => "docCode",
		"sql" => " and c.docCode=# "
	),
	array (
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "linkman",
		"sql" => " and c.linkman=# "
	),
	array (
		"name" => "mobil",
		"sql" => " and c.mobil=# "
	),
	array (
		"name" => "postCode",
		"sql" => " and c.postCode=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "consigneeId",
		"sql" => " and c.consigneeId=# "
	),
	array (
		"name" => "consignee",
		"sql" => " and c.consignee=# "
	),
	array (
		"name" => "auditmanId",
		"sql" => " and c.auditmanId=# "
	),
	array (
		"name" => "auditman",
		"sql" => " and c.auditman=# "
	),
	array (
		"name" => "receiveDate",
		"sql" => " and c.receiveDate=# "
	),
	array (
		"name" => "mailCode",
		"sql" => " and c.mailCode=# "
	),
	array (
		"name" => "isSign",
		"sql" => " and c.isSign=# "
	),
	array (
		"name" => "signman",
		"sql" => " and c.signman=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "signDate",
		"sql" => " and c.signDate=# "
	),
	array (
		"name" => "ext1",
		"sql" => " and c.ext1=# "
	),
	array(// 物料代码
		"name" => "productCode",
		"sql" => " and  c.id in(select i.mainId from oa_stock_innotice_equ i where i.productCode like CONCAT('%',#,'%')) "
	),
	array (
		"name" => "ext2",
		"sql" => " and c.ext2=# "
	),
	array (
		"name" => "ext3",
		"sql" => " and c.ext3=# "
	)
)
?>