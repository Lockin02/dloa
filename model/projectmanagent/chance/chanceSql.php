<?php
/**
 * @author Administrator
 * @Date 2012-08-02 15:58:24
 * @version 1.0
 * @description:销售商机 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select t.boostTime,g.goodsNameStr,c.id ,c.contractTurnDate,c.chanceCode ,c.chanceName ,c.chanceLevel ,c.chanceStage ,c.winRate ,c.chanceType ,c.chanceTypeName ,c.chanceNature ,c.chanceNatureName ,c.chanceMoney ,c.predictContractDate ,c.predictExeDate ,c.contractPeriod ,c.newUpdateDate ,c.trackman ,c.trackmanId ,c.customerName ,c.customerId ,c.address ,c.customerProvince ,c.customerType ,c.customerTypeName,c.remark ,c.progress ,c.competitor ,c.won ,c.Country ,c.CountryId ,c.Province ,c.ProvinceId ,c.City ,c.CityId ,c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.prinvipalName ,c.prinvipalId ,c.prinvipalDept ,c.prinvipalDeptId ,c.status ,c.closeId ,c.closeName ,c.closeTime ,c.closeRegard ,c.ExaStatus ,c.ExaDT ,c.customerNeed ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime,c.createName ,c.createId ,c.orderNatureName ,c.orderNature ,c.rObjCode ,c.isTemp ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.originalId ,c.changeTips ,c.isBecome ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus ,c.customTypeId ,c.customTypeName ,c.warnDate ,c.reterStart,c.updateRecord
         		,c.contractCode,c.formBelong,c.formBelongName,c.businessBelong,c.businessBelongName,c.module,c.moduleName
                from oa_sale_chance c left join
				(
				 select GROUP_CONCAT(goodsName) as goodsNameStr,chanceId from oa_sale_chance_goods group by chanceId
				) g on c.id=g.chanceId
				left join
		        (
		          select max(createTime) as boostTime,chanceId from  oa_sale_chance_boost group by chanceId
		        ) t on c.id=t.chanceId

         		where 1=1  and c.isTemp=0",
         "select_list_sumMoney"=>"select sum(c.chanceMoney) as chanceMoney" .
         		" from oa_sale_chance c left join
				(
				 select GROUP_CONCAT(goodsName) as goodsNameStr,chanceId from oa_sale_chance_goods group by chanceId
				) g on c.id=g.chanceId
				left join
		        (
		          select max(createTime) as boostTime,chanceId from  oa_sale_chance_boost group by chanceId
		        ) t on c.id=t.chanceId

         		where 1=1 ",


         "select_chancelist"=>"select c.id ,c.contractTurnDate,c.oldId,c.timingDate,c.chanceCode ,c.chanceName ,c.chanceLevel ,c.chanceStage ,c.winRate ,c.chanceType ,c.chanceTypeName ,c.chanceNature ,c.chanceNatureName ,c.chanceMoney ,c.predictContractDate ,c.predictExeDate ,c.contractPeriod ,c.newUpdateDate ,c.trackman ,c.trackmanId ,c.customerName ,c.customerId ,c.address ,c.customerProvince ,c.customerType ,c.customerTypeName,c.remark ,c.progress ,c.competitor ,c.won ,c.Country ,c.CountryId ,c.Province ,c.ProvinceId ,c.City ,c.CityId ,c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.prinvipalName ,c.prinvipalId ,c.prinvipalDept ,c.prinvipalDeptId ,c.status ,c.closeId ,c.closeName ,c.closeTime ,c.closeRegard ,c.ExaStatus ,c.ExaDT ,c.customerNeed ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime ,c.createName ,c.createId ,c.orderNatureName ,c.orderNature ,c.rObjCode ,c.contractCode ,c.isTemp ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.originalId ,c.changeTips ,c.isBecome ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus ,c.customTypeId ,c.customTypeName ,c.warnDate ,c.reterStart  from oa_sale_chance_timing c where 1=1 ",
		  /**
		  * 根据跟踪人过滤我的销售商机  LiuBo
		  */
		 "select_mychance"=>"select c.id ,c.contractTurnDate,c.chanceCode ,c.chanceName ,c.chanceLevel ,c.chanceStage ,c.winRate ,c.chanceType ,c.chanceTypeName ,c.chanceNature ,c.chanceNatureName ," .
		 		"c.chanceMoney ,c.predictContractDate ,c.predictExeDate ,c.contractPeriod ,c.newUpdateDate ,c.trackman ,c.trackmanId ,c.customerName ,c.customerId ,c.address ," .
		 		"c.customerProvince ,c.customerType ,c.customerTypeName,c.remark ,c.progress ,c.competitor ,c.won ,c.Country ,c.CountryId ,c.Province ,c.ProvinceId ,c.City ,c.CityId ,c.areaName ," .
		 		"c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.prinvipalName ,c.prinvipalId ,c.prinvipalDept ,c.prinvipalDeptId ,c.status ,c.closeId ,c.closeName ," .
		 		"c.closeTime ,c.closeRegard ,c.ExaStatus ,c.ExaDT ,c.customerNeed ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ," .
		 		"c.orderNatureName ,c.orderNature ,c.rObjCode ,c.contractCode ,c.isTemp ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.originalId ,c.changeTips ," .
		 		"c.isBecome ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus ,c.customTypeId ,c.customTypeName ,c.warnDate ,c.reterStart," .
		 		"s.id as trackId,c.trackman,s.trackmanId,s.chanceId from oa_sale_chance_trackman s inner join oa_sale_chance c on s.chanceId=c.id where 1=1 ",

		//服务产品统计
		"select_serviceProduct"=>"SELECT c.id,c.Province,c.City,c.status,c.chanceType,c.chanceName,c.createTime,c.newUpdateDate,c.chanceCode,c.winRate,c.chanceStage,c.predictExeDate,c.contractPeriod,p.goodsName as conProductName,p.goodsId as conProductId,p.number,i.goodsTypeId,sub.topParentId,sub.topParentType
				@FROM
				   oa_sale_chance_goods p
				LEFT JOIN oa_sale_chance c ON c.id = p.chanceId
				LEFT JOIN oa_goods_base_info i ON p.goodsId = i.id
				LEFT JOIN ( SELECT t.id, p.goodsType AS topParentType,p.id AS topParentId FROM oa_goods_type t,
				            (SELECT * FROM oa_goods_type WHERE parentId =- 1 ) p WHERE t.lft >= p.lft AND t.rgt <= p.rgt
				          ) sub ON i.goodsTypeId = sub.id
				WHERE
				   sub.topParentId = ".ESMPRODUCTTYPE." ",
	   //销售产品统计
		"select_chanceProduct"=>"SELECT p.goodsName as conProductName,c.winRate,sum(p.number) as numberSum,c.id,c.chanceType,c.chanceName,c.createTime,c.newUpdateDate,c.chanceCode,c.predictExeDate,c.contractPeriod,p.goodsName as conProductName,p.goodsId as conProductId,p.number,i.goodsTypeId,sub.topParentId,sub.topParentType
				@FROM
				   oa_sale_chance_goods p
				LEFT JOIN oa_sale_chance c ON c.id = p.chanceId
				LEFT JOIN oa_goods_base_info i ON p.goodsId = i.id
				LEFT JOIN ( SELECT t.id, p.goodsType AS topParentType,p.id AS topParentId FROM oa_goods_type t,
				            (SELECT * FROM oa_goods_type WHERE parentId =- 1 ) p WHERE t.lft >= p.lft AND t.rgt <= p.rgt
				          ) sub ON i.goodsTypeId = sub.id
				WHERE
				   sub.topParentId = ".isSell." ",
       //销售产品统计---下拉从表sql
       "select_chanceProduct_info"=>"select c.id,c.predictContractDate,c.winRate,c.chanceStage,c.status,c.chanceCode,c.chanceName,p.goodsName as conProductName,p.number from oa_sale_chance c LEFT JOIN oa_sale_chance_goods p ON c.id=p.chanceId where 1=1 ",


       //销售物料统计
		"select_chanceEqu"=>"select e.productName,e.productId,sum(e.number) as numberSum,c.winRate,c.chanceName,c.chanceType,c.status from oa_sale_chance_equ e
               LEFT JOIN oa_sale_chance c ON e.chanceId=c.id where 1=1",
       //销售物料统计---下拉从表sql
		"select_chanceEqu_info"=>"select c.id,c.predictContractDate,c.winRate,c.chanceStage,c.status,c.chanceCode,c.chanceName,e.productName,e.productId,e.number from oa_sale_chance c
				LEFT JOIN oa_sale_chance_equ e ON c.id=e.chanceId where 1=1",
	    //商机信息列表--产品统计sql
		"select_chanceGoods"=>"SELECT  GROUP_CONCAT(CAST(c.id AS char)) as chanceIds,count(c.id) as chanceNum,p.goodsName,sum(p.money) as chanceMoney,c.winRate,c.chanceStage,sum(p.number) as numberSum,c.id,c.chanceType,c.chanceName,c.createTime,c.newUpdateDate,c.chanceCode,c.predictExeDate,c.contractPeriod,p.goodsName,p.goodsId,p.number,i.goodsTypeId,sub.topParentId,sub.topParentType
				@FROM
				   (select id,chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,sum(money) as money from oa_sale_chance_goods GROUP BY chanceId,goodsId) p
				LEFT JOIN oa_sale_chance c ON c.id = p.chanceId
				LEFT JOIN oa_goods_base_info i ON p.goodsId = i.id
				LEFT JOIN ( SELECT t.id, p.goodsType AS topParentType,p.id AS topParentId FROM oa_goods_type t,
				            (SELECT * FROM oa_goods_type WHERE parentId =- 1 ) p WHERE t.lft >= p.lft AND t.rgt <= p.rgt
				          ) sub ON i.goodsTypeId = sub.id
                 where 1=1 and status=5",
	    //商机信息列表--产品统计sql --下拉从表
	    "select_chanceGoods_info"=>"SELECT  GROUP_CONCAT(CAST(c.id AS char)) as chanceIds,count(c.id) as chanceNum,p.goodsName,sum(p.money) as chanceMoney,c.winRate,c.chanceStage,sum(p.number) as numberSum,c.id,c.chanceType,c.chanceName,c.createTime,c.newUpdateDate,c.chanceCode,c.predictExeDate,c.contractPeriod,p.goodsName,p.goodsId,p.number,i.goodsTypeId,sub.topParentId,sub.topParentType
				@FROM
				   (select id,chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,sum(money) as money from oa_sale_chance_goods GROUP BY chanceId,goodsId) p
				LEFT JOIN oa_sale_chance c ON c.id = p.chanceId
				LEFT JOIN oa_goods_base_info i ON p.goodsId = i.id
				LEFT JOIN ( SELECT t.id, p.goodsType AS topParentType,p.id AS topParentId FROM oa_goods_type t,
				            (SELECT * FROM oa_goods_type WHERE parentId =- 1 ) p WHERE t.lft >= p.lft AND t.rgt <= p.rgt
				          ) sub ON i.goodsTypeId = sub.id
                 where 1=1 and status=5",
);

