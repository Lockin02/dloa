<?php
/**
 * @author Administrator
 * @Date 2012-05-15 14:04:07
 * @version 1.0
 * @description:试用项目 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.isFail,c.projectCode ,c.serCon,c.affirmMoney,c.executive,c.projectName,
            c.planContractMoney,c.chanceCode,
            c.planSignDate,c.ExaStatus,c.ExaDT,c.applyName ,c.applyNameId ,c.customerName ,c.customerId ,c.customerWay,
            c.customerType,c.customerTypeName ,c.province ,c.provinceId ,c.city ,c.cityId ,c.areaName ,c.areaPrincipal,
            c.areaPrincipalId ,c.areaCode,c.beginDate ,c.closeDate ,c.projectDescribe ,c.budgetMoney ,c.updateTime,
            c.updateName ,c.updateId ,c.createTime ,c.createName,c.createId,c.status,c.projectProcess,c.chanceId,
            c.SingleType,c.projectDays, c.productLine,c.turnDate,c.turnStatus,c.turnProject,
            datediff(date_format(NOW(),'%Y-%m-%d'),p.actBeginDate)  as actDate,
            p.feeAll, if(ex.extensionNum is null,0,ex.extensionNum) as extensionNum, ce.winRate
        from oa_trialproject_trialproject c
        left join oa_sale_chance ce on c.chanceId = ce.id
        left join
            (select count(id) as extensionNum,trialprojectId from oa_trialproject_extension GROUP BY trialprojectId) ex
            on c.id=ex.trialprojectId
        left join oa_esm_project p on c.id = p.contractid and p.contractType = 'GCXMYD-04'
        where 1=1"
);

$condition_arr = array(
    array(
        "name" => "chanceId",
        "sql" => " and c.chanceId=# "
    ),
    array(
        "name" => "isFail",
        "sql" => " and c.isFail=# "
    ),
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "serConArr",
        "sql" => "and c.serCon in(arr)"
    ),
    array(
        "name" => "serCon",
        "sql" => "and c.serCon=# "
    ),
    array(
        "name" => "turnStatus",
        "sql" => "and c.turnStatus=# "
    ),
    array(
        "name" => "ExaStatus",
        "sql" => "and c.ExaStatus=# "
    ),
    array(
        "name" => "ExaStatusArr",
        "sql" => "and c.ExaStatus in(arr) "
    ),
    array(
        "name" => "status",
        "sql" => "and c.status =#"
    ),
    array(
        "name" => "projectCode",
        "sql" => " and c.projectCode like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "projectName",
        "sql" => " and c.projectName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "applyName",
        "sql" => " and c.applyName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "applyNameId",
        "sql" => " and c.applyNameId=# "
    ),
    array(
        "name" => "customerName",
        "sql" => " and c.customerName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "customerId",
        "sql" => " and c.customerId=# "
    ),
    array(
        "name" => "customerWay",
        "sql" => " and c.customerWay=# "
    ),
    array(
        "name" => "customerType",
        "sql" => " and c.customerType=# "
    ),
    array(
        "name" => "customerTypeName",
        "sql" => " and c.customerTypeName=# "
    ),
    array(
        "name" => "province",
        "sql" => " and c.province=# "
    ),
    array(
        "name" => "provinceId",
        "sql" => " and c.provinceId=# "
    ),
    array(
        "name" => "city",
        "sql" => " and c.city=# "
    ),
    array(
        "name" => "cityId",
        "sql" => " and c.cityId=# "
    ),
    array(
        "name" => "areaName",
        "sql" => " and c.areaName=# "
    ),
    array(
        "name" => "areaPrincipal",
        "sql" => " and c.areaPrincipal=# "
    ),
    array(
        "name" => "areaPrincipalId",
        "sql" => " and c.areaPrincipalId=# "
    ),
    array(
        "name" => "areaCode",
        "sql" => " and c.areaCode=# "
    ),
    array(
        "name" => "beginDate",
        "sql" => " and c.beginDate=# "
    ),
    array(
        "name" => "closeDate",
        "sql" => " and c.closeDate=# "
    ),
    array(
        "name" => "projectDescribe",
        "sql" => " and c.projectDescribe=# "
    ),
    array(
        "name" => "budgetMoney",
        "sql" => " and c.budgetMoney=# "
    ),
    array(
        "name" => "updateTime",
        "sql" => " and c.updateTime=# "
    ),
    array(
        "name" => "updateName",
        "sql" => " and c.updateName=# "
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(//自定义条件
        "name" => "mySearchCondition",
        "sql" => "$"
    ),
    array(//自定义条件
        "name" => "productLineArr",
        "sql" => "and c.productLine in(arr)"
    )
);