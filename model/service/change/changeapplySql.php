<?php
/**
 * @author Administrator
 * @Date 2011年12月3日 10:33:49
 * @version 1.0
 * @description:设备更换申请单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.relDocId ,c.rObjCode ,c.relDocCode ,c.relDocName ,c.relDocType ,c.customerId ,c.customerName ,c.adress ,c.applyUserName ,c.applyUserCode ,c.ExaStatus ,c.ExaDT ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_service_change_apply c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocCode",
   		"sql" => " and c.relDocCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocName",
   		"sql" => " and c.relDocName  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocType",
   		"sql" => " and c.relDocType  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "applyUserName",
   		"sql" => " and c.applyUserName=# "
   	  ),
   array(
   		"name" => "applyUserCode",
   		"sql" => " and c.applyUserCode=# "
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
   	  ),
  array(
   		"name" => "productCode",
   		"sql" => " and c.id in( select mainId from oa_service_change_item  where productCode like CONCAT('%',#,'%'))   "
   	  ),
  array(
   		"name" => "productName",
   		"sql" => " and c.id in( select mainId from oa_service_change_item  where productName like CONCAT('%',#,'%'))   "
   	  )
)
?>