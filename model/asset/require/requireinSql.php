<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.billNo ,c.requireId ,c.requireCode ,c.applyDate ,c.applyId ,c.applyName ,c.applyDeptId ,c.applyDeptName ,
			c.outStockStatus ,c.receiveStatus ,c.status ,c.remark ,c.backReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,
			c.confirmId ,c.confirmName ,c.confirmTime ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName 
		from oa_asset_requirein c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
	),
    array(
   		"name" => "billNo",
   		"sql" => " and c.billNo like CONCAT('%',#,'%') "
   	),
	array(
		"name" => "requireId",
		"sql" => " and c.requireId=# "
	),
	array(
		"name" => "requireCode",
		"sql" => " and c.requireCode like CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	),
   array(
   		"name" => "applyName",
   		"sql" => " and c.applyName like CONCAT('%',#,'%') "
   	),
   array(
   		"name" => "applyDeptId",
   		"sql" => " and c.applyDeptId=# "
   	),
   array(
   		"name" => "applyDeptName",
   		"sql" => " and c.applyDeptName like CONCAT('%',#,'%') "
   	),
	array(
		"name" => "outStockStatus",
		"sql" => " and c.outStockStatus=# "
	),
	array(
		"name" => "outStockStatusArr",
		"sql" => " and c.outStockStatus in(arr) "
	),
	array(
		"name" => "receiveStatus",
		"sql" => " and c.receiveStatus=# "
	),
	array(
		"name" => "receiveStatusArr",
		"sql" => " and c.receiveStatus in(arr) "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "statusArr",
		"sql" => " and c.status in(arr)  "
	),
	array(
		"name" => "statusNotArr",
		"sql" => " and c.status not in(arr)  "
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
   	)
)
?>