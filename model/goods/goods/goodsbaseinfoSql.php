<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:16:27
 * @version 1.0
 * @description:产品基本信息 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.goodsTypeId ,c.goodsTypeName ,c.goodsCode ,c.goodsName ,c.unitName ,c.version ,c.useStatus ,c.isEncrypt,c.isMature,c.remark ,c.auditDeptName ,c.auditDeptCode ,c.exeDeptName ,c.exeDeptCode ,c.description ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,goodsClass,c.osGoodsName  from oa_goods_base_info c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "idArr",
        "sql" => " and c.id in(arr)"
    ),
    array(
        "name" => "goodsTypeId",
        "sql" => " and c.goodsTypeId=# "
    ),
    array(
        "name" => "goodsTypeName",
        "sql" => " and c.goodsTypeName=# "
    ),
    array(
        "name" => "goodsCode",
        "sql" => " and c.goodsCode=# "
    ),
    array(
        "name" => "goodsName",
        "sql" => " and c.goodsName like CONCAT('%',#,'%')"
    ),
	array(
		"name" => "goodsNameEq",
		"sql" => " and c.goodsName=# "
	),
    array(
        "name" => "osGoodsName",
        "sql" => " and c.osGoodsName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "unitName",
        "sql" => " and c.unitName=# "
    ),
    array(
        "name" => "version",
        "sql" => " and c.version=# "
    ),
    array(
        "name" => "useStatus",
        "sql" => " and c.useStatus=# "
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
    ),
    array(
        "name" => "exeDeptCode",
        "sql" => " and c.exeDeptCode=# "
    ),
    array(
        "name" => "productCode",
        "sql" => " and c.id in( select mainId from oa_goods_properties where id in (select mainId from oa_goods_properties_item where status = 'ZC' and productCode like CONCAT('%',#,'%'))) "
    ),
    array(
        "name" => "productName",
        "sql" => " and c.id in( select mainId from oa_goods_properties where id in (select mainId from oa_goods_properties_item where status = 'ZC' and productName like CONCAT('%',#,'%'))) "
    )
);