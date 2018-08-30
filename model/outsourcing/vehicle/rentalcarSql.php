<?php
/**
 * @author Michael
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,
			c.formCode ,c.applicantPhone ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,
			c.projectManager ,c.province ,c.provinceId ,c.city ,c.cityId ,c.usePlace ,c.testRange ,c.testRangeCode ,
			c.testTime ,c.testTimeCode ,c.mileage ,c.testPeriod ,c.testPeriodCode ,c.testCycle ,c.expectStartDate ,
			c.useCycle ,c.expectUseDay ,c.useCarAmount ,c.rentalProperty ,c.rentalPropertyCode ,c.isEquipDriver ,
			c.isTestEngineer ,c.payGasoline ,c.payGasolineCode ,c.paymentGasoline ,c.paymentGasolineCode ,
			c.payParking ,c.payParkingCode ,c.paymentParking ,c.paymentParkingCode ,c.isPayDriver ,c.isPayDriverCode ,
			c.applyExplain ,c.isApplyOilCard ,c.state ,c.backReason ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,
			c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.allFee ,c.monthlyFee,c.estimateAmonut
		from oa_outsourcing_rentalcar c where 1=1 " ,
	"select_excelOut"=>"select c.id ,c.formCode ,c.projectCode ,c.projectName ,c.projectType ,c.rentalProperty ,c.createName ,c.createTime ,c.applicantPhone ,c.province ,c.city ,c.useCarAmount ,c.expectStartDate ,c.useCycle from oa_outsourcing_rentalcar c where 1=1 ",
	//受理列表sql，关联最近审批人
	"select_deallist"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.formCode ,c.applicantPhone ,c.projectId ,c.projectCode ,c.projectName ,c.projectType ,c.projectTypeCode ,c.projectManager ,c.province ,c.provinceId ,c.city ,c.cityId ,c.usePlace ,c.testRange ,c.testRangeCode ,c.testTime ,c.testTimeCode ,c.mileage ,c.testPeriod ,c.testPeriodCode ,c.testCycle ,c.expectStartDate ,c.useCycle ,c.expectUseDay ,c.useCarAmount ,c.rentalProperty ,c.rentalPropertyCode ,c.isEquipDriver ,c.isTestEngineer ,c.payGasoline ,c.payGasolineCode ,c.paymentGasoline ,c.paymentGasolineCode ,c.payParking ,c.payParkingCode ,c.paymentParking ,c.paymentParkingCode ,c.isPayDriver ,c.isPayDriverCode ,c.applyExplain ,c.state ,c.backReason ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,e.user as ExaUserId ,u.USER_NAME as ExaUser,p.businessBelongName as projectBelongName
		from oa_outsourcing_rentalcar c
		left join (
			select replace(f. user ,',','') as user ,w.Pid
			from flow_step_partent f
			left join wf_task w on w.task=f.Wf_task_ID
			where w.code='oa_outsourcing_rentalcar'
			group by w.Pid,f.user desc
		) e on c.id=e.Pid
		left join user u on u.USER_ID=e.user
		left join oa_esm_project p on p.id=c.projectId
		where 1=1 "
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
		"name" => "formCode",
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applicantPhone",
		"sql" => " and c.applicantPhone LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectName",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectType",
		"sql" => " and c.projectType LIKE CONCAT('%',#,'%') "
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
		"name" => "usePlace",
		"sql" => " and c.usePlace LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "testRange",
		"sql" => " and c.testRange=# "
	),
	array(
		"name" => "testRangeCode",
		"sql" => " and c.testRangeCode=# "
	),
	array(
		"name" => "testTime",
		"sql" => " and c.testTime=# "
	),
	array(
		"name" => "testTimeCode",
		"sql" => " and c.testTimeCode=# "
	),
	array(
		"name" => "mileage",
		"sql" => " and c.mileage=# "
	),
	array(
		"name" => "testPeriod",
		"sql" => " and c.testPeriod=# "
	),
	array(
		"name" => "testPeriodCode",
		"sql" => " and c.testPeriodCode=# "
	),
	array(
		"name" => "testCycle",
		"sql" => " and c.testCycle=# "
	),
	array(
		"name" => "expectStartDate",
		"sql" => " and c.expectStartDate=# "
	),
	array(
		"name" => "expectStartDateSea",
		"sql" => " and c.expectStartDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "useCycle",
		"sql" => " and c.useCycle=# "
	),
	array(
		"name" => "useCycleSea",
		"sql" => " and c.useCycle LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "expectUseDay",
		"sql" => " and c.expectUseDay=# "
	),
	array(
		"name" => "useCarAmount",
		"sql" => " and c.useCarAmount=# "
	),
	array(
		"name" => "useCarAmountSea",
		"sql" => " and c.useCarAmount LIKE CONCAT('%',#,'%') "
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
		"name" => "isEquipDriver",
		"sql" => " and c.isEquipDriver=# "
	),
	array(
		"name" => "isTestEngineer",
		"sql" => " and c.isTestEngineer=# "
	),
	array(
		"name" => "payGasoline",
		"sql" => " and c.payGasoline=# "
	),
	array(
		"name" => "payGasolineCode",
		"sql" => " and c.payGasolineCode=# "
	),
	array(
		"name" => "paymentGasoline",
		"sql" => " and c.paymentGasoline=# "
	),
	array(
		"name" => "paymentGasolineCode",
		"sql" => " and c.paymentGasolineCode=# "
	),
	array(
		"name" => "payParking",
		"sql" => " and c.payParking=# "
	),
	array(
		"name" => "payParkingCode",
		"sql" => " and c.payParkingCode=# "
	),
	array(
		"name" => "paymentParking",
		"sql" => " and c.paymentParking=# "
	),
	array(
		"name" => "paymentParkingCode",
		"sql" => " and c.paymentParkingCode=# "
	),
	array(
		"name" => "isPayDriver",
		"sql" => " and c.isPayDriver=# "
	),
	array(
		"name" => "isPayDriverCode",
		"sql" => " and c.isPayDriverCode=# "
	),
	array(
		"name" => "applyExplain",
		"sql" => " and c.applyExplain=# "
	),
	array(
		"name" => "isApplyOilCard",
		"sql" => " and c.isApplyOilCard=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state IN (#) "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus IN (#) "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
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
		"name" => "createTimeSta",
		"sql" => " and c.createTime > BINARY # "
	),
	array(
		"name" => "createTimeEnd",
		"sql" => " and c.createTime < BINARY # "
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
		"name" => "useCarPlace",
		"sql" => " and (c.province LIKE CONCAT('%',#,'%') or c.city LIKE CONCAT('%',#,'%') or c.usePlace LIKE CONCAT('%',#,'%')) "
	)
)
?>