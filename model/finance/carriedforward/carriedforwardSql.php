<?php
/**
 * @author Show
 * @Date 2011年8月8日 星期一 11:00:07
 * @version 1.0
 * @description:合同结转表 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.saleId ,c.saleCode ,c.saleType ,c.rObjCode,c.invoiceId ,c.outStockId ,c.outStockCode ,c.outStockType ,c.outStockDetailId ,c.carryRate ,c.thisDate ,c.status ,c.createId ,c.createName  from oa_finance_carriedforward c where 1=1 ",
    "selectJoin" => "select
				c.id ,c.saleId ,c.saleCode ,c.saleType,c.rObjCode ,c.periodNo,c.outStockId ,c.outStockCode ,c.outStockType ,c.outStockDetailId ,c.carryRate ,c.thisDate ,c.status ,c.createId ,c.createName ,c.carryType,
				i.subCost,i.customerName,
				round(( i.subCost * c.carryRate /100 ),2) as carryMoney,
				i.productName,i.productCode,
				d.productModel,d.invoiceNo
				from oa_finance_carriedforward c
					left join
						(select i.invoiceNo,d.productModel,d.id as invoiceDetailId from oa_finance_invoice i left join oa_finance_invoice_detail d on i.id = d.invoiceId ) d on d.invoiceDetailId = c.invoiceDetailId
					left join
					(select
						c.id ,c.docCode ,c.docType ,c.isRed ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.customerName,i.id as outStockDetailId,i.subCost,i.productName,i.productCode
					from oa_stock_outstock c left join oa_stock_outstock_item i on c.id = i.mainId where 1=1) i
				on c.outStockDetailId = i.outStockDetailId where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
    array(
   		"name" => "saleId",
   		"sql" => " and c.saleId=# "
    ),
    array(
   		"name" => "saleCode",
   		"sql" => " and c.saleCode=# "
    ),
    array(
   		"name" => "saleCodeSearch",
   		"sql" => " and c.saleCode like concat('%',#,'%')  "
    ),
    array(
   		"name" => "saleType",
   		"sql" => " and c.saleType=# "
    ),
    array(
   		"name" => "invoiceId",
   		"sql" => " and c.invoiceId=# "
    ),
    array(
   		"name" => "outStockId",
   		"sql" => " and c.outStockId=# "
    ),
    array(
   		"name" => "outStockCode",
   		"sql" => " and c.outStockCode=# "
    ),
    array(
   		"name" => "outStockCodeSearch",
   		"sql" => " and c.outStockCode like concat('%',#,'%') "
    ),
    array(
   		"name" => "outStockDetailId",
   		"sql" => " and c.outStockDetailId=# "
    ),
    array(
   		"name" => "thisDate",
   		"sql" => " and c.thisDate=# "
    ),
    array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
    array(
   		"name" => "carryType",
   		"sql" => " and c.carryType=# "
    ),
    array(
   		"name" => "customerName",
   		"sql" => " and i.customerName like concat('%',#,'%') "
    )
)
?>