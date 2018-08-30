<?php

/**
 * 工程服务附件管理控制层
 */
class controller_engineering_file_uploadfile extends controller_base_action {

	function __construct() {
		$this->objName = "uploadfile";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}



	/**
	 * 跳转到项目附件页面
	 */
	function c_toEsmFilePage(){
		if(!empty($_GET['isView'])){
			$this->assign ( 'isView', 1 );
		}else{
			$this->assign ( 'isView', 0 );
		}
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'objTable', $_GET ['objTable'] );
		$this->view ( 'list' );
	}

	/**
	 * 文件下载接口
	 */
	function c_toDownFile() {
		$this->service->downFile2 ( $_GET ['inDocument'], $_GET ['newName'], $_GET ['originalName'] );
	}

	/**
	 * 通过附件id下载附件
	 */
	function c_toDownFileById() {
		$this->service->downFileById ( $_GET ['fileId'] );
	}

	function c_readInfo() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('-read' );
	}
}
?>