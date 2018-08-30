<?php
$sql_arr = array (
	"equipment_list" => "select p.testType,p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ," .
			"p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountAllOld," .
			"p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.surpplierId ,p.surpplierName ," .
			"p.equUseYear ,p.planPrice ,p.isAsset,p.isTask,p.batchNumb,p.leastPackNum,p.leastOrderNum,p.inputProductName," .
			"p.productCategoryName,p.productCategoryCode,p.isProduce,p.isBack,p.qualityCode,p.qualityName,p.isPurch,p.arrivalPeriod,p.purchPeriod  from oa_purch_plan_equ p where p.amountAll>0 and p.isPurch=1",
	"equipment_list_all" => "select p.testType,p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ," .
			"p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountAllOld," .
			"p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.surpplierId ,p.surpplierName ," .
			"p.productCategoryName,p.productCategoryCode,p.isProduce,p.isBack,p.qualityCode,p.qualityName,p.isPurch,p.appOpinion   from oa_purch_plan_equ p where 1=1",
//	"equpment_basic1"=> "select p.*,c.beforeChangeAmount,c.amount as cAmount,c.notCarryAmount,c.byWayAmount,c.alreadyCarryAmount,c.stockAmount " .
//			"from oa_purch_plan_equ p left join oa_contract_sales_equ c on (p.contOnlyId=c.contOnlyId) ,oa_purch_plan_basic b where 1=1 ",
	//��ȡ���ϵ�ִ�����
	"equipment_execute_list"=>"select p.id,p.basicId,p.basicNumb,p.pattem,p.unitName,p.dateHope,p.productNumb,p.productName,p.amountAll,p.amountIssued,if(sum(te.amountIssued) is null,0,sum(te.amountIssued)) as inquiryNumbs,cast(if(sum(ae.amountAll) is null,0,sum(ae.amountAll)) as decimal(10,0)) as orderAmount, " .
								"if(sum(ae.amountIssued)is null,0,sum(ae.amountIssued)) as stokcNum " .
								"from oa_purch_plan_equ p " .
									 " left join oa_purch_task_equ te on(p.id=te.planEquId) " .
						             " left join oa_purch_apply_equ ae on(ae.taskEquId=te.id and ae.isTemp=0) " .
						             " where p.isPurch=1 and 1=1",
	//��ȡͬ��������
	"equpment_batch" => "select c.sendUserId,c.sendName,c.ExaStatus,c.sourceNumb,c.sendName,c.createId,p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.isTask,p.batchNumb  from oa_purch_plan_equ p left join oa_purch_plan_basic c on (p.basicId=c.id  and p.amountAll>0) where p.isPurch=1 and p.isTemp=0 ",
	//��ȡ�豸+�ƻ�������Ϣ����
	"equpment_basic" => "select c.sendUserId,c.state,c.sendName ,c.sendTime,c.sourceNumb ,c.contractName ,p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountAllOld ,p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.isTask,p.batchNumb,p.qualityCode,p.qualityName  from oa_purch_plan_equ p left join oa_purch_plan_basic c on (p.basicId=c.id and c.ExaStatus='���' and p.amountAll>0) where p.isPurch=1 and p.isTemp=0 ",
	//��ȡ�豸+�ƻ�������Ϣ����
	"equpment_plan" => "select c.sendUserId,c.state,c.sendName ,c.sendTime,c.sourceNumb ,c.contractName ,p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.isTask,p.batchNumb  from oa_purch_plan_equ p left join oa_purch_plan_basic c on (p.basicId=c.id ) where p.isPurch=1 and p.isTemp=0  and p.amountAll>0 and c.ExaStatus='���' and c.productSureStatus=1 ",
	//��ȡ�豸+�ϼ������ۺ�ͬ�豸����Ϣ���� ע����������ɹ��������
	"equpment_sup" => "select " .
			"p.id,p.objCode,p.purchType,p.basicNumb,p.basicId,p.productName,p.productId,p.productNumb,p.amountAll,p.amountIssued,p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.status ," .
			"c.id as cId,c.objAssId as cObjAssId,c.objAssType as cObjAssType " .
		"from oa_purch_plan_equ p left join oa_purch_plan_equ_objass c on (p.id=c.planEquId and c.objAssType in('order','stock_equ','assets_equ','rdproject_equ') ) ",
		//��ȡ��������
	"equipment_purchaser_list"=>"select te.id,te.planEquId,t.sendName " .
								"from oa_purch_task_equ te  " .
									 " left join oa_purch_task_basic t on(t.id=te.basicId) " .
						             " where 1=1",
      "minDateHope"=>"select min(p.dateHope) as dateHope from oa_purch_plan_equ p where p.isPurch=1 and 1=1",
      "equpment_progress"=>"select p.id,c.sourceNumb,p.basicId,p.basicNumb,v.customerName,p.productName,p.productNumb,p.amountAll,p.amountIssued,p.dateIssued,p.dateHope,p.purchType
								from oa_purch_plan_equ p
									left join oa_purch_plan_basic c on p.basicId=c.id
										left join view_oa_order v on v.orgId=c.sourceId
												where p.isPurch=1 and p.isTemp=0 and p.amountAll>0"


);

