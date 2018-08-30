<?php
/**
 * @author Show
 * @Date 2011��12��5�� ����һ 10:19:51
 * @version 1.0
 * @description:������ͬ���Ʋ�
 */
class controller_contract_other_other extends controller_base_action {

	function __construct() {
		$this->objName = "other";
		$this->objPath = "contract_other";
		parent::__construct ();
	}

	/*
	 * ��ת��������ͬ
	 */
    function c_page() {
       $this->view('list');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonFinanceInfo() {
		$service = $this->service;

		//��ʼ����������
		if(isset($_POST['payandinv'])){
			$thisSet = $service->initSetting_c($_POST['payandinv']);
			$_POST[$thisSet] = 1;
			unset($_POST['payandinv']);
		}

		//ϵͳȨ��
		$deptLimit = $this->service->this_limit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if(strstr($deptLimit,';;')){
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_financeInfo');
		}else{//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ

			if(!empty($deptLimit)){
				$_POST['deptsIn'] = $deptLimit ;
				$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
				$rows = $service->page_d ('select_financeInfo');
			}
		}

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
		$arr ['advSql'] = $service->advSql;//������չSQL
		echo util_jsonUtil::encode ( $arr );
	}

     /**
     * ��дtoadd
     */
    function c_toAdd() {
		$this->showDatadicts ( array ('fundType' => 'KXXZ' ));
		$this->showDatadicts ( array ('signCompanyType' => 'QYDX' ));
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),null,true);

		//����������Ϣ��Ⱦ
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//��������
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//���㷽ʽ

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$this->assign('isSysCode',ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
		$this->assign('isShare',PAYISSHARE);//�Ƿ����÷��÷�̯

		$this->assign('userId',$_SESSION['USER_ID']);

		//��ȡ������˾����
		$this->assign('formBelong',$_SESSION['USER_COM']);
		$this->assign('formBelongName',$_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong',$_SESSION['USER_COM']);
		$this->assign('businessBelongName',$_SESSION['USER_COM_NAME']);

		$this->view ( 'add' );
	}

	/**
	 * ��������
	 */
	function c_toAddDept(){
		$this->showDatadicts ( array ('fundType' => 'KXXZ' ));
		$this->showDatadicts ( array ('signCompanyType' => 'QYDX' ));
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),null,true);

