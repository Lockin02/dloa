<?php
/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:40:56
 * @version 1.0
 * @description:租车登记表 sql配置文件
 */
$defaultForRecordsSql = <<<EOT
select t.id AS rentalContractId,t.rentUnitPriceCalWay,t.contractNature AS rentalContractNature,t.orderCode AS rentalContractCode,t.contractUseDay,t.rentUnitPrice,c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.allregisterId ,c.contractType ,c.contractTypeCode ,c.suppId ,c.suppCode ,c.suppName ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManagerId ,c.projectManager ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.useCarNum ,c.driverName ,c.rentalContractCode ,c.rentalContractId ,c.rentalProperty ,c.rentalPropertyCode ,c.carNum ,c.drivingReason ,c.effectLogTime ,c.startMileage ,c.endMileage ,c.effectMileage ,c.gasolinePrice ,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.mealsCost ,c.accommodationCost ,c.remark ,c.estimate ,c.estimateTime ,c.state ,c.deductInformation ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.shortRent ,c.isCardPay ,c.carModel ,c.carModelCode ,c.overtimePay ,c.specialGas,
if(t.rentUnitPriceCalWay is null,c.shortRent,t.rentUnitPrice) as rentalCarCost
FROM oa_outsourcing_register c 
	LEFT JOIN (
			SELECT * FROM
					(
					SELECT
							d.id,d.contractNature,d.contractNatureCode,d.contractType,d.contractTypeCode,d.orderCode,d.orderTempCode,d.orderName,d.principalName,d.principalId,d.deptName,d.deptId,d.signCompany,d.signCompanyId,d.companyProvince,d.companyProvinceCode,d.companyCity,d.companyCityCode,d.orderMoney,d.signDate,d.contractStartDate,if(d.contractStartDate <> '',"1","") AS shortRentTip,d.contractEndDate,d.contractUseDay,d.linkman,d.phone,d.address,d.isNeedStamp,d.stampType,d.ownCompany,d.ownCompanyId,d.ownCompanyCode,d.fundCondition,d.contractContent,d.remark,d.isStamp,d.STATUS,d.isTemp,d.originalId,d.changeTip,d.changeReason,d.isNeedRestamp,d.isNeedPayables,d.feeDeptName,d.feeDeptId,d.returnMoney,d.rentalcarId,d.rentalcarCode,d.rentUnitPriceCalWay,d.rentUnitPrice,d.oilPrice,d.fuelCharge,d.objCode,d.signedStatus,d.signedDate,d.signedMan,d.signedManId,d.ExaStatus,d.ExaDT,d.createId,d.createName,d.createTime,d.updateId,d.updateName,d.updateTime,v.carNumber,r.projectId,d.projectCode,p.contractType AS projectType,p.id AS Pid,
					IF ( p.contractType = 'GCXMYD-04', m.projectId, p.id  ) AS esmprojectId 
					FROM
							oa_contract_rentcar d LEFT JOIN oa_contract_vehicle v ON v.contractId = d.id LEFT JOIN oa_outsourcing_rentalcar r ON r.id = d.rentalcarId LEFT JOIN oa_esm_project p ON p.id = d.projectId LEFT JOIN oa_esm_project_mapping m ON p.id = m.pkProjectId 
					WHERE d.isTemp = 0 AND d.STATUS = 2 AND v.isTemp = 0 ORDER BY d.id DESC 
					) innert 
			WHERE innert.orderCode <> '' GROUP BY innert.orderCode,innert.carNumber 
	) t ON ( t.carNumber = c.carNum AND t.contractStartDate <= c.useCarDate AND t.contractEndDate >= c.useCarDate 
      AND IF (t.projectType = 'GCXMYD-04',(t.Pid = c.projectId OR t.esmprojectId = c.projectId),t.projectCode = c.projectCode))
WHERE 1 = 1 
EOT;


