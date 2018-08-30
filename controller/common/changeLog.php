<?php

/**
 *
 * ͳһ���������Ʋ�
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
	 * ��ת����ʾ��ʷ�汾
	 */
	function c_tolist() {
		//		$this->permCheck ($_GET ['originalId'],'purchase_contract_purchasecontract');//��ȫУ��
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'list' );
	}

	/**
	 * ��ʷ�汾�б�
	 */
	function c_toChangeLogList() {
		$this->assignFunc ( $_GET );
		$this->view ( 'changelog-list' );
	}

	/**
	 * ǩ����ʷ�б�
	 */
	function c_toSignLogList() {
		$this->assignFunc ( $_GET );
		$this->view ( 'signlog-list' );
	}

	/**
	 * ��ת�����ۺ�ͬ��ʾ��ʷ�汾
	 */
	function c_toOrderList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['origi1nalId'] );

		$logObj = isset($_GET ['logObj'])? $_GET ['logObj'] : '';
		$this->assign ( 'logObj', $logObj );

		$this->view ( 'order-list' );
	}

	/**
	 * ��ת�������ͬ��ʾ��ʷ�汾
	 */
	function c_toSerivceList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'service-list' );
	}

	/**
	 * ��ת�����޺�ͬ��ʾ��ʷ�汾
	 */
	function c_toRentalList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rental-list' );
	}

	/**
	 * ��ת���з���ͬ��ʾ��ʷ�汾
	 */
	function c_toRdprojectList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rdproject-list' );
	}

	/**
	 * ��ת���ɹ�������ʾ��ʷ�汾
	 */
	function c_toTaskList() {
		$this->permCheck ( $_GET ['objId'], 'purchase_task_basic' ); //��ȫУ��
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->view ( 'task-list' );
	}

	/**
	 * ��ת���ɹ�������ʾ��ʷ�汾
	 */
	function c_toPlanList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'plan-list' );
	}

	/**
	 * ��ת������������ʾ��ʷ�汾
	 */
	function c_toProduceApplyList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'produceapply-list' );
	}

	/**
	 * ��ת����������ʾ��ʷ�汾
	 */
	function c_toProduceTaskList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'producetask-list' );
	}

	/**
	 * �������¼��ҳ
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
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ�����ϸ��¼��ҳ.................................................................................................................................
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
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ�����ϸ��¼��ҳ(�ϲ���¼).................................................................................................................................
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
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ���ȷ��
	 */
	function c_confirmChange() {
		if (empty ( $_POST ['logObj'] ) || empty ( $_POST ['id'] )) {
			echo 0;
			return;
		}
		echo $this->service->confirmChange_d ( $_POST ['id'], $_POST ['logObj'] );
	}

	/**
	 * ��ȡ����ı���ֶ�
	 */
	function c_getChangeObjs() {
		$rs = $this->service->getChangeObjs_d ( $_POST ['tempId'], $_POST ['logObj'] );
		echo implode ( $rs, ',' );
	}

	/**
	 * ��ȡ����ı���ֶΣ����ǰ��ֵ��������ֵ
	 */
	function c_getChangeInformation (){
		$rs = $this->service->getChangeInformation_d ( $_POST ['tempId'], $_POST ['logObj'] );
		echo util_jsonUtil::encode ( $rs );
	}

	/***********************************************************************************/
	/**
	 * ���ۺ�ͬǩ�ռ�¼
	 */
	function c_toOrderSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'order-signin' );
	}
	/**
	 * �����ͬǩ�ռ�¼
	 */
	function c_toServiceSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'service-signin' );
	}

	/**
	 * ���޺�ͬǩ�ռ�¼
	 */
	function c_toRentalSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rentalcontract-signin' );
	}
	/**
	 * �з���ͬǩ�ռ�¼
	 */
	function c_toRdprojectSignin() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'rdproject-signin' );
	}
	/**
	 * �ɹ�����ǩ�ռ�¼
	 */
	function c_toPurchaseSign() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'list-sign' );
	}

	/**
	 * ��ת�������ƻ���ʾ��ʷ�汾
	 */
	function c_toOutplanList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->view ( 'outplan-list' );
	}

	/**
	 * ��ת���鿴�����ϸҳ��
	 */
	function c_toView() {
		$this->assign ( 'logObj', $_GET ['logObj'] );
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'detailId', $_GET ['detailId'] );
		$this->assign ( 'detailType', $_GET ['detailType'] );
		$this->view ( "view" );
	}
	/**
	 * ������ �����¼
	 */
	function c_toBorrowChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'borrow-changelist' );
	}
	/**
	 * ���� �����¼
	 */
	function c_toPresentChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'present-changelist' );
	}

	/**
	 * ���ϱ��
	 */
	function c_toContractEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'equ-changelist' );
	}

	/**
	 * ���������ϱ��
	 */
	function c_toBorrowEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'borrowequ-changelist' );
	}

	/**
	 * �������ϱ��
	 */
	function c_toPresentEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'presentequ-changelist' );
	}

	/**
	 * �������ϱ��
	 */
	function c_toExchangeEquChangeList() {
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'exchangeequ-changelist' );
	}
	/**
	 * �̻�����
	 */
	function c_tochanceList(){
		$this->assign ( 'objId', $_GET ['objId'] );
		$this->assign ( 'originalId', $_GET ['originalId'] );
		$this->view ( 'chance-list' );
	}
	/**
	 * ��ͬ�������
	 */
	function c_getChangeFieldCn(){
		$logObj = $_POST ['logObj'];
		$objId = $_POST ['objId'];
		$datas = $this->service->getChangeFieldCnByObjId ($logObj,$objId);
		echo util_jsonUtil::encode ( $datas );
	}
}
?>
