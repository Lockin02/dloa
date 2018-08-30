<?php
/**
 * @author Show
 * @Date 2012年9月26日 星期三 16:21:52
 * @version 1.0
 * @description:报销申请单 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select
			c.id ,c.ID ,c.BillNo ,c.salesArea,c.salesAreaId,c.InputManName ,c.InputMan ,c.InputDate ,c.CostManName ,c.CostMan ,c.CostDepartName ,c.CostDepartID ,
			c.CostManCom ,c.CostManComId ,c.Area ,c.ProjectNo ,c.CostDates ,c.CostMasterID ,c.CostBelongtoDeptIds ,c.CostClientType ,
			c.CostClientArea ,c.CostClientName ,c.ServiceQuantity ,c.Status ,c.UpdateDT ,c.isProject ,c.xm_sid ,c.RecInvoiceDT ,
			c.isNotReced ,c.Amount ,c.Updator ,c.PayDT ,c.IsFinRec ,c.FinRecDT ,c.SubDept ,c.ExamType ,c.CheckAmount ,c.isHandUp ,
			c.HandUpDT ,c.Payee ,c.rand_key ,c.Acc ,c.AccBank ,c.isNew ,c.ExaStatus ,c.ExaDT ,c.DetailType ,c.CostBelongTo ,c.CostBelongCom,
			c.CostBelongComId ,c.chanceId ,c.chanceCode ,c.chanceName ,c.contractId ,c.contractCode ,c.contractName ,c.customerName ,c.CustomerType,
			c.customerId ,c.projectName ,c.projectId ,c.ProjectNO,c.proManagerName ,c.proManagerId ,c.proProvince,c.proProvinceId,c.isPush ,c.province ,c.city ,c.customerDept ,
			c.invoiceMoney ,c.invoiceNumber ,c.esmCostdetailId,c.feeRegular,c.feeSubsidy,c.CostBelongDeptId,c.IsFinRec as recView,
			if(c.CostBelongDeptName is null,c.CostBelongtoDeptIds,c.CostBelongDeptName) as CostBelongDeptName,c.isLate,c.needExpenseCheck,
			if(c.Purpose is null,c.CostClientType,c.Purpose) as Purpose,c.subCheckDT,c.isFinAudit,c.projectType,c.module,c.moduleName
		from cost_summary_list c where 1 ",
	"select_amount"=>"select sum(c.Amount) as Amount from cost_summary_list c where 1",
	//模板选择专用，非本类信息
	"select_model" => "select c.id,c.id as modelType,c.templateName as modelTypeName,c.contentId as fields from cost_customtemplate c where 1 ",
	"select_projectlist" => "select
			p.id,p.projectCode,p.projectName,p.managerName,p.statusName,
			c.amount,c.saveAmount,c.waitCheckAmount,c.waitConfirmAmount,c.waitAuditAmount,c.waitPayAmount,c.complatedAmount,c.expenseNum
		from
			oa_esm_project p
				inner join
			(select
				projectId,projectName,projectNO,
				sum(Amount) as amount,
				sum(if(Status = '编辑',Amount,0)) saveAmount,
				sum(if(Status = '部门检查',Amount,0)) waitCheckAmount,
				sum(if(Status = '等待确认',Amount,0)) waitConfirmAmount,
				sum(if(Status = '部门审批',Amount,0)) waitAuditAmount,
				sum(if(Status = '出纳付款',Amount,0)) waitPayAmount,
				sum(if(Status = '完成',Amount,0)) complatedAmount,
				count(*) as expenseNum
			from
				cost_summary_list c
			where
				isPush = '1' and projectId <> 0 #c group by projectId
			) as c
				on p.id = c.projectId
		where 1 #p ",
	"count_all" => "select sum(c.Amount) as Amount,sum(c.invoiceMoney) as invoiceMoney,sum(c.invoiceNumber) as invoiceNumber,
			sum(c.invoiceNumber) as invoiceNumber,sum(c.feeRegular) as feeRegular,sum(c.feeSubsidy) as feeSubsidy
		from cost_summary_list c where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "HeadID",
		"sql" => " and c.HeadID=# "
	),
	array (
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "BillNoSearch",
		"sql" => " and c.BillNo like concat('%',#,'%')"
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId = #"
	),
	array (
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like concat('%',#,'%')"
	),
	array (
		"name" => "projectCodeSearch",
		"sql" => " and c.projectNo like concat('%',#,'%')"
	),
	array (
		"name" => "chanceCodeSearch",
		"sql" => " and c.chanceCode like concat('%',#,'%')"
	),
	array (
		"name" => "chanceNameSearch",
		"sql" => " and c.chanceName like concat('%',#,'%')"
	),
	array (
		"name" => "contractCodeSearch",
		"sql" => " and c.contractCode like concat('%',#,'%')"
	),
	array (
		"name" => "contractNameSearch",
		"sql" => " and c.contractName like concat('%',#,'%')"
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
		"name" => "costUser",
		"sql" => " and ( c.InputMan=#  or c.CostMan = # )"
	),
	array (
		"name" => "CostDepartID",
		"sql" => " and c.CostDepartID=# "
	),
	array (
		"name" => "isProject",
		"sql" => " and c.isProject=# "
	),
	array (
		"name" => "ProjectNo",
		"sql" => " and c.ProjectNo=# "
	),
	array (
		"name" => "ProjectNoNull",
		"sql" => " and c.ProjectNo = '' or c.ProjectNo is null "
	),
	array (
		"name" => "Purpose",
		"sql" => " and c.Purpose=# "
	),
	array (
		"name" => "PurposeSearch",
		"sql" => " and c.Purpose like concat('%',#,'%') "
	),
	array (
		"name" => "Amount",
		"sql" => " and c.Amount=# "
	),
	array (
		"name" => "AmountSearch",
		"sql" => " and c.Amount like concat('%',#,'%') "
	),
	array (
		"name" => "CostMasterID",
		"sql" => " and c.CostMasterID=# "
	),
	array (
		"name" => "Status",
		"sql" => " and c.Status=# "
	),
	array (
		"name" => "StatusArr",
		"sql" => " and c.Status in(arr)"
	),
	array (
		"name" => "StatusFin",
		"sql" => " and (c.Status=# and c.isFinAudit = 1) "
	),
	array (
		"name" => "StatusNor",
		"sql" => " and (c.Status=# and c.isFinAudit = 0) "
	),
	array (
		"name" => "CostBelongTo",
		"sql" => " and c.CostBelongTo=# "
	),
	array (
		"name" => "CustomerType",
		"sql" => " and c.CustomerType=# "
	),
	array (
		"name" => "xm_sid",
		"sql" => " and c.xm_sid=# "
	),
	array (
		"name" => "DetailType",
		"sql" => " and c.DetailType=# "
	),
	array (
		"name" => "DetailTypeArr",
		"sql" => " and c.DetailType in(arr)"
	),
    array (
        "name" => "ExaStatusArr",
        "sql" => " and c.ExaStatus in(arr)"
    ),
	array (
		"name" => "modelType",
		"sql" => " and c.modelType=# "
	),
	array (
		"name" => "InputManName",
		"sql" => " and c.InputManName=# "
	),
	array (
		"name" => "InputManNameSearch",
		"sql" => " and c.InputManName like concat('%',#,'%') "
	),
	array (
		"name" => "CostManName",
		"sql" => " and c.CostManName=# "
	),
	array (
		"name" => "CostDepartName",
		"sql" => " and c.CostDepartName=# "
	),
	array (
		"name" => "CostBelongDeptName",
		"sql" => " and c.CostBelongDeptName=# "
	),
	array (
		"name" => "CostBelongDeptId",
		"sql" => " and c.CostBelongDeptId=# "
	),
	array (
		"name" => "CostBelongDeptIds",
		"sql" => " and c.CostBelongDeptId in(arr)"
	),
	array (
		"name" => "DeptIds",
		"sql" => " and (c.CostBelongDeptId in(arr) or c.CostDepartID in(arr))"
	),
	array (
		"name" => "isNew",
		"sql" => " and c.isNew = #"
	),
	array (
		"name" => "needExpenseCheck",
		"sql" => " and c.needExpenseCheck = #"
	),
	array (
		"name" => "isPush",
		"sql" => " and c.isPush = #"
	),
	array (
		"name" => "pstatus",
		"sql" => " and p.status = #"
	),
	array (
		"name" => "pmanagerName",
		"sql" => " and p.managerName like concat('%',#,'%') "
	),
	array (
		"name" => "feeManId",
		"sql" => " and c.feeManId = #"
	),
	array (
		"name" => "salesAreaId",
		"sql" => " and c.salesAreaId = #"
	),
	array (
		"name" => "PayDTYear",
		"sql" => " and date_format(c.PayDT, '%Y') = #"
	),
	array (
		"name" => "proProvinceSearch",
		"sql" => " and c.proProvinceId = #"
	)
)
?>