<?php
$sql_arr = array(
	"select_default" => "select " .
				"c.id,c.assesName,c.beginDate,c.endDate,c.assesTotal,c.assesType,c.assesRemarks,c.status,c.manageId,c.manageName,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime " .
				" from oa_supp_assessment c " .
			" where 1=1 ",
	"select_MyManage" => " select " .
				"c.id,c.assesName,c.beginDate,c.endDate,c.assesTotal,c.assesType,c.assesRemarks,c.status,c.manageId,c.manageName,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime " .
				" from oa_supp_assessment c " .
			" where 1=1 "
);


$condition_arr = array(
	array(
		"name" => "id", //����Id
		"sql" => "and c.id=#"
	),
	array(
		"name" => "manageId", //������Id
		"sql" => "and c.manageId=#"
	),
	array(
		"name" => "statusArr", //״̬
		"sql" => " and c.status in(arr) "
	),
	array(
		"name" => "assesNameSeach", //�����ֶΣ�������������
		"sql" => " and c.assesName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "createNameSeach", //�����ֶΣ�����������
		"sql" => " and c.createName like CONCAT('%',#,'%') "
	),

);
?>
