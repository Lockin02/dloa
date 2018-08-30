<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:07:02
 * @version 1.0
 * @description:工作量确认单明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentCode ,c.parentId ,c.suppVerifyId,c.officeId ,c.officeName ,c.country ,c.countryId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.place ,c.outsourcing ,c.outsourcingName ,c.outsourceContractId ,c.outsourceContractCode ,c.outsourceContract ,c.outsourceSuppId ,c.outsourceSuppCode ,c.outsourceSupp ,c.principalId ,c.principal ,c.scheduleTotal ,c.presentSchedule ,c.userId ,c.userName ,c.beginDate ,c.endDate ,c.feeDay ,c.beginDatePM ,c.endDatePM ,c.feeDayPM ,c.budgetFee ,c.confirmFee ,c.feeTotal ,c.confirmFeePM ,c.feeTotalPM ,c.feeRemark ,c.projectId ,c.projectCode ,c.projectName ,c.remark ,c.status ,c.statusName ,c.closeDate ,c.closeDesc ,c.ExaStatus ,c.ExaDT ,c.approveId ,c.approveName ,c.approveTime ,c.managerId ,c.managerName ,c.managerAuditState ,c.managerAuditDate ,c.managerAuditRemark ,c.serverManagerId ,c.serverManagerName ,c.serverAuditState ,c.serverAuditDate ,c.serverAuditRemark ,c.areaManager ,c.areaManagerId ,c.areaAuditState ,c.areaAuditDate ,c.areaAuditRemark ,c.purchAuditState ,c.purchAuditDate ,c.purchAuditRemark,w.status as parentState,w.beginDate as parentBeginDate,w.endDate as parentEndDate,c.ids,c.personnelType ,c.personnelTypeName ,c.settlementState,c.suppVerifyId,s.status as suppState  from oa_outsourcing_workverify_detail c left join oa_outsourcing_workverify w on c.parentId=w.id left join oa_outsourcing_suppverify s on c.suppVerifyId=s.id where 1=1 ",

         "select_project"=>"select c.id ,c.parentCode ,c.parentId ,c.suppVerifyId,c.officeId ,c.officeName ,c.country ,c.countryId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.place ,c.outsourcing ,c.outsourcingName ,c.outsourceContractId ,c.outsourceContractCode ,c.outsourceContract ,c.outsourceSuppId ,c.outsourceSuppCode ,c.outsourceSupp ,c.principalId ,c.principal ,c.scheduleTotal ,c.presentSchedule ,c.userId ,c.userName ,c.beginDate ,c.endDate ,c.feeDay ,c.budgetFee ,c.confirmFee ,c.feeTotal ,c.feeRemark ,c.projectId ,c.projectCode ,c.projectName ,c.remark ,c.status ,c.statusName ,c.closeDate ,c.closeDesc ,c.ExaStatus ,c.ExaDT ,c.approveId ,c.approveName ,c.approveTime ,c.managerId ,c.managerName ,c.managerAuditState ,c.managerAuditDate ,c.managerAuditRemark,p.managerId,p.managerName,w.status as parentState,c.personnelType ,c.personnelTypeName  from oa_outsourcing_workverify_detail c left join oa_outsourcing_workverify w on c.parentId=w.id left join oa_esm_project p on c.projectId=p.id where 1=1 ",

         "select_suppVerify"=>"select c.userName as pesonName ,c.feeDay as totalDay ,c.confirmFee as outBudgetPrice ,c.beginDate ,c.endDate from oa_outsourcing_workverify_detail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "suppVerifyId",
   		"sql" => " and c.suppVerifyId=# "
   	  ),
   array(
   		"name" => "officeId",
   		"sql" => " and c.officeId=# "
   	  ),
   array(
   		"name" => "officeIdArr",
   		"sql" => " and c.officeId in(arr) "
   	  ),
   array(
   		"name" => "officeName",
   		"sql" => " and c.officeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "country",
   		"sql" => " and c.country=# "
   	  ),
   array(
   		"name" => "countryId",
   		"sql" => " and c.countryId=# "
   	  ),
   array(
   		"name" => "province",
   		"sql" => " and c.province like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "provinceArr",
   		"sql" => " and c.province in(arr) "
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
   		"name" => "place",
   		"sql" => " and c.place=# "
   	  ),
   array(
   		"name" => "outsourcing",
   		"sql" => " and c.outsourcing like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "outsourcingArr",
   		"sql" => " and c.outsourcing in(arr) "
   	  ),
   array(
   		"name" => "outsourcingName",
   		"sql" => " and c.outsourcingName=# "
   	  ),
   array(
   		"name" => "outsourceContractId",
   		"sql" => " and c.outsourceContractId=# "
   	  ),
   array(
   		"name" => "outsourceContractCode",
   		"sql" => " and c.outsourceContractCode=# "
   	  ),
   array(
   		"name" => "outsourceContract",
   		"sql" => " and c.outsourceContract=# "
   	  ),
   array(
   		"name" => "outsourceSuppId",
   		"sql" => " and c.outsourceSuppId=# "
   	  ),
   array(
   		"name" => "outsourceSuppCode",
   		"sql" => " and c.outsourceSuppCode=# "
   	  ),
   array(
   		"name" => "outsourceSupp",
   		"sql" => " and c.outsourceSupp like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "principalId",
   		"sql" => " and c.principalId=# "
   	  ),
   array(
   		"name" => "principal",
   		"sql" => " and c.principal like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "scheduleTotal",
   		"sql" => " and c.scheduleTotal=# "
   	  ),
   array(
   		"name" => "presentSchedule",
   		"sql" => " and c.presentSchedule=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
   	  ),
   array(
   		"name" => "feeDay",
   		"sql" => " and c.feeDay=# "
   	  ),
   array(
   		"name" => "budgetFee",
   		"sql" => " and c.budgetFee=# "
   	  ),
   array(
   		"name" => "confirmFee",
   		"sql" => " and c.confirmFee=# "
   	  ),
   array(
   		"name" => "feeTotal",
   		"sql" => " and c.feeTotal=# "
   	  ),
   array(
   		"name" => "feeRemark",
   		"sql" => " and c.feeRemark=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectIdArr",
   		"sql" => " and c.projectId in(arr) "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projecttName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "closeDesc",
   		"sql" => " and c.closeDesc=# "
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
   		"name" => "managerId",
   		"sql" => " and c.managerId=# "
   	  ),
   array(
   		"name" => "managerName",
   		"sql" => " and c.managerName=# "
   	  ),
   array(
   		"name" => "managerAuditState",
   		"sql" => " and c.managerAuditState=# "
   	  ),
   array(
   		"name" => "managerAuditDate",
   		"sql" => " and c.managerAuditDate=# "
   	  ),
   array(
   		"name" => "managerAuditRemark",
   		"sql" => " and c.managerAuditRemark=# "
   	  ),
   array(
   		"name" => "serverManagerId",
   		"sql" => " and c.serverManagerId=# "
   	  ),
   array(
   		"name" => "serverManagerName",
   		"sql" => " and c.serverManagerName=# "
   	  ),
   array(
   		"name" => "serverAuditState",
   		"sql" => " and c.serverAuditState=# "
   	  ),
   array(
   		"name" => "serverAuditDate",
   		"sql" => " and c.serverAuditDate=# "
   	  ),
   array(
   		"name" => "serverAuditRemark",
   		"sql" => " and c.serverAuditRemark=# "
   	  ),
   array(
   		"name" => "areaManager",
   		"sql" => " and c.areaManager=# "
   	  ),
   array(
   		"name" => "areaManagerId",
   		"sql" => " and c.areaManagerId=# "
   	  ),
   array(
   		"name" => "areaAuditState",
   		"sql" => " and c.areaAuditState=# "
   	  ),
   array(
   		"name" => "areaAuditDate",
   		"sql" => " and c.areaAuditDate=# "
   	  ),
   array(
   		"name" => "areaAuditRemark",
   		"sql" => " and c.areaAuditRemark=# "
   	  ),
   array(
   		"name" => "parentState",
   		"sql" => " and w.status=# "
   	  ),
   array(
   		"name" => "suppState",
   		"sql" => " and s.status=# "
   	  ),
   array(
   		"name" => "parentStateArr",
   		"sql" => " and w.status in(arr) "
   	  ),
   array(
   		"name" => "suppStateArr",
   		"sql" => " and s.status in(arr) "
   	  ),
	array (
		"name" => "esmManagerIdFind",
		"sql" => "and (find_in_set(#,p.managerId) > 0 )"
	),
   array(
   		"name" => "purchAuditState",
   		"sql" => " and c.purchAuditState=# "
   	  ),
   array(
   		"name" => "purchAuditDate",
   		"sql" => " and c.purchAuditDate=# "
   	  ),
   array(
   		"name" => "purchAuditRemark",
   		"sql" => " and c.purchAuditRemark=# "
   	  ),
   array(
   		"name" => "personnelType",
   		"sql" => " and c.personnelType=# "
   	  ),
   array(
   		"name" => "settlementState",
   		"sql" => " and c.settlementState=# "
   	  ),
   array(
   		"name" => "settlementStateNeq",
   		"sql" => " and c.settlementState<># "
   	  )
)
?>