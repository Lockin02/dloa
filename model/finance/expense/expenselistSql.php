<?php

/**
 * @author Show
 * @Date 2012年12月6日 星期四 14:29:37
 * @version 1.0
 * @description:报销申请单 sql配置文件 DetailType
  1.部门报销
  2.(工程)项目报销
  3.研发项目报销
  4.售前费用
  5.售后费用
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.HeadID ,c.InputMan ,c.InputManName ,c.InputDate ,c.CostMan ,c.CostDepartID ,c.CostDepartName ,c.CostManCom ,c.CostManComId ,c.isProject ,c.ProjectNO ,c.Purpose ,c.CostMasterID ,c.Status ,c.CostBelongTo ,c.CostBelongDeptName ,c.CostBelongCom ,c.CostBelongComId ,c.CustomerType ,c.xm_sid ,c.DetailType ,c.modelType ,c.ModelTypeName ,c.chanceId ,c.chanceCode ,c.chanceName ,c.contractId ,c.contractCode ,c.contractName ,c.customerName ,c.customerId ,c.projectName ,c.projectId ,c.proManagerName ,c.proManagerId ,c.BillNo ,c.isNew ,c.isPush ,c.province ,c.city ,c.customerDept ,c.formMoney ,c.invoiceMoney ,c.invoiceNumber ,c.CostManName ,c.esmCostdetailId  from cost_detail_list c where 1=1 "
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
		"name" => "InputMan",
		"sql" => " and c.InputMan=# "
	),
	array (
		"name" => "InputManName",
		"sql" => " and c.InputManName=# "
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
		"name" => "CostDepartName",
		"sql" => " and c.CostDepartName=# "
	),
	array (
		"name" => "CostManCom",
		"sql" => " and c.CostManCom=# "
	),
	array (
		"name" => "CostManComId",
		"sql" => " and c.CostManComId=# "
	),
	array (
		"name" => "isProject",
		"sql" => " and c.isProject=# "
	),
	array (
		"name" => "ProjectNO",
		"sql" => " and c.ProjectNO=# "
	),
	array (
		"name" => "Purpose",
		"sql" => " and c.Purpose=# "
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
		"name" => "CostBelongTo",
		"sql" => " and c.CostBelongTo=# "
	),
	array (
		"name" => "CostBelongDeptName",
		"sql" => " and c.CostBelongDeptName=# "
	),
	array (
		"name" => "CostBelongCom",
		"sql" => " and c.CostBelongCom=# "
	),
	array (
		"name" => "CostBelongComId",
		"sql" => " and c.CostBelongComId=# "
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
		"name" => "modelType",
		"sql" => " and c.modelType=# "
	),
	array (
		"name" => "ModelTypeName",
		"sql" => " and c.ModelTypeName=# "
	),
	array (
		"name" => "chanceId",
		"sql" => " and c.chanceId=# "
	),
	array (
		"name" => "chanceCode",
		"sql" => " and c.chanceCode=# "
	),
	array (
		"name" => "chanceName",
		"sql" => " and c.chanceName=# "
	),
	array (
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array (
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array (
		"name" => "contractName",
		"sql" => " and c.contractName=# "
	),
	array (
		"name" => "customerName",
		"sql" => " and c.customerName=# "
	),
	array (
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "proManagerName",
		"sql" => " and c.proManagerName=# "
	),
	array (
		"name" => "proManagerId",
		"sql" => " and c.proManagerId=# "
	),
	array (
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "isNew",
		"sql" => " and c.isNew=# "
	),
	array (
		"name" => "isPush",
		"sql" => " and c.isPush=# "
	),
	array (
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array (
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array (
		"name" => "customerDept",
		"sql" => " and c.customerDept=# "
	),
	array (
		"name" => "formMoney",
		"sql" => " and c.formMoney=# "
	),
	array (
		"name" => "invoiceMoney",
		"sql" => " and c.invoiceMoney=# "
	),
	array (
		"name" => "invoiceNumber",
		"sql" => " and c.invoiceNumber=# "
	),
	array (
		"name" => "CostManName",
		"sql" => " and c.CostManName=# "
	),
	array (
		"name" => "esmCostdetailId",
		"sql" => " and c.esmCostdetailId=# "
	)
)
?>