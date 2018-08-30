<?php
/**
 * @author Administrator
 * @Date 2011年9月13日 14:34:44
 * @version 1.0
 * @description:赠送申请控制层
 */
class controller_projectmanagent_present_present extends controller_base_action {

	function __construct() {
		$this->objName = "present";
		$this->objPath = "projectmanagent_present";
		parent::__construct ();
	 }

	/*
	 * 跳转到赠送申请
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

   /**
    * 赠送 --商机tab
    */
   function c_listChance(){
   	  $this->assign('chanceId',$_GET['chanceId']);
   	  $this->view("listchance");
   }

    /**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$chanceId = isset($_GET['id']) ? $_GET['id'] : null;
		$contractId = isset($_GET['contractId']) ? $_GET['contractId'] : null;
		if($chanceId){
			$this->permCheck($chanceId,'projectmanagent_chance_chance');
            $chanceDao = new model_projectmanagent_chance_chance();
		    $rows = $chanceDao->get_d($chanceId);
		    //复制license数据
		    $licenseDao = new model_yxlicense_license_tempKey();
		    $rows = $licenseDao->copyLicense($rows);

		    foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
		    //用于新增页源单类型
		    $singleType = "chance";
		}elseif($contractId){
			$conDao = new model_contract_contract_contract();
			$rows = $conDao->get_d($contractId);
			//用于新增页源单类型
			$singleType = "contract";
			$contractCode = $rows['contractCode'];
			$contractName = $rows['contractName'];
		}

		$this->assign('chanceId' ,$chanceId );
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('salesName' , $_SESSION['USERNAME']);
		$this->assign('salesNameId', $_SESSION['USER_ID']);
		$this->assign('createTime', day_date);
		$this->assign('SingleType' , isset($singleType) ? $singleType : null );
        $this->assign('customerName' , isset($rows['customerName']) ? $rows['customerName'] : null );
        $this->assign('customerId' , isset($rows['customerId']) ? $rows['customerId'] : null );
        $this->assign('areaName' , isset($rows['areaName']) ? $rows['areaName'] : null );
        $this->assign('areaCode' , isset($rows['areaCode']) ? $rows['areaCode'] : null );
        $this->assign('areaPrincipal' , isset($rows['areaPrincipal']) ? $rows['areaPrincipal'] : null );
        $this->assign('areaPrincipalId' , isset($rows['areaPrincipalId']) ? $rows['areaPrincipalId'] : null );
        /*************商机下推借试用冗余信息*******************/
        $this->assign('chanceCode' , isset($rows['chanceCode']) && $chanceId ? $rows['chanceCode'] : null );
        $this->assign('chanceId' , isset($rows['id']) && $chanceId ? $rows['id'] : null );
        /***************************************************/

        /*************合同下推借试用冗余信息*******************/
        $this->assign('contractCode' , isset($contractCode) && $contractId ? $contractCode : null );
        $this->assign('contractName' , isset($contractName) && $contractId ? $contractName : null );
        $this->assign('contractId' , isset($_GET['contractId']) && $contractId  ? $_GET['contractId'] : null );
        /***************************************************/
		$this->assign('salesName', $_SESSION['USERNAME']);
		$this->assign('salesNameId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('createTime', day_date);
		$this->view ( 'add' );
	}

    /**
     * 我的新增赠送页面
     */
    function c_myPresent(){
       $this->assign('userId', $_SESSION['USER_ID']);
       $this->display('mypresent');
    }

