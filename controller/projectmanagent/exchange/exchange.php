<?php

/**
 * @author Liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:换货申请单控制层
 */
class controller_projectmanagent_exchange_exchange extends controller_base_action {

	function __construct() {
		$this->objName = "exchange";
		$this->objPath = "projectmanagent_exchange";
		parent :: __construct();
	}

    /**
     * 获取分页数据转成Json (重写)
     */
    function c_pageJson() {
        $service = $this->service;

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($_REQUEST['toListMy']) && $_REQUEST['toListMy'] == '1'){
            $service->setCompany(0);
            $service->getParam ( $_REQUEST );
            $rows = $service->page_d ();
            //数据加入安全码
            $rows = $this->sconfig->md5Rows ( $rows );
        }else if(isset($sysLimit['销售区域']) && $sysLimit['销售区域'] != ''){
            $service->getParam ( $_REQUEST );

            if(!strstr($sysLimit['销售区域'], ';;')){
                $areaLimit = explode(",",$sysLimit['销售区域']);
                foreach ($areaLimit as $k => $v){
                    if($v == ''){
                        unset($areaLimit['$k']);
                    }
                }
                $service->searchArr['contractAreaCode'] = $areaLimit;
            }

            //$service->getParam ( $_POST ); //设置前台获取的参数信息

            //$service->asc = false;
            $rows = $service->page_d ();
            //数据加入安全码
            $rows = $this->sconfig->md5Rows ( $rows );
        }else{
            $rows = array();
        }


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
     * 合同TAB页--换货
     */
    function c_exchangeByContractlist(){
    	$this->assign("contractId",$_GET['contractId']);
    	$this->view("contractlist");
    }
	/**
	 * 跳转到换货申请单列表
	 */
	function c_page() {
		$this->view('list');
	}
	function c_toMyexchange() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('mylist');
	}
	/**
	 * 跳转到新增换货申请单页面
	 */
	function c_toAdd() {
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->assign('createTime', date('Y-m-d'));
		//获取所有合同权限
		$allContract = $this->service->this_limit['所有合同权限'];
		$this->assign('allContract', $allContract);
        $contractId = isset ($_GET['contractId']) ? $_GET['contractId'] : null;
        if($contractId){
         	$conDao = new model_contract_contract_contract();
         	$rows = $conDao->get_d($contractId);
        }
        $this->assign('contractCode', isset($rows['contractCode']) ? $rows['contractCode'] : '');
        $this->assign('contractName', isset($rows['contractName']) ? $rows['contractName'] : '');
        $this->assign('contractId', isset($_GET['contractId']) ? $_GET['contractId'] : '');
        $this->assign('customerName' , isset($rows['customerName']) ? $rows['customerName'] : null );
        $this->assign('customerId' , isset($rows['customerId']) ? $rows['customerId'] : null );
		$this->view('add');
	}

	/**
	 * 跳转到编辑换货申请单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userId',$_SESSION['USER_ID']);
		//数据字典-换货类型
		$this->showDatadicts(array('exchangeType' => 'HHLX'),$obj['exchangeType']);
		//获取所有合同权限
		$allContract = $this->service->this_limit['所有合同权限'];
		$this->assign('allContract', $allContract);
		$this->view('edit');
	}
	/**
	 * 换货申请单查看页面-tab
	 */
	function c_manageTab(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('id',$_GET['id']);
		$this->assign('skey',$_GET['skey']);
		$this->assign("contractId",$obj['contractId']);

		$this->view('view-tab');
	}
	/**
	 * 跳转到查看换货申请单页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$conDao = new model_contract_contract_contract();
		$conArr = $conDao->get_d($obj['contractId']);
		$this->assign("contractMoney",$conArr['contractMoney']);
		$this->assign("costEstimates",$conArr['costEstimates']);
		$this->assign("saleCost",$conArr['saleCost']);
		$this->assign("serCost",$conArr['serCost']);
		$this->assign("exgross",$conArr['exgross']);
		$this->view('view');
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->assign('id',$_GET['id']);
			$this->assign('skey',$_GET['skey']);
			$this->assign("contractId",$obj['contractId']);
			$this->view('view-tab');
		} else {
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			$this->assign('userId', $_SESSION['USER_ID']);
			$this->view('edit');
		}
	}
	/******************************************************************/

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id) {
			if ($_GET['act'] == 'app') {
				succ_show('controller/projectmanagent/exchange/ewf_index.php?actTo=ewfSelect&billId=' . $id);
			} else {
				//如果添加成功，则跳转到服务合同编辑页面
				msgRF('添加成功！');
			}
		}
		//$this->listDataDict();
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object, $isEditInfo)) {
			msg('编辑成功！');
		}
	}

	/**
	 * 审批后的确认操作
	 */
	function c_confirmExchange() {
		if (!empty ($_GET['spid'])) {
			$this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 新增红色销售库时，带出物料清单模板
	 */
	function c_getItemListAtCkSalesRed() {
		$id = isset ($_POST['relDocId']) ? $_POST['relDocId'] : null;
		$rows = $this->service->getDetailInfo($id, array (
			"backequ"
		));
		$listStr = $this->service->showProItemAtCkSalesRed($rows);
		echo util_jsonUtil :: iconvGB2UTF($listStr);
	}


	/**
	 * 新增蓝色销售库时，带出物料清单模板
	 */
	function c_getItemListAtCkSalesBlue() {
		$id = isset ($_POST['relDocId']) ? $_POST['relDocId'] : null;
		$rows = $this->service->getDetailInfo($id, array (
			"equ"
		));
		$listStr = $this->service->showProItemAtCkSalesBlue($rows);
		echo util_jsonUtil :: iconvGB2UTF($listStr);
	}
	/*********************************************************************************************************/
	/**
	 * 发货列表tab
	 */
	function c_toShipTab() {
		$this->display('ship-tab');
	}


    /**
     * 换货物料确认需求
     */
    function c_assignment(){
    	$this->display('assignments');
    }



	/**
	 * 发货列表
	 */
	function c_toExhangeShipments() {
		if (isset ($_GET['finish']) && $_GET['finish'] == 1) {
			$this->assign('listJS', 'exchange-shipped-grid.js');
		} else {
			$this->assign('listJS', 'exchange-shipments-grid.js');
		}
		$this->display('shipments');
	}

	/**
	 * 发货列表
	 */
	function c_toExhangeShipped() {
		$this->display('shipped');
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_shipmentsPageJson() {
		$rateDao = new model_stock_outplan_contractrate();
		$service = $this->service;
		$service->getParam($_REQUEST);
		//$service->asc = false;
		$otherDataDao = new model_common_otherdatas();
	    $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
	      if(!empty($sysLimit['销售区域']) && !strstr($sysLimit['销售区域'],";;")){
	    	$service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(ct.areaCode,'".$sysLimit['销售区域']."')";
	    }
		$rows = $service->pageBySqlId('select_shipments');
		$rows = $this->sconfig->md5Rows($rows);
		//发货需求进度备注
		$orderIdArr = array ();
		foreach ($rows as $key => $val) {
			$orderIdArr[$key] = $rows[$key]['id'];
		}
		$orderIdStr = implode(',', $orderIdArr);
		$rateDao->searchArr['relDocIdArr'] = $orderIdStr;
		$rateDao->asc = false;
		$rateArr = $rateDao->list_d();
		if (is_array($rows) && count($rows)) {
			foreach ($rows as $key => $val) {
				$rows[$key]['rate'] = "";
				if (is_array($rateArr) && count($rateArr)) {
					foreach ($rateArr as $index => $value) {
						if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_contract_exchangeapply' == $rateArr[$index]['relDocType']) {
							$rows[$key]['rate'] = $rateArr[$index]['keyword'];
						}
					}
				}
			}
		}
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/*************************************设备总汇表 start **************************************/
	/**
	 * 合同发货设备-计划统计列表
	 */
	function c_shipEquList(){
		$equNo = isset( $_GET['productCode'] )?$_GET['productCode']:"";
		$equName = isset( $_GET['productName'] )?$_GET['productName']:"";
		$searchArr = array();
		if($equNo!=""){
			$searchArr['productCode'] = $equNo;
		}
		if($equName!=""){
			$searchArr['productName'] = $equName;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.productId,p.productNumb");

		$rows = $service->pageEqu_d();
		$this->pageShowAssign();

		$this->assign('equNumb', $equNo);
		$this->assign('equName', $equName);
		$this->assign('list', $this->service->showEqulist_s($rows));
		$this->display('list-equ');
		unset($this->show);
		unset($service);
	}

	/***********************************设备总汇表 end *********************************/

	/**
	 * 物料处理方法 编辑
	 */
	function c_toViewTab() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//成本概算显示
		$conDao = new model_contract_contract_contract();
		$conArr = $conDao->get_d($obj['contractId']);
		//售前商机费用
		$chanceCost = $conDao->getChanceCostByid($obj['contractId']);
		$this->assign("chanceCost" , $chanceCost);
		$this->assign("saleCost" , $conArr['saleCost']);
		$this->assign("serCost" , $conArr['serCost']);
		$this->assign("exgross" , $conArr['exgross']);
		$this->assign("costEstimates" , $conArr['costEstimates']);
		$this->assign("contractMoney" , $conArr['contractMoney']);

		$this->view('view');
	}
	/**
	 * 跳转到核对换货申请单页面
	 */
	function c_toCheck() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('check');
	}
}
?>