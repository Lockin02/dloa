<?php
/**
 * @author Administrator
 * @Date 2011年5月11日 0:26:52
 * @version 1.0
 * @description:发货单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.contractType,c.contractTypeName,c.id ,c.shipCode ,c.planId ,c.planCode ,c.docType ,c.docId ,c.docCode ,c.docStatus ,c.customerContCode ,c.shipType ,c.customerId ,c.customerName ,c.address ,c.linkman ,c.mobil ,c.postCode ,c.remark ,c.outstockmanId ,c.outstockman ,c.shipmanId ,c.shipman ,c.auditmanId ,c.auditman ,c.shipDate ,c.mailCode ,c.isMail ,c.isSign ,c.signman ,c.createTime ,c.createName ,c.createId ,c.updateName ,c.updateTime ,c.updateId ,c.signDate ,c.ext1 ,c.ext2 ,c.ext3 ,c.shipStatus ,c.rObjCode from oa_stock_ship c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "contractType",
   		"sql" => " and c.contractType=# "
        ),
	array(
   		"name" => "contractTypeName",
   		"sql" => " and c.contractTypeName=# "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "shipCode",
   		"sql" => " and c.shipCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "planId",
   		"sql" => " and c.planId=# "
   	  ),
   array(
   		"name" => "planCode",
   		"sql" => " and c.planCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "docType",
   		"sql" => " and c.docType=# "
   	  ),
   array(
   		"name" => "docId",
   		"sql" => " and c.docId=# "
   	  ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerContCode",
   		"sql" => " and c.customerContCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "shipType",
   		"sql" => " and c.shipType=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "linkman",
   		"sql" => " and c.linkman=# "
   	  ),
   array(
   		"name" => "mobil",
   		"sql" => " and c.mobil=# "
   	  ),
   array(
   		"name" => "postCode",
   		"sql" => " and c.postCode=# "
   	  ),
   array(
   		"name" => "itemRemark",
   		"sql" => " and  c.id in(select i.mainId from oa_stock_ship_product i where i.remark like CONCAT('%',#,'%')) "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "outstockmanId",
   		"sql" => " and c.outstockmanId=# "
   	  ),
   array(
   		"name" => "outstockman",
   		"sql" => " and c.outstockman=# "
   	  ),
   array(
   		"name" => "shipmanId",
   		"sql" => " and c.shipmanId=# "
   	  ),
   array(
   		"name" => "shipman",
   		"sql" => " and c.shipman=# "
   	  ),
   array(
   		"name" => "auditmanId",
   		"sql" => " and c.auditmanId=# "
   	  ),
   array(
   		"name" => "auditman",
   		"sql" => " and c.auditman=# "
   	  ),
   array(
   		"name" => "shipDate",
   		"sql" => " and c.shipDate=# "
   	  ),
   array(
   		"name" => "mailCode",
   		"sql" => " and c.mailCode=# "
   	  ),
   array(
   		"name" => "isMail",
   		"sql" => " and c.isMail=# "
   	  ),
   array(
   		"name" => "isSign",
   		"sql" => " and c.isSign=# "
   	  ),
   array(
   		"name" => "signman",
   		"sql" => " and c.signman=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "signDate",
   		"sql" => " and c.signDate=# "
   	  ),
   array(
   		"name" => "ext1",
   		"sql" => " and c.ext1=# "
   	  ),
   array(
   		"name" => "ext2",
   		"sql" => " and c.ext2=# "
   	  ),
   array(
   		"name" => "ext3",
   		"sql" => " and c.ext3=# "
   	  ),
   array(
   		"name" => "shipStatus",
   		"sql" => " and c.shipStatus=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode like CONCAT('%',#,'%') "
   	  )
)
?>