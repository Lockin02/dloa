<?php
/**
 * @author zengzx
 * @Date 2011年5月4日 15:33:31
 * @version 1.0
 * @description:发货计划控制层
 */
class controller_stock_outplan_outplan extends controller_base_action {

	function __construct() {
		$this->objName = "outplan";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * 跳转到发货计划
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * 发货计划邮寄接收人
     */
     function c_toMailTo(){
     	$this->view('mailTo');
     }


	/*
	 * 跳转到发货计划-tab
	 */
    function c_listTab() {
      $this->display('list-tab');
    }

	/*
	 * 默认的列表跳转方法
	 */
	function c_list() {
		$this->assign('docStatus',$_GET['docStatus']);
		$this->display ( 'list' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$rateDao = new model_stock_outplan_outplanrate();
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//print_r($_POST['docTypeArr']);
//		if(isset($_POST['docTypeArr'])){
//			$this->searchArr['docType']=$_POST['docTypeArr'];
//		}
		$this->service->searchArr['createId1'] = $_SESSION['USER_ID'];
		$rows = $service->getPageBySelectPlan();
		$planIdArr = array();
		foreach ( $rows as $key=>$val ){
			$planIdArr[$key]=$rows[$key]['id'];
		}
		$planIdStr = implode(',',$planIdArr);
		$rateDao->searchArr['planIdArr']=$planIdStr;
		$rateArr = $rateDao->list_d();
		foreach( $rows as $key=>$val ){
			$rows[$key]['rate']="";
			foreach( $rateArr as $index=>$value ){
				if( $rows[$key]['id']==$rateArr[$index]['planId'] ){
					$rows[$key]['rate']=$rateArr[$index]['keyword'];
				}
			}
		}
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_listByOrderId(){
	 	switch($_GET['objType']){
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
	 			break;
	 		case 'oa_present_present' :
	 			$modelName="projectmanagent_present_present";
	 			break;
	 	}
		$this->permCheck($_GET['id'],$modelName);
		$this->assign( 'docId',$_GET['id'] );
		$this->assign( 'docType',$_GET['objType'] );
		$this->display( 'listbyorder' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageByOrderId() {
		$service = $this->service;
		$service->getParam ( $_POST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$searchArr = array(
			'docId' => $_POST['docId'],
			'docType' => $_POST['docType']
		);
		$rows = $service->getPageBySelectPlan();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
	 	switch($_GET['docType']){
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
	 			break;
	 		case 'oa_present_present' :
	 			$modelName="projectmanagent_present_present";
	 			break;
	 	}
		$this->permCheck($_GET['id'],$modelName);
		$contId = $_GET['id'];
		$docType = $_GET['docType'];
		$service = $this->service;
		//根据类型获取策略类名
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$cont = $service->getDocInfo( $contId, new $relatedStrategy() );
		$detail = $cont['orderequ'];
		$datadict = new model_system_datadict_datadict ();
//		if( is_array($detail) ){
//			foreach( $detail as $key=>$val ){
//				if( !$detail[$key]['productLineName'] ){
//					$productLineName = $datadict->getDataNameByCode($detail[$key]['productLine']);
//					$cont['orderequ'][$key]['productLineName'] = $productLineName;
//				}
//			}
//		}
		if( $docType ){
			switch($docType){
			case"oa_sale_order":$skey=$this->md5Row($contId,'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($contId,'contract_rental_rentalcontract');break;
			case"oa_sale_service":$skey=$this->md5Row($contId,'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($contId,'rdproject_yxrdproject_rdproject');break;
			case"oa_borrow_borrow":$skey=$this->md5Row($contId,'projectmanagent_borrow_borrow');break;
			case"oa_present_present":$skey=$this->md5Row($contId,'projectmanagent_present_present');break;
			}
		}
		$paramArr['docId'] = $contId;
		//源单查看页面url
		$viewUrl = $service->viewRelInfo( $paramArr,$skey, new $relatedStrategy() );
		$cont['docview'] = $viewUrl;
		//从表显示
		if( $cont['orderequ']!='' ){
			$products = $service->showItemAdd($cont['orderequ'], new $relatedStrategy());
		}
		foreach($cont as $key => $val){
			$this->assign($key,$val);
		}
		$this->showDatadicts ( array ('type' => 'FHXZ' ) );
		$this->assign( 'products',$products );
		$this->assign( 'docId',$cont['id'] );
//		$this->assign( 'docApplicant',$cont['docApplicant'] );
//		$this->assign( 'docApplicantId',$cont['docApplicantId'] );
//		$this->assign( 'customerName',$cont['customerName'] );
//		$this->assign( 'customerId',$cont['customerId'] );
		$this->assign( 'docCode',$cont['planDocCode'] );
//		$this->assign( 'docview',$cont['docview'] );
		$this->assign( 'docName',$cont['planDocName'] );
//		$this->assign( 'address',$cont['address'] );
		$this->assign( 'docType',$_GET['docType'] );
		$this->assign( 'pageAction','add' );
		$this->display ( 'add' );
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$toFeedBackInfo = $_POST [$this->objName];

//		echo "<pre>";
//		print_R($toFeedBackInfo);
		if( isset($_GET['issued']) ){
			$toFeedBackInfo['issuedStatus'] = 1;
			$toFeedBackInfo['planIssuedDate'] = date("Y-m-d H:i:s");
		}
		$toFeedBackInfo['docStatus'] = 'WFH';
		$toFeedBackInfo['status'] = 'WZX';
		$id = $this->service->add_d ( $toFeedBackInfo, $isAddInfo );
		if( isset($toFeedBackInfo['issuedStatus']) && $id ){
			$this->service->mailTo($toFeedBackInfo);
		}
		$msg = $_GET ["msg"] ? $_GET ["msg"] : '添加成功！';
		if ($id) {
			msgRf ( $msg );
		}else{
			msgRf ( '添加失败' );
		}
	}


	/**
	 * 跳转到修改出库申请页面
	 */
	function c_toEdit(){
		$this->permCheck();
	 	if( !isset($this->service->this_limit['发货计划']) ){
	 		showmsg('没有权限,需要开通权限请联系oa管理员');
	 	}
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//获取策略类名
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$outplanInfo = $service->get_d($planId);
		if( $docType ){
			switch($docType){
			case"oa_sale_order":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($outplanInfo['docId'],'contract_rental_rentalcontract');break;
			case"oa_sale_service":$skey=$this->md5Row($outplanInfo['docId'],'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($outplanInfo['docId'],'rdproject_yxrdproject_rdproject');break;
			case"oa_borrow_borrow":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_borrow_borrow');break;
			case"oa_present_present":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_present_present');break;
			}
		}

		foreach ($outplanInfo as $key => $val) {
            if ($key == 'details') {//物料清单
				$products = $service->showItemEdit($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'docCode' ){//为源单号加上url
				$viewUrl = $service->viewRelInfo( $outplanInfo,$skey, new $relatedStrategy() );
				$outplanInfo['docCode'] .= $viewUrl;
				$this->show->assign("docCode",$outplanInfo['docCode']);
			}else
				$this->show->assign($key, $val);
        }
		$this->showDatadicts ( array ('type' => 'FHXZ' ),'FHXZ-FH' );
		$this->display("edit");
	}


	/**
 	 * 编辑发货计划
	 */
	function c_edit() {
		$planInfo = $_POST [$this->objName];
		if( isset($_GET['issued']) ){
			$planInfo['issuedStatus'] = 1;
			$outplanInfo['planIssuedDate'] = day_date;
		}
		if ( $this->service->edit_d ( $planInfo, true ) ) {
			$msg = $_GET ["msg"] ? $_GET ["msg"] : '添加成功！';
			msg ( $msg );
		}else{
			msg ( '保存失败' );
		}
	}

	/**
	 * 跳转到修改出库申请页面
	 */
	function c_toView(){
		$this->permCheck();//安全检验
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//获取策略类名
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$outplanInfo = $service->getToView_d($planId);
		$outplanInfo = $service->setChangeKey_d($outplanInfo);

		if( $docType ){
			switch($docType){
			case"oa_sale_order":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($outplanInfo['docId'],'contract_rental_rentalcontract');break;
			case"oa_sale_service":$skey=$this->md5Row($outplanInfo['docId'],'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($outplanInfo['docId'],'rdproject_yxrdproject_rdproject');break;
			case"oa_borrow_borrow":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_borrow_borrow');break;
			case"oa_present_present":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_present_present');break;
			}
		}

		foreach ($outplanInfo as $key => $val) {
            if ($key == 'details') {
				$products = $service->showItemView($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'docCode' ){
				$viewUrl = $service->viewRelInfo( $outplanInfo,$skey, new $relatedStrategy() );
				$outplanInfo['docCode'] .= $viewUrl;
				$this->show->assign("docCode",$outplanInfo['docCode']);
			}else
				$this->show->assign($key, $val);
        }
        $type = $this->getDataNameByCode($outplanInfo['type']);
		$this->assign('type',$type);
		$this->display("view");
	}

	/**
	 * 发货历史
	 */
	function c_outHistory(){
		$this->assign( 'docId' , $_GET['docId']);
		$this->assign( 'docType' , $_GET['docType']);
		$this->display( 'outhistory' );
	}

	/**
	 * 跳转到发货计划反馈页面
	 */
	function c_toFeedBack(){
		$this->permCheck();
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//获取策略类名
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$outplanInfo = $service->get_d($planId);
		foreach ($outplanInfo as $key => $val) {
            if ($key == 'details') {
				$products = $service->showItemView($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}
			$this->show->assign($key, $val);
        }
        if( $outplanInfo['isShipped'] == 1 )
        	$this->show->assign( 'shipped','checked' );
        else
        	$this->show->assign( 'notShipped','checked' );
        if( $outplanInfo['isOnTime'] == 1 )
        	$this->show->assign( 'ontime','checked' );
        else
        	$this->show->assign( 'notOntime','checked' );
		$this->assign('type',$this->getDataNameByCode($outplanInfo['type']));
//		$this->showDatadicts(array ( 'delayType' => 'YQYYGL' ),$outplanInfo['delayType']);
		$this->display("feedback");
	}

	/**
 	 * 发货计划反馈
	 */
	function c_feedback() {
		$planInfo = $_POST [$this->objName];
		$detailCode = str_replace("。",',',$planInfo['delayDetailCode']);
		$dataDao = new model_system_datadict_datadict();
		$parentArr = $dataDao->getDatadictsByChildCodes($detailCode);
		$parentStr = implode(',',$parentArr);
		$parentNameArr = $dataDao->getDataByCodes($parentStr);
		$tempArr = array();
		foreach ( $parentNameArr as $key=>$val ){
			$tempArr[$key]=$val['name'];
		}
		$parentNameStr = implode(',',$tempArr);
		$planInfo['delayTypeCode']=$parentStr;
		$planInfo['delayType']=$parentNameStr;
//		echo "<pre>";
//		print_R($planInfo);
		if ( $this->service->feedback_d ( $planInfo, true ) ) {
			msg('反馈成功');
		}
	}
	/**
	 * 计算周次
	 */
	 function c_week(){
	 	$date = $_POST['date'];
		echo  date('W',strtotime($date) );
	 }

	 /**
	  * ajax下达发货计划
	  */
	  function c_issuedFun(){
	  	$this->permCheck($_POST['id']);
	 	if( !isset($this->service->this_limit['发货计划']) ){
	 		echo 2;
	 		return 0;
	 	}
	  	$planId = $_POST['id'];
	  	$planInfo = array(
	  		'id' => $planId,
			'issuedStatus' => 1,
			'planIssuedDate' => date("Y-m-d H:i:s")
	  	);
	  	echo $this->service->updateById( $planInfo );
	  }

	  /**
	   * ajax设置加密狗完成进度
	   */
	   function c_selectDongleRate(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['加密狗完成进度']) ){
		 		echo 2;
		 		return 0;
		 	}
		  	$planId = $_POST['id'];
		  	$planInfo = array(
		  		'id' => $planId,
		  		'dongleRate' => 1
		  	);
		  	echo $this->service->updateById( $planInfo );
	   }

	  /**
	   * ajax关闭发货计划
	   */
	   function c_closePlan(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['发货计划']) ){
		 		echo 2;
		 		return 0;
		 	}
		  	$planId = $_POST['id'];
		  	$planInfo = array(
		  		'id' => $planId,
		  		'docStatus' => 'YGB'
		  	);
		  	echo $this->service->updateById( $planInfo );
	   }

	  /**
	   * ajax恢复发货计划
	   */
	   function c_reopenPlan(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['发货计划']) ){
		 		echo 2;
		 		return 0;
		 	}
		  	$planId = $_POST['id'];
		  	echo $this->service->setStatus( $planId );
	   }


	   /**
	    * 借用发货列表查看详细
	    */
	    function c_viewByBorrow(){
	    	$this->permCheck($_GET['id'],'projectmanagent_borrow_borrow');
		 	$this->assign( 'id',$_GET['id'] );
		 	$this->assign( 'objType',$_GET['objType'] );
	    	$this->display('viewbyborrow-tab');
	    }

	   /**
	    * 赠送发货列表查看详细
	    */
	    function c_viewByPresent(){
	    	$this->permCheck($_GET['id'],'projectmanagent_present_present');
		 	$this->assign( 'id',$_GET['id'] );
		 	$this->assign( 'objType',$_GET['objType'] );
	    	$this->display('viewbypresent-tab');
	    }

	/**
	 * 合同发货列表页查看详细tab
	 */
	 function c_viewByOrder(){
	 	switch($_GET['objType']){
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
	 	}
	 	$this->permCheck($_GET['id'],$modelName);
	 	$this->assign( 'id',$_GET['id'] );
	 	$this->assign( 'objType',$_GET['objType'] );
	 	$this->display( 'viewbyorder-tab' );
	 }


	/**
	 * 填写出库单时，动态添加从表模板
	 */
	function c_getItemList () {
		$outplan=isset($_POST['planId'])?$_POST['planId']:null;
		$list=$this->service->getEquList_d($outplan);
		echo $list;
	}

	/**
	 * 合同发货设备表
	 */
	 function c_shipByEquPage(){
	 	$this->display( 'shipbyequlist' );
	 }
	/**
	 * 获取分页数据转成Json
	 */
	function c_equJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'productId';
		$rows = $service->pageBySqlId ( 'select_equ' );
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
	function c_contJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->pageBySqlId ( 'select_cont' );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


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


	/**
	 * 合同发货tab
	 */
	 function c_shipTab(){
 		$this->display( 'contequ-tab' );
	 }

	/**
	 * 发货计划详细tab
	 */
	 function c_outplandetailTab(){
	 	$this->permCheck($_GET['planId']);//安全检验
	 	$this->assign( 'planId',$_GET['planId'] );
	 	$this->assign( 'docType',$_GET['docType'] );
	 	$this->display( 'detail-tab' );
	 }

	/**
	 * 发货计划列表从表
	 */
	 function c_byOutplanJson(){
	 	$outplanEqu = new model_stock_outplan_outplanProduct();
		$outplanEqu->searchArr['mainId'] = $_POST['mainId'];
		$outplanEqu->searchArr['isDelete'] = 0;
		$outplanEqu->groupBy = " c.id";
		$rows = $outplanEqu->pageBySqlId ("select_product");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $outplanEqu->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	 /***************************************************************************************************/

	/**
	 * @exclude 跳转到修改出库申请页面
	 * @author zengzx
	 * @param
	 * @version 2011年7月26日 15:25:52
	 */
	function c_toChange(){
		$this->permCheck();
	 	if( !isset($this->service->this_limit['发货计划']) ){
	 		showmsg('没有权限,需要开通权限请联系oa管理员');
	 	}
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//获取策略类名
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$outplanInfo = $service->get_d($planId);
		if( $docType ){
			switch($docType){
			case"oa_sale_order":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($outplanInfo['docId'],'contract_rental_rentalcontract');break;
			case"oa_sale_service":$skey=$this->md5Row($outplanInfo['docId'],'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($outplanInfo['docId'],'rdproject_yxrdproject_rdproject');break;
			case"oa_borrow_borrow":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_borrow_borrow');break;
			case"oa_present_present":$skey=$this->md5Row($outplanInfo['docId'],'projectmanagent_present_present');break;
			}
		}
		//获取默认邮件收件人
		if(is_array($this->service->mailArr)){
			$mailArr=$this->service->mailArr;
		}else{
			$mailArr=array();
		}
		$salemanArr = $service->getSaleman($outplanInfo['docId'],new $relatedStrategy);
		array_push($mailArr,$salemanArr);
		foreach($mailArr as $key=>$val){
			$nameArr[$key]=$val['responsible'];
			$idArr[$key]=$val['responsibleId'];
		}
		$nameArr=array_flip($nameArr);
		$nameArr=array_flip($nameArr);
		$idArr=array_flip($idArr);
		$idArr=array_flip($idArr);
		$nameStr = implode(',',$nameArr);
		$idStr = implode(',',$idArr);
		foreach ($outplanInfo as $key => $val) {
            if ($key == 'details') {//物料清单
				$products = $service->showItemChange($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'docCode' ){//为源单号加上url
				$viewUrl = $service->viewRelInfo( $outplanInfo,$skey, new $relatedStrategy() );
				$outplanInfo['docCode'] .= $viewUrl;
				$this->show->assign("docCode",$outplanInfo['docCode']);
			}else
				$this->show->assign($key, $val);
        }
        $this->assign('TO_NAME',$nameStr);
        $this->assign('TO_ID',$idStr);
		$this->show->assign('type',$this->getDataNameByCode( 'FHXZ-FH' ));
		$this->assign( 'pageAction','change' );
		$this->display("change");


	}

	/**
	 * @exclude 变更保存
	 * @author zengzx
	 * @param
	 * @version 2011年7月26日 15:25:52
	 */
	function c_change () {
		try {
			$id = $this->service->change_d ( $_POST ['outplan'] );
			if($id){
				msgRf("变更成功！");
//				echo "变更成功！";
			}else{
				msg("变更不成功！");
//				echo "变更不成功！";
			}
		} catch ( Exception $e ) {
			msg ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}
	/**
	 * 未完成出库的发货计划列表
	 */
	function c_awaitList(){
		$this->display('awaitlist');
	}

	/**
	 * 未完成出库的发货计划列表-数据
	 */
	 function c_awaitJson(){
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
	 * 未完成调拨的发货计划列表 --- 借试用出库
	 */
	function c_awaitListBorrow(){
		$this->display('awaitlistborrow');
	}

	/**
	 * 未完成调拨的发货计划列表-数据 --- 借试用出库
	 */
	 function c_awaitJsonBorrow(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->getPageBySelectAllocation ();
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
	 * 获取发货需求设备combogrid数据
	 */
	 function c_shipEquJson(){
	 	$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->pageBySqlId("select_contequ");
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
	* 根据条件获取业务对象数量
	* 重写c_getCountByName，用于发货需求设备combogrid选择必填验证
	*/
	function c_getCountByNameInd(){
		$nameCol=$_REQUEST ['nameCol'];
		$nameVal=$_REQUEST ['nameVal'];
		$idVal=$_REQUEST ['idVal'];
		$idCol='productId';
		$nameVal=util_jsonUtil::iconvUTF2GB($nameVal);
		$checkParam=$_REQUEST ['checkParam'];
		if(!empty($idVal)){
			$checkParam[$idCol]=$idVal;
			$checkParam[$nameCol]=$nameVal;
			$this->service->tbl_name='oa_shipequ_view';
			$objs=$this->service->find($checkParam);
			if(is_array($objs)&&count($objs)>0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$this->service->tbl_name='oa_shipequ_view';
			$num = $this->service->findCount ( array($nameCol=>$nameVal));
			echo $num;
		}
	}

	/**
	 * Ajax取消变更提醒
	 */
	 function c_cancleTips(){
	 	$planId = $_POST['id'];
	 	$outplanEquDao = new model_stock_outplan_outplanProduct();
	 	$condition = array(
	 		'mainId' => $planId
	 	);
	 	$updateKey = array(
	 		'changeTips' => 0
	 	);
	 	if($outplanEquDao->update($condition,$updateKey)){
	 		if( $this->service->update(array('id'=>$planId),$updateKey) ){
				echo 1;
	 		}else{
	 			echo 0;
	 		}
	 	}else{
	 		echo 0;
	 	}
	 }


	/**
	 * ajax 根据发货计划Id获取发货计划源单信息
	 */
	function c_getPlanRel(){
		$planId = $_POST['planId'];
		$relDocInfo = $this->service->getPlanRel_d($planId);
		echo util_jsonUtil::encode ( $relDocInfo );
	}

	function c_getBToOEqu(){
		$service = $this->service;
	  	$planId = $_POST['id'];
	  	$rowNum = $_POST['rowNum'];
	  	$docType = $_POST['docType'];
	  	$perm = $_POST['perm'];
		$relatedStrategy = $service->relatedStrategyArr[$docType];
	  	$equDao = new model_stock_outplan_outplanProduct();
        $rows = $equDao->findAll(array("mainId" => $planId,"BToOTips" => '1',"isDelete"=>'0'));
		if($perm=='view'){
			$equStr = $service->shwoBToOEquView( $rows,$rowNum, new $relatedStrategy() );
		}elseif($perm=='change'){
      	  $equStr = $service->shwoBToOEquChange( $rows,$rowNum, new $relatedStrategy() );
		}else{
      	  $equStr = $service->shwoBToOEqu( $rows,$rowNum, new $relatedStrategy() );
		}
        echo $equStr;
	}

 }
?>