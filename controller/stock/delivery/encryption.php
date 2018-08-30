<?php
/**
 * @author Michael
 * @Date 2014��5��29�� 16:42:09
 * @version 1.0
 * @description:������������Ʋ�
 */
class controller_stock_delivery_encryption extends controller_base_action {

	function __construct() {
		$this->objName = "encryption";
		$this->objPath = "stock_delivery";
		parent::__construct ();
	}

	/**
	 * ��ת�������������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������������б�
	 */
	function c_pageMission() {
		$this->view('list-mission');
	}

	/**
	 * ��ת��δ��ɼ����������б�
	 */
	function c_notPage() {
		$this->view('list-not');
	}

	/**
	 * ��ת��δ��ɼ����������б��ɱ༭��
	 */
	function c_notPageMission() {
		$this->view('list-notMission');
	}

	/**
	 * ��ת������ɼ����������б�
	 */
	function c_yesPage() {
		$this->view('list-yes');
	}

	/**
	 * ��ת����������������ҳ��
	 */
	function c_toAdd() {
		$sourceDocId = isset ( $_GET ['sourceDocId'] ) ? $_GET ['sourceDocId'] : null;
		$equIds = isset ( $_GET ['equIds'] ) ? $_GET ['equIds'] : null;

		$contractDao = new model_contract_contract_contract ();
		$obj = $contractDao->getContractInfo ( $sourceDocId, array ("equ" ) );
		$this->assign("sourceDocType" ,'��ͬ'); //Դ������
		$this->assign("sourceDocTypeCode" ,'CONTRACT'); //Դ�����ͱ���
		$this->assign("sourceDocId" ,$obj ['id']); //Դ��id
		$this->assign("sourceDocCode" ,$obj ['objCode']); //Դ�����
		$this->assign("customerName" ,$obj ['customerName']); //�ͻ�����
		$this->assign("customerId" ,$obj ['customerId']); //�ͻ�ID
		$this->assign("headMan" ,$obj ['prinvipalName']); //������
		$this->assign("headManId" ,$obj ['prinvipalId']); //������ID

		//��ȡ���п�ִ��id
		$objEquIds = '';
		$equDao = new model_contract_contract_equ();
		if (is_array($obj['equ'])) {
			foreach ($obj['equ'] as $key => $val) {
				$equObj = $equDao->get_d( $val['id'] );
				if ($equObj['number'] - $equObj['encryptionNum'] > 0) {
					$objEquIds .= $val['id'] . ',';
				}
			}
		}

		//��ѡ�е�id�й��˳���ִ�е�id
		$equIdsStr = '';
		if ($equIds) {
			$equIdsArr = explode(',' ,$equIds);
			if (is_array($equIdsArr)) {
				foreach ($equIdsArr as $key => $val) {
					$equObj = $equDao->get_d( $val );
					if ($equObj['number'] - $equObj['encryptionNum'] > 0) {
						$equIdsStr .= $val . ',';
					}
				}
			}
		}

		$ids = $equIds ? $equIdsStr : $objEquIds;
		if (!$ids) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�п�ִ�м�¼!');window.close();"
				 ."</script>";
			exit();
		}

		$this->assign("issueName" ,$_SESSION['USERNAME']); //�´���
		$this->assign("issueId" ,$_SESSION['USER_ID']); //�´���ID
		$this->assign("issueDate" ,date("Y-m-d")); //�´�����

		$this->assign("equIds" ,$ids);
		$this->view ( 'add' ,true);
	}

	/**
	 * ��дadd
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$obj['state'] = isset ($_GET['issued']) ? 1 : 0;
		$rs = $this->service->add_d( $obj );
		if($rs) {
			if(isset ($_GET['issued'])) {
				msg( '�´�ɹ���' );
			} else {
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���༭����������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit' ,true);
	}

	/**
	 * ��дedit
	 */
	function c_edit() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$obj['state'] = isset ($_GET['issued']) ? 1 : 0;
		$rs = $this->service->edit_d( $obj );
		if($rs) {
			if(isset ($_GET['issued'])) {
				msg( '�´�ɹ���' );
			} else {
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴����������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ajax�Ҽ��´�����
	 */
	function c_assignMission() {
		$rs = $this->service->assignMission_d($_POST['id']);
		if ($rs) {
			$this->service->mailIssued_d( $_POST['id'] ); //�����ʼ�֪ͨ
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ajax�Ҽ���������
	 */
	function c_receiveMission() {
		$rs = $this->service->updateById(array('id'=>$_POST['id'] ,'state'=>2 ,'receiveDate'=>date("Y-m-d")));
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ת����ɼ���������ҳ��
	 */
	function c_toFinish() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('finish' ,true);
	}

	/**
	 * ��ɼ���������
	 */
	function c_finish() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];

		$rs = $this->service->finish_d( $obj );
		if($rs) {
			msg( '����ɹ���' );
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}
}
?>