$condition_arr = array (
	array(
		"name" => "id",		//ͨ��Id��ѯ
		"sql" => " and p.id=# "
	),
	//��ҵ�����Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=# "
	),
	//��ҵ�����Ids
	array(
		"name" => "basicIds",
		"sql" => " and p.basicId in(arr) "
	),
	array(
		"name" => "purchTypeArr",
		"sql" => " and p.purchType in(arr) "
	),
	//��ҵ�������
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	),
	array(
		"name" => "basicNumbSear",
		"sql" => " and p.basicNumb  like CONCAT('%',#,'%')"
	),
	array(
		"name" => "dateIssued",
		"sql" => " and p.dateIssued  like CONCAT('%',#,'%')"
	),
	//�Ƿ���ʹ��
	array(
		"name" => "deviceIsUse",
		"sql" => " and p.deviceIsUse=# "
	),
	//״̬
	array(
		"name" => "status",
		"sql" => " and p.status=# "
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
	array(
		"name" => "planEquId",
		"sql" => " and te.planEquId=# "
	),
	//���� �豸����
	array(
		"name" => "productName",
		"sql" => " and p.productName like CONCAT('%',#,'%')"
	),
	//���� �豸���
	array(
		"name" => "seachProductNumb",
		"sql" => " and p.productNumb like CONCAT('%',#,'%') "
	),
	array(
		"name" => "sendBeginTime",
		"sql" => "and p.dateIssued >=#"
	),
	array(
		"name" => "sendEndTime",
		"sql" => "and p.dateIssued<=#"
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	//id����
	array(
		"name" => "arrayIds",
		"sql" => " and p.id in(arr) "
	),
	//�ɹ��ƻ��� �Ƿ���ʹ��isUse
	array(
		"name" => "isUse",
		"sql" => " and c.isUse=# "
	),
	//�ɹ��ƻ��� ״̬
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	//���κ�
	array(
		"name" => "batchNumb",
		"sql" => " and p.batchNumb=# "
	),
	//���κ�
	array(
		"name" => "searchBatchNumb",
		"sql" => " and p.batchNumb like CONCAT('%',#,'%') "
	),
	array(
		"name" => "sourceNumb",
		"sql" => " and c.sourceNumb like CONCAT('%',#,'%') "
	),
	array(
		"name" => "customerName",
		"sql" => " and v.customerName like CONCAT('%',#,'%') "
	),
	//�ɹ��ƻ��� �ɹ�����
	array(
		"name" => "purchType",
		"sql" => " and c.purchType=# "
	),
	//�ɹ��ƻ��� �ɹ�����
	array(
		"name" => "purchTypeEqu",
		"sql" => " and p.purchType=# "
	),
	array(
		"name" => "isNoAsset",
		"sql" => " and (p.isAsset is null or p.isAsset='') "
	),
	array(
		"name" => "notInId",
		"sql" => " and p.basicId not in(arr) "
	),
	array(
		"name" => "isBack",
		"sql" => " and p.isBack =# "
	),
	array(
		"name" => "isNotTask",
		"sql" => " and p.amountAll !=p.amountIssued "
	),
	array(
		"name" => "sendBeginTime",
		"sql" => " and c.sendTime >= #"
	),
	array(
		"name" => "sendEndTime",
		"sql" => " and c.sendTime <= #"
	)

);
?>