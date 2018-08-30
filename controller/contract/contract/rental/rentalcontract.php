<?php
header("Content-type: text/html; charset=gb2312");
/*
 * Created on 2011-1-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 租赁合同控制层
 */
class controller_contract_rental_rentalcontract extends controller_base_action{
	function __construct(){
		$this->objName = "rentalcontract";
		$this->objPath = "contract_rental";
		parent::__construct();
	}


	/**
	 * 跳转到新建出租合同页面
	 *
	 */
	function c_toAddSContract () {
		$this->assign('orderInput', ORDER_INPUT );
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('hiresName', $_SESSION['USERNAME']);
		$this->assign('hiresId', $_SESSION['USER_ID']);
		$this->assign('createTime' , day_date);
		$id = $_GET['id'];
        if ($id) {
        	$this->permCheck($id,projectmanagent_chance_chance);
			$condiction = array (
				"id" => $id
			);
			$cluesDao = new model_projectmanagent_chance_chance();
			$rows = $cluesDao->get_d($id);
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
            $this->customerProCity($rows['customerId']);//根据客户获取 客户的省市信息
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
            $orderChance = new model_contract_rental_tentalcontractequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);
			$orderCus = new model_contract_rental_customizelist();
            $customizelist = $orderCus->changeCusOrder($rows['customizelist']);

			$this->assign('chance', $chanceequ[0]);
			$this->assign('productNumber', $chanceequ[1]);
			$this->assign('customizelist', $customizelist[0]);
			$this->assign('PreNum', $customizelist[1]);
			$this->assign('chanceId', $id);
			$this->display('add-chance');
		} else {
			$this->display('add');
		}
	}
	 /**
      * 根据客户ID 获取 客户的省市信息
     */
    function customerProCity($customerId){
      $dao = new model_customer_customer_customer();
      $proId = $dao->find(array("id" => $customerId),null,"ProvId");//省份ID
      $cityId = $dao->find(array("id" => $customerId),null,"CityId");//城市ID
      $this->assign("orderProvinceId",$proId['ProvId']);
      $this->assign("orderCityId",$cityId['CityId']);
    }
	/**
	 * 合同查看Tab---关闭信息
	 */
	function c_toCloseInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['state'] == '3' || $rows['state'] == '9' ) {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeinfo');

		} else {
			echo '<span>暂无相关信息</span>';
		}
	}
/**********************************************************************************************/
     /**
      * 未审批合同
      */
     function c_toListWtj(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
	 	$this->display("listwtj");
     }
	/**
	 *审批中批列表
	 */
	 function c_toListWsp(){
	 	$this->assign("userId" , $_SESSION['USER_ID']);
	 	$this->display("listwsp");
	 }

	 /**
	  * 在执行的租赁合同
	  */
	  function c_toListZzx(){
	  	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->display('listzzx');
	  }

	 /**
	  * 在执行的租赁合同
	  */
	  function c_toListYwc(){
	  	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->display('listywc');
	  }
	  /**
	   * 已关闭的租赁合同
	   */
	   function c_toListYwg(){
	   	$this->assign("userId" , $_SESSION['USER_ID']);
	   	 $this->display('listywg');
	   }
