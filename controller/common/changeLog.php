<?php

/**
 *
 * 统一变更处理控制层
 * @author chengl
 *
 */
class controller_common_changeLog extends controller_base_action {

	function __construct() {
		$this->objName = "changeLog";
		$this->objPath = "common";
		parent::__construct ();
	}

	/**
	 * 跳转到显示历史版本
	 */
	function c_tolist() {
		//		$this->permCheck ($_GET ['originalId'],'purchase_contract_purchasecontract');//安全校验
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'list' );
	}

	/**
	 * 历史版本列表
	 */
	function c_toChangeLogList() {
		$this->assignFunc ( $_GET );
		$this->view ( 'changelog-list' );
	}

	/**
	 * 签收历史列表
	 */
	function c_toSignLogList() {
		$this->assignFunc ( $_GET );
		$this->view ( 'signlog-list' );
	}

	/**
	 * 跳转到销售合同显示历史版本
	 */
	function c_toOrderList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['origi1nalId'] );

		$logObj = isset($_GET ['logObj'])? $_GET ['logObj'] : '';
		$this->assign ( 'logObj', $logObj );

		$this->view ( 'order-list' );
	}

	/**
	 * 跳转到服务合同显示历史版本
	 */
	function c_toSerivceList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'service-list' );
	}

	/**
	 * 跳转到租赁合同显示历史版本
	 */
	function c_toRentalList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rental-list' );
	}

	/**
	 * 跳转到研发合同显示历史版本
	 */
	function c_toRdprojectList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rdproject-list' );
	}

	/**
	 * 跳转到采购任务显示历史版本
	 */
	function c_toTaskList() {
		$this->permCheck ( $_GET ['objId'], 'purchase_task_basic' ); //安全校验
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->view ( 'task-list' );
	}

	/**
	 * 跳转到采购申请显示历史版本
	 */
	function c_toPlanList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'plan-list' );
	}

	/**
	 * 跳转到生产申请显示历史版本
	 */
	function c_toProduceApplyList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'produceapply-list' );
	}

	/**
	 * 跳转到生产任务示历史版本
	 */
	function c_toProduceTaskList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'producetask-list' );
	}

	/**
	 * 变更主记录分页
	 */
	function c_pageJson() {
		$logObj = $_POST ["logObj"];
		$changeFieldCn = $_POST['changeFieldCn'];
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$obj['logObj'] = $logObj;
		$obj['changeFieldCn'] = $changeFieldCn;
		$rows = $service->page_d ( $obj );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取变更明细记录分页.................................................................................................................................
	 */
	function c_pageJsonDetail() {
		$logObj = $_POST ["logObj"];
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->searchArr ["nameNotNull"] = 1;
		$isLast = false;
		$isGetUpdate = false;
		if (! empty ( $_POST ['isLast'] )) {
			$isLast = true;
		}
		if (! empty ( $_POST ['isGetUpdate'] )) {
			$service->searchArr ['isGetUpdate'] = true;
		}

		$service->pageSize = 999;
		$isTemp = $_POST ['isTemp'];
		$rows = $service->pageDetail_d ( $logObj, $isLast, $_POST ['isTemp'] );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 获取变更明细记录分页(合并记录).................................................................................................................................
	 */
	function c_pageJsonDetailMerge() {
		$logObj = $_POST ["logObj"];
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->searchArr ["nameNotNull"] = 1;
		$isLast = false;
		$isGetUpdate = false;
		if (! empty ( $_POST ['isLast'] )) {
			$isLast = true;
		}
		if (! empty ( $_POST ['isGetUpdate'] )) {
			$service->searchArr ['isGetUpdate'] = true;
		}

		$service->pageSize = 999;
		$isTemp = $_POST ['isTemp'];
		$rows = $service->pageDetailMerge ( $logObj, $isLast, $_POST ['isTemp'] );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 变更确认
	 */
	function c_confirmChange() {
		if (empty ( $_POST ['logObj'] ) || empty ( $_POST ['id'] )) {
			echo 0;
			return;
		}
		echo $this->service->confirmChange_d ( $_POST ['id'], $_POST ['logObj'] );
	}

	/**
	 * 获取对象的变更字段
	 */
	function c_getChangeObjs() {
		$rs = $this->service->getChangeObjs_d ( $_POST ['tempId'], $_POST ['logObj'] );
		echo implode ( $rs, ',' );
	}

	/**
	 * 获取对象的变更字段，变更前的值，变更后的值
	 */
	function c_getChangeInformation (){
		$rs = $this->service->getChangeInformation_d ( $_POST ['tempId'], $_POST ['logObj'] );
		echo util_jsonUtil::encode ( $rs );
	}

	/***********************************************************************************/
	/**
	 * 销售合同签收记录
	 */
	function c_toOrderSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'order-signin' );
	}
	/**
	 * 服务合同签收记录
	 */
	function c_toServiceSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'service-signin' );
	}

	/**
	 * 租赁合同签收记录
	 */
	function c_toRentalSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rentalcontract-signin' );
	}
	/**
	 * 研发合同签收记录
	 */
	function c_toRdprojectSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rdproject-signin' );
	}
	/**
	 * 采购订单签收记录
	 */
	function c_toPurchaseSign() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'list-sign' );
	}

	/**
	 * 跳转到发货计划显示历史版本
	 */
	function c_toOutplanList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->view ( 'outplan-list' );
	}

	/**
	 * 跳转到查看变更明细页面
	 */
	function c_toView() {
		$this->assign ( 'logObj', $_GET ['logObj'] );
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'detailId', $_GET ['detailId'] );
		$this->assign ( 'detailType', $_GET ['detailType'] );
		$this->view ( "view" );
	}
	/**
	 * 借试用 变更记录
	 */
	function c_toBorrowChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'borrow-changelist' );
	}
	/**
	 * 赠送 变更记录
	 */
	function c_toPresentChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'present-changelist' );
	}

	/**
	 * 物料变更
	 */
	function c_toContractEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'equ-changelist' );
	}

	/**
	 * 借试用物料变更
	 */
	function c_toBorrowEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'borrowequ-changelist' );
	}

	/**
	 * 赠送物料变更
	 */
	function c_toPresentEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'presentequ-changelist' );
	}

	/**
	 * 换货物料变更
	 */
	function c_toExchangeEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'exchangeequ-changelist' );
	}
	/**
	 * 商机更新
	 */
	function c_tochanceList(){
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'chance-list' );
	}
	/**
	 * 合同变更属性
	 */
	function c_getChangeFieldCn(){
		$logObj = $_POST ['logObj'];
		$objId = $_POST ['objId'];
		$datas = $this->service->getChangeFieldCnByObjId ($logObj,$objId);
		echo util_jsonUtil::encode ( $datas );
	}
}
?>
