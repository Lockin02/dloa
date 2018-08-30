<?php
/**
 * @author tse
 * @Date 2014��1��4�� 9:24:00
 * @version 1.0
 * @description:���̸�������Ʋ� 
 */
class controller_engineering_file_esmfile extends controller_base_action {
	function __construct() {
		$this->objName = "esmfile";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}
	
	/**
	 * ��ת�����̸������б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת���������̸�����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭���̸�����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); // ��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴���̸�����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); // ��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * ��ת����Ŀ�ĵ��鿴ҳ��
	 */
	function c_pageForProjectView() {
		$this->assign ( 'projectId', $_GET ['projectId'] );
		$this->view ( 'viewlist' );
	}
	/**
	 * ��ת����Ŀ�ĵ��ɱ༭ҳ��
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
	 * �����ϴ�
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
			//�ϴ������󷵻ظø�����Ϣ��ǰ̨��ʾ
			echo util_jsonUtil::encode ( $file );
		}
	}
	
	/*
	 * ͨ������id���ظ���
	*/
	function c_toDownFileById() {
		$this->service->downFileById ( $_GET ['fileId'] );
	}
	
	/*
	 * ��дajax��ʽɾ����������
	* 1.ɾ�������ļ�
	* 2.ɾ�����ݿ��¼
	*/
	function c_ajaxdelete() {
		try {
			$service = $this->service;
			$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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