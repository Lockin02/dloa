<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:07:46
 * @version 1.0
 * @description:报销申请费用明细(部门报销) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.ID ,c.HeadID ,c.RNo ,c.CostTypeID ,c.CostMoney ,c.days ,c.Remark ,c.BillNo ,
			c.AssID,c.MainType,c.MainTypeId,c.specialApplyNo,c.isAddByAuditor,c.toTakeOutTypeId
		from cost_detail c where 1=1 ",
	"select_count" => "select
			c.CostTypeID ,sum(c.CostMoney*c.days) as CostMoney ,sum(c.CostMoney) as CostPrice ,if(t.showDays = 1,sum(c.days),0) as days,c.specialApplyNo,c.Remark ,c.BillNo ,c.MainType,c.MainTypeId,c.isAddByAuditor,c.toTakeOutTypeId
		from cost_detail c left join cost_type t on c.CostTypeID = t.CostTypeID where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ID",
		"sql" => " and c.ID=# "
	),
	array (
		"name" => "HeadID",
		"sql" => " and c.HeadID=# "
	),
	array (
		"name" => "RNo",
		"sql" => " and c.RNo=# "
	),
	array (
		"name" => "CostTypeID",
		"sql" => " and c.CostTypeID=# "
	),
	array (
		"name" => "CostMoney",
		"sql" => " and c.CostMoney=# "
	),
	array (
		"name" => "days",
		"sql" => " and c.days=# "
	),
	array (
		"name" => "Remark",
		"sql" => " and c.Remark=# "
	),
	array (
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "AssID",
		"sql" => " and c.AssID=# "
	)
)
?>