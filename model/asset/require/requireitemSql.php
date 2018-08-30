<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 14:10:34
 * @version 1.0
 * @description:资产需求申请明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.productCode,c.productId,c.productName,c.productPrice,c.brand,c.spec,c.name,c.inquiryAmount,c.suggestion,c.id ,c.mainId ,c.description ,c.expectAmount ,c.number ,c.executedNum ,c.dateHope ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.purchDept ,c.purchAmount  from oa_asset_requireitem c where 1=1 ",
         "list_apply"=>"select c.productCode,c.productId,c.productName,c.productPrice,c.brand,c.spec,c.name as inputProductName,c.name,c.inquiryAmount,c.suggestion,c.id ,c.id as requireItemId,c.mainId ,c.description,c.expectAmount ,c.number as applyAmount,c.executedNum ,c.dateHope ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_asset_requireitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "name",
   		"sql" => " and c.name=# "
        ),
	array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  ),
   array(
   		"name" => "expectAmount",
   		"sql" => " and c.expectAmount=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	  ),
   array(
   		"name" => "dateHope",
   		"sql" => " and c.dateHope=# "
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