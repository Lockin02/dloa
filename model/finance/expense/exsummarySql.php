<?php

/**
 * @author Show
 * @Date 2012年10月11日 星期四 10:01:33
 * @version 1.0
 * @description:报销汇总主表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.ID ,c.BillNo ,c.InputMan ,c.InputDate ,c.CostMan ,c.CostDepartID ,c.Area ,c.ProjectNo ,c.CostDates ,c.CostMasterID ,c.CostBelongtoDeptIds ,c.CostClientType ,c.CostClientArea ,c.CostClientName ,c.ServiceQuantity ,c.Status ,c.UpdateDT ,c.isProject ,c.xm_sid ,c.RecInvoiceDT ,c.isNotReced ,c.Amount ,c.Updator ,c.PayDT ,c.IsFinRec ,c.FinRecDT ,c.SubDept ,c.ExamType ,c.CostBelongTo ,c.CheckAmount ,c.isHandUp ,c.HandUpDT ,c.Payee ,c.rand_key ,c.allregisterid from cost_summary_list c where 1=1 "
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
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "InputMan",
		"sql" => " and c.InputMan=# "
	),
	array (
		"name" => "InputDate",
		"sql" => " and c.InputDate=# "
	),
	array (
		"name" => "CostMan",
		"sql" => " and c.CostMan=# "
	),
	array (
		"name" => "CostDepartID",
		"sql" => " and c.CostDepartID=# "
	),
	array (
		"name" => "Area",
		"sql" => " and c.Area=# "
	),
	array (
		"name" => "ProjectNo",
		"sql" => " and c.ProjectNo=# "
	),
	array (
		"name" => "CostDates",
		"sql" => " and c.CostDates=# "
	),
	array (
		"name" => "CostMasterID",
		"sql" => " and c.CostMasterID=# "
	),
	array (
		"name" => "CostBelongtoDeptIds",
		"sql" => " and c.CostBelongtoDeptIds=# "
	),
	array (
		"name" => "CostClientType",
		"sql" => " and c.CostClientType=# "
	),
	array (
		"name" => "CostClientArea",
		"sql" => " and c.CostClientArea=# "
	),
	array (
		"name" => "CostClientName",
		"sql" => " and c.CostClientName=# "
	),
	array (
		"name" => "ServiceQuantity",
		"sql" => " and c.ServiceQuantity=# "
	),
	array (
		"name" => "Status",
		"sql" => " and c.Status=# "
	),
	array (
		"name" => "UpdateDT",
		"sql" => " and c.UpdateDT=# "
	),
	array (
		"name" => "isProject",
		"sql" => " and c.isProject=# "
	),
	array (
		"name" => "xm_sid",
		"sql" => " and c.xm_sid=# "
	),
	array (
		"name" => "RecInvoiceDT",
		"sql" => " and c.RecInvoiceDT=# "
	),
	array (
		"name" => "isNotReced",
		"sql" => " and c.isNotReced=# "
	),
	array (
		"name" => "Amount",
		"sql" => " and c.Amount=# "
	),
	array (
		"name" => "Updator",
		"sql" => " and c.Updator=# "
	),
	array (
		"name" => "PayDT",
		"sql" => " and c.PayDT=# "
	),
	array (
		"name" => "IsFinRec",
		"sql" => " and c.IsFinRec=# "
	),
	array (
		"name" => "FinRecDT",
		"sql" => " and c.FinRecDT=# "
	),
	array (
		"name" => "SubDept",
		"sql" => " and c.SubDept=# "
	),
	array (
		"name" => "ExamType",
		"sql" => " and c.ExamType=# "
	),
	array (
		"name" => "CostBelongTo",
		"sql" => " and c.CostBelongTo=# "
	),
	array (
		"name" => "CheckAmount",
		"sql" => " and c.CheckAmount=# "
	),
	array (
		"name" => "isHandUp",
		"sql" => " and c.isHandUp=# "
	),
	array (
		"name" => "HandUpDT",
		"sql" => " and c.HandUpDT=# "
	),
	array (
		"name" => "Payee",
		"sql" => " and c.Payee=# "
	),
	array (
		"name" => "rand_key",
		"sql" => " and c.rand_key=# "
	)
)
?>