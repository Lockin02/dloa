<?php
/*
 * Created on 2011-1-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class controller_purchase_contract_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	}
 	/**
	 * �ⲿ�ӿ�
	 * ͨ����Ʒ�嵥Id����δ�´�����
	 */
	function c_getAppProNotIssNum(){
		$purchAppProId = isset( $_GET['purchAppProId'] )?$_GET['purchAppProId']:exit;
		echo $this->service->getAppProNotIssNum_d($purchAppProId);
	}

	/**
	 * ��ִ�еĶ�������pagejson
	 */
	function c_managPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=$service->dealExeRows_d($rows);
		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

		function c_toEditDate() {
			$service = $this->service;
			$rows = $service->get_d ( $_GET ['id'] );
			foreach ( $rows as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->display ( 'edit' );
		}
 }
?>
