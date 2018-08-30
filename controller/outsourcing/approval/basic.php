<?php
/**
 * @author Administrator
 * @Date 2013��11��19�� ���ڶ� 23:55:18
 * @version 1.0
 * @description:���������Ʋ� ��ͬ״̬
                        0.δ�ύ
                        1.������
                        2.ִ����
                        3.�ѹر�
                        4.�����
 */
class controller_outsourcing_approval_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "outsourcing_approval";
		parent::__construct ();
	 }

	/**
	 * ��ת����������б�
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * ��ת����������б�(����)
	 */
    function c_toMyList() {
    	$this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
    	$this->assign ('userId', $_SESSION['USER_ID'] );
        $this->view('my-list');
    }

   /**
	 * ��ת�������������ҳ��
	 */
	function c_toAdd() {
		$this->view ('add' ,true);
	}

    /**
	 * ��ת�������������ҳ��
	 */
	function c_toAddByApply() {
		$applyId=isset($_GET ['applyId'])?$_GET ['applyId']:'';
		$applyDao=new model_outsourcing_outsourcing_apply();//��ȡ���������Ϣ
		$applyRow=$applyDao->get_d($applyId);
		foreach ( $applyRow as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$projectDao=new model_engineering_project_esmproject();//��ȡ��Ŀ��Ϣ
		$projectRow=$projectDao->get_d($applyRow['projectId']);

		if(is_array($projectRow)){
			$this->assign ('nature', $projectRow['nature'] );
			$this->assign ('natureName', $projectRow['natureName'] );
			$this->assign ('managerId', $projectRow['managerId'] );
			$this->assign ('managerName', $projectRow['managerName'] );
			$this->assign ('salesmanId', $projectRow['salesmanId'] );
			$this->assign ('salesman', $projectRow['salesman'] );
			$this->assign ('contractMoney', $projectRow['contractMoney'] );
		}else{
			$this->assign ('nature', '');
			$this->assign ('natureName', '' );
			$this->assign ('managerId', '' );
			$this->assign ('managerName', '' );
			$this->assign ('salesmanId', '' );
			$this->assign ('salesman', '' );
			$this->assign ('contractMoney', '' );
		}
		switch($applyRow['outType']){
			case '1':$this->assign ('outsourcing', 'HTWBFS-01');$this->assign ('outsourcingName', '����');break;
			case '2':$this->assign ('outsourcing', 'HTWBFS-03');$this->assign ('outsourcingName', '�ְ�');break;
			case '3':$this->assign ('outsourcing', 'HTWBFS-02');$this->assign ('outsourcingName', '��Ա����');break;
			default:$this->assign ('outsourcing', '');$this->assign ('outsourcingName', '');break;
		}
		$esmbudgetDao=new model_engineering_budget_esmbudget();//��ȡ��ĿԤ����Ϣ
		$esmbudgetRows=$esmbudgetDao->getAllBudgetDetail_d($applyRow['projectId']);

		$this->showDatadicts ( array ('payType' => 'HTFKFS' ) );//���ʽ
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ) ); //˰��
        $this->assign ( 'datenow', date("Y-m-d") );
        $this->assign ( 'usernow', $_SESSION['USERNAME'] );
        $this->assign ( 'userId', $_SESSION['USER_ID'] );
        $this->view ('add' ,true);
   }

   /**
	 * ��ת���༭�������ҳ��
	 */
	function c_toEdit() {
//   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//���ʽ
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //˰��
        $this->assign ( 'datenow', date("Y-m-d") );
		$this->view ('edit' ,true);
	}

   /**
	 * ��ת���༭�������ҳ��
	 */
	function c_toChange() {
//   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//���ʽ
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //˰��
        $this->assign ( 'datenow', date("Y-m-d") );
      	$this->view ('change' ,true);
   }

   	/**
	 * ����鿴tab
	 */
	function c_changeTab(){
//		$this->permCheck (); //��ȫУ��
		$newId = $_GET ['id'];
		$this->assign('id',$newId);

		$rs = $this->service->find(array('id' => $newId ),null,'originalId');
		$this->assign('originalId',$rs['originalId']);

		$this->display('changetab');
	}


   /**
	 * ��ת���鿴�������ҳ��
	 */
	function c_toView() {
//      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������ʾ����
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType );
		$this->view ( 'view' );
	}
      /**
	 * ��ת���鿴�������ҳ��
	 */
	function c_toViewTab() {
//      $this->permCheck (); //��ȫУ��
		$this->assign('id',$_GET ['id'] );
		$this->view ( 'view-tab' );
	}

      /**
	 * ��ת���鿴�������ҳ��
	 */
	function c_toChangeView() {
//      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������ʾ����
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType );
		$this->view ( 'change-view' );
	}

   /**
    * ��������¼�����ת���б�ҳ
    */
	function c_add(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' . $applyId);
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			msg ( '����ʧ�ܣ�' );
		}
   }

  /**
    * �༭����ת���б�ҳ
    */
	function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			msg ( '����ʧ�ܣ�' );
		}
   }

   	/**
	 * �������
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		try {
			$id = $this->service->change_d ( $_POST [$this->objName] );

			succ_show("controller/outsourcing/approval/ewf_change.php?actTo=ewfSelect&billId=" . $id);

		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}

	/**
	 * ���������ɺ���
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAuditChange_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/*
	 * �����������ͨ������
	 */
	 function c_dealAfterAuditPass() {
	 	if (! empty ( $_GET ['spid'] )) {
	 		//�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	 }
	 /**
	 * ��ȡ�����������������б�
	 */
	function c_pullPage() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->page_d ('select_pull');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>