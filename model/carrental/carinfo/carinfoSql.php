<?php

/**
 * @author Show
 * @Date 2011年12月27日 21:39:04
 * @version 1.0
 * @description:车辆信息(oa_carrental_carinfo) sql配置文件 车辆状态 status
                                                   0 生效
                                                   1 失效
 */
$sql_arr = array (
	 "select_default"=>"select c.id ,c.unitsId ,c.unitsName ,c.carType ,c.carTypeName ,c.carNo ,c.brand ,c.displacement ,
	 		c.fuelConsumption ,c.perFuel ,c.buyDate ,c.limitedSeating ,c.status ,c.owners ,c.driver ,c.linkPhone ,
	 		c.lastCheckDate ,c.isSign ,c.evaluate ,c.remark ,c.useDays ,c.createId ,c.createName ,c.createTime ,
	 		c.updateId ,c.updateName ,c.updateTime
	 	from oa_carrental_carinfo c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "unitsId",
		"sql" => " and c.unitsId=# "
	),
	array (
		"name" => "unitsName",
		"sql" => " and c.unitsName=# "
	),
	array (
		"name" => "carType",
		"sql" => " and c.carType=# "
	),
	array (
		"name" => "carNo",
		"sql" => " and c.carNo=# "
	),
	array (
		"name" => "carNoEq",
		"sql" => " and c.carNo=# "
	),
	array (
		"name" => "limitedSeating",
		"sql" => " and c.limitedSeating=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "driver",
		"sql" => " and c.driver=# "
	),
	array (
		"name" => "linkPhone",
		"sql" => " and c.linkPhone=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>