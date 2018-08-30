<?php
/**
 * @author huangzf
 * @Date 2012年8月20日 星期一 20:43:04
 * @version 1.0
 * @description:安全库存列表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.actNum ,
	        c.minNum ,c.maxNum ,c.loadNum ,c.useFull ,c.moq ,c.price ,c.purchUserCode ,c.purchUserName ,c.prepareDay ,
	        c.minAmount ,c.isFillUp ,c.fillNum ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,
	        c.updateId ,c.updateTime,c.managerType,c.manageDept,c.manageDeptId
	    from oa_stock_safetystock c where 1 "
);

$condition_arr = array (
    array (
        "name" => "manageDeptId",
        "sql" => " and c.manageDeptId=# "
    ),
    array (
        "name" => "manageDeptIds",
        "sql" => " and c.manageDeptId in(arr) "
    ),
    array (
        "name" => "manageDept",
        "sql" => " and c.manageDept=# "
    ),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "productCode",
		"sql" => " and c.productCode=# "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array (
		"name" => "pattern",
		"sql" => " and c.pattern=# "
	),
	array (
		"name" => "unitName",
		"sql" => " and c.unitName=# "
	),
	array (
		"name" => "actNum",
		"sql" => " and c.actNum=# "
	),
	array (
		"name" => "minNum",
		"sql" => " and c.minNum=# "
	),
	array (
		"name" => "maxNum",
		"sql" => " and c.maxNum=# "
	),
	array (
		"name" => "loadNum",
		"sql" => " and c.loadNum=# "
	),
	array (
		"name" => "useFull",
		"sql" => " and c.useFull=# "
	),
	array (
		"name" => "moq",
		"sql" => " and c.moq=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "purchUserCode",
		"sql" => " and c.purchUserCode=# "
	),
	array (
		"name" => "purchUserName",
		"sql" => " and c.purchUserName=# "
	),
	array (
		"name" => "prepareDay",
		"sql" => " and c.prepareDay=# "
	),
	array (
		"name" => "minAmount",
		"sql" => " and c.minAmount=# "
	),
	array (
		"name" => "isFillUp",
		"sql" => " and c.isFillUp=# "
	),
	array (
		"name" => "fillNum",
		"sql" => " and c.fillNum=# "
	),
	array (
		"name" => "managerType",
		"sql" => " and c.managerType=# "
	),
	array (
		"name" => "managerTypes",
		"sql" => " and c.managerType in($) "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
);