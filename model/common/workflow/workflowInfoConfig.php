<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * ��������չ��Ϣ����
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

/**
 * �ʼ���Ϣ����˵��
 * 1.��ά�����е�keyֵһ������Ҫ��ѯ�ı���,���Ǳ����Ļ���Ҳ��֪����ʲô�����Ӧ��
 *
 * @param1 thisCode����Ҫ��ѯ���ֶΣ�����c.��ͷ��
 * @param2 thisInfo����Ҫ��չ���ʼ���������ݣ���sql��ѯ�������ֶζ�Ӧ��û�ж�Ӧ������Ƶñ�����Ҫ������<br/>
 *
 * @param3 objArr ѡ��������˵������
 * ѡ���ֶΣ�-- ׼ȷ����˵���ǽ�ҵ����ж�Ӧ���ֶ����õ�����������
 * objCode ҵ����
 * objName ҵ������
 * objCustomer ҵ��ͻ�
 * objAmount ҵ����
 * ע��
 * 1.�����ֶ�ʱ����Ҫ��ǰ׺�������ò���
 * 2.�����ֶΣ���Ҫ��thisCode�д���
 */
$workflowInfoConfig = array(
	'oa_finance_invoiceapply' => array(//��Ʊ�������������չ�ֶ�
		'thisCode' => 'c.applyNo,c.customerName,c.invoiceMoney',
		'thisInfo' => '���뵥��: $applyNo , ��Ʊ��λ : $customerName , ��Ʊ��� : $invoiceMoney ',
		'objArr' => array(
			'objCode' => 'applyNo',
			'objCustomer' => 'customerName',
			'objAmount' => 'invoiceMoney'
		)
	),
	'oa_finance_payablesapply' => array(//�����������������չ�ֶ�
		'thisCode' => 'c.id,c.formNo,c.remark,c.supplierName,c.payMoney',
		//ID2209��ժҪ����������ֶν������ 2016-11-24 haojin
		'joinTable' => array(
			'joinSql' => 'SELECT p.id,p.payapplyId,p.expand2 AS purchType,a.id AS mainId from oa_finance_payablesapply_detail p LEFT JOIN oa_purch_apply_basic a ON p.objCode = a.hwapplyNumb ',
			'joinSqlForProjectNo' => 'select p.projectCode,p.projectName,money as objAmount from oa_finance_payablesapply_detail d left join oa_esm_project p on d.expand3 = p.id where expand1 = "������Ŀ" and payapplyid = ',
			'action' => 'searchForPurchType'
		),
		'thisInfo' => '�����뵥��id : $id , ���뵥��: $formNo , ������; : $remark , ���λ : $supplierName , ������ ��$payMoney',
		'objArr' => array(
			'objCode' => 'formNo',
			'objCustomer' => 'supplierName',
			'objAmount' => 'payMoney'
		)
	),
	'oa_sale_outsourcing' => array(//�����ͬ���������չ�ֶ�
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '��ͬ��: $orderCode , ��ͬ���� : $orderName , ǩԼ��˾ : $signCompanyName , ��ͬ��� : $orderMoney ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'SELECT if(projectCode is null,"",projectCode) as projectCode,if(projectName is null,"",projectName) as projectName,orderMoney as objAmount FROM oa_sale_outsourcing WHERE ID = ',
			'action' => 'oa_sale_outsourcing'
		),
		'objArr' => array(
			'objCode' => 'orderCode',
			'objName' => 'orderName',
			'objCustomer' => 'signCompanyName',
			'objAmount' => 'orderMoney'
		)
	),
	'oa_sale_other' => array(//��ͬ���������չ�ֶ�
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '��ͬ��: $orderCode , ��ͬ���� : $orderName , ǩԼ��˾ : $signCompanyName , ��ͬ��� : $orderMoney ',
		'objArr' => array(
			'objCode' => 'orderCode',
			'objName' => 'orderName',
			'objCustomer' => 'signCompanyName',
			'objAmount' => 'orderMoney'
		)
	),
	'oa_hr_trialdeptsuggest' => array(//���Ž���
		'thisCode' => 'c.userName,c.deptName,c.deptSuggestName,c.suggestion',
		'thisInfo' => 'Ա������: $userName , �������� : $deptName , ���Ž��� : $deptSuggestName , �������� : $suggestion '
	),
	'cost_summary_list' => array(//��������
		'thisCode' => 'c.BillNo,c.Amount,c.CostManName,c.CostMan,if((c.projectNo is not null and c.projectNo <> ""),concat("/",c.projectNo),"") as projectNo
		,if((c.projectName is not null and c.projectName <> ""),concat("/",c.projectName),"") as projectName,case when c.DetailType = 1 then "���ŷ���"  when c.DetailType = 2 then "��ͬ��Ŀ����"  when c.DetailType = 3 then "�з�����"  when c.DetailType = 4 then "��ǰ����" when c.DetailType = 5 then "�ۺ����"  ELSE "���̱���" END as DetailTypeName',
		'thisInfo' => '$DetailTypeName$projectNo$projectName/$BillNo/$AmountԪ',//'��������: $BillNo , ������� : $Amount , ������ : $CostManName ',
		'joinTable' => array(
			'joinSql' => 'select id,ProjectNo,projectName from cost_summary_list where DetailType in (2,3) and id = ',
			'joinSqlForProjectNo' => 'select id,ProjectNo,projectName,proProvince from cost_summary_list where id = ',
			'action' => 'searchForCostInfo'
		),
		'objArr' => array(
			'objCode' => 'BillNo',
			'objAmount' => 'Amount',
			'objUser'=>'CostMan',
			'objUserName'=>'CostManName'
		)
	),
	'oa_esm_project' => array(//������Ŀ
		'thisCode' => 'c.projectCode,c.projectName,c.managerName',
		'thisInfo' => '��Ŀ���: $projectCode , ��Ŀ���� : $projectName ,��Ŀ����$managerName ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project where id = ',
			'action' => 'oa_esm_project'
		),
		'objArr' => array(
			'objCode' => 'projectCode',
			'objName' => 'projectName'
		)
	),
	'oa_esm_change_baseinfo' => array(//������Ŀ���
		'thisCode' => 'c.projectCode,c.projectName,c.changeDescription',
		'thisInfo' => '��Ŀ���: $projectCode , ��Ŀ���� : $projectName ,���˵����$changeDescription ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_change_baseinfo where id = ',
			'action' => 'oa_esm_change_baseinfo'
		),
		'objArr' => array(
			'objCode' => 'projectCode',
			'objName' => 'projectName'
		)
	),
	'oa_esm_project_close' => array(//������Ŀ�ر�
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project_close where id = ',
			'action' => 'oa_esm_project_close'
		)
	),
	'oa_esm_project_statusreport' => array(//������Ŀ�ܱ�
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project_statusreport where id = ',
			'action' => 'oa_esm_project_statusreport'
		)
	),
	'oa_contract_contract' => array(//��ͬ����
		'thisCode' => 'c.contractCode,c.contractName,c.customerName,c.contractMoney',
		'thisInfo' => '��ͬ���: $contractCode , ��ͬ���� : $contractName',
		'objArr' => array(
			'objCode' => 'contractCode',
			'objName' => 'contractName',
			'objCustomer' => 'customerName',
			'objAmount' => 'contractMoney'
		)
	),
	'oa_borrow_borrow' => array(//��ͬ����
		'thisCode' => 'c.Code , c.createName',
		'thisInfo' => '���õ���: $Code , ������ : $createName',
		'objArr' => array(
			'objCode' => 'Code'
		)
	),
	'oa_flights_require'=>array(//��Ʊ��Ʊ��������
		'thisCode' => 'c.requireName,c.requireTime,c.startPlace,c.endPlace,c.startDate,c.airName',
		'thisInfo' => '&nbsp;&nbsp;������ ��$requireName, �������� ��$requireTime, �������� ��$startDate ,�������� ��$startPlace ,������� ��$endPlace<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	),
	'oa_finance_compensate'=>array(//�⳥������
		'thisCode' => 'c.formCode,c.formDate',
		'thisInfo' => '���ݺ� ��$formCode, ��������:$formDate',
		'objArr' => array(
			'objCode' => 'formCode'
		)
	),
	'oa_general_specialapply'=>array(//�ر���������
		'thisCode' => 'c.applyUserName,c.applyDate,c.formNo',
		'thisInfo' => '������ ��$applyUserName, ��������:$applyDate, ����:$formNo ',
		'joinTable' => array(
			'joinSql' => 'select id,projectCode,projectName from oa_general_specialcostbelong where detailType in (2,3) and mainId = ',
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_general_specialcostbelong where mainId = ',
			'action' => 'searchForSpecialapplyInfo'
		),
		'objArr' => array(
			'objCode' => 'formNo'
		)
	),
	'oa_hr_leave'=>array( //��ְ��������
		'thisCode' => 'c.leaveCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '���ݱ�� ��$leaveCode, Ա����� :$userNo, Ա������ :$userName, ���� :$deptName, ְλ :$jobName',
		'objArr' => array(
			'objCode' => 'leaveCode'
		)
	),
	'oa_hr_worktime_apply'=>array( //�ڼ��ռӰ���������
		'thisCode' => 'c.applyCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '���ݱ�� ��$applyCode, Ա����� :$userNo, Ա������ :$userName, ���� :$deptName, ְλ :$jobName',
		'objArr' => array(
			'objCode' => 'applyCode'
		)
	),
	'oa_outsourcing_allregister'=>array( //�⳵�Ǽ�����
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_outsourcing_allregister where id = ',
			'action' => 'oa_outsourcing_allregister'
		),
	),
	'oa_outsourcing_rentalcar'=>array( // �⳵��������
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_outsourcing_rentalcar where id = ',
			'action' => 'oa_outsourcing_rentalcar'
		),
	)
);

?>
