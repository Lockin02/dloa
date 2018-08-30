<?php
/**
 * @author Show
 * @Date 2011年11月26日 星期六 17:00:10
 * @version 1.0
 * @description:项目章程(oa_esm_charter) sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.contractId ,c.contractCode ,c.rObjCode ,c.workRate ,c.projectCode ,
            c.projectName ,c.customerName ,c.customerId ,c.incomeType ,c.incomeTypeName ,c.officeName ,c.officeId ,c.deptName ,
            c.deptId ,c.managerName ,c.managerId ,c.planBeginDate ,c.planEndDate ,c.projectObjectives ,c.description ,
            c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime
        from oa_esm_charter c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
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
        "name" => "rObjCode",
        "sql" => " and c.rObjCode=# "
    ),
    array(
        "name" => "workRate",
        "sql" => " and c.workRate=# "
    ),
    array(
        "name" => "projectCode",
        "sql" => " and c.projectCode=# "
    ),
    array(
        "name" => "projectCodeEq",
        "sql" => " and c.projectCode=# "
    ),
    array(
        "name" => "projectName",
        "sql" => " and c.projectName like CONCAT('%',#,'%') "
    ), array(
        "name" => "customerName",
        "sql" => " and c.customerName like CONCAT('%',#,'%') "
    ), array(
        "name" => "customerId",
        "sql" => " and c.customerId like=# "
    ),
    array(
        "name" => "officeName",
        "sql" => " and c.officeName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "officeId",
        "sql" => " and c.officeId=# "
    ),
    array(
        "name" => "deptName",
        "sql" => " and c.deptName=# "
    ),
    array(
        "name" => "deptId",
        "sql" => " and c.deptId=# "
    ),
    array(
        "name" => "managerName",
        "sql" => " and c.managerName=# "
    ),
    array(
        "name" => "managerId",
        "sql" => " and c.managerId=# "
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
        "name" => "projectObjectives",
        "sql" => " and c.projectObjectives=# "
    ),
    array(
        "name" => "description",
        "sql" => " and c.description=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
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
    )
);