<?php
/**
 * @author Show
 * @Date 2011��12��3�� ������ 10:29:00
 * @version 1.0
 * @description:�����ͬ���Ʋ�
 */
class controller_contract_outsourcing_outsourcing extends controller_base_action {

	function __construct() {
		$this->objName = "outsourcing";
		$this->objPath = "contract_outsourcing";
		parent::__construct ();
	}

	/*
	 * ��ת�������ͬ
	 */
    function c_page() {
       $this->view('list');
    }

    /**
     * ������Ŀ������ܱ�
     */
    function c_pageForESM(){
		$this->view('listforesm');
    }

    /**
     * �з���Ŀ�����Ŀ���ܱ�
     */
    function c_pageForRD(){
		$this->view('listforrd');
    }

    /**
     * ��дtoadd
     */
    function c_toAdd() {
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//�������
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//��ͬ�����ʽ

		//����������Ϣ��Ⱦ
		$this->showDatadicts ( array ('payFor' => 'FKLX' ) );//��������
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//���㷽ʽ

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//��������

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$otherdatas = new model_common_otherdatas();
		$this->assign('deptName',$otherdatas->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME'));

		$this->assign('isSysCode',ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
		$this->view ( 'add' );
	}

	/**
	 * ��������
	 */
	function c_toAddDept(){
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//��ͬ�������
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//��ͬ�����ʽ

		//����������Ϣ��Ⱦ
		$this->showDatadicts ( array ('payFor' => 'FKLX' ) );//��������
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//���㷽ʽ

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//��������


		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$otherdatas = new model_common_otherdatas();
		$this->assign('deptName',$otherdatas->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME'));

		$this->assign('isSysCode',ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
		$this->view ( 'adddept' );
	}

     /**
     * �ڽ���Ŀ�б���ת
     */
    function c_addForProject() {
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//��ͬ�������
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//��ͬ�����ʽ

//    	$this->permCheck (); //��ȫУ��
		$obj = $this->service->getInfoProject_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), "HTWB03" );//�������

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//��������

		$this->assign('thisDate',day_date);
		$this->view ( 'addforproject' );
	}

	/*
	 * ��ת��Tab��Ŀ�����ͬ
	 */
    function c_listForProject() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('listforproject');
    }

	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ($object);
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] ) ;
				}else{
					succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgRf('����ɹ�');
			}
		}else{
			msgRf('����ʧ��');
		}
	}

	/**
	 * ���������������
	 */
	function c_addDept() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] );
				}else{
					succ_show('controller/contract/outsourcing/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgGo ( '����ɹ���', '?model=contract_outsourcing_outsourcing&action=toAddDept' );
			}
		}else{
			msgGo ( '����ʧ�ܣ�', '?model=contract_outsourcing_outsourcing&action=toAddDept' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		$id = $this->service->editInfo_d ( $object);
		if ($id) {
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] );
				}else{
					succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgRf('����ɹ�');
			}
		}else{
			msgRf('����ʧ��');
		}
	}

	/**
	 * ��ת�����������Ĳ鿴ҳ��
	 */
	 function c_viewAccraditation(){
	 	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		//�Ƿ�
		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		$this->view ( 'viewAccraditation' );
	 }

	 /**
	  * �鿴ҳ�� - ��������������Ϣ
	  */
	 function c_viewAlong(){
	 	$this->permCheck (); //��ȫУ��

		//�ύ������鿴����ʱ���عرհ�ť
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',1);
		}else{
			$this->assign('hideBtn',0);
		}

		$obj = $this->service->getInfo_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		//�Ƿ�
		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;
		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		//ǩ��״̬
		$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

		$this->assign ( 'payFor', $this->getDataNameByCode ( $obj ['payFor'] ) );
		$this->assign ( 'payType', $this->getDataNameByCode ( $obj ['payType'] ) );

		$this->view ( 'viewAlong' );
	 }

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$obj = $this->service->get_d ( $_GET ['id'] );
			$this->assignFunc($obj);

			//�ύ������鿴����ʱ���عرհ�ť
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			//�������{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

			//�Ƿ�
			$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
			$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

			//ǩ��״̬
			$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
			$this->view ( 'view' );
		} else {

			$obj = $this->service->getInfo_d ( $_GET ['id'] );
			$this->assignFunc($obj);

			//�������{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
			$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
			$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ), $obj ['outPayType'] );//��ͬ���ʽ
			$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//��ͬ�����ʽ

			//���ø�������
