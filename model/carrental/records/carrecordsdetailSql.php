<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:08:21
 * @version 1.0
 * @description:用车明细(oa_carrental_records_detail) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.recordsId,c.worklogId ,c.useDate ,c.beginNum ,c.endNum ,c.mileage ,c.useHours ,
            c.useReson ,c.travelFee ,c.fuelFee ,c.roadFee ,c.effectiveLog ,c.createId ,c.createName ,c.createTime ,
            c.updateId ,c.updateName ,c.updateTime,c.carNo,c.carId,c.parkingFee,c.rentalType,c.rentalTypeName,c.projectId ,
            c.projectCode ,c.projectName ,c.activityId,c.activityName
        from oa_carrental_records_detail c where 1=1 ",
	"select_forweeklog" => "select r.projectId ,r.projectName ,r.projectCode ,r.carNo ,r.carType,r.driver ,r.linkPhone,
			c.recordsId,c.worklogId ,c.useDate ,c.beginNum ,c.endNum ,c.mileage ,c.useHours ,c.useReson ,c.travelFee ,c.fuelFee ,
			c.roadFee ,c.effectiveLog,c.id
		from oa_carrental_records r inner join oa_carrental_records_detail c on r.id = c.recordsId",
	"select_count" => "select count(*) as useDays from oa_carrental_records_detail c where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "carNoSearch",
		"sql" => " and c.carNo like concat('%',#,'%') "
	),
	array (
		"name" => "carId",
		"sql" => " and c.carId=# "
	),
	array (
		"name" => "recordsId",
		"sql" => " and c.recordsId=# "
	),
	array (
		"name" => "worklogId",
		"sql" => " and c.worklogId=# "
	),
	array (
		"name" => "useDate",
		"sql" => " and c.useDate=# "
	),
	array (
		"name" => "beginNum",
		"sql" => " and c.beginNum=# "
	),
	array (
		"name" => "endNum",
		"sql" => " and c.endNum=# "
	),
	array (
		"name" => "mileage",
		"sql" => " and c.mileage=# "
	),
	array (
		"name" => "useHours",
		"sql" => " and c.useHours=# "
	),
	array (
		"name" => "travelFee",
		"sql" => " and c.travelFee=# "
	),
	array (
		"name" => "fuelFee",
		"sql" => " and c.fuelFee=# "
	),
	array (
		"name" => "roadFee",
		"sql" => " and c.roadFee=# "
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
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "weekId",
		"sql" => " and c.worklogId in (select w.id from oa_esm_worklog w inner join oa_esm_weeklog e on w.weekId = e.id where e.id = #) "
	)
)
?>