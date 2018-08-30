<?php
/**
 * ²É¹ºÉêÇësql
 */
$sql_arr = array(
    "select_apply" => "select c.ifShow,c.backReason,c.purchaseDept,c.purchaseDeptId,c.agencyCode,c.agencyName,c.address,c.relDocId,c.relDocCode,c.amounts,c.id,c.formCode,c.applyDetId,c.applyDetName,c.applicantId,c.applicantName,c.applyTime,c.planCode,c.planYear,c.useDetId,c.useDetName,c.userId,c.userName,c.userTel,c.estimatPrice,c.moneyAll,c.purchCategory,c.purchaseType,c.assetUseCode,c.assetUse,c.assetClass,c.remark,c.acceptDate,c.orderDate,c.arrivaDate,c.approvalId,c.approvalName,c.state,c.ExaStatus,c.ExaDT,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,c.importProject,c.moneyProject,c.otherProject,c.purchType,c.isRd ,c.projectId ,c.projectCode ,c.projectName,c.agencyCode from oa_asset_purchase_apply c where  1=1 ",
    "select_apply_all" => "select c.ifShow,c.purchaseDept,c.purchaseDeptId,c.agencyCode,c.agencyName,c.address,c.relDocId,c.relDocCode,c.amounts,c.id,c.formCode,c.applyDetId,c.applyDetName,c.applicantId,c.applicantName
			,c.applyTime,c.planCode,c.planYear,c.useDetId,c.useDetName,c.userId,c.userName,c.userTel,c.estimatPrice,c.moneyAll,c.purchCategory,c.purchaseType
			,c.assetUseCode,c.assetUse,c.assetClass,c.remark,c.acceptDate,c.orderDate,c.arrivaDate,c.approvalId,c.approvalName,c.state,c.ExaStatus,c.ExaDT
			,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,c.importProject,c.moneyProject,c.otherProject,c.purchType,c.isRd
			,c.projectId ,c.projectCode ,c.projectName,c.productSureStatus,c.agencyCode from oa_asset_purchase_apply c where  1=1 ",
);
$condition_arr = array(
    array(
        "name" => "agencyCodeArr",
        "sql" => " and c.agencyCode in(arr) "
    ),
    array(
        "name" => "purchaseDepts",
        "sql" => " and c.purchaseDept in(arr) "
    ),
    array(
        "name" => "ifShow",
        "sql" => " and c.ifShow=# "
    ),
    array(
        "name" => "agencyCode",
        "sql" => " and c.agencyCode=# "
    ),
    array(
        "name" => "agencyName",
        "sql" => " and c.agencyName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "relDocId",
        "sql" => " and c.relDocId=# "
    ),
    array(
        "name" => "relDocCode",
        "sql" => " and c.relDocCode like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "formCode",
        "sql" => " and c.formCode like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "applyDetId",
        "sql" => " and c.applyDetId=# "
    ),
    array(
        "name" => "applyDetName",
        "sql" => " and c.applyDetName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "applicantName",
        "sql" => " and c.applicantName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "applicantId",
        "sql" => " and c.applicantId =# "
    ),
    array(
        "name" => "applyTime",
        "sql" => " and c.applyTime like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "planCode",
        "sql" => " and c.planCode like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "planYear",
        "sql" => " and c.planYear like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "useDetId",
        "sql" => " and c.useDetId =# "
    ),
    array(
        "name" => "useDetName",
        "sql" => " and c.useDetName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "useDetId",
        "sql" => " and c.useDetId like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "userId",
        "sql" => " and c.userId =# "
    ),
    array(
        "name" => "userName",
        "sql" => " and c.userName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "userTel",
        "sql" => " and c.userTel like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "estimatPrice",
        "sql" => " and c.estimatPrice  like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "moneyAll",
        "sql" => " and c.moneyAll like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "useDetName",
        "sql" => " and c.useDetName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "userName",
        "sql" => " and c.userName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "assetUseCode",
        "sql" => " and c.assetUseCode =#"
    ),
    array(
        "name" => "purchCategory",
        "sql" => " and c.purchCategory like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "purchaseType",
        "sql" => " and c.purchaseType like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "assetUse",
        "sql" => " and c.assetUse like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "assetClass",
        "sql" => " and c.assetClass like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "acceptDate",
        "sql" => " and c.acceptDate like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "orderDate",
        "sql" => " and c.orderDate like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "arrivaDate",
        "sql" => " and c.arrivaDate like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "approvalId",
        "sql" => " and c.approvalId like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "approvalName",
        "sql" => " and c.approvalName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "state",
        "sql" => " and c.state like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "stateNoIn",
        "sql" => " and c.state not in(arr)"
    ),
    array(
        "name" => "ExaStatus",
        "sql" => " and c.ExaStatus like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "ExaDT",
        "sql" => " and c.ExaDT like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId =#"
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "updateName",
        "sql" => " and c.updateName like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "updateId",
        "sql" => " and c.updateId like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "updateTime",
        "sql" => " and c.updateTime like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "importProject",
        "sql" => " and c.importProject like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "moneyProject",
        "sql" => " and c.moneyProject like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "otherProject",
        "sql" => " and c.otherProject like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "deptCon",
        "sql" => "$"
    ),
    array(
        "name" => "productName",
        "sql" => "and c.id in(select applyId from oa_asset_purchase_apply_item where productName like CONCAT('%',#,'%'))"
    ),
    array(
        "name" => "purchType",
        "sql" => " and c.purchType =#"
    ),
    array(
        "name" => "productSureUserId",
        "sql" => " and c.productSureUserId= # "
    ),
    array(
        "name" => "productSureStatus",
        "sql" => " and c.productSureStatus= # "
    ),
    array(
        "name" => "productSureStatusArr",
        "sql" => " and c.productSureStatus in(arr) "
    ),
    array(
        "name" => "totalManagerId",
        "sql" => " and FIND_IN_SET(#,c.totalmanagerid) > 0 "
    )
)
?>