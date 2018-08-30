<?php
/**
 * @author Administrator
 * @Date 2013年3月6日 星期三 17:29:11
 * @version 1.0
 * @description:质量标准控制层
 */
class controller_produce_quality_standard extends controller_base_action {

	function __construct() {
		$this->objName = "standard";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 跳转到质量标准列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增质量标准页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * 跳转到编辑质量标准页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], true );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}
	 
	/**
	 * 跳转到查看质量标准页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * 获取最新上传的一个文件
	 * Enter description here ...
	 */
	function c_getFile(){
		$this->permCheck (); //安全校验
		$uploadFile = new model_file_uploadfile_management ();
		$files = $uploadFile->getFilesByObjId ( $_GET['id'], $this->service->tbl_name );
		if(is_array($files)&&!empty($files))
			echo $files[0]['id'];
		else 
			echo '';
	}
}
?>