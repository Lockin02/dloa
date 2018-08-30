<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:07:53
 * @version 1.0
 * @description:用车记录(oa_carrental_records) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectName ,c.projectCode ,c.carId ,c.carNo ,c.carType ,
            c.driver ,c.linkPhone ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,
            c.beginDate,c.endDate
        from oa_carrental_records c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode like concat('%',#,'%') "
	),
	array (
		"name" => "carId",
		"sql" => " and c.carId=# "
	),
	array (
		"name" => "carNo",
		"sql" => " and c.carNo=# "
	),
	array (
		"name" => "carNoSearch",
		"sql" => " and c.carNo like concat('%',#,'%') "
	),
	array (
		"name" => "carType",
		"sql" => " and c.carType=# "
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
        "name" => "worklogId",
        "sql" => " and c.worklogId=# "
    )
)
?>