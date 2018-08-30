<?php
/**
 * @author zengzx
 * @Date 2011��5��4�� 15:33:31
 * @version 1.0
 * @description:�����ƻ����Ʋ�
 */
class controller_stock_outplan_outplan extends controller_base_action {

	function __construct() {
		$this->objName = "outplan";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * ��ת�������ƻ�
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * �����ƻ��ʼĽ�����
     */
     function c_toMailTo(){
     	$this->view('mailTo');
     }


	/**
	 * ��ת���������ݲ鿴ҳ��-�����ƻ�tab
	 */
    function c_listTab() {
      $this->display('list-tab');
    }
    
	/**
	 * Ĭ�ϵ��б���ת����
	 */
	function c_list() {
		$this->assign('docStatus',$_GET['docStatus']);
		$this->display ( 'list' );
	}
	
	/**
	 * ��ת���������ݲ鿴ҳ��-�����ƻ�tab
	 */
	function c_listTabForExchange() {
		$this->assign('docId',$_GET['id']);
		$this->display('listforexchange-tab');
	}
	
	/**
	 * �������ݲ鿴ҳ�淢���ƻ����б���ת����
	 */
	function c_listForExchange() {
		$this->assign('docStatus',$_GET['docStatus']);
		$this->assign('docId',$_GET['docId']);
		$this->display ( 'listforexchange' );
	}
		
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$rateDao = new model_stock_outplan_outplanrate();
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_listByOrderId(){
//	 	switch($_GET['objType']){
//	 		case 'oa_sale_order' :
//	 			$modelName="projectmanagent_order_order";
//	 			break;
//	 		case 'oa_sale_lease' :
//	 			$modelName="contract_rental_rentalcontract";
//	 			break;
//	 		case 'oa_sale_service' :
//	 			$modelName="engineering_serviceContract_serviceContract";
//	 			break;
//	 		case 'oa_sale_rdproject' :
//	 			$modelName="rdproject_yxrdproject_rdproject";
//	 			break;
//	 		case 'oa_borrow_borrow' :
//	 			$modelName="projectmanagent_borrow_borrow";
//	 			break;
//	 		case 'oa_present_present' :
//	 			$modelName="projectmanagent_present_present";
//	 			break;
//	 	}
//		$this->permCheck($_GET['id'],$modelName);
		$this->assign( 'docId',$_GET['id'] );
		$this->assign( 'docType',$_GET['objType'] );
		$this->display( 'listbyorder' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageByOrderId() {
		$service = $this->service;
		$service->getParam ( $_POST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchArr = array(
			'docId' => $_POST['docId'],
			'docType' => $_POST['docType']
		);
		$rows = $service->getPageBySelectPlan();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_pageByOrderIdBymycontract() {
		$service = $this->service;
		$service->getParam ( $_POST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchArr = array(
			'docId' => $_POST['docId'],
			'docType' => 'oa_contract_contract'
		);
		$rows = $service->getPageBySelectPlan();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
	 	switch($_GET['docType']){
	 		case 'oa_contract_contract' :
	 			$modelName="model_contract_contract_contract";
	 			break;
	 		case 'oa_borrow_borrow' :
	 			$modelName="projectmanagent_borrow_borrow";
	 			break;
	 		case 'oa_present_present' :
	 			$modelName="projectmanagent_present_present";
	 			break;
	 		case 'oa_contract_exchangeapply' :
	 			$modelName="projectmanagent_exchange_exchange";
	 			break;
	 	}
//		$this->permCheck($_GET['id'],$modelName);
		$contId = $_GET['id'];
		$docType = $_GET['docType'];
		$service = $this->service;
		//�������ͻ�ȡ��������
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$cont = $service->getDocInfo( $contId, new $relatedStrategy() );
		//add by chengl ����������λ��ʼ��
		if(!empty($cont['signSubject'])){
			$this->assign("sysCompanyCode",strtolower($cont['signSubject']));
		}else{
			$this->assign("sysCompanyCode","dl");
		}
		$equIds=isset($_GET['equIds'])?$_GET['equIds']:null;
//		if( $docType ){
//			switch($docType){
//			case"oa_sale_order":$skey=$this->md5Row($contId,'projectmanagent_order_order');break;
//			case"oa_sale_lease":$skey=$this->md5Row($contId,'contract_rental_rentalcontract');break;
//			case"oa_sale_service":$skey=$this->md5Row($contId,'engineering_serviceContract_serviceContract');break;
//			case"oa_sale_rdproject":$skey=$this->md5Row($contId,'rdproject_yxrdproject_rdproject');break;
//			case"oa_borrow_borrow":$skey=$this->md5Row($contId,'projectmanagent_borrow_borrow');break;
//			case"oa_present_present":$skey=$this->md5Row($contId,'projectmanagent_present_present');break;
//			}
//		}
		$paramArr['docId'] = $contId;
		$skey = '';
		//Դ���鿴ҳ��url
		$viewUrl = $service->viewRelInfo( $paramArr,$skey, new $relatedStrategy() );
//		$equArr = $cont['equ'];
//		echo "<pre>";
//		print_R($equArr);
//		foreach( $equArr as $key=>$val ){
//			if( $val['isBorrowToorder']==1 ){
//				unset($equArr[$key]);
//			}
//		}
// 		//�ӱ���ʾ
// 		if( $cont['equ']!='' ){
// 			$products = $service->showItemAdd($detail, new $relatedStrategy());
// 		}
		foreach($cont as $key => $val){
			if($key == 'orderId'){
				$this->assign('contractId',$val);
			}else if($key == 'orderCode'){
				$this->assign('contractCode',$val);
			}else{
				$this->assign($key,$val);
			}
		}

		if(!isset($cont['isWarrantyName'])){
			$this->assign('isWarrantyName','');
		}

		$this->assign('equIds',$equIds);
		$this->assign('docType',$docType);
		$this->showDatadicts ( array ('type' => 'FHXZ' ) );
// 		$this->assign( 'products',$products );
		$this->assign( 'docview',$viewUrl );
		$this->assign( 'docId',$cont['id'] );
		$this->showDatadicts ( array ('isWarranty' => 'BXZK' ), null, true );  //��ӱ���״��
		$this->assign( 'pageAction','add' );
		$this->view ( 'add' ,true,true);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$toFeedBackInfo = $_POST [$this->objName];
		if( isset($_GET['issued']) ){
			$toFeedBackInfo['issuedStatus'] = 1;
    		$toFeedBackInfo['planIssuedDate'] = date("Y-m-d H:i:s");
		}
		$toFeedBackInfo['docStatus'] = 'WFH';
		$toFeedBackInfo['status'] = 'WZX';
		$id = $this->service->add_d ( $toFeedBackInfo, $isAddInfo );
		$msg = $_GET ["msg"] ? $_GET ["msg"] : '��ӳɹ���';
    	if ($id) {
			msgRf ( $msg );
		}else{
 			msgRf ( '���ʧ��' );
		}
	}


	/**
	 * ��ת���޸ĳ�������ҳ��
	 */
	function c_toEdit(){
		$this->permCheck();
	 	if( !isset($this->service->this_limit['�����ƻ�']) ){
	 		showmsg('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
	 	}
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//��ȡ��������
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
            if ($key == 'details') {//�����嵥
				$products = $service->showItemEdit($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'docCode' ){//ΪԴ���ż���url
				$viewUrl = $service->viewRelInfo( $outplanInfo,$skey, new $relatedStrategy() );
				$outplanInfo['docCode'] .= $viewUrl;
				$this->show->assign("docCode",$outplanInfo['docCode']);
			}else
				$this->show->assign($key, $val);
        }
		$this->showDatadicts ( array ('type' => 'FHXZ' ),'FHXZ-FH' );
		$this->display("edit",true,true);
	}


	/**
 	 * �༭�����ƻ�
	 */
	function c_edit() {
		$this->checkSubmit();
		$planInfo = $_POST [$this->objName];
		if( isset($_GET['issued']) ){
			$planInfo['issuedStatus'] = 1;
			$outplanInfo['planIssuedDate'] = day_date;
		}
		if ( $this->service->edit_d ( $planInfo, true ) ) {
			$msg = $_GET ["msg"] ? $_GET ["msg"] : '��ӳɹ���';
			msg ( $msg );
		}else{
			msg ( '����ʧ��' );
		}
	}

	/**
	 * ��ת���޸ĳ�������ҳ��
	 */
	function c_toView(){
//		$this->permCheck();//��ȫ����
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//��ȡ��������
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

        $files = $this->service->getFilesByObjId($planId, false, 'stock_outplan_outplan_filing');
        $this->assign('files', $files);

        $type = $this->getDataNameByCode($outplanInfo['type']);
		$this->assign('type',$type);
		$this->display("view");
	}

	/**
	 * ������ʷ
	 */
	function c_outHistory(){
		$this->assign( 'docId' , $_GET['docId']);
		$this->assign( 'docType' , $_GET['docType']);
		$this->display( 'outhistory' );
	}

	/**
	 * ��ת�������ƻ�����ҳ��
	 */
	function c_toFeedBack(){
		$this->permCheck();
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$isEx = isset($_GET['isEx']) ? $_GET['isEx'] : "0";
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//��ȡ��������
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
		$this->assign('isEx',$isEx);
		$this->assign('docType',$docType);
		$this->assign('planId',$planId);
//		$this->showDatadicts(array ( 'delayType' => 'YQYYGL' ),$outplanInfo['delayType']);
		$this->display("feedback");
	}

	/**
 	 * �����ƻ�����
	 */
	function c_feedback() {
		$planInfo = $_POST [$this->objName];
		$detailCode = str_replace("��",',',$planInfo['delayDetailCode']);
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
			if($planInfo['isEx'] =='0')
			   msg('�����ɹ�');
			else
			   $planId = $planInfo['planId'];
			   $docType= $planInfo['docType'];
			   msgGo('�����ɹ�,����ת��������ҳ��','index1.php?model=stock_outplan_ship&action=toAdd&id='.$planId.'&skey=c272d06794&docType='.$docType.'');
		}
	}
	/**
	 * �����ܴ�
	 */
	 function c_week(){
	 	$date = $_POST['date'];
		echo  date('W',strtotime($date) );
	 }

	 /**
	  * ajax�´﷢���ƻ�
	  */
	  function c_issuedFun(){
	  	$this->permCheck($_POST['id']);
	 	if( !isset($this->service->this_limit['�����ƻ�']) ){
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
	   * ajax���ü��ܹ���ɽ���
	   */
	   function c_selectDongleRate(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['���ܹ���ɽ���']) ){
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
	   * ajax�������ܹ���ɽ���
	   */
	   function c_resetDongleRate(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['���ܹ���ɽ���']) ){
		 		echo 2;
		 		return 0;
		 	}
		  	$planId = $_POST['id'];
		  	$planInfo = array(
		  		'id' => $planId,
		  		'dongleRate' => 0
		  	);
		  	echo $this->service->updateById( $planInfo );
	   }

	  /**
	   * ajax�رշ����ƻ�
	   */
	   function c_closePlan(){
		   	try{
		   		$this->permCheck($_POST['id']);
			 	if( !isset($this->service->this_limit['�����ƻ�']) ){
			 		echo 2;
			 		return 0;
			 	}
			  	$planId = $_POST['id'];
			 	$productDao = new model_stock_outplan_outplanProduct();
			 	$planInfo = $this->service->get_d($planId);
			 	$productDao->unsetIssuedInfo($planInfo,$_POST['docType']);
			  	$planInfo = array(
			  		'id' => $planId,
			  		'docStatus' => 'YGB'
			  	);
			  	echo $this->service->updateById( $planInfo );
		   	}catch( Exception $e ){
		   		echo 0;
		   	}
	   }

	  /**
	   * ajax�ָ������ƻ�
	   */
	   function c_reopenPlan(){
	   		$this->permCheck($_POST['id']);
		 	if( !isset($this->service->this_limit['�����ƻ�']) ){
		 		echo 2;
		 		return 0;
		 	}
		  	$planId = $_POST['id'];
		  	echo $this->service->setStatus( $planId );
	   }


	   /**
	    * ���÷����б�鿴��ϸ
	    */
	    function c_viewByBorrow(){
	    	$this->permCheck($_GET['id'],'projectmanagent_borrow_borrow');
	    	$borrowDao = new model_projectmanagent_borrow_borrow();
	    	$rows = $borrowDao->get_d($_GET['id']);
            $oldId = $_GET['id'];
            if($rows['isTemp'] == 1 && $rows['originalId'] > 0){
                $oldId = $rows['originalId'];
            }

		 	$this->assign( 'id',$rows['id'] );
            $this->assign( 'oldId',$oldId );
		 	$this->assign( 'linkId',$_GET['linkId'] );
		 	$this->assign( 'objType',$_GET['objType'] );
		 	$this->assign( 'initTip',$rows['initTip'] );
	    	$this->display('viewbyborrow-tab');
	    }

	   /**
	    * ���ͷ����б�鿴��ϸ
	    */
	    function c_viewByPresent(){
	    	$this->permCheck($_GET['id'],'projectmanagent_present_present');
		 	$this->assign( 'id',$_GET['id'] );
		 	$this->assign( 'linkId',$_GET['linkId'] );
		 	$this->assign( 'objType',$_GET['objType'] );
	    	$this->display('viewbypresent-tab');
	    }

	/**
	 * ��ͬ�����б�ҳ�鿴��ϸtab
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
	 * ��д���ⵥʱ����̬��Ӵӱ�ģ��
	 */
	function c_getItemList () {
		$outplan=isset($_POST['planId'])?$_POST['planId']:null;
		$list=$this->service->getEquList_d($outplan);
		echo $list;
	}

	/**
	 * ��ͬ�����豸��
	 */
	 function c_shipByEquPage(){
	 	$this->display( 'shipbyequlist' );
	 }
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_equJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'productId';
		$rows = $service->pageBySqlId ( 'select_equ' );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_contJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->pageBySqlId ( 'select_cont' );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ͬ�����豸-�ƻ�ͳ���б�
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
	 * ��ͬ����tab
	 */
	 function c_shipTab(){
 		$this->display( 'contequ-tab' );
	 }

	/**
	 * �����ƻ���ϸtab
	 */
	 function c_outplandetailTab(){
	 	$this->permCheck($_GET['planId']);//��ȫ����
	 	$this->assign( 'planId',$_GET['planId'] );
	 	$this->assign( 'docType',$_GET['docType'] );
	 	$this->display( 'detail-tab' );
	 }

	/**
	 * �����ƻ��б�ӱ�
	 */
	 function c_byOutplanJson(){
	 	$outplanEqu = new model_stock_outplan_outplanProduct();
		$outplanEqu->searchArr['mainId'] = $_POST['mainId'];
		$outplanEqu->searchArr['isDelete'] = 0;
		$outplanEqu->groupBy = " c.id";
		$rows = $outplanEqu->listBySqlId ("select_product");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $outplanEqu->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	 /***************************************************************************************************/

	/**
	 * @exclude ��ת���޸ĳ�������ҳ��
	 * @author zengzx
	 * @param
	 * @version 2011��7��26�� 15:25:52
	 */
	function c_toChange(){
		$this->permCheck();
	 	if( !isset($this->service->this_limit['�����ƻ�']) ){
	 		showmsg('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
	 	}
		$planId = isset($_GET['id']) ? $_GET['id'] : null;
		$docType=isset($_GET['docType'])?$_GET['docType']:null;
		$service = $this->service;
		//��ȡ��������
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
			case"oa_contract_contract":$skey=$this->md5Row($outplanInfo['docId'],'contract_contract_contract');break;
			}
		}
		//��ȡĬ���ʼ��ռ���
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
            if ($key == 'details') {//�����嵥
				$products = $service->showItemChange($outplanInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'docCode' ){//ΪԴ���ż���url
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
	 * @exclude �������
	 * @author zengzx
	 * @param
	 * @version 2011��7��26�� 15:25:52
	 */
	function c_change () {
		try {
			$id = $this->service->change_d ( $_POST ['outplan'] );
			if($id){
				msgRf("����ɹ���");
//				echo "����ɹ���";
			}else{
				msg("������ɹ���");
//				echo "������ɹ���";
			}
		} catch ( Exception $e ) {
			msg ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}
	/**
	 * δ��ɳ���ķ����ƻ��б�
	 */
	function c_awaitList(){
		$this->display('awaitlist');
	}

	/**
	 * δ��ɳ���ķ����ƻ��б�-����
	 */
	 function c_awaitJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }


	/**
	 * δ��ɵ����ķ����ƻ��б� --- �����ó���
	 */
	function c_awaitListBorrow(){
		$this->display('awaitlistborrow');
	}

	/**
	 * δ��ɵ����ķ����ƻ��б�-���� --- �����ó���
	 */
	 function c_awaitJsonBorrow(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->getPageBySelectAllocation ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }

	/**
	 * ��ȡ���������豸combogrid����
	 */
	 function c_shipEquJson(){
         $service = $this->service;
         $service->_isSetCompany = 0;
         $service->getParam ( $_REQUEST );
         //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
         //$service->asc = false;
         $rows = $service->pageBySqlId("select_contequ");
         //���ݼ��밲ȫ��
         $rows = $this->sconfig->md5Rows ( $rows );
         $arr = array ();
         $arr ['collection'] = $rows;
         //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
         $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
         $arr ['page'] = $service->page;
         $arr ['pageSql'] = $service->listSql;
         echo util_jsonUtil::encode ( $arr );
	 }


	/**
	* ����������ȡҵ���������
	* ��дc_getCountByName�����ڷ��������豸combogridѡ�������֤
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
	 * Ajaxȡ���������
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
	 * ajax ���ݷ����ƻ�Id��ȡ�����ƻ�Դ����Ϣ
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



	function c_Starset() {
		//$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('star');
	}

	function c_editstar() {
		//$this->permCheck(); //��ȫУ��
		$_POST [$this->objName]['id'] = $_GET['id'];
		$_POST [$this->objName]['reterStart'] = $_REQUEST['value'];
		//msg($_REQUEST['value']);
		$planInfo = $_POST [$this->objName];
		if( isset($_GET['issued']) ){
			$planInfo['issuedStatus'] = 1;
			$outplanInfo['planIssuedDate'] = day_date;
		}
		if ( $this->service->edit_star ( $planInfo, true ) ) {
			$msg = $_GET ["msg"] ? $_GET ["msg"] : '��ӳɹ���';
			msg ( $msg );
		}else{
			msg ( '����ʧ��' );
		}
	}

	//���ƻ�����������ϣ���������ڵĴ�С��ϵ
	function c_checkShipPlanDate(){
		$contractDao = new model_contract_contract_contract();
		$contractArr = $contractDao->find(array('id' => $_POST['contractId']),null,'deliveryDate');
	//�ж��Ƿ��� 6 �����ú�ͬ��Χ��--��ʱ
		if(strpos(CONTOOLIDS,$_POST['contractId'])){
			if(!empty($contractArr['deliveryDate'])){
			if(date("Y-m-d",strtotime($_POST['shipPlanDate'])) > date("Y-m-d",strtotime($contractArr['deliveryDate']))){
				echo 1;
			}else{
				echo 0;
			}
		  }else{
			echo 0;
		  }
		}else{
			echo 0;
		}
	}

	//��ȷ�ϵķ����ƻ��б�
	function c_confirmList(){
		$this->view('confirmlist');
	}

	//��ȷ�ϵķ����ƻ��б�
	function c_confirmListJson(){
		$service = $this->service;
		$service->getParam ( array('docType' => 'oa_contract_contract', 'isNeedConfirm' => 1) );
		$rows = $service->pageBySqlId("select_confirmList");

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	//ȷ�Ϸ����ƻ�
	function c_confirm(){
		if($this->service->confirm_d($_POST)){
			echo 1;
		}else{
			echo 0;
		}

	}


	//�رշ�������
	function c_toCloseOutMat(){
	 	switch($_GET['docType']){
	 		case 'oa_contract_contract' :
	 			$modelName="model_contract_contract_contract";
	 			break;
	 		case 'oa_borrow_borrow' :
	 			$modelName="projectmanagent_borrow_borrow";
	 			break;
	 		case 'oa_present_present' :
	 			$modelName="projectmanagent_present_present";
	 			break;
	 		case 'oa_contract_exchangeapply' :
	 			$modelName="projectmanagent_exchange_exchange";
	 			break;
	 	}
//		$this->permCheck($_GET['id'],$modelName);
		$contId = $_GET['id'];
		$docType = $_GET['docType'];
		$service = $this->service;
		//�������ͻ�ȡ��������
		$relatedStrategy = $service->relatedStrategyArr[$docType];
		$cont = $service->getDocInfo( $contId, new $relatedStrategy() );
		//add by chengl ����������λ��ʼ��
		if(!empty($cont['signSubject'])){
			$this->assign("sysCompanyCode",strtolower($cont['signSubject']));
		}else{
			$this->assign("sysCompanyCode","dl");
		}
		$equIds=isset($_GET['equIds'])?$_GET['equIds']:null;
//		if( $docType ){
//			switch($docType){
//			case"oa_sale_order":$skey=$this->md5Row($contId,'projectmanagent_order_order');break;
//			case"oa_sale_lease":$skey=$this->md5Row($contId,'contract_rental_rentalcontract');break;
//			case"oa_sale_service":$skey=$this->md5Row($contId,'engineering_serviceContract_serviceContract');break;
//			case"oa_sale_rdproject":$skey=$this->md5Row($contId,'rdproject_yxrdproject_rdproject');break;
//			case"oa_borrow_borrow":$skey=$this->md5Row($contId,'projectmanagent_borrow_borrow');break;
//			case"oa_present_present":$skey=$this->md5Row($contId,'projectmanagent_present_present');break;
//			}
//		}
		$paramArr['docId'] = $contId;
		$skey = '';
		//Դ���鿴ҳ��url
		$viewUrl = $service->viewRelInfo( $paramArr,$skey, new $relatedStrategy() );
//		$equArr = $cont['equ'];
//		echo "<pre>";
//		print_R($equArr);
//		foreach( $equArr as $key=>$val ){
//			if( $val['isBorrowToorder']==1 ){
//				unset($equArr[$key]);
//			}
//		}
// 		//�ӱ���ʾ
// 		if( $cont['equ']!='' ){
// 			$products = $service->showItemAdd($detail, new $relatedStrategy());
// 		}
		foreach($cont as $key => $val){
			$this->assign($key,$val);
		}
		$this->assign('equIds',$equIds);
		$this->assign('docType',$docType);
		$this->showDatadicts ( array ('type' => 'FHXZ' ) );
// 		$this->assign( 'products',$products );
		$this->assign( 'docview',$viewUrl );
		$this->assign( 'docId',$cont['id'] );
		$this->showDatadicts ( array ('isWarranty' => 'BXZK' ), null, true );  //��ӱ���״��
		$this->assign( 'pageAction','add' );
		$this->view ( 'closeoutmat' ,true,true);

	}

    /**
     * ���������ϴ�ҳ��
     */
    function c_toUploadFile()
    {
        $files = $this->service->getFilesByObjId($_GET['id'], true, 'stock_outplan_outplan_filing');
        $this->assign('files', $files);

        $this->assign("serviceId", $_GET['id']);
        $this->assign("serviceType", $_GET['type']);
        $text = $_GET['type'] . "2";
        $this->assign("serviceType2", $text);
        $this->display('uploadfile');
    }

    /**
     * ���������ϴ�����
     */
    function c_uploadfile()
    {
        $row = $_POST[$this->objName];
        $id = $this->service->uploadfile_d($row);
        if ($id) {
            msg('��ӳɹ���');
        } else {
            msg('���ʧ�ܣ�');
        }
    }
 }
?>