<?php

/**
 * @author Liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:�������뵥���Ʋ�
 */
class controller_projectmanagent_exchange_exchange extends controller_base_action {

	function __construct() {
		$this->objName = "exchange";
		$this->objPath = "projectmanagent_exchange";
		parent :: __construct();
	}

    /**
     * ��ȡ��ҳ����ת��Json (��д)
     */
    function c_pageJson() {
        $service = $this->service;

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($_REQUEST['toListMy']) && $_REQUEST['toListMy'] == '1'){
            $service->setCompany(0);
            $service->getParam ( $_REQUEST );
            $rows = $service->page_d ();
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows ( $rows );
        }else if(isset($sysLimit['��������']) && $sysLimit['��������'] != ''){
            $service->getParam ( $_REQUEST );

            if(!strstr($sysLimit['��������'], ';;')){
                $areaLimit = explode(",",$sysLimit['��������']);
                foreach ($areaLimit as $k => $v){
                    if($v == ''){
                        unset($areaLimit['$k']);
                    }
                }
                $service->searchArr['contractAreaCode'] = $areaLimit;
            }

            //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

            //$service->asc = false;
            $rows = $service->page_d ();
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows ( $rows );
        }else{
            $rows = array();
        }


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
     * ��ͬTABҳ--����
     */
    function c_exchangeByContractlist(){
    	$this->assign("contractId",$_GET['contractId']);
    	$this->view("contractlist");
    }
	/**
	 * ��ת���������뵥�б�
	 */
	function c_page() {
		$this->view('list');
	}
	function c_toMyexchange() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('mylist');
	}
	/**
	 * ��ת�������������뵥ҳ��
	 */
	function c_toAdd() {
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->assign('createTime', date('Y-m-d'));
		//��ȡ���к�ͬȨ��
		$allContract = $this->service->this_limit['���к�ͬȨ��'];
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
	 * ��ת���༭�������뵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userId',$_SESSION['USER_ID']);
		//�����ֵ�-��������
		$this->showDatadicts(array('exchangeType' => 'HHLX'),$obj['exchangeType']);
		//��ȡ���к�ͬȨ��
		$allContract = $this->service->this_limit['���к�ͬȨ��'];
		$this->assign('allContract', $allContract);
		$this->view('edit');
	}
	/**
	 * �������뵥�鿴ҳ��-tab
	 */
	function c_manageTab(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('id',$_GET['id']);
		$this->assign('skey',$_GET['skey']);
		$this->assign("contractId",$obj['contractId']);

		$this->view('view-tab');
	}
	/**
	 * ��ת���鿴�������뵥ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
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
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck(); //��ȫУ��
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
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id) {
			if ($_GET['act'] == 'app') {
				succ_show('controller/projectmanagent/exchange/ewf_index.php?actTo=ewfSelect&billId=' . $id);
			} else {
				//�����ӳɹ�������ת�������ͬ�༭ҳ��
				msgRF('��ӳɹ���');
			}
		}
		//$this->listDataDict();
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object, $isEditInfo)) {
			msg('�༭�ɹ���');
		}
	}

	/**
	 * �������ȷ�ϲ���
	 */
	function c_confirmExchange() {
		if (!empty ($_GET['spid'])) {
			$this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ������ɫ���ۿ�ʱ�����������嵥ģ��
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
	 * ������ɫ���ۿ�ʱ�����������嵥ģ��
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
	 * �����б�tab
	 */
	function c_toShipTab() {
		$this->display('ship-tab');
	}


    /**
     * ��������ȷ������
     */
    function c_assignment(){
    	$this->display('assignments');
    }



	/**
	 * �����б�
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
	 * �����б�
	 */
	function c_toExhangeShipped() {
		$this->display('shipped');
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_shipmentsPageJson() {
		$rateDao = new model_stock_outplan_contractrate();
		$service = $this->service;
		$service->getParam($_REQUEST);
		//$service->asc = false;
		$otherDataDao = new model_common_otherdatas();
	    $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
	      if(!empty($sysLimit['��������']) && !strstr($sysLimit['��������'],";;")){
	    	$service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(ct.areaCode,'".$sysLimit['��������']."')";
	    }
		$rows = $service->pageBySqlId('select_shipments');
		$rows = $this->sconfig->md5Rows($rows);
		//����������ȱ�ע
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
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/*************************************�豸�ܻ�� start **************************************/
	/**
	 * ��ͬ�����豸-�ƻ�ͳ���б�
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

	/***********************************�豸�ܻ�� end *********************************/

	/**
	 * ���ϴ����� �༭
	 */
	function c_toViewTab() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_exchange_exchange();
		$obj = $contDao->getDetailInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['exchangeCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_exchange_exchange&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['exchangeCode'] . '</a>';
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//�ɱ�������ʾ
		$conDao = new model_contract_contract_contract();
		$conArr = $conDao->get_d($obj['contractId']);
		//��ǰ�̻�����
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
	 * ��ת���˶Ի������뵥ҳ��
	 */
	function c_toCheck() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('check');
	}
}
?>