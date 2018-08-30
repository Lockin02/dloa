<?php
/**
 * 资产采购申请明细sql
 * @author fengxw
 */
$sql_arr = array (
	"select_applyItem" => "select c.description,c.amounts,c.id,c.applyId,c.applyCode,c.planEquId,c.productCode,c.productId,c.productName,c.pattem,c.unitName,c.supplierId,c.supplierName,c.applyAmount,c.issuedAmount,c.checkAmount,c.price,c.taxRate,c.taxPrice,c.tax,c.moneyAll,c.dateHope,c.checkDate,c.remark,c.purchDept,c.purchAmount,c.isDel,c.equUseYear ,c.planPrice ,c.isAsset,c.productCategoryName,c.productCategoryCode,c.inputProductName,suggestion,inquiryAmount,requireItemId from oa_asset_purchase_apply_item c where  1=1 ",
	"select_applyItem_confirm" => "select c.description,c.amounts,c.id,c.applyId,c.applyCode,c.planEquId,c.productCode as productNumb,c.productId,c.productName,c.pattem,c.unitName,c.supplierId,c.supplierName,c.applyAmount as amountAll,c.issuedAmount,c.checkAmount,c.price,c.taxRate,c.taxPrice,c.tax,c.moneyAll,c.dateHope,c.checkDate,c.remark,c.purchDept,c.purchAmount,c.isDel,c.equUseYear ,c.planPrice ,c.isAsset,c.productCategoryName,c.productCategoryCode,c.inputProductName from oa_asset_purchase_apply_item c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "idArr",
   		"sql" => " and c.id in(arr) "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "relDocId",
		"sql" => " and  c.applyId in(select i.id from oa_asset_purchase_apply i where i.relDocId=#) "
        ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "basicId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyCode",
   		"sql" => " and c.applyCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "applicantName",
   		"sql" => " and c.applicantName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "pattem",
   		"sql" => " and c.pattem like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "purchDept",
   		"sql" => " and c.purchDept =# "
   	  ),
   array(
   		"name" => "purchAmount",
   		"sql" => " and c.purchAmount =# "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel =# "
   	  ),
   array(
   		"name" => "isDelCon",
   		"sql" => "$"
   	  )
)
?>