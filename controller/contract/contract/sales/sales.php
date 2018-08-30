<?php

/*
 * Created on 2010-6-24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 */
class controller_contract_sales_sales extends controller_base_action {

	function __construct() {
		$this->objName = "sales";
		$this->objPath = "contract_sales";
		parent :: __construct();
	}

	/********************增*************************/
	//TODO;
	/**
	 * 合同起草页面
	 */
	function c_index() {
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->display('add');
	}

	/**
	 * 添加对象
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id && $_GET['act'] == 'save') {
			showmsg('保存成功！', '?model=contract_sales_sales&action=myApplyTab');
		}
		elseif ($id) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_contract_sales');
		} else {
			showmsg('保存失败！', '?model=contract_sales_sales&action=myApplyTab');
		}
	}

	/*******************删***************************/
	/*
	 * 重写删除方法
	 */
	function c_deletes() {
		try {
			$delete=$this->service->deletes_d ( $_GET ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 删除对象-数据库操作阶段
	 */
	function c_del() {
		if ($this->service->deletes_d($_GET['id'])) {
			msg('删除成功！');
		} else {
			msg('删除失败！');
		}
	}

	/**
	 * 删除对象-假删除-数据库操作阶段
	 */
	function c_notdel() {
		if ($this->service->notDel($_GET['id'])) {
			msg('删除成功！');
		} else {
			msg('删除失败！');
		}
	}


	/*******************改*****************************/
	//TODO;
	/**
	 * 修改对象-暂定只用于没有提交过审批的合同
	 */
	function c_edit() {
		$object = $this->service->edit_d($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			msgRf('保存成功！');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&examCode=oa_contract_sales&formName=销售合同审批');
		} else {
			msgRf('保存失败！');
		}
	}

	/**
	 * 重写合同信息修改 infoedit
	 */
	 function c_infoedit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->editBySelf ( $object, $isEditInfo )) {
			msg ( '保存成功！' );
		}
	}

	/**
	 * 重新编辑对象-使用于被打回后重新编辑的对象
	 */
	function c_editForBackAction() {
		$object = $this->service->editAgainAfterBack($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			msgRf('保存成功！');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_sales&formName=销售合同审批');
		} else {
			msgRf('保存失败！', 'index1.php?model=contract_sales_sales&action=myApplyContractAction');
		}
	}

	/**
	 * 变更合同
	 */
	function c_changeContract() {
		$object = $this->service->contractChange($_POST[$this->objName]);
		if ($object && $_GET['act'] == 'save') {
			showmsg('保存成功！', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction');
		} else if ($object) {
			succ_show('controller/contract/sales/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=合同变更');
		} else {
			showmsg('保存失败！', 'index1.php?model=contract_sales_sales&action=myPrincipalContractAction');
		}
	}

	/**
	 * 初始化对象-用于编辑合同
	 */
	function c_init() {

		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForEdit($rowsT);
		//附件
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber']);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('edit');
	}

	/**
	 * 初始化对象-打回后编辑合同
	 */
	function c_editAfterBackAction() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForEdit($rowsT);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('backedit');
	}

	/**
	 * 初始化对象-用于变更合同
	 */
	function c_readInfoForChange() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForChange($rowsT);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$this->assign('applyTime', day_date);
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);
		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->assign('formNumber', $this->businessCode());

