<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:37:07
 * @version 1.0
 * @description:盘点基本信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.checkType ,c.stockId ,c.stockCode ,c.stockName ,c.auditStatus  ,c.dealUserId ,c.dealUserName ,c.auditUserName ,c.auditUserId ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_check_info c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "checkType",
   		"sql" => " and c.checkType=# "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "auditStatus",
   		"sql" => " and c.auditStatus=# "
   	  ),
   array(
   		"name" => "dealUserId",
   		"sql" => " and c.dealUserId=# "
   	  ),
   array(
   		"name" => "dealUserName",
   		"sql" => " and c.dealUserName=# "
   	  ),
   array(
   		"name" => "auditUserName",
   		"sql" => " and c.auditUserName=# "
   	  ),
   array(
   		"name" => "auditUserId",
   		"sql" => " and c.auditUserId=# "
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
)
?>