     /**
      * 合同列表Tab页---赠送
      */
     function c_presentOrder(){
     	$this->assign("orderId",$_GET['id']);
        $this->display('orderview');
     }
    /**
	 * 重写int
	 */
	function c_init() {
//		$this->permCheck();
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$rows = $this->service->get_d($_GET['id']);

		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
       $SingleType = $rows['SingleType'];
				switch($SingleType){
					case "" :
					    $this->assign('SingleType',"无");
					    $this->assign('singleCode',"无");
					    break;
					case "chance" :
					    $this->assign('SingleType',"商机");
					    $chacneId = $rows['chanceId'];
					    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='.$chacneId.'&perm=view\')">'.$rows['chanceCode'].'</span>';
					    $this->assign('singleCode',$code);
					    break;
					case "order" :
					    $this->assign('SingleType',"合同");
					    $orderId = $rows['orderId'];
					    $orderCode = $rows['orderCode'];
					    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='.$orderId.'&perm=view\')">'.$orderCode.'</span>';
					    $this->assign('singleCode',$code);
					    break;
				}
		if ($perm == 'view') {
			$this->assign ( 'orderCode', $this->service->OrderView ( $rows['orderId'],$rows['limits'], $rows['orderCode'] ) );
            //获取变更原因
             $changeDao = new model_common_changeLog("present");
             $changeReason = $changeDao->getObjByTempId($_GET['id']);
             $this->assign("changeReason",$changeReason[0]['changeReason']);
             $isTemp = $this->service->isTemp($_GET['id']);
             $this->assign("isTemp",$isTemp);
             $this->assign('originalId', $rows['originalId']);

            $this->view('view');
		} else {
			$this->view('edit');
		}
	}

	//变更查看
	function c_auditView(){
		$rows = $this->service->get_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
       $SingleType = $rows['SingleType'];
		switch($SingleType){
			case "" :
			    $this->assign('SingleType',"无");
			    $this->assign('singleCode',"无");
			    break;
			case "chance" :
			    $this->assign('SingleType',"商机");
			    $chacneId = $rows['chanceId'];
			    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='.$chacneId.'&perm=view\')">'.$rows['chanceCode'].'</span>';
			    $this->assign('singleCode',$code);
			    break;
			case "order" :
			    $this->assign('SingleType',"合同");
			    $orderId = $rows['orderId'];
			    $orderCode = $rows['orderCode'];
			    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='.$orderId.'&perm=view\')">'.$orderCode.'</span>';
			    $this->assign('singleCode',$code);
			    break;
		}
		$this->assign ( 'orderCode', $this->service->OrderView ( $rows['orderId'],$rows['limits'], $rows['orderCode'] ) );
        //获取变更原因
         $changeDao = new model_common_changeLog("present");
         $changeReason = $changeDao->getObjByTempId($_GET['id']);
         $this->assign("changeReason",$changeReason[0]['changeReason']);
         $isTemp = $this->service->isTemp($_GET['id']);
         $this->assign("isTemp",$isTemp);
         $this->assign('originalId', $rows['originalId']);
		$this->view("auditView");
	}

    /**
     * 查看tab
     */
    function c_viewTab(){
         $rows = $this->service->get_d ( $_GET ['id'] );
		$this->assign ( 'id', $_GET ['id'] );
		$this->assign ( 'originalId', $rows ['originalId'] );
		$this->display ( 'view-tab' );
    }
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$rows = $_POST [$this->objName];
        if($_GET ['act'] == "app"){
            $rows['dealStatus'] = ($rows['dealStatus'] != NULL)? $rows['dealStatus'] : 4;
            $rows['ExaStatus'] = '物料确认';
        }

		$id = $this->service->add_d ($rows , $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        //判断是否直接提交审批
		if ($id && $_GET ['act'] == "app") {
            msgRF ( "提交成功!等待赠送物料确认!" );
//			succ_show ( 'controller/projectmanagent/present/ewf_present.php?actTo=ewfSelect&billId=' . $id );
		}else{
			msgRF ( $msg );
		}
		//$this->listDataDict();
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
        if($_GET ['act'] == "app"){
            $object['dealStatus'] = ($object['dealStatus'] != NULL)? $object['dealStatus'] : 4;
            $object['ExaStatus'] = '物料确认';
        }
        $editResult = $this->service->edit_d ( $object, $isEditInfo );
        if ($editResult && $_GET ['act'] == "app") {
            msgRF ( "提交成功!等待赠送物料确认!" );
        }else if ($editResult) {
			msgRF ( '编辑成功！' );
		}
	}

