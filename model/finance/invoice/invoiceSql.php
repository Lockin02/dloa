<?php
$sql_arr = array (
	//默认列表
    "select_invoice" => "select c.id,c.invoiceCode,c.applyId,c.applyNo,c.invoiceNo,c.invoiceUnitId,c.objId,c.objCode,c.objType,c.salesman,c.salesmanId,
		c.invoiceUnitName,c.invoiceTime,c.invoiceType,c.invoiceMoney,c.softMoney,c.isMail,c.createName,c.createTime,c.updateName,c.serviceMoney,c.hardMoney ,c.repairMoney,
		c.updateTime,c.isRed,c.belongId,c.allAmount,c.status,c.rObjCode,c.contractUnitId,c.contractUnitName,c.deptName,c.deptId,c.managerName,c.managerId,
		c.equRentalMoney,c.spaceRentalMoney,c.invoiceTypeName,c.otherMoney,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,
		c.invoiceMoneyCur,c.softMoneyCur,c.serviceMoneyCur,c.hardMoneyCur,c.repairMoneyCur,c.equRentalMoneyCur,c.spaceRentalMoneyCur,c.otherMoneyCur,c.rentBeginDate,c.rentEndDate,c.rentDays,
		c.dsEnergyCharge,c.dsEnergyChargeCur,c.dsWaterRateMoney,c.dsWaterRateMoneyCur,c.houseRentalFee,c.houseRentalFeeCur,c.installationCost,c.installationCostCur
		from oa_finance_invoice c where 1=1 ",
    //左连接显示开票记录和开票申请
    "invoiceAndApply" => "select c.id,c.invoiceCode,c.applyId,c.applyNo,c.invoiceNo,c.invoiceUnitId,c.objCode,c.objType,c.salesman,c.salesmanId,
        c.invoiceUnitName,c.invoiceTime,c.invoiceType,c.invoiceMoney,c.softMoney,c.isMail,c.createName,c.createId,c.createTime,c.serviceMoney,c.hardMoney ,c.repairMoney,a.customerName,
        c.isRed,c.belongId,c.status,a.linkMan,a.linkPhone,a.unitAddress,a.linkAddress,a.expressCompany,a.expressCompanyId ,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName from oa_finance_invoice c left join oa_finance_invoiceapply a on c.applyId = a.id where 1=1 ",
	//主从表查询 - 不显示主表金额字段
    "select_todeal" => "select c.id,c.invoiceCode,c.applyId,c.applyNo,c.invoiceNo,c.invoiceUnitId,c.objCode,c.objType,c.salesman,c.salesmanId,
		c.invoiceUnitName,c.invoiceTime,c.invoiceType,c.invoiceTypeName,
		d.id as detailId,d.productId,d.productName,d.amount,d.softMoney,d.hardMoney,d.repairMoney,d.serviceMoney,d.psType,
		d.equRentalMoney,d.spaceRentalMoney,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
		from oa_finance_invoice c left join oa_finance_invoice_detail d on c.id = d.invoiceId where 1=1 ",
	//按年统计金额
	"allInvoiceInTheYear" => "select
			sum(if(c.isRed = 0,c.softMoney,-c.softMoney)) as allSoftMoney,
			sum(if(c.isRed = 0,c.hardMoney,-c.hardMoney)) as allHardMoney,
			sum(if(c.isRed = 0,c.serviceMoney,-c.serviceMoney)) as allServiceMoney,
			sum(if(c.isRed = 0,c.repairMoney,-c.repairMoney)) as allRepairMoney,
			sum(if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney)) as allEquRentalMoney,
			sum(if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney)) as allSpaceRentalMoney,
			sum(if(c.isRed = 0,c.otherMoney,-c.otherMoney)) as allOtherMoney,
			sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as allMoney
		from oa_finance_invoice c",
	//按季度和开票类型统计金额
	"invoiced" => "SELECT
			sum(if(c.isRed = 0,c.softMoney,-c.softMoney)) as softMoney_Quarter,
			sum(if(c.isRed = 0,c.hardMoney,-c.hardMoney)) as hardMoney_Quarter,
			sum(if(c.isRed = 0,c.serviceMoney,-c.serviceMoney)) as serviceMoney_Quarter,
			sum(if(c.isRed = 0,c.repairMoney,-c.repairMoney)) as repairMoney_Quarter,
			sum(if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney)) as equRentalMoney_Quarter,
			sum(if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney)) as spaceRentalMoney_Quarter,
			sum(if(c.isRed = 0,c.otherMoney,-c.otherMoney)) as otherMoney_Quarter,
			sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as money_Quarter,
			QUARTER(c.invoiceTime) as thisQuarter ,
			c.invoiceType
		FROM oa_finance_invoice c ",
	"invoicedByType" => "SELECT
			sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as money_Quarter,
			QUARTER(c.invoiceTime) as thisQuarter,
			c.invoiceType
		FROM oa_finance_invoice c",
	"all"  => "select
			c.id,c.invoiceNo,c.invoiceCode,
			if(c.isRed = 0,c.allAmount,-c.allAmount) as allAmount,
			if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney) as invoiceMoney,
			if(c.isRed = 0,c.softMoney,-c.softMoney) as softMoney,
			if(c.isRed = 0,c.hardMoney,-c.hardMoney) as hardMoney,
			if(c.isRed = 0,c.repairMoney,-c.repairMoney) as repairMoney,
			if(c.isRed = 0,c.serviceMoney,-c.serviceMoney) as serviceMoney,
			if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney) as equRentalMoney,
			if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney) as spaceRentalMoney,
			if(c.isRed = 0,c.otherMoney,-c.otherMoney) as otherMoney,
			if(c.isRed = 0,c.dsEnergyCharge,-c.dsEnergyCharge) as dsEnergyCharge,
            if(c.isRed = 0,c.dsWaterRateMoney,-c.dsWaterRateMoney) as dsWaterRateMoney,
            if(c.isRed = 0,c.houseRentalFee,-c.houseRentalFee) as houseRentalFee,
            if(c.isRed = 0,c.installationCost,-c.installationCost) as installationCost,
			c.invoiceTime,
			c.invoiceTypeName,
			c.objType,
			c.salesmanId,
			c.salesman,
			c.invoiceUnitName,
			c.invoiceUnitId,
			c.invoiceType,c.isRed,
			c.salesman as prinvipalName,
			c.objCode as orderCode,
			c.rObjCode as rObjCode,
			c.remark,c.createTime,
			c.invoiceContent as productName,
			c.psType,
			c.areaName,
			c.invoiceUnitProvince,
			c.invoiceUnitTypeName,
			c.invoiceUnitType,c.salemanArea as thisAreaName,
			c.managerName as areaPrincipal,
			c.formBelong,
			c.formBelongName,
			c.businessBelong,
			c.businessBelongName,
			c.rentBeginDate,
			c.rentEndDate,
			c.rentDays,
			if(c.objType = 'KPRK-12',con.contractTypeName,c.objTypeName) as objTypeCN,
			con.signSubject,
			con.signSubjectName,
			con.contractNatureName
		from oa_finance_invoice c
			left join oa_contract_contract con on con.isTemp = 0 and con.id = c.objId
		where 1=1 ",
    //全部合同开票查询列表
    "all_excel" => "select
			c.id,c.invoiceNo,c.invoiceCode,
			if(c.isRed = 0,c.allAmount,-c.allAmount) as allAmount,
			if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney) as invoiceMoney,
			if(c.isRed = 0,c.softMoney,-c.softMoney) as softMoney,
			if(c.isRed = 0,c.hardMoney,-c.hardMoney) as hardMoney,
			if(c.isRed = 0,c.repairMoney,-c.repairMoney) as repairMoney,
			if(c.isRed = 0,c.serviceMoney,-c.serviceMoney) as serviceMoney,
			if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney) as equRentalMoney,
			if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney) as spaceRentalMoney,
			if(c.isRed = 0,c.otherMoney,-c.otherMoney) as otherMoney,
			if(c.isRed = 0,c.dsEnergyCharge,-c.dsEnergyCharge) as dsEnergyCharge,
            if(c.isRed = 0,c.dsWaterRateMoney,-c.dsWaterRateMoney) as dsWaterRateMoney,
            if(c.isRed = 0,c.houseRentalFee,-c.houseRentalFee) as houseRentalFee,
            if(c.isRed = 0,c.installationCost,-c.installationCost) as installationCost,
			c.invoiceTypeName,
			c.invoiceTime,
			c.objType,
			c.salesmanId,
			c.salesman,
			c.invoiceUnitName,
			c.invoiceUnitId,
			c.invoiceUnitProvince,
			c.invoiceTypeName,
			c.isRed,
			c.allAmount as amount,
			c.salesman as prinvipalName,
			c.objCode as orderCode,
			c.rObjCode as rObjCode,
			c.remark,c.createTime,
			c.invoiceContent as productName,
			c.psType,
			c.areaName,
			c.invoiceUnitProvince,
			c.invoiceUnitTypeName,
			c.invoiceUnitType,
			c.formBelong,
			c.formBelongName,
			c.businessBelong,
			c.businessBelongName,
			c.salemanArea as thisAreaName,
			c.managerName as areaPrincipal,
			if(c.objType = 'KPRK-12',con.contractTypeName,c.objTypeName) as objTypeCN,
			con.signSubject,con.signSubjectName,con.contractNatureName
		from oa_finance_invoice c
			left join oa_contract_contract con on con.isTemp = 0 and con.id = c.objId
		where 1=1",
	//开票查询，不合并
	"all_nomerge" => "select
			c.id,c.invoiceNo,c.invoiceCode,
			if(c.isRed = 0,d.amount,-d.amount) as allAmount,
			if(c.isRed = 0,round((d.softMoney + d.hardMoney + d.repairMoney + d.serviceMoney + d.equRentalMoney + d.spaceRentalMoney ),2),-round((d.softMoney + d.hardMoney + d.repairMoney + d.serviceMoney  + d.equRentalMoney + d.spaceRentalMoney ),2)) as invoiceMoney,
			if(c.isRed = 0,d.softMoney,-d.softMoney) as softMoney,
			if(c.isRed = 0,d.hardMoney,-d.hardMoney) as hardMoney,
			if(c.isRed = 0,d.repairMoney,-d.repairMoney) as repairMoney,
			if(c.isRed = 0,d.serviceMoney,-d.serviceMoney) as serviceMoney,
			if(c.isRed = 0,d.equRentalMoney,-d.equRentalMoney) as equRentalMoney,
			if(c.isRed = 0,d.spaceRentalMoney,-d.spaceRentalMoney) as spaceRentalMoney,
			if(c.isRed = 0,c.otherMoney,-c.otherMoney) as otherMoney,
			if(c.isRed = 0,c.dsEnergyCharge,-c.dsEnergyCharge) as dsEnergyCharge,
            if(c.isRed = 0,c.dsWaterRateMoney,-c.dsWaterRateMoney) as dsWaterRateMoney,
            if(c.isRed = 0,c.houseRentalFee,-c.houseRentalFee) as houseRentalFee,
            if(c.isRed = 0,c.installationCost,-c.installationCost) as installationCost,
			c.invoiceTime,
			c.objType,
			c.invoiceTypeName,
			c.salesmanId,
			c.salesman,
			c.invoiceUnitName,
			c.invoiceUnitId,
			c.invoiceUnitProvince,
			c.invoiceTypeName,
			c.isRed,
			c.allAmount as amount,
			c.salesman as prinvipalName,
			c.objCode as orderCode,
			c.rObjCode as rObjCode,
			c.remark,c.createTime,
			c.invoiceContent as productName,
			c.psType,
			c.areaName,
			c.invoiceUnitProvince,
			c.invoiceUnitTypeName,
			c.invoiceUnitType,
			c.salemanArea as thisAreaName,
			c.managerName as areaPrincipal,
			c.formBelong,
			c.formBelongName,
			c.businessBelong,
			c.businessBelongName,
			if(c.objType = 'KPRK-12',con.contractTypeName,c.objTypeName) as objTypeCN,
			con.signSubject,con.signSubjectName,con.contractNatureName
		from oa_finance_invoice c
			inner join oa_finance_invoice_detail d on c.id = d.invoiceId
			left join oa_contract_contract con on con.isTemp = 0 and con.id = c.objId
		where 1=1",
    //全部合同开票查询列表
    "all_sum" => "select
			round(sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)),2) as invoiceMoney,
			round(sum(if(c.isRed = 0,c.softMoney,-c.softMoney)),2) as softMoney,
			round(sum(if(c.isRed = 0,c.hardMoney,-c.hardMoney)),2) as hardMoney,
		    round(sum(if(c.isRed = 0,c.repairMoney,-c.repairMoney)),2) as repairMoney,
		    round(sum(if(c.isRed = 0,c.serviceMoney,-c.serviceMoney)),2) as serviceMoney,
		    round(sum(if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney)),2) as equRentalMoney,
		    round(sum(if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney)),2) as spaceRentalMoney,
		    round(sum(if(c.isRed = 0,c.otherMoney,-c.otherMoney)),2) as otherMoney,
		    round(sum(if(c.isRed = 0,c.dsEnergyCharge,-c.dsEnergyCharge)),2) as dsEnergyCharge,
            round(sum(if(c.isRed = 0,c.dsWaterRateMoney,-c.dsWaterRateMoney)),2) as dsWaterRateMoney,
            round(sum(if(c.isRed = 0,c.houseRentalFee,-c.houseRentalFee)),2) as houseRentalFee,
            round(sum(if(c.isRed = 0,c.installationCost,-c.installationCost)),2) as installationCost,
		    round(sum(if(c.isRed = 0,c.allAmount,-c.allAmount)),2) as amount
		from oa_finance_invoice c
			left join oa_contract_contract con on con.isTemp = 0 and con.id = c.objId
		where 1=1 ",
    //统计总金额
    "sumApplyMoney" => "select sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as allMoney from oa_finance_invoice c ",
   	//统计所有金额
    "sumAllMoney" => "select sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as invoiceMoney,sum(if(c.isRed = 0,c.softMoney,-c.softMoney)) as softMoney,
    		sum(if(c.isRed = 0,c.hardMoney,-c.hardMoney)) as hardMoney,sum(if(c.isRed = 0,c.repairMoney,-c.repairMoney)) as repairMoney,
    		sum(if(c.isRed = 0,c.serviceMoney,-c.serviceMoney)) as serviceMoney,sum(if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney)) as equRentalMoney,
    		sum(if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney)) as spaceRentalMoney,sum(if(c.isRed = 0,c.otherMoney,-c.otherMoney)) as otherMoney,
    		MAX(invoiceTime) AS invoiceTime
    	from oa_finance_invoice c ",
    //统计总金额,带业务编号
    "sumApplyMoneyrObjCode" => "select sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as allMoney,rObjCode from oa_finance_invoice c ",
    //统计总金额，带源单id
    "sumAllMoneyObjId" => "select sum(if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney)) as allMoney,objId from financeView_invoice c "
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and c.id =#"
    ),
    array (
        "name" => "ids",
        "sql" => "and c.id in(arr)"
    ),
	array (
		"name" => "createId",
		"sql" => "and c.createId =#"
	),
	array (
		"name" => "applyId",
		"sql" => "and c.applyId =#"
	),
	array (
		"name" => "invoiceType",
		"sql" => "and c.invoiceType =#"
	),
	array (//开票号
		"name" => "invoiceNo",
		"sql" => "and c.invoiceNo like CONCAT('%',#,'%')"
	),
	array (//开票时间
		"name" => "invoiceTime",
		"sql" => "and c.invoiceTime like BINARY CONCAT('%',#,'%')"
	),
	array (//关联编号查询
		"name" => "objCodeSearch",
		"sql" => "and c.objCode like CONCAT('%',#,'%')"
	),
	array (//合同号
		"name" => "noOrTempNo",
		"sql" => "and (c.objCode like concat('%',#,'%') or s.orderCode like CONCAT('%',#,'%') or s.orderTempCode like CONCAT('%',#,'%'))"
	),
    array (//销售员
        "name" => "salesman",
        "sql" => "and c.salesman like CONCAT('%',#,'%')"
    ),
    array (//销售员
        "name" => "salesmanId",
        "sql" => "and c.salesmanId = #"
    ),
	array (
		"name" => "year",
		"sql" => "and YEAR(c.invoiceTime) = #"
	),
	array (
		"name" => "objCode",
		"sql" => "and c.objCode = #"
	),
	array (
		"name" => "objType",
		"sql" => "and c.objType = #"
	),
	array (
		"name" => "objId",
		"sql" => "and c.objId = #"
	),
	array (
		"name" => "objIds",
		"sql" => "and c.objId in(arr)"
	),
	array (
		"name" => "objTypes",
		"sql" => "and c.objType in(arr)"
	),array(
		"name" => "isMailNo",
		"sql" => " and c.isMail <> # "
	),
	array (
		"name" => "beginYear",
		"sql" => "and DATE_FORMAT(c.invoiceTime,'%Y%m') between # "
	),array(
		"name" => "endYear",
		"sql" => " and # "
	),
	array (
		"name" => "beginDate",
		"sql" => "and DATE_FORMAT(c.invoiceTime,'%Y%m') >= DATE_FORMAT(#,'%Y%m')"
	),array(
		"name" => "endDate",
		"sql" => " and DATE_FORMAT(c.invoiceTime,'%Y%m') <= DATE_FORMAT(#,'%Y%m')"
	),array(
		"name" => "invoiceUnitNameSearch",
		"sql" => "and c.invoiceUnitName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "invoiceUnitId",
		"sql" => "and c.invoiceUnitId = #"
	),
	array (
		"name" => "invoiceUnitName",
		"sql" => "and c.invoiceUnitName = #"
	),
	array (
		"name" => "invoiceUnitType",
		"sql" => "and c.invoiceUnitType = #"
	),
	array (
		"name" => "invoiceUnitProvince",
		"sql" => "and c.invoiceUnitProvince = #"
	),
	array (
		"name" => "orderPrincipalId",
		"sql" => "and if( s.orderPrincipal = '' or s.orderPrincipal  is null,c.salesmanId,s.orderPrincipalId) = #"
	),
	array (
		"name" => "areaPrincipalId",
		"sql" => "and if( s.areaPrincipalId = '' or s.areaPrincipalId  is null,userArea.Leader_Id,s.areaPrincipalId) = #"
	),
	array (
		"name" => "orderId",
		"sql" => "and s.id = #"
	),
	array (
		"name" => "customerType",
		"sql" => "and cus.TypeOne = #"
	),
	array(
		"name" => "status",
		"sql" => "and c.status = #"
	),
	array(
		"name" => "invoiceMoney",
		"sql" => "and c.invoiceMoney = #"
	),
	array(
		"name" => "invoiceTimeSearch",
		"sql" => "and c.invoiceTime like BINARY concat('%',#,'%')"
	),
	array(
		"name" => "areaName",
		"sql" => "and c.areaName = #"
	),
    array(
        "name" => "areaNameArr",
        "sql" => "and c.areaName in(arr)"
    ),
	array(
		"name" => "rObjCode",
		"sql" => "and c.rObjCode = #"
	),
	array(
		"name" => "rObjCodes",
		"sql" => "and c.rObjCode in(arr)"
	),
	array(
		"name" => "conSignSubjectNameSearch",
		"sql" => "and con.signSubjectName like concat('%',#,'%')"
	),
	array(
		"name" => "signSubjectName",
		"sql" => "and con.signSubjectName like concat('%',#,'%')"
	),
	array(
		"name" => "deptIdArr",
		"sql" => "and c.deptId in(arr)"
	),
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	)
);