		//����������Ϣ��Ⱦ
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//��������
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//���㷽ʽ

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$this->assign('isSysCode',ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
		$this->assign('isShare',PAYISSHARE);//�Ƿ����÷��÷�̯

		$this->assign('userId',$_SESSION['USER_ID']);

		//��ȡ������˾����
		$this->assign('formBelong',$_SESSION['USER_COM']);
		$this->assign('formBelongName',$_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong',$_SESSION['USER_COM']);
		$this->assign('businessBelongName',$_SESSION['USER_COM_NAME']);

		$this->view ( 'adddept' );
	}

    //���� - �����ⲿ����
    function c_toAddPay(){
        $this->showDatadicts ( array ('fundType' => 'KXXZ' ),'KXXZB');
        $this->assign('fundTypeHidden','KXXZB');
        $this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$_GET['projectType'],true);
        $this->assign('projectTypeHidden',$_GET['projectType']);

        //����������Ϣ��Ⱦ
		$this->showDatadicts ( array ('payFor' => 'FKLX' ),null,false,array('expand1' => 1) );//��������
        $this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//���㷽ʽ

        $this->assign('projectId',$_GET['projectId']);
        $this->assign('projectCode',$_GET['projectCode']);
        $this->assign('projectName',$_GET['projectName']);
        $this->assign('orderMoney',$_GET['orderMoney']);
        $this->assign('deptId',$_SESSION['DEPT_ID']);
        $this->assign('deptName',$_SESSION['DEPT_NAME']);
        $this->assign('principalId',$_SESSION['USER_ID']);
        $this->assign('principalName',$_SESSION['USERNAME']);
        $this->assign('thisDate',day_date);

        $this->assign('isSysCode',ORDERCODE_INPUT);//�Ƿ��ֹ������ͬ��
        $this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('isShare',PAYISSHARE);//�Ƿ����÷��÷�̯

        $this->view ( 'addpay' );
    }

	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//�����ึ������������
						succ_show('controller/contract/other/ewf_forsalarypayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
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
	function c_addDept($isAddInfo = true) {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//�����ึ������������
						succ_show('controller/contract/other/ewf_forsalarypayapplydept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					} 
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
				}
			}else{
				msgGo ( '����ɹ���', '?model=contract_other_other&action=toAddDept' );
			}
		}else{
			msgGo ( '����ʧ�ܣ�', '?model=contract_other_other&action=toAddDept' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
		$object = $_POST [$this->objName];
		$id = $this->service->editInfo_d ( $object);
		if ($id) {
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					if($object ['payapply']['isSalary'] == 1){//�����ึ������������
						succ_show('controller/contract/other/ewf_forsalarypayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'].'&billDept='.$object['payapply']['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}
				}else{
					if($object['fundType'] == 'KXXZB'){
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
					}else{
						succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['deptId'].'&billCompany='.$object['businessBelong']);
					}
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
		$obj['orgFundType'] = $obj['fundType'];

		$this->assignFunc($obj);

		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		$this->assign ( 'fundType', $this->getDataNameByCode ( $obj ['fundType'] ) );

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
		$obj = $this->service->getInfo_d ( $_GET ['id'] );
		$obj['orgFundType'] = $obj['fundType'];
		$this->assignFunc($obj);

		//�ύ������鿴����ʱ���عرհ�ť
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',1);
		}else{
			$this->assign('hideBtn',0);
		}

		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->assign('file1',$this->service->getFilesByObjId ( $obj ['id'], false,'oa_sale_otherpayapply' ));

		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		$this->assign ( 'payFor', $this->getDataNameByCode ( $obj ['payFor'] ) );
		$this->assign ( 'payType', $this->getDataNameByCode ( $obj ['payType'] ) );

		$this->assign('isInvoice',$this->service->rtYesOrNo_d($obj['isInvoice']));

		//ί�и���
		$this->assign('isEntrust',$this->service->rtYesOrNo_d($obj['isEntrust']));
		//�Ƿ����ึ��
		$this->assign('isSalary',$this->service->rtYesOrNo_d($obj['isSalary']));

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->view ( $thisObjCode .'-viewAlong' );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {

			$obj = $this->service->getInfo_d ( $_GET ['id'] );
			$obj['orgFundType'] = $obj['fundType'];
			$this->assignFunc($obj);
			  //�ύ������鿴����ʱ���عرհ�ť
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			//�������{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

			$this->assign ( 'fundType', $this->getDataNameByCode ( $obj ['fundType'] ) );

			$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
			$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

			//ǩ��״̬
			$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

			$this->view ( 'view' );
		} else {
			$obj = $this->service->getInfo_d ( $_GET ['id'] );

			$this->assignFunc($obj);

			//����
		   	$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
			$this->assign('file1',$this->service->getFilesByObjId ( $obj ['id'], true,'oa_sale_otherpayapply' )) ;

			$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$obj ['projectType'],true);
			$this->showDatadicts ( array ('fundType' => 'KXXZ' ), $obj ['fundType'] );

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

			//����������Ϣ��Ⱦ
			$this->showDatadicts ( array ('payFor' => 'FKLX' ),$obj['payFor'],false,array('expand1' => 1) );//��������
			$this->showDatadicts ( array ('payType' => 'CWFKFS' ),$obj['payType']);//���㷽ʽ
			$this->assign('userId',$_SESSION['USER_ID']);
			$this->assign('isShare',PAYISSHARE);//�Ƿ����÷��÷�̯
			$this->assign('payee',$this->getDataNameByCode($obj['payee']));
			$this->assign('comments',$this->getDataNameByCode($obj['comments']));

			$this->view ('edit' );
		}
	}

	/**
	 * ��ͬtabҳ
	 */
	function c_viewTab(){
		$this->assign('id',$_GET['id']);
		$obj = $this->service->get_d ( $_GET ['id'] );
		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->display($thisObjCode . '-viewtab');
	}

	/**
	 * ���·�����
	 */
	function c_toUpdateReturnMoney(){
		//��ȡ��ͬ������Ϣ
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('updatereturnmoney');
	}

	/**
	 * ���·�����
	 */
	function c_updateReturnMoney(){
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if($rs){
			msg('����ɹ�');
		}else{
			msg('����ʧ��');
		}
	}

	/**
	 *��ת�������ҳ��
	 */
	 function c_toStamp(){
	 	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('applyDate',day_date);
// 		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
		$this->assign('file','�����κθ���');

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
       	$this->service->dealAfterAudit_d($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ������ͬ���������
	 */
	function c_dealAfterAuditPayapply(){
       	$this->service->dealAfterAuditPayapply_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��ת����������ͬ
	 */
	 function c_myOther(){
	 	$this->view('mylist');
	 }

	/**
     * �ҵ�������ͬ
     */
    function c_myOtherListPageJson() {
    	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['principalIdAndCreateId'] = $_SESSION['USER_ID'];
		$service->setCompany(0); # �����б�,����Ҫ���й�˾����
		$rows = $service->page_d ('select_financeInfo');
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
	 * ��ת����������ͬ - ״̬�ϲ��б�
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
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���ϵ�� *********************/
	/**
	 * �������ҳ��
	 */
	function c_toChange(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);

		//����
	   	$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('projectType' => 'QTHTXMLX' ),$obj ['projectType'],true);

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//��������

	   	//������������ѡ��
		$datadictDao = new model_system_datadict_datadict ();
		$rs = $datadictDao->find(array('dataCode' => $obj['fundType']),null,'expand1');
		$this->assign('isNeed',$rs['expand1']);

		$this->view('change');
	}

	/**
	 * �������
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$object = $_POST [$this->objName];
		try {
			$id = $this->service->change_d ( $object );
			if($object['fundType'] == 'KXXZB'){
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billDept=' .$object['feeDeptId'].'&billCompany='.$object['businessBelong']);
			}else{
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney'] .'&billCompany='.$object['businessBelong']);
			}
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

		$rs = $this->service->find(array('id' => $newId ),null,'originalId,fundType');
		$this->assign('originalId',$rs['originalId']);

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($rs['fundType']);
		$this->display($thisObjCode . '-changetab');
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

	/******************* S ���뵼������ *******************/
	/**
	 * ��������
	 */
	function c_exportExcel(){
		$service = $this->service;
		$service->getParam ( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = 'c.createTime';
		$rows = $service->listBySqlId ('select_financeInfo');

		return model_contract_common_contractExcelUtil::otherContratOut_e ( $rows );
	}
	//add chenrf
	/**
	 * ��ת����̯��ϸ����ҳ��
	 */
	function c_toPayImportExcel(){
		$this->view('pay-import');
	}
	/**
	 * ��̯��ϸ����ҳ��
	 */
	function c_payImportExcel(){
		$resultArr=$this->service->payImportExcel();
		$title='��̯��ϸ������';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼������ *******************/
	/**
	 * ��֤����
	 */
	function c_canPayapply(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapply_d($id);
		echo $rs;
		exit();
	}

	/**
	 * �˿�������֤
	 */
	function c_canPayapplyBack(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapplyBack_d($id);
		echo $rs;
		exit();
	}
	/**
	 *
	 *ɾ���������ʱ����
	 */
	function c_delTempImport(){
		$this->service->delPayDetail();
	}
	/**
	 * �رպ�ͬ
	 */
	function c_toClose(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('close');
	}
	
	/**
	 * �ر�ʱ�ύ����
	 */
	function c_close(){
		$object = $_POST[$this->objName];
		$id = $this->service->edit_d($object);
		if ($id) {
			succ_show('controller/contract/other/ewf_close.php?actTo=ewfSelect&billId='.$object['id']);
		}else{
			msg('����ʧ��');
		}
	}
}