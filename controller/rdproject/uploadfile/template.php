<?php
/**
 * ��������ģ����Ʋ���
 * @author chengl
 *
 */
class controller_rdproject_uploadfile_template extends controller_base_action {
	function __construct() {
		$this->objName = "template";
		$this->objPath = "rdproject_uploadfile";
		parent::__construct ();
	}

	/**
	 * ������
	 */
	function c_tree() {
		$searchArr = array ("parentId" => $_GET ['parentId'], "projectType" => $_POST ['projectType'] );
		$service = $this->service;
		$service->asc = false;
		$service->searchArr = $searchArr;
		$rows = $service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��������
	 */
	function c_saveType() {
		$type = $_POST ['type'];
		$type ['name'] = util_jsonUtil::iconvUTF2GB ( $type ['name'] );
		$id = $this->service->saveType_d ( $type );
		echo $id;
	}

	function c_tolist() {
		$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->view ( "list" );
	}
}
?>