    /**
     * 审批流回调方法
     */
    function c_dealAfterAudit(){
        if (! empty ( $_GET ['spid'] )) {
            //审批流回调方法
            $this->service->workflowCallBack_equConfirm($_GET['spid']);
        }
        $urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
        //防止重复刷新
        if($urlType){
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }else{
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }


/*********************************************************************************************************/

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
	 * 未审批Json
	 */
	function c_pageJsonAuditNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"presentId" );
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
		$rows = $this->sconfig->md5Rows ( $rows,"presentId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

/*********************************************************************************************************/
/**
 * 发货列表tab
 */
function c_toShipTab(){
      $this->display('ship-tab');
}


/**
 * 物料确认需求列表
 */
function c_toAssignments(){
      $this->display('assignments');
}


/**
 * 发货列表
 */
function c_toPresentShipments(){
      $this->display('shipments');
}

/**
 * 发货列表
 */
function c_toPresentShipped(){
      $this->display('shipped');
}
/**
 * 获取分页数据转成Json
 */
function c_shipmentsPageJson() {
	$rateDao = new model_stock_outplan_contractrate();
	$service = $this->service;
	$service->getParam ( $_REQUEST );
	//$service->asc = false;
	$otherDataDao = new model_common_otherdatas();
    $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
    if(!empty($sysLimit['销售区域'])){
    	if(!strstr($sysLimit['销售区域'],";;")){
    		$service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'".$sysLimit['销售区域']."')";
    	}
        $rows = $service->pageBySqlId('select_shipments');
    }else{
    	$rows = "";
    }

	$rows = $this->sconfig->md5Rows ( $rows );
    //发货需求进度备注
	$orderIdArr = array();
	foreach ( $rows as $key=>$val ){
		$orderIdArr[$key]=$rows[$key]['id'];
	}
	$orderIdStr = implode(',',$orderIdArr);
	$rateDao->searchArr['relDocIdArr']=$orderIdStr;
	$rateDao->asc=false;
	$rateArr = $rateDao->list_d();
	if(is_array($rows)&&count($rows)){
		foreach( $rows as $key=>$val ){
			$rows[$key]['rate']="";
			if(is_array($rateArr)&&count($rateArr)){
				foreach( $rateArr as $index=>$value ){
					if( $rows[$key]['id']==$rateArr[$index]['relDocId']&&'oa_present_present'==$rateArr[$index]['relDocType'] ){
						$rows[$key]['rate']=$rateArr[$index]['keyword'];
					}
				}
			}
		}
	}
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
function c_assignmentJson() {
	$rateDao = new model_stock_outplan_assignrate();
	$service = $this->service;
	$service->getParam ( $_REQUEST );
	//$service->asc = false;

	$otherDataDao = new model_common_otherdatas();
    $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
    if(!empty($sysLimit['销售区域'])){
    	if(!strstr($sysLimit['销售区域'],";;")){
    		$service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'".$sysLimit['销售区域']."')";
    	}
    	$rows = $service->pageBySqlId('select_assignment');
    }else{
    	$rows = "";
    }


	$rows = $this->sconfig->md5Rows ( $rows );
    //发货需求进度备注
	$orderIdArr = array();
	foreach ( $rows as $key=>$val ){
		$orderIdArr[$key]=$rows[$key]['id'];
        //判断如果是变更的单据，查找并替换单据ID
        if ($val['changeTips'] == '1' && $val['dealStatus'] != '1') {
            $mid = $this->service->findChangeId($val['id']);
            $rows[$key]['id'] = $mid;
            $rows[$key]['oldId'] = $val['id'];
        }else{
            $rows[$key]['oldId'] = $val['id'];
        }
	}

	$orderIdStr = implode(',',$orderIdArr);
	$rateDao->searchArr['relDocIdArr']=$orderIdStr;
	$rateDao->searchArr['relDocType']='oa_present_present';
	$rateDao->asc=false;
	$rateArr = $rateDao->list_d();
	if(is_array($rows)&&count($rows)){
		foreach( $rows as $key=>$val ){
			$rows[$key]['rate']="";
			if(is_array($rateArr)&&count($rateArr)){
				foreach( $rateArr as $index=>$value ){
					if( $rows[$key]['id']==$rateArr[$index]['relDocId']&&'oa_present_present'==$rateArr[$index]['relDocType'] ){
						$rows[$key]['rate']=$rateArr[$index]['keyword'];
					}
				}
			}
		}
	}
	$arr = array ();
	$arr ['collection'] = $rows;
	//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
	$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
	$arr ['page'] = $service->page;
    $arr ['pageSql'] = $service->listSql;
	echo util_jsonUtil::encode ( $arr );
}

