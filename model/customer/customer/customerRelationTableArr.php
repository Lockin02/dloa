<?php
/**
 * �ͻ�����ҵ�������
 * key Ϊҵ�����
 * value Ϊ�������ֶι�ϵ
 */
$customerRelationTableArr=array(
	"sale"=>
	array(
		// ��������
		"oa_sale_clues"=>array("��������","id"=>"customerId","Name"=>"customerName"),
		//��ϵ��
		"oa_customer_linkman"=>array("��ϵ��","id"=>"customerId"),
		//�̻�
		"oa_sale_chance"=>array("�̻�","id"=>"customerId","Name"=>"customerName"),
		//������
		"oa_borrow_borrow"=>array("������","id"=>"customerId","Name"=>"customerName"),
		//����
		"oa_present_present"=>array("����","id"=>"customerNameId","Name"=>"customerName"),
		//���ۺ�ͬ
		"oa_contract_contract"=>array("��ͬ","id"=>"customerId","Name"=>"customerName")
		//�����ͬ
//		"oa_sale_service"=>array("�����ͬ","id"=>"cusNameId","Name"=>"cusName"),
//		//���޺�ͬ
//		"oa_sale_lease"=>array("���޺�ͬ","id"=>"tenantId","Name"=>"tenant"),
//		//�з���ͬ
//		"oa_sale_rdproject"=>array("�з���ͬ","id"=>"cusNameId","Name"=>"cusName")
	),
	"delivery"=>
	array(
		//�ʼ���Ϣ
		"oa_mail_info"=>array("�ʼ���Ϣ","id"=>"customerId","Name"=>"customerName"),
		//�����ƻ�
		"oa_stock_outplan"=>array("�����ƻ�","id"=>"customerId","Name"=>"customerName"),
		//������
		"oa_stock_ship"=>array("������","id"=>"customerId","Name"=>"customerName")
	),
//	"production"=>
//	array(
//		//��������
//		"oa_produce_protask"=>array("��������","id"=>"customerId","Name"=>"customerName")
//	),
	"finance"=>
	array(
		//��Ʊ��¼
		"oa_finance_invoice"=> array("��Ʊ��¼","id"=>'invoiceUnitId',"Name"=>'invoiceUnitName'),
		//��Ʊ����
		"oa_finance_invoiceapply" => array("��Ʊ����","id"=>'customerId',"Name"=>'customerName'),
		//�����¼
		"oa_finance_income" => array("�����¼","id"=>'incomeUnitId',"Name"=>'incomeUnitName')
	),
	"stock"=>
	array(
		//���
		"oa_stock_instock"=>array("��ⵥ","id"=>'clientId',"Name"=>'clientName'),
		//����
		"oa_stock_outstock"=>array("���ⵥ","id"=>'customerId',"Name"=>'customerName'),
		//����
		"oa_stock_allocation"=>array("������","id"=>'customerId',"Name"=>'customerName')
	)
);
?>