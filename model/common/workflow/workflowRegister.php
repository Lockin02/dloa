<?php
/**
 * ��ͨ����ҵ���������Ϣ
 * url          �����鿴ҳ��
 * viewUrl      �б�鿴ҳ��
 * isSkey     �Ƿ����
 * keyObj      ���ܶ���
 * rtUrl      ���ص���·��
 * isChange   �Ƿ��Ǳ������
 * changeCode ��Ӧ�������
 * allStep    ����ÿһ���������ص�����·��
 * search     �������ʹ��sql������
 * orgCode    ʵ��ʹ�õĹ���������
 */
$urlArr = array(
	/********************************�º�ͬ��������ע��*********************************************************************/
	'��ͬ����TA' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'��ͬ����TB' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'��ͬ����A' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'�������ͬ����' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'��ͬ����B' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'��ͬ�������A' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'�������ͬ�������' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'��ͬ�������B' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'��ͬɾ������' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=' //�б�ҳ��鿴·��
	),
	'�º�ͬ����ȷ������' => array( //�º�ͬ����
		'url' => '?model=contract_contract_equ&action=toEquView&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_equ&action=toEquView&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contequlink&action=confirmAudit&spid='
	),
	'�º�ͬ����ȷ�ϱ������' => array( //�º�ͬ����
		'url' => '?model=contract_contract_equ&action=toEquView&changeView=1&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_equ&action=toEquView&changeView=1&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contequlink&action=confirmChange&spid='
	),
	'����������ȷ������' => array( //����������
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_borrow_borrowequlink&action=confirmAudit&spid='
	),
	'����������ȷ�ϱ������' => array( //����������
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&changeView=1&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_borrow_borrowequlink&action=confirmChange&spid='
	),
	'��������ȷ������' => array( //��������
		'url' => '?model=projectmanagent_present_presentequ&action=toEquView&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_present_presentequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_present_presentequlink&action=confirmAudit&spid='
	),
	'��������ȷ�ϱ������' => array( //��������
		'url' => '?model=projectmanagent_present_presentequ&action=toEquView&changeView=1&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_present_presentequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_present_presentequlink&action=confirmChange&spid='
	),
	'��������ȷ������' => array( //�˻�����
		'url' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_exchange_exchangeequlink&action=confirmAudit&spid='
	),
	'��������ȷ�ϱ������' => array( //�˻�����
		'url' => '?model=projectmanagent_exchange_exchange&action=toEquView&changeView=1&linkId=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&&perm=view&linkId=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_exchange_exchangeequlink&action=confirmChange&spid='
	),
	'��ͬ�쳣�ر�' => array( //
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&closeType=close&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmCloseApprovalNo&spid='
	),
	'��ͬ�������' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'�����˻�����' => array(
		'url' => '?model=projectmanagent_return_return&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_return_return&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_return_return&action=confirmReturn&spid='
	),
	'������������' => array(
		'url' => '?model=projectmanagent_exchange_exchange&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_exchange_exchange&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_exchange_exchange&action=confirmExchange&spid='
	),
	'�ۿ���������' => array(
		'url' => '?model=contract_deduct_deduct&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_deduct_deduct&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_deduct_deduct&action=confirmDeduct&spid='
	),
	'������Ŀ����' => array(
		'url' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //�б�ҳ��鿴·��
		//		'rtUrl' => '?model=projectmanagent_trialproject_trialproject&action=confirmDeduct&spid='
	),
	'������Ŀ��������' => array(
		'url' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'��ǰ֧������' => array(
		'viewUrl' => '?model=projectmanagent_support_support&action=toView&id=',
		'url' => '?model=projectmanagent_support_support&action=appEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=projectmanagent_support_support&action=confirmSupport&urlType=1&spid=',
		'allStep' => '1'
	),
	'������������' => array(
		'url' => '?model=contract_stamp_stampapply&action=toAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_stamp_stampapply&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_stamp_stampapply&action=dealAfterAudit&spid='
	),
	'��ͬ������������' => array(
		'url' => '?model=contract_stamp_stampapply&action=toAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_stamp_stampapply&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_stamp_stampapply&action=dealAfterAudit&spid='
	),
	'�⳵��������' => array(
		'url' => '?model=outsourcing_vehicle_rentalcar&action=toAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=outsourcing_vehicle_rentalcar&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=outsourcing_vehicle_rentalcar&action=dealAfterAuditPass&spid='
	),
	'�⳵�Ǽ�����' => array(
		'url' => '?model=outsourcing_vehicle_allregister&action=toAudit&hideBtn=true&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=outsourcing_vehicle_allregister&action=toAudit&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=outsourcing_vehicle_allregister&action=dealAfterAuditPass&spid='
	),
	'�⳵��ͬ����' => array(
		'url' => '?model=outsourcing_contract_rentcar&action=toAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=outsourcing_contract_rentcar&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=outsourcing_contract_rentcar&action=dealAfterAudit&spid='
	),
	'�⳵��ͬ�������' => array(
		'url' => '?model=outsourcing_contract_rentcar&action=toChangeTab&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=outsourcing_contract_rentcar&action=toChangeTab&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=outsourcing_contract_rentcar&action=dealAfterAuditChange&spid='
	),
	'�����ͬ�ر�����' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewTab&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=viewTab&id=', //�б�ҳ��鿴·��
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing'
	),
	'������ͬ�ر�����' => array(
		'url' => '?model=contract_other_other&action=viewTab&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_other_other&action=viewTab&id=', //�б�ҳ��鿴·��
		'isSkey' => '1',
		'keyObj' => 'contract_other_other'
	),
	'��ͬ����C' => array( //�º�ͬ����
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'��ͬ�������C' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	/*********************************�º�ͬ��������ע��**END******************************************************************/
	//���񲿷�
	'�ɹ���������' => array( //��������
		'url' => '?model=finance_payablesapply_payablesapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'�ɹ��˿�����' => array( //�ɹ��˿�����
		'url' => '?model=finance_payablesapply_payablesapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'��������' => array( //��������
		'url' => '?model=finance_payablesapply_payablesapply&action=toViewAudit&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'��Ʊ����' => array( //��Ʊ����
		'url' => '?model=finance_invoiceapply_invoiceapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_invoiceapply_invoiceapply',
		'rtUrl' => '?model=finance_invoiceapply_invoiceapply&action=dealAfterAudit&spid='
	),
	'��ؿ�Ʊ����' => array(
		'url' => '?model=finance_invoiceapply_invoiceapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_invoiceapply_invoiceapply'
	),

	'�˿�����' => array( //�˿�����
		'url' => '?model=finance_payablesapply_payablesapply&action=initBack&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=toView&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'�⳥������' => array(
		'url' => '?model=finance_compensate_compensate&action=toViewAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=finance_compensate_compensate&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=finance_compensate_compensate&action=dealAfterAudit&spid='
	),

	//�ɹ�����
	'�ɹ����뵥' => array( //�ɹ�����
		'url' => '?model=purchase_plan_basic&action=read&actType=audit&id=',
		'viewUrl' => '?model=purchase_plan_basic&action=read&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=emailNotice&urlType=1&spid='
	),
	'�ɹ�ѯ�۵�����' => array( //�ɹ�ѯ��
		'url' => '?model=purchase_inquiry_inquirysheet&action=toAssignSupp&actType=audit&id=',
		'viewUrl' => '?model=purchase_inquiry_inquirysheet&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_inquiry_inquirysheet',
		'rtUrl' => '?model=purchase_inquiry_inquirysheet&action=assignSuppByApproval&urlType=1&spid='
	),
	'�ɹ���ͬ����' => array( //�ɹ�����
		'url' => '?model=purchase_contract_purchasecontract&action=approViewTab&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toReadTab&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=updateOnWayNumb&urlType=1&spid='
	),
	'�ɹ���������(����������)' => array( //�ɹ�����
		'url' => '?model=purchase_contract_purchasecontract&action=approViewTab&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toReadTab&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=updateOnWayNumb&urlType=1&spid='
	),
	'�ɹ������������' => array( //�ɹ�����
		'url' => '?model=purchase_contract_purchasecontract&action=toTabView&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=init&perm=view&id=',
		'auditedViewUrl' => '?model=purchase_contract_purchasecontract&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'isChange' => '1',
		'changeCode' => 'purchasecontract',
		'seachCode' => 'purchasecontractIsChange',
		'orgCode' => '�ɹ���ͬ����'
	),
	'�ɹ�������ֹ����' => array( //�ɹ�����
		'url' => '?model=purchase_contract_purchasecontract&action=toCloseTabRead&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toCloseTabRead&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=dealClose&urlType=1&spid='
	),
	'�ɹ������ر�����' => array( //�ɹ�����
		'url' => '?model=purchase_contract_purchasecontract&action=toCloseOrderTabRead&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toCloseOrderTabRead&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract'
	),
	'�ɹ���������' => array( //�ɹ�����
		'url' => '?model=purchase_delivered_delivered&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=purchase_delivered_delivered&action=init&perm=view&id=',
		//		'rtUrl'=>'?model=purchase_delivered_delivered&action=updateApplyPrice&spid=',
		'isSkey' => '1',
		'keyObj' => 'purchase_delivered_delivered'
	),
	'�����ɹ���������' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=produce&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=produce&show=1&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=dealApproval&urlType=1&spid=',
		'allStep' => '1'
	),
	'�з��ɹ���������' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=rdproject&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=rdproject&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => "?model=purchase_plan_basic&action=confirmAudit&urlType=1&spid="
	),
	'�ʲ��ɹ���������' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=assets&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=emailNotice&urlType=1&spid='
	),
	'�ɹ�����������' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'isChange' => '1',
		'seachCode' => 'purchaseplan',
		'orgCode' => '�ɹ�����������',
		'rtUrl' => '?model=purchase_plan_basic&action=dealChange&urlType=1&spid='
	),
	'�ɹ�����ر�����' => array(
		'viewUrl' => '?model=purchase_task_basic&action=toCloseRead&id=',
		'url' => '?model=purchase_task_basic&action=toCloseRead&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_task_basic'
	),
    
	'�豸����' => array(
		'viewUrl' => '?model=projectmanagent_present_present&action=init&perm=view&id=',
		'url' => '?model=projectmanagent_present_present&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_present_present',
        'rtUrl' => '?model=projectmanagent_present_present&action=dealAfterAudit&spid='
	),

	//���۲���
	'����������' => array( //������
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=borrowExa&urlType=1&spid='
	), //���۲���
	'Ա��������' => array( //Ա��������
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
        'rtUrl' => '?model=projectmanagent_borrow_borrow&action=borrowExa&urlType=1&spid='
	),
	'Ա����������' => array( //Ա������
		'url' => '?model=projectmanagent_borrow_renew&action=view&id=',
		'viewUrl' => '?model=projectmanagent_borrow_renew&action=view&id=',
		'rtUrl' => '?model=projectmanagent_borrow_renew&action=updateBorrow&urlType=1&spid='
	),
	'Ա��������ת������' => array( //Ա��������ת��
		'url' => '?model=projectmanagent_borrow_borrow&action=subtenancyView&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=subtenancyView&id=',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=updateSubtenancy&urlType=1&spid='
	),
	'�����ñ������' => array(
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=toViewTab&change=1&id=',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=confirmChangeToApprovalNo&urlType=1&spid='
	),
	'�����ù黹����' => array(
		'url' => '?model=projectmanagent_borrowreturn_borrowreturn&action=toAudit&id=',
		'viewUrl' => '?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id=',
		'rtUrl' => '?model=projectmanagent_borrowreturn_borrowreturn&action=dealAfterAudit&spid='
	),
	'�����ùر�����' => array(
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=toViewTab&change=1&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow'
	),
	'�����÷������ϱ��' => array(
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquChangeView&linkId=',
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquChangeView&linkId=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
		'rtUrl' => '?model=projectmanagent_borrow_borrowequ&action=dealAfterChangeAudit&spid='
	),
	'���ͱ������' => array(
		'url' => '?model=projectmanagent_present_present&action=auditView&id=',
		'viewUrl' => '?model=projectmanagent_present_present&action=auditView&id=',
		'rtUrl' => '?model=projectmanagent_present_present&action=confirmChangeToApprovalNo&urlType=1&spid='
	),
	//�ִ沿��
	'��������' => array( //����ƻ�
		'url' => '?model=stock_fillup_fillup&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=stock_fillup_fillup&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'stock_fillup_fillup',
		'rtUrl' => '?model=stock_fillup_fillup&action=dealAfterAudit&spid='
	),

	//��Ӧ�̲���
	'��Ӧ�����' => array( //��Ӧ��
		'url' => '?model=supplierManage_temporary_temporary&action=init&perm=view&id=',
		'viewUrl' => '?model=supplierManage_temporary_temporary&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_temporary_temporary'

	),

	//�̶���������
	'�ʲ�������������' => array( //�ʲ���������
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement',
		'rtUrl' => '?model=asset_require_requirement&action=dealAfterAudit&spid='
	),
	//
	'�ʲ���������(�����)' => array( //�ʲ���������(�����)
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement',
		'rtUrl' => '?model=asset_require_requirement&action=dealAfterAudit&spid='
	),
	//�̶���������
	'�ʲ�����������' => array( //�ʲ�������
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement'
	),
	//�̶��ʲ��ճ�����
	'�ʲ���������' => array( //�ʲ�����
		'url' => '?model=asset_daily_borrow&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_borrow&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_borrow',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_borrow&spid='
	),
	'�ʲ���������' => array( //�ʲ�����
		'url' => '?model=asset_daily_allocation&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_allocation&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_allocation',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_allocation&spid='
	),

	'�ʲ���������' => array( //�ʲ�����
		'url' => '?model=asset_daily_rent&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_rent&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_rent'
	),


	//�̶��ʲ��Ĳɹ�����
	'�̶��ʲ��ɹ�����' => array( //�ɹ�����
		'url' => '?model=asset_purchase_apply_apply&action=init&perm=viewaudit&viewBtn=1&id=',
		'viewUrl' => '?model=asset_purchase_apply_apply&action=init&perm=viewaudit&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_purchase_apply_apply',
		'rtUrl' => '?model=asset_purchase_apply_apply&action=auditSendEmail&urlType=1&spid='
	),

	//�̶��ʲ��ı�������
	'�ʲ�������������' => array( //��������
		'url' => '?model=asset_disposal_scrap&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_disposal_scrap&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_disposal_scrap',
		'rtUrl' => '?model=asset_disposal_scrap&action=dealAfterAudit&spid='
	),

	//�̶��ʲ��ĳ�������
	'�ʲ�������������' => array( //��������
		'url' => '?model=asset_disposal_sell&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_disposal_sell&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_disposal_sell',
		'rtUrl' => '?model=asset_disposal_sell&action=dealAfterAudit&spid='
	),

	//�̶��ʲ���ά������
	'�ʲ�ά����������' => array( //ά������
		'url' => '?model=asset_daily_keep&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_keep&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_keep',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_keep&spid='
	),
	//�̶��ʲ�����ʧ����
	'�ʲ���ʧ��������' => array( //��ʧ����
		'url' => '?model=asset_daily_lose&action=audit&id=',
		'viewUrl' => '?model=asset_daily_lose&action=audit&id=',
		'allStep' => '1',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_lose',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_lose&spid='
	),
	//�̶��ʲ�������
	'�̶��ʲ�����' => array( //�ʲ�����
		'url' => '?model=asset_purchase_receive_receive&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_purchase_receive_receive&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_purchase_receive_receive'
	),
	//�̶��ʲ�������
	'�ʲ�������������' => array( //�ʲ�����
		'url' => '?model=asset_daily_charge&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_charge&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_charge',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_charge&spid='
	),
	//�̶��ʲ��Ĺ黹
	'�ʲ��黹��������' => array( //�ʲ��黹
		'url' => '?model=asset_daily_return&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_return&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_return',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_return&spid='
	),
	//�̶��ʲ��������¼
	'�ʲ�������������' => array(
		'url' => '?model=asset_assetcard_clean&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_assetcard_clean&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_assetcard_clean',
		'rtUrl' => '?model=asset_assetcard_clean&action=dealAfterAudit&spid='
	),
	//����ת�ʲ���������
	'����ת�ʲ���������' => array(
		'url' => '?model=asset_require_requirein&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=asset_require_requirein&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirein',
		'rtUrl' => '?model=asset_require_requirein&action=dealAfterAudit&spid='
	),
	//�ʲ�ת������������
	'�ʲ�ת������������' => array(
		'url' => '?model=asset_require_requireout&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=asset_require_requireout&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requireout',
		'rtUrl' => '?model=asset_require_requireout&action=dealAfterAudit&spid='
	),
	'������Ŀ����' => array(
		'url' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
		'viewUrl' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_project_esmproject',
		'rtUrl' => '?model=engineering_project_esmproject&action=dealAfterAudit&spid='
	),
    '������Ŀ�깤����' => array(
        'url' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
        'viewUrl' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
        'isSkey' => '1',
        'keyObj' => 'engineering_project_esmproject',
        'rtUrl' => '?model=engineering_project_esmproject&action=dealAfterCompleteAudit&spid='
    ),
	'������Ŀ���' => array(
		'url' => '?model=engineering_change_esmchange&action=viewAudit&id=',
		'viewUrl' => '?model=engineering_change_esmchange&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_change_esmchange',
		'rtUrl' => '?model=engineering_change_esmchange&action=dealAfterAudit&spid='
	),
	'������Ŀ�ر�����' => array(
		'url' => '?model=engineering_close_esmclose&action=toAudit&id=',
		'viewUrl' => '?model=engineering_close_esmclose&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_close_esmclose',
		'rtUrl' => '?model=engineering_close_esmclose&action=dealAfterAudit&spid='
	),
	//������Ŀ�ܱ�����
	'������Ŀ�ܱ�����' => array(
		'url' => '?model=engineering_project_statusreport&action=toAudit&id=',
		'viewUrl' => '?model=engineering_project_statusreport&action=toView&id=',
		'isSkey' => '1',
		'allStep' => 1,
		'keyObj' => 'engineering_project_statusreport',
		'rtUrl' => '?model=engineering_project_statusreport&action=dealAfterAudit&spid='
	),
	//��Ŀ�豸����
	'��Ŀ�豸����' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//������Ŀ�豸����
	'�豸����(��Ŀ)' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//��Ŀ�豸����
	'�豸����(����)' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//�������������
	'�������������' => array(
		'url' => '?model=service_accessorder_accessorder&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_accessorder_accessorder&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_accessorder_accessorder'
	),
	//���ü������뵥����
	'���ü������뵥����' => array(
		'url' => '?model=service_reduce_reduceapply&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_reduce_reduceapply&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_reduce_reduceapply',
		'rtUrl' => '?model=service_reduce_reduceapply&action=dealAfterAudit&spid='
	),
	//�豸�������뵥����
	'�豸�������뵥����' => array(
		'url' => '?model=service_change_changeapply&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_change_changeapply&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_change_changeapply'
	),
	//ά�ޱ����걨������
	'ά�ޱ�������' => array(
		'url' => '?model=service_repair_repairquote&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_repair_repairquote&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_repair_repairquote'
	),
	'�����ͬ����' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewAccraditation&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAudit&spid='
	),
	'�����ͬ�������' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=changeTab&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=changeTab&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAuditChange&spid='
	),
	'�����ͬ���������' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewAlong&showBtn=0&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=viewAlong&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAuditPayapply&spid='
	),
	'������ͬ����' => array(
		'url' => '?model=contract_other_other&action=viewAlong&act=auditView&viewOn=audit&showBtn=0&id=',
		'viewUrl' => '?model=contract_other_other&action=viewAlong&viewOn=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAudit&spid='
	),
	'������ͬ�������' => array(
		'url' => '?model=contract_other_other&action=changeTab&id=',
		'viewUrl' => '?model=contract_other_other&action=changeTab&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAuditChange&spid='
	),
	'������ͬ���������' => array(
		'url' => '?model=contract_other_other&action=viewAlong&act=auditView&viewOn=audit&hideBtn=1&id=',
		'viewUrl' => '?model=contract_other_other&action=viewAlong&viewOn=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAuditPayapply&spid='
	),
	//��Ӧ�̹���
	'������������' => array(
		'url' => '?model=supplierManage_scheme_scheme&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=supplierManage_scheme_scheme&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_scheme_scheme',
		'rtUrl' => '?model=supplierManage_scheme_scheme&action=auditSendEmail&urlType=1&spid='
	),
	'�¹�Ӧ����������' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'��Ӧ�̼��ȿ�������' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'��Ӧ����ȿ�������' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'������Ŀ����' => array(
		'url' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_trialproject_trialproject&action=confirmExa&spid='
	),
	'������Ŀ��������' => array(
		'url' => '?model=projectmanagent_trialproject_extension&action=viewTab&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'���۱�������' => array(
		'url' => '?model=projectmanagent_stockup_stockup&action=toView&viewType=aduit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_stockup_stockup&action=toView&id=', //�б�ҳ��鿴·��
		//		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'�������⳥��ȷ��' => array(
		'url' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=moneyView&viewType=aduit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=moneyView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=confirmExa&spid=',
		'allStep' => '1'
	),
	'�ʼ챨������' => array(
		'url' => '?model=produce_quality_qualityereport&action=toAudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=produce_quality_qualityereport&action=toView&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=produce_quality_qualityereport&action=dealAfterAudit&spid='
	),
    '���ϱ����ʼ�����' => array(
        'url' => '?model=produce_quality_qualityereport&action=toAudit&id=', //����ʱҳ��鿴·��
        'viewUrl' => '?model=produce_quality_qualityereport&action=toView&id=', //�б�ҳ��鿴·��
        'rtUrl' => '?model=produce_quality_qualityereport&action=dealAfterAudit&spid='
    ),
	'��������' => array(
		'url' => '?model=info_notice&action=detailaudit&id=', //����ʱҳ��鿴·��
		'viewUrl' => '?model=info_notice&action=detailaudit&id=', //�б�ҳ��鿴·��
		'rtUrl' => '?model=info_notice&action=applered&spid='
	),
	'������������' => array(
		'url' => '?model=reqm_apply_list&action=toAudit&keyId=',
		'viewUrl' => '?model=reqm_apply_list&action=toAudit&keyId='
	),
	'������������' => array(
		'url' => '?model=cost_stat_import&action=detail&id=',
		'viewUrl' => '?model=cost_stat_import&action=detail&id='
		//'rtUrl' => '?model=cost_stat_import&action=billDocument&spid='
	),

	/********************************* ԭOAϵͳ�е����������벿�� ********************************/

	'��Ƹ����' => array(
		'url' => 'general/doc_manage/hrms_recruit/info.php?billId=', //��������ʱ�鿴·��
		'viewUrl' => 'general/doc_manage/hrms_recruit/info.php?billId=' //�����б�鿴·��
	),
	'�������ڵ���' => array(
		'url' => 'general/doc_manage/hrms_remove/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hrms_remove/info.php?billId='
	),
	'���ݼ�' => array(
		'url' => 'general/doc_manage/hols/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hols/info.php?billId='
	),
	'���ݼ�A' => array(
		'url' => 'general/doc_manage/hols/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hols/info.php?billId='
	),
	'��������' => array(
		'url' => 'general/news/show/show_news.php?NEWS_ID=',
		'viewUrl' => 'general/news/show/show_news.php?NEWS_ID=',
		'rtUrl' => 'general/news/manage/sendmail.php?NEWS_ID&spid='
	),
    '��' => array(
//        'url' => 'general/costmanage/loan/loan_detail.php?ID=',
//        'viewUrl' => 'general/costmanage/loan/loan_detail.php?ID='
        'url' => '?model=loan_loan_loan&action=toAudit&id=',
        'viewUrl' => '?model=loan_loan_loan&action=toView&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAudit&spid='
    ),
    '�������' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
	'������Ŀ��' => array(
//        'url' => 'general/costmanage/loan/loan_detail.php?ID=',
//        'viewUrl' => 'general/costmanage/loan/loan_detail.php?ID='
        'url' => '?model=loan_loan_loan&action=toAudit&id=',
        'viewUrl' => '?model=loan_loan_loan&action=toView&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAudit&spid='
    ),
    '������Ŀ�������' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
    '�����ű������' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
	//	'������' => array(
	//		'url' => 'general/costmanage/reim/summary_detail.php?doAct=����&BillNo=',
	//		'viewUrl' => 'general/costmanage/reim/summary_detail.php?doAct=����&BillNo='
	//	),
	'��������' => array(
		'url' => '?model=finance_expense_exsummary&action=toAudit&id=',
		'viewUrl' => '?model=finance_expense_exsummary&action=toView&id=',
		'allStep' => 1,
		'rtUrl' => '?model=finance_expense_exsummary&action=dealAfterAudit&spid='
	),
	'���ڱ���������' => array(
		'url' => '?model=finance_expense_exsummary&action=toAudit&id=',
		'viewUrl' => '?model=finance_expense_exsummary&action=toView&id=',
		'allStep' => 1,
		'rtUrl' => '?model=finance_expense_exsummary&action=dealAfterAudit&spid='
	),
	'�ر���������' => array(
		'url' => '?model=general_special_specialapply&action=toAudit&id=',
		'viewUrl' => '?model=general_special_specialapply&action=toView&id=',
		'rtUrl' => '?model=general_special_specialapply&action=dealAfterAudit&spid='
	),
	/********************************* ԭOAϵͳ�е����������벿�� ********************************/
	/**���¹�����**/
	'ְλ˵����' => array(
		'viewUrl' => '?model=hr_position_positiondescript&action=toView&id=',
		'url' => '?model=hr_position_positiondescript&action=toView&id=',
		'isSkey' => '1'
	),
	'��Ա��������' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'��Ա��������(�ƻ���)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'��Ա�����޸�����' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toAuditEditView&id=',
		'url' => '?model=hr_recruitment_apply&action=toAuditEditView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealEditApply&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'�ڲ��Ƽ�����' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),

	'�ڲ��Ƽ�����(������)' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),
	'�ڲ��Ƽ�����(�Ƿ�����)' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),


	'������������' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'������������(�����λ)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'������������(������)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'������������(���ػ�Ա��)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'������������(�Ǳ��ػ�Ա��)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'��ְ��������' => array(
		'viewUrl' => '?model=hr_leave_leave&action=toView&id=',
		'url' => '?model=hr_leave_leave&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_leave_leave&action=leaveMail&urlType=1&spid=',
		'allStep' => '1'
	),
	'��ְ�����嵥ȷ��' => array(
		'viewUrl' => '?model=hr_leave_handover&action=toHandoverList&id=',
		'url' => '?model=hr_leave_handover&action=toHandoverList&actType=audit&id=',
		'rtUrl' => '?model=hr_leave_handover&action=dealApproval&urlType=1&spid=',
		'allStep' => '1'
	),
	'������������' => array(
		'viewUrl' => '?model=hr_transfer_transfer&action=toView&id=',
		'url' => '?model=hr_transfer_transfer&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_transfer_transfer&action=dealTransfer&urlType=1&spid='
	),
	'���ÿ�������' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'���ÿ�������(�߹�)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'���ÿ�������(��������)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'���ÿ�������(�ܼ�)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'��ְ�ʸ���������' => array(
		'viewUrl' => '?model=hr_personnel_certifyapply&action=toViewApply&id=',
		'url' => '?model=hr_personnel_certifyapply&action=toViewApply&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_personnel_certifyapply',
		'rtUrl' => '?model=hr_personnel_certifyapply&action=dealAfterAudit&spid='

	),
	'��ְ�ʸ���֤����' => array(
		'viewUrl' => '?model=hr_certifyapply_cassess&action=toView&id=',
		'url' => '?model=hr_certifyapply_cassess&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_certifyapply_cassess',
		'rtUrl' => '?model=hr_certifyapply_cassess&action=dealAfterAudit&spid='
	),
	'Ա�������ƻ���' => array(
		'viewUrl' => '?model=hr_tutor_coachplan&action=toSimpleRead&id=',
		'url' => '?model=hr_tutor_coachplan&action=toSimpleRead&actType=audit&id=',
		'rtUrl' => '?model=hr_tutor_coachplan&action=confirmExa&spid='
	),
	'��ʦ��������' => array(
		'viewUrl' => '?model=hr_tutor_reward&action=toRead&id=',
		'url' => '?model=hr_tutor_reward&action=toRead&actType=audit&id=',
		'rtUrl' => '?model=hr_tutor_reward&action=confirmExa&spid='
	),
	'��ְ�ʸ�������' => array(
		'viewUrl' => '?model=hr_certifyapply_certifyresult&action=toView&id=',
		'url' => '?model=hr_certifyapply_certifyresult&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_certifyapply_certifyresult',
		'rtUrl' => '?model=hr_certifyapply_certifyresult&action=dealAfterAudit&spid='
	),
	'���Ž�������' => array(
		'viewUrl' => '?model=hr_trialplan_trialdeptsuggest&action=toView&id=',
		'url' => '?model=hr_trialplan_trialdeptsuggest&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_trialplan_trialdeptsuggest'
		//		,
		//		'rtUrl' => '?model=hr_trialplan_trialdeptsuggest&action=dealAfterAudit&spid=',
		//		'allStep' => '1'
	),
	'���Ž�������(���)' => array(
		'viewUrl' => '?model=hr_trialplan_trialdeptsuggest&action=toView&id=',
		'url' => '?model=hr_trialplan_trialdeptsuggest&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_trialplan_trialdeptsuggest'
		//		,
		//		'rtUrl' => '?model=hr_trialplan_trialdeptsuggest&action=dealAfterAudit&spid=',
		//		'allStep' => '1'
	),
	'��Ƹ�ƻ�����' => array(
		'viewUrl' => '?model=hr_recruitplan_plan&action=toView&id=',
		'url' => '?model=hr_recruitplan_plan&action=toView&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_recruitplan_plan'
	),
	'��Ƹ�ƻ��޸�����' => array(
		'viewUrl' => '?model=hr_recruitplan_plan&action=toView&id=',
		'url' => '?model=hr_recruitplan_plan&action=toView&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_recruitplan_plan'
	)
