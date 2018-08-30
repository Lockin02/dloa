<?php
/**
 * @author Administrator
 * @Date 2012-08-07 09:38:05
 * @version 1.0
 * @description:离职管理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.leaveCode ,c.ExaStatus,c.id ,c.userNo ,c.wageLevelCode,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.entryDate ,c.becomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.requireDate ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.contractBegin,c.contractEnd ,o.id as handoverId,c.remark ,c.userSelfCstatus,c.handoverCstatus ,c.salaryEndDate ,c.state ,c.comfirmQuitDate ,c.salaryPayDate,c.pensionReduction ,c.fundReduction ,c.employmentEnd ,c.softSate ,c.emailSate,c.workProvinceId ,c.workProvince ,c.mobile ,c.projectManager ,c.projectManagerId ,c.personEmail ,c.postAddress ,c.leaveApplyDate ,c.realReason ,c.isBack ,p.companyName ,p.deptIdS ,p.deptNameS ,p.deptIdT ,p.deptNameT,p.deptIdF ,p.deptNameF ,p.personnelTypeName ,p.personnelType
	from oa_hr_leave c
	LEFT JOIN oa_hr_personnel p ON (p.userNo=c.userNo)
	LEFT JOIN oa_leave_handover o on c.id=o.leaveId
	where 1=1 ",
	"select_leave"=>"select c.leaveCode ,c.ExaStatus ,c.id ,c.userNo ,c.wageLevelCode,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.entryDate ,c.becomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.requireDate ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.contractBegin ,c.contractEnd ,o.id as handoverId ,c.remark ,c.userSelfCstatus ,c.handoverCstatus ,c.salaryEndDate ,c.state ,c.comfirmQuitDate ,c.salaryPayDate ,c.pensionReduction ,c.fundReduction ,c.employmentEnd ,c.softSate ,c.emailSate ,c.workProvinceId ,c.workProvince ,c.mobile ,c.personEmail ,c.postAddress ,c.leaveApplyDate ,c.leaveApplyDate ,c.realReason ,c.isBack ,p.companyName ,p.deptIdS ,p.deptNameS ,p.deptIdT ,p.deptNameT,p.deptIdF ,p.deptNameF
	from oa_hr_leave c
	LEFT JOIN oa_leave_handover o on c.id=o.leaveId
	LEFT JOIN oa_hr_personnel p ON (p.userNo=c.userNo)
	where 1=1 ",
	"select_unconfirm"=>" select d.id ,d.items ,d.recipientName ,c.leaveCode ,c.userNo ,c.userName ,e.companyName ,p.personnelTypeName ,c.deptName ,p.deptNameS ,p.deptNameT,p.deptNameF ,c.jobName ,c.workProvince ,c.entryDate ,c.quitTypeName ,c.comfirmQuitDate ,c.salaryEndDate
	from oa_hr_handover_list d
	LEFT JOIN oa_leave_handover e
	on e.id=d.handoverId
	LEFT JOIN oa_hr_leave c
	on c.id=e.leaveId
	LEFT JOIN oa_hr_personnel p
	on p.userNo=c.userNo
	where d.affstate=0 and c.state <> 4 "
);

$condition_arr = array (
	array(
		"name" => "leaveCode",
		"sql" => " and c.leaveCode LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
		)
	,
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
		)
	,
	array(
		"name" => "jobName",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "becomeDate",
		"sql" => " and c.becomeDate=# "
	),
	array(
		"name" => "becomeFraction",
		"sql" => " and c.becomeFraction=# "
	),
	array(
		"name" => "entryPlace",
		"sql" => " and c.entryPlace=# "
	),
	array(
		"name" => "quitDate",
		"sql" => " and c.quitDate=# "
	),
	array(
		"name" => "quitTypeCode",
		"sql" => " and c.quitTypeCode=# "
	),
	array(
		"name" => "quitTypeName",
		"sql" => " and c.quitTypeName=# "
	),
	array(
		"name" => "quitReson",
		"sql" => " and c.quitReson=# "
	),
	array(
		"name" => "requireDate",
		"sql" => " and c.requireDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
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
		"name" => "state",
		"sql" => " and c.state in(arr) "
	),
	array(
		"name" => "stateS",
		"sql" => " and (
			if(#='1'
				,c.state=1
				,if(#='2_1'
					,c.state='2' and (curdate()<= c.comfirmQuitDate or c.ExaStatus!='完成')
					,if(#='2_2'
						,c.state='2' and c.ExaStatus='完成' and curdate()>c.comfirmQuitDate
						,if(#=3
							,c.state=3
							,c.state=4
						)
					)
				)
			)
		) "
	),
	array(
		"name" => "comfirmQuitDate",
		"sql" => " and c.comfirmQuitDate LIKE BINARY CONCAT('%',#,'%')  "
	),
	array(
		"name" => "comfirmQuitDateN",
		"sql" => " and c.comfirmQuitDate != '0000-00-00' "
	),
	array(
		"name" => "comfirmQuitDateGrid",
		"sql" => " and c.comfirmQuitDate=#"
	),
	array(
		"name" => "salaryEndDate",
		"sql" => " and c.salaryEndDate LIKE BINARY CONCAT('%',#,'%')  "
	),
	array(
		"name" => "salaryPayDate",
		"sql" => " and c.salaryPayDate LIKE BINARY CONCAT('%',#,'%')  "
	),
	array(
		"name" => "pensionReduction",
		"sql" => " and c.pensionReduction LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "fundReduction",
		"sql" => " and c.fundReduction LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "quitReson",
		"sql" => " and c.quitReson  LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "rzrq",
		"sql" => " and c.entryDate>= BINARY # "
	),
	array(
		"name" => "lzrq",
		"sql" => " and c.comfirmQuitDate>= BINARY # "
	),
	array(
		"name" => "xlzrq",
		"sql" => " and c.comfirmQuitDate< BINARY # "
	),
	array(
		"name" => "qdti",
		"sql" => " and (c.comfirmQuitDate>=# or c.ExaStatus != '完成')"
	),
	array(
		"name" => "quitTypeCodeSame",
		"sql" => " and c.quitTypeCode=# "
	),
	array(
		"name" => "handoverCstatus",
		"sql" => " and c.handoverCstatus=# "
	),
	array(
		"name" => "userNameSame",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "deptIdSame",
		"sql" => " and p.deptId =# "
	),
	array(
		"name" => "deptIdSameS",
		"sql" => " and p.deptIdS =# "
	),
	array(
		"name" => "deptIdSameT",
		"sql" => " and p.deptIdT =# "
	),
// -----新增
	array(
		"name" => "djbh",
		"sql" => " and c.leaveCode LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "ygbh",
		"sql" => " and p.userNo LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "djzt",
		"sql" => " and c.state = # "
	),
	array(
		"name" => "ygqrzt",
		"sql" => " and c.userSelfCstatus =# "
	),
	array(
		"name" => "gs",
		"sql" => " and p.companyName =# "
	),
	array(
		"name" => "companyNameI",
		"sql" => " and p.companyName in(arr) "
	),
	array(
		"name" => "zw",
		"sql" => " and c.jobName =# "
	),
	array(
		"name" => "rzrq2",
		"sql" => " and c.entryDate<= BINARY # "
	),
	array(
		"name" => "qwlzrq",
		"sql" => " and c.requireDate>= BINARY # "
	),
	array(
		"name" => "qwlzrq2",
		"sql" => " and c.requireDate<= BINARY # "
	),
	array(
		"name" => "lzrq2",
		"sql" => " and c.comfirmQuitDate<= BINARY # "
	),
	array(
		"name" => "gzjsjzrq",
		"sql" => " and c.salaryEndDate>= BINARY # "
	),
	array(
		"name" => "gzzfrq",
		"sql" => " and c.salaryPayDate>= BINARY # "
	),
	array(
		"name" => "gzzfrq2",
		"sql" => " and c.salaryPayDate<= BINARY # "
	),
	array(
		"name" => "gzjsjzrq2",
		"sql" => " and c.salaryEndDate<= BINARY # "
	),
	array(
		"name" => "sbjy",
		"sql" => " and c.pensionReduction =# "
	),
	array(
		"name" => "gjjjy",
		"sql" => " and c.fundReduction =# "
	),
	array(
		"name" => "ygzz",
		"sql" => " and c.employmentEnd =# "
	),
	array(
		"name" => "bgrjzt",
		"sql" => " and c.softSate =# "
	),
	array(
		"name" => "spzt",
		"sql" => " and c.ExaStatus =# "
	),
	array(
		"name" => "jdbz",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "lzyy",
		"sql" => " and c.quitReson LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "isBack",
		"sql" => " and c.isBack =# "
	),
	array(
		"name" => "realReason",
		"sql" => " and c.realReason LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "handoverIdN",
		"sql" => " and o.id is null  "
	),
	array(
		"name" => "handoverId",
		"sql" => " and o.id is not null  "
	),
	array(
		"name" => "handoverCstatusS",
		"sql" => " and (if(#=1,o.id is null,if(#='WQR',o.id is not null and c.handoverCstatus='WQR',o.id is not null and c.handoverCstatus='YQR')))  "
	),
	array(
		"name" => "idNotIn",
		"sql" => " and c.id not in(arr)  "
	)
)
?>