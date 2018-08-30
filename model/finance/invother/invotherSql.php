<?php

/**
 * @author Show
 * @Date 2011年12月28日 星期三 19:03:43
 * @version 1.0
 * @description:应付其他发票 sql配置文件 审核状态 ExaStatus
 * 0.未审核
 * 1.已审核
 */
$sql_arr = array(
    "select_default" => "select c.id, c.invoiceCode, c.invoiceNo, c.formDate, c.formNumber,c.supplierName, 
            c.supplierId, c.address, c.payDate, c.bank, c.isRed, c.taxRate, c.invType,
            IF(c.isRed = 0, c.formAssessment, -c.formAssessment) AS formAssessment,
            IF(c.isRed = 0, c.formCount, -c.formCount) AS formCount,
            IF(c.isRed = 0, c.amount, -c.amount) AS amount,
            c.subjects, c.currency, c.sourceType, c.menuNo, c.excRate, c.hookMoney,
            c.departmentsId, c.departments, c.salesmanId, c.salesman, c.ExaStatus, c.period,
            c.ExaDT, c.exaMan, c.exaManId, c.status, c.belongId, c.remark, c.createId, c.createName, c.createTime, 
            c.updateId, c.updateName, c.updateTime,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName
        from oa_finance_invother c where 1=1 ",
    "select_history" => "select distinct c.id, c.invoiceCode, c.invoiceNo, c.formDate, c.formNumber,c.supplierName,
            c.supplierId, c.address, c.payDate, c.bank, c.isRed, c.taxRate, c.invType,
            IF(c.isRed = 0, c.formAssessment, -c.formAssessment) AS formAssessment,
            IF(c.isRed = 0, c.formCount, -c.formCount) AS formCount,
            IF(c.isRed = 0, c.amount, -c.amount) AS amount,
            c.subjects, c.currency, c.sourceType, c.menuNo, c.excRate,
            c.departmentsId, c.departments, c.salesmanId, c.period, c.hookMoney,
            c.salesman, c.ExaStatus, c.ExaDT, c.exaMan, c.exaManId, c.status, c.belongId, c.remark, 
            c.createId, c.createName, c.createTime, c.updateId, c.updateName, c.updateTime
        from oa_finance_invother c left join oa_finance_invother_detail d on c.id = d.mainId",
    "select_sum" => "select
			sum(IF(c.isRed = 0, d.allCount, -d.allCount)) as formCount,
			sum(IF(c.isRed = 0, d.amount, -d.amount)) as amount,
			sum(IF(c.isRed = 0, d.assessment, -d.assessment)) as formAssessment
		from oa_finance_invother_detail d INNER JOIN oa_finance_invother c ON c.id = d.mainId"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "invoiceCode",
        "sql" => " and c.invoiceCode=# "
    ),
    array(
        "name" => "invoiceCodeSearch",
        "sql" => " and c.invoiceCode like concat('%',#,'%')"
    ),
    array(
        "name" => "menuNoSearch",
        "sql" => " and c.menuNo like concat('%',#,'%')"
    ),
    array(
        "name" => "invoiceNo",
        "sql" => " and c.invoiceNo=# "
    ),
    array(
        "name" => "invoiceNoSearch",
        "sql" => " and c.invoiceNo like concat('%',#,'%')"
    ),
    array(
        "name" => "formDate",
        "sql" => " and c.formDate=# "
    ),
    array(
        "name" => "supplierName",
        "sql" => " and c.supplierName like concat('%',#,'%')"
    ),
    array(
        "name" => "supplierId",
        "sql" => " and c.supplierId=# "
    ),
    array(
        "name" => "payDate",
        "sql" => " and c.payDate=# "
    ),
    array(
        "name" => "isRed",
        "sql" => " and c.isRed=# "
    ),
    array(
        "name" => "taxRate",
        "sql" => " and c.taxRate=# "
    ),
    array(
        "name" => "invType",
        "sql" => " and c.invType=# "
    ),
    array(
        "name" => "amount",
        "sql" => " and c.amount=# "
    ),
    array(
        "name" => "formAssessment",
        "sql" => " and c.formAssessment=# "
    ),
    array(
        "name" => "formCount",
        "sql" => " and c.formCount=# "
    ),
    array(
        "name" => "sourceType",
        "sql" => " and c.sourceType=# "
    ),
	array(
		"name" => "sourceTypeNone",
		"sql" => " and c.sourceType='' "
	),
    array(
        "name" => "menuNo",
        "sql" => " and c.menuNo=# "
    ),
    array(
        "name" => "departmentsId",
        "sql" => " and c.departmentsId=# "
    ),
    array(
        "name" => "departments",
        "sql" => " and c.departments like concat('%',#,'%')"
    ),
    array(
        "name" => "salesmanId",
        "sql" => " and c.salesmanId=# "
    ),
    array(
        "name" => "salesman",
        "sql" => " and c.salesman like concat('%',#,'%')"
    ),
    array(
        "name" => "ExaStatus",
        "sql" => " and c.ExaStatus=# "
    ),
    array(
        "name" => "ExaDT",
        "sql" => " and c.ExaDT=# "
    ),
    array(
        "name" => "beginYear",
        "sql" => " and YEAR(c.ExaDT) >= # "
    ),
    array(
        "name" => "beginMonth",
        "sql" => " and MONTH(c.ExaDT) >= # "
    ),
    array(
        "name" => "endYear",
        "sql" => " and YEAR(c.ExaDT) <= # "
    ),
    array(
        "name" => "endMonth",
        "sql" => " and MONTH(c.ExaDT) <= # "
    ),
    array(
        "name" => "exaMan",
        "sql" => " and c.exaMan like concat('%',#,'%')"
    ),
    array(
        "name" => "exaManId",
        "sql" => " and c.exaManId=# "
    ),
    array(
        "name" => "status",
        "sql" => " and c.status=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "dObjId",
        "sql" => " and d.objId=# "
    ),
    array(
        "name" => "dObjType",
        "sql" => " and d.objType=# "
    ),
    array(
        "name" => "ExaStatus",
        "sql" => " and c.ExaStatus=# "
    ),
    array(
        "name" => "period",
        "sql" => " and c.period=# "
    )
);