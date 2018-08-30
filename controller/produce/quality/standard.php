<?php
/**
 * @author Administrator
 * @Date 2013��3��6�� ������ 17:29:11
 * @version 1.0
 * @description:������׼���Ʋ�
 */
class controller_produce_quality_standard extends controller_base_action {

	function __construct() {
		$this->objName = "standard";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ת��������׼�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������׼ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭������׼ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], true );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}
	 
	/**
	 * ��ת���鿴������׼ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * ��ȡ�����ϴ���һ���ļ�
	 * Enter description here ...
	 */
	function c_getFile(){
		$this->permCheck (); //��ȫУ��
		$uploadFile = new model_file_uploadfile_management ();
		$files = $uploadFile->getFilesByObjId ( $_GET['id'], $this->service->tbl_name );
		if(is_array($files)&&!empty($files))
			echo $files[0]['id'];
		else 
			echo '';
	}
}
?>