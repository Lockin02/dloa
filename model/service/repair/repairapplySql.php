<?php
/**
 * @author huangzf
 * @Date 2011年12月2日 9:39:07
 * @version 1.0
 * @description:维修申请单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.docDate ,c.customerId ,c.customerName ,c.contactUserId ,c.contactUserName ,c.telephone ,c.adress ,c.applyUserName ,c.applyUserCode ,c.saleUserIdea ,c.subCost ,c.subReduceCost ,c.deliveryDocCode,c.docStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.contractId,c.contractCode,c.contractName,c.prov from oa_service_repair_apply c where 1=1 ",
		 "select_export"=>"select c.id ,c.docCode ,c.docDate ,c.customerId ,c.customerName ,c.contactUserId ,c.contactUserName ,c.telephone ,c.adress ,c.applyUserName ,c.applyUserCode ,c.saleUserIdea ,c.subCost ,c.subReduceCost ,c.deliveryDocCode,c.docStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.contractId,c.contractCode,c.contractName,
		 							ai.productCode,ai.productName,ai.pattern,ai.serilnoName,ai.fittings ,ai.checkInfo from oa_service_repair_apply c right join oa_service_repair_applyitem ai on(ai.mainId=c.id) where 1=1 "
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
   		"name" => "docDate",
   		"sql" => " and c.docDate=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "deliveryDocCode",
   		"sql" => " and c.deliveryDocCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contactUserId",
   		"sql" => " and c.contactUserId=# "
   	  ),
   array(
   		"name" => "contactUserName",
   		"sql" => " and c.contactUserName=# "
   	  ),
   array(
   		"name" => "telephone",
   		"sql" => " and c.telephone=# "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "applyUserName",
   		"sql" => " and c.applyUserName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "applyUserCode",
   		"sql" => " and c.applyUserCode=# "
   	  ),
   array(
   		"name" => "saleUserIdea",
   		"sql" => " and c.saleUserIdea=# "
   	  ),
   array(
   		"name" => "subCost",
   		"sql" => " and c.subCost=# "
   	  ),
   array(
   		"name" => "subReduceCost",
   		"sql" => " and c.subReduceCost=# "
   	  ),
   array(
   		"name" => "docStatus",
   		"sql" => " and c.docStatus=# "
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
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.id in( select mainId from oa_service_repair_applyitem where productId =#)"
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.id in( select mainId from oa_service_repair_applyitem where productCode like CONCAT('%',#,'%'))   "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.id in( select mainId from oa_service_repair_applyitem where productName like CONCAT('%',#,'%'))   "
   	  ),
   array(
   		"name" => "serilnoName",
   		"sql" => " and c.id in( select mainId from oa_service_repair_applyitem where serilnoName like CONCAT('%',#,'%'))   "
   	  ),
   array(
		"name" => "prov",
		"sql" => " and c.prov like CONCAT('%',#,'%') "
	 )
)
?>