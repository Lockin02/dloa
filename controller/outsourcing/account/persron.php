<?php
/**
 * @author Administrator
 * @Date 2013��12��15�� ������ 22:23:01
 * @version 1.0
 * @description:���������Ա���޿��Ʋ�
 */
class controller_outsourcing_account_persron extends controller_base_action {

	function __construct() {
		$this->objName = "persron";
		$this->objPath = "outsourcing_account";
		parent::__construct ();
	 }

	/**
	 * ��ת�����������Ա�����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������������Ա����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���������Ա����ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���������Ա����ҳ��
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
	 * ��ȡ�������ݷ���json
	 */
	function c_accountListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$approvalId=$_POST['approvalId'];
		$verifyIds=$_POST['verifyIds'];
		$rows = $service->accountListJson_d ($approvalId,$verifyIds);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

 }
?>