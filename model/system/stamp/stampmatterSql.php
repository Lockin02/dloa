<?php
/**
 * @author show
 * @Date 2013年12月5日 11:48:32
 * @version 1.0
 * @description:盖章使用事项配置 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.configId ,c.stamp_cId ,c.matterName ,c.directions ,c.needAudit ,c.status  from oa_system_stamp_matter c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "configId",
		"sql" => " and c.configId=# "
	),
    array (
        "name" => "stamp_cId",
        "sql" => " and c.stamp_cId=# "
    ),
	array (
		"name" => "matterName",
		"sql" => " and c.matterName=# "
	),
	array (
		"name" => "directions",
		"sql" => " and c.directions=# "
	),
	array (
		"name" => "needAudit",
		"sql" => " and c.needAudit=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "matterNameSearch",
		"sql" => " and c.matterName like concat('%',#,'%') "
	)
)
?>