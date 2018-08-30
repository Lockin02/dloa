<?php
/**
 * @author Administrator
 * @Date 2011年6月19日 14:44:33
 * @version 1.0
 * @description:出库单物料清单 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.contractId ,c.contractCode ,
            c.contractName ,c.relDocId ,c.relDocCode ,c.relDocName ,c.pattern ,c.unitName ,c.aidUnit ,c.converRate ,c.batchNum ,
            c.cost ,c.subCost ,c.salecost ,c.saleSubCost ,c.stockId ,c.stockCode ,c.stockName ,c.shouldOutNum ,c.actOutNum ,
            c.shelfLife ,c.prodDate ,c.validDate ,c.costCenter ,c.positionNo ,c.feedstockSize ,c.feedstockNum ,c.purchaseCode ,
            c.purchaseId ,c.serialnoName ,c.serialnoId ,c.remark, c.k3Code,c.proType,c.proTypeId
        from oa_stock_outstock_item c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.mainId=# "
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
        "sql" => " and c.productName=# "
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
        "name" => "salecost",
        "sql" => " and c.salecost=# "
    ),
    array(
        "name" => "saleSubCost",
        "sql" => " and c.saleSubCost=# "
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
        "name" => "shouldOutNum",
        "sql" => " and c.shouldOutNum=# "
    ),
    array(
        "name" => "actOutNum",
        "sql" => " and c.actOutNum=# "
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
        "name" => "costCenter",
        "sql" => " and c.costCenter=# "
    ),
    array(
        "name" => "positionNo",
        "sql" => " and c.positionNo=# "
    ),
    array(
        "name" => "feedstockSize",
        "sql" => " and c.feedstockSize=# "
    ),
    array(
        "name" => "feedstockNum",
        "sql" => " and c.feedstockNum=# "
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
        "name" => "remark",
        "sql" => " and c.remark=# "
    )
);