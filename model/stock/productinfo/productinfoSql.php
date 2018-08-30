<?php
/**
 * @author huangzf
 * @Date 2011年5月7日 10:00:23
 * @version 1.0
 * @description:物料基本信息 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id ,c.proTypeId ,c.proType ,c.productCode ,c.productName ,c.pattern ,
			c.priCost ,c.unitName ,c.aidUnit ,c.converRate ,c.warranty ,c.arrivalPeriod ,c.accountingCode ,
			c.checkType ,c.properties ,c.supplier ,c.stockId ,c.stockName ,c.stockCode ,c.remark ,c.ext1 ,
			c.ext2 ,c.ext3 ,c.statType ,c.statTypeName ,c.encrypt ,c.allocation ,c.createName ,c.createId ,
			c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.changeProductId ,c.changeProductCode ,
			c.changeProductName ,c.closeReson ,c.leastPackNum ,c.leastOrderNum ,c.material ,c.brand ,c.color ,
			c.purchUserCode ,c.purchUserName ,c.purchPeriod,c.packageInfo,c.depreciation,c.priceLock,c.relProductId,
			c.relProductCode,c.relProductName,c.businessBelong,c.businessBelongName
		from oa_stock_product_info c where 1=1 ",
	"select_productandconf" => "select
			c.id ,c.proTypeId ,c.proType ,c.productCode ,c.productName ,c.pattern ,c.priCost ,c.unitName ,c.aidUnit ,c.converRate,c.allocation,c.warranty ,c.arrivalPeriod ,c.accountingCode ,c.checkType ,c.properties ,c.supplier ,c.stockId ,c.stockName,c.priceLock,
			c.stockCode ,c.remark ,c.ext1 ,c.ext2 ,c.ext3 ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.encrypt,c.statType,c.statTypeName,
			co.configId,co.hardWareId ,co.configType,co.configName ,co.configPattern,co.configCode ,co.configNum ,co.explains
		from
			oa_stock_product_info  c
			left join
			(
			select
				co.id as configId,co.hardWareId ,co.configType,co.configName ,co.configPattern,co.configCode ,co.configNum ,co.explains
			from
				oa_stock_product_configuration co
			where
				co.configType='proaccess'
			) co on(c.id=co.hardwareId)
			where 1=1 ",
	"select_esm" => "select c.id ,c.proTypeId ,c.proType ,c.productCode ,c.productName ,c.pattern ,c.unitName ,
			c.changeProductName,c.changeProductCode,c.purchPeriod,c.warranty,c.depreciation,
			IFNULL(a.inventoryAsset,0 ) as inventoryAsset,
			IFNULL(s.actNum,0 ) as inventoryStock
		from
			oa_stock_product_info c
			left join
			(select productId,sum(actNum) as actNum from oa_stock_inventory_info GROUP BY productId) s on c.id = s.productId
			left join
			(select productId,count(*) as inventoryAsset from oa_stock_inventory_info GROUP BY productId) a on c.id = a.productId
		where 1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "idArr",
		"sql" => "and c.id in(arr)"
	),
	array(
		"name" => "proTypeId",
		"sql" => " and c.proTypeId in(arr)"
	),
	array(
		"name" => "proType",
		"sql" => " and c.proType=# "
	),
	array(
		"name" => "productCode",
		"sql" => " and c.productCode like CONCAT('%',#,'%')"
	),
	array(
		"name" => "nproductCode",
		"sql" => " and c.productCode =#"
	),
	array(
		"name" => "yproductCode",
		"sql" => " and c.productCode =# "
	),
	array(
		"name" => "productCodeEq",
		"sql" => " and c.productCode =# "
	),
	array(
		"name" => "productName",
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "pattern",
		"sql" => " and c.pattern like CONCAT('%',#,'%') "
	),
	array(
		"name" => "brand",
		"sql" => " and c.brand like CONCAT('%',#,'%') "
	),
	array(
		"name" => "priCost",
		"sql" => " and c.priCost=# "
	),
	array(
		"name" => "unitName",
		"sql" => " and c.unitName like CONCAT('%',#,'%') "
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
		"name" => "warranty",
		"sql" => " and c.warranty=# "
	),
	array(
		"name" => "arrivalPeriod",
		"sql" => " and c.arrivalPeriod=# "
	),
	array(
		"name" => "accountingCode",
		"sql" => " and c.accountingCode=# "
	),
	array(
		"name" => "checkType",
		"sql" => " and c.checkType=# "
	),
	array(
		"name" => "statTypeArr",
		"sql" => " and c.statType in(arr) "
	),
	array(
		"name" => "notStatTypeArr",
		"sql" => " and c.statType not in(arr) "
	),
	array(
		"name" => "properties",
		"sql" => " and c.properties=# "
	),
	array(
		"name" => "supplier",
		"sql" => " and c.supplier=# "
	),
	array(
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array(
		"name" => "stockName",
		"sql" => " and c.stockName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong =#"
	),
	array(
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(//状态
		"name" => "ext1",
		"sql" => " and c.ext1=# "
	),
    array(//状态
        "name" => "notExt1",
        "sql" => " and c.ext1 <> # "
    ),
	array(//K3编码
		"name" => "ext2",
		"sql" => " and c.ext2=# "
	),
	array(//K3编码
		"name" => "ext2Search",
		"sql" => " and c.ext2 like CONCAT('%',#,'%')"
	),
	array(//K3编码
		"name" => "ext2Eq",
		"sql" => " and c.ext2=# "
	),
	array(//物料属性
		"name" => "ext3",
		"sql" => " and c.ext3=# "
	),
	array(//物料属性
		"name" => "ext3Search",
		"sql" => " and c.ext3 like CONCAT('%',#,'%')"
	),
	array(
		"name" => "encrypt",
		"sql" => "and c.encrypt=#"
	),
	array(
		"name" => "allocation",
		"sql" => "and c.allocation=#"
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
		"name" => "aaa",
		"sql" => "proTypeId in (select id from oa_stock_product_type where  lft >(select lft from oa_stock_product_type where proType = # ) and rgt < (select  rgt from oa_stock_product_type where proType = #) and (rgt-lft=1))"
	),
	array(
		"name" => "priceLock",
		"sql" => " and c.priceLock=# "
	)
);