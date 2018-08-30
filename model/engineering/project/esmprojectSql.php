<?php
/**
 * @author Show
 * @Date 2011年11月24日 星期四 17:20:15
 * @version 1.0
 * @description:工程项目(oa_esm_project) sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select * from oa_esm_project c where 1",
    //列表查询sql
	"select_esm_fee"=>"select c.id,c.projectCode,c.projectName,c.contractId,c.contractCode,c.contractType,
                c.contractTypeName,c.rObjCode, c.charterId,c.workRate,c.customerId,c.customerName,c.contractCountry,
                c.contractProvince,c.contractCity, c.officeId,c.officeName,c.deptId,c.deptName,c.managerId,c.managerName,
                c.productLine,c.productLineName,c.newProLine,c.newProLineName,c.budgetAll,c.budgetField,c.budgetOutsourcing,
                c.budgetOther,c.budgetDay,c.budgetPeople,c.budgetPerson,c.budgetEqu,c.feeCar,c.feeFlightsShare,
                c.feeEquImport,c.feePK,
                c.nature,c.natureName,c.categoryName,c.category,c.cycle,c.cycleName,c.outsourcing,c.outsourcingName,
                c.description,c.remark,c.status,c.statusName,c.workDescription,c.closeDate,c.closeDesc,
                c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,
                c.contractMoney,c.attribute,c.attributeName,c.createTypeName,c.signTypeName,c.toolType,
                c.customerTypeName,c.nature2Name,c.platformName,c.netName,c.businessBelong, c.businessBelongName,
                c.formBelong, c.formBelongName, c.areaManager,c.areaManagerId,c.techManager,c.techManagerId,
                c.customerLinkMan,c.salesmanId,c.salesman,c.rdUser,c.rdUserId,
                c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.expectedDuration,c.actDuration,
                c.workedDays,c.needDays,
                c.peopleNumber,c.dlPeople,c.outsourcingPeople,c.outsourcingTypeName,c.outsourcingJob,
                c.country,c.countryId,c.province,c.provinceId,c.city,c.cityId,c.estimates,
                c.feeAll,c.feeField,c.feeOutsourcing,c.feeOther,c.feeDay,c.feePeople,c.feePerson,c.feeFieldImport,
                if(e.feeEqu is null,c.feeEqu,c.feeEqu + e.feeEqu) as feeEqu,c.feePayables,c.feeFlights,c.feeSubsidy,c.feeSubsidyImport,
                c.feeAllProcess,c.feeFieldProcess,c.projectProcess,c.earnedValue,c.perEarnedValue,c.maxLogDay,c.exgross,
                'esm' as pType,c.incomeType,c.incomeTypeName
            from
                oa_esm_project c
                left join
                (SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e on e.projectId = c.id
            where 1",
	"select_defaultAndFee"=>"select * @FROM
        (
            select c.id,c.id as proId,c.projectCode,c.projectName,c.contractId,c.contractCode,c.contractType,
                c.contractTypeName,c.rObjCode, c.charterId,c.workRate,c.customerId,c.customerName,c.contractCountry,
                c.contractProvince,c.contractCity, c.officeId,c.officeName,c.deptId,c.deptName,c.managerId,c.managerName,
                c.productLine,c.productLineName,c.newProLine,c.newProLineName,c.budgetAll,c.budgetField,c.budgetOutsourcing,
                c.budgetOther,c.budgetDay,c.budgetPeople,c.budgetPerson,c.budgetEqu,c.feeCar,c.feeFlightsShare,
                c.feeEquImport,c.feePK,
                c.nature,c.natureName,c.categoryName,c.category,c.cycle,c.cycleName,c.outsourcing,c.outsourcingName,
                c.description,c.remark,c.status,c.statusName,c.workDescription,c.closeDate,c.closeDesc,
                c.ExaStatus,c.ExaDT,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,
                c.contractMoney,c.attribute,c.attributeName,c.createTypeName,c.signTypeName,c.toolType,
                c.customerTypeName,c.nature2Name,c.platformName,c.netName,c.businessBelong, c.businessBelongName,
                c.formBelong, c.formBelongName, c.areaManager,c.areaManagerId,c.techManager,c.techManagerId,
                c.customerLinkMan,c.salesmanId,c.salesman,c.rdUser,c.rdUserId,
                c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.expectedDuration,c.actDuration,
                c.workedDays,c.needDays,
                c.peopleNumber,c.dlPeople,c.outsourcingPeople,c.outsourcingTypeName,c.outsourcingJob,
                c.country,c.countryId,c.province,c.provinceId,c.city,c.cityId,c.estimates,
                c.feeAll,c.feeField,c.feeOutsourcing,c.feeOther,c.feeDay,c.feePeople,c.feePerson,c.feeFieldImport,
                if(e.feeEqu is null,c.feeEqu,c.feeEqu + e.feeEqu) as feeEqu,c.feePayables,c.feeFlights,c.feeSubsidy,c.feeSubsidyImport,
                c.feeAllProcess,c.feeFieldProcess,c.projectProcess,c.earnedValue,c.perEarnedValue,c.maxLogDay,c.exgross,
                'esm' as pType,c.incomeType,c.incomeTypeName,'' as proLineMoney,'' as deliverySchedule,null as revenue,null as shipCost
            from
                oa_esm_project c
                left join
                (SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e on e.projectId = c.id
            where 1
            union ALL
            select
               CONCAT('c',p.id) as id,p.id as proId,p.projectCode,p.projectName,p.contractId,p.contractCode,'GCXMYD-01' as contractType,
               '鼎利合同' as contractTypeName, null as rObjCode,null as charterId,null as workRate,cp.customerId,cp.customerName,cp.contractCountry,
               cp.contractProvince,cp.contractCity,cp.areaCode as officeId,cp.areaName as officeName,null as deptId,
               null as deptName,null as managerId,null as managerName,
               p.proLineCode as productLine,p.proLineName as productLineName,
               p.proLineCode as newProLine,p.proLineName as newProLineName,p.budget as budgetAll,0 as budgetField,0 as budgetOutsourcing,
               0 as budgetOther,0 as budgetDay,0 as budgetPeople,0 as budgetPerson,0 as budgetEqu,0 as feeCar,0 as feeFlightsShare,0 as feeEquImport,0 as feePK,
               null as nature,null as natureName,null as categoryName,null as cateGory,null as cycle,null as cycleName,null as outsourcing,null as outsourcingName,
               null as description,null as remark,if(p. SCHEDULE = '100' and cp.invoiceMoney=(cp.contractMoney-cp.deductMoney-cp.badMoney-cp.uninvoiceMoney) and p.DeliverySchedule >= 100,'GCXMZT03',if((p.DeliverySchedule < 100 or p.DeliverySchedule is null),'GCXMZT02','GCXMZT04')) AS STATUS,
							 if(p. SCHEDULE = '100' and cp.invoiceMoney=(cp.contractMoney-cp.deductMoney-cp.badMoney-cp.uninvoiceMoney) and p.DeliverySchedule >= 100,'关闭',if((p.DeliverySchedule < 100 or p.DeliverySchedule is null),'在建','完工')) AS statusName,
               null as workDescription,null as closeDate,null as closeDesc,'完成' as ExaStatus,cp.ExaDT,p.createId,p.createName,p.createTime,p.updateId,p.updateName,p.updateTime,
               cp.contractMoney,'GCXMSS-03' as attribute,'产品项目' as attributeName,null as createTypeName,
               null as signTypeName,null as toolType,cp.customerTypeName,null as nature2Name,null as platformName,
               null as netName,cp.businessBelong,cp.businessBelongName,cp.formBelong,cp.formBelongName,
               cp.areaPrincipal as areaManager,cp.areaPrincipalId as areaManagerId,null as techManager,null as techManagerId,
               null as customerLinkMan,null as salesmanaId,null as salesman,null as rdUser,null as rdUserId,p.planBeginDate,p.planEndDate,p.actBeginDate,p.actEndDate,null as expectedDuration,
               null as actDuration,null as workedDays,null as needDays,null as peopleNumber,null as dlPeople,null as outsourcingPeople,null as outsourcingTypeName,
               null as outsourcingJob,cp.contractCountry as country,cp.contractCountryId as countryId,cp.contractProvince as province,cp.contractProvinceId as provinceId,
               cp.contractCity as city,cp.contractCityId as cityId,p.estimates,
               p.feeAll as feeAll,null as feeField,null as feeOutsourcing,null as feeOther,null as feeDay,null as feePeople,null as feePerson,null as feeFieldImport,
               null as feeEqu,null feePayables,null as feeFlights,null as feeSubsidy,null as feeSubsidyImport,
               null as feeAllProcess,null as feeFieldProcess,p.proschedule as projectProcess,null as earnedValue,null as perEarnedValue,
               null as maxLogDay,p.exgross,'pro' as pType,'' AS incomeType,p.earningsTypeName AS incomeTypeName,p.proLineMoney,p.deliverySchedule,p.revenue,p.shipCost
            from oa_contract_project p
            LEFT JOIN oa_contract_contract cp ON p.contractId = cp.id
            where p.esmProjectId is null
        )c
        where 1",
    "select_memberlist" => "select c.id,c.projectCode,c.projectName,c.contractId,c.contractCode,c.contractType,c.contractTypeName,c.rObjCode,
			c.charterId,c.workRate,c.customerId,c.customerName,
			c.officeId,c.officeName,c.deptId,c.deptName,c.managerId,c.managerName,c.productLine,c.productLineName,c.newProLine,c.newProLineName,
			c.budgetAll,c.budgetField,c.budgetOutsourcing,c.budgetOther,c.budgetDay,c.budgetPeople,c.budgetPerson,c.budgetEqu,
			c.nature,c.natureName,c.categoryName,c.cycleName,c.outsourcingName,
			c.status,c.statusName,c.workDescription,c.closeDate,c.closeDesc,
			c.ExaStatus,c.ExaDT,c.updateId,c.updateName,c.updateTime,
			c.contractMoney,c.attribute,c.attributeName,c.createTypeName,c.signTypeName,c.toolType,
			c.customerTypeName,c.nature2Name,c.platformName,c.netName,
			c.areaManager,c.areaManagerId,c.techManager,c.techManagerId,c.customerLinkMan,c.salesmanId,c.salesman,c.rdUser,c.rdUserId,
			c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.expectedDuration,c.actDuration,c.workedDays,c.needDays,
			c.peopleNumber,c.dlPeople,c.outsourcingPeople,c.outsourcingTypeName,c.outsourcingJob,
			c.country,c.countryId,c.province,c.provinceId,c.city,c.cityId,c.maxLogDay,
			c.feeAll,c.feeField,c.feeOutsourcing,c.feeOther,c.feeDay,c.feePeople,c.feePerson,
			c.feeAllProcess,c.feeFieldProcess,c.projectProcess,c.earnedValue,c.perEarnedValue
		from
			oa_esm_project c
			LEFT JOIN
			oa_esm_project_member m on c.id = projectId
		where 1 and m.status <> 1",
   	"sumWorkRate" => "select sum(c.workRate) as workRate from oa_esm_project c where 1 ",
   	"sumProcess" => "select round(sum(c.workRate*c.projectProcess/100),2) as allProcess from oa_esm_project c where 1 ",
    "select_defaultAndFeeForUpload"=>"
        select * @FROM
        (	
				select c.id,c.id as proId,c.projectCode,c.contractId,c.contractCode,c.contractType,c.projectMoneyWithTax as projectMoney,
                'esm' as pType,c.feeAll,c.feePK,c.budgetAll,c.curIncome,c.productLine,c.newProLine,c.workRate,c.incomeTypeName,c.projectProcess
            from
                oa_esm_project c
                left join
                (SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e on e.projectId = c.id
            where 1
            union ALL
            select
               p.id,p.id as proId,p.projectCode,p.contractId,p.contractCode,'GCXMYD-01' as contractType,p.proMoney as projectMoney,
               'pro' as pType,p.cost as feeAll,0 as feePK,p.budget as budgetAll,p.revenue as curIncome,
               p.proLineCode as productLine,p.proLineCode as newProLine,null as workRate,p.earningsTypeName AS incomeTypeName,p.proschedule as projectProcess
            from oa_contract_project p
            LEFT JOIN oa_contract_contract cp ON p.contractId = cp.id
            where p.esmProjectId is null
        )c
        where 1 
    "
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
        "name" => "ids",
        "sql" => " and c.id in(arr) "
    ),
    array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
    ),
    array(
        "name" => "projectCodeArr",
        "sql" => " and c.projectCode in(arr) "
    ),
    array(
        "name" => "projectCodes",
        "sql" => " and c.projectCode in(arr) "
    ),
    array(
   		"name" => "projectCodeSearch",
   		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "projectSearch",
   		"sql" => " and (c.projectCode like CONCAT('%',#,'%') or c.projectName like CONCAT('%',#,'%'))"
    ),
    array(
   		"name" => "projectCodeEq",
   		"sql" => " and c.projectCode=# "
    ),
    array(
   		"name" => "attribute",
   		"sql" => " and c.attribute=# "
    ),
    array(
        "name" => "attributes",
        "sql" => " and c.attribute in(arr)"
    ),
    array(
   		"name" => "projectName",
   		"sql" => " and c.projectName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
    ),
    array(
        "name" => "contractIdArr",
        "sql" => " and c.contractId in(arr)"
    ),
    array(
        "name" => "newProLines",
        "sql" => " and c.newProLine in(arr)"
    ),
    array(
   		"name" => "contractType",
   		"sql" => " and c.contractType=# "
    ),
    array(
        "name" => "contractTypes",
        "sql" => " and c.contractType in(arr)"
    ),
    array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
    ),
    array(
   		"name" => "contractCodeSearch",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "contractCodeFullSearch",
        "sql" => " and c.contractCode=#"
    ),
    array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode=# "
    ),
    array(
   		"name" => "rObjCodeSearch",
   		"sql" => " and c.rObjCode like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
    ),
    array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
    ),
    array(
   		"name" => "officeId",
   		"sql" => " and c.officeId=# "
    ),
    array(
   		"name" => "officeIds",
   		"sql" => " and c.officeId in(arr) "
    ),
    array(
   		"name" => "officeName",
   		"sql" => " and c.officeName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
    ),
    array(
   		"name" => "deptName",
   		"sql" => " and c.deptName like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "findManager",
        "sql"=>" and  ( find_in_set( # , c.managerId ) > 0 ) "
    ),
    array(
   		"name" => "managerId",
   		"sql" => " and c.managerId=# "
    ),
    array(
   		"name" => "managerName",
   		"sql" => " and c.managerName  like CONCAT('%',#,'%') "
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
        "name" => "planEndYear",
        "sql" => " and YEAR(c.planEndDate)=# "
    ),
    array(
   		"name" => "actBeginDate",
   		"sql" => " and c.actBeginDate=# "
    ),
    array(
   		"name" => "actEndDate",
   		"sql" => " and c.actEndDate=# "
    ),
    array(
   		"name" => "nature",
   		"sql" => " and c.nature=# "
    ),
    array(
   		"name" => "category",
   		"sql" => " and c.category=# "
    ),
    array(
   		"name" => "cycle",
   		"sql" => " and c.cycle=# "
    ),
    array(
   		"name" => "peopleNumber",
   		"sql" => " and c.peopleNumber=# "
    ),
    array(
   		"name" => "outsourcing",
   		"sql" => " and c.outsourcing=# "
    ),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
    array(
        "name" => "selectstatus",
        "sql" => " and c.status=# "
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
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
    array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
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
   		"name" => "workRate",
   		"sql" => " and c.workRate=# "
    ),
    array(
   		"name" => "memberId",
   		"sql" => " and if(m.isManager = 1,m.memberId=#,0) "
    ),
    array(
   		"name" => "logMember",
   		"sql" => " and ( m.memberId=# or c.attribute = 'GCXMSS-04')"
    ),
    array(
   		"name" => "mIsCanRead",
   		"sql" => " and m.isCanRead=# "
    ),
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
	array (
		"name" => "statusArr",
		"sql" => "and c.status in(arr)"
	),
	array (
		"name" => "trialStr",
		"sql" => " and c.contractId in(arr) "
	),
	array(
		"name" => "beginDateSearch",
		"sql" => " and if(c.actBeginDate < c.planBeginDate,c.actBeginDate,c.planBeginDate) >=# "
	),
	array(
		"name" => "endDateSearch",
		"sql" => " and if(c.actEndDate > c.planEndDate,c.actEndDate,c.planEndDate) <=# "
	),
	array(
		"name" => "province",
		"sql" => " and c.province=# "
	),
	array(
		"name" => "contractTypeK",
		"sql" => " and k.contractType=# "
	),
	array(
		"name" => "contractTypeMix",
		"sql" => " and (c.contractType=# or (select contractType from oa_contract_contract where id = c.contractId)=# ) "
	),
	array(
		"name" => "productLine",
		"sql" => " and c.productLine=# "
	),
	array(
		"name" => "productLineName",
		"sql" => " and c.productLineName=# "
	),
    array(
        "name" => "newProLine",
        "sql" => " and c.newProLine=# "
    ),
    array(
        "name" => "newProLineName",
        "sql" => " and c.newProLineName=# "
    ),
    array(
        "name" => "feeOutOfLimit",
        "sql" => " and c.feeAll > c.budgetAll"
    ),
    array(
        "name" => "negativeExgross",
        "sql" => " and c.exgross < 0"
    ),
    array(
        "name" => "lowExgross",
        "sql" => " and (c.exgross >= 0 AND c.exgross < 15)"
    ),
    array(
        "name" => "pType",
        "sql" => " and c.pType = # "
    )
);