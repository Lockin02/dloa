<?php
/**
 * 资产采购任务明细sql
 * @author fengxw
 */
$sql_arr = array (
	"select_taskItem" => "select c.id,c.parentId,c.taskCode,c.applyId,c.applyCode,c.applyEquId,c.productCode,c.productId,c.productName,c.pattem,c.unitName,c.supplierId,c.supplierName,c.purchAmount,c.taskAmount,c.issuedAmount,c.price,c.taxRate,c.taxPrice,c.tax,c.moneyAll,c.dateHope,c.remark from oa_asset_purchase_task_item c where  1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id =# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId =# "
   	  ),
   array(
   		"name" => "taskCode",
   		"sql" => " and c.taskCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId =# "
   	  ),
   array(
   		"name" => "applyCode",
   		"sql" => " and c.applyCode like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "applyEquId",
   		"sql" => " and c.applyEquId like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId =# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "pattem",
   		"sql" => " and c.pattem like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "purchAmount",
   		"sql" => " and c.purchAmount like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "taskAmount",
   		"sql" => " and c.taskAmount like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "issuedAmount",
   		"sql" => " and c.issuedAmount like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "taxRate",
   		"sql" => " and c.taxRate like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "taxPrice",
   		"sql" => " and c.taxPrice like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "tax",
   		"sql" => " and c.tax like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "moneyAll",
   		"sql" => " and c.moneyAll like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "dateHope",
   		"sql" => " and c.dateHope =# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  )
)
?>