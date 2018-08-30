<?php
/**
 * @description: ��Ӧ������
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_assess_assessment extends controller_base_action{
 	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-9 ����08:53:05
	 */
	function __construct() {
		$this->objName = "assessment";
		$this->objPath = "supplierManage_assess";
		//ͳһע�����ֶΣ������ͬ�����в�ͬ�ļ���ֶΣ��ڸ��Է���������Ĵ�����
		$this->operArr = array ("name" => "��������", "status" => "״̬" );
		parent::__construct();
	}

	/**
	 * @desription ��ת������Ӧ������
	 * @param tags
	 * @date 2010-11-8 ����02:18:04
	 */
	function c_saaToAdd () {
		$this->showDatadicts( array("gpglx") );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
     * ע�ṩӦ����Ϣ����
	 * @date 2010-9-20 ����02:06:22
	 */
	function c_saaAdd() {
		$objArr = $_POST [$this->objName];
		$objArr['status'] = $this->service->statusDao->statusEtoK("save");
		$id = $this->service->add_d ( $objArr,true );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "�������������" . $objArr ['assesName'] . "��";
			$this->behindMethod ( $objArr );
			succ_show ( '?model=supplierManage_assess_suppassess&action=sasAddList&assId='.$id );
		}else{
			msg ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * @desription ��ת�޸�
	 * @param tags
	 * @date 2010-11-14 ����10:07:25
	 */
	function c_saaToEdit () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * @desription �޸�
	 * @param tags
	 * @date 2010-11-14 ����10:20:18
	 */
	function c_saaEdit () {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			succ_show ( '?model=supplierManage_assess_suppassess&action=sasAddList&assId='.$object['id'] );
		}else{
			msg ( '�޸�ʧ�ܣ�' );
		}
	}

	/**
	 * @desription �鿴
	 * @param tags
	 * @date 2010-11-12 ����04:13:04
	 */
	function c_saaRead () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$suppDao = new model_supplierManage_assess_suppassess();
		$suppArr = $suppDao->getAllByAssesId($assId);
		$suppStr = $suppDao->sasReadList_d($suppArr);

		$normDao = new model_supplierManage_assess_norm();
		$normArr = $normDao->getAllByAssesId_d($assId);
		$normStr = $normDao->sasReadList_d($normArr);
		$this->arrToShow ( $arr );

		$this->show->assign ( 'suppStr',  $suppStr );
		$this->show->assign ( 'normStr',  $normStr );
		$this->show->assign ( 'assId',  $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * @desription ���������ת
	 * @param tags
	 * @date 2010-11-14 ����11:36:47
	 */
	function c_assToClose () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		$closeArr = $this->service->showClose($assId);
		$this->show->assign ( 'list',  $this->service->showClose_s($closeArr) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-close' );
	}

	/**
	 * @desription �ر�����
	 * @param tags
	 * @date 2010-11-14 ����11:36:47
	 */
	function c_assClose () {
		$assId = isset( $_POST['assId'] )?$_POST['assId']:exit;
		$object = array(
			"id" => $assId
		);
		$this->beforeMethod( $object );
		$val = $this->service->assClose_d( $_POST );
		if ( $val ) {
			$object ['operType_'] = '�ر�����';
			$this->behindMethod ( $object );
			msgBack2 ( '�ر������ɹ���');
		}else{
			msgBack2 ( '�ر�����ʧ�ܣ�');
		}
	}

	/**
	 * @desription ��������
	 * @param tags
	 * @date 2010-11-13 ����10:52:28
	 */
	function c_saaStart () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		if( $this->service->saaStartIs_d($assId) ){
			$object = array(
				"id" => $assId,
				"status" => $this->service->statusDao->statusEtoK("ongoing")
			);
			$this->beforeMethod( $object );
			$val = $this->service->edit_d($object, true);
			if ( $val ) {
				$object ['operType_'] = '��������';
				$this->behindMethod ( $object );
				msgGo ( '���������ɹ���','?model=supplierManage_assess_assessment&action=saaMyMangeTab' );
			}
		}else{
			msgGo ( '�����������������ٽ����ύ��','?model=supplierManage_assess_assessment&action=saaMyMangeTab' );
		}
	}

/**********************************�ҵ���������********************************************/

	/**
	 * @desription �Ҹ����tabҳ
	 * @param tags
	 * @date 2010-11-13 ����02:46:16
	 */
	function c_saaMyMangeTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyManageTab' );
	}

	/**
	 * @desription �Ҹ����������δ�رգ�
	 * @param tags
	 * @date 2010-11-13 ����02:03:57
	 */
	function c_saaMyMangeList () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyMangeList' );
	}

	/**
	 * @desription �Ҹ���������б�Json��δ�رգ�
	 * @param tags
	 * @date 2010-11-13 ����02:11:22
	 */
	function c_saaPJMyManage () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array(
			"manageId" => $_SESSION['USER_ID'],
			"statusArr" => $service->statusDao->statusEtoK("save"). "," .$service->statusDao->statusEtoK("ongoing")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription �Ҹ�����������رգ�
	 * @param tags
	 * @date 2010-11-13 ����03:08:54
	 */
	function c_saaMyMangeListClose () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MyMangeListClose' );
	}

	/**
	 * @desription �Ҹ���������б�Json���ѹرգ�
	 * @param tags
	 * @date 2010-11-13 ����02:11:22
	 */
	function c_saaPJMyManageClose () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array(
			"manageId" => $_SESSION['USER_ID'],
			"statusArr" => $service->statusDao->statusEtoK("close"). "," .$service->statusDao->statusEtoK("end")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

/**********************************��������********************************************/

	/**
	 * @desription ���������tabҳ
	 * @param tags
	 * @date 2010-11-13 ����02:46:16
	 */
	function c_saaMangeTab () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-ManageTab' );
	}

	/**
	 * @desription ���������������δ�رգ�
	 * @param tags
	 * @date 2010-11-13 ����02:03:57
	 */
	function c_saaMangeList () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MangeList' );
	}

	/**
	 * @desription ��������������б�Json��δ�رգ�
	 * @param tags
	 * @date 2010-11-13 ����02:11:22
	 */
	function c_saaPJManage () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array(
			"statusArr" => $service->statusDao->statusEtoK("save"). "," .$service->statusDao->statusEtoK("ongoing")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription ����������������رգ�
	 * @param tags
	 * @date 2010-11-13 ����03:08:54
	 */
	function c_saaMangeListClose () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-MangeListClose' );
	}

	/**
	 * @desription ��������������б�Json���ѹرգ�
	 * @param tags
	 * @date 2010-11-13 ����02:11:22
	 */
	function c_saaPJManageClose () {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = array(
			"statusArr" => $service->statusDao->statusEtoK("close"). "," .$service->statusDao->statusEtoK("end")
		);
		$this->searchVal( 'assesName' );
		$this->searchVal( 'createName' );
		$rows = $service->saaPage_d ("select_MyManage");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

/**********************************��������********************************************/
	/**
	 * @desription �ύ
	 * @param tags
	 * @date 2010-11-16 ����02:42:06
	 */
	function c_submit () {
		$id = isset($_GET['assId'])?$_GET['assId']:exit;
		$val = $this->service->submit_d( $id );
		if( $val==0 ){
			msgGo ( '������д��������������ٽ����ύ��');
		}else if($val==1){
			msgGo ( '�ύ�ɹ���');
		}
	}

	/**
	 * @desription �鿴ҳ��Tab
	 * @param tags
	 * @date 2010-11-16 ����04:49:16
	 */
	function c_saaViewTab () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//��ȫУ��
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId',  $assId );
		$this->show->assign ( 'viewPerm',  "1" );
		$this->show->assign ( 'skey_',  $_GET['skey'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-saaViewTab' );
	}

	/**
	 * @desription �鿴ҳ��
	 * @param tags
	 * @date 2010-11-16 ����07:07:33
	 */
	function c_saaView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//��ȫУ��
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$arr = $this->service->saaArrById_d ( $assId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts( array("gpglx"),$arr['0']['assesType'] );
		$this->show->assign ( 'gpglx',  $this->getDataNameByCode($arr['0']['assesType']) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

}

?>
