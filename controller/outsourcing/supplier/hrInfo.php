<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:49:55
 * @version 1.0
 * @description:��Ӧ��������Դ��Ϣ���Ʋ�
 */
class controller_outsourcing_supplier_hrInfo extends controller_base_action {

	function __construct() {
		$this->objName = "hrInfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ӧ��������Դ��Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������Ӧ��������Դ��Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ӧ��������Դ��Ϣҳ��
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
	 * ��ת���鿴��Ӧ��������Դ��Ϣҳ��
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
	function c_listJsonView() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if(is_array($rows)){
			//���غϼ�
			$service->sort = "";
			$service->searchArr = array('suppId' => $_POST['suppId']);
			$objArr = $service->listBySqlId('select_sum');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['skillArea'] = '����';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>