<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:24:00
 * @version 1.0
 * @description:工程附件表控制层 
 */
class controller_engineering_file_esmfile extends controller_base_action {
	function __construct() {
		$this->objName = "esmfile";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}
	
	/**
	 * 跳转到工程附件表列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增工程附件表页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑工程附件表页面
	 */
	function c_toEdit() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看工程附件表页面
	 */
	function c_toView() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * 跳转到项目文档查看页面
	 */
	function c_pageForProjectView() {
		$this->assign ( 'projectId', $_GET ['projectId'] );
		$this->view ( 'viewlist' );
	}
	/**
	 * 跳转到项目文档可编辑页面
	 */
	function c_pageForProject() {
		$this->assign ( 'projectId', $_GET ['projectId'] );
		$this->view ( 'list' );
	}
	
	function c_toUploadFile(){
		$this->assign('typeId', $_GET['typeId']);
		$this->assign('projectId', $_GET['projectId']);
		$esmfiletypeDao = new model_engineering_file_esmfiletype(); 
		$esmfiletypeObj = $esmfiletypeDao->get_d($_GET['typeId']);
		$this->assign('typeName', $esmfiletypeObj['fileName']);
		
		$this->view('uploadfile');
	}
	
	/**
	 * 附件上传
	 */
	function c_toAddFile() {
		// In this demo we trigger the uploadError event in SWFUpload by returning a status code other than 200 (which is the default returned by PHP)
		if (! isset ( $_FILES ["file"] ) || ! is_uploaded_file ( $_FILES ["file"] ["tmp_name"] ) || $_FILES ["file"] ["error"] != 0) {
			// Usually we'll only get an invalid upload if our PHP.INI upload sizes are smaller than the size of the file we allowed
			// to be uploaded.
			header ( "HTTP/1.1 500 File Upload Error" );
			if (isset ( $_FILES ["file"] )) {
				echo $_FILES ["file"] ["error"];
			}
			exit ( 0 );
		} else {
// 			$_POST['serviceId']=isset($_POST['serviceId'])?$_POST['serviceId']:'';
// 			$_POST['serviceNo']=isset($_POST['serviceNo'])?$_POST['serviceNo']:'';
 			$_POST['serviceType']=isset($_POST['serviceType'])?$_POST['serviceType']:'';
			$_POST['typeId']= isset($_POST['typeId'])?$_POST['typeId']:'';
			$_POST ['typeName']= isset($_POST['typeId'])?util_jsonUtil::iconvUTF2GB($_POST['typeName']):'';
			$_POST ['projectId']= isset($_POST['projectId'])?$_POST['projectId']:'';
			$file = $this->service->uploadFileAction ( $_FILES, $_POST );
			//上传附件后返回该附件信息给前台显示
			echo util_jsonUtil::encode ( $file );
		}
	}
	
	/*
	 * 通过附件id下载附件
	*/
	function c_toDownFileById() {
		$this->service->downFileById ( $_GET ['fileId'] );
	}
	
	/*
	 * 重写ajax方式删除单个附件
	* 1.删除附件文件
	* 2.删除数据库记录
	*/
	function c_ajaxdelete() {
		try {
			$service = $this->service;
			$service->getParam ( $_POST ); //设置前台获取的参数信息
			//			echo "111111" . $_POST['id'];
			$file = $this->service->get_d ( $_POST ['id'] );
			$this->service->delFile ( 'oa_esm_file', $file ['newName'] );
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
}