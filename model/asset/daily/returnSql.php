<?php
/**
 * 资产归还sql
 *@linzx
 */
$sql_arr = array (
	"select_return" => "select c.isSign,c.signDate,c.agencyCode,c.agencyName,c.id,c.billNo,c.borrowId,c.borrowNo,c.returnDate,c.deptId,c.deptName,c.returnMan," .
			"c.returnType,c.returnManId,c.remark,c.ExaStatus,c.ExaDT,c.createName,c.createId," .
			"c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_return c where 1=1"
);
$condition_arr = array (
   array(// 物料名称
		"name" => "productName",
		"sql" => " and  c.id in(select i.allocateID from oa_asset_returnitem i where i.assetName like CONCAT('%',#,'%')) "
	),
	array(// 物料代码
		"name" => "productCode",
		"sql" => " and  c.id in(select i.allocateID from oa_asset_returnitem i where i.assetCode like CONCAT('%',#,'%')) "
	),
    array (
        "name" => "agencyCode",
        "sql" => "and c.agencyCode = # "
    ),
	array (
		"name" => "agencyName",
		"sql" => "and c.agencyName CONCAT('%',#,'%') "
	),
    array (
        "name" => "signDate",
        "sql" => "and c.signDate = # "
    ),
    array (
        "name" => "isSign",
        "sql" => "and c.isSign = # "
    ),
    array (
        "name" => "id",
        "sql" => "and c.id = # "
    ),
	array (
		"name" => "borrowId",
		"sql" => "and c.borrowId like CONCAT('%',#,'%') "
	),
	array (
		"name" => "billNo",
		"sql" => "and c.billNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "billNoEq",
		"sql" => "and c.billNo = # "
	),
	array (
		"name" => "borrowNo",
		"sql" => "and c.borrowNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId like CONCAT('%',#,'%') "
	),
	array (
		"name" => "returnType",
		"sql" => "and c.returnType like CONCAT('%',#,'%') "
	),
	array (
		"name" => "returnDate",
		"sql" => "and c.returnDate like CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptName",
		"sql" => "and c.deptName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "returnMan",
		"sql" => "and c.returnMan like CONCAT('%',#,'%') "
	),
	array (
		"name" => "returnManId",
		"sql" => "and c.returnManId like CONCAT('%',#,'%') "
	),
	array (
		"name" => "remark",
		"sql" => "and c.remark   like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus  like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaDT",
		"sql" => "and c.ExaDT = # "
	),
	array (
		"name" => "createName",
		"sql" => "and c.createName in(arr) "
	),
	array (
		"name" => "createId",
		"sql" => "and c.createId = # "
	),
	array (
		"name" => "createTime",
		"sql" => "and c.createTime = # "
	),
	array (
		"name" => "updateName",
		"sql" => "and c.updateName = # "
	),
	array (
		"name" => "updateId",
		"sql" => "and c.updateId = # "
	),
	array (
		"name" => "updateTime",
		"sql" => "and c.updateTime = # "
	),
   //自定义条件
   array(
		"name" => "agencyCondition",
		"sql" => "$"
	)
);
?>
