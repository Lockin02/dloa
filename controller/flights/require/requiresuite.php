<?php
/**
 * @author Administrator
 * @Date 2013��7��12�� 9:47:40
 * @version 1.0
 * @description:������Ա����Ʋ�
 */
class controller_flights_require_requiresuite extends controller_base_action {

	function __construct() {
		$this->objName = "requiresuite";
		$this->objPath = "flights_require";
		parent :: __construct();
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$rows = array();
		$service = $this->service;
		//��ȡȨ���ֶ�
		$cardNoLimit = isset($_REQUEST['cardNoLimit']) ? $_REQUEST['cardNoLimit'] : '';

		$service->getParam ( $_REQUEST );//����ǰ̨��ȡ�Ĳ�����Ϣ

		$detailRows = $service->page_d ();
		if($detailRows){
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $detailRows );
		}
		//֤�����봦��
		$rows = $this->cardFilter($rows,$cardNoLimit);

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;

		//��ȡȨ���ֶ�
		$cardNoLimit = isset($_REQUEST['cardNoLimit']) ? $_REQUEST['cardNoLimit'] : '';
		
		$service->getParam($_REQUEST);
		$rows = $service->list_d();

		//֤�����봦��
		$rows = $this->cardFilter($rows,$cardNoLimit);

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);

		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ֤���������
	 */
	function cardFilter($rows,$cardNoLimit){
		foreach($rows as $key => $val){
			//Ȩ�޹���
	        if( $val['cardType'] == 'JPZJLX-01' && ($cardNoLimit == '1'|| $_SESSION['USER_ID'] == $val['airId'])){
	        	$rows[$key]['cardNo'] = $val['cardNoHidden'];
	        }
		}
		return $rows;
	}

	/**
	 * ��ת��������Ա���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������Ա��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭������Ա��ҳ��
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
	 * ��ת���鿴������Ա��ҳ��
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