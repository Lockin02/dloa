<?php
/**
 * @author show
 * @Date 2014年5月7日 9:47:17
 * @version 1.0
 * @description:公用费用分摊 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
            c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,c.supplierName,
            c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
            c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
            c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.costMoney ,c.hookMoney ,c.unHookMoney ,c.hookStatus,
            c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
            c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName, c.auditor, c.auditorId, c.belongCompany,
			c.module,c.moduleName,c.feeManId,c.feeMan,c.salesAreaId,c.salesArea,c.contractName,c.currency
        from oa_finance_cost c where c.isTemp = 0 AND c.isDel = 0",
    "select_default_sum" => "SELECT SUM(costMoney) AS costMoney, SUM(hookMoney) AS hookMoney,
            SUM(unHookMoney) AS unHookMoney
        from oa_finance_cost c where c.isTemp = 0 AND c.isDel = 0",
    "select_change" => "select c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
            c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,c.supplierName,
            c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
            c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
            c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.costMoney ,c.hookMoney ,c.unHookMoney ,c.hookStatus,
            c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
            c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName, c.auditor, c.auditorId,c.contractName,
            c.currency
        from oa_finance_cost c where isDel = 0 ",
    "select_hook" => "select c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
            c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,c.supplierName,
            c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
            c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
            c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.costMoney ,c.hookMoney ,c.unHookMoney ,c.hookStatus,
            c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
            c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName, c.auditor, c.auditorId ,c.module, c.moduleName,
            c.contractName,c.currency
        from oa_finance_cost c
            LEFT JOIN
            oa_finance_cost_hook_detail d ON  c.id = d.hookId
        where c.isTemp = 0 AND c.isDel = 0",
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "ids",
        "sql" => " and c.id in(arr)"
    ),
    array(
        "name" => "hookId",
        "sql" => " and d.mainId IN( SELECT mainId FROM oa_finance_cost_hook_detail WHERE hookId = #) "
    ),
    array(
        "name" => "objId",
        "sql" => " and c.objId=# "
    ),
    array(
        "name" => "objCodeSearch",
        "sql" => " and c.objCode LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "objType",
        "sql" => " and c.objType=# "
    ),
    array(
        "name" => "companyName",
        "sql" => " and c.companyName=# "
    ),
    array(
        "name" => "company",
        "sql" => " and c.company=# "
    ),
    array(
        "name" => "shareObjType",
        "sql" => " and c.shareObjType=# "
    ),
	array(
		"name" => "shareObjTypeNo",
		"sql" => " and c.shareObjType <> # "
	),
    array(
        "name" => "inPeriod",
        "sql" => " and c.inPeriod=# "
    ),
    array(
        "name" => "inPeriodSmall",
        "sql" => " and (LEFT(inPeriod,4) < LEFT(#,4)
            OR (LEFT(inPeriod,4) = LEFT(#,4) && CAST(SUBSTR(inPeriod, 6, 7) AS SIGNED) <= CAST(SUBSTR(#, 6, 7) AS SIGNED)))"
    ),
    array(
        "name" => "belongPeriod",
        "sql" => " and c.belongPeriod=# "
    ),
    array(
        "name" => "detailType",
        "sql" => " and c.detailType=# "
    ),
	array (
		"name" => "detailTypeArr",
		"sql" => " and c.detailType in(arr)"
	),
    array(
        "name" => "belongDeptName",
        "sql" => " and c.belongDeptName=# "
    ),
    array(
        "name" => "belongDeptId",
        "sql" => " and c.belongDeptId=# "
    ),
    array(
        "name" => "belongDeptIds",
        "sql" => "  and c.belongDeptId in(arr)"
    ),
    array(
        "name" => "belongId",
        "sql" => " and c.belongId=# "
    ),
    array(
        "name" => "belongName",
        "sql" => " and c.belongName=# "
    ),
    array(
        "name" => "chanceCode",
        "sql" => " and c.chanceCode=# "
    ),
    array(
        "name" => "chanceCodeSearch",
        "sql" => " and c.chanceCode LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "chanceId",
        "sql" => " and c.chanceId=# "
    ),
    array(
        "name" => "province",
        "sql" => " and c.province=# "
    ),
    array(
        "name" => "customerType",
        "sql" => " and c.customerType=# "
    ),
    array(
        "name" => "contractCode",
        "sql" => " and c.contractCode=# "
    ),
    array(
        "name" => "contractName",
        "sql" => " and c.contractName=# "
    ),
    array(
        "name" => "contractCodeSearch",
        "sql" => " and c.contractCode LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "contractId",
        "sql" => " and c.contractId=# "
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
        "sql" => " and c.projectCode LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "projectName",
        "sql" => " and c.projectName=# "
    ),
	array(
		"name" => "projectNameSearch",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
    array(
        "name" => "projectType",
        "sql" => " and c.projectType=# "
    ),
    array(
        "name" => "parentTypeId",
        "sql" => " and c.parentTypeId=# "
    ),
    array(
        "name" => "parentTypeName",
        "sql" => " and c.parentTypeName=# "
    ),
    array(
        "name" => "costTypeId",
        "sql" => " and c.costTypeId=# "
    ),
    array(
        "name" => "costTypeName",
        "sql" => " and c.costTypeName=# "
    ),
	array(
		"name" => "costTypeNameNo",
		"sql" => " and c.costTypeName <> # "
	),
    array(
        "name" => "costMoney",
        "sql" => " and c.costMoney=# "
    ),
    array(
        "name" => "hookMoney",
        "sql" => " and c.hookMoney=# "
    ),
    array(
        "name" => "unHookMoney",
        "sql" => " and c.unHookMoney=# "
    ),
    array(
        "name" => "hookStatus",
        "sql" => " and c.hookStatus=# "
    ),
    array(
        "name" => "hookStatusArr",
        "sql" => " and c.hookStatus in(arr)"
    ),
    array(
        "name" => "hookTime",
        "sql" => " and c.hookTime=# "
    ),
    array(
        "name" => "auditStatus",
        "sql" => " and c.auditStatus=# "
    ),
    array(
        "name" => "auditStatusArr",
        "sql" => " and c.auditStatus in(arr) "
    ),
    array(
        "name" => "auditStatusNo",
        "sql" => " and c.auditStatus <> # "
    ),
    array(
        "name" => "auditDate",
        "sql" => " and c.auditDate=# "
    ),
    array(
        "name" => "auditDateYear",
        "sql" => " and date_format(c.auditDate, '%Y') = # "
    ),
	array (
		"name" => "feeManId",
		"sql" => " and c.feeManId = #"
	),
	array (
		"name" => "salesAreaId",
		"sql" => " and c.salesAreaId = #"
	),
	array (//自定义条件
        "name" => "mySearchCondition",
        "sql" => "$"
    )
);