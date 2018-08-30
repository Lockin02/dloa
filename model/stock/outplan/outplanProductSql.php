<?php
/**
 * @author Administrator
 * @Date 2011年5月11日 9:50:17
 * @version 1.0
 * @description:发货通知单物料清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.BToOTips,c.id ,c.mainId ,c.docType ,c.docId ,c.docCode ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.number ,c.lockNum ,c.executedNum ,c.unitName ,c.aidUnit ,c.converRate ,c.stockId ,c.stockCode ,c.stockName ,c.shelfLife ,c.prodDate ,c.validDate ,c.isDelete  from oa_stock_outplan_product c where 1=1 ",
         "select_product"=>"select c.BToOTips,c.id ,c.mainId ,c.docType ,c.docId ,c.docCode ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.number ,c.lockNum,c.changeTips,
         		c.executedNum ,c.unitName ,c.aidUnit ,c.converRate ,c.stockId ,c.stockCode ,c.stockName ,c.shelfLife ,c.prodDate ,c.validDate ,c.isDelete,c.productLineName,c.changeTips,sum(s.number) as shipNum
         			 from oa_stock_outplan_product c left join oa_stock_ship_product s on c.mainId=s.planId and c.id=s.planEquId and c.productId=s.productId where 1=1"
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
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "lockNum",
   		"sql" => " and c.lockNum=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
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
   	  ),
   array(
   		"name" => "isDelete",
   		"sql" => " and c.isDelete=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "BToOTips",
   		"sql" => " and c.BToOTips=# "
   	  )
)
?>