    /**
     * 根据客户或区域ID获取对应信息
     */
    function c_getCustomAndAreaInfo(){
        $customerId = isset($_REQUEST['customerId'])? $_REQUEST['customerId'] : '';
        $areaId = isset($_REQUEST['areaId'])? $_REQUEST['areaId'] : '';
        $datadictDao = new model_system_datadict_datadict ();

        $resultArr = array();
        $resultArr['customerInfo'] = array();
        $resultArr['areaInfo'] = array();
        // 获取客户信息
        if($customerId != ''){
            $customerDao = new model_customer_customer_customer();
            $customerArr = $customerDao->get_d($customerId);
            $customerArr['TypeOneName'] =  $datadictDao->getDataNameByCode ( $customerArr['TypeOne'] );

            // 省份或城市信息为空的替换为该客户的国家信息 PM2468
            $customerArr['Prov'] = ($customerArr['Country'] != '中国')? $customerArr['Country'] : $customerArr['Prov'];
            $customerArr['City'] = ($customerArr['Country'] != '中国')? $customerArr['Country'] : $customerArr['City'];

            $resultArr['customerInfo'] = $customerArr;
        }

        // 获取区域信息
        if($areaId != ''){
            $areaDao = new model_system_region_region();
            $salepersonDao = new model_system_saleperson_saleperson();
            $areaArr = $areaDao->get_d($areaId);
            $areaArr['moduleName'] = ($areaArr['moduleName'] == '')? $datadictDao->getDataNameByCode ( $areaArr['module'] ) : $areaArr['moduleName'];
            $resultArr['areaInfo']['module'] = array();
            $resultArr['areaInfo']['module']['module'] = $areaArr['module'];
            $resultArr['areaInfo']['module']['moduleName'] = $areaArr['moduleName'];

            $condition['salesAreaId'] = $areaId;
            $salepersonArr = $salepersonDao->findAll($condition);
            $resultArr['areaInfo']['businessBelong'] = array();
            $catchBusinessArr = array();// 用于去重
            foreach ($salepersonArr as $k => $v){
                if($v['businessBelong'] != '' && $v['businessBelongName'] != '' && !in_array($v['businessBelong'],$catchBusinessArr)){
                    $catchBusinessArr[] = $v['businessBelong'];
                    $businessBelongs['businessBelong'] = $v['businessBelong'];
                    $businessBelongs['businessBelongName'] = $v['businessBelongName'];
                    $resultArr['areaInfo']['businessBelong'][] = $businessBelongs;
                }
            }
        }
        echo util_jsonUtil::encode ( $resultArr );
    }

