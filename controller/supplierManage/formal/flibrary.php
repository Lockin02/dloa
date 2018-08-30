<?php
class controller_supplierManage_formal_flibrary extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-11 ����10:39:19
	 */
	function __construct() {
		$this->objName = "flibrary";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	}
	/**
	 * @desription ��ת����ʽ������б�
	 * @param tags
	 * @date 2010-11-11 ����10:41:06
	 */
	function c_toFsupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * @desription ��ת����Ӧ�̺ϸ���б�
	 */
	function c_toPassSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-pass-list' );
	}
	/**
	 * @desription ��ת����Ӧ�̲��ϸ���б�
	 */
	function c_toFailSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-fail-list' );
	}
	/**
	 * @desription ��ת��������Ӧ�̿��б�
	 */
	function c_toOtherSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-other-list' );
	}
	/**
	 * @desription ��ת���޸�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_tobasicEdit() {
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows ['file'] = $this->service->getFilesByObjId ( $rows ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$trainDao = new model_supplierManage_formal_bankinfo ();
		$this->assign ( 'list', $trainDao->showTrainEditList ( $Id, array ('KHBank' => 'KHBANK' ) ) );
		$this->showDatadicts ( array ('KHBank' => 'KHBANK' ) );
		$this->showDatadicts ( array ('suppCategory' => 'gyslb' ),$rows['suppCategory'] ); //��Ӧ�����

		$this->display ( 'basicE',true );
	}

	/**
	 * ��Ӧ�����ҳ��
	 */
	function c_toAdd() {
		//������
		$objCode = generatorSerial ();
		//ϵͳ���
		$sysCode = generatorSerial ();
		$this->assign ( 'objCode', $objCode );
		$this->assign ( 'systemCode', $sysCode );

		//��ȡ�����ֵ��������
		$this->showDatadicts ( array ('KHBank' => 'KHBANK' ) ); //��������
		$this->showDatadicts ( array ('suppCategory' => 'gyslb' ) ); //��Ӧ�����
		//��Ӧ�̵ĳ�������
		$datadictDao = new model_system_datadict_datadict ();
		$datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		$stasseDao = new model_supplierManage_temporary_stasse ();
		$str = $stasseDao->add_s ( $datadictArr ['lskpg'] );
		$this->show->assign ( "str", $str );
		$this->assign ( 'flag', $_GET ['flag'] );
		//�����ӹ�Ӧ������
		if (empty ( $_GET ['valPlus'] )) {
			$this->assign ( 'valPlus', '' );
		} else {
			$this->assign ( 'valPlus', $_GET ['valPlus'] );
		}
		$this->display ( 'add',true );
	}
	/**
	 * @desription ��ת���޸�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toEdit() {
		if($_GET ['suppGrade']=="A"||$_GET ['suppGrade']=="B"||$_GET ['suppGrade']=="C"){
			if(!$this->service->this_limit['��Ӧ�̱༭']){
				echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
				exit();
			}
		}
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$objCode = isset ( $_GET ['objCode'] ) ? $_GET ['objCode'] : null;
		$this->show->assign ( "id", $Id );
		$this->show->assign ( "objCode", $objCode );
		$this->assign('skey_',$_GET['skey']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-Etab');
	}
	/**
	 * @desription ��ת���鿴������Ϣҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_tobasicRead() {
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->getSuppInfoById( $Id );

		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$trainDao = new model_supplierManage_formal_bankinfo ();
		$contratDao=new model_supplierManage_formal_sfcontact();
		$this->assign ( 'list', $trainDao->showViewBank ( $Id) );
		$this->assign ( 'contratlist', $contratDao->showViewContact ( $Id) );
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->display ( 'basicR' );
	}
	/**
	 * @desription ��ת���鿴������Ϣҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toassessMsg() {
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->get_d ( $Id );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-toRegMsg' );
	}
	/**
	 * @desription ��ת���鿴ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toRead() {
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$objCode = isset ( $_GET ['objCode'] ) ? $_GET ['objCode'] : null;
		$this->show->assign ( "id", $Id );
		$this->show->assign ( "objCode", $objCode );
		$this->assign('skey_',$_GET['skey']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-Rtab' );
	}
	/**
	 * @desription ��ת�����为����ҳ��
	 * @param tags
	 * @date 2010-11-12 ����01:48:42
	 */
	function c_toAduitP() {
//		if(!$this->service->this_limit['���为����']){
//			echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
//			exit();
//		}
		$this->permCheck ();//��ȫУ��
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-aduit' );

	}

	/**
	 *
	 * ��ת����Ӧ�����ϵ���
	 */
	function c_toImportPage() {
		$this->view ( "info-import" );
	}
	/**
	 * �޸Ķ���
	 */
	function c_flibedit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object,true )) {
			msg ( '����ɹ���' );
		}
	}
	/**
	 * �޸���ϵ�˻�����Ϣ
	 */
	function c_basicEdit() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		if ($this->service->editinfo_d ( $object, true)) {
			//			echo $_GET['parentId'];
			//			$url ='?model=supplierManage_formal_flibrary&action=tobasicEdit&parentId='+$_GET['parentId']+'&parentCode='+$_GET['parentCode'];
			////			echo $url;
			msgGo ( '�༭�ɹ���' );
		}
	}

	function c_addByExternal() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$supplier = $_POST [$this->objName];
		$id = $this->service->addByExternal_d ( $_POST [$this->objName] );
		$supplier ['id'] = $id;

		if ($id) {
			if (! empty ( $_POST ['valPlus'] )) {
				echo "<script>window.opener.jQuery('#valHidden" . $_POST ['valPlus'] . "').val('" . util_jsonUtil::encode ( $supplier ) . "');</script>";
			}
			msgRf ( '��ӳɹ���' );
		} else {
			msgRf ( '���ʧ��!' );
		}
	}

	/**
	 * @desription ��ת���Ҹ���Ĺ�Ӧ���б�
	 * @param tags
	 * @date 2010-11-16 ����01:47:36
	 */
	function c_myReslist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-mylist' );
	}
	/**
	 * @desription ��ת����ע��Ĺ�Ӧ���б�
	 * @param tags
	 * @date 2010-11-16 ����01:47:36
	 */
	function c_myloglist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-myloglist' );
	}
	/*
 * �Ҹ���Ĺ�Ӧ���б����ݻ�ȡ
 */
	function c_mypageJson() {
		$service = $this->service;
		$manageUserId = isset ( $_SESSION ['USER_ID'] ) ? $_SESSION ['USER_ID'] : null;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$this->searchVal ( 'suppName' );
	//	$this->searchVal ( 'products' );
		$rows = $this->service->myResSupp ( $manageUserId );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
 * �Ҳ�Ĺ�Ӧ���б����ݻ�ȡ
 */
	function c_mylogpageJson() {
		$service = $this->service;
		$createId = isset ( $_SESSION ['USER_ID'] ) ? $_SESSION ['USER_ID'] : null;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->searchVal ( 'suppName' );
		$this->searchVal ( 'products' );
		$rows = $this->service->myLogSupp ( $createId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * ��ʽ���ȡ���ݷ���
 * */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//		$service->asc = false;
		$this->searchVal ( 'suppName' );
		$this->searchVal ( 'products' );
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * �ϸ񣬲��ϸ�������Ӧ�̻�ȡ���ݷ���
 * */
	function c_suppJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//		$service->asc = false;
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * �ϸ񣬲��ϸ�������Ӧ�̻�ȡ���ݷ���(������ϵ��)
 * */
	function c_suppcontJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//		$service->asc = false;
		$rows = $service->pageBySqlId('select_cont');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription ���������ù�Ӧ��ҳ��
	 * @param tags
	 * @date 2010-11-17 ����06:46:19
	 */
	function c_stoc() {
		if(!$this->service->this_limit['���ù�Ӧ��']){
			echo "<script>alert('û��Ȩ�޽��в���!');location='?model=supplierManage_formal_flibrary&action=toFsupplist'</script>";
			exit();
		}
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$flibrary = array ("id" => $Id, "status" => $this->service->statusDao->statusEtoK ( "common" ) );
		$this->operArr = array ();
		$this->beforeMethod ( $flibrary );
		$val = $this->service->edit_d ( $flibrary, true );
		if ($val) {
			$flibrary ['operType_'] = '������Ӧ��';

			$this->behindMethod ( $flibrary );
			msgGo ( '�����ɹ���', '?model=supplierManage_formal_flibrary&action=toFsupplist' );
		}
		echo util_jsonUtil::iconvGB2UTF ( $val );

	}

	/**
	 * @desription ���������ù�Ӧ��ҳ��
	 * @param tags
	 * @date 2010-11-17 ����06:46:19
	 */
	function c_ctos() {
		if(!$this->service->this_limit['���ù�Ӧ��']){
			echo "<script>alert('û��Ȩ�޽��в���!');location='?model=supplierManage_formal_flibrary&action=toFsupplist'</script>";
			exit();
		}
		$this->permCheck ();//��ȫУ��
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$flibrary = array ("id" => $Id, "status" => $this->service->statusDao->statusEtoK ( "stop" ) );
		$this->operArr = array ();
		$this->beforeMethod ( $flibrary );
		$val = $this->service->edit_d ( $flibrary, true );
		if ($val) {
			$flibrary ['operType_'] = '���ù�Ӧ��';
			$this->behindMethod ( $flibrary );
			msgGo ( '���óɹ���', '?model=supplierManage_formal_flibrary&action=toFsupplist' );
		}
		echo util_jsonUtil::iconvGB2UTF ( $val );
	}

	/**��ɾ����Ӧ��
	 *author can
	 *2011-4-7
	 */
	function c_delSupplier() {
		if(!$this->service->this_limit['ɾ����Ӧ��']){
			echo "2";
			exit();
		}
		$condition = array ('id' => $_POST ['id'] );
		if ($this->service->updateField ( $condition, 'delFlag', '1' )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**�Թ�Ӧ��Ϊ��λ�鿴�����¼���ɹ���Ʊ��¼
	*author can
	*2011-7-21
	*/
	function c_supplierInfo(){
		$this->permCheck ();//��ȫУ��
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$this->assign('id',$id);
		$this->display('infor-tab');
	}

	/**
	 * @desription ajax�жϹ�Ӧ�������Ƿ��ظ�
	 * @param tags
	 * @date 2010-11-10 ����05:06:08
	 */
	function c_ajaxSuppName() {
		$service = $this->service;
		$suppName = isset ($_GET['suppName']) ? $_GET['suppName'] : false;
		$searchArr = array (
			"ajaxSuppName" => $suppName
		);
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$isRepeat = $service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	function c_getSuppInfo(){
		$suppId=isset($_POST['id'])?$_POST['id']:"";
		$rows=$this->service->get_d($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows);
		}else{
			echo "";
		}
	}


	/*********************** excel ���� ***************************/
	/**
	 * excel����ҳ��
	 */
	function c_toExcel(){
		$this->display('excel');
	}

	/**
	 * excel����
	 */
	function c_excelImport(){
		$resultArr = $this->service->excelImport_d ();
		$title = '��Ӧ����Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * excel����
	 */
	function c_excelOutport(){
		set_time_limit(0);
		$service = $this->service;
		$service->sort = 'c.busiCode';
		$service->asc = false;
		$rows = $service->list_d('excel_select');
//		var_dump($rows);

		return util_excelUtil::exportSupplier ( $rows );
	}

	/**
	 * ���빩Ӧ��
	 *
	 */
	 function c_importSupplier(){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_supplierManage_formal_importSupplierUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$newResult=util_jsonUtil::encode ( $excelData );;
			echo "<script>window.parent.setExcelValue('".$newResult."');self.parent.tb_remove()</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');history.go(-1);</script>";
		}

	 }
}
?>
