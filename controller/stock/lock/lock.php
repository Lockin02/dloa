<?php

/**
 * @author Show
 * @Date 2011��1��17�� ����һ 11:05:31
 * @version 1.0
 * @description:�ֿ�������¼����Ʋ�
 */
class controller_stock_lock_lock extends controller_base_action {

	function __construct() {
		$this->objName = "lock";
		$this->objPath = "stock_lock";
		parent::__construct ();
	}

	/*
	 * ��ת���ֿ�������¼��
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * �鿴ĳ���豸��������¼
	 */
	function c_lockRecords() {
	 	$docName=$this->service->docArr[$_GET['objType']];
		$this->permCheck($_GET['id'],$docName);
        $this->show->assign('id' , $_GET['id']);
        $this->show->assign('objType' , $_GET['objType']);
        $this->show->assign('objCode' , $_GET['objCode']);
        $this->show->assign('equId' , $_GET['equId']);
	    $this->show->assign('stockId' , $_GET['stockId']);
//		if (! empty ( $_GET ['objCode'] )) {
//			$rows = $this->service->lockRecordsByobjCode ( $_GET ['objCode'], $_GET ['objType'] );
//		} else {
//			$rows = $this->service->lockRecordsByEquId ( $_GET ['equId'], $_GET ['stockId'] );
//		}
//		$this->assign ( 'list', $this->service->showLock ( $rows ) );
//		$this->display ( 'locklist' ); // ԭ��ͬ������¼

        $this->display ( 'lockrecord' ); // �ȶ���������¼
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_lockPageJson() {
		$service = $this->service;

       if (empty ($_POST['objId'])){
       	   unset ($_POST['objId']);
       }
       if(empty ($_POST['stockId'])){
       		unset ($_POST['stockId']);
       }
       if(empty ($_POST['objEquId'])){
       		unset ($_POST['objEquId']);
       }
       if(empty ($_POST['objType'])){
       		unset ($_POST['objType']);
       }
		$service->searchArr = array(
			'objId' => $_POST['objId'],
			'objType' => $_POST['objType']
		);
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['objType'] = $_POST['objType'];
//		$service->searchArr['objId'] = $_POST['objId'];
		$rows = $service->pageBySqlId("select_default");

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
     /**
     * �鿴�豸����������
     */
    function c_lockAmount() {

    	$this->show->assign('id' , $_GET['id']);
    	$this->show->assign('objCode' , $_GET['objCode']);
    	$this->display ( 'lockamount' );
    }
	/**
	 * �����������
	 */
	function c_batchAdd() {
		$lock = $_POST ['lock'];
		if ($this->service->stockLock_d ( $lock )) {
			msgGo ( '�����ɹ�!' );
		} else {
			msgGo ( '����ʧ�ܣ��������Ƿ��в�Ʒ��Ϣ�������������Ƿ���ڿ�ִ��������' );
		}
	}

	/**
	 * ajax�����������
	 */
	function c_lockAjax() {
//		if(!$this->service->this_limit['����']){
//			echo 0;
//			exit();
//		}
		$lock = $_POST ['lock'];
		$lock=util_jsonUtil::iconvUTF2GBArr($lock);
		$rows=array();
		$sourceObj=array();

		$rows['stockId']=$lock['stockId'];
		$rows['productId']=$lock['productId'];
		$sourceObj['docId']=$lock['objId'];
		$sourceObj['docType']=$lock['objType'];
		$sourceObj['docCode']=$lock['objCode'];
		$sourceObj['rObjCode']=$lock['rObjCode'];
		$lockNum=$lock['lockNum'];

		$this->service->releaseLockByEqu ( $rows,$sourceObj,$lockNum*-1 );
		echo 1;
	}
	/*
	 * ��ȡ�豸��ĳ���ֿ��µ��Ѿ���������
	 */
	function c_getEqusStockLockNum() {
		$docType = $_POST ['docType'];
		$equIds = $_POST ['equIds'];
		$equIdArr = explode ( ",", $equIds );
		$stockId = $_POST ['stockId'];
		$arr = $this->service->getEqusStockLockNumArr ( $equIdArr, $stockId,$docType );
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ת��ͬ����ҳ��
	 */
	function c_toLokStock() {
		$service = $this->service;
		$docType = $_GET['objType'];
		$docName = $service->docArr[$docType];
		//$this->permCheck($_GET['id'],$docName);
		$docModel=$service->docModelArr[$docType];
		$docDao = new $docModel();
		$rows = $docDao->get_d($_GET['id']);
		$rows = $docDao->showDetaiInfo($rows);
		$this->assign("id", $_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('objCode', $_GET['objCode']);
		$this->assign('objType', $_GET['objType']);
//		//�õ�Ĭ�ϲֿ����Ϣ(���òֿ�ӿ�)
		$stockInfoDao = new model_stock_stockinfo_systeminfo();
		$stockInfo = $stockInfoDao->getStockByType_d($docType);
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['stockId']);
		$this->assign('stockCode', $stockInfo['stockCode']);
		$this->display('lockstock');

	}

	/**
	 * ������
	 */
	 function c_readLockNum(){
	 	$this->assign( 'productId',$_GET['productId'] );
	 	$this->assign( 'stockId',$_GET['stockId'] );
	 	$this->display( 'locknumlist' );
	 }
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_locknumJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows=$service->getPageLockLog();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = count($rows);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>