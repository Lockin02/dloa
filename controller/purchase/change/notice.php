<?php
/**
 * @description: 采购变更通知
 * @date 2010-12-24 上午10:00:20
 * @author chengl
 * @version V1.0
 */
class controller_purchase_change_notice extends controller_base_action {

	function __construct() {
		$this->objName = 'notice';
		$this->objPath = 'purchase_change';
		parent::__construct ();
	}

	/*****************************************显示分割线********************************************/

	/**
	 * 跳转到变更通知列表
	 */
	function c_toChangeList() {
		$this->display ( "list" );
	}

	/**
	 * 接收变更通知
	 */
	function c_receive() {
		$this->service->receive_d ( $_GET ['id'] );
		echo 1;
	}

	/*
	 * @desription 跳转到任务变更通知列表
	 * @author qian
	 * @date 2011-1-12 上午11:07:33
	 */
	function c_toChangeTaskList () {
		$this->display("list-task");
	}

	/**
	 * 跳转到我的采购合同变更通知列表
	 * @return return_type
	 */
	function c_myChangeList () {
		$this->display("list-mycontract");
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$returnObj = $this->objName;
		$$returnObj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $$returnObj as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		//		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			//TODO:获取全部下属表单数据
			$this->service->getChildArr_d( $_GET['id'] );
			$this->display (  'view' );
		} else {
			$this->display ( 'edit' );
		}
	}


	/*****************************************Ajax与JSON方法*****************************************/
	/**
	 * 我的变更--过滤登陆用户
	 * 此方法一般用于显示“个人”的计划、任务、合同变更列表页
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_myChangeJSON () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['updateId'] = $_SESSION['USER_ID'];
//		$rows = $service->page_d ();
		$rows = $service->pageBySqlId("list");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @descritpion 过滤出采购计划
	 * @author qian
	 * @date 2011-2-25-10:20
	 */
	function c_pageJsonPlan(){
		$service = $this->service;
		$service->getParam($_POST);	//设置前台获取的参数信息
		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$service->searchArr['modelCode'] = "plan";
		$rows = $service->pageBySqlId("list");
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode( $arr );
	}


	/**
	 * @description 过滤出任务与计划的变更。此方法是任务的变更列表过滤。
	 * @author qian
	 * @date 2011-2-25 10:04
	 */
	function c_pageJsonTask(){
		$service = $this->service;
		$service->getParam($_POST);	//设置前台获取的参数信息
//		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$service->searchArr['modelCode'] = "task";
		$rows = $service->pageBySqlId("list");
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode( $arr );
	}


	/*****************************************Ajax与JSON方法*****************************************/

}
?>
