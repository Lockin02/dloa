<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * ������������������ļ�
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

//Ҫ�鿴Ч����,��isEdit�͸������������detailSet ��Ϊ1,��������ǸĻ���,����Ҫ��Է�
//����ģʽ,1��ʱ���ύ������
$isEdit = 0;

/**
 * �ʼ���Ϣ����˵��
 * 1.��ά�����е�keyֵһ������Ҫ��ѯ�ı���,���Ǳ����Ļ���Ҳ��֪����ʲô�����Ӧ��
 *
 * @param1 thisCode����Ҫ��ѯ���ֶΣ�һ����c.��ͷ������Ҫ����
 * @param2 thisInfo����Ҫ��չ���ʼ���������ݣ���sql��ѯ�������ֶζ�Ӧ��û�ж�Ӧ������Ƶñ�����Ҫ������<br/>
 * @param3 detailSet �Ƿ���Ҫ����ϸ����
 * @param4 actFunc ��ϸ���õķ�����workflowMailInit.php ����ļ���
 */


$workflowMailConfig = array(
	'oa_finance_invoiceapply' => array(//��Ʊ�������������չ�ֶ�
		'thisCode' => 'c.applyNo,c.customerName,c.invoiceMoney',
		'thisInfo' => '���뵥��: $applyNo , ��Ʊ��λ : $customerName , ��Ʊ��� : $invoiceMoney '
	),

	'oa_finance_payablesapply' => array(//�����������������չ�ֶ�
		'thisCode' => 'c.id,c.formNo,c.formDate,c.supplierName,c.payMoney',
		'thisInfo' => '�����뵥��id : $id , ���뵥��: $formNo , �������� : $formDate , ���λ : $supplierName , ' .
			'Դ����ţ�$sourceCode �������� ��$payMoney ' .
			'<br>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red">����������������絥�ݲ���ί�и���뽫���������ύ���������֧����</span>',
		'detailSet' => 1,
		'selectSql' => 'select c.id,c.formNo,c.formDate,c.supplierName,c.payMoney,d.objCode,d.objType,d.money,d.productNo,d.productName
			from oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where 1=1 ',
		'actFunc' => 'payablesapplyFun'
	),
	'oa_sale_stampapply' => array(//�����������������չ�ֶ�
		'thisCode' =>'c.fileName,c.signCompanyName,c.stampType,c.stampExecutionName,c.useMatters,c.remark',
		'thisInfo' => '�����ļ���: $fileName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;�ļ����͵�λ: $signCompanyName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;��������:$stampType ,<br/> &nbsp;&nbsp;&nbsp;&nbsp;��������:$stampExecutionName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;ʹ������: $useMatters , <br/>&nbsp;&nbsp;&nbsp;&nbsp;˵��: $remark'
	),
	'oa_sale_outsourcing' => array(//�����ͬ���������չ�ֶ�
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '��ͬ��: $orderCode , ��ͬ���� : $orderName , ǩԼ��˾ : $signCompanyName , ��ͬ��� : $orderMoney '
	),
	'oa_sale_other' => array(//��ͬ���������չ�ֶ�
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '��ͬ��: $orderCode , ��ͬ���� : $orderName , ǩԼ��˾ : $signCompanyName , ��ͬ��� : $orderMoney '
	),
	'oa_purch_inquiry' => array(//�ɹ�ѯ�������չ�ֶ�
		'thisCode' => 'c.inquiryCode',
		'thisInfo' => 'ѯ�۵���: $inquiryCode ',
		'detailSet' =>1,
		'selectSql' => 'select p.parentId,c.id,c.inquiryCode,p.productNumb,p.productName,p.amount
			 			from oa_purch_inquiry_equ p left join oa_purch_inquiry c on c.id = p.parentId where 1=1 ',
		'actFunc' => 'inquiryFun'
	),
	'oa_purch_apply_basic' => array(//�ɹ����������չ�ֶ�
		'thisCode' => 'c.hwapplyNumb',
		'thisInfo' => '�������: $hwapplyNumb ',
		'detailSet' =>1,
		'selectSql' => 'select p.basicId,c.id,c.hwapplyNumb,p.productNumb,p.productName,p.pattem,p.units,p.amountAll,p.dateIssued,p.dateHope,p.applyDeptName,p.sourceNumb,p.purchType
			 			from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id = p.basicId where 1=1 ',
		'actFunc' => 'purchaseontractFun'
	),
	'oa_hr_recommend_bonus' => array(//�ڲ��Ƽ����������չ�ֶ�
		'thisCode' => 'c.formCode',
		'thisInfo' => '�ڲ��Ƽ�����: $formCode ',
		'detailSet' =>1,
		'selectSql' => 'select c.id ,c.formCode ,c.formDate ,c.formManId ,c.formManName ,c.isRecommendName ,c.resumeId ,c.resumeCode ,c.positionId ,c.positionName ,c.developPositionId
		 ,c.developPositionName ,c.job ,c.jobName ,c.entryDate ,c.becomeDate ,c.beBecomDate ,c.recommendName ,c.recommendId ,c.recommendReason ,c.state ,c.isBonus ,c.bonus ,c.bonusProprotion
		 ,c.firstGrantDate ,c.firstGrantBonus ,c.secondGrantDate ,c.secondGrantBonus ,c.remark
		 from oa_hr_recommend_bonus c where 1=1 ',
		'actFunc' => 'recommendbonusFun'
	),
	'oa_hr_personnel_transfer' => array(//Ա�����������չ�ֶ�
		'thisCode' => 'c.formCode,c.applyDate,c.userName,c.preDeptNameS,c.afterDeptNameS,c.preDeptNameT,c.afterDeptNameT,c.preJobName,c.afterJobName',
		'thisInfo' => '&nbsp;&nbsp;���뵥��: $formCode, <br/> &nbsp;&nbsp;�������� ��$applyDate, <br/> &nbsp;&nbsp;������Ա���� :$userName ,<br/> &nbsp;&nbsp;����ǰ��������:$preDeptNameS , <br/> &nbsp;&nbsp;�������������: $afterDeptNameS ,<br/> &nbsp;&nbsp;����ǰ��������:$preDeptNameT , <br/> &nbsp;&nbsp;��������������: $afterDeptNameT , <br/> &nbsp;&nbsp;����ǰְλ :$preJobName, <br/> &nbsp;&nbsp;������ְλ :$afterJobName,'

	),
	'oa_hr_personnel_certifyapply' => array(//��ְ�ʸ�����
		'thisCode' => 'c.applyDate,c.careerDirectionName,c.baseLevelName,c.baseGradeName',
		'thisInfo' => '&nbsp;&nbsp;�������ڣ�$applyDate, ����ְҵ��չͨ��: $careerDirectionName, ���뼶�� ��$baseLevelName, ���뼶�� :$baseGradeName '
	),
	'oa_hr_certifyapplyassess' => array(//��ְ�ʸ�����
		'thisCode' => 'c.careerDirectionName,c.baseLevelName,c.baseGradeName',
		'thisInfo' => '&nbsp;&nbsp;����ְҵ��չͨ��: $careerDirectionName, ���뼶�� ��$baseLevelName, ���뼶�� :$baseGradeName <br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:�������������ͨ�����������޸ĺ��ύ����</font>'
	),
	'oa_contract_contract' => array(//��ͬ����
		'thisCode' => 'c.contractCode,c.contractName,c.prinvipalName',
		'thisInfo' => '&nbsp;&nbsp;��ͬ���: $contractCode, ��ͬ���� ��$contractName, ��ͬ������ :$prinvipalName <br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:�������������ͨ�����������޸ĺ��ύ����</font>'
	),
	'oa_asset_allocation' => array(//�ʲ���������
		'thisCode' => 'c.billNo',
		'thisInfo' => '&nbsp;&nbsp;���ݱ��: $billNo',
		'detailSet' =>0,
	),
	'oa_hr_recruitment_apply'=>array(   //��Ա��������
		'thisCode' => 'c.formCode,c.formManName,c.deptName,c.positionName',
		'thisInfo' => '&nbsp;&nbsp;���ݱ��: $formCode, ����� ��$formManName, ������ :$deptName ,����ְλ : $positionName<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:�������������ͨ�����������޸ĺ��ύ����</font>'
	),
	'oa_flights_require'=>array(//��Ʊ��Ʊ��������
		'detailSet' =>1,
		'selectSql' => 'SELECT c.requireName,c.requireTime,c.startPlace,c.endPlace,c.startDate,s.airName FROM oa_flights_require c 
		LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT(airName)) AS airName,mainId FROM oa_flights_require_suite GROUP BY mainId) s ON s.mainId = c.id
		WHERE 1 = 1 ',
		'actFunc' => 'flightsRequireFun'
	),
	'oa_asset_requirement'=>array(//�̶��ʲ�������������
		'thisCode' => 'c.requireCode',
		'thisInfo' => '&nbsp;&nbsp;���󵥱��: $requireCode<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	),
	'oa_stockup_apply' => array(//�ڲ��Ƽ����������չ�ֶ�
		'thisCode' => 'c.listNo,c.projectName,c.appUserName,c.description',
		'thisInfo' => '&nbsp;&nbsp;������: $listNo, ��Ŀ���� ��$projectName, ������ :$appUserName ,˵�� : $description',
		'detailSet' =>1,
		'selectSql' => 'select c.id ,c.listNo,c.projectName,c.appUserName,c.description,d.productName ,d.productNum ,d.productConfig ,d.exDeliveryDate
		 from oa_stockup_apply_products d left join oa_stockup_apply c on c.id = d.appId where 1=1 ',
		'actFunc' => 'stockUpProductsFun'
	),
	'oa_hr_worktime_apply'=>array( //�ڼ��ռӰ���������
		'thisCode' => 'c.applyCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '���ݱ�� ��$applyCode, Ա����� :$userNo, Ա������ :$userName, ���� :$deptName, ְλ :$jobName',
		'objArr' => array(
			'objCode' => 'applyCode'
		)
	),
	'oa_asset_scrap'=>array(//�̶��ʲ�������������
		'thisCode' => 'c.billNo',
		'thisInfo' => '&nbsp;&nbsp;���ϵ����:$billNo<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	)
);