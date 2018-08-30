<?php
/**
 * @author Administrator
 * @Date 2011年5月11日 9:32:47
 * @version 1.0
 * @description:发货单详细清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.planEquId,c.mainId ,c.docType ,c.docId ,c.docCode ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.contNum ,c.number ,c.unitName ,c.aidUnit ,c.converRate ,c.stockId ,c.stockCode ,c.stockName ,c.shelfLife ,c.prodDate ,c.validDate ,c.remark from oa_stock_ship_product c where 1=1 "
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
   		"name" => "mainCode",
   		"sql" => " and c.mainCode=# "
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
   		"sql" => " and c.docCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "planEquId",
   		"sql" => " and c.planEquId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
   	  ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "contNum",
   		"sql" => " and c.contNum=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
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
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName=# "
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
   	  )
)
?>