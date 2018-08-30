<?php
/**
 * 资产采购任务sql
 * @author fengxw
 */
$sql_arr = array (
	"select_task" => "select c.id,c.formCode,c.sendTime,c.sendId,c.sendName,c.purcherId,c.purcherName,c.userTel,c.remark,c.acceptDate,c.endDate,c.arrivaDate,c.state,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime from oa_asset_purchase_task c where  1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id =# "
        ),
	array(
   		"name" => "formCode",
   		"sql" => " and c.formCode like CONCAT('%',#,'%')"
        ),
	array(
   		"name" => "sendTime",
   		"sql" => " and c.sendTime like CONCAT('%',#,'%')"
        ),
    array(
   		"name" => "sendId",
   		"sql" => " and c.sendId =# "
   	  ),
   array(
   		"name" => "sendName",
   		"sql" => " and c.sendName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "purcherId",
   		"sql" => " and c.purcherId =# "
   	  ),
   array(
   		"name" => "purcherName",
   		"sql" => " and c.purcherName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "userTel",
   		"sql" => " and c.userTel like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "acceptDate",
   		"sql" => " and c.acceptDate like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "arrivaDate",
   		"sql" => " and c.arrivaDate like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
    array(
   		"name" => "createTime",
   		"sql" => " and c.createTime like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "updateName",
   		"sql" => " and c.updateName like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
    array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime like CONCAT('%',#,'%')"
   	  ),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select parentId from oa_asset_purchase_task_item where productName like CONCAT('%',#,'%'))"
	)
)
?>