$sql_arr = array (
    "select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.allregisterId ,c.contractType ,c.contractTypeCode ,c.suppId ,c.suppCode ,c.suppName ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManagerId ,c.projectManager ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.useCarNum ,c.driverName ,c.rentalContractCode ,c.rentalContractId ,c.rentalProperty ,c.rentalPropertyCode ,c.carNum ,c.drivingReason ,c.effectLogTime ,c.startMileage ,c.endMileage ,c.effectMileage ,c.gasolinePrice ,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.rentalCarCost ,c.mealsCost ,c.accommodationCost ,c.remark ,c.estimate ,c.estimateTime ,c.state ,c.deductInformation ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.shortRent ,c.isCardPay ,c.carModel ,c.carModelCode ,c.overtimePay ,c.specialGas from oa_outsourcing_register c where 1=1",
    "select_default_forRecords"=> $defaultForRecordsSql,

	//按月统计已经提交的租车登记记录(如果有对应合同的话也会关联上)
	"select_Month"=>"select c.id ,c.allregisterId ,c.suppId ,c.suppCode ,c.suppName ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManager ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.useCarNum ,c.driverName ,c.rentalProperty ,c.rentalPropertyCode ,c.carNum ,c.drivingReason ,c.effectLogTime ,c.remark ,c.estimate ,c.estimateTime ,c.state ,c.deductInformation ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.isCardPay ,c.carModel ,c.carModelCode " .
		",SUM(c.shortRent) as shortRent" .
		",(SUM(c.effectMileage)*if(c.rentalPropertyCode='ZCXZ-01' ,t.fuelCharge ,c.gasolineKMPrice)) as gasolineKMCost" .
		",SUM(c.effectMileage) as effectMileage " .
		",SUM(c.reimbursedFuel) as reimbursedFuel " .
		",SUM(c.parkingCost) as parkingCost " .
        ",SUM(c.tollCost) as tollCost " .
		",SUM(c.mealsCost) as mealsCost " .
		",SUM(c.overtimePay) as overtimePay " .
		",SUM(c.specialGas) as specialGas " .
		",SUM(c.accommodationCost) as accommodationCost " .
		",SUM(c.effectLogTime) as effectLogTime " .
		",(SUM(c.effectMileage)*t.fuelCharge+SUM(c.reimbursedFuel)+SUM(c.parkingCost)+SUM(c.tollCost)+SUM(c.mealsCost)+SUM(c.accommodationCost)+SUM(c.overtimePay)+SUM(c.specialGas)+c.rentalCarCost) as allCost " .
		",c.rentalCarCost ,t.oilPrice as gasolinePrice ,if(c.rentalPropertyCode='ZCXZ-01' ,t.fuelCharge ,c.gasolineKMPrice) as gasolineKMPrice ,t.id as rentalContractId, t.rentUnitPriceCalWay, t.contractNature AS rentalContractNature,t.orderCode as rentalContractCode ,t.contractUseDay ,COUNT(c.id) as registerNum " .
		" from oa_outsourcing_register c" .
		" left join (SELECT * FROM(
			select d.id ,d.contractNature ,d.contractNatureCode ,d.contractType ,d.contractTypeCode ,d.orderCode ,d.orderTempCode ,d.orderName ,d.principalName ,d.principalId ,d.deptName ,d.deptId ,d.signCompany ,d.signCompanyId ,d.companyProvince ,d.companyProvinceCode ,d.companyCity ,d.companyCityCode ,d.orderMoney ,d.signDate ,d.contractStartDate ,d.contractEndDate ,d.contractUseDay ,d.linkman ,d.phone ,d.address ,d.isNeedStamp ,d.stampType ,d.ownCompany ,d.ownCompanyId ,d.ownCompanyCode ,d.fundCondition ,d.contractContent ,d.remark ,d.isStamp ,d.status ,d.isTemp ,d.originalId ,d.changeTip ,d.changeReason ,d.isNeedRestamp ,d.isNeedPayables ,d.feeDeptName ,d.feeDeptId ,d.returnMoney ,d.rentalcarId ,d.rentalcarCode, d.rentUnitPriceCalWay ,d.rentUnitPrice ,d.oilPrice ,d.fuelCharge ,d.objCode ,d.signedStatus ,d.signedDate ,d.signedMan ,d.signedManId ,d.ExaStatus ,d.ExaDT ,d.createId ,d.createName ,d.createTime ,d.updateId ,d.updateName ,d.updateTime ,date_format(d.createTime,'%Y-%m-%d') as createDate ,v.carNumber ,r.projectId,d.projectCode,	p.contractType as projectType,p.id as Pid,if(p.contractType='GCXMYD-04',m.projectId,p.id) as esmprojectId from oa_contract_rentcar d ".
			" left join oa_contract_vehicle v on v.contractId=d.id ".
			" left join oa_outsourcing_rentalcar r on r.id=d.rentalcarId ".
        "  LEFT JOIN oa_esm_project p ON p.id=d.projectId".
    "  LEFT JOIN oa_esm_project_mapping m on p.id=m.pkProjectId".
             " where d.isTemp = 0 and d.status=2 and v.isTemp=0 order by d.id desc)innert WHERE innert.orderCode <> '' GROUP BY innert.orderCode,innert.carNumber
			) t on t.carNumber=c.carNum and t.contractStartDate<=c.useCarDate and t.contractEndDate>=c.useCarDate and if(t.projectType='GCXMYD-04',(t.Pid=c.projectId or t.esmprojectId=c.projectId),t.projectCode=c.projectCode)" .
		" where c.state = 1 ",

	"select_excelOut"=>"select c.id ,c.driverName ,c.createName ,c.createTime ,c.useCarDate ,c.projectName ,c.province ,c.city ,c.carNum ,c.startMileage ,c.endMileage ,c.effectMileage ,c.gasolinePrice,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost,c.rentalCarCost ,c.mealsCost ,c.accommodationCost ,c.effectLogTime from oa_outsourcing_register c where c.state = 1 " ,

	//从车辆项目信息导出租车登记
	"selcet_projectOut"=>"select c.id,c.suppName ,c.projectName ,c.projectCode ,c.carNum ,c.useCarDate ,c.rentalProperty ,c.rentalContractCode ,c.rentalCarCost ,c.estimate from oa_outsourcing_register c " .
		"left join oa_outsourcing_allregister d on(c.allregisterId = d.id) " .
		"where d.ExaStatus='完成' ",

	//已经审批完成的租车登记记录
	"select_detail"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.allregisterId ,c.suppId ,c.suppCode ,c.suppName ,c.projectId ,c.projectCode ,c.projectName ,c.projectManagerId ,c.projectManager ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.useCarNum ,c.driverName ,c.rentalContractCode ,c.rentalContractId ,c.rentalProperty ,c.rentalPropertyCode ,c.carNum ,c.drivingReason ,c.effectLogTime ,c.startMileage ,c.endMileage ,c.effectMileage ,c.gasolinePrice ,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost, c.rentalCarCost ,c.mealsCost ,c.accommodationCost ,c.remark ,c.estimate ,c.estimateTime ,c.state ,c.deductInformation ,c.changeReason ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.overtimePay ,c.specialGas from oa_outsourcing_register c " .
		"left join oa_outsourcing_allregister d on(c.allregisterId = d.id) " .
		"where d.ExaStatus='完成' ",

	//汇总计算
	"select_sum"=>"select MIN(c.startMileage) as startMileage ,MAX(c.endMileage) as endMileage ,SUM(c.effectMileage) as effectMileage ,AVG(c.rentalCarCost) as rentalCarCost ,AVG(if(c.gasolinePrice>0 ,c.gasolinePrice ,'')) as gasolinePrice ,SUM(c.reimbursedFuel) as reimbursedFuel ,SUM(c.gasolineKMCost) as gasolineKMCost ,SUM(c.parkingCost) as parkingCost ,SUM(c.tollCost) as tollCost ,SUM(c.mealsCost) as mealsCost ,SUM(c.accommodationCost ) as accommodationCost ,SUM(c.effectLogTime ) as effectLogTime ,SUM(c.overtimePay ) as overtimePay ,SUM(c.specialGas ) as specialGas from oa_outsourcing_register c where 1=1 "
);


