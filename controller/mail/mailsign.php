<?php
/**
 * 邮寄签收控制层类
 */
class controller_mail_mailsign extends controller_base_action {

	function __construct() {
		$this->objName = "mailsign";
		$this->objPath = "mail";
		parent::__construct ();
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);
		$mailObjInfo = $this->service->getInvoiceInfo($_GET['docId']);
		unset($mailObjInfo['id']);
		$this->assignFunc($mailObjInfo);

		$mailmanArr = $this->service->getMailman($mailObjInfo);

		$this->assign('salesmanId',$mailmanArr[0]);
		$this->assign('salesman',$mailmanArr[1]);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/**
	 * 初始化对象
	 */
	function c_init() {
		//$returnObj = $this->objName;
		$obj = $this->service->get_d ( $_GET ['id'] );
		//附件
		$obj['file'] = $this->service->getFilesByObjNo($obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$deptName=$this->service->getDeptByUserId($_SESSION['USER_ID']);
		if($deptName=="财务部"){
			$service->searchArr['invoiceapply']="invoiceapply";
		}else{
			$service->searchArr['notInvoiceapply']="invoiceapply";
		}
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 初始化对象
	 */
	function c_read() {
		$condition = array( 'mailinfoId'=>$_GET['docId'] );
		$obj = $this->service->find ( $condition );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display('read');
	}


}
?>