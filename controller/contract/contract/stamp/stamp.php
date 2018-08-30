<?php
/**
 * @author Show
 * @Date 2011��12��16�� ������ 11:24:28
 * @version 1.0
 * @description:���¼�¼���Ʋ�
 */
class controller_contract_stamp_stamp extends controller_base_action {

	function __construct() {
		$this->objName = "stamp";
		$this->objPath = "contract_stamp";
		parent::__construct ();
	}

	/*
	 * ��ת�����¼�¼�б�
	 */
    function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonForStampType() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_POST );

		$rs = $this->service->getStampTypeList_d();
		if(is_array($rs)){
			$service->searchArr['stampTypes'] = implode($rs,',');

			//$service->asc = false;
			$rows = $service->page_d ();
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

     /**
	 * ���¼�¼
	 */
	function c_listrecords(){
		$this->view ( 'listrecords' );
	}

	/**
	 * ��ת���������¼�¼ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���¼�¼ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//���ø�������
        $stampConfigDao = new model_system_stamp_stampconfig();
        $stampArr = $stampConfigDao->getStampType_d();
        $this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//��������

		$this->view ( 'edit');
	}

    /**
     * �޸Ķ���
     */
    function c_editWithBusiness() {
//      $this->permCheck (); //��ȫУ��
        $object = $_POST [$this->objName];
        if ($this->service->editWithBusiness_d ( $object)) {
            msg ( '�༭�ɹ���' );
        }
    }

	/**
	 * ��ת���鿴���¼�¼ҳ��
	 */
	function c_toView() {
		$id = $_GET['id'];

		//���ø��»�����Ϣ
		$obj = $this->service->get_d($id);

		//��ȡ����Դ����Ϣ����Ⱦ
		$newClass = $this->service->getClass($obj['contractType']);
		$initObj = new $newClass();
		$rs = $this->service->initStamp_d($obj,$initObj);

		$contractTypeCN =  $this->getDataNameByCode ( $obj ['contractType']) ;
		//������Ⱦ
		$this->assign ( 'contractTypeCN',$contractTypeCN );

		//��չҳ�����
		$this->assignFunc($rs);
		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expandview');
		//ȷ��ҳ�����
		$this->assignFunc($obj);

//		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
		$this->assign ( 'contractTypeCN', $contractTypeCN );
		$this->assign( 'status' , $this->service->rtStampType_d($obj['status']));
		$this->display ( 'view' );
	}

	/**
	 * ����ȷ��ҳ��
	 */
	function c_toConfirmStamp(){
		$id = $_GET['id'];

		//���ø��»�����Ϣ
		$obj = $this->service->get_d($id);

		//��ȡ����Դ����Ϣ����Ⱦ
		$newClass = $this->service->getClass($obj['contractType']);
		$initObj = new $newClass();
		$rs = $this->service->initStamp_d($obj,$initObj);

		//������Ⱦ
		$this->assign ( 'contractTypeCN', $this->getDataNameByCode ( $obj ['contractType'] ) );

		//��չҳ�����
		$this->assignFunc($rs);
		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expand');

		//ȷ��ҳ�����
		$this->assignFunc($obj);
		//�����ֵ���Ⱦ
//		$this->showDatadicts ( array ('stampType' => 'HTGZ' ), $obj ['stampType'] ,false);

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//��������

		$this->display( 'confirmstamp');
	}

	/**
	 * ����ȷ�� - ��
	 */
	function c_confirmStamp(){
     	if($this->service->confirmStamp_d ( $_POST[$this->objName] )){
			msgRf('ȷ�ϳɹ�');
     	}else{
     		msgRf('ȷ��ʧ��');
     	}
	}

    /**
     * ȷ�ϸ��²��� - �첽
     */
     function c_confirmedSealed(){
     	if($this->service->confirmedSealed_d ( $_POST['id'] )){
			msgRf('ȷ�ϳɹ�');
     	}else{
     		msgRf('ȷ��ʧ��');
     	}
     }

     /**
	 * �鿴Tab�в鿴���¼�¼
	 * �����Ǵ�tabҳ�������ĺ�ͬid�ͺ�ͬ����
	 */
	function c_viewForContract(){
		$this->assignFunc($_GET);
        $this->assign('userId',$_SESSION['USER_ID']);
		$this->view ( 'listforcontract' );
	}
    /**
     * ҳ����ʾ
     */
    function c_toViewOnly(){
        $conditions = array(
            "id" => $_GET ['id']
        );
        $obj= $this->service->find($conditions);
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }
        $this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
        $this->assign ( 'contractType', $this->getDataNameByCode ( $obj ['contractType'] ) );
        $this->assign ( 'status', $this->service->rtStampType_d ( $obj ['status'] ) );

        $this->view ( 'viewonly' );
    }

	/**
	 * ��������
	 */
	function c_batchStamp(){
	 	$arr = $_POST['rowIds'];
	 	if($this->service->batchStamp_d( $arr )){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 	exit();
	}

	/**
	 * ��������Ƿ��Ѹ���
	 */
	function c_isStamped(){
		if($this->service->find(array('contractId' => $_POST['contractId'],'contractType' => $_POST['contractType']),'id')){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * �������Ƿ������ͬ����
	 */
	function c_checkRepeat(){
		$this->service->getParam($_POST);
		$rs = $this->service->list_d();
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * �رո��¹���
	 */
	function c_close(){
		$id = $_POST['id'];
		if($this->service->close_d($id)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>