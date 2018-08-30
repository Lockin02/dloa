<?php
/**
 * @author Administrator
 * @Date 2013年7月8日 14:46:37
 * @version 1.0
 * @description:关联出现条件 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id,c.mainId,c.mainName,c.balanceCode,c.billDate,c.billMoney,c.billCode,c.billTypeName,c.billContent,c.swfupload,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime from oa_flights_balance_bill c where 1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "mainName",
   		"sql" => " and c.mainName=# "
   	  ),
   array(
   		"name" => "balanceCode",
   		"sql" => " and c.balanceCode=# "
   	  ),
   array(
   		"name" => "billDate",
   		"sql" => " and c.billDate=# "
   	  ),
   array(
   		"name" => "billMoney",
   		"sql" => " and c.billMoney=# "
   	  ),
   array(
   		"name" => "billCode",
   		"sql" => " and c.billCode=# "
   	  ),
   array(
   		"name" => "billTypeName",
   		"sql" => " and c.billTypeName=# "
   	  ),
   array(
   		"name" => "billContent",
   		"sql" => " and c.billContent=# "
   	  ),
   array(
   		"name" => "billContent",
   		"sql" => " and c.billContent=# "
   	  ),
   array(
   		"name" => "swfupload",
   		"sql" => " and c.swfupload=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>