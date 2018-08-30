<?php
$rule="2,10";//����ȡ�ӵڶ�λ��ʼ������Ϊ10λ(����10λ����)
/**
 * ҵ��ģ��У�����ע������
 */
$securityRule = array (
		/**
		 * �ͻ�ģ�飬һ��ȡ��action�е�objName��ͬ,��id�����������ֶΣ�����������������md5����(ע��������,����)
		 * �ڻ�ȡ��ʱ��ͨ��row['skey_'],row['skey_1']....��ȡ��Ӧ����key
		 */
		"customer" => array ("id,Name" )  ,

		//�ɹ���ʼ
		"purchasecontract"=>array("id"),
		"contractchange"=>array("id"),
		"basic"=>array("id"),
		"inquirysheet"=>array("id"),
		"arrival" => array("id"),
		"delivered" => array("id"),
		//�ɹ�����

		//��ͬ��ʼ
		"order" => array("id"),
		"serviceContract" => array("id"),
		"rentalcontract" => array("id"),
		"rdproject" => array("id"),
		"share" => array("orderId,id"),
		"other" => array("id"),
		"outsourcing" => array("id"),
		//��ͬ����

        //������ʼ
        "clues" => array("id"),
        "track" => array("id"),
        //��������
        //�̻���ʼ
        "chance" => array("id"),
        "borrow" => array("id"),
        //�̻�����
        //�����˻�
        "return" => array("id"),
        //�����˻��ý���
        //����
        "present" => array("id"),

        //����Ӧ���˿�
        'invoice' => array("id,applyId"),
        'invoiceapply' => array("id,applyId"),
        'receviable' => array("objId"),
        'income' => array("id"),
        'payables' => array("id,applyId"),
        'payablesapply' => array("id,applyId"),
        'payable' => array("objId"),
        'invpurchase' => array("id"),
        'baseinfo' => array("hookMainId")//������ϵ��
        //�������

        //�������
		,'tstask' => array("id")
		,'accessorder'=>array("id")
		,'repairapply'=>array("id")
		,'applyitem'=>array("id")
		,'repairquote'=>array("id")
		,'repaircheck'=>array("id")
		,'reduceapply'=>array("id")
		,'reduceitem'=>array("id")
		,'changeapply'=>array("id")
		,'reduceitem'=>array("id")
        //�������


        //������ʼ
        ,'outplan'=>array("id",3 , 16)
        ,'ship'=>array("id",2 , 18)
        ,'lock'=>array("id",4 , 18)
        ,'external'=>array("id",3,17)
        ,'protask'=>array("id",2,17)
        ,'present'=>array("id",3,19)
        //��������


		//�ִ����ʼ
		,"stockin"=>array("id")
		,"stockout"=>array("id")
		,"allocation"=>array("id")
		,"fillup"=>array("id")
		,"checkinfo"=>array("id")
		//�ִ�������

		//��Ӧ�̹���ʼ
		,"temporary"=>array("id")
		,"flibrary"=>array("id")
		,"assessment"=>array("id")
		,"supasses"=>array("id")
		//��Ӧ�̹������

		//������Դ����
		,"personnel"=>array("id")
);
?>