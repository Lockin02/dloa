<?php

/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:19:03
 * @version 1.0
 * @description:任职资格等级认证评价表模板 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.modelName ,c.careerDirection ,c.careerDirectionName ,c.baseLevel ,c.baseLevelName ,c.baseGrade ,c.baseGradeName ,c.remark ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_baseinfo_certifytemplate c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "noId",
		"sql" => " and c.Id <> # "
	),
	array (
		"name" => "modelName",
		"sql" => " and c.modelName=# "
	),
	array (
		"name" => "modelNameSearch",
		"sql" => " and c.modelName like concat('%',#,'%')"
	),
	array (
		"name" => "careerDirection",
		"sql" => " and c.careerDirection=# "
	),
	array (
		"name" => "careerDirectionName",
		"sql" => " and c.careerDirectionName=# "
	),
	array (
		"name" => "baseLevel",
		"sql" => " and c.baseLevel=# "
	),
	array (
		"name" => "baseLevelName",
		"sql" => " and c.baseLevelName=# "
	),
	array (
		"name" => "baseGrade",
		"sql" => " and c.baseGrade=# "
	),
	array (
		"name" => "baseGradeName",
		"sql" => " and c.baseGradeName=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
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
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	)
)
?>