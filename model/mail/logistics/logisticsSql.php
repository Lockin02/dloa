<?php

/**
 * @author Show
 * @Date 2011��5��27�� ������ 9:35:54
 * @version 1.0
 * @description:������˾������Ϣ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.companyCode ,c.companyName ,c.introduction ,c.address ,c.phone ,c.rangeDelivery ,
			c.speed ,c.security,c.isDefault  from oa_mail_logistics c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "companyCode",
		"sql" => " and c.companyCode=# "
	),
	array (
		"name" => "companyName",
		"sql" => " and c.companyName=# "
	),
	array (
		"name" => "introduction",
		"sql" => " and c.introduction=# "
	),
	array (
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array (
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array (
		"name" => "rangeDelivery",
		"sql" => " and c.rangeDelivery=# "
	),
	array (
		"name" => "speed",
		"sql" => " and c.speed=# "
	),
	array (
		"name" => "security",
		"sql" => " and c.security=# "
	)
)
?>