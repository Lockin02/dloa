<?php
$sql_arr = array (
//	"equipment_list" => "select " .
//				"p.id,p.deviceNumb,p.deviceIsUse,p.applyEquOnlyId,p.basicVersionNumb,p.basicNumb,p.basicId,p.objectsNumb,p.typeTabName,p.typeTabId,p.typeEquTabName,p.typeEquTabId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued,p.amountIssuedActual,p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.planId,p.plantNumb,p.planEquId,p.planEquNumb,p.taskId,p.taskNumb,p.taskEquId,p.taskEquNumb,p.applyPrice,p.applyFactPrice,p.status  " .
//			"from oa_purch_apply_equ p where 1=1",
//	"equ_list" => " select " .
//				"p.id, p.deviceNumb, p.deviceIsUse, p.applyEquOnlyId, p.basicVersionNumb, p.basicNumb, p.basicId, p.objectsNumb, p.typeTabName, p.typeTabId, p.typeEquTabName, p.typeEquTabId, p.productName, p.productId, p.productNumb, p.amountAll, p.amountIssued, p.amountIssuedActual, p.dateIssued, p.dateHope, p.dateEnd, p.remark, p.planId, p.plantNumb, p.planEquId, p.planEquNumb, p.taskId, p.taskNumb, p.taskEquId, p.taskEquNumb, p.applyPrice, p.applyFactPrice, p.status  " .
//			"from oa_purch_apply_equ p where 1=1 ",
	"equipment_list" => "select " .
				"p.id,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued,p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status  " .
			"from oa_purch_apply_equ p where 1=1",
	"equ_list" => " select " .
				"p.id, p.basicNumb, p.basicId, p.productName, p.productId, p.productNumb, p.amountAll, p.amountIssued, p.dateIssued, p.dateHope, p.dateEnd, p.remark, p.applyPrice, p.applyFactPrice, p.status  " .
			"from oa_purch_apply_equ p where 1=1 ",


    "equ_list1" => "select p.id ,p.objCode ,p.inquiryEquId ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.amountAll ,p.amountIssued ,p.dateIssued ," .
    		"p.dateHope ,p.dateEnd ,p.remark ,p.units,p.applyPrice ,p.pattem, p.moneyAll,p.applyFactPrice ,p.status  from oa_purch_apply_equ p where 1=1 ",
//	"page_list" =>  "select p.*,c.beforeChangeAmount,c.amount as cAmount,c.notCarryAmount,c.byWayAmount,c.alreadyCarryAmount,c.stockAmount  " .
//			"from oa_purch_plan_equ p left join oa_contract_sales_equ c on (p.contOnlyId=c.contOnlyId) where 1=1"
);

$condition_arr = array (
	//�������뵥Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=# "
	),
	//��ҵ�������
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	),
	//�Ƿ���ʹ��
//	array(
//		"name" => "deviceIsUse",
//		"sql" => " and p.deviceIsUse=# "
//	),
	//״̬
	array(
		"name" => "status",
		"sql" => " and p.status=# "
	),
	//״̬����
	array(
		"name" => "statusArr",
		"sql" => " and p.status in(arr) "
	),
	//����Id
	array(
		"name" => "id",
		"sql" => " and p.id=# "
	),
	//����Ids
	array(
		"name" => "ids",
		"sql" => " and p.id in(arr) "
	),
	//�豸Id
	array(
		"name" => "productId",
		"sql" => " and p.productId=# "
	),
	//�豸���
	array(
		"name" => "productNumb",
		"sql" => " and p.productNumb=# "
	),
	//������taskNumb
//	array(
//		"name" => "taskNumb",
//		"sql" => " and p.taskNumb=# "
//	)
//	,array(
//		"name" => "proListId",
//		"sql" => " and p.proListId=#"
//	),array(
//		"name" => "productNumb",
//		"sql" => " and p.proListId like CONCAT('%',#,'%')"
//	),array(
//		"name" => "productName",
//		"sql" => " and p.productName like CONCAT('%',#,'%')"
//	)
);
?>