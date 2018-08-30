<?php
/**
 * @author huangzf
 * @Date 2011年5月12日 15:05:22
 * @version 1.0
 * @description:入库单物料清单 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.inStockId ,
            c.inStockCode ,c.inStockName ,c.relDocId ,c.relDocCode ,c.relDocName ,c.contractId ,c.contractCode ,c.contractName ,
            c.unitName ,c.aidUnit ,c.converRate ,c.batchNum ,c.price ,c.subPrice ,c.storageNum ,c.actNum ,c.shelfLife ,
            c.prodDate ,c.validDate ,c.purchaseCode ,c.purchaseId ,c.serialnoName ,c.serialnoId ,c.hookNumber ,
            c.hookAmount ,c.unHookNumber ,c.unHookAmount ,c.remark,c.warranty,c.k3Code,c.proType,c.proTypeId
        from oa_stock_instock_item c where 1=1 "
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
        "name" => "pattern",
        "sql" => " and c.pattern=# "
    ),
    array(
        "name" => "inStockId",
        "sql" => " and c.inStockId=# "
    ),
    array(
        "name" => "inStockCode",
        "sql" => " and c.inStockCode=# "
    ),
    array(
        "name" => "inStockName",
        "sql" => " and c.inStockName=# "
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
        "name" => "price",
        "sql" => " and c.price=# "
    ),
    array(
        "name" => "subPrice",
        "sql" => " and c.subPrice=# "
    ),
    array(
        "name" => "storageNum",
        "sql" => " and c.storageNum=# "
    ),
    array(
        "name" => "actNum",
        "sql" => " and c.actNum=# "
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
        "name" => "hookNumber",
        "sql" => " and c.hookNumber=# "
    ),
    array(
        "name" => "hookAmount",
        "sql" => " and c.hookAmount=# "
    ),
    array(
        "name" => "unHookNumber",
        "sql" => " and c.unHookNumber=# "
    ),
    array(
        "name" => "unHookAmount",
        "sql" => " and c.unHookAmount=# "
    )
);