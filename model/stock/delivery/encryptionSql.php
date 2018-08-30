<?php
/**
 * @author Michael
 * @Date 2014年5月29日 16:42:09
 * @version 1.0
 * @description:交付加密锁任务 sql配置文件
 */
$sql_arr = array (
   "select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.sourceDocId ,c.sourceDocCode ,c.sourceDocType ,c.sourceDocTypeCode ,c.headMan ,c.headManId ,c.customerName ,c.customerId ,c.issueName ,c.issueId ,c.issueDate ,c.receiveDate ,c.finshDate ,c.remark ,c.state ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_delivery_encryption c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array(
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array(
		"name" => "sourceDocId",
		"sql" => " and c.sourceDocId=# "
	),
	array(
		"name" => "sourceDocCode",
		"sql" => " and c.sourceDocCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sourceDocType",
		"sql" => " and c.sourceDocType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sourceDocTypeCode",
		"sql" => " and c.sourceDocTypeCode=# "
	),
	array(
		"name" => "headMan",
		"sql" => " and c.headMan LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "headManId",
		"sql" => " and c.headManId=# "
	),
	array(
		"name" => "customerName",
		"sql" => " and c.customerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array(
		"name" => "issueName",
		"sql" => " and c.issueName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "issueId",
		"sql" => " and c.issueId=# "
	),
	array(
		"name" => "issueDate",
		"sql" => " and c.issueDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "receiveDate",
		"sql" => " and c.receiveDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "finshDate",
		"sql" => " and c.finshDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
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