<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 16:45:47
 * @version 1.0
 * @description:���鱨���嵥���Ʋ�
 */
class controller_produce_quality_qualityereportitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereportitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ת�����鱨���嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������鱨���嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���鱨���嵥ҳ��
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
	 * ��ת���鿴���鱨���嵥ҳ��
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
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->asc = false;
        $rows = $service->list_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
	 *
	 * ��ȡ���鱨���嵥subgrid����
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
	 *
	 * ��ȡ���鱨���嵥editgrid����
	 */
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}
}