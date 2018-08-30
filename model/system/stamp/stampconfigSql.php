<?php

/**
 * @author Show
 * @Date 2012年3月29日 星期四 9:41:06
 * @version 1.0
 * @description:盖章配置表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.stampName,c.stampName as stampType ,c.principalName ,c.principalId,c.businessBelongId,d.dataName as businessBelongName,c.typeId, c.typeName, c.legalPersonUsername, c.legalPersonName, c.remark ,
			c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.status,c.stampSort
		from oa_system_stamp_config c
		left join oa_system_datadict d on c.businessBelongId = d.dataCode and d.parentCode = 'QYZT'
		where 1 ",
	"select" => "select c.id,c.stampName,c.stampName as stampType ,c.principalName ,
			c.principalId ,c.businessBelongId,d.dataName as businessBelongName, c.typeId, c.typeName, c.legalPersonUsername, c.legalPersonName, c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,
			c.updateTime,c.status
		from oa_system_stamp_config c 
		left join oa_system_datadict d on c.businessBelongId = d.dataCode and d.parentCode = 'QYZT'
		where 1=1 ",
	"select_forOption" => "select c.stampName as code,c.stampName as name  from oa_system_stamp_config c where 1=1 ",
    "select_history" => "select d.id, c.id as pid, d.stampName, d.principalName, d.principalId,d.businessBelongId, d.typeId, d.typeName, d.startTime, d.endTime, d.remark from oa_system_stamp_config c right join oa_system_stamp_config_history d on c.id = d.pid where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "stampName",
		"sql" => " and c.stampName=# "
	),
	array (
		"name" => "stampIds",
		"sql" => " and c.id like concat('%',#,'%') "
	),
	array (
		"name" => "stampNameSearch",
		"sql" => " and c.stampName like concat('%',#,'%') "
	),
    array (
        "name" => "stampNameSearch1",
        "sql" => " and d.stampName like concat('%',#,'%') "
    ),
	array (
		"name" => "principalName",
		"sql" => " and c.principalName=# "
	),
    array (
        "name" => "principalNameSearch1",
        "sql" => " and d.principalName like concat('%',#,'%') "
    ),
    array (
        "name" => "principalNameSearch2",
        "sql" => " and c.principalName like concat('%',#,'%') "
    ),
	array (
		"name" => "principalId",
		"sql" => " and c.principalId=# "
	),
	array (
		"name" => "findPrincipalId",
		"sql" => " and find_in_set( #,c.principalId ) >0"
	),
    array (
        "name" => "legalPersonUsernameSearch",
        "sql" => " and c.legalPersonUsername like concat('%',#,'%') "
    ),
    array (
        "name" => "legalPersonNameSearch",
        "sql" => " and c.legalPersonName like concat('%',#,'%') "
    ),
    array (
        "name" => "businessBelongNameSer",
        "sql" => " and d.dataName like concat('%',#,'%') "
    ),
    array (
        "name" => "typeNameSer",
        "sql" => " and c.typeName like concat('%',#,'%') "
    ),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
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
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	)
)
?>