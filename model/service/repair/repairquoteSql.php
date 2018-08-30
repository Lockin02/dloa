<?php
/**
 * @author huangzf
 * @Date 2011年12月3日 10:11:04
 * @version 1.0
 * @description:维修报价申报单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.chargeUserCode ,c.chargeUserName ,c.docDate ,c.ExaStatus ,c.ExaDT ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_service_repair_quote c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "chargeUserCode",
   		"sql" => " and c.chargeUserCode=# "
   	  ),
   array(
   		"name" => "chargeUserName",
   		"sql" => " and c.chargeUserName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "docDate",
   		"sql" => " and c.docDate=# "
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
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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