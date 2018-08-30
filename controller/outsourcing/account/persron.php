<?php
/**
 * @author Administrator
 * @Date 2013年12月15日 星期日 22:23:01
 * @version 1.0
 * @description:外包结算人员租赁控制层
 */
class controller_outsourcing_account_persron extends controller_base_action {

	function __construct() {
		$this->objName = "persron";
		$this->objPath = "outsourcing_account";
		parent::__construct ();
	 }

	/**
	 * 跳转到外包结算人员租赁列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增外包结算人员租赁页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑外包结算人员租赁页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看外包结算人员租赁页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

   	/**
	 * 获取所有数据返回json
	 */
	function c_accountListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$approvalId=$_POST['approvalId'];
		$verifyIds=$_POST['verifyIds'];
		$rows = $service->accountListJson_d ($approvalId,$verifyIds);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

 }
?>