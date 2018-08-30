<?php
/**
 * @author show
 * @Date 2014年5月4日 15:21:04
 * @version 1.0
 * @description:不合格物料明细 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.mainId ,c.objId ,c.objCode ,c.objType ,c.objItemId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.serialNo ,c.result ,c.resultName ,c.level ,c.levelName ,c.remark ,c.result1 ,c.result2 ,c.result3 ,c.result4 ,c.result5 ,c.result6 ,c.result7 ,c.result8 ,c.result9 ,c.result10  from oa_produce_quality_ereportfailureitem c where 1=1 ",
    "for_compensate" => "select c.id as qualityequId,c.productId ,c.productCode as productNo,c.productName ,c.pattern as productModel,c.unitName ,
            c.serialNo as serialNos,1 as number,c.objItemId as returnequId
        from oa_produce_quality_ereportfailureitem c where 1 "
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
        "name" => "isCompensated",
        "sql" => " and c.isCompensated=# "
    ),
    array(
        "name" => "objId",
        "sql" => " and c.objId=# "
    ),
    array(
        "name" => "objCode",
        "sql" => " and c.objCode=# "
    ),
    array(
        "name" => "objType",
        "sql" => " and c.objType=# "
    ),
    array(
        "name" => "objItemId",
        "sql" => " and c.objItemId=# "
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
        "name" => "unitName",
        "sql" => " and c.unitName=# "
    ),
    array(
        "name" => "serialNo",
        "sql" => " and c.serialNo=# "
    ),
    array(
        "name" => "result",
        "sql" => " and c.result=# "
    ),
    array(
        "name" => "resultName",
        "sql" => " and c.resultName=# "
    ),
    array(
        "name" => "level",
        "sql" => " and c.level=# "
    ),
    array(
        "name" => "levelName",
        "sql" => " and c.levelName=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
    ),
    array(
        "name" => "result1",
        "sql" => " and c.result1=# "
    ),
    array(
        "name" => "result2",
        "sql" => " and c.result2=# "
    ),
    array(
        "name" => "result3",
        "sql" => " and c.result3=# "
    ),
    array(
        "name" => "result4",
        "sql" => " and c.result4=# "
    ),
    array(
        "name" => "result5",
        "sql" => " and c.result5=# "
    ),
    array(
        "name" => "result6",
        "sql" => " and c.result6=# "
    ),
    array(
        "name" => "result7",
        "sql" => " and c.result7=# "
    ),
    array(
        "name" => "result8",
        "sql" => " and c.result8=# "
    ),
    array(
        "name" => "result9",
        "sql" => " and c.result9=# "
    ),
    array(
        "name" => "result10",
        "sql" => " and c.result10=# "
    )
);