<?php
/**
 * ����������Ʋ���
 * @author chengl
 *
 */
class controller_rdproject_uploadfile_uploadfiletype extends controller_base_action {
	function __construct() {
		$this->objName = "uploadfiletype";
		$this->objPath = "rdproject_uploadfile";
		parent::__construct ();
	}

	/**
	 * ������
	 */
	function c_tree() {
		$projectId = $_GET ['projectId'];
		if (!empty ( $_POST ['projectId'] )) {
			$projectId = $_POST ['projectId'];
		}
		$searchArr = array ("parentId" => $_GET ['parentId'], "projectId" => $projectId );
		$service = $this->service;
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

	/**
	 * ����ģ�嵽��Ŀ������
	 */
	function c_copyTemplateToType(){
		$projectType=$_POST['projectType'];//���Ƶ���Ŀ����
		$projectId=$_POST['projectId'];
		$tag=$this->service->copyTemplateToType($projectId,$projectType);
		echo $tag;
	}
}
?>
