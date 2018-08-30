<?php
/**
 * @author Administrator
 * @Date 2012��1��11�� 16:58:43
 * @version 1.0
 * @description:��Ӧ��������ϸ���Ʋ�
 */
class controller_supplierManage_assessment_assesmentitem extends controller_base_action {

	function __construct() {
		$this->objName = "assesmentitem";
		$this->objPath = "supplierManage_assessment";
		parent::__construct ();
	 }

	/*
	 * ��ת����Ӧ��������ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������Ӧ��������ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ӧ��������ϸҳ��
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
	 * ��ת���鿴��Ӧ��������ϸҳ��
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
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ("select_schemeItem");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * ��ȡ�������ݷ���json(��������)
     */
    function c_assesListJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->searchArr ['assesManId'] = $_SESSION ['USER_ID'];
        $service->sort='id';
        $service->asc=false;
        $rows = $service->list_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }
 }
?>