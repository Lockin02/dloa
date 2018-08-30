<?php

/**
 * @author zengzx
 * @Date 2012��5��11�� 11:41:37
 * @version 1.0
 * @description:�ʲ�����������Ʋ�
 */
class controller_asset_require_requirement extends controller_base_action {

	function __construct() {
		$this->objName = "requirement";
		$this->objPath = "asset_require";
		parent :: __construct();
	}

	/*
	 * ��ת���ʲ����������б�
	 */
	function c_page() {
		//��ȡ��ַ������������״̬����
		$this->assign('isRecognize', $_GET['isRecognize']);
		$this->view('list');
	}


	/*
	 * ��ת���ʲ����������б�
	 */
	function c_myPage() {
		$this->view('mylist');
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->service->searchArr['applyManId']=$_SESSION['USER_ID'];

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/*
	 * ��ת���ʲ����������б�
	 */
	function c_signPage() {
		$this->assign('requireId', $_GET['requireId']);
		$this->view('sign');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_signJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$this->service->searchArr['applyManId']=$_SESSION['USER_ID'];

		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_signview');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
		 * ��ת�������ʲ���������ҳ��
		 */
	function c_toAdd() {
		$this->showDatadicts(array (
			'useType' => 'ZCYT'
		));
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d('DL');
		$this->assign('applyDate', day_date);
		$this->assign('signId', $_SESSION['USER_ID']);
		$this->assign('signName', $_SESSION['USERNAME']);
		$this->assign('signDeptId', $_SESSION['DEPT_ID']);
		$this->assign('signDeptName', $_SESSION['DEPT_NAME']);
		$this->assign('userCompanyCode', $_SESSION['Company']);
		$this->assign('userCompanyName', $branchArr['NameCN']);
		$this->assign('sendUserId', $_SESSION['USER_ID']);
		$this->assign('sendName', $_SESSION['USERNAME']);
		$this->view('add');
	}

	/**
		 * ��ת���༭�ʲ���������ҳ��
		 */
	function c_toEdit() {
		$this->showDatadicts(array (
			'useType' => 'ZCYT'
		));
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		if (isset ($_GET['btn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}

		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('sendUserId', $_SESSION['USER_ID']);
		$this->assign('sendName', $_SESSION['USERNAME']);
		$this->view('edit');
	}


	/**
	 * ��ת���鿴�ʲ���������ҳ��
	 */
	function c_toViewTab() {
		$this->assign( 'requireId',$_GET['requireId'] );
		$this->assign( 'requireCode',$_GET['requireCode'] );
		$this->view('view-tab');
	}


	/**
	 * ��ת���鿴�ʲ���������ҳ��
     */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		if(is_numeric($_GET['id']) && strlen($_GET['id']) < 32){//��OA����
			$obj = $this->service->get_d($_GET['id']);
			if($obj['requireType']=='1'){
				$obj['requireType']='����';
			}else{
				$obj['requireType']='����';
			}
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			$this->view('view');
		}else{//��OA����
			// ����aws
			// ��aws��ȡ�ʲ�������������
			$result = util_curlUtil::getDataFromAWS ( 'asset', 'getRequireInfo', array (
					"requireId" => $_GET['id']
			) );
			$requireInfo = util_jsonUtil::decode ( $result ['data'], true );
			// �������ݴ���
			$data = $requireInfo ['data'] ['requireInfo'];
			$user = util_jsonUtil::userBuild ( $data ['theuser'] );
			$this->assign ( 'requireCode', $data ['num'] );
			$this->assign ( 'userName', $user ['userName'] );
			$this->assign ( 'userDeptName', $data ['udepartment'] );
			$this->assign ( 'userCompanyName', $data ['ucompany'] );
			$this->assign ( 'beginDate', $data ['borrowdate'] );
			$this->assign ( 'returnDate', $data ['planreturndate'] );
			$this->assign ( 'expectAmount', $data ['money'] );
			$this->assign ( 'applyDate', $data ['processingdate'] );
			$this->assign ( 'recognizeAmount', $data ['confirmmoney'] );
			$this->assign ( 'address', $data ['address'] );
			$this->assign ( 'remark', $data ['remark'] );
			// �ӱ����ݴ���
			// �ʲ�����������ϸ
			if (! empty ( $requireInfo ['data'] ['details'] )) {
				$requireDetails = array ();
				foreach ( $requireInfo ['data'] ['details'] as $k => $v ) {
					$v ['detailId'] = $k;
					array_push ( $requireDetails, $v );
				}
			}
			$this->assign ( 'requireDetails', util_jsonUtil::encode ( $requireDetails ) );

			$this->view('view-new');
		}
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$requireObj = $_POST[$this->objName];
//		echo "<pre>";
//		print_R($requireObj);
		if( $requireObj['addressFlag']!='1' ){
			$filename = WEB_TOR . "view/template/asset/require/addressInfo.txt";
			$handle = fopen($filename, "r");//��ȡ�������ļ�ʱ����Ҫ���ڶ����������ó�'rb'
	    	//ͨ��filesize����ļ���С���������ļ�һ���Ӷ���һ���ַ�����
	    	$contents = fread($handle, filesize ($filename));
	    	fclose($handle);
	    	$requireObj['address']=$contents;
		}
		if( $requireObj['expectAmount']=='' ){
			$requireObj['expectAmount']=0;
		}
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			$requireObj['isSubmit'] = 1;
			$msgStr = '�ύ�ɹ�';
		}else{
			$msgStr = '����ɹ�';
			$requireObj['isRecognize'] = 2;
		}
		$id = $this->service->add_d($requireObj, $isAddInfo);
//		if ($id) {
//			if ("audit" == $actType) {
//				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $id
//				. '&flowMoney=' . $requireObj['expectAmount'] .'&billDept='. $requireObj['userDeptId']);
//			} else {
//				msgRf('��ӳɹ�');
//			}
//		}
		if($id){
			msgRf($msgStr);
		}else{
			msgRf('�ύʧ��');
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST[$this->objName];
		if( $object['expectAmount']=='' ){
			$object['expectAmount']=0;
		}
		$object['ExaStatus']='';
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			$object['isSubmit'] = 1;
			$object['isRecognize'] =0;
		}
		$flag = $this->service->edit_d($object, $isEditInfo);
//		if ($flag) {
//			if ("audit" == $actType) {
//				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $object['id']
//				. '&flowMoney=' . $object['expectAmount'].'&billDept='. $object['userDeptId']);
//			} else {
//				msgRf('�༭�ɹ�');
//			}
//		}
		if ($flag) {
			if ("submit" == $actType) {
				msgRf('�ύ�ɹ�');
			} else {
				msgRf('�༭�ɹ�');
			}
		}else{
			msgRf('�༭ʧ��');
		}
	}

	/**
		 * ��ת���鿴�ʲ����� ���ȷ�� ҳ��
		 */
	function c_toRecognize() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('amounts');
	}

	/**
	 * ��ת���鿴�ʲ���������ҳ�� ���ȷ�� ҳ��--����
	*/
	function c_toRecognizeView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('amounts-view');
	}
	/**
	 * �޸Ķ���
	 */
	function c_recognize($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST[$this->objName];
//		echo "<pre>";
//		print_R($object);
		$obj = $this->service->get_d($object['id']);
		if( $object['recognizeAmount']=='' ){
			$object['recognizeAmount']=0;
		}
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$flag = $this->service->edit_d($object, $isEditInfo);

		if ($flag) {
			if ("audit" == $actType) {
				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $object['id']
				. '&flowMoney=' . $object['recognizeAmount'] .'&billDept='. $object['userDeptId']);
			} else {
				msgRf('�ύ�ɹ�');
			}
		}
	}

