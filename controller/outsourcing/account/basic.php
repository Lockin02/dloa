<?php
/**
 * @author Administrator
 * @Date 2013��12��15�� ������ 22:23:01
 * @version 1.0
 * @description:���������Ʋ� ��ͬ״̬
                        0.δ�ύ
                        1.������
                        2.ִ����
                        3.�ѹر�
                        4.�����
 */
class controller_outsourcing_account_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "outsourcing_account";
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
    	$this->assign ('userId', $_SESSION['USER_ID'] );
        $this->view('my-list');
    }

	/**
	 * ��ת���������ȷ���б�
	 */
    function c_toAffirmList() {
        $this->view('affirm-list');
    }

   /**
	 * ��ת�������������ҳ��
	 */
	function c_toAdd() {
		$applyId=isset($_GET ['appId'])?$_GET ['appId']:'';
		$applyDao=new model_outsourcing_approval_basic();//��ȡ���������Ϣ
		$applyRow=$applyDao->get_d($applyId);
		foreach ( $applyRow as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$projectDao=new model_engineering_project_esmproject();//��ȡ��Ŀ��Ϣ
		$projectRow=$projectDao->get_d($applyRow['projectId']);
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$applyRow['payType'] );//���ʽ
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$applyRow['taxPointCode'] ); //˰��
        $this->assign ( 'datenow', date("Y-m-d") );
        $this->assign ( 'usernow', $_SESSION['USERNAME'] );
        $this->assign ( 'userId', $_SESSION['USER_ID'] );
        $this->view ( 'add' );
   }

   /**
	 * ��ת���������Ӧ�̹����������������ҳ��
	 */
	function c_toSuppVerifyAdd() {
		$suppVerifyId = isset($_GET ['suppVerifyId'])?$_GET ['suppVerifyId']:'';
		$suppVerifyDao = new model_outsourcing_workverify_suppVerify();//��ȡ�����Ӧ�̹�������Ϣ
		$suppVerifyRow = $suppVerifyDao->get_d($suppVerifyId);
		foreach ( $suppVerifyRow as $key => $val ) {
			$this->assign ($key ,$val);
		}

		$verifyDetailDao = new model_outsourcing_workverify_verifyDetail();//��ȡ��������ϸ��Ϣ
		$verifyDetailRow = $verifyDetailDao->findAll(array('suppVerifyId'=>$suppVerifyId) ,null ,'projectId');
		$proIds = '';
		foreach ( $verifyDetailRow as $key => $val ) {
			$proIds .= $val['projectId'].',';
		}
		$this->assign ('proIds' ,substr($proIds ,0 ,-1));

		$this->showDatadicts (array('payType' => 'HTFKFS'));//���ʽ
		$this->showDatadicts (array('taxPointCode' => 'WBZZSD')); //˰��

		$this->view ( 'supp-add' );
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
		$this->view ( 'edit');
	}

   /**
	 * ��ת���༭�������ҳ��
	 */
	function c_toAffirm() {
//		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ),$obj['payType'] );//���ʽ
		$this->showDatadicts ( array ('taxPointCode' => 'WBZZSD' ),$obj['taxPointCode'] ); //˰��
		$this->view ( 'affirm');
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
    * ��������¼�����ת���б�ҳ
    */
	function c_add(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $applyId);
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	* �ӹ�������������¼�
	*/
	function c_suppVerifyAdd(){
		$_POST['basic']['state'] = isset ($_GET['isSubmit']) ? 1 : 0;

		$applyId = $this->service->add_d($_POST['basic']);
		if($applyId){
			msg ( '����ɹ���' );
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ajax�ύ
	 */
	function c_ajaxSubmit() {
		try {
			$this->service->update(array('id'=>$_POST['id']) ,array('state'=>'1'));
			$this->service->sendMailAffirm_d($_POST['id']);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

     /**
    * �༭����ת���б�ҳ
    */
	function c_edit(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

    /**
    * �������ȷ�ϱ༭����ת���б�ҳ
    */
	function c_affirmEdit(){
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$_POST['basic']['state'] = 2;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			if($actType!=''){
				succ_show('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['basic']['id']);
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	* �ӹ������༭
	*/
	function c_suppVerifyEdit(){
		$_POST['basic']['state'] = isset ($_GET['isSubmit']) ? 1 : 0;

		$applyId = $this->service->edit_d($_POST['basic']);
		if($applyId){
			msg ( '����ɹ���' );
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ajax��ʽɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxdeletes() {
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

   /**
    * ��������--��ȡ��������Ľ��
    */
	function c_getCanApply(){
		$id = $_POST['id'];
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();
		$applyMoney = $payablesapplyDao -> getApplyMoneyByPur_d($id,'YFRK-05');
		$orderMoney = $this->service->find(array('id'=>$id),null,'orderMoney');
   		$canApply = bcsub($orderMoney['orderMoney'],$applyMoney,2);
   		echo $canApply;
	}
	/*
	 * ��ת������������ɸѡ����ҳ��
	 */
	function c_toExportOut(){
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ));
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ));
		$this->showDatadicts ( array ('projectType' => 'GCXMXZ' ));
		$this->view("exportOut");
	}
	/*
	 * ���������ܵ���
	 */
	 function c_exportOut(){
 		set_time_limit(0);
	   	$service = $this->service;
	 	$formData = $_POST[$this->objName];
	 	$service->searchArr['ExaStatusArr'] = '��������,���,���';
	 	if(trim($formData['formCode'])){ //���ݱ��
			$service->searchArr['formCode'] = trim($formData['formCode']);
	 	}
	 	if(trim($formData['approvalCode'])){ //���������
			$service->searchArr['approvalCode'] = trim($formData['approvalCode']);
	 	}
	 	if(trim($formData['projectCode'])){ //��Ŀ���
			$service->searchArr['projectCode'] = trim($formData['projectCode']);
	 	}
	 	if(trim($formData['projectName'])){ //��Ŀ����
			$service->searchArr['projectName'] = trim($formData['projectName']);
	 	}
	 	if(trim($formData['outsourcing'])){ //�����ʽ
			$service->searchArr['outsourcing'] = trim($formData['outsourcing']);
	 	}
	 	if(trim($formData['outContractCode'])){ //������
			$service->searchArr['outContractCode'] = trim($formData['outContractCode']);
	 	}
	 	if(trim($formData['suppName'])){ //�����Ӧ��
			$service->searchArr['suppName'] = trim($formData['suppName']);
	 	}
	 	if(trim($formData['projectType'])){ //��Ŀ����
			$service->searchArr['projectType'] = trim($formData['projectType']);
	 	}
	 	if(trim($formData['saleManangerName'])){ //���۸�����
			$service->searchArr['saleManangerName'] = trim($formData['saleManangerName']);
	 	}
	 	if(trim($formData['projectManangerName'])){ //��Ŀ����
			$service->searchArr['projectManangerName'] = trim($formData['projectManangerName']);
	 	}
	 	if(trim($formData['payType'])){ //���ʽ
			$service->searchArr['payType'] = trim($formData['payType']);
	 	}
	 	if(trim($formData['ExaStatus'])){ //����״̬
			$service->searchArr['ExaStatus'] = trim($formData['ExaStatus']);
	 	}
	 	$rows = $service->list_d('select_excel');
	 	if(!$rows){
	 		msg("û����ؼ�¼��");
	 		return 0;
	 	}
	 	for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array(
		);
		$modelName = '������㵼��ģ��';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr,$rows, $modelName);

	 }
 }
?>