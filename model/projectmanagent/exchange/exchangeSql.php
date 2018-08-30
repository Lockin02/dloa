<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:换货申请单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.exchangeType,c.exchangeTypeName,c.deliveryCondition,c.arrivalDate,c.expectedDate,c.objCode,c.exchangeCode,c.customerName,c.customerId ,c.contractObjCode ,c.contractId,c.contractName ,c.contractCode ,c.saleUserId ,c.saleUserName ,c.reason ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus  from oa_contract_exchangeapply c LEFT JOIN oa_contract_contract oc on c.contractId = oc.id where 1=1 "
        ,"select_shipments"=>"select c.issuedBackStatus,c.id ,c.objCode,c.exchangeCode,c.customerName,c.customerId ,c.contractObjCode
        		,c.customTypeId,c.customTypeName,c.warnDate,c.contractId,c.contractName ,c.contractCode ,c.saleUserId ,c.saleUserName ,c.reason ,c.updateTime
        		,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT
        		,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus
        		,l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne, l.id as linkId
         		 from oa_contract_exchangeapply c left join oa_exchange_equ_link l on
         		 		(c.id=l.exchangeId and l.exchangeType='oa_contract_exchangeapply' and l.isTemp=0) left join oa_contract_contract ct on c.contractId=ct.id  where c.isTemp=0 "
		,"select_equ" => "SELECT * FROM ( SELECT
								ce.productId AS id,
								ce.productId,
								ce.productCode,
								ce.productCode AS productNo,
								ce.productName,
								SUM(ce.number) AS number,
								SUM(ce.executedNum) AS executedNum,
								SUM(ce.onWayNum) AS onWayNum
							FROM
								oa_contract_exchange_equ ce
							RIGHT JOIN oa_contract_exchangeapply c ON (c.id = ce.exchangeId)
							WHERE
								1 = 1
							AND c.ExaStatus = '完成' AND c.DeliveryStatus != 'TZFH'
							AND ce.isTemp = 0
							AND ce.isDel = 0
							GROUP BY productId ) ce WHERE 1 = 1 "
	,"select_cont" => "SELECT
						c.id,
						c.exchangeCode,
						ce.number,
						ce.onWayNum,
						ce.executedNum
					FROM
						oa_contract_exchangeapply c LEFT JOIN oa_contract_exchange_equ ce
							ON ( c.id=ce.exchangeId )
					WHERE
						1 = 1
					AND c.ExaStatus = '完成' AND c.DeliveryStatus != 'TZFH' AND ce.isTemp=0 AND ce.isDel=0 "
);


$condition_arr = array (
	array (
		"name" => "issuedBackStatus",
		"sql" => " and c.issuedBackStatus=# "
	),
    array(
        "name"=>"exchangeCode",
        "sql"=>" and c.exchangeCode like CONCAT('%',#,'%')"
    ),
	array (
		"name" => "customTypeId",
		"sql" => " and c.customTypeId=# "
	),
	array (
		"name" => "customTypeName",
		"sql" => " and c.customTypeName=# "
	),
	array (
		"name" => "warnDate",
		"sql" => " and c.warnDate=# "
	),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "contractObjCode",
   		"sql" => " and c.contractObjCode=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "saleUserId",
   		"sql" => " and c.saleUserId=# "
   	  ),
   array(
   		"name" => "saleUserName",
   		"sql" => " and c.saleUserName=# "
   	  ),
   array(
   		"name" => "changeReason",
   		"sql" => " and c.changeReason=# "
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
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
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
   		"name" => "makeStatus",
   		"sql" => " and c.makeStatus=# "
   	  ),
   array(
        "name" => "dealStatusArr",
        "sql" => "and c.dealStatus in(arr) "
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
   		"name" => "lExaStatusArr",
   		"sql" => " and l.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "DeliveryStatus2",
   		"sql" => " and c.DeliveryStatus in(arr) "
   	  )
	/**********设备汇总表**********/
	,array (
		"name" => "productId",
		"sql" => " and ce.productId=# "
	),array (
		"name" => "productCode",
		"sql" => " and ce.productCode like CONCAT('%',#,'%')  "
	),array (
		"name" => "productName",
		"sql" => " and ce.productName like CONCAT('%',#,'%')  "
	),
	array (
	    "name" => "areaCodeSql",
	    "sql" => "$"
	),
    array (
        "name" => "createIdSql",
        "sql" => "$"
    ),
    array(
        "name" => "contractAreaCode",
        "sql" => " and oc.areaCode in(arr) "
    )
)
?>