	/**
	 * �̶��ʲ��ճ�������������ִ�з���
	 */
	function c_dealAfterAudit(){
		//�������ص�����
        $this->service->workflowCallBack($_GET['spid']);
       	echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}


	/**
	 * �ı䵥��״̬
	 */
	function c_reback() {
		try {
			$object = $_POST[$this->objName];
			$this -> service -> backDetail_d($object);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}

	/**
	 * �ı䵥��״̬
	 */
	function c_rebackMail() {
		try {
			$id = isset($_GET['id']) ? $_GET['id'] : false;
			$this->service->sendMailAtBack($id);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}
	/**
	 * ��ص���
	 */
	function c_toBackDetail() {
		$obj = $this -> service -> get_d($_GET['id']);
		$this->assignFunc($obj);
		$this -> view('backdetail');
	}
	/**
	 * ����
	 */
	function c_backDetail(){
		$rs = $this->service->backDetail_d($_POST[$this->objName]);
		if($rs){
			msg('��سɹ�');
		}
	}

	/**
	 * ��ص���
	 */
	function c_toBackDetailView() {
		$obj = $this -> service -> get_d($_GET['id']);
		$this->assignFunc($obj);
		$this -> view('backdetailView');
	}

	/**
	 * ����
	 */
	function c_rollback() {
		try {
			$id = isset($_GET['id']) ? $_GET['id'] : false;
			$this->service->rollback_d($id);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}

}
?>