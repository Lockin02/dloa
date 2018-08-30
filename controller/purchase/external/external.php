<?php
/* 采购计划统一接口控制层
 * Created on 2011-3-9
 * Created on by can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class controller_purchase_external_external extends controller_base_action {
	function __construct(){
		$this->objName="external";
		$this->objPath="purchase_external";
		parent::__construct ();
	}

	/**
	 * 统一跳转到采购计划添加页面
	 */
	function c_toAdd () {
		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);
		$this->assign('purchType',$_GET['purchType']);
		$sourceId=$_GET['sourceId'];

		$this->assign('emailID',$this->service->_emailID);
		$this->assign('emailName',$this->service->_emailName);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
		/*根据$sournceId来判断是从采购计划统一接口下达采购计划还是从各业务下达采购计划*/
		if(!empty($sourceId)){
			$this->assign('sourceId',$sourceId);
			//根据不同类型的单据ID，获取其信息
			$source=$this->service->getInfoList_d($sourceId,$_GET['purchType']);
			$this->assign('sourceName',$source['sourceName']);
			$this->assign('sourceCode',$source['sourceCode']);
		}else{
			$this->assign('sourceName','');
			$this->assign('sourceCode','');
			$this->assign('sourceId','');
		}
		$this->view('plan-add',true);
	}

	/**
	 * 编辑采购申请
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_edit () {
		$basic=$_POST['basic'];
		if($basic['ExaStatus']=="物料确认打回"){
			$object=$this->service->editBack_d($basic);
		}else{
			$object=$this->service->edit_d($basic);
		}
		if($object){
			msgGo('保存成功','index1.php?model=purchase_plan_basic&action=myApplyList');
		}else{
			msgGo('保存失败','index1.php?model=purchase_plan_basic&action=myApplyList');

		}
	}

	/**
	 *
	 * 采购申请确认
	 */
	function c_confirm(){
		$object=$this->service->confirm_d($_POST['basic']);
		if($object){
			echo "<script>alert('确认成功!');window.opener.window.show_page();window.close();</script>";
		}else{
			echo "<script>alert('确认失败!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 编辑采购申请时直接提交审批
	 */
	function c_toSubmitAuditByEdit () {
		$basic=$_POST['basic'];
		if($basic['ExaStatus']=="物料确认打回"){
			$this->service->editBack_d($basic);
		}else{
			$this->service->edit_d($basic);
		}
		switch($_POST['basic']['purchType']){
			case 'assets':
					succ_show ( 'controller/purchase/plan/ewf_index.php?actTo=ewfSelect&billId='.$_POST[basic][id].'&examCode=oa_purch_plan_basic&formName=资产采购申请审批&billDept='.$_POST['basic']['departId'] );
					break;
			case 'rdproject':
					succ_show ( 'controller/purchase/plan/ewf_rdproject_index.php?actTo=ewfSelect&billId='.$_POST[basic][id].'&examCode=oa_purch_plan_basic&formName=研发采购申请审批&billDept='.$_POST['basic']['departId'] );
					break;
			case 'produce':
					succ_show ( 'controller/purchase/plan/ewf_produce_index.php?actTo=ewfSelect&billId='.$_POST[basic][id].'&examCode=oa_purch_plan_basic&formName=生产采购申请审批' );
					break;
		}
	}

	/**跳转到资产采购申请页面
	*author can
	*2011-7-5
	*/
	function c_toAssetAdd(){
		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);
		$this->assign("createId" , $_SESSION['USER_ID']);
		$this->assign("createName" , $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));

		$this->createSubmitTag();//创建重复提交校验
		$this->show->display('purchase_external_assets-add');
	}

	/**
	 *
	 * 研发采购
	 */
	function c_toRdAdd(){
		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $this->view("rd-add",true);
	}

	/**跳转到研发采购申请页面
	*author can
	*2011-7-5
	*/
	function c_toRdprojectAdd(){
		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);
		$this->assign("createId" , $_SESSION['USER_ID']);
		$this->assign("createName" , $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));

		$this->createSubmitTag();//创建重复提交校验
		$this->show->display('purchase_external_rdproject-add');
	}

	/**跳转到生产采购申请页面
	*author can
	*2011-7-5
	*/
	function c_toProduceAdd(){
		$this->assign("sendTime" , date("Y-m-d"));
		$this->assign("dateHope" , date("Y-m-d"));
		$this->assign("sendUserId" , $_SESSION['USER_ID']);
		$this->assign("sendName" , $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $purchDepart = $deptmentDao->getDeptId_d('交付部');
        $this->assign('purchDepart','交付部');
        $this->assign('purchDepartId',$purchDepart['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->createSubmitTag();//创建重复提交校验
		$this->show->display('purchase_external_produce-add');
	}

	/**
	 * 原料计算跳转到采购申请页面
	 */
	function c_toAddByMaterial() {
		$data = $_POST['data'][0];
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($data['planId']);
		if (is_array($planObj)) {
			$this->assignFunc($planObj);
		} else {
			$planArr = array('productionBatch' ,'relDocCode');
			foreach ($planArr as $key => $val) {
				$this->assign($val ,'');
			}
		}

		unset($data['planId']);
		unset($data['bomConfigId']);
		if (is_array($data)) {
			$productDao = new model_stock_productinfo_productinfo();
			$productObjs = array();
			$rows = array(); //从表的数据
			foreach ($data as $key => $val) {
				if ($val > 0) {
					$tmp = array();
					if (empty($productObjs[$key])) {
						$productObj = $productDao->find(array('productCode' => $key));
						$productObjs[$key] = $productObj;
					}
					$tmp['productId'] = $productObjs[$key]['id'];
					$tmp['productNumb'] = $productObjs[$key]['productCode'];
					$tmp['productName'] = $productObjs[$key]['productName'];
					$tmp['pattern'] = $productObjs[$key]['pattern'];
					$tmp['unitName'] = $productObjs[$key]['unitName'];
					$tmp['productTypeId'] = $productObjs[$key]['proTypeId'];
					$tmp['productTypeName'] = $productObjs[$key]['proType'];
					$tmp['leastPackNum'] = $productObjs[$key]['leastPackNum'];
					$tmp['leastOrderNum'] = $productObjs[$key]['leastOrderNum'];
					$tmp['amountAll'] = $val;
					$tmp['dateIssued'] = day_date;
					$tmp['dateHope'] = day_date;
					array_push($rows ,$tmp);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->assign("sendTime", date("Y-m-d"));
		$this->assign("dateHope", date("Y-m-d"));
		$this->assign("sendUserId", $_SESSION['USER_ID']);
		$this->assign("sendName", $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao = new model_common_otherdatas();
		$deptmentDao = new model_deptuser_dept_dept();
		$this->assign('department' ,$_SESSION['DEPT_NAME']);
		$this->assign('departId' , $_SESSION['DEPT_ID']);
		$purchDepart = $deptmentDao->getDeptId_d('交付部');
		$this->assign('purchDepart' ,'交付部');
		$this->assign('purchDepartId' ,$purchDepart['DEPT_ID']);

		$this->view('add-material' ,true);
	}

	/**
	 * 物料统计计算跳转到采购申请页面--生产计划
	 */
	function c_toAddByCaculate() {
		$planArr = array('productionBatch' ,'relDocCode'); // 没有生产计划单需要的信息
		foreach ($planArr as $key => $val) {
			$this->assign($val ,'');
		}
		$produceplanDao = new model_produce_plan_produceplan();
		$datas = $produceplanDao->get_produceTask($_GET['productCodes'],$_GET['taskIds']);
		if (is_array($datas)) {
			$idArr = array();
			foreach ($datas as $v){
				array_push($idArr, $v['id']);
			}
			//获取采购的部分
			$equDao = new model_purchase_plan_equipment();
			$equArr = $equDao->findAll("applyEquId IN(".implode(',', $idArr).") AND purchType = 'produce'",null,"applyEquId,amountAll");
			$productDao = new model_stock_productinfo_productinfo();
			$productObjs = array();
			$rows = array(); //从表的数据
			foreach ($datas as $key => &$val) {
				//过滤掉已经下达采购的部分
				if(!empty($equArr)){
					foreach ($equArr as $v){
						if($val['id'] == $v['applyEquId']){
							$val['num'] -=  $v['amountAll'];
						}
					}
				}
				if($val['num'] > 0){
					if (empty($productObjs[$val['productId']])) {
						$productObj = $productDao->get_d($val['productId']);
						$productObjs[$val['productId']] = $productObj;
					}
					$val['applyEquId']      = $val['id'];
					$val['productNumb']     = $val['productCode'];
					$val['productTypeId']   = $productObjs[$val['productId']]['proTypeId'];
					$val['productTypeName'] = $productObjs[$val['productId']]['proType'];
					$val['leastPackNum']    = $productObjs[$val['productId']]['leastPackNum'];
					$val['leastOrderNum']   = $productObjs[$val['productId']]['leastOrderNum'];
					$val['amountAll']       = $val['num'];
					$val['dateIssued']      = day_date;
					$val['dateHope']        = day_date;
					array_push($rows ,$val);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->assign("sendTime", date("Y-m-d"));
		$this->assign("dateHope", date("Y-m-d"));
		$this->assign("sendUserId", $_SESSION['USER_ID']);
		$this->assign("sendName", $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao = new model_common_otherdatas();
		$deptmentDao = new model_deptuser_dept_dept();
		$this->assign('department' ,$_SESSION['DEPT_NAME']);
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $purchDepart = $deptmentDao->getDeptId_d('交付部');
        $this->assign('purchDepart' ,'交付部');
        $this->assign('purchDepartId' ,$purchDepart['DEPT_ID']);

		$this->view('add-material' ,true);
	}

	/**
	 * 物料统计计算跳转到采购申请页面--生产任务
	 */
	function c_toAddByCaculateTask() {
		$planArr = array('productionBatch' ,'relDocCode'); // 没有生产计划单需要的信息
		foreach ($planArr as $key => $val) {
			$this->assign($val ,'');
		}
		$producetaskDao = new model_produce_task_producetask();
		$datas = $producetaskDao->get_produceTask($_GET['productCodes'],$_GET['taskIds']);
		if (is_array($datas)) {
			$idArr = array();
			foreach ($datas as $v){
				array_push($idArr, $v['id']);
			}
			//获取采购的部分
			$equDao = new model_purchase_plan_equipment();
			$equArr = $equDao->findAll("applyEquId IN(".implode(',', $idArr).") AND purchType = 'produce'",null,"applyEquId,amountAll");
			$productDao = new model_stock_productinfo_productinfo();
			$productObjs = array();
			$rows = array(); //从表的数据
			foreach ($datas as $key => &$val) {
				//过滤掉已经下达采购的部分
				if(!empty($equArr)){
					foreach ($equArr as $v){
						if($val['id'] == $v['applyEquId']){
							$val['num'] -=  $v['amountAll'];
						}
					}
				}
				if($val['num'] > 0){
					if (empty($productObjs[$val['productId']])) {
						$productObj = $productDao->get_d($val['productId']);
						$productObjs[$val['productId']] = $productObj;
					}
					$val['applyEquId']      = $val['id'];
					$val['productNumb']     = $val['productCode'];
					$val['productTypeId']   = $productObjs[$val['productId']]['proTypeId'];
					$val['productTypeName'] = $productObjs[$val['productId']]['proType'];
					$val['leastPackNum']    = $productObjs[$val['productId']]['leastPackNum'];
					$val['leastOrderNum']   = $productObjs[$val['productId']]['leastOrderNum'];
					$val['amountAll']       = $val['num'];
					$val['dateIssued']      = day_date;
					$val['dateHope']        = day_date;
					array_push($rows ,$val);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->assign("sendTime", date("Y-m-d"));
		$this->assign("dateHope", date("Y-m-d"));
		$this->assign("sendUserId", $_SESSION['USER_ID']);
		$this->assign("sendName", $_SESSION['USERNAME']);

		//获取部门ID
		$deptDao = new model_common_otherdatas();
		$deptmentDao = new model_deptuser_dept_dept();
		$this->assign('department' ,$_SESSION['DEPT_NAME']);
		$this->assign('departId' , $_SESSION['DEPT_ID']);
		$purchDepart = $deptmentDao->getDeptId_d('交付部');
		$this->assign('purchDepart' ,'交付部');
		$this->assign('purchDepartId' ,$purchDepart['DEPT_ID']);

		$this->view('add-material' ,true);
	}

	/*===================================业务处理======================================*/
	/**
	 * 根据采购类型和物料所关联的主表ID，获取物料清单
	 */
	function c_addItemList () {
		$parentId=isset($_POST['parentId'])?$_POST['parentId']:null;
		$purchType=isset($_POST['purchType'])?$_POST['purchType']:null;
		//根据不同类型的采购计划，获取其物料清单
		$itemRows=$this->service->getItemsByParentId_d($purchType,$parentId);
		//根据不同类型的单据ID，获取其信息，并用于保存信息到关联表
		$mianRows=$this->service->getInfoList_d($parentId,$purchType);
		//获取添加采购计划时的物料清单列表
		$itemList=$this->service->showAddList_d($itemRows,$mianRows,$purchType);
		echo $itemList;
	}


	/**
	 *下达采购计划
	 */
	function c_addByMaterial() {
		$this->checkSubmit();
		if( $_POST['basic']['ismail']==1 ){
			$mailArr = $_POST['basic']['email'];
			unset( $_POST['basic']['ismail'] );
			unset( $_POST['basic']['email'] );
		}
		$id=$this->service->add_d($_POST['basic']);
		if( $_POST['basic']['ismail']==1 ){
			$this->service->sendEmail_d($id,$mailArr);
		}
		//$purchType用于判断调用各业务具体的跳转方法还是调用 父类的跳转方法
		echo "<script>alert('保存成功!');window.opener.window.close();window.close();</script>";
	}

	/**
	 *下达采购计划
	 */
	function c_add() {
		$this->checkSubmit();
		if( $_POST['basic']['ismail']==1 ){
			$mailArr = $_POST['basic']['email'];
			$isEmail = $_POST['basic']['ismail'];
			unset( $_POST['basic']['ismail'] );
			unset( $_POST['basic']['email'] );
		}
		$id=$this->service->add_d($_POST['basic']);
		if( $isEmail ){
			$this->service->sendEmail_d($id,$mailArr);
		}
		//$purchType用于判断调用各业务具体的跳转方法还是调用 父类的跳转方法
		$this->service->toShowPage($id,$_POST['basic']['purchType'],$_POST['external']['purchType']);
	}

	/**
	 * 进行采购申请时直接提交审批
	 */
	function c_toSubmitAudit () {
		$id=$this->service->add_d($_POST['basic']);
		if($id){
			switch($_POST['basic']['purchType']){
				case 'assets':
						succ_show ( 'controller/purchase/plan/ewf_index2.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purch_plan_basic&formName=资产采购申请审批&billDept='.$_POST['basic']['departId']  );
						break;
				case 'rdproject':
						succ_show ( 'controller/purchase/plan/ewf_rdproject_index2.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purch_plan_basic&formName=研发采购申请审批&billDept='.$_POST['basic']['departId'] );
						break;
				case 'produce':
						succ_show ( 'controller/purchase/plan/ewf_produce_index2.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purch_plan_basic&formName=生产采购申请审批' );
						break;
			}
		}else{
			$this->service->toShowPage($id,$_POST['basic']['purchType'],$_POST['external']['purchType']);
		}
	}

	/**
	 * 下达采购计划
	 */
	 function c_purchasePlan (){
         $sendId="";
         $senName="";
	 	switch($_GET['purchType']){
	 		case 'oa_sale_order' :
	 			$modelName="projectmanagent_order_order";
	 			break;
	 		case 'oa_sale_lease' :
	 			$modelName="contract_rental_rentalcontract";
	 			break;
	 		case 'oa_sale_service' :
	 			$modelName="engineering_serviceContract_serviceContract";
	 			break;
	 		case 'oa_sale_rdproject' :
	 			$modelName="rdproject_yxrdproject_rdproject";
	 			break;
	 		case 'oa_borrow_borrow' :
	 			$modelName="projectmanagent_borrow_borrow";
                $row=$this->service->getInfoList_d($_GET['orderId'],$_GET['purchType']);
                if($row){
                    $sendId=$row['salesNameId'];
                    $senName=$row['salesName'];
                }
	 			break;
	 		case 'oa_present_present' :
	 			$modelName="projectmanagent_present_present";
                $row=$this->service->getInfoList_d($_GET['orderId'],$_GET['purchType']);
                if($row){
                    $sendId=$row['salesNameId'];
                    $senName=$row['salesName'];
                }
	 			break;
	 	}
		$this->permCheck($_GET['orderId'],$modelName);
	 	$orderId = $_GET['orderId'];
	 	$this->assign( 'sourceID',$_GET['orderId'] );
	 	$this->assign( 'sourceNumb',$_GET['orderCode'] );
	 	$equIds=isset($_GET['equIdArr'])?$_GET['equIdArr']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$tDay = date('Y-m-d');
// 		$orderInfo = $this->service->getItemsByParentId_d( $purchType,$orderId );
// 		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
// 		if($equIds){
// 			$objDao=new model_projectmanagent_borrow_borrow();
// 			$orderInfo = $objDao->getItemsByEquIds( $equIds);
// 		}else{
// 			$orderInfo = $this->service->getItemsByParentId_d( $purchType,$orderId );
// 		}
// 		foreach ( $orderInfo as $key=>$val){
// 			if($_GET['purchType']=='oa_borrow_borrow'){
// 				$orderInfo[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType( $orderInfo[$key]['productId'], 'outStockCode' );
// 			}else{
// 				$orderInfo[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType( $orderInfo[$key]['productId'], 'salesStockCode' );
// 			}
// 		}
// 		$detail = $this->service->showAddList_d( $orderInfo,null,$purchType );
// 		$this->assign( 'detail',$detail );
	 	$sendTime = date("Y-m-d");
	 	$this->assign( 'sendTime',$sendTime );
	 	$this->assign( 'objCode',$_GET['objCode'] );
	 	$this->assign( 'contractName',$_GET['orderName'] );
	 	$this->assign('equIds',$equIds);
	 	$this->assign( 'purchType',$purchType );
	 	$this->assign('equIds',$equIds);
	 	$this->assign( 'sendUserId',$_SESSION['USER_ID'] );
	 	$this->assign( 'sendName',$_SESSION['USERNAME'] );
		$interfObj = new model_common_interface_obj();
	 	$this->assign( 'purchTypeName',$interfObj->typeKToC( $purchType ));

		//获取默认邮件接收人
		$mailRecieve = $this->service->getSendMen_d();
         if($sendId!=""){
             $mailRecieve['TO_ID']=$mailRecieve['TO_ID'].",".$sendId;
             $mailRecieve['TO_NAME']=$mailRecieve['TO_NAME'].",".$senName;
         }
		$this->assignFunc($mailRecieve);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $purchDepart = $deptmentDao->getDeptId_d('交付部');
        $this->assign('purchDepart','交付部');
        $this->assign('purchDepartId',$purchDepart['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->assign('tDay',$tDay);
	 	//$this->show->displayview( "purchase_external_external-cont-add" );
	 	$this->view('cont-add',true);
	 }

	/**
	 * 新合同管理下达采购申请
	 *
	 */
	 function c_toAddByContract(){
	 	$contractId = $_GET['contractId'];
	 	$this->assign( 'sourceID',$_GET['contractId']);
	 	$this->assign( 'sourceNumb',$_GET['contractCode'] );
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$equIds=isset($_GET['equIds'])?$_GET['equIds']:null;
         //获取合同信息
         $row=$this->service->getInfoList_d($_GET['contractId'],$purchType);
// 		if($equIds){
// 			$objDao=new model_purchase_external_contract();
// 			$orderInfo = $objDao->getItemsByEquIds( $equIds);
// 		}else{
// 			$orderInfo = $this->service->getItemsByParentId_d( $purchType,$contractId );
// 		}
// 		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
// 		foreach ( $orderInfo as $key=>$val){
// 			if($_GET['purchType']=='oa_borrow_borrow'){
// 				$orderInfo[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType( $orderInfo[$key]['productId'], 'outStockCode' );
// 			}else{
// 				$orderInfo[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType( $orderInfo[$key]['productId'], 'salesStockCode' );
// 			}
// 		}
// 		$detail = $this->service->showAddList_d( $orderInfo,null,$purchType );
// 		$this->assign( 'detail',$detail );
	 	$sendTime = date("Y-m-d");
	 	$this->assign('equIds',$equIds);
	 	$this->assign( 'sendTime',$sendTime );
	 	$this->assign( 'objCode',$_GET['objCode'] );
	 	$this->assign('contractId',$_GET['contractId']);
	 	$this->assign( 'contractName',$_GET['contractName'] );
	 	$this->assign( 'purchType',$purchType );
	 	$this->assign( 'sendUserId',$_SESSION['USER_ID'] );
	 	$this->assign( 'sendName',$_SESSION['USERNAME'] );
		$interfObj = new model_common_interface_obj();
	 	$this->assign( 'purchTypeName',$interfObj->typeKToC( $purchType ));

		//获取默认邮件接收人
		$mailRecieve = $this->service->getSendMen_d();
         if($row){
             $mailRecieve['TO_ID']=$mailRecieve['TO_ID'].",".$row['prinvipalId'];
             $mailRecieve['TO_NAME']=$mailRecieve['TO_NAME'].",".$row['prinvipalName'];
         }
		$this->assignFunc($mailRecieve);

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('department' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('departId' , $_SESSION['DEPT_ID']);
        $purchDepart = $deptmentDao->getDeptId_d('交付部');
        $this->assign('purchDepart','交付部');
        $this->assign('purchDepartId',$purchDepart['DEPT_ID']);
		$this->showDatadicts(array('qualityList'=>'CGZJSX'));
		$this->view( "contract-add",true );
	 }

	/**
	 * 合同采购列表
	 */
	 function c_contPurchPage(){
	 	$this->assign('purchType',$_GET['objType']);
	 	$this->assign('orderId',$_GET['orderId']);
	 	$this->display('pagebyorder');
	 }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageByOrder() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
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
	 * 获取所有数据返回json
	 */
	function c_externalJson() {
		$service = $this->service;
		$purchType = $_POST['purchType'];
		$equIds = $_POST['equIds'];
		$borrowId = $_POST['borrowId'];
		$contractId = $_REQUEST['contractId'];
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$lockDao=new model_stock_lock_lock();
		switch ($purchType){
			case "oa_present_present" :
				$detailDao = new model_projectmanagent_present_presentequ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['presentId'] = $borrowId;
				}
				break;
			case "oa_borrow_borrow" :
				$detailDao = new model_projectmanagent_borrow_borrowequ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['borrowId'] = $borrowId;
				}
				break;
			case "HTLX-XSHT" :
				$detailDao = new model_contract_contract_equ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['contractId'] = $contractId;
				}
				break;
			case "HTLX-ZLHT" :
				$detailDao = new model_contract_contract_equ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['contractId'] = $contractId;
				}
				break;
			case "HTLX-FWHT" :
				$detailDao = new model_contract_contract_equ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['contractId'] = $contractId;
				}
				break;
			case "HTLX-YFHT" :
				$detailDao = new model_contract_contract_equ;
				$detailDao->getParam ( $_REQUEST );
				if(!empty($equIds)){
					$detailDao->searchArr ['equIds'] = $equIds;
				}else{
					$detailDao->searchArr ['contractId'] = $contractId;
				}
				break;
		}
		$rows = $detailDao->list_d ();
		$dateIssued = date("Y-m-d");
		foreach($rows as $k=>$v){
			$lockNum=$lockDao->getEquStockLockNum($v['id']);
			$rows[$k]['issuedNum'] = $rows[$k]['number'] - $lockNum - $rows[$k]['issuedPurNum'] - $rows[$k]['issuedProNum'];
			if($_POST['purchType']=='oa_borrow_borrow'){
				$rows[$k]['exeNum'] = $inventoryDao->getExeNumsByStockType( $rows[$k]['productId'], 'outStockCode' );
				$rows[$k]['exeNum'] = !empty($rows[$k]['exeNum'])?$rows[$k]['exeNum']:0;
			}else{
				$rows[$k]['exeNum'] = $inventoryDao->getExeNumsByStockType( $rows[$k]['productId'], 'salesStockCode' );
				$rows[$k]['exeNum'] = !empty($rows[$k]['exeNum'])?$rows[$k]['exeNum']:0;
			}
			if($purchType == "HTLX-XSHT" || $purchType == "HTLX-ZLHT" || $purchType == "HTLX-FWHT" || $purchType == "HTLX-YFHT"){
				$rows[$k]['productNumb'] = $rows[$k]['productCode'];
			}else{
				$rows[$k]['productNumb'] = $rows[$k]['productNo'];
			}
			$rows[$k]['dateIssued'] = $dateIssued;
			$rows[$k]['purchType'] = $purchType;
			$rows[$k]['pattem'] = $rows[$k]['productModel'];
			$rows[$k]['qualityCode'] = $rows[$k]['equipment'];
			$rows[$k]['contNum'] = $rows[$k]['number'];
			$rows[$k]['remainNum'] = $rows[$k]['issuedNum'];
			$rows[$k]['amountAll'] = $rows[$k]['issuedNum'];
			$rows[$k]['applyEquId'] = $rows[$k]['id'];
			$rows[$k]['equObjAssId'] = $rows[$k]['id'];
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ajax根据物料ID获取库存数量
	 */
	function c_ajaxGetStockNum() {
		$productId = $_POST['productId'];
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$systeminfoDao = new model_stock_stockinfo_systeminfo();
		$stockSysObj = $systeminfoDao->get_d("1");
		$saleStockId = $stockSysObj['salesStockId'];
		$inventoryDao->searchArr = array("stockId"=>$saleStockId ,"productId"=>$productId);
		$inventoryArr = $inventoryDao->listBySqlId();
		$stockNum = $inventoryArr[0]['exeNum'];
		if(!is_array($inventoryArr)){
			$stockNum = "0";
		}
		echo $stockNum;
	}

	 /**
	  * ajax 检查下达采购数量是否被允许
	  */
	function c_ajaxChkAmountAllOld(){
		$backData = array();
		$chkData = isset($_POST['chkData'])? $_POST['chkData'] : array();
		$equIds = isset($_POST['equIds'])? rtrim($_POST['equIds'],",") : '';
		if(count($chkData) > 0 && $equIds != ''){
			$backData['result'] = array();
			$allEqus = $this->service->_db->getArray("select id,sequence,amountAllOld,issuedPurNum from oa_stock_fillup_detail where id in ({$equIds});");

			foreach ($allEqus as $key => $equ){
				$catchArr = array();
				if(bcadd($chkData[$equ['id']],$equ['issuedPurNum']) > $equ['amountAllOld']){
					$catchArr['id'] = $equ['id'];
					$catchArr['productNo'] = $equ['sequence'];
					$catchArr['amountAll'] = $chkData[$equ['id']];
					$catchArr['issuedPurNum'] = $equ['issuedPurNum'];
					$backData['result'][] = $catchArr;
				}
			}

			$backData['msg'] = (empty($backData['result']))? "ok" : "no";
		}

		echo util_jsonUtil::encode ( $backData );
	}
 }
?>
