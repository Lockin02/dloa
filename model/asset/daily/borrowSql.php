<?php
$sql_arr = array ("select_borrow" => "select c.agencyCode,c.agencyName,c.isSign,c.signDate,c.reposeDeptId,c.reposeDeptName,c.requireId,c.requireCode," .
		"c.docStatus,c.id,c.billNo,c.borrowDate,c.predictDate,c.deptId,c.deptName,c.borrowCustomeId,c.borrowCustome," .
		"c.chargeManId,c.chargeMan,c.reposeManId,c.reposeMan,c.remark,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ," .
		"c.createTime ,c.updateName ,c.updateId ,c.updateTime " .
		"from oa_asset_borrow c where 1=1 " );
$condition_arr = array (
    array (
        "name" => "agencyCode",
        "sql" => "and c.agencyCode = #"
    ),
	array (
		"name" => "agencyName",
		"sql" => "and c.agencyName CONCAT('%',#,'%')"
	),
   array(// 物料名称
		"name" => "productName",
		"sql" => " and  c.id in(select i.borrowId from oa_asset_borrowitem i where i.assetName like CONCAT('%',#,'%')) "
	),
	array(// 物料代码
		"name" => "productCode",
		"sql" => " and  c.id in(select i.borrowId from oa_asset_borrowitem i where i.assetCode like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "isSign",
		"sql" => "and c.isSign=#"
	),
	array (
		"name" => "reposeDeptId",
		"sql" => "and c.reposeDeptId=#"
	),
	array (
		"name" => "requireId",
		"sql" => "and c.requireId=#"
	),
	array (
		"name" => "requireCode",
		"sql" => "and c.requireCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array (
		"name" => "unDocStatus",
		"sql" => "and c.docStatus<>#"
	),
	array (
		"name" => "docStatus",
		"sql" => "and c.docStatus=#"
	),
	array (
		"name" => "billNo",
		"sql" => "and c.billNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "billNoEq",
		"sql" => "and c.billNo = #"
	),
	array (
		"name" => "borrowDate",
		"sql" => "and c.borrowDate like CONCAT('%',#,'%')"
	),
	array(
		"name" => "predictDate",
		"sql" => " and c.predictDate = #"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId =#"
	),
	array (
		"name" => "deptName",
		"sql" => "and c.deptName =#"
	),
	array(
		"name" => "borrowCustomeId",
		"sql" => " and c.borrowCustomeId = # "
	),
	array(
		"name" => "borrowCustome",
		"sql" => " and  c.borrowCustome  like CONCAT('%',#,'%')"
	),array (
		"name" => "chargeManId",
		"sql" => "and c.chargeManId =#"
	),
	array (
		"name" => "chargeMan",
		"sql" => "and c.chargeMan =#"
	),array(
		"name" => "reposeManId",
		"sql" => " and c.reposeManId = #"
	),
	array(
		"name" => "reposeMan",
		"sql" => " and c.reposeMan = #"
	),  array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and (c.createId=# or c.reposeManId=#)"
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
);
?>