,
	'�ƻ�����Ա����(������)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'�ƻ�����Ա����(������)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'��Ա����ʵϰ��(������)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'��Ա����ʵϰ��(�Ƿ�����)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'��ְ/���ڲ�����Ա����(������)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'�ƻ�����Ա����(�Ƿ�����)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'�ƻ�����Ա����(�Ƿ�����)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'��ְ/���ڲ�����Ա����(�Ƿ�����)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'��Ա����(����������)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'�ڼ��ռӰ���������' => array(
		'viewUrl' => '?model=hr_worktime_apply&action=toView&id=',
		'url' => '?model=hr_worktime_apply&action=toView&actType=audit&id='
	),

	'��Ʊ��������' => array(
		'viewUrl' => '?model=flights_require_require&action=toView&id=',
		'url' => '?model=flights_require_require&action=toView&actType=audit&id=',
		//		'rtUrl' => '?model=flights_require_require&action=dealAfterAudit&spid='
	),
	'��Ŀ�����������' => array(
		'viewUrl' => '?model=outsourcing_outsourcing_apply&action=toView&id=',
		'url' => '?model=outsourcing_outsourcing_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_outsourcing_apply&action=dealAfterAudit&spid='
	),
	'�����������' => array(
		'viewUrl' => '?model=outsourcing_approval_basic&action=toView&id=',
		'url' => '?model=outsourcing_approval_basic&action=toView&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_approval_basic&action=dealAfterAuditPass&spid='
	),
	'�������������' => array(
		'viewUrl' => '?model=outsourcing_approval_basic&action=changeTab&id=',
		'url' => '?model=outsourcing_approval_basic&action=changeTab&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_approval_basic&action=dealAfterAuditChange&spid='
	),
	'�����������' => array(
		'viewUrl' => '?model=outsourcing_account_basic&action=toView&id=',
		'url' => '?model=outsourcing_account_basic&action=toView&actType=audit&id='
	),
	'��Ŀ����ۿ�����' => array(
		'viewUrl' => '?model=outsourcing_deduct_deduct&action=toView&id=',
		'url' => '?model=outsourcing_deduct_deduct&action=toView&actType=audit&id='
	),
	'�����Ӧ������' => array(
		'viewUrl' => '?model=outsourcing_supplier_basicinfo&action=toTabView&id=',
		'url' => '?model=outsourcing_supplier_basicinfo&action=toTabView&actType=audit&id='
	),
	'�����Ӧ�̱������' => array(
		'viewUrl' => '?model=outsourcing_supplier_basicinfo&action=toTabChangeView&id=',
		'url' => '?model=outsourcing_supplier_basicinfo&action=toTabChangeView&actType=audi&id=',
		'rtUrl' => '?model=outsourcing_supplier_basicinfo&action=dealChange&actType=audit&spid='
	),
	'��Ʒ����������������' => array(
		'viewUrl' => '?model=stockup_apply_apply&action=toView&id=',
		'url' => '?model=stockup_apply_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=stockup_apply_apply&action=appAfterMail&spid='
	),
	'��Ʒ����������������' => array(
		'viewUrl' => '?model=stockup_application_application&action=toView&id=',
		'url' => '?model=stockup_application_application&action=toView&actType=audit&id='
	),
	'���������������' => array(
		'viewUrl' => '?model=finance_payablesapply_payablesapplychange&action=toAuditView&id=',
		'url' => '?model=finance_payablesapply_payablesapplychange&action=toAuditView&id=',
		'rtUrl' => '?model=finance_payablesapply_payablesapplychange&action=dealAfterAudit&spid='
	),
	'����������������' => array(
		'viewUrl' => '?model=produce_plan_picking&action=toView&id=',
		'url' => '?model=produce_plan_picking&action=toView&actType=audit&id=',
		'rtUrl' => '?model=produce_plan_picking&action=dealAfterAudit&spid='
	),
	'������������' => array(
		'viewUrl' => '?model=produce_apply_produceapply&action=toView&id=',
		'url' => '?model=produce_apply_produceapply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=produce_apply_produceapply&action=dealAfterAudit&spid='
	),
	'��������������' => array(
		'url' => '?model=produce_apply_produceapply&action=toChangeTab&actType=actType&id=',//����ʱҳ��鿴·��
		'viewUrl' => '?model=produce_apply_produceapply&action=toChangeTab&id=',//�б�ҳ��鿴·��
		'rtUrl' => '?model=produce_apply_produceapply&action=dealAfterAuditChange&spid='
	),
	'�����ƻ��ر�����' => array(
		'viewUrl' => '?model=produce_plan_produceplan&action=toCloseView&id=',//�б�ҳ��鿴·��
		'url' => '?model=produce_plan_produceplan&action=toCloseView&actType=audit&id='
	),
    '���ϱ�����������' => array(
        'viewUrl' => '?model=stock_outstock_stockout&action=toView&docType=CKDLBF&id=',//���ϴ��ϳ��ⵥҳ��鿴·��
        'url' => '?model=stock_outstock_stockout&action=toView&docType=CKDLBF&actType=audit&id=',
        'rtUrl' => '?model=stock_outstock_stockout&action=dealAfterAudit&docType=DLBF&spid='
    )
);