	/*******************************变更   开始***************************************************/
	function c_toChange() {
		$changeLogDao = new model_common_changeLog ('borrow');
		if($changeLogDao->isChanging($_GET['id'])){
        	msgGo ( "该合同已在变更审批中，无法变更." );
        }
        //临时记录id
        $tempId = isset($_GET['tempId']) ? $_GET['tempId'] : '';
        //判断是否存在临时保存的记录
        if(empty($tempId)){
        	$sql = "select tempId,ExaStatus from oa_present_changlog where id = (select max(id) as id from oa_present_changlog " .
        			"where objType = 'present' and objId = ". $_GET['id'] ." and changeManId = '" . $_SESSION['USER_ID'] . "')";
        	$rs = $this->service->_db->getArray($sql);
        	$tempId = !empty($rs) && $rs[0]['ExaStatus'] != AUDITED ? $rs[0]['tempId'] : '';
        }else{
        	$sql = "select changeReason from oa_present_changlog where objType = 'present' and tempId = ". $tempId;
        	$rs = $this->service->_db->getArray($sql);
        	$changeReason = !empty($rs) ? $rs[0]['changeReason'] : '';
        }
        $this->assign('tempId', $tempId);
        $this->assign('changeReason', isset($_GET['tempId']) && !empty($changeReason) ? $changeReason : '');//变更原因
        $presentId = isset($_GET['tempId']) ? $_GET['tempId'] : $_GET['id'];
        $this->assign('presentId', $presentId);
        $rows = $this->service->get_d($presentId);
		//附件
		$rows ['file'] = $this->service->getFilesByObjId ( $rows ['id'], true );
		$rows = $this->service->initChange ( $rows );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//id始终为源单id
		if(isset($_GET['tempId'])){
			$this->assign('id', $_GET['id']);
		}
		$SingleType = $rows['SingleType'];
				switch($SingleType){
					case "" :
					    $this->assign('SingleType',"无");
					    $this->assign('singleCode',"无");
					    break;
					case "chance" :
					    $this->assign('SingleType',"商机");
					    $chacneId = $rows['chanceId'];
					    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='.$chacneId.'&perm=view\')">'.$rows['chanceCode'].'</span>';
					    $this->assign('singleCode',$code);
					    break;
					case "order" :
					    $this->assign('SingleType',"合同");
					    $orderId = $rows['orderId'];
					    $orderCode = $rows['orderCode'];
					    $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='.$orderId.'&perm=view\')">'.$orderCode.'</span>';

					    $this->assign('singleCode',$code);
					    break;
				}
           $this->view ( 'change' );
	}

	/**
	 * 变更方法
	 */
	function c_change(){
		try {
			$Info = $_POST ['present'];
		    foreach($Info['presentequ'] as $key =>$val){
				if (empty($val['productName'])){
					unset($Info['presentequ'][$key]);
				}
			}
			foreach($Info['presentequTemp'] as $key =>$val){
				if (empty($val['productName'])){
					unset($Info['presentequTemp'][$key]);
				}
			}
			$id = $this->service->change_d ($Info);
			if($Info['isSub'] == '0'){
				msg('保存成功！');
			}else{
                $newObj = $this->service->find(array("id" => $id));
			    $updateData = array(
                    "dealStatus" => "2",
                    "ExaStatus" => "物料确认",
                    "changeTips" => 1
                );
                $this->service->update(array("id" => $id),$updateData);
                $this->service->update(array("id" => $newObj['originalId']),$updateData);
                msg('提交成功!等待赠送物料确认!');
//				echo "<script>this.location='controller/projectmanagent/present/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}
	/**
	 * 变更审批通过后 处理方法
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			//审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
		}
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//防止重复刷新
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}
	}
	/*******************************变更  end***************************************************/


	/*************************************设备总汇表 start **************************************/
	/**
	 * 合同发货设备-计划统计列表
	 */
	function c_shipEquList(){
		$equNo = isset( $_GET['productNo'] )?$_GET['productNo']:"";
		$equName = isset( $_GET['productName'] )?$_GET['productName']:"";
		$searchArr = array();
		if($equNo!=""){
			$searchArr['productNo'] = $equNo;
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
 }
?>