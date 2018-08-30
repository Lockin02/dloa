<?php
/**
 * @author Michael
 * @Date 2014��9��3�� 17:25:41
 * @version 1.0
 * @description:�����������뵥�嵥���Ʋ�
 */
class controller_produce_plan_pickingitem extends controller_base_action {

	function __construct() {
		$this->objName = "pickingitem";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�������������뵥�嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�����������������뵥�嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�����������뵥�嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit');
	}

	/**
	 * ��ת���鿴�����������뵥�嵥ҳ��
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
	 * ��ȡ�����ƻ�������������������ת��Json
	 */
	function c_pageJsonProduct() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d('select_product');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���鿴�����ƻ���������������ҳ��
	 */
	function c_toViewProduct() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign('applyNum' ,$_GET['applyNum'] > 0 ? $_GET['applyNum'] : '*');
		$this->view('view-product');
	}

	/**
	 * ��дlistJson
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				$numArr = $pickDao->getProductNum_d($val['productCode']);
				$rows[$key]['JSBC'] = $numArr['JSBC']; //���豸������
				$rows[$key]['KCSP'] = $numArr['KCSP']; //�����Ʒ������
				$rows[$key]['SCC']  = $numArr['SCC'];  //����������
			}
		}
		if($_REQUEST['type'] == 'back'){//������������ʱ����
			$rows = $service->dataProcessAtBack_d ($rows,$_REQUEST['pickingId']);
		}
		if($_REQUEST['type'] == 'edit'){//�༭�������뵥ʱ����
			$rows = $service->dataProcessAtEdit_d ($rows,$_REQUEST['taskId'],$_REQUEST['planId']);
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * ���ϳ���ʱ����ȡʵ�ʿɳ������������
	 */
	function c_getNumAtOutStock(){
		$rs = $this->service->find(array('id' => $_POST['id']),null,'applyNum,realityNum');
		echo $rs['applyNum'] - $rs['realityNum'];
	}
 }