$condition_arr = array (
   array(
        "name" => "ids",
        "sql" => " and c.Id in(arr)"
   ),
   array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "chanceLevel",
   		"sql" => " and c.chanceLevel=# "
   	  ),
   array(
   		"name" => "chanceStage",
   		"sql" => " and c.chanceStage=# "
   	  ),
   array(
   		"name" => "winRate",
   		"sql" => " and c.winRate=# "
   	  ),
   array(
   		"name" => "chanceType",
   		"sql" => " and c.chanceType=# "
   	  ),
   array(
   		"name" => "chanceTypeName",
   		"sql" => " and c.chanceTypeName=# "
   	  ),
   array(
   		"name" => "chanceNature",
   		"sql" => " and c.chanceNature=# "
   	  ),
   array(
   		"name" => "chanceNatureName",
   		"sql" => " and c.chanceNatureName=# "
   	  ),
   array(
   		"name" => "chanceMoney",
   		"sql" => " and c.chanceMoney=# "
   	  ),
   array(
   		"name" => "predictContractDate",
   		"sql" => " and c.predictContractDate=# "
   	  ),
   array(
   		"name" => "predictExeDate",
   		"sql" => " and c.predictExeDate=# "
   	  ),
   array(
   		"name" => "contractPeriod",
   		"sql" => " and c.contractPeriod=# "
   	  ),
   array(
   		"name" => "newUpdateDate",
   		"sql" => " and c.newUpdateDate=# "
   	  ),
   array(
   		"name" => "trackman",
   		"sql" => " and c.trackman=# "
   	  ),
   array(
   		"name" => "trackmanId",
   		"sql" => " and c.trackmanId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "customerProvince",
   		"sql" => " and c.customerProvince=# "
   	  ),
   array(
   		"name" => "customerType",
   		"sql" => " and c.customerType=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "progress",
   		"sql" => " and c.progress=# "
   	  ),
   array(
   		"name" => "competitor",
   		"sql" => " and c.competitor=# "
   	  ),
   array(
   		"name" => "won",
   		"sql" => " and c.won=# "
   	  ),
   array(
   		"name" => "Country",
   		"sql" => " and c.Country=# "
   	  ),
   array(
   		"name" => "CountryId",
   		"sql" => " and c.CountryId=# "
   	  ),
   array(
   		"name" => "Province",
   		"sql" => " and c.Province=# "
   	  ),
   array(
   		"name" => "ProvinceId",
   		"sql" => " and c.ProvinceId=# "
   	  ),
   array(
   		"name" => "City",
   		"sql" => " and c.City=# "
   	  ),
   array(
   		"name" => "CityId",
   		"sql" => " and c.CityId=# "
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
   		"name" => "prinvipalName",
   		"sql" => " and c.prinvipalName=# "
   	  ),
   array(
   		"name" => "prinvipalId",
   		"sql" => " and c.prinvipalId=# "
   	  ),
   array(
   		"name" => "prinvipalDept",
   		"sql" => " and c.prinvipalDept=# "
   	  ),
   array(
   		"name" => "prinvipalDeptId",
   		"sql" => " and c.prinvipalDeptId=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status =# "
   	  ),
   array(
   		"name" => "statusArr",
   		"sql" => " and c.status in(arr) "
   	  ),
   array(
   		"name" => "closeId",
   		"sql" => " and c.closeId=# "
   	  ),
   array(
   		"name" => "closeName",
   		"sql" => " and c.closeName=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "closeRegard",
   		"sql" => " and c.closeRegard=# "
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
   		"name" => "customerNeed",
   		"sql" => " and c.customerNeed=# "
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
   array(
   		"name" => "orderNatureName",
   		"sql" => " and c.orderNatureName=# "
   	  ),
   array(
   		"name" => "orderNature",
   		"sql" => " and c.orderNature=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "changeEquDate",
   		"sql" => " and c.changeEquDate=# "
   	  ),
   array(
   		"name" => "changeEquName",
   		"sql" => " and c.changeEquName=# "
   	  ),
   array(
   		"name" => "changeEquNameId",
   		"sql" => " and c.changeEquNameId=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "isBecome",
   		"sql" => " and c.isBecome=# "
   	  ),
   array(
   		"name" => "makeStatus",
   		"sql" => " and c.makeStatus=# "
   	  ),
   array(
   		"name" => "dealStatus",
   		"sql" => " and c.dealStatus=# "
   	  ),
   array(
   		"name" => "DeliveryStatus",
   		"sql" => " and c.DeliveryStatus=# "
   	  ),
   array(
   		"name" => "customTypeId",
   		"sql" => " and c.customTypeId=# "
   	  ),
   array(
   		"name" => "customTypeName",
   		"sql" => " and c.customTypeName=# "
   	  ),
   array(
   		"name" => "warnDate",
   		"sql" => " and c.warnDate=# "
   	  ),
   array(
   		"name" => "reterStart",
   		"sql" => " and c.reterStart=# "
   	  ),
   array(
   		"name" => "trackmanIdForS",
   		"sql" => " and s.trackmanId=# "
   	),
   array(
   		"name" => "timingDate",
   		"sql" => " and c.timingDate=# "
   	),
   	array(
	    "name" => "goodsName",
	    "sql" => "and c.id in (select chanceId from oa_sale_chance_goods where goodsName like CONCAT('%',#,'%'))"
	),
	array(
	    "name" => "goodsNameSer",
	    "sql" => "and p.goodsName like CONCAT('%',#,'%')"
	),
	array(
	    "name" => "conProductIdC",
	    "sql" => "and p.goodsId =#"
	),
	array(
	    "name" => "productId",
	    "sql" => "and e.productId =#"
	),
	array(
	    "name" => "productName",
	    "sql" => "and e.productName like CONCAT('%',#,'%')"
	),
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
	array(
		"name" => "staClose",//搜索条件-合同编号
		"sql"=>" and c.status not in(#) "
	),
	array(
		"name" => "staSJ",//搜索条件-合同编号
		"sql"=>" and c.status not in(#) "
	),
    array(
   		"name" => "module",
   		"sql" => " and c.module=# "
   	),
    array(
   		"name" => "moduleName",
   		"sql" => " and c.moduleName=# "
   	)
)
?>