<?php
/**
 * @author tse
 * @Date 2014年4月1日 11:53:04
 * @version 1.0
 * @description:合同验收单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select * from " .
         		"(" .
         		"select c.id ,c.dateCode,c.checkDateR,c.days,c.remark,c.confirmStatus ,c.checkStatus ,c.contractCode ,c.realEndDate ,c.clause ,c.checkDate ,c.checkDate as  checkDateOld, c.isSend ,c.remind ," .
         		"c.realCheckDate ,c.reason,c.contractId,c.isChange,c.clauseInfo," .
         		"d.areaCode,d.areaName,d.appNameStr,d.prinvipalId,d.goodsTypeStr,d.contractType,d.contractName,d.customerName,d.customerId,d.customerType,d.contractCountryId,d.contractProvinceId" .
         		",d.contractCityId,d.createId,d.completeDate,d.ExaStatus" .
         		"  from oa_contract_check c" .
         		" left join oa_contract_contract d on(c.contractId = d.id) where 1=1 ".CONTOOLIDS_D."" .
         		")c where 1=1",
		"select_clause" =>"select c.id ,c.dateCode,c.checkDateR,c.days,c.remark,c.confirmStatus ,c.checkStatus ,c.contractCode ,c.realEndDate ,c.clause ,c.clauseInfo,c.checkDate ,c.isSend ,c.remind ,c.realCheckDate ,
				c.isChange ,c.reason,c.contractId,d.dateName  from oa_contract_check c left join oa_contract_check_setting d
				on c.clause = d.clause where 1=1 ",
		"select_statistical"=>"select * from(" .
				"select c.id ,c.dateCode,c.checkDateR,c.days,c.remark,c.confirmStatus ,c.checkStatus ,c.contractCode ,c.realEndDate ,c.clause ,c.checkDate ,c.isSend ," .
				"c.remind ,c.realCheckDate ,c.reason,c.contractId ,d.ExaDTOne,if(c.checkDate > CURDATE(),0,1) as isOutDate,  " .
		"if(c.confirmStatus = '已确认' and c.checkStatus = '已验收',1,0) as isFinish," .
		"d.areaCode,d.areaName,d.appNameStr,d.prinvipalId,d.goodsTypeStr,d.contractType,d.contractName,d.customerName,d.customerId,d.customerType,d.contractCountryId,d.contractProvinceId" .
		",d.contractCityId,d.createId,d.completeDate,d.ExaStatus" .
		" from oa_contract_check c left join oa_contract_contract d on(c.contractId = d.id) where 1=1  ".CONTOOLIDS_D."" .
				")c where 1=1"

);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "days",
   		"sql" => " and c.days=# "
        ),
   array(
   		"name" => "confirmStatus",
   		"sql" => " and c.confirmStatus=# "
   	  ),
   array(
   		"name" => "checkStatus",
   		"sql" => " and c.checkStatus=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "realEndDate",
   		"sql" => " and c.realEndDate=# "
   	  ),
   array(
   		"name" => "clause",
   		"sql" => " and c.clause=# "
   	  ),
   array(
   		"name" => "checkDate",
   		"sql" => " and c.checkDate=# "
   	  ),
   array(
   		"name" => "isSend",
   		"sql" => " and c.isSend=# "
   	  ),
   array(
   		"name" => "remind",
   		"sql" => " and c.remind=# "
   	  ),
   array(
   		"name" => "realCheckDate",
   		"sql" => " and c.realCheckDate=# "
   	  ),
   array(
   		"name" => "reason",
   		"sql" => " and c.reason=# "
   	  ),
   array(
   		"name" => "clauseInfo",
   		"sql" => " and c.clauseInfo=# "
   	  ),
   array(
   		"name" => "contractCodeSearch",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "clauseSearch",
   		"sql" => " and c.clause like CONCAT('%',#,'%') "
   	  ),
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
	array (//自定义条件2
		"name" => "mySearchCondition2",
		"sql" => "$"
	),
   array(
   		"name" => "isChange",
   		"sql" => " and c.isChange =# "
   	  ),
	array (
		"name" => "ExaYear",
		"sql" => " and date_format(c.ExaDTOne,'%Y')=# "
	),
	array (
		"name" => "ExaYearMonth",
		"sql" => " and date_format(c.ExaDTOne,'%Y-%m')=# "
	)
)
?>