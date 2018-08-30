<?php

/**
 * @author Administrator
 * @Date 2011年5月4日 19:49:09
 * @version 1.0
 * @description:收料通知单信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.arrivalCode ,c.arrivalDate,c.arrivalType ,c.purchaseId ,c.purchaseCode ,c.supplierName ,c.supplierId ,c.remark ,c.purchManId ,c.purchManName ,c.purchMode ,c.deliveryPlace ,c.stockId ,c.stockName ,c.qualityStatus ,c.state ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.completionTime  from oa_purchase_arrival_info c where 1=1 ",
	"select_equ" => "select c.id ,c.arrivalCode ,c.purchaseId ,c.purchaseCode,c.supplierName ,c.stockName ,c.purchManName ,c.deliveryPlace ,p.arrivalId ,p.contractId ,p.businessType ,p.businessId ,p.productId ,p.productName ,p.sequence ,p.pattem ,p.batchNum ,p.units ,p.price ,p.moneyAll ,p.arrivalNum ,p.storageNum ,p.checkType,p.arrivalDate,p.month from oa_purchase_arrival_equ p " .
	" left join oa_purchase_arrival_info c on p.arrivalId=c.id where 1=1 ",
    "select_detail" => "select
        c.arrivalCode ,c.arrivalDate,c.arrivalType ,c.purchaseId ,c.purchaseCode ,
        c.supplierName ,c.supplierId ,c.remark ,c.purchManId ,c.purchManName ,
        c.purchMode ,c.deliveryPlace ,c.stockId ,c.stockName ,c.qualityStatus ,
        c.state ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,
        c.updateTime,
        e.id ,e.arrivalId ,e.contractId ,e.businessType ,e.businessId ,e.sequence ,
        e.productName ,e.sequence ,e.pattem ,e.batchNum ,e.units ,e.price ,e.moneyAll ,
        e.arrivalNum ,e.storageNum ,e.checkType,e.arrivalDate,e.month,
        e.qualityCode,e.qualityName,e.qualityPassNum,e.deliveredNum,e.completionTime
    from
        oa_purchase_arrival_info c inner join oa_purchase_arrival_equ e on c.id = e.arrivalId
    where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "arrivalCode",
		"sql" => " and c.arrivalCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "arrivalType",
		"sql" => " and c.arrivalType=# "
	),
	array (
		"name" => "purchaseId",
		"sql" => " and c.purchaseId=# "
	),
	array (
		"name" => "purchaseCode",
		"sql" => " and c.purchaseCode=# "
	),
	array (
		"name" => "supplierName",
		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "supplierId",
		"sql" => " and c.supplierId=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "purchManId",
		"sql" => " and c.purchManId=# "
	),
	array (
		"name" => "purchManName",
		"sql" => " and c.purchManName like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "purchMode",
		"sql" => " and c.purchMode=# "
	),
	array (
		"name" => "deliveryPlace",
		"sql" => " and c.deliveryPlace=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "qualityStatus",
		"sql" => " and c.qualityStatus=# "
	),
	array (
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array (
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "sequence",
		"sql" => "and c.id in(select arrivalId from oa_purchase_arrival_equ where sequence like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "productName",
		"sql" => "and c.id in(select arrivalId from oa_purchase_arrival_equ where productName like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "purchaseCodeSearch",
		"sql" => " and c.purchaseCode like CONCAT('%',#,'%')  "
	),
    array(
        "name" => "isCanInstock",
        "sql" => "and c.id in (select c.arrivalId from oa_purchase_arrival_equ c where ((c.qualityCode!='ZJSXCG' and c.arrivalNum>c.storageNum) or (c.qualityCode='ZJSXCG' and c.qualityPassNum>c.storageNum)) group by c.arrivalId)"
    ),
    array(
        "name" => "isCanInstockEqu",
        "sql" => " and ((e.qualityCode!='ZJSXCG' and e.arrivalNum>e.storageNum) or (e.qualityCode='ZJSXCG' and e.qualityPassNum>e.storageNum))"
    )
)
?>