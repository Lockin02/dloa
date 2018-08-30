<?php

/**
 * @author Show
 * @Date 2012年1月5日 星期四 10:00:48
 * @version 1.0
 * @description:测试卡信息(oa_cardsys_cardsinfo) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.operators ,c.netType ,c.packageType ,c.ratesOf ,c.cardNo ,c.pinNo ,c.cityName ,c.cardType ,
			c.projectId ,c.projectCode ,c.projectName ,c.openerId ,c.openerName ,c.ownerId ,c.ownerName ,c.openDate ,
			c.allMoney ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.idCardNo,
			c.closeDate,c.cardType,c.cardTypeName,c.location
		from oa_cardsys_cardsinfo c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "operators",
		"sql" => " and c.operators=# "
	),
	array (
		"name" => "netType",
		"sql" => " and c.netType=# "
	),
	array (
		"name" => "packageType",
		"sql" => " and c.packageType=# "
	),
	array (
		"name" => "ratesOf",
		"sql" => " and c.ratesOf=# "
	),
	array (
		"name" => "cardNo",
		"sql" => " and c.cardNo=# "
	),
	array (
		"name" => "cardNoEq",
		"sql" => " and c.cardNo=# "
	),
	array (
		"name" => "cardNoSearch",
		"sql" => " and c.cardNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "pinNo",
		"sql" => " and c.pinNo=# "
	),
	array (
		"name" => "cityName",
		"sql" => " and c.cityName=# "
	),
	array (
		"name" => "cardType",
		"sql" => " and c.cardType=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "openerId",
		"sql" => " and c.openerId=# "
	),
	array (
		"name" => "openerName",
		"sql" => " and c.openerName=# "
	),
	array (
		"name" => "openerNameSearch",
		"sql" => " and c.openerName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ownerId",
		"sql" => " and c.ownerId=# "
	),
	array (
		"name" => "ownerName",
		"sql" => " and c.ownerName=# "
	),
	array (
		"name" => "ownerNameSearch",
		"sql" => " and c.ownerName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "openDate",
		"sql" => " and c.openDate=# "
	),
	array (
		"name" => "allMoney",
		"sql" => " and c.allMoney=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>