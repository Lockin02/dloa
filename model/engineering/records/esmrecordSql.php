<?php
/**
 * @author show
 * @Date 2014年12月19日 15:41:46
 * @version 1.0
 * @description:工程项目版本记录 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id, c.projectId, c.projectCode, c.projectName, c.workRate, c.contractId,
        c.contractCode, c.contractType, c.contractTypeName, c.rObjCode, c.customerId, c.customerName, c.productLineName,
        c.contractMoney, c.officeId, c.officeName, c.deptId, c.deptName, c.formBelong, c.formBelongName,
        c.businessBelong, c.businessBelongName, c.managerId, c.managerName, c.planBeginDate, c.planEndDate,
        c.expectedDuration, c.actBeginDate, c.actEndDate, c.actDuration, c.estimates, c.budgetAll, c.budgetField,
        c.budgetOutsourcing, c.budgetOther, c.budgetDay, c.budgetPeople, c.budgetPerson, c.budgetEqu, c.budgetPK,
        c.feeAll, c.feeField, c.feeOutsourcing, c.feeOther, c.feeFlights, c.feeDay, c.feePeople, c.feePerson,
        c.feeEqu, c.feePayables, c.feePK, c.feeFieldImport, c.feeAllProcess, c.feeFieldProcess, c.projectProcess,
        c.earnedValue, c.perEarnedValue, c.natureName, c.categoryName, c.cycleName, c.outsourcingName, c.description,
        c.status, c.statusName, c.attribute, c.attributeName, c.customerTypeName, c.nature2Name, c.platformName,
        c.netName, c.createTypeName, c.signTypeName, c.toolType, c.areaManager, c.techManager, c.customerLinkMan,
        c.salesman, c.rdUser, c.peopleNumber, c.dlPeople, c.outsourcingPeople, c.outsourcingTypeName, c.outsourcingJob,
        c.country, c.province, c.city, c.version, c.isUse, c.storeYear, c.storeMonth, c.createId, c.createName,
        c.createTime  from oa_esm_records_project c
    where 1=1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array(
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode LIKE CONCAT('%', #, '%') "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array(
		"name" => "workRate",
		"sql" => " and c.workRate=# "
	),
	array(
		"name" => "contractId",
		"sql" => " and c.contractId=# "
	),
	array(
		"name" => "contractCode",
		"sql" => " and c.contractCode=# "
	),
	array(
		"name" => "contractCodeSearch",
		"sql" => " and c.contractCode LIKE CONCAT('%', #, '%') "
	),
	array(
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array(
		"name" => "contractTypeName",
		"sql" => " and c.contractTypeName=# "
	),
	array(
		"name" => "rObjCode",
		"sql" => " and c.rObjCode=# "
	),
	array(
		"name" => "customerId",
		"sql" => " and c.customerId=# "
	),
	array(
		"name" => "customerName",
		"sql" => " and c.customerName=# "
	),
	array(
		"name" => "contractMoney",
		"sql" => " and c.contractMoney=# "
	),
	array(
		"name" => "officeId",
		"sql" => " and c.officeId=# "
	),
	array(
		"name" => "officeName",
		"sql" => " and c.officeName=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array(
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array(
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array(
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array(
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array(
		"name" => "planBeginDate",
		"sql" => " and c.planBeginDate=# "
	),
	array(
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array(
		"name" => "expectedDuration",
		"sql" => " and c.expectedDuration=# "
	),
	array(
		"name" => "actBeginDate",
		"sql" => " and c.actBeginDate=# "
	),
	array(
		"name" => "actEndDate",
		"sql" => " and c.actEndDate=# "
	),
	array(
		"name" => "actDuration",
		"sql" => " and c.actDuration=# "
	),
	array(
		"name" => "estimates",
		"sql" => " and c.estimates=# "
	),
	array(
		"name" => "budgetAll",
		"sql" => " and c.budgetAll=# "
	),
	array(
		"name" => "budgetField",
		"sql" => " and c.budgetField=# "
	),
	array(
		"name" => "budgetOutsourcing",
		"sql" => " and c.budgetOutsourcing=# "
	),
	array(
		"name" => "budgetOther",
		"sql" => " and c.budgetOther=# "
	),
	array(
		"name" => "budgetDay",
		"sql" => " and c.budgetDay=# "
	),
	array(
		"name" => "budgetPeople",
		"sql" => " and c.budgetPeople=# "
	),
	array(
		"name" => "budgetPerson",
		"sql" => " and c.budgetPerson=# "
	),
	array(
		"name" => "budgetEqu",
		"sql" => " and c.budgetEqu=# "
	),
	array(
		"name" => "budgetPK",
		"sql" => " and c.budgetPK=# "
	),
	array(
		"name" => "feeAll",
		"sql" => " and c.feeAll=# "
	),
	array(
		"name" => "feeField",
		"sql" => " and c.feeField=# "
	),
	array(
		"name" => "feeOutsourcing",
		"sql" => " and c.feeOutsourcing=# "
	),
	array(
		"name" => "feeOther",
		"sql" => " and c.feeOther=# "
	),
	array(
		"name" => "feeFlights",
		"sql" => " and c.feeFlights=# "
	),
	array(
		"name" => "feeDay",
		"sql" => " and c.feeDay=# "
	),
	array(
		"name" => "feePeople",
		"sql" => " and c.feePeople=# "
	),
	array(
		"name" => "feePerson",
		"sql" => " and c.feePerson=# "
	),
	array(
		"name" => "feeEqu",
		"sql" => " and c.feeEqu=# "
	),
	array(
		"name" => "feePayables",
		"sql" => " and c.feePayables=# "
	),
	array(
		"name" => "feePK",
		"sql" => " and c.feePK=# "
	),
	array(
		"name" => "feeFieldImport",
		"sql" => " and c.feeFieldImport=# "
	),
	array(
		"name" => "feeAllProcess",
		"sql" => " and c.feeAllProcess=# "
	),
	array(
		"name" => "feeFieldProcess",
		"sql" => " and c.feeFieldProcess=# "
	),
	array(
		"name" => "projectProcess",
		"sql" => " and c.projectProcess=# "
	),
	array(
		"name" => "earnedValue",
		"sql" => " and c.earnedValue=# "
	),
	array(
		"name" => "perEarnedValue",
		"sql" => " and c.perEarnedValue=# "
	),
	array(
		"name" => "natureName",
		"sql" => " and c.natureName=# "
	),
	array(
		"name" => "categoryName",
		"sql" => " and c.categoryName=# "
	),
	array(
		"name" => "cycleName",
		"sql" => " and c.cycleName=# "
	),
	array(
		"name" => "outsourcingName",
		"sql" => " and c.outsourcingName=# "
	),
	array(
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "statusName",
		"sql" => " and c.statusName=# "
	),
	array(
		"name" => "attribute",
		"sql" => " and c.attribute=# "
	),
	array(
		"name" => "attributeName",
		"sql" => " and c.attributeName=# "
	),
	array(
		"name" => "customerTypeName",
		"sql" => " and c.customerTypeName=# "
	),
	array(
		"name" => "nature2Name",
		"sql" => " and c.nature2Name=# "
	),
	array(
		"name" => "platformName",
		"sql" => " and c.platformName=# "
	),
	array(
		"name" => "netName",
		"sql" => " and c.netName=# "
	),
	array(
		"name" => "createTypeName",
		"sql" => " and c.createTypeName=# "
	),
	array(
		"name" => "signTypeName",
		"sql" => " and c.signTypeName=# "
	),
	array(
		"name" => "toolType",
		"sql" => " and c.toolType=# "
	),
	array(
		"name" => "areaManager",
		"sql" => " and c.areaManager=# "
	),
	array(
		"name" => "techManager",
		"sql" => " and c.techManager=# "
	),
	array(
		"name" => "customerLinkMan",
		"sql" => " and c.customerLinkMan=# "
	),
	array(
		"name" => "salesman",
		"sql" => " and c.salesman=# "
	),
	array(
		"name" => "rdUser",
		"sql" => " and c.rdUser=# "
	),
	array(
		"name" => "peopleNumber",
		"sql" => " and c.peopleNumber=# "
	),
	array(
		"name" => "dlPeople",
		"sql" => " and c.dlPeople=# "
	),
	array(
		"name" => "outsourcingPeople",
		"sql" => " and c.outsourcingPeople=# "
	),
	array(
		"name" => "outsourcingTypeName",
		"sql" => " and c.outsourcingTypeName=# "
	),
	array(
		"name" => "outsourcingJob",
		"sql" => " and c.outsourcingJob=# "
	),
	array(
		"name" => "country",
		"sql" => " and c.country=# "
	),
	array(
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array(
		"name" => "city",
		"sql" => " and c.city=# "
	),
	array(
		"name" => "version",
		"sql" => " and c.version=# "
	),
	array(
		"name" => "isUse",
		"sql" => " and c.isUse=# "
	),
	array(
		"name" => "storeYear",
		"sql" => " and c.storeYear=# "
	),
	array(
		"name" => "storeMonth",
		"sql" => " and c.storeMonth=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (// 自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	)
);