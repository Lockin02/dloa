<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:14:21
 * @version 1.0
 * @description:]��ͬ�տ�ƻ����Ʋ�
 */
class controller_contract_contract_receiptplan extends controller_base_action {

	function __construct() {
		$this->objName = "receiptplan";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * ��ת��]��ͬ�տ�ƻ��б�
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$this->service->getParam ( $_REQUEST );
		$this->service->asc = false;
		$rows = $this->service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}

   /**
	 * ��ת������]��ͬ�տ�ƻ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   	}

   /**
	 * ��ת���༭]��ͬ�տ�ƻ�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   	}

   /**
	 * ��ת���鿴]��ͬ�տ�ƻ�ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
	}

	/**
	 * ��ȡ��ͬ��������
	 */
	function c_selectPayCon(){
		$this->assign('contractId',$_GET['contractId']);
		//ѡ��ģʽ
		$modeType = isset($_GET['modeType']) ? $_GET['modeType'] : 0;
		$this->assign('modeType',$modeType);

		$this->view('selectlist');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_selectPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ����δ�����б� json
	 */
	function c_dealPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$conditionSql = "sql: and now() > DATE_ADD(c.Tday,INTERVAL 4 MONTH)";
		$service->searchArr['mySearchCondition'] = $conditionSql;
		$rows = $service->page_d ('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ���º�ͬ��������
	 */
	function c_updatePlan() {
		$dao = new model_contract_contract_contract();
		$conInfo = $dao -> get_d($_GET['contractId']);
		$this->assign("contractId", $_GET['contractId']);
		$this->assign("contractMoney", $conInfo['contractMoney']);
		$this->assign("contractCode", $conInfo['contractCode']);
		$this->assign("isfinance",$_GET['isfinance']);
		$this->view("updatePlan");
	}

	//���º�ͬ��������
	function c_updatePlanAdd() {
		$obj = $_POST[$this->objName];
		$flag = $this->service->updatePlan_d($obj);
		if ($flag) {
			$incomeDao = new model_finance_income_incomecheck();
			$incomeDao->initData_d($obj['contractId']);
			msg('����ɹ���');
		}
	}
	/**
	 * ��д����ԭ��ҳ��
	 */
	function c_toUpdateDealReason(){
		$this->assign("id",$_GET['id']);
		$this->view("updateDealReason");
	}
	/**
	 * ���³���ԭ��
	 */
	function c_updateDealReason() {
		$this->checkSubmit();
        $arr = $_POST [$this->objName];
		$this->service->updateDealReason_d ($arr);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '�����ɹ���';
		msg ( $msg );
	}

}