/**
 * ���ڱ��ҵ������ж�ʱʹ�������ͷ�����
 */
$changeFunArr = array(
	'�ɹ���ͬ����' => array( //�ɹ�����
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'isTemp',
		'taskName' => '�ɹ������������',
		'rtUrl' => '?model=purchase_change_contractchange&action=confirmChangeToApprovalNo&urlType=1&spid=',
		'seachCode' => 'purchasecontractNotChange'
	)
);


/**
 * ����Ҫ���������������
 */
$notInWorkflow = array(
	'��������'
);


/**
 * �յ�����ʱ���÷���
 */
$receiveActionArr = array(
	'��������' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'receiveForm'
	),
	'���ڱ���������' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'receiveForm'
	),
    '��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'receiveForm'
    ),
	'������Ŀ��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'receiveForm'
    )
);

/**
 * �˵�����ʱ���÷���
 */
$backActionArr = array(
	'��������' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'backForm'
	),
	'���ڱ���������' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'backForm'
	),
    '��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'backForm'
    )
	,
    '������Ŀ��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'backForm'
    )
);


/**
 * TODO����������ҵ��ע�� -- ע�⣬���õ���Model�㷽��
 * className - ҵ���������
 * funName - ҵ����÷�����
 */
$batchAuditArr = array(
	'�����ͬ����' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAudit_d'
	),
	'�����ͬ�������' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAuditChange_d'
	),
	'�����ͬ���������' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAuditPayapply_d'
	),
	'�����ͬ�ر�����' => array(),
	'������ͬ����' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAudit_d'
	),
	'������ͬ�������' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAuditChange_d'
	),
	'������ͬ���������' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAuditPayapply_d'
	),
	'������ͬ�ر�����' => array(),
	'��������' => array(
		'className' => 'model_finance_expense_exsummary',
		'funName' => 'dealAfterAudit_d'
	),
	'���ڱ���������' => array(
		'className' => 'model_finance_expense_exsummary',
		'funName' => 'dealAfterAudit_d'
	),
	'��Ʊ����' => array(
		'className' => 'model_finance_invoiceapply_invoiceapply',
		'funName' => 'dealAfterAudit_d'
	),
	'��ؿ�Ʊ����' => array(),
	'�ɹ���������' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'�ɹ��˿�����' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'��������' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'������Ŀ����' => array(
		'className' => 'model_engineering_project_esmproject',
		'funName' => 'dealAfterAudit_d'
	),
    '������Ŀ�깤����' => array(
        'className' => 'model_engineering_project_esmproject',
        'funName' => 'dealAfterCompleteAudit_d'
    ),
	'������Ŀ���' => array(
		'className' => 'model_engineering_change_esmchange',
		'funName' => 'dealAfterAudit_d'
	),
	'������Ŀ�ر�����' => array(
		'className' => 'model_engineering_close_esmclose',
		'funName' => 'dealAfterAudit_d'
	),
	'������Ŀ�ܱ�����' => array(
		'className' => 'model_engineering_project_statusreport',
		'funName' => 'dealAfterAudit_d'
	),
	'�ʼ챨������' => array(
		'className' => 'model_produce_quality_qualityereport',
		'funName' => 'workflowCallBack'
	),
    '���ϱ����ʼ�����' => array(
        'className' => 'model_produce_quality_qualityereport',
        'funName' => 'workflowCallBack'
    ),
	'�⳥������' => array(
		'className' => 'model_finance_compensate_compensate',
		'funName' => 'workflowCallBack'
	),
	'�ر���������' => array(
		'className' => 'model_general_special_specialapply',
		'funName' => 'dealAfterAudit_d'
	),
	'��Ŀ�豸����' => array(),
	'�����ù黹����' => array(
		'className' => 'model_projectmanagent_borrowreturn_borrowreturn',
		'funName' => 'workflowCallBack'
	),
	'��ְ��������' => array(
		'className' => 'model_hr_leave_leave',
		'funName' => 'dealAfterAudit_d'
	),
	'�����������' => array(
		'className' => 'model_outsourcing_approval_basic',
		'funName' => 'workflowCallBack'
	),
	'������Ŀ����' => array(
		'className' => 'model_projectmanagent_trialproject_trialproject',
		'funName' => 'workflowCallBack'
	),
	'������Ŀ��������' => array(
		'className' => 'model_projectmanagent_trialproject_extension',
		'funName' => 'workflowCallBack'
	),
	'��ͬ����A' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'��ͬ�������A' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'��ͬ����B' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'��ͬ�������B' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'��ͬ����C' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'��ͬ�������C' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'������������' => array(
		'className' => 'model_projectmanagent_exchange_exchange',
		'funName' => 'workflowCallBack'
	),
	'�����˻�����' => array(
		'className' => 'model_projectmanagent_return_return',
		'funName' => 'workflowCallBack'
	),
	'������������' => array(
		'className' => 'model_contract_stamp_stampapply',
		'funName' => 'workflowCallBack'
	),
	'��ͬ������������' => array(
		'className' => 'model_contract_stamp_stampapply',
		'funName' => 'workflowCallBack'
	),
	'�ۿ���������' => array(
		'className' => 'model_contract_deduct_deduct',
		'funName' => 'workflowCallBack'
	),
	'��������' => array(
		'className' => 'model_stock_fillup_fillup',
		'funName' => 'workflowCallBack'
	),
	'�����ɹ���������' => array(
		'className' => 'model_purchase_plan_basic',
		'funName' => 'workflowCallBack'
	),
	'�ɹ���������' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack'
	),
	'�ɹ�������ֹ����' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack_close'
	),
	'���ü������뵥����' => array(
		'className' => 'model_service_reduce_reduceapply',
		'funName' => 'workflowCallBack'
	),
	'��Ӧ�̼��ȿ�������' => array(
		'className' => 'model_supplierManage_assessment_supasses',
		'funName' => 'workflowCallBack'
	),
	'�¹�Ӧ������' => array(
		'className' => 'model_supplierManage_assessment_supasses',
		'funName' => 'workflowCallBack'
	),
	'�⳵��������' => array(
		'className' => 'model_outsourcing_vehicle_rentalcar',
		'funName' => 'workflowCallBack'
	),
	'�⳵��ͬ����' => array(
		'className' => 'model_outsourcing_contract_rentcar',
		'funName' => 'workflowCallBack'
	),
	'�⳵��ͬ�������' => array(
		'className' => 'model_outsourcing_contract_rentcar',
		'funName' => 'workflowCallBack_change'
	),
	'�⳵�Ǽ�����' => array(
		'className' => 'model_outsourcing_vehicle_allregister',
		'funName' => 'workflowCallBack'
	),
    '���ϱ�����������' => array(
        'className' => 'model_stock_outstock_stockout',
        'funName' => 'workflowCallBack_idleScrap'
    ),
	'�����ñ������' => array(
		'className' => 'model_projectmanagent_borrow_borrow',
		'funName' => 'workflowCallBack_change'
	),
    '�豸����' => array(
        'className' => 'model_projectmanagent_present_present',
        'funName' => 'workflowCallBack_equConfirm'
    ),
	'���ͱ������' => array(
		'className' => 'model_projectmanagent_present_present',
		'funName' => 'workflowCallBack'
	),
	'�ɹ���������(����������)' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack'
	),
	'��Ŀ�����������' => array(
		'className' => 'model_outsourcing_outsourcing_apply',
		'funName' => 'dealAfterAudit_d'
	),
    '��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAudit_d'
    ),
    '�������' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAuditChange_d'
    ),
	'������Ŀ��' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAudit_d'
    ),
    '������Ŀ�������' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAuditChange_d'
    ),
    '�����÷������ϱ��' => array(
        'className' => 'model_projectmanagent_borrow_borrowequ',
        'funName' => 'dealAfterChangeAudit_d'
    )
);