<?php

/**
 * @author Show
 * @Date 2011年1月17日 星期一 11:05:31
 * @version 1.0
 * @description:仓库锁定记录表控制层
 */
class controller_stock_lock_lock extends controller_base_action {

	function __construct() {
		$this->objName = "lock";
		$this->objPath = "stock_lock";
		parent::__construct ();
	}

	/*
	 * 跳转到仓库锁定记录表
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 查看某个设备的锁定记录
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
//		$this->display ( 'locklist' ); // 原合同锁定记录

        $this->display ( 'lockrecord' ); // 先订单锁定记录
	}
	/**
	 * 获取分页数据转成Json
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
		$service->getParam ( $_POST ); //设置前台获取的参数信息
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
     * 查看设备已锁定总数
     */
    function c_lockAmount() {

    	$this->show->assign('id' , $_GET['id']);
    	$this->show->assign('objCode' , $_GET['objCode']);
    	$this->display ( 'lockamount' );
    }
	/**
	 * 批量锁定库存
	 */
	function c_batchAdd() {
		$lock = $_POST ['lock'];
		if ($this->service->stockLock_d ( $lock )) {
			msgGo ( '锁定成功!' );
		} else {
			msgGo ( '锁定失败，请检查库存是否有产品信息或者锁定数量是否大于可执行数量！' );
		}
	}

	/**
	 * ajax批量解锁库存
	 */
	function c_lockAjax() {
//		if(!$this->service->this_limit['解锁']){
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
	 * 获取设备在某个仓库下的已经锁定数量
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
	 * 跳转合同锁定页面
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
//		//拿到默认仓库的信息(调用仓库接口)
		$stockInfoDao = new model_stock_stockinfo_systeminfo();
		$stockInfo = $stockInfoDao->getStockByType_d($docType);
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['stockId']);
		$this->assign('stockCode', $stockInfo['stockCode']);
		$this->display('lockstock');

	}

	/**
	 * 锁存量
	 */
	 function c_readLockNum(){
	 	$this->assign( 'productId',$_GET['productId'] );
	 	$this->assign( 'stockId',$_GET['stockId'] );
	 	$this->display( 'locknumlist' );
	 }
	/**
	 * 获取分页数据转成Json
	 */
	function c_locknumJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows=$service->getPageLockLog();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = count($rows);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>