/**********************************************************************************************/
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$orderInfo = $_POST [$this->objName];
//		if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "否" ){
//				$orderInfo['orderTempCode'] = "LS".$orderInfo['orderTempCode'];
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}else if($orderInfo['sign'] == "是"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			if(!empty($orderInfo['orderTempCode'])){
//				$orderInfo['orderTempCode'] = $orderInfo['orderTempCode'];
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else {
//			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
//		}

		$id = $this->service->add_d ($orderInfo);
        if($id){
			if($_GET['act']=='app'){
				succ_show('controller/contract/rental/ewf_index2.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//如果添加成功，则跳转到服务合同编辑页面
				msgGO ( '添加成功！' ,'?model=contract_rental_rentalcontract&action=toAddSContract');
			}
		}
		//$this->listDataDict();
	}

	/**
	 * 商机转租赁合同
	 */
	function c_chanceAdd() {
		$orderInfo =  $_POST [$this->objName];
//		if($orderInfo['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $orderInfo['sign'] == "否" ){
//			   $orderInfo['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}else if($orderInfo['sign'] == "是"){
//				$orderInfo['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$orderInfo['tenantId']);
//			}
//			$id = $this->service->add_d ($orderInfo);
//		}else if($orderInfo['orderInput'] == "0"){
//			$id = $this->service->add_d ($orderInfo);
//		}else {
//			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
//		}
		$id = $this->service->add_d ($orderInfo);
        if($id){
			if($_GET['act']=='app'){
				succ_show('controller/contract/rental/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
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
		$orderCodeDao = new model_common_codeRule();
        if($object['sign'] == "是" && $object['orderCode'] == ''){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$object['tenantId']);
		}
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
		/**
	 * 物料修改
	 */
	function c_proedit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$id = $this->service->proedit_d ( $object, $isEditInfo );

		if ($id) {
            $this->service->updateOrderShipStatus_d($id);
			msgRF ( '编辑成功' );
		}
	}

	/**
	 * 重写int
	 */
	function c_init() {
//		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);

		if ($perm == 'view') {
            //权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['hiresId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
                $rows = $this->service->filterWithoutField('租赁合同金额',$rows,'form',array('orderMoney','orderTempMoney'));
                $rows['rentalcontractequ'] = $this->service->filterWithoutField('租赁设备金额',$rows['rentalcontractequ'],'list',array('price','money'));
            }

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            //合同文本权限

			 if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['hiresId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
	                if(!empty($this->service->this_limit ['租赁合同金额'])){
	               $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );
				}else{
					$rows ['file2']="";
				}
            }else{
            	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );
            }

            $rows = $this->service->initView($rows);

            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }

             if ($rows['sign'] == '是') {
					$this->assign('sign', '是');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('sign', '否');
					};

			if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstate', '已提交');
				} else if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstate', '已拿到');
					};
			if($rows['shipCondition'] == '0' ){
				$this->assign ( 'shipCondition', '立即发货' );
			}else if($rows['shipCondition'] == '1' ){
				$this->assign ( 'shipCondition', '通知发货' );
			}else{
				$this->assign ( 'shipCondition', '' );
			}
			$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
			$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		}else{
            $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
            //合同文本权限
		    $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
            $rows = $this->service->initEdit($rows);

            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }

			if ($perm == 'signIn') {
				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));
				$this->showDatadicts(array (
					'customerType' => 'KHLX'
				), $rows['customerType']);
				$this->showDatadicts(array (
					'invoiceType' => 'FPLX'
				), $rows['invoiceType']);
				$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
				$this->display('signin');
			} else {

    			if($rows['sign'] == '是'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '否'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '已提交'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '已拿到'){
                	$this->assign('orderstateNo'  , 'checked');
                };
    			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
    			$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
    			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
    			$this->display('edit');
    		}
        }
	}

	/**
	 * 重写int
	 */
	function c_toViewForAudit() {
		$rows = $this->service->get_d($_GET['id']);

        $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
        //合同文本权限
    	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_lease2' );

        $rows = $this->service->initView($rows);

        foreach ($rows as $key => $val) {
            $this->show->assign($key, $val);
        }

         if ($rows['sign'] == '是') {
				$this->assign('sign', '是');
			} else
				if ($rows['sign'] == '否') {
					$this->assign('sign', '否');
				};

		if ($rows['orderstate'] == '已提交') {
				$this->assign('orderstate', '已提交');
			} else if ($rows['orderstate'] == '已拿到') {
					$this->assign('orderstate', '已拿到');
				};
		if($rows['shipCondition'] == '0' ){
			$this->assign ( 'shipCondition', '立即发货' );
		}else if($rows['shipCondition'] == '1' ){
			$this->assign ( 'shipCondition', '通知发货' );
		}else{
			$this->assign ( 'shipCondition', '' );
		}
		$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
		$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view');
	}

    /**
     * 物料修改
     */
    function c_productedit() {
		if(!isset($this->service->this_limit['物料修改'])||$this->service->this_limit['物料修改'] != 1){
			echo "没有物料修改的权限，请联系OA管理员开通";
			exit();
		}
		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);
