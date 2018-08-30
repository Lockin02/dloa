<?php
/**
 * @author Michael
 * @Date 2014年3月6日 星期四 11:22:50
 * @version 1.0
 * @description:租车合同 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.contractNature ,c.contractNatureCode ,c.contractType ,c.contractTypeCode ,c.orderCode ,c.orderTempCode ,c.orderName ,c.principalName ,c.principalId ,c.deptName ,c.deptId ,c.signCompany ,c.signCompanyId ,c.companyProvince ,c.companyProvinceCode ,c.companyCity ,c.companyCityCode ,c.orderMoney ,c.signDate ,c.contractStartDate ,c.contractEndDate ,c.contractUseDay ,c.linkman ,c.phone ,c.address ,c.isNeedStamp ,c.stampType ,c.ownCompany ,c.ownCompanyId ,c.ownCompanyCode ,c.isUseOilcard ,c.oilcardMoney ,c.fundCondition ,c.contractContent ,c.remark ,c.isStamp ,c.status ,c.isTemp ,c.originalId ,c.changeTip ,c.changeReason ,c.isNeedRestamp ,c.isNeedPayables ,c.feeDeptName ,c.feeDeptId ,c.returnMoney ,c.rentalcarId ,c.rentalcarCode ,c.rentUnitPrice ,c.oilPrice ,c.fuelCharge ,c.objCode ,c.signedStatus ,c.signedDate ,c.signedMan ,c.signedManId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,date_format(c.createTime,'%Y-%m-%d') as createDate ,c.projectId ,c.projectName ,c.projectCode ,c.projectType ,c.projectTypeCode ,c.projectManagerId ,c.projectManager ,c.officeName ,c.officeId ,c.payBankName ,c.payBankNum ,c.payMan ,c.payConditions ,c.payType ,c.payTypeCode ,c.payApplyMan from oa_contract_rentcar c where c.isTemp = 0 ",
	"select_excelOut"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.contractNature ,c.contractNatureCode ,c.contractType ,c.contractTypeCode ,c.orderCode ,c.orderTempCode ,c.orderName ,c.principalName ,c.principalId ,c.deptName ,c.deptId ,c.signCompany ,c.signCompanyId ,c.companyProvince ,c.companyProvinceCode ,c.companyCity ,c.companyCityCode ,c.orderMoney ,c.signDate,c.contractStartDate ,c.contractEndDate  ,c.linkman ,c.phone ,c.address ,c.isNeedStamp ,c.stampType ,c.ownCompany ,c.ownCompanyId ,c.ownCompanyCode ,c.fundCondition ,c.contractContent ,c.remark ,c.isStamp ,c.status ,c.isTemp ,c.originalId ,c.changeTip ,c.changeReason ,c.isNeedRestamp ,c.isNeedPayables ,c.feeDeptName ,c.feeDeptId ,c.returnMoney ,c.rentalcarId ,c.rentalcarCode ,c.rentUnitPrice ,c.oilPrice ,c.fuelCharge ,c.objCode ,c.signedStatus ,c.signedDate ,c.signedMan ,c.signedManId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,date_format(c.createTime,'%Y-%m-%d') as createDate ,c.projectId ,c.projectName ,c.projectCode ,c.projectType ,c.projectTypeCode ,c.projectManagerId ,c.projectManager ,c.officeName ,c.officeId from oa_contract_rentcar c where c.isTemp = 0 and c.ExaStatus in('完成','变更审批中') ",
	"select_rentCarInformation" => "select c.id ,c.orderCode ,c.oilPrice ,c.fuelCharge ,c.rentUnitPrice ,r.projectName ,r.projectCode ,r.projectId ,r.rentalProperty  ,r.rentalPropertyCode ,v.suppName ,v.suppCode ,v.id as suppId ,p.officeId ,p.officeName ,p.managerName ,p.managerId ,p.province ,p.provinceId ,p.city ,p.cityId from oa_contract_rentcar c " .
		" left join oa_outsourcing_rentalcar r on c.rentalcarId=r.id " .
		" left join oa_outsourcessupp_vehiclesupp v on c.signCompanyId=v.id " .
		" left join oa_esm_project p on r.projectId=p.id " .
		"where c.isTemp = 0 ",
	//根据项目ID和车牌号获取合同信息
	"select_byPidCar"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.contractNature ,c.contractNatureCode ,c.contractType ,c.contractTypeCode ,c.orderCode ,c.orderTempCode ,c.orderName ,c.principalName ,c.principalId ,c.deptName ,c.deptId ,c.signCompany ,c.signCompanyId ,c.companyProvince ,c.companyProvinceCode ,c.companyCity ,c.companyCityCode ,c.orderMoney ,c.signDate ,c.contractStartDate ,c.contractEndDate ,c.contractUseDay ,c.linkman ,c.phone ,c.address ,c.isNeedStamp ,c.stampType ,c.ownCompany ,c.ownCompanyId ,c.ownCompanyCode ,c.fundCondition ,c.contractContent ,c.remark ,c.isStamp ,c.status ,c.isTemp ,c.originalId ,c.changeTip ,c.changeReason ,c.isNeedRestamp ,c.isNeedPayables ,c.feeDeptName ,c.feeDeptId ,c.returnMoney ,c.rentalcarId ,c.rentalcarCode ,c.rentUnitPrice ,c.oilPrice ,c.fuelCharge ,c.objCode ,c.signedStatus ,c.signedDate ,c.signedMan ,c.signedManId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,date_format(c.createTime,'%Y-%m-%d') as createDate from oa_contract_rentcar c ".
		" left join oa_contract_vehicle v on v.contractId=c.id ".
		" left join oa_outsourcing_rentalcar r on r.id=c.rentalcarId ".
		" where c.isTemp = 0 and v.isTemp=0 ",
    "select_financeInfo" => "select
            c.id,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.contractNature,
            c.contractNatureCode,c.contractType,c.contractTypeCode,c.orderCode,c.orderTempCode,c.orderName,
            c.principalName,c.principalId,c.deptName,c.deptId,c.signCompany,c.signCompanyId,c.companyProvince,c.companyProvinceCode,
            c.companyCity,c.companyCityCode,c.orderMoney,c.signDate,c.contractStartDate,c.contractEndDate,c.contractUseDay,
            c.linkman,c.phone,c.address,c.isNeedStamp,c.stampType,c.ownCompany,c.ownCompanyId,c.ownCompanyCode,c.isUseOilcard,
            c.oilcardMoney,c.fundCondition,c.contractContent,c.remark,c.isStamp,c. status,c.isTemp,c.originalId,
            c.changeTip,c.changeReason,c.isNeedRestamp,c.isNeedPayables,c.feeDeptName,c.feeDeptId,c.returnMoney,c.rentalcarId,
            c.rentalcarCode,c.rentUnitPrice,c.oilPrice,c.fuelCharge,c.objCode,c.signedStatus,c.signedDate,c.signedMan,
            c.signedManId,c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,
            date_format(c.createTime, '%Y-%m-%d') AS createDate,c.projectId,c.projectName,c.projectCode,c.projectType,
            c.projectTypeCode,c.projectManagerId,c.projectManager,c.officeName,c.officeId,c.payBankName,c.payBankNum,
            c.payMan,c.payConditions,c.payType,c.payTypeCode,c.payApplyMan,
            if(pa.payApplyMoney is null,0,pa.payApplyMoney) +c.initPayMoney as payApplyMoney,
            if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney  as payedMoney,
            if(invo.invotherMoney is null ,0,invo.invotherMoney)  as invotherMoney,
            if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) as confirmInvotherMoney,
            if(p.payedMoney is null,0,p.payedMoney) + c.initPayMoney -
                (if(invo.confirmInvotherMoney is null ,0,invo.confirmInvotherMoney) )
                - c.returnMoney as needInvotherMoney
        from
            oa_contract_rentcar c
        left join
            (select p.objId,sum(if(i.payFor <> 'FKLX-03' ,p.money,-p.money)) as payApplyMoney from oa_finance_payablesapply i inner join oa_finance_payablesapply_detail p on i.id =p.payapplyId where p.objId <> 0 and p.objType = 'YFRK-06' and i.ExaStatus <> '打回' and i.status not in('FKSQD-04','FKSQD-05') group by p.objId) pa on c.id = pa.objId
        left join
            (select p.objId,sum(if(i.formType <>'CWYF-03', p.money,-p.money)) as payedMoney from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where p.objId <> 0 and p.objType = 'YFRK-06' group by p.objId) p on c.id = p.objId
        left join
            (
                select i.objId,
                sum(if(i.allCount = 0,i.amount,i.allCount)) as invotherMoney,
                SUM(IF(c.ExaStatus = 1,if(i.allCount = 0,i.amount,i.allCount),0)) AS confirmInvotherMoney
                from oa_finance_invother_detail i LEFT JOIN oa_finance_invother c on i.mainId = c.id
                where i.objId <>0 and i.objType = 'YFQTYD03' group by i.objId
            ) invo on c.id = invo.objId
        where c.isTemp = 0"
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "idArr",
		"sql" => " and c.id in(arr) "
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
		"name" => "contractNature",
		"sql" => " and c.contractNature=# "
	),
	array(
		"name" => "contractNatureCode",
		"sql" => " and c.contractNatureCode=# "
	),
	array(
		"name" => "contractType",
		"sql" => " and c.contractType=# "
	),
	array(
		"name" => "contractTypeCode",
		"sql" => " and c.contractTypeCode=# "
	),
	array(
		"name" => "orderCode",
		"sql" => " and c.orderCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "orderTempCode",
		"sql" => " and c.orderTempCode=# "
	),
	array(
		"name" => "orderName",
		"sql" => " and c.orderName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "principalName",
		"sql" => " and c.principalName=# "
	),
	array(
		"name" => "principalId",
		"sql" => " and c.principalId=# "
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
		"name" => "signCompany",
		"sql" => " and c.signCompany LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "signCompanyId",
		"sql" => " and c.signCompanyId=# "
	),
	array(
		"name" => "companyProvince",
		"sql" => " and c.companyProvince=# "
	),
	array(
		"name" => "companyProvinceCode",
		"sql" => " and c.companyProvinceCode=# "
	),
	array(
		"name" => "companyCity",
		"sql" => " and c.companyCity=# "
	),
	array(
		"name" => "companyCityCode",
		"sql" => " and c.companyCityCode=# "
	),
	array(
		"name" => "orderMoney",
		"sql" => " and c.orderMoney=# "
	),
	array(
		"name" => "signDate",
		"sql" => " and c.signDate=# "
	),
	array(
		"name" => "signDateSea",
		"sql" => " and c.signDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "signDateSta",
		"sql" => " and c.signDate >= BINARY # "
	),
	array(
		"name" => "signDateEnd",
		"sql" => " and c.signDate <= BINARY # "
	),
	array(
		"name" => "contractStartDate",
		"sql" => " and c.contractStartDate=# "
	),
	array(
		"name" => "contractEndDate",
		"sql" => " and c.contractEndDate=# "
	),
	array(
		"name" => "contractUseDay",
		"sql" => " and c.contractUseDay=# "
	),
	array(
		"name" => "linkman",
		"sql" => " and c.linkman=# "
	),
	array(
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array(
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array(
		"name" => "isNeedStamp",
		"sql" => " and c.isNeedStamp=# "
	),
	array(
		"name" => "stampType",
		"sql" => " and c.stampType=# "
	),
	array(
		"name" => "ownCompany",
		"sql" => " and c.ownCompany=# "
	),
	array(
		"name" => "ownCompanyArr",
		"sql" => " and c.ownCompany in(arr) "
	),
	array(
		"name" => "ownCompanyId",
		"sql" => " and c.ownCompanyId=# "
	),
	array(
		"name" => "ownCompanyCode",
		"sql" => " and c.ownCompanyCode=# "
	),
	array(
		"name" => "fundCondition",
		"sql" => " and c.fundCondition=# "
	),
	array(
		"name" => "contractContent",
		"sql" => " and c.contractContent=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "isStamp",
		"sql" => " and c.isStamp=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
	),
	array(
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array(
		"name" => "originalId",
		"sql" => " and c.originalId=# "
	),
	array(
		"name" => "changeTip",
		"sql" => " and c.changeTip=# "
	),
	array(
		"name" => "changeReason",
		"sql" => " and c.changeReason=# "
	),
	array(
		"name" => "isNeedRestamp",
		"sql" => " and c.isNeedRestamp=# "
	),
	array(
		"name" => "isNeedPayables",
		"sql" => " and c.isNeedPayables=# "
	),
	array(
		"name" => "feeDeptName",
		"sql" => " and c.feeDeptName=# "
	),
	array(
		"name" => "feeDeptId",
		"sql" => " and c.feeDeptId=# "
	),
	array(
		"name" => "returnMoney",
		"sql" => " and c.returnMoney=# "
	),
	array(
		"name" => "rentalcarId",
		"sql" => " and c.rentalcarId=# "
	),
	array(
		"name" => "rentalcarCode",
		"sql" => " and c.rentalcarCode=# "
	),
	array(
		"name" => "rentUnitPrice",
		"sql" => " and c.rentUnitPrice=# "
	),
	array(
		"name" => "oilPrice",
		"sql" => " and c.oilPrice=# "
	),
	array(
		"name" => "fuelCharge",
		"sql" => " and c.fuelCharge=# "
	),
	array(
		"name" => "signedStatus",
		"sql" => " and c.signedStatus=# "
	),
	array(
		"name" => "signedDate",
		"sql" => " and c.signedDate=# "
	),
	array(
		"name" => "signedMan",
		"sql" => " and c.signedMan=# "
	),
	array(
		"name" => "signedManId",
		"sql" => " and c.signedManId=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
    array(
        "name" => "ExaStatusArr",
        "sql" => " and c.ExaStatus in(arr) "
    ),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "createDateSta",
		"sql" => " and date_format(c.createTime,'%Y-%m-%d') >= BINARY # "
	),
	array(
		"name" => "createDateEnd",
		"sql" => " and date_format(c.createTime,'%Y-%m-%d') <= BINARY # "
	),
	array(
		"name" => "createTimeSea",
		"sql" => " and c.createTime LIKE BINARY CONCAT('%',#,'%') "
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
		"name" => "projectName",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectIdEq",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and r.projectId=# "
	),
	array(
		"name" => "carNum",
		"sql" => " and v.carNumber=# "
	),
	array(
		"name" => "contractManage",
		"sql" => " and (c.status!=5 and c.status!=7)"
	)
)
?>