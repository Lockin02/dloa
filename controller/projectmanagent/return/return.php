<?php
/**
 * @author LiuB
 * @Date 2011年5月30日 19:42:14
 * @version 1.0
 * @description:销售退货控制层
 */
class controller_projectmanagent_return_return extends controller_base_action {

	function __construct() {
		$this->objName = "return";
		$this->objPath = "projectmanagent_return";
		parent :: __construct();
	}

	/**
	 * 跳转到销售退货-管理页面
	 */
	function c_page() {
		$this->view("list");
	}

	/**
	 * 跳转到待入库销售退货页面
	 */
	function c_awaitList() {
		$this->view("awaitlist");
	}

    /**
     * 合同Tab查看页-退货
     */
    function c_returnByContractlist(){
    	$this->assign("contractId" , $_GET['contractId']);
        $this->display("contractlist");
    }

    	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
         $orderId = isset ($_GET['orderId']) ? $_GET['orderId'] : null;
         $orderType = isset ($_GET['orderType']) ? $_GET['orderType'] : null;
         //编号
         $codeDao = new model_common_codeRule();
         $returnCode = $codeDao->commonCode("returnCode","returnCode","TH");
         $this->assign("returnCode",$returnCode);
         if(!empty ($orderId)){
             $rows = $this->service->getOrderInfo($orderId,$orderType);
         	 $this->assign('orderName', $rows['orderName']);
         	 if(empty($rows['orderCode'])){
                 $this->assign('orderCode', $rows['orderTempCode']);
         	 }else{
         	   	 $this->assign('orderCode', $rows['orderCode']);
         	 }
            $rosPro = $this->service->getOrderProInfo($rows,$orderType);
            $this->assign('equ', $rosPro['1']);
            $this->assign('Num', $rosPro['0']);
         	$this->assign('orderId', $rows['id']);
         	$this->assign('prinvipalName', $this->prinvipalName($rows,$orderType));
            $this->assign('createName', $_SESSION['USERNAME']);
			$this->assign('createId', $_SESSION['USER_ID']);
			$this->assign('createTime', date('Y-m-d'));
			$this->view ( 'orderadd' );
         }else{
         	$contractId = isset ($_GET['contractId']) ? $_GET['contractId'] : null;
         	if($contractId){
         		$conDao = new model_contract_contract_contract();
         		$rows = $conDao->get_d($contractId);
         	}
         	$this->assign('contractCode', isset($rows['contractCode']) ? $rows['contractCode'] : '');
         	$this->assign('contractName', isset($rows['contractName']) ? $rows['contractName'] : '');
         	$this->assign('contractId', isset($_GET['contractId']) ? $_GET['contractId'] : '');
	        $this->assign('createName', $_SESSION['USERNAME']);
			$this->assign('createId', $_SESSION['USER_ID']);
			$this->assign('userId', $_SESSION['USER_ID']);
			$this->assign('createTime', date('Y-m-d'));
			$this->view ( 'add' );
         }
	}
	/**
	 * 处理四种合同负责人
	 */
	function prinvipalName($rows,$orderType){
		switch($orderType){
             case "oa_sale_order" :  return $rows['prinvipalName'];break;
             case "oa_sale_service" :  return $rows['orderPrincipal'];break;
             case "oa_sale_lease" : return $rows['hiresName'];break;
             case "oa_sale_rdproject" : return $rows['orderPrincipal'];break;
		}
	}

	/**
	 * 销售退货单查看页面-tab
	 */
	function c_viewTab() {
		$this->assign('id', $_GET['id']);
		$this->assign('skey', $_GET['skey']);
		$this->view('view-tab');
	}

    /**
	 * 重写int
	 */
	function c_init() {
//		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);

		//渲染从表
		if ($perm == 'view') {
			$rows = $this->service->initView($rows);
			$rows['contractCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toViewTab&id=" . $rows['contractId'] . '",1,'. $rows['contractId'].')\'>' . $rows['contractCode'] ."</a>";
		} else {
			$rows = $this->service->initEdit($rows);
		}

		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		if ($perm == 'view') {
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->view('view');
		} else {
            $this->assign('returnId' , $_GET['id']);
            $this->assign('userId' , $_SESSION['USER_ID']);
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
			$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
			$this->view('edit');
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id) {
			if ($_GET['act'] == 'app') {
				succ_show('controller/projectmanagent/return/ewf_index2.php?actTo=ewfSelect&billId=' . $id);
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
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

 /**************************************审批相关****************************************************************/

	/**
	 * 我的审批 - tab
	 */
	function c_auditTab() {
		$this->display('audittab');
	}

	/**
	 * 我的审批 － 未审批页面
	 */
	function c_toAuditNo() {
		$this->display('auditno');
	}

	/**
	 * 我的审批 － 已审批的页面
	 */
	function c_toAuditYes() {
		$this->display('audityes');
	}
    /**
     * 我的审批 - 审批查看页
     */
    function c_toAuditView(){
    	$rows = $this->service->get_d($_GET['id']);
    	$rows = $this->service->initView($rows);
    	foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
    	$this->display('auditView');
    }

    /**
     * 获取分页数据转成Json (重写)
     */
    function c_pageJson() {
        $service = $this->service;
        $service->setCompany(0);

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($_REQUEST['toListMy']) && $_REQUEST['toListMy'] == '1'){
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
	 * 未审批Json
	 */
	function c_pageJsonAuditNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows , 'returnId');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 已审批Json
	 */
	function c_pageJsonAuditYes() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows , 'returnId');
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
 /******************************************************************************************************/

    /**
     * 跳转-- 我的销售退货单
     */
    function c_toMyreturn(){
    	$this->assign('userId' , $_SESSION['USER_ID']);
    	$this->display('mylist');
    }
/********************************************************************************************************/

/**
 * 红色出库时，下推模板添加从表模板
 */
   function c_getItemList () {
     $returnId=isset($_POST['returnId'])?$_POST['returnId']:null;
     $returnEquDao=new model_projectmanagent_return_returnequ();
     $rows=$returnEquDao->getDetailbyWait_d($returnId);
     // k3编码加载处理
       $productinfoDao = new model_stock_productinfo_productinfo();
       $rows = $productinfoDao->k3CodeFormatter_d($rows);
     echo util_jsonUtil::iconvGB2UTF ($returnEquDao->showProAtEdit($rows));
}


	/**
	 * 审批后的确认操作
	 */
	function c_confirmReturn() {
	    $this->service->workflowCallBack($_GET['spid']);
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 *  退货-待收货任务
	 */
	function c_returnWaitList(){

        $this->view("returnWaitList");
	}
}
?>