		$this->display('change');
	}

     /**
      * 变更合同保存后的修改
      */
      function c_readInfoForChangeEdit() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfoForChange($rowsT);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$this->assign('applyTime', day_date );
		$this->assign('applyName', $_SESSION['USERNAME']);
		$this->assign('applyId', $_SESSION['USER_ID']);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->assign('formNumber', $this->businessCode());

		$this->display('change-edit');
	}

	/**
	 * 页面显示-指定合同负责人
	 */
	function c_changePrincipal() {
		$rowsT = $this->service->getContractInfo_d($_GET['id'], 'none');
		$this->assign('id', $_GET['id']);
		$this->assign('contractNumber', $rowsT['contNumber']);
		$this->assign('contractName', $rowsT['contName']);
		$this->assign('oldprincipalName', $rowsT['principalName']);
		$this->assign('oldprincipalId', $rowsT['principalId']);
		$this->show->display('contract_common_changeprincipal');
	}

	/**
	 * 页面显示-指定合同执行人
	 */
	function c_changeExecute() {
		$rowsT = $this->service->getContractInfo_d($_GET['id'], 'none');
		$this->assign('id', $_GET['id']);
		$this->assign('contractNumber', $rowsT['contNumber']);
		$this->assign('contractName', $rowsT['contName']);
		$this->assign('oldexecutorName', $rowsT['executorName']);
		$this->assign('oldexecutorId', $rowsT['executorId']);
		$this->show->display('contract_common_changeexecutor');
	}

	/**
	 * 数据操作-指定合同负责人/执行人
	 */
	function c_changePeopleAction() {
		$object = $this->service->editBySelf($_POST[$this->objName]);
		if ($object) {
			msg('指定成功！');
		} else {
			msg('指定失败！');
		}
	}

	/**
	 * 进入合同启动执行页面
	 */
	function c_contractBeginAction() {
		$returnObj = $this->service->returnSignStatus($_GET['id']);
		$this->assign('contNumber', $_GET['contNumber']);
		$this->assign('contractId', $_GET['id']);
		$this->assign('signStatus', $this->service->showSignStatus($returnObj['signStatus']));
		$this->show->display('contract_common_bcinfo-begin');
	}

	function c_beginAction() {
		$object = $this->service->contractBegin($_POST[$this->objName]);
		if ($object) {
			msg('启动成功！');
		} else {
			msg('启动失败！');
		}
	}

	/**
	 * 进入关闭合同页面
	 */
	function c_intoCloseAction() {
		$rows = $this->service->returnMainMsg($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('id', $_GET['id']);
		$this->assign('closeTime', day_date);
		$this->assign('closerName', $_SESSION['USERNAME']);
		$this->assign('closerId', $_SESSION['USER_ID']);
		$this->showDatadicts(array ('closeType' => 'GBYY'));
		$this->show->display('contract_common_close');
	}

	/**
	 * 关闭合同
	 */
	function c_closeAction() {
		$object = $this->service->contractClose($_POST[$this->objName]);
		if ($object) {
			msg('关闭成功！');
		} else {
			msg('关闭失败！');
		}
	}

	/*******************查*****************************/
	//TODO;
	/**
	 * 合同详情
	 */
	function c_infoTab(){
		$this->assign('id',$_GET['id']);
		$this->assign('contNumber' , $_GET['contNumber']);
		$this->display( 'salesinfo-tab');
	}

	/**
	 * 审批合同时查看合同信息
	 */
	function c_readBaseInfoInEx() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		//附件
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);

		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('readinex');
	}

	/*
	 * 查看合同信息
	 */
	function c_salesinfo() {
		$this->assign('id',$_GET['id']);
		$this->display('salesinfo-tab');
	}
	/**
	 * 初始化对象-查看合同信息-包括合同相关记录
	 */
	function c_readDetailedInfo() {
		$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);
		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('view-baseinfo');
	}

	/**
	 * 初始化对象-查看合同信息-包括合同相关记录-无修改页面
	 */
	 function c_readDetailedInfoNoedit(){
	 	$rowsT = $this->service->getContractInfo_d($_GET['id']);
		$rows = $this->service->contractInfo($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);

		$this->assignFunc($rows);
		$this->assign('invoiceType',$this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType',$this->getDataNameByCode($rows['customerType']));
		$this->display('view');
	 }

	/*
	 * 查看合同信息修改页面
	 */
	 function c_BaseInfoEdit(){
 		$rowsT = $this->service->getContractInfo_d($_GET['id'],'none');
		$rows = $this->service->contractInfoForEdit($rowsT);
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);
		$this->assignFunc($rows);
		//改变签约状态
		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->assign('contractId', $_GET['id']);
		$this->display('baseinfo-edit');
	 }

	/***********************列表*********************/
	//TODO;
	/**
	 * 在审批的销售合同
	 */
	function c_showExaSalesAction() {
		$this->display('zsp');
	}

	/*
	 * 重写在审批合同的pageJson
	 */
	 function c_zspPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;
		$rows = $service->pageBySqlId('contract_list');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * 未执行的合同
	 */
	function c_showNotExecutingSaleAction() {
		$this->display('wzx');
	}

	/*
	 * 重写未执行的PageJson
	 */
	 function c_wzxPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;
        $rows = $service->pageBySqlId('contract_list');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }


	/**
	 * 执行中的合同tab
	 */
	function c_zzxTab() {
		$this->display('zzx-tab');
	}

	/**
	 * 执行中的合同
	 */
	function c_showExecutingSalesAction() {
		$this->display('zzx');
	}

	 /*
      * 重写在执行的PageJson方法
      */
     function c_zzxPageJson() {
     	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;

        $rows = $service->pageBySqlId('contract_list');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
     }

	/**
	 * 合同设备执行情况
	 */
	function c_showExecutingSalesDetail() {
		$service = $this->service;
		$this->assign('sealocation', '"?model=contract_sales_sales&action=showExecutingSalesDetail&"+searchfield+"="+searchvalue');
		/****专业分割线****/

		$service->getParam($_GET);
		$service->searchArr['contStatusEqual'] = 1;
		$service->sort = 'updateTime';
		$rows = $service->page_d();
		$this->pageShowAssign();
		$this->assign('list', $service->showExecuting_s($rows));
		$this->display('list');
	}

	/**
	 * 变更中的合同
	 */
	function c_showChangingSalesAction() {
		$this->display('zbg');
	}

	/*
	 * 重写在变更的PageJson方法
	 */
	 function c_zbgPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->searchArr['changeStatus'] = 1;
		$service->searchArr['changingstatus'] = '';

        $rows = $service->pageBySqlId('contract_changing');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * 已关闭的合同
	 */
	function c_showClosedSalesAction() {
		$this->display('ygb');
	}
	/*
	 * 重写已关闭的PageJson方法
	 */
	 function c_pageJsonClose() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;

        $rows = $service->pageBySqlId('contract_close');

        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * 我的合同申请tab
	 */
	function c_myApplyTab() {
       $this->display('myapply-tab');
	}
	/**
	 * 我的合同申请
	 */
	function c_myApplyContractAction() {
		$this->display('myapply');
	}

	/**
	 * 我负责的合同
	 */
	function c_myPrincipalContractAction() {
		$this->display('myprincipal');
	}

	/**
	 * 我执行的合同
	 */
	function c_myExecuteContractAction() {
		$this->display('myexecute');
	}
	/**
	 * 我的审批-销售合同TAB
	 */
	function c_salespactTab () {
		$this->display( 'salespact-tab');
	}

	/**
	 * 合同版本浏览
	 */
	function c_versionShow() {
		$this->assign('contNumber', $_GET['contNumber']);
		$this->display('history');
	}

	/*
	 * 合同版本PageJson
	 */
	 function c_historyListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

        $rows = $service->pageBySqlId('version_list');

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * 重写我负责的合同 PageJson
	 */
	 function c_myprincipalPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->searchArr['principalId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');

        $rows = $service->page_d ();
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }
	 /*
	  * 重写我的合同申请 PageJson
	  */
	 function c_myApplyPageJson() {
	  	$service = $this->service;
	  	$service->getParam ( $_POST ); // 设置前台获取的参数信息

	  	$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');

        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/*
	 * 重写我执行的合同 PageJson
	 */
      function c_myExecutePageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); // 设置前台获取的参数信息
		$service->asc = false;

		$service->searchArr['executorId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('contract_list');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
      }

      /*
       * 重写合同历史PageJson
       */
	 function c_historyPageJson() {
       	$service = $this->service;
	  	$service->getParam ( $_POST ); // 设置前台获取的参数信息
	  	$service->asc = false;

	  	$service->searchArr['contNumber'] = $_POST['contNumber'];
		$rows = $service->pageBySqlId('contract_list');

        foreach( $rows as $key => $val ){
        	$rows[$key]['contStatusC'] = $service->returnContStatus( $val['contStatus']);
        }
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
     }
	 /**
	  * grid 审批页面
	  */
	 function c_toAuditTab(){
	 	$this->display('auditTab');
	 }

	 /**
	  * grid 审批页面
	  */
	 function c_approvalNo(){
	 	$this->display('unaudit');
	 }

	 /**
	  * 未审批的合同
	  */
	 function c_unauditPageJson(){
		$service = $this->service;
	  	$service->getParam ( $_POST ); // 设置前台获取的参数信息

	  	$service->searchArr['findInName'] = $_SESSION['USER_ID'];
	  	$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId('contract_examining');
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
	 }

	 /**
	  * 已审批的合同
	  */
	 function c_approvalYes(){
		$this->display('audited');
	 }

	 /**
	  * 已审批的合同
	  */
	 function c_auditedPageJson(){
		$service = $this->service;
	  	$service->getParam ( $_POST ); // 设置前台获取的参数信息

	  	$service->searchArr['findInName'] = $_SESSION['USER_ID'];
	  	$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId('contract_examined');
        $arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
	 }

	 /*********************合同处理*******************/
	 //TODO;

	/**
	  *  跳转到合同处理页面
	  */
	 function c_executeContract(){
	 	$service = $this->service;
	 	$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInEC($rows);
		$this->assignFunc($rows);
	 	$this->display('executecontract');
	 }

	 /**
	  *  跳转锁定库存页面
	  */
	 function c_toLockStockByContract(){
		$service = $this->service;
		$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInEC($rows);
		$this->assignFunc($rows);

		//拿到默认仓库的信息(调用仓库接口)
		$stockInfoDao=new model_stock_stockinfo_stockinfo();
		$stockInfo=$stockInfoDao->getDefaultStockInfo();
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['id']);
		$this->display('lockstock');
	 }

	 /**
	  *  合同锁定设备
	  */
	 function c_saleToLock(){
		$service = $this->service;
		$rows = $service->find(array('id'=>$_GET['id']),null,'id,contNumber,contName,formalNo');
		$rows = $service->showDetailInLock($rows);
		$this->assignFunc($rows);
		$this->display('lock');
	 }

	 /*********************签约部分*********************/
	 //TODO;
	 /**
	  * 合同签约管理列表
	  */
	 function c_signList(){
		$this->display('signlist');
	 }

	 /**
	  * 签收合同动作
	  */
	 function c_sign(){
		if ($this->service->sign_d($_POST['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	 }

	 /**
	  * 修改合同签约状态
	  */
	 function c_toSign(){
		$rows = $this->service->getContractInfo_d($_GET['id'],'none');
		$rows['file'] = $this->service->getFilesByObjNo($rows['contNumber'], false);
		$rows['signStatus'] = $this->service->showSignStatus($rows['signStatus']);
		$this->assignFunc($rows);
		//改变签约状态
		$this->assign('contractId', $_GET['id']);
		$this->display('sign');
	 }

	 /**
	  * 更改签约状态
	  */
	 function c_editBySelf() {
		$object = $this->service->editBySelf($_POST[$this->objName]);
		if ($object) {
			msg('修改成功！');
		} else {
			msg('修改失败！');
		}
	}

		/**
		 * @ ajax判断项
		 *
		 */
	    function c_ajaxContNumber() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['contNumber'] ) ? $_GET ['contNumber'] : false;

			$searchArr = array ("ajaxContNumber" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
}
?>