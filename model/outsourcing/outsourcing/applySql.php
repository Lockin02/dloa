<?php
/**
 * @author Administrator
 * @Date 2013年9月17日 11:30:50
 * @version 1.0
 * @description:外包申请表 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,
            c.applyCode ,c.notEnough ,c.notElse ,c.notSkill ,c.notCost ,c.hopeDate,c.notMart ,c.whyDescription ,
            c.projectId ,c.projectCode ,c.projecttName ,c.projectAddress ,c.projectClientType ,c.projectStartTime ,
            c.projectEndTime ,c.projectCycle ,c.projectCharge ,c.projectChargeTel ,c.contractBudget ,c.inBudget ,
            c.outBudget ,c.personSum ,c.parContactBudget ,c.partProportion ,c.inBudgetGrossMargin ,
            c.outBudgetGrossMargin ,c.inOutBudgetDifference ,c.inOutGrossMarginDifference ,c.riskLevel ,
            c.workloadDescription ,c.projectRequest ,c.riskAssess ,c.staffApplicationsProgram ,c.exaDT ,c.exaStatus ,
            c.approveId ,c.approveName ,c.approveTime ,c.createId ,c.createName ,c.createTime ,c.updateId ,
            c.updateName ,c.updateTime ,c.outType,c.state ,c.cancelReason ,c.cancelManName ,c.cancelManId ,
            c.dealDate,p.provinceId
        from
            oa_outsourcing_apply c left join oa_esm_project p on c.projectId=p.id
        where 1=1 ",
    "select_deal_list" => "select c.id ,c.applyCode ,c.hopeDate,c.projectId ,c.projectCode ,c.projecttName ,
            c.projectAddress ,c.projectClientType ,c.projectStartTime ,c.projectEndTime ,c.projectCycle ,
            c.projectCharge ,c.projectChargeTel  ,c.personSum ,c.exaDT ,c.exaStatus ,c.approveId ,c.approveName ,
            c.approveTime ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.outType,
            c.state ,c.cancelReason ,c.cancelManName ,c.cancelManId ,c.dealDate,p.officeName,p.province,p.nature,
            p.natureName,c.ExaDT,a.id as appId,a.ExaStatus as appExaStatus
        from
            oa_outsourcing_apply c
            left join oa_esm_project p on c.projectId=p.id
            left join oa_outsourcing_approval a on(a.applyId=c.id)
        where 1=1 "

);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
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
        "name" => "applyCode",
        "sql" => " and c.applyCode  like concat('%',#,'%')  "
    ),
    array(
        "name" => "applyCodeSearch",
        "sql" => " and c.applyCode like concat('%',#,'%')  "
    ),
    array(
        "name" => "notEnough",
        "sql" => " and c.notEnough=# "
    ),
    array(
        "name" => "notElse",
        "sql" => " and c.notElse=# "
    ),
    array(
        "name" => "notSkill",
        "sql" => " and c.notSkill=# "
    ),
    array(
        "name" => "notCost",
        "sql" => " and c.notCost=# "
    ),
    array(
        "name" => "notMart",
        "sql" => " and c.notMart=# "
    ),
    array(
        "name" => "whyDescription",
        "sql" => " and c.whyDescription=# "
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
        "name" => "projectCodeLike",
        "sql" => " and c.projectCode like concat('%',#,'%') "
    ),
    array(
        "name" => "projecttName",
        "sql" => " and c.projecttName=# "
    ),
    array(
        "name" => "projecttNameLike",
        "sql" => " and c.projecttName like concat('%',#,'%') "
    ),
    array(
        "name" => "projectAddress",
        "sql" => " and c.projectAddress=# "
    ),
    array(
        "name" => "projectAddressLike",
        "sql" => " and c.projectAddress like concat('%',#,'%') "
    ),
    array(
        "name" => "projectClientType",
        "sql" => " and c.projectClientType=# "
    ),
    array(
        "name" => "projectClientTypeLike",
        "sql" => " and c.projectClientType like concat('%',#,'%') "
    ),
    array(
        "name" => "projectStartTime",
        "sql" => " and c.projectStartTime=# "
    ),
    array(
        "name" => "projectEndTime",
        "sql" => " and c.projectEndTime=# "
    ),
    array(
        "name" => "projectCycle",
        "sql" => " and c.projectCycle=# "
    ),
    array(
        "name" => "projectCharge",
        "sql" => " and c.projectCharge=# "
    ),
    array(
        "name" => "projectChargeLike",
        "sql" => " and c.projectCharge like concat('%',#,'%') "
    ),
    array(
        "name" => "projectChargeTel",
        "sql" => " and c.projectChargeTel=# "
    ),
    array(
        "name" => "projectChargeTelLike",
        "sql" => " and c.projectChargeTel like concat('%',#,'%') "
    ),
    array(
        "name" => "contractBudget",
        "sql" => " and c.contractBudget=# "
    ),
    array(
        "name" => "inBudget",
        "sql" => " and c.inBudget=# "
    ),
    array(
        "name" => "outBudget",
        "sql" => " and c.outBudget=# "
    ),
    array(
        "name" => "personSum",
        "sql" => " and c.personSum=# "
    ),
    array(
        "name" => "parContactBudget",
        "sql" => " and c.parContactBudget=# "
    ),
    array(
        "name" => "partProportion",
        "sql" => " and c.partProportion=# "
    ),
    array(
        "name" => "inBudgetGrossMargin",
        "sql" => " and c.inBudgetGrossMargin=# "
    ),
    array(
        "name" => "outBudgetGrossMargin",
        "sql" => " and c.outBudgetGrossMargin=# "
    ),
    array(
        "name" => "inOutBudgetDifference",
        "sql" => " and c.inOutBudgetDifference=# "
    ),
    array(
        "name" => "inOutGrossMarginDifference",
        "sql" => " and c.inOutGrossMarginDifference=# "
    ),
    array(
        "name" => "riskLevel",
        "sql" => " and c.riskLevel=# "
    ),
    array(
        "name" => "workloadDescription",
        "sql" => " and c.workloadDescription=# "
    ),
    array(
        "name" => "projectRequest",
        "sql" => " and c.projectRequest=# "
    ),
    array(
        "name" => "riskAssess",
        "sql" => " and c.riskAssess=# "
    ),
    array(
        "name" => "staffApplicationsProgram",
        "sql" => " and c.staffApplicationsProgram=# "
    ),
    array(
        "name" => "exaDT",
        "sql" => " and c.exaDT=# "
    ),
    array(
        "name" => "exaStatus",
        "sql" => " and c.exaStatus=# "
    ),
    array(
        "name" => "exaStatusArr",
        "sql" => " and c.exaStatus in(arr) "
    ),
    array(
        "name" => "approveId",
        "sql" => " and c.approveId=# "
    ),
    array(
        "name" => "approveName",
        "sql" => " and c.approveName=# "
    ),
    array(
        "name" => "approveTime",
        "sql" => " and c.approveTime=# "
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
        "name" => "createNameLike",
        "sql" => " and c.createName like concat('%',#,'%') "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array(
        "name" => "createTimeLike",
        "sql" => " and c.createTime like BINARY concat('%',#,'%') "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "updateName",
        "sql" => " and c.updateName=# "
    ),
    array(
        "name" => "updateTime",
        "sql" => " and c.updateTime=# "
    ),
    array(
        "name" => "outType",
        "sql" => " and c.outType=# "
    ),
    array(
        "name" => "state",
        "sql" => " and c.state=# "
    ),
    array(
        "name" => "cancelReason",
        "sql" => " and c.cancelReason=# "
    ),
    array(
        "name" => "cancelManName",
        "sql" => " and c.cancelManName=# "
    ),
    array(
        "name" => "cancelManId",
        "sql" => " and c.cancelManId=# "
    ),
    array(
        "name" => "dealDate",
        "sql" => " and c.dealDate=# "
    )
);