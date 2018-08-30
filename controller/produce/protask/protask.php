<?php
/**
 * @author Administrator
 * @Date 2011年6月2日 10:30:25
 * @version 1.0
 * @description:生产任务书控制层
 */
class controller_produce_protask_protask extends controller_base_action {

	function __construct() {
		$this->objName = "protask";
		$this->objPath = "produce_protask";
		parent::__construct ();
	 }

	/*
	 * 跳转到生产任务书
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
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
		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$this->assign('issuedDeptName' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
        $this->assign('issuedDeptId' , $_SESSION['DEPT_ID']);
		$contId = $_GET['id'];
		$relDocType = $_GET['docType'];
		$protaskCode = 'PTC-' . generatorSerial ();
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr[$relDocType];
		$contObj = $service->getDocInfo( $contId, new $relatedStrategy() );
		$detail = $contObj['orderequ'];
		$datadict = new model_system_datadict_datadict ();
		if( is_array($detail) ){
			foreach( $detail as $key=>$val ){
				if( !$detail[$key]['productLineName'] ){
					$productLineName = $datadict->getDataNameByCode($detail[$key]['productLine']);
					$contObj['orderequ'][$key]['productLineName'] = $productLineName;
				}
			}
		}
		foreach( $contObj as $key => $val ){
			if( $key == 'orderequ' ){
				$products = $service->showItemAdd($contObj['orderequ'], new $relatedStrategy());
				$this->assign( 'products',$products );
			}else{
				$this->assign( $key,$val );
			}
		}
		$deptmentDao=new model_deptuser_dept_dept();
        $purchDepart = $deptmentDao->getDeptId_d('交付部');
        $this->assign('execDeptName','交付部');
        $this->assign('execDeptId',$purchDepart['DEPT_ID']);
		$this->assign( 'issuedmanId',$_SESSION['USER_ID'] );
		$this->assign( 'issuedman',$_SESSION['USERNAME'] );
		$this->assign( 'taskCode',$protaskCode );
		$this->assign( 'relDocType',$relDocType );
		$this->display ( 'add' );
	}


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$protaskObj = $_POST [$this->objName];
		if( isset($_GET['issued']) ){
			$protaskObj['issuedStatus'] = 1;
			$protaskObj['issuedDate'] = day_date;
		}
		$protaskObj['proStatus'] = 'WWC';
		$id = $this->service->add_d ( $protaskObj, $isAddInfo );
		$msg = $_GET ["msg"] ? $_GET ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}


	/**
	 * 跳转到修改生产计划页面
	 */
	function c_toEdit(){
		$taskId = isset($_GET['id']) ? $_GET['id'] : null;
		$relDocType=isset($_GET['relDocType'])?$_GET['relDocType']:null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr[$relDocType];
		$protaskInfo = $service->get_d($taskId);
		foreach ($protaskInfo as $key => $val) {
            if ($key == 'details') {
				$products = $service->showItemEdit($protaskInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}
			$this->show->assign($key, $val);
        }
//		$this->showDatadicts ( array ('type' => 'FHXZ' ),'FHXZ-FH' );
		switch ($relDocType){
				case 'oa_sale_order':$this->display("edit");break;
				case 'oa_sale_lease':$this->display("edit");break;
				case 'oa_sale_service':$this->display("edit");break;
				case 'oa_sale_rdproject':$this->display("edit");break;
				case 'oa_borrow_borrow':$this->display("edit");break;
				case 'oa_present_present':$this->display("edit");break;
				default:break;
			}
	}

	/**
	 * 修改生产计划
	 */
	 function c_edit(){
	 	$taskObj = $_POST[$this->objName];
	 	if( isset( $_GET['issued'] ) ){
			$taskObj['issuedStatus'] = 1;
			$taskObj['issuedDate'] = day_date;
	 	}
	 	if( $this->service->edit_d( $taskObj ) ){
			$msg = $_GET ["msg"] ? $_GET ["msg"] : '编辑成功！';
	 	}
		msg ( $msg );
	 }



	/**
	 * 跳转到查看生产页面
	 */
	function c_toView(){
		$taskId = isset($_GET['id']) ? $_GET['id'] : null;
		$relDocType=isset($_GET['relDocType'])?$_GET['relDocType']:null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr[$relDocType];
		$protaskInfo = $service->get_d($taskId);

		if( $relDocType ){
			switch($relDocType){
			case"oa_sale_order":$skey=$this->md5Row($protaskInfo['relDocId'],'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($protaskInfo['relDocId'],'contract_rental_rentalcontract');break;
			case"oa_sale_service":$skey=$this->md5Row($protaskInfo['relDocId'],'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($protaskInfo['relDocId'],'rdproject_yxrdproject_rdproject');break;
			case"oa_borrow_borrow":$skey=$this->md5Row($protaskInfo['relDocId'],'projectmanagent_borrow_borrow');break;
			case"oa_present_present":$skey=$this->md5Row($protaskInfo['relDocId'],'projectmanagent_present_present');break;
			}
		}

		foreach ($protaskInfo as $key => $val) {
            if ($key == 'details') {
				$products = $service->showItemView($protaskInfo['details'], new $relatedStrategy());
				$this->show->assign("products",$products);
			}if( $key == 'relDocCode' ){
				$orderUrl = $service->viewRelInfo( $protaskInfo,$skey, new $relatedStrategy() );
				$protaskInfo['relDocCode'] .= $orderUrl;
				$this->show->assign("relDocCode",$protaskInfo['relDocCode']);
			}else
				$this->show->assign($key, $val);
        }
//        $type = $this->getDataNameByCode($protaskInfo['type']);
//		$this->assign('type',$type);
		switch ($relDocType){
				case 'oa_sale_order':$this->display("view");break;
				case 'oa_sale_lease':$this->display("view");break;
				case 'oa_sale_service':$this->display("view");break;
				case 'oa_sale_rdproject':$this->display("view");break;
				case 'oa_borrow_borrow':$this->display("view");break;
				case 'oa_present_present':$this->display("view");break;
				default:break;
			}
	}

	/**
	 * 合同生产列表
	 */

	 function c_contProPage(){
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
	   * ajax关闭生产任务
	   */
	   function c_finishTask(){
	   		$this->permCheck($_POST['id']);
//		 	if( !isset($this->service->this_limit['发货计划']) ){
//		 		echo 2;
//		 		return 0;
//		 	}
		  	$taskId = $_POST['id'];
		  	$taskInfo = array(
		  		'id' => $taskId,
		  		'proStatus' => 'YWC'
		  	);
		  	echo $this->service->updateById( $taskInfo );
	   }

 }
?>