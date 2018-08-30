<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:13:27
 * @version 1.0
 * @description:������ϸ����Ʋ�
 */
class controller_finance_expense_expenseass extends controller_base_action {

	function __construct() {
		$this->objName = "expenseass";
		$this->objPath = "finance_expense";
		parent :: __construct();
	}

	/**
	 * ��ת��������ϸ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ������ϸ�����б�
	 */
	function c_detailList(){
		$this->assign('HeadID',$_GET['HeadID']);
		$this->view('listdetail');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_detailPageJson() {
		$service = $this->service;
;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/******************* ��ɾ��� ***********************/

	/**
	 * ��ת������������ϸ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭������ϸ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴������ϸ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>