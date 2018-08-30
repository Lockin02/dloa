<?php
/**
 * @author huangzf
 * @Date 2011年6月19日 10:00:24
 * @version 1.0
 * @description:调拨单物料清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.relDocId ,c.relDocCode ,c.relDocName ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.aidUnit ,c.converRate ,c.batchNum ,c.cost ,c.subCost ,c.allocatNum ,c.shelfLife ,c.prodDate ,c.validDate ,c.exportStockId ,c.exportStockCode ,c.exportStockName ,c.importStockId ,c.importStockCode ,c.importStockName ,c.purchaseCode ,c.purchaseId ,c.serialnoName ,c.serialnoId ,c.remark ,c.k3Code  from oa_stock_allocation_item c where 1=1 ",
		 "select_back"=>"select c.id,
						       m.outEndDate ,
						       c.productId,
						       c.productCode,
						       c.productName,
						       c.pattern,
						       c.unitName,
						       c.batchNum,
						       c.cost,
						       c.subCost,
						       c.allocatNum,
						       c.serialnoName,
						       c.serialnoId,
						       c.remark
							from oa_stock_allocation_item c right join oa_stock_allocation m on(m.id=c.mainId)
							where m.toUse='CHUKUGUIH' "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "relDocCode",
   		"sql" => " and c.relDocCode=# "
   	  ),
   array(
   		"name" => "relDocName",
   		"sql" => " and c.relDocName=# "
   	  ),   	  
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "aidUnit",
   		"sql" => " and c.aidUnit=# "
   	  ),
   array(
   		"name" => "converRate",
   		"sql" => " and c.converRate=# "
   	  ),
   array(
   		"name" => "batchNum",
   		"sql" => " and c.batchNum=# "
   	  ),
   array(
   		"name" => "cost",
   		"sql" => " and c.cost=# "
   	  ),
   array(
   		"name" => "subCost",
   		"sql" => " and c.subCost=# "
   	  ),
   array(
   		"name" => "allocatNum",
   		"sql" => " and c.allocatNum=# "
   	  ),
   array(
   		"name" => "shelfLife",
   		"sql" => " and c.shelfLife=# "
   	  ),
   array(
   		"name" => "prodDate",
   		"sql" => " and c.prodDate=# "
   	  ),
   array(
   		"name" => "validDate",
   		"sql" => " and c.validDate=# "
   	  ),
   array(
   		"name" => "exportStockId",
   		"sql" => " and c.exportStockId=# "
   	  ),
   array(
   		"name" => "exportStockCode",
   		"sql" => " and c.exportStockCode=# "
   	  ),
   array(
   		"name" => "exportStockName",
   		"sql" => " and c.exportStockName=# "
   	  ),
   array(
   		"name" => "importStockId",
   		"sql" => " and c.importStockId=# "
   	  ),
   array(
   		"name" => "importStockCode",
   		"sql" => " and c.importStockCode=# "
   	  ),
   array(
   		"name" => "importStockName",
   		"sql" => " and c.importStockName=# "
   	  ),
   array(
   		"name" => "purchaseCode",
   		"sql" => " and c.purchaseCode=# "
   	  ),
   array(
   		"name" => "purchaseId",
   		"sql" => " and c.purchaseId=# "
   	  ),
   array(
   		"name" => "serialnoName",
   		"sql" => " and c.serialnoName=# "
   	  ),
   array(
   		"name" => "serialnoId",
   		"sql" => " and c.serialnoId=# "
   	  ),
   	array(
   		"name" => "relDocId",
   		"sql" => " and m.relDocId=# "
   	  ),
   	 array(
   	 		"name" => "relDocIdIn",
   			"sql" => " and m.relDocId in($) "
   	 ), 
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name"=>"relDocType",
   		"sql"=>" and EXISTS  (select 1 from oa_stock_allocation a where a.id=c.mainId and a.relDocType=# and a.toUse='CHUKUGUIH' and a.docStatus='YSH' )"
   )	  
)
?>