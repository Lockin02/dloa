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
        $_POST['session_uid'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

//		$rs = $this->service->getStampTypeList_d();
//		if(!empty($rs)){
//			$service->searchArr['stampTypes'] = implode(',',$rs);
//
//			//$service->asc = false;
//			$rows = $service->page_d ();
//			//���ݼ��밲ȫ��
//			$rows = $this->sconfig->md5Rows ( $rows );
//		}

        // ���˺�ͬ�����б���Ϣ��ʾ��ȫ�����޸� 2017-01-05 huanghaojin
        $rows = $service->listBySqlId('select_list1');
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
	 * ���¼�¼
	 */
	function c_listrecords(){
		$this->view ( 'listrecords' );
	}

    /**
     *  �ҵĸ�������
     */
    function c_myList(){
        $this->view ( 'listmy' );
    }

    /**
     * �ҵĸ�������json
     */
    function c_myPageJson(){
        $service = $this->service;
        $rows = array();

        $_POST['applyUserId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST );

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode ( $arr );
    }
    /******************** ��ɾ�Ĳ� ******************/

	/**
	 * ��ת���������¼�¼ҳ��
	 */
	function c_toAdd() {
        $initArr = array(
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'deptId' => $_SESSION['DEPT_ID'],
            'deptName' => $_SESSION['DEPT_NAME'],
            'applyDate' => day_date
        );
        $this->assignFunc($initArr);
        $this->showDatadicts ( array ('stampExecution' => 'HTGZXZ'),null,true);
        $this->showDatadicts ( array ('contractType' => 'HTGZYD'),null,true);
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
        if($obj['contractType'] != 'HTGZYD-TB'){
            $newClass = $this->service->getClass($obj['contractType']);
            $initObj = new $newClass();
            $rs = $this->service->initStamp_d($obj,$initObj);
        }

		$contractTypeCN =  $this->getDataNameByCode ( $obj ['contractType']) ;
		//������Ⱦ
// 		$this->assign ( 'contractTypeCN',$contractTypeCN );

		//��չҳ�����
// 		$this->assignFunc($rs);
// 		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expandview');
		//ȷ��ҳ�����
		$this->assignFunc($obj);

//		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );
		$contractType =  $this->service->getType($obj['contractType']);
		$skey = $this->md5Row($obj['contractId'],$contractType);
		switch ($obj['contractType']){
			case 'HTGZYD-01' : 
				$url = '?model=contract_outsourcing_outsourcing&action=init&perm=view&actType=audit&viewBtn=0&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-02' :
				$url = '?model=contract_other_other&action=viewAlong&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-03' : 
				$url = '?model=purchase_contract_purchasecontract&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-04' :
				$url = '?model=contract_contract_contract&action=init&perm=view&actType=audit&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-05' :
				$url = '?model=contract_stamp_stampapply&action=toView&hideBtn=1&id='.$obj['applyId'].'&skey='.$skey;break;
			case 'HTGZYD-06' :
				$url = '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-07' :
				$url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
            case 'HTGZYD-TB':// PMS 557 ������һ��������롾Ͷ���걨��,ֻ����OA�ӿڻ�����,��������������OA��Դ����Ϣ
                $url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id=122&skey='.$skey;
                break;
		}
		$this->assign('stampUrl', $url);
// 		print_r($url);die();
		$this->assign ( 'contractTypeCN', $contractTypeCN );
		$this->assign( 'status' , $this->service->rtStampType_d($obj['status']));

        $stampApplyDao = new model_contract_stamp_stampapply();
        $stampapplyData = $stampApplyDao->get_d($obj['applyId']);
        $this->assign('stampCompany',$stampapplyData['stampCompany']);

		$this->view ( 'view' );
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
// 		$this->assignFunc($rs);
// 		$this->view( $this->service->getBusinessCode($obj['contractType']) .'-expand');

		$contractType =  $this->service->getType($obj['contractType']);
		$skey = $this->md5Row($obj['contractId'],$contractType);
		switch ($obj['contractType']){
			case 'HTGZYD-01' :
				$url = '?model=contract_outsourcing_outsourcing&action=init&perm=view&actType=audit&viewBtn=0&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-02' :
				$url = '?model=contract_other_other&action=viewAlong&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-03' :
				$url = '?model=purchase_contract_purchasecontract&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-04' :
				$url = '?model=contract_contract_contract&action=init&perm=view&actType=audit&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-05' :
				$url = '?model=contract_stamp_stampapply&action=toView&hideBtn=1&id='.$obj['applyId'].'&skey='.$skey;break;
			case 'HTGZYD-06' :
				$url = '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;break;
			case 'HTGZYD-07' : 
				$url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id='.$obj['contractId'].'&skey='.$skey;
                break;
            case 'HTGZYD-TB':// PMS 557 ������һ��������롾Ͷ���걨��,ֻ����OA�ӿڻ�����,��������������OA��Դ����Ϣ
                $url = '?model=outsourcing_contract_rentcar&action=toView&hideBtn=1&id=122&skey='.$skey;
                break;
		}
		$this->assign('stampUrl', $url);

		//ȷ��ҳ�����
		$this->assignFunc($obj);
		//�����ֵ���Ⱦ
//		$this->showDatadicts ( array ('stampType' => 'HTGZ' ), $obj ['stampType'] ,false);

		//���ø�������
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//��������

		$this->view( 'confirmstamp');
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
	/**
	 *
	 * ����ͬ�Ƿ���ڸ�������(δ����)
	 */
	function c_checkStamp(){
		$re=$this->service->checkStamp($_POST);
		if($re){
			echo 1;             //����
		}else
			echo 0;            //������
	}

    /**
     * ��д��ȡ������Ϣ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

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
        $arr ['listSql'] = $service->listSql;
        // ��¼������������ created by huanghaojin (���ڵ���ʱ��������������)
        $search_Array = $service->searchArr;
        $search_Array['page'] = $_REQUEST['page'];
        $search_Array['pageSize'] = $_REQUEST['pageSize'];
        unset($search_Array['isSearchTag_']);
        $_SESSION['searchArr'] = $search_Array;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * ������ͬ���¼�¼��ϢEXCEL created by huanghaojin 2016-10-09
     */
    function c_toExportExcel(){
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(600);

        $exportType = isset($_REQUEST['exportType'])? $_REQUEST['exportType'] : '';
        unset($_REQUEST['exportType']);
        $colIdStr = $_REQUEST['colId'];
        $colNameStr = $_REQUEST['colName'];
        unset($_REQUEST['colId']);
        unset($_REQUEST['colName']);

        // ����¼��session�ڵ����������ֶμ��봫���������(���ڵ���ʱ��������������)
        if(is_array($_SESSION['searchArr'])){
            foreach ($_SESSION['searchArr'] as $k => $v) {
                if(!isset($_REQUEST[$k])){
                    $_REQUEST[$k] = $v;
                }
            }
        }
        $service->getParam($_REQUEST);

        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);
        $rows = array();
        if($exportType == "getAll"){//�������������ѯ����������
            $rows = $service->listBySqlId('select_default');
        }

        $datadictDao = new model_system_datadict_datadict ();
        if(is_array($rows) && !empty($rows)){
            //ƥ�䵼����
            $dataArr = array ();
            $colIdArr = array_flip($colIdArr);
            foreach ($rows as $key => $row) {
                foreach ($colIdArr as $index => $val) {
                    if($index == 'contractType'){
                        $colIdArr[$index] = $datadictDao->getNameByCode('HTGZYD',$row[$index]);
                    }else if($index == 'status'){
                        switch ($row[$index]){
                            case '1':
                                $colIdArr[$index] = '�Ѹ���';
                                break;
                            case '2':
                                $colIdArr[$index] = '�ѹر�';
                                break;
                            default:
                                $colIdArr[$index] = 'δ����';
                                break;
                        }
                    }else{
                        $colIdArr[$index] = $row[$index];
                    }
                }
                array_push($dataArr, $colIdArr);
            }
            return model_contract_stamp_stampExportUtil::contractStampExcel($colArr, $dataArr);
        }else{return false;}

    }
}