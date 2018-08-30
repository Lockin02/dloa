<?php
$sql_arr = array ("select_rent" => "select c.id,c.billNo,c.deptId,c.deptName,c.lesseeid,c.lesseeName," .
		"c.lessee,c.contractNo,c.rentNum,c.rentAmount,c.reason,c.applicatId,c.applicat,c.applicatDate,c.beginDate,c.endDate," .
		"c.remark,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ," .
		"c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_asset_rent c where 1=1 " );
$condition_arr = array (
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
		"sql" => "and c.billNo = #"
	),

	array(
		"name" => "deptId",
		"sql" => " and c.deptId = #"
	),
	array (
		"name" => "deptName",
		"sql" => "and c.deptName =#"
	),
	array (
		"name" => "lesseeid",
		"sql" => "and c.lesseeid =#"
	),
	array(
		"name" => "lesseeName",
		"sql" => " and c.lesseeName = # "
	),array(
		"name" => "lessee",
		"sql" => " and  c.lessee = #"
	),
	array(
		"name" => "contractNo",
		"sql" => " and  c.contractNo = #"
	),
	array (
		"name" => "rentNum",
		"sql" => "and c.rentNum =#"
	),
	array (
		"name" => "rentAmount",
		"sql" => "and c.rentAmount =#"
	),
	array (
		"name" => "reason",
		"sql" => "and c.reason =#"
	),
	array (
		"name" => "applicatId",
		"sql" => "and c.applicatId =#"
	),
	array (
		"name" => "applicat",
		"sql" => "and c.applicat =#"
	),
	array (
		"name" => "applicatDate",
		"sql" => "and c.applicatDate =#"
	),
	array (
		"name" => "endDate",
		"sql" => "and c.endDate =#"
	),

	  array(
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
   	  ),array(
   		"name" => "isSign",
   		"sql" => " and c.isSign=# "
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
);
?>
