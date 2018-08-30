<?php
/**
 * @author suxc
 * @Date 2011年5月6日 9:52:24
 * @version 1.0
 * @description:退料通知单信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.returnCode ,c.returnType ,c.sourceId ,c.sourceCode ,c.supplierName ,c.supplierId ,c.remark ,c.purchManId ,c.purchManName ,c.deliveryPlace ,c.purchMode ,c.stockId ,c.stockName ,c.returnDate ,c.state ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.ExaStatus,c.ExaDT  from oa_purchase_delivered c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "returnCode",
   		"sql" => " and c.returnCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "returnType",
   		"sql" => " and c.returnType=# "
   	  ),
   array(
   		"name" => "sourceId",
   		"sql" => " and c.sourceId=# "
   	  ),
   array(
   		"name" => "sourceCode",
   		"sql" => " and c.sourceCode=# "
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "purchManId",
   		"sql" => " and c.purchManId=# "
   	  ),
   array(
   		"name" => "purchManName",
   		"sql" => " and c.purchManName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "deliveryPlace",
   		"sql" => " and c.deliveryPlace=# "
   	  ),
   array(
   		"name" => "purchMode",
   		"sql" => " and c.purchMode=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName=# "
   	  ),
   array(
   		"name" => "returnDate",
   		"sql" => " and c.returnDate=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
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
		"name" => "productNumb",
		"sql" => "and c.id in(select basicId from oa_purchase_delivered_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select basicId from oa_purchase_delivered_equ where productName like CONCAT('%',#,'%'))"
	)
)
?>