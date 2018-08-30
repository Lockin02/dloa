<?php
$sql_arr = array(
    "select_invoiceapply" => "select c.id,c.applyNo,c.customerName,c.payedAmount,c.contAmount,c.status,c.isOffSite,
		c.invoiceType,c.applyDate,c.invoiceMoney,c.createName,c.updateTime,c.ExaStatus,c.ExaDT,c.objCode,c.objType,c.rObjCode,
		c.invoiceTypeName,c.customerTypeName,c.objTypeName,c.isNeedStamp,c.stampType,c.formBelong,c.formBelongName,
		c.businessBelong,c.businessBelongName,c.currency,c.contLocal,c.rentBeginDate,c.rentEndDate,c.rentDays
 		from oa_finance_invoiceapply c where 1",
    "sum_applyed" =>
        "select sum(c.invoiceMoney) from oa_finance_invoiceapply c where 1"
);
$condition_arr = array(
    array(
        "name" => "objCode",
        "sql" => "and c.objCode =#"
    ),
    array(
        "name" => "objTypes",
        "sql" => "and c.objType in(arr)"
    ),
    array(
        "name" => "objId",
        "sql" => " and c.objId = #"
    ),
    array(
        "name" => "createId",
        "sql" => "and c.createId =#"
    ),
    array(
        "name" => "ExaStatus",
        "sql" => "and c.ExaStatus =#"
    ),
    array(
        "name" => "applyNo",
        "sql" => "and c.applyNo like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "noApplyNo",
        "sql" => " and c.applyNo <> # "
    ),
    array(
        "name" => "customerNameSearch",
        "sql" => " and customerName like concat('%',#,'%')"
    ),
    array(
        "name" => "createName",
        "sql" => " and createName like concat('%',#,'%')"
    ),
    array(
        "name" => "objCodeSearch",
        "sql" => " and c.objCode like concat('%',#,'%')"
    ),
    array(
        "name" => "rObjCodeSearch",
        "sql" => " and c.rObjCode like concat('%',#,'%')"
    ),
    array(
        "name" => "done",
        "sql" => " and c.invoiceMoney <= c.payedAmount"
    ),
    array(
        "name" => "undo",
        "sql" => " and (c.payedAmount is null or c.payedAmount = '' or c.invoiceMoney > c.payedAmount )"
    ),
    array(
        "name" => "applyDateSearch",
        "sql" => " and DATE_FORMAT(c.applyDate,'%Y-%m-%d') like concat('%',#,'%')"
    ),
    array(
        "name" => "isNeedStamp",
        "sql" => " and c.isNeedStamp = # "
    ),
    array(
        "name" => "stampType",
        "sql" => " and c.stampType = # "
    )
);
