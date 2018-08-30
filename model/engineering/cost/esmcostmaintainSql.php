<?php
/**
 * @author Show
 * @Date 2014年06月27日
 * @version 1.0
 * @description:项目费用维护(oa_esm_costmaintain) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,p.projectCode ,p.projectName ,p.status ,p.statusName ,
			p.planBeginDate,p.actBeginDate ,p.planEndDate ,p.actEndDate ,DATE_FORMAT(c.month,'%Y-%m') as month,c.budget ,c.fee ,c.feeWait ,c.parentCostTypeId ,c.parentCostType ,
			c.costTypeId ,c.costType ,c.createId ,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.remark,c.ExaStatus,c.ExaDT,c.isDel
		from oa_esm_costmaintain c left join oa_esm_project p on p.id = c.projectId where 1=1 ",
	"count_all" => "select sum(c.fee) as fee,sum(c.feeWait) as feeWait from oa_esm_costmaintain c where 1=1"
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
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusName",
		"sql" => " and c.statusName=# "
	),
	array (
		"name" => "actBeginDate",
		"sql" => " and c.actBeginDate=# "
	),
	array (
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array (
		"name" => "actEndDate",
		"sql" => " and c.actEndDate=# "
	),
	array (
		"name" => "month",
		"sql" => " and c.month like CONCAT('%',#,'%') "
	),
	array (
		"name" => "budget",
		"sql" => " and c.budget=# "
	),
	array (
		"name" => "fee",
		"sql" => " and c.fee=# "
	),
	array (
		"name" => "parentCostTypeId",
		"sql" => " and c.parentCostTypeId=# "
	),
	array (
		"name" => "parentCostType",
		"sql" => " and c.parentCostType=# "
	),
	array (
		"name" => "costTypeId",
		"sql" => " and c.costTypeId=# "
	),
	array (
		"name" => "costType",
		"sql" => " and c.costType=# "
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
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	)
)
?>