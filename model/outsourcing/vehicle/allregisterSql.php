<?php
/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:43:01
 * @version 1.0
 * @description:租车登记汇总 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManager ,c.projectManagerId ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.contractUseDay ,c.actualUseDay ,c.effectMileage ,c.rentalCarCost ,c.gasolinePrice ,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.mealsCost ,c.accommodationCost ,c.effectLogTime ,c.state ,c.ExaStatus ,c.ExaDT ,c.overtimePay ,c.specialGas from oa_outsourcing_allregister c where 1=1 ",

	//导出所有
	"select_excelOut"=>"select c.id ,substring(c.useCarDate , 1 ,7) ,c.projectCode ,c.projectName ,c.projectManager ,c.actualUseDay ,c.effectMileage ,c.rentalCarCost ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.mealsCost ,c.accommodationCost ,c.overtimePay ,c.specialGas ,(if(c.rentalCarCost is null,0,c.rentalCarCost)+c.reimbursedFuel+if(c.gasolineKMCost is null,0,c.gasolineKMCost)+c.parkingCost+c.tollCost+c.mealsCost+c.accommodationCost+c.overtimePay+c.specialGas) as allCost,c.effectLogTime from oa_outsourcing_allregister c where 1=1 ",

	//导出已经提交审批
	"select_excelOutFinish"=>"select c.id ,substring(c.useCarDate , 1 ,7) ,c.projectCode ,c.projectName ,c.projectManager ,c.actualUseDay ,c.effectMileage ,c.rentalCarCost ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.mealsCost ,c.accommodationCost ,c.overtimePay ,c.specialGas ,(if(c.rentalCarCost is null,0,c.rentalCarCost)+c.reimbursedFuel+if(c.gasolineKMCost is null,0,c.gasolineKMCost)+c.parkingCost+c.tollCost+c.mealsCost+c.accommodationCost+c.overtimePay+c.specialGas) as allCost,c.effectLogTime from oa_outsourcing_allregister c where c.state<>0 ",

	//用车信息列表(每个项目每个月每辆车)
	"select_message"=>"select c.id as allRegisterId,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManager ,c.projectManagerId ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.contractUseDay ,c.actualUseDay ,c.gasolinePrice ,c.state ,c.ExaStatus ,c.ExaDT ,r.suppName ,r.rentalContractCode ,r.rentalContractId ,r.carNum ,r.contractType,MIN(r.startMileage) as startMileage ,MAX(r.endMileage) as endMileage ,SUM(r.effectMileage) as effectMileage ,r.gasolineKMPrice ,SUM(r.reimbursedFuel) as reimbursedFuel ,SUM(r.gasolineKMCost) as gasolineKMCost ,r.rentalCarCost ,SUM(r.mealsCost) as mealsCost ,SUM(r.parkingCost) as parkingCost ,SUM(r.tollCost) as tollCost ,SUM(r.accommodationCost) as accommodationCost ,SUM(r.effectLogTime) as effectLogTime ,SUM(r.overtimePay) as overtimePay ,SUM(r.specialGas) as specialGas ,r.estimate ,r.remark ,r.id as registerId ,r.id as id ,r.rentalPropertyCode ,COUNT(r.id) as registerNum from oa_outsourcing_allregister c ".
		" left join oa_outsourcing_register r on r.allregisterId = c.id ".
		" where 1=1 ",
	//带服务经理的数据
	"select_service"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManager ,c.projectManagerId ,c.officeName ,c.officeId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.useCarDate ,c.contractUseDay ,c.actualUseDay ,c.effectMileage ,c.rentalCarCost ,c.gasolinePrice ,c.gasolineKMPrice ,c.reimbursedFuel ,c.gasolineKMCost ,c.parkingCost ,c.tollCost ,c.mealsCost ,c.accommodationCost ,c.effectLogTime ,c.state ,c.ExaStatus ,c.ExaDT ,c.overtimePay ,c.specialGas ,p.managerId from oa_outsourcing_allregister c ".
		" left join (
			SELECT
				m.provinceId,
				GROUP_CONCAT(m.managerId) AS managerId,
				GROUP_CONCAT(m.managerName) AS managerName
			FROM
				oa_esm_office_managerinfo m
			GROUP BY
				m.provinceId
		) p on p.provinceId=c.provinceId ".
		" where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
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
		"name" => "projectCodeSea",
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
		"name" => "projectManagerSea",
		"sql" => " and c.projectManager LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectManagerIdSea",
		"sql" => " and c.projectManagerId LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "officeName",
		"sql" => " and c.officeName=# "
	),
	array(
		"name" => "officeNameSea",
		"sql" => " and c.officeName LIKE CONCAT('%',#,'%') "
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
		"name" => "useCarDateSea",
		"sql" => " and c.useCarDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "useCarDateSta",
		"sql" => " and date_format(c.useCarDate,'%Y-%m') >= BINARY # "
	),
	array(
		"name" => "useCarDateEnd",
		"sql" => " and date_format(c.useCarDate,'%Y-%m') <= BINARY # "
	),
	array(
		"name" => "useCarNum",
		"sql" => " and c.useCarNum=# "
	),
	array(
		"name" => "useCarNumSea",
		"sql" => " and c.useCarNum LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "contractUseDay",
		"sql" => " and c.contractUseDay=# "
	),
	array(
		"name" => "contractUseDaySea",
		"sql" => " and c.contractUseDay LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "actualUseDay",
		"sql" => " and c.actualUseDay=# "
	),
	array(
		"name" => "actualUseDaySea",
		"sql" => " and c.actualUseDay LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "effectMileage",
		"sql" => " and c.effectMileage=# "
	),
	array(
		"name" => "rentalCarCost",
		"sql" => " and c.rentalCarCost=# "
	),
	array(
		"name" => "gasolineCost",
		"sql" => " and c.gasolineCost=# "
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
		"name" => "mealsCost",
		"sql" => " and c.mealsCost=# "
	),
	array(
		"name" => "accommodationCost",
		"sql" => " and c.accommodationCost=# "
	),
	array(
		"name" => "effectLogTime",
		"sql" => " and c.effectLogTime=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
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
	//用车信息表查找
	array(
		"name" => "suppNameSea",
		"sql" => " and r.suppName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentalContractCodeSea",
		"sql" => " and r.rentalContractCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "rentalPropertyCodeSea",
		"sql" => " and r.rentalPropertyCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "estimate",
		"sql" => " and r.estimate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and r.remark LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "carNumSea",
		"sql" => " and r.carNum LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "serviceManagerIdSea",
		"sql" => " and p.managerId LIKE CONCAT('%',#,'%') "
	)
)
?>