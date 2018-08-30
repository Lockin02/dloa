<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:14:21
 * @version 1.0
 * @description:]合同收款计划控制层
 */
class controller_contract_contract_receiptplan extends controller_base_action {

	function __construct() {
		$this->objName = "receiptplan";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * 跳转到]合同收款计划列表
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$this->service->getParam ( $_REQUEST );
		$this->service->asc = false;
		$rows = $this->service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}

   /**
	 * 跳转到新增]合同收款计划页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   	}

   /**
	 * 跳转到编辑]合同收款计划页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   	}

   /**
	 * 跳转到查看]合同收款计划页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
	}

	/**
	 * 获取合同付款条件
	 */
	function c_selectPayCon(){
		$this->assign('contractId',$_GET['contractId']);
		//选择模式
		$modeType = isset($_GET['modeType']) ? $_GET['modeType'] : 0;
		$this->assign('modeType',$modeType);

		$this->view('selectlist');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_selectPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 超期未到款列表 json
	 */
	function c_dealPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$conditionSql = "sql: and now() > DATE_ADD(c.Tday,INTERVAL 4 MONTH)";
		$service->searchArr['mySearchCondition'] = $conditionSql;
		$rows = $service->page_d ('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 更新合同付款条件
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

	//更新合同付款条件
	function c_updatePlanAdd() {
		$obj = $_POST[$this->objName];
		$flag = $this->service->updatePlan_d($obj);
		if ($flag) {
			$incomeDao = new model_finance_income_incomecheck();
			$incomeDao->initData_d($obj['contractId']);
			msg('保存成功！');
		}
	}
	/**
	 * 填写超期原因页面
	 */
	function c_toUpdateDealReason(){
		$this->assign("id",$_GET['id']);
		$this->view("updateDealReason");
	}
	/**
	 * 更新超期原因
	 */
	function c_updateDealReason() {
		$this->checkSubmit();
        $arr = $_POST [$this->objName];
		$this->service->updateDealReason_d ($arr);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '操作成功！';
		msg ( $msg );
	}

}