<?php
/**
 * �䶯��ʽ���Ʋ���
 *  @author chenzb
 */
class controller_asset_basic_change extends controller_base_action {

	function __construct() {
		$this->objName = "change";
		$this->objPath = "asset_basic";

		parent :: __construct();
	}

	/**
		 * ��ת���䶯��ʽ��Ϣ�б�
		 */
	function c_page() {
		$this->view('list');
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {

		$this->view('add');
	}
	/**
		 * ��ʼ������
		 */
	function c_init() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->view('view');
		} else {
			$this->view('edit');
		}
	}

	/**
	 * @ ajax�ж���
	 *
	 */
	function c_ajaxDataCode() {
		$service = $this->service;
		$projectName = isset ($_GET['subcode']) ? $_GET['subcode'] : false;

		$searchArr = array (
			"subcode" => $projectName
		);

		$isRepeat = $service->isRepeat($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * ����ɾ������
	 */
	function c_deletes() {
		//$this->permDelCheck ();
		$message = "";
		try {
			$this->service->deletes_d($_GET['id']);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}


	/**
	 * ��ת���䶯��ʽ����ҳ��
	 * @create 2012��1��30�� 10:08:55
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * �䶯��ʽ����
	 * @create 2012��1��30�� 10:08:59
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'code',
			1 => 'name',
			2 => 'type',
			3 => 'digest'
		); //�ֶ�����
		$resultArr = $this->service->import_d ( $objKeyArr );
	}

}
?>