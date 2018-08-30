<?php
$sql_arr = array (
	"select_allocation" => "select c.inAgencyCode,c.inAgencyName,c.outAgencyCode,c.outAgencyName,c.id,c.billNo,c.moveDate,c.outDeptId,c.outDeptName,c.inDeptId,c.inDeptName," .
	"c.outProId,c.outProName,c.inProId,c.inProName,c.proposerId,c.proposer,c.recipientId,c.recipient,c.remark,c.ExaStatus ,c.ExaDT ,c.isSign,c.createName ," .
	"c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_asset_allocation c where 1=1 "
);
$condition_arr = array (
    array (
        "name" => "outAgencyCode",
        "sql" => "and c.outAgencyCode = #"
    ),
	array (
		"name" => "outAgencyName",
		"sql" => "and c.outAgencyName CONCAT('%',#,'%')"
	),
    array (
        "name" => "inAgencyCode",
        "sql" => "and c.inAgencyCode = #"
    ),
	array (
		"name" => "inAgencyName",
		"sql" => "and c.inAgencyName CONCAT('%',#,'%')"
	),
	array (
		"name" => "userId",
		"sql" => "and (c.createId=# or c.recipientId=#) "
	),
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array (
		"name" => "billNo",
		"sql" => "and c.billNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "billNoEq",
		"sql" => "and c.billNo = # "
	),
	array (
		"name" => "moveDate",
		"sql" => "and c.moveDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "outDeptId",
		"sql" => " and c.outDeptId = #"
	),
	array (
		"name" => "outDeptName",
		"sql" => "and c.outDeptName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "inDeptId",
		"sql" => "and c.inDeptId =#"
	),
	array (
		"name" => "inDeptName",
		"sql" => " and c.inDeptName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "projectId",
		"sql" => " and (c.outProId = # or c.inProId = #)"
	),
	array (
		"name" => "outProId",
		"sql" => " and c.outProId = #"
	),
	array (
		"name" => "outProName",
		"sql" => "and c.outProName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "inProId",
		"sql" => "and c.inProId =#"
	),
	array (
		"name" => "inProName",
		"sql" => " and c.inProName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "proposerId",
		"sql" => " and  c.proposerId = #"
	),
	array (
		"name" => "proposer",
		"sql" => " and  c.proposer = #"
	),
	array (
		"name" => "recipientId",
		"sql" => "and c.recipientId =#"
	),
	array (
		"name" => "recipient",
		"sql" => "and c.recipient =#"
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "isSign",
		"sql" => " and c.isSign=# "
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
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
);
?>