$condition_arr = array (
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
		"name" => "allregisterId",
		"sql" => " and c.allregisterId=# "
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
		"name" => "suppId",
		"sql" => " and c.suppId=# "
	),
	array(
		"name" => "suppCode",
		"sql" => " and c.suppCode=# "
	),
	array(
		"name" => "suppName",
		"sql" => " and c.suppName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "suppNameE",
		"sql" => " and c.suppName LIKE CONCAT('%',#,'%') "
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
		"name" => "projectCodeE",
		"sql" => " and c.projectCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array(
		"name" => "projectNameSea",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectType",
		"sql" => " and c.projectType=# "
	),
	array(
		"name" => "projectTypeCode",
		"sql" => " and c.projectTypeCode=# "
	),
	array(
		"name" => "projectManager",
		"sql" => " and c.projectManager=# "
	),
	array(
		"name" => "officeName",
		"sql" => " and c.officeName=# "
	),
	array(
		"name" => "officeId",
		"sql" => " and c.officeId=# "
	),
	array(
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array(
		"name" => "provinceSea",
		"sql" => " and c.province LIKE CONCAT('%',#,'%') "
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
		"name" => "citySea",
		"sql" => " and c.city LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "cityId",
		"sql" => " and c.cityId=# "
	),
	array(
		"name" => "useCarDate",
		"sql" => " and c.useCarDate=# "
	),
	array(
		"name" => "useCarDateA",
		"sql" => " and c.useCarDate >= BINARY # "
	),
	array(
		"name" => "useCarDateB",
		"sql" => " and c.useCarDate <= BINARY # "
	),
	array(
		"name" => "useCarDateSta",
		"sql" => " and c.useCarDate >= BINARY # "
	),
	array(
		"name" => "useCarDateEnd",
		"sql" => " and c.useCarDate <= BINARY # "
	),
	array(
		"name" => "useCarDateSea",
		"sql" => " and c.useCarDate LIKE BINARY CONCAT('%',#,'%') "
	),
    array(
		"name" => "useCarDateLimit",
		"sql" => " and DATE_FORMAT(c.useCarDate,\"%Y-%m\") = # "
	),
    array (
        "name" => "useCarDateOutRangeSql",
        "sql" => "$"
    ),
	array(
		"name" => "useCarNum",
		"sql" => " and c.useCarNum=# "
	),
	array(
		"name" => "carNumber",
		"sql" => " and c.carNum LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "driverName",
		"sql" => " and c.driverName=# "
	),
	array(
		"name" => "driverNameSea",
		"sql" => " and c.driverName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentalContractCode",
		"sql" => " and c.rentalContractCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentalContractCodeE",
		"sql" => " and c.rentalContractCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentalContractId",
		"sql" => " and c.rentalContractId=# "
	),
	array(
		"name" => "rentalProperty",
		"sql" => " and c.rentalProperty=# "
	),
	array(
		"name" => "rentalPropertyCode",
		"sql" => " and c.rentalPropertyCode=# "
	),
	array(
		"name" => "carNum",
		"sql" => " and c.carNum=# "
	),
	array(
		"name" => "drivingReason",
		"sql" => " and c.drivingReason=# "
	),
	array(
		"name" => "effectLogTime",
		"sql" => " and c.effectLogTime=# "
	),
	array(
		"name" => "startMileage",
		"sql" => " and c.startMileage=# "
	),
	array(
		"name" => "endMileage",
		"sql" => " and c.endMileage=# "
	),
	array(
		"name" => "effectMileage",
		"sql" => " and c.effectMileage=# "
	),
	array(
		"name" => "gasolineCost",
		"sql" => " and c.gasolineCost=# "
	),
	array(
		"name" => "gasolinePrice",
		"sql" => " and c.gasolinePrice=# "
	),
	array(
		"name" => "parkingCost",
		"sql" => " and c.parkingCost=# "
	),
    array(
		"name" => "tollCost",
		"sql" => " and c.tollCost=# "
	),
	array(
		"name" => "rentalCarCost",
		"sql" => " and c.rentalCarCost=# "
	),
	array(
		"name" => "mealsCost",
		"sql" => " and c.mealsCost=# "
	),
	array(
		"name" => "accommodationCost",
		"sql" => " and c.accommodationCost=# "
	),
	array(
		"name" => "estimate",
		"sql" => " and c.estimate=# "
	),
	array(
		"name" => "estimateTime",
		"sql" => " and c.estimateTime=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "changeReason",
		"sql" => " and c.changeReason=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createNameSea",
		"sql" => " and c.createName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "createTimeSea",
		"sql" => " and c.createTime LIKE BINARY CONCAT('%',#,'%') "
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
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array(
		"name" => "stateD",
		"sql" => " and d.state=# "
	)
)
?>