//		foreach ($rows['rentalcontractequ'] as $k => $v){
//        	 if($v['purchasedNum'] > 0 || $v['issuedShipNum'] > 0){
//                   echo "该合同已执行发货或采购操作，请选择变更流程修改物料";
//                   exit();
//        	 }
//        }
            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            $rows = $this->service->editProduct($rows);
             foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }
    			if($rows['sign'] == '是'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '否'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '已提交'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '已拿到'){
                	$this->assign('orderstateNo'  , 'checked');
                };
    			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			   $this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
    			$this->display('productedit');
    		}
	/**跳转到租赁合同变更页面
	*author can
	*2011-6-2
	*/
	function c_toChange(){
   	    $changer = isset ($_GET['changer'])? $_GET['changer'] :null;
   	    $changeC = isset ($_GET['changeC'])? $_GET['changeC'] :null;
   	    $changeLogDao = new model_common_changeLog ('rentalcontract');
		if($changeLogDao->isChanging($_GET['id'])){
         msgGo ( "该合同已在变更审批中，无法变更." );
         }
		$rows = $this->service->get_d($_GET['id']);
  	    $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}

		if($rows['sign'] == '是'){
        	$this->assign('signYes' , 'checked');
        }else if($rows['sign'] == '否'){
        	$this->assign('signNo'  , 'checked');
        };

        if($rows['orderstate'] == '已提交'){
        	$this->assign('orderstateYes' , 'checked');
        }else if($rows['orderstate'] == '已拿到'){
        	$this->assign('orderstateNo'  , 'checked');
        };
		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
		$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
			$this->showDatadicts(array ( 'orderNature' => 'ZLHTSX' ), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**变更租赁合同
	*author can
	*2011-6-1
	*/
	function c_change() {
		try {
			$changer=isset($_GET['changer'])?$_GET['changer']:null;
			$changeC=isset($_GET['changeC'])?$_GET['changeC']:null;
			$infoArr = $_POST ['rentalcontract'];
			$isDel="0";
			 foreach($infoArr['rentalcontractequ'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['rentalcontractequ'][$key]);
				}
				if(array_key_exists ("isDel",$val)){
					//判断物料是否有下达发货，下达采购，下达生产
                   $fsql="select count(id) as isExe from oa_lease_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=".$val['oldId']." ";
                   $isExeArr = $this->service->_db->getArray($fsql);
                    $isExe = $isExeArr[0]['isExe'];
					$isDel="1";
				}
			}
			foreach($infoArr['rentalcontractequTemp'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['rentalcontractequTemp'][$key]);
				}
			}
		   if($isDel=="1" && $isExe != "0"){
	         $formName="租赁合同变更（删物料）";
//	         $formName="租赁合同审批";
           }else{
           	 $formName="租赁合同审批";
           }
			$id = $this->service->change_d ( $infoArr );
			if($changer=="changer"){
				 if($changeC == "changeC"){
				 	 echo "<script>this.location='controller/contract/rental/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				 }else{
				 	echo "<script>this.location='controller/contract/rental/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				 }

			}else{
				echo "<script>this.location='controller/contract/rental/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}

/**********************************************************************************************************/
	/**
	 * 关闭合同页面
	 */
	function c_CloseOrder() {
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userName', $_SESSION['USERNAME']);
		$this->assign('dateTime', date('Y-m-d'));
		$this->display('closeorder');
	}
	/**
	 * 关闭合同
	 */
	function c_close($isEditInfo = false) {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$object = $_POST[$this->objName];
		$id = $this->service->close_d($object, $isEditInfo);
		if ($id && $_GET['actType'] == "app") {
           succ_show('controller/contract/rental/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
		}else {
			msg('关闭成功！');
		}
	}

	/**
     * 关闭合同审批查看页
     */
    function c_toCloseView() {

    	$rows = $this->service->get_d($_GET['id']);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('view', $this->service->closeOrderView($_GET['id']));
    	$this->display('closeview');
    }


	/**
	 * 关闭审批tab
	 */
	function c_toCloseAuditTab() {
		$this->display('closeaudittab');
	}

	/**
	 * 未审批的关闭
	 */
	function c_toCloseAuditUndo() {
		$this->display('closeauditundo');
	}

	/**
	 * 未审批Json
	 */
	function c_jsonCloseAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '租赁合同异常关闭审批';
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 已审批的关闭
	 */
	function c_toCloseAuditDone() {
		$this->display('closeauditdone');
	}

	/**
	 * 已审批Json
	 */
	function c_closeAuditYesJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '租赁合同异常关闭审批';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
/**************************************************************/

    /**
     * 合同签收
     */
	function c_toSign() {
				$this->permCheck();//安全校验
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);


		//渲染从表

			//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
	    $rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));

				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->display('signin');
	}


	/**
	 * 合同签收
	 */
	function c_signInVerify($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->signin_d($object, $isEditInfo)) {
			msgRF('签收完成！');
		}
	}

	/**
	 * by maizp
	 * 转为正式合同
	 */
	 function c_toBecomeContract() {
		$this->permCheck();//安全校验
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_lease2' );
		//渲染从表
		$rows = $this->service->becomeEdit($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign("pId", $_GET['id']);
		$this->display("becomecontract");
	}

		/**
	 * by maizp
	 */
	 function c_become($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$orderCodeDao = new model_common_codeRule();
       if($object['orderInput'] == "1"){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_lease",$object['tenantId']);
			if($this->service->becomeEdit_d($object)){
				msgRF('已转为正式合同');
			}
		}else if($object['orderInput'] == "0"){
			if($this->service->becomeEdit_d($object)){
				msgRF('已转为正式合同');
			}
		}else {
			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
		}
	}

	/************************审批部分**************************/
	/**
	 * 跳转到我的审批Tab页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 下午02:46:46
	 * @author qian
	 */
	function c_toApprovalTab () {
		$this->display( 'tab-app' );
	}

	/**
	 * 审批中列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
	function c_toApprovalNo () {
		$this->display( 'approvalno' );
	}
	/**变更的合同审批Tab
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditTab () {
		$this->display('change-audit-tab');
	}
	/**未审批的变更合同
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditNo(){
		$this->display('change-auditno');
	}
	/**已审批的变更合同
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditYes(){
		$this->display('change-audityes');
	}
	/**未审批的变更合同PageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('change_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**已审批的变更服务合同PageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('change_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * add by can 2011-06-01
	 * 确认合同变更并且跳转到未审批的合同列表页
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contract = $this->service->get_d ( $objId );
				$contract1 = $this->service->get_d ( $contract['originalId'] );

				$changeLogDao = new model_common_changeLog ( 'rentalcontract' );
				$changeLogDao->confirmChange_d ( $contract );
				    if ($contract ['ExaStatus'] == "打回") { //打回情况
				            $sql = "update oa_sale_lease set isBecome = 0 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
							//查找有无借试用转销售物料，有则删除最近一条
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"lease","change",$objId);
					}else{
						$orderInfo = $this->service->get_d ( $contract['originalId'] );
						foreach ( $contract['rentalcontractequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['rentalcontractequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					      //查找有无借试用转销售物料
					      	  $dao = new model_projectmanagent_borrow_toorder();
			                  $borrowChange = $dao->getRelOrderequ($contract['originalId'],"lease","changeE",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							   $this->service->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_lease set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
						}
						//查找有无 借出单，有则自动生成归还单
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"lease");
					}
			}
		}

		//防止重复刷新
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//防止重复刷新
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=contract_rental_rentalcontract&action=allAuditingList'</script>";
		}
		//$this->display ( 'change-approvalNo' );
	}

 /**
     * 取消变更提醒
     */
    function c_cancelBecome(){
        $orderId = $_GET['id'];
        $sql = "update oa_sale_lease set isBecome = 0 where id = $orderId";
        $this->service->query($sql);
        return $orderId;
    }

	/**
	 * 合同查看页面
	 */
	function c_toViewTab() {
//        $this->permCheck ();//安全校验
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/**审批变更合同的查看页面
	*author can
	*2011-6-1
	*/
	function c_toReadTab() {
        $this->permCheck();
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$originalKey=$this->md5Row($rows ['originalId']);
		$this->assign ( 'orderCode', $rows ['orderCode'] );
		$this->assign ( 'originalId', $rows ['originalId'] );
		$this->assign ( 'originKey', $originalKey);
		$this->display('read-tab');
	}


	/**
	 * 未审批json
	 */
	function c_pageJsonNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 已审批列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
	function c_toApprovalYes () {
		$this->display( 'approvalyes' );
	}

	/**
	 * 已审批json
	 */
	function c_pageJsonYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


/***************************************************************************************************************/

	/**
	 * 合同详细内容看
	 */
	function c_toViewRContract(){
		$this->show->display( $this->objPath . '_' .$this->objName .'-view' );
	}

	/**
	 * 跳转到租赁合同Tab页面
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午02:58:32
	 * @author qian
	 */
	function c_toRentalConTab () {
		$this->show->display( $this->objPath . '_' . $this->objName . '-tab-add' );
	}

	/**
	 * 跳转到新建承租合同页面
	 * 即指跟别人租东西的合同页面
	 */
	function c_toAddRContract () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-add' );
	}


	/**
	 * 跳转到编辑租赁合同页面
	 */
	function c_toEditRContract () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-edit' );
	}

	/**
	 * 进行变更
	 *
	 * @return return_type
	 * @date 2011-1-22 下午01:45:06
	 * @author qian
	 */
	function c_toAppChange () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-change' );
	}

	/**
	 * 跳转到列表页面Tab页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:03:54
	 * @author qian
	 */
	function c_toRentConTabList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-tab-list' );
	}

	/**
	 * 待提交审批列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
	function c_toWaitApp () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-wait' );
	}

	/**
	 * 变更中列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
//	function c_toChange () {
//		$this->show->display( $this->objPath . '_' .$this->objName .'-list-change' );
//	}

	/**
	 * 我的审批变更中列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
	function c_toMyChangeList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-mychange' );
	}

	/**
	 * @author qian
	 * 完成合同需要确认
	 */
	function c_toVerify(){
		$this->show->display( $this->objPath . '_' . $this->objName . '-verify' );
	}

	/**
	 * 已完成列表页
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午04:20:30
	 * @author qian
	 */
	function c_toFinished () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list-finish' );
	}

	/**
	 * 跳转到合同列表--我申请的合同
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-21 下午05:09:44
	 * @author qian
	 */
	function c_toMyRContractList () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-list' );
	}

	/**
	 * 查看变更内容
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 下午02:23:49
	 * @author qian
	 */
	function c_toViewChange () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-view-change' );
	}

	/**
	 * 我的租赁合同变更审批--Tab
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 下午03:10:34
	 * @author qian
	 */
	function c_toChangeTab () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-tab-change' );
	}

	/**
	 * 我的租赁合同变更审批--未审批
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 下午03:12:08
	 * @author qian
	 */
	function c_toChangeNo () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-changeNo' );
	}

	/**
	 * 我的租赁合同变更审批--已审批
	 *
	 * @param tags
	 * @return return_type
	 * @date 2011-1-22 下午03:13:21
	 * @author qian
	 */
	function c_toChangeYes () {
		$this->show->display( $this->objPath . '_' .$this->objName .'-changeYes' );
	}

	/**
	 * @author qian
	 * 跳转到审批工作流的选择页面
	 */
	 function c_toChooseWorkFlow(){
	 	$this->show->display( $this->objPath . '_' .$this->objName .'-toChoose' );
	 }

	/**
	 * @author qian
	 * 我的租赁合同审批工作流
	 */
	 function c_toWorkFlow(){
	 	$this->show->display( $this->objPath . '_' .$this->objName .'-workflow' );
	 }

	 /**
	 * @author qian
	 * 审批工作流里的查看页面
	 */
	 function c_toViewWorkFlow(){
	 	$this->show->display( $this->objPath . '_' . $this->objName . '-view2' );
	 }

	 /**
	 * @author qian
	 * 审批
	 */
	 function c_approvalWorkFlow(){
	 	$this->show->display( $this->objPath . '_' . $this->objName . '-approval' );
	 }

	 /************************************************************************************************/
	     /**
     * 合并临时合同 获取合同内容
     */
	function c_ajaxList() {
		$rows = $this->service->get_d ( $_POST ['id'] );
		$rows = util_jsonUtil::encode ( $rows );
		echo $rows;
	}


	/**
	 * @ ajax判断项
	 * 临时合同号
	 */
	function c_ajaxOrderTempCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderTempCode']) ? $_GET['orderTempCode'] : false;
        $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	function c_ajaxOrderCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderCode']) ? $_GET['orderCode'] : false;

		 $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/*
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletesOrder() {
		//$this->permDelCheck ();
		$orderId = $_POST ['id'];
		$orderType = $_POST ['type'];
		try {
			//查找是否有 借试用转销售的关联物料 有则删除
			$dao = new model_projectmanagent_borrow_toorder();
			$dao->getRelOrderequ($orderId,$orderType);
			$del = $this->service->deletes_d ( $orderId );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
   /************************************************************************************************/
   /**
    * 发货需求查看
    */
   function c_toShipView(){
	    $rows = $this->service->get_d($_GET['id']);
		//渲染从表
			//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
		$rows = $this->service->initView($rows);


		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
            if ($rows['sign'] == '是') {
					$this->assign('sign', '是');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('sign', '否');
					};

			if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstate', '已提交');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstate', '已拿到');
					};
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view-ship');
		}
		/*******************************************************************************************************/

    /**********************开票收款权限部分*********************************/
    /**
     * 开票tab
     */
    function c_toInvoiceTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['租赁合同开票tab'])){
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**
	 * 开票申请tab
	 */
	function c_toInvoiceApplyTab() {
		$obj = $_GET ['obj'];
		if ($this->service->isKeyMan_d ( $obj ['objId'] ) || ! empty ( $this->service->this_limit ['租赁合同开票tab'] )) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj ['objType'] . '&obj[objCode]=' . $obj ['objCode'] . '&obj[objId]=' . $obj ['objId'] . '&skey=' . $_GET ['skey'];
			succ_show ( $url );
		} else {
			echo '没有权限,需要开通权限请联系oa管理员';
		}
	}


    /**
     * 到款tab
     */
    function c_toIncomeTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['租赁合同到款tab'])){
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**********************开票收款权限部分*********************************/
    /*
     * by MaiZhenPeng
     */
     function c_toContractListAll(){
		$this->display("listAll");
	}
	/***************************************/


/**
 * 我的合同 -- xxx （用于导出权限）
 */
 function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$orderDao = new model_projectmanagent_order_order();
//        $export = isset ( $service->this_limit ['单条合同导出'] ) ? $service->this_limit ['单条合同导出'] : null;

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/**
	 * 获取分页数据转成Json
	 */
	function c_myOrderPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $orderDao = new model_projectmanagent_order_order();
        $export = isset ( $service->this_limit ['单条合同导出'] ) ? $service->this_limit ['单条合同导出'] : null;
		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');

		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$rows = $service->isGoodsReturn ( $rows);
		foreach($rows as $k => $v){
             $rows[$k]['exportOrder'] = $export;
		   }
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		if($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']){
			$orderDao = new model_projectmanagent_order_order();
			$rows = $orderDao->filterContractMoney_d ($rows,$this->service->tbl_name);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 到款分配使用
	 */
	function c_orderForIncomePj(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		$orderDao = new model_projectmanagent_order_order();
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 从表选择物料处理配置
	 */
     function c_ajaxorder(){
        $isEdit = isset($_GET['isEdit'])?$_GET['isEdit'] : null;
        $configInfo = $this->service->c_configuration( $_GET['id'],$_GET['Num'],$_GET['trId'],$isEdit);
         echo $configInfo[0];
     }

    /**
     * 审批通过后发送邮件并跳回列表
     */
     function c_configOrder(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
       $this->service->configOrder_d($objId);
       $contract = $this->service->get_d ( $objId );
        if ($contract ['ExaStatus'] == "完成") {
          $toorderDao = new model_projectmanagent_borrow_toorder();
          $toorderDao->findLoan($objId,"lease");
        }
       echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
     }

     /**********************************************************************************************/
/**
 * 验证合同号的唯一性
 */
function c_ajaxCode(){
		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderCode'] ) ? $_GET ['ajaxOrderCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		}
		 }
function c_ajaxTempCode(){

		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderTempCode'] ) ? $_GET ['ajaxOrderTempCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		} }
}
?>