//			$stampConfigDao = new model_system_stamp_stampconfig();
//			$stampArr = $stampConfigDao->getStampType_d();
//			$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//��������

			//����������Ϣ��Ⱦ
			$this->showDatadicts ( array ('payFor' => 'FKLX' ),$obj['payFor']);//��������
			$this->showDatadicts ( array ('payType' => 'CWFKFS' ),$obj['payType']);//���㷽ʽ

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��ͬtabҳ
	 */
	function c_viewTab(){
		$this->permCheck (); //��ȫУ��
		$this->assign('id',$_GET['id']);
		$this->display('viewtab');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->page_d ('select_info');

		if(is_array($rows)){
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );

			//�ϼƼ���
			$rows = $this->service->pageCount_d($rows);

			$objArr = $service->listBySqlId('count_list');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['createDate'] = '�ϼ�';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת�������ҳ��
	 */
	function c_toStamp(){
	 	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
		$this->assign('applyDate',day_date);

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//��������

		//��ǰ����������
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisUserName',$_SESSION['USERNAME']);

		$this->view ( 'stamp' );
	}

	/**
	 * ����������Ϣ����
	 */
	function c_stamp(){
		$rs = $this->service->stamp_d($_POST[$this->objName]);
		if ($rs) {
			msg ( "����ɹ���" );
		}else{
			msg ( "����ʧ�ܣ�" );
		}
	}

	/**
	 * ������ɺ�����µķ���
	 */
	function c_dealAfterAudit(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAudit_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * �����ͬ���������
	 */
	function c_dealAfterAuditPayapply(){
       	$this->service->dealAfterAuditPayapply_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��ת���������ͬ
	 */
	function c_myOutsourcing(){
	 	$this->view('mylist');
	}

	/**
     * �ҵ������ͬ
     */
    function c_myOutsourcingListPageJson() {
    	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['principalIdAndCreateId'] = $_SESSION['USER_ID'];
		$rows = $service->page_d ('select_info');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���������ͬ
	 */
	function c_myStatusList(){
	 	$status = isset($_GET['status']) ? $_GET['status'] : 0 ;
	 	$this->assign('status',$status);
	 	$this->view('mystatuslist');
	}

	/**
	 * �رպ�ͬ
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => '3'))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * �����ϴ�
	 */
	function c_toUploadFile(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * �޸���Ϣ - ��ǰ�����޸ı�ע
	 */
	function c_toUpdateInfo(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->view('updateinfo');
	}

	/**
	 * �޸Ķ���
	 */
	function c_updateInfo() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object);
		if ($id) {
			msg('����ɹ�');
		}else{
			msg('����ʧ��');
		}
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}


	/***************** S ���뵼��ϵ�� *********************/
	/**
	 * ��ͬ����
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ��ͬ�������
	 */
	function c_excelIn(){
		if($_POST['actionType'] == 0){
			$resultArr = $this->service->addExecelData_d ();
		}

		$title = '��ͬ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/***************** E ���뵼��ϵ�� *********************/


	/******************* S ���ϵ�� *********************/
	function c_toChange(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//��ͬ�����ʽ

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//��������

		$this->view('change');
	}

	/**
	 * �������
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		try {
			$id = $this->service->change_d ( $_POST [$this->objName] );

			succ_show("controller/contract/outsourcing/ewf_change.php?actTo=ewfSelect&billId=" . $id);

		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}

	/**
	 * ������ɺ�����µķ���
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAuditChange_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ����鿴tab
	 */
	function c_changeTab(){
		$this->permCheck (); //��ȫУ��
		$newId = $_GET ['id'];
		$this->assign('id',$newId);

		$rs = $this->service->find(array('id' => $newId ),null,'originalId');
		$this->assign('originalId',$rs['originalId']);

		$this->display('changetab');
	}

	/**
	 * ����鿴��ͬ  - �鿴ԭ��ͬ
	 */
	function c_changeView(){
		$this->permCheck (); //��ȫУ��
		$id = $_GET ['id'];

		$obj = $this->service->get_d ( $id );

		$this->assignFunc($obj);

		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $id, false,$this->service->tbl_name )) ;

		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );

		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;
		$this->assign('isNeedRestamp',$this->service->rtYesOrNo_d($obj ['isNeedRestamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
		$this->view ( 'changeview' );
	}
	/******************* E ���ϵ�� *********************/

	/******************* S ǩ��ϵ�� *********************/
	/**
	 * ��ͬǩ�� - �б�tabҳ
	 */
	function c_signTab(){
		$this->display('signTab');
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signingList(){
		$this->view('signinglist');
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signedList(){
		$this->view('signedlist');
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_toSign(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//��ͬ�����ʽ

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//��������

		$this->view('sign');
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_sign(){
		$object = $_POST [$this->objName];
		$id = $this->service->sign_d ( $object);
		if ($id) {
			msgRf('ǩ�ճɹ�');
		}else{
			msgRf('ǩ��ʧ��');
		}
	}

	/******************* E ǩ��ϵ�� *********************/
}
?>