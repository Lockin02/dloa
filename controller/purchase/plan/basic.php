<?php
/**
 * @filename	basic.php
 * @function	采购计划主表控制类
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-12
 * @lastmodify	2011-1-12
 * @package	oae/controller/purchase/plan
 * @link		no
 */
class controller_purchase_plan_basic extends controller_base_action {

	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		$this->objName = 'basic';
		$this->objPath = 'purchase_plan';
		parent::__construct ();
	}
	/*****************************************采购管理-采购计划主体********************************************/

	/**
	 * 采购管理-采购计划 主体框架frameplanChangeList
	 */
	function c_index() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'frame' );
	}

	/**
	 * 采购管理-采购计划--左导航栏
	 */
	function c_toIndexMenu() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display (  'menu' );
	}

	/**
	 * 跳转到编辑页面
	 *
	 * @param tags
	 */
	function c_toEdit () {
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign('invnumber',count( $plan ["childArr"] ));
		switch($purchType){
			case 'assets':
			$this->assign ( 'list', $this->service->showAssetEdit_s ( $plan ) );
			$this->display('assets-edit');
			break;
			case 'rdproject':
			$this->assign ( 'list', $this->service->showRdEdit_s ( $plan  ) );
			$this->display('rdproject-edit');
			break;
//			case 'rdproject':$this->view('rd-edit');break;
			case 'produce':
					$equipmentDao = new model_purchase_plan_equipment ();
					$this->showDatadicts(array('qualityList'=>'CGZJSX'));
					$this->assign ( 'listWithType', $this->service->showEditType_s ( $plan ["childArr"] ) );
					$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], true ) );
					if($plan['batchNumb']!=""){
						$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
						$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
					}else{
						$this->assign ( 'batchEquList', "");
					}
					$this->display('produce-edit');
					break;
			default:
			$this->assign ( 'list', $this->service->showEdit_s ( $plan ["childArr"] ) );
		}
	}

		/**
	 * 跳转审批编辑页面
	 *
	 * @param tags
	 */
	function c_toAuditEdit () {
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isLastStep($_GET['id'],$this->service->tbl_name);
		if($flag){
			$plan = $this->service->getPlan_d ( $id );
			if($plan['isPlan']==1){
				$this->assign('isPlanYes','checked');
			}else{
				$this->assign('isPlanNo','checked');
			}
			foreach ( $plan as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign('invnumber',count( $plan ["childArr"] ));
			$equipmentDao = new model_purchase_plan_equipment ();
						$this->assign ( 'listWithType', $this->service->showEditAudit_s ( $plan ["childArr"] ) );
			$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
			if($plan['batchNumb']!=""){
				$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
				$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
			}else{
				$this->assign ( 'batchEquList', "");
			}
			$this->display('produce-audit-edit');
		}else{
			$this->c_read();
		}
	}
	    /**
     * 生产采购审批后
     */
     function c_dealApproval(){
		if (! empty ( $_GET ['spid'] )) {
			//审批流回调方法
            $this->service->workflowCallBack_deal($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}


	/**
	 * 跳转到确认物料页面(固定资产及研发采购用)
	 * add by chengl 2012-04-07
	 * @param tags
	 */
	function c_toConfirmProduct () {
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;

		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$purchType=$plan['purchType'];
		$this->assign('invnumber',count( $plan ["childArr"] ));
		$this->assign ( 'list', $this->service->showConfirmEdit_s ( $plan ["childArr"] ) );

		switch($purchType){
			case 'assets':
			$this->display('assets-confirm');
			break;
			case 'rdproject':
			$this->display('rdproject-confirm');
		}
	}

	/**
	 * 跳转到分配采购员页面(固定资产及研发采购用)
	 * add by chengl 2012-04-07
	 * @param tags
	 */
	function c_toConfirmUser () {
		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;

		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$purchType=$plan['purchType'];
		$this->assign('invnumber',count( $plan ["childArr"] ));
		if($purchType=="rdproject"){
			$this->assign ( 'list', $this->service->showConfirmRdRead_s ( $plan ["childArr"] ) );
		}else{
			$this->assign ( 'list', $this->service->showConfirmAssetRead_s ( $plan ["childArr"] ) );
		}

		switch($purchType){
			case 'assets':
			$this->display('assets-confirmuser');
			break;
			case 'rdproject':
			$this->display('rdproject-confirmuser');
		}
	}


	/**
	 * 采购管理-采购计划--右边导航Tab页
	 */
	function c_toTabList() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'list-tab' );
	}

	/**
	 * 变更的采购申请列表
	 */
	function c_planChangeList () {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$this->display ( 'change-list' );
	}

	/**
	 * @description 申请变更列表
	 * @author qian
	 * @date 2011-2-17 14:57
	 */
	function c_changePageJson() {
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		if($purchType=="contract_sales"){
			$service->searchArr ['purchTypeArr'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//借试用采购
			$service->searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//所有采购类型
			//$service->searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else{
			$service->searchArr ['purchType']=$purchType;
		}
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *根据合同ID，列出采购申请列表
	 */
	function c_toListByContractId () {
		$planNumb = isset ( $_GET ['planNumb'] ) ? $_GET ['planNumb'] : "";
		$contractId = isset ( $_GET ['contractId'] ) ? $_GET ['contractId'] : "";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		if ($planNumb != "") {
			$searchArr ['seachPlanNumb'] = $planNumb;
		}
		$searchArr['purchType']=$purchType;
		$searchArr['sourceID']=$contractId;
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr=$searchArr;
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->pagePlan_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();
		$this->assign ( 'planNumb', $planNumb );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'contractId', $contractId );
		$this->assign ( 'list', $this->service->showListByContract_s ( $rows ) );
		$this->display ( 'list-contract' );
		unset ( $this->show );
	}

	/*****************************************个人办公-我下达的采购计划********************************************/

	/**
	 * 我下达的采购计划-统计列表
	 */
	function c_myPlanList() {
		$planNumb = isset ( $_GET ['planNumb'] ) ? $_GET ['planNumb'] : "";
		$searchArr = array();
		if ($planNumb != "") {
			$searchArr ['seachPlanNumb'] = $planNumb;
		}
		$searchArr ['createId'] = $_SESSION ['USER_ID'];
		$searchArr ['state'] = $this->service->stateToSta ( "execute" );

		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = $searchArr;
		$showpage = new includes_class_page ();
		$rows = $service->pagePlan_d ();
		$this->pageShowAssign ();	//分页
		$this->assign ( 'planNumb', $planNumb );
		$this->assign ( 'purchType', $rows[0]['purchType'] );
		$this->assign ( 'list', $service->showMyPlanlist_s ( $rows ) );

		$this->display ( 'my-list-plan' );
		unset ( $this->show );
	}

	/**我的采购申请列表
	*author can
	*2011-6-19
	*/
	function c_toMyList(){
		$this->display('myplan-list');
	}

	/**我的采购申请导航
	*author can
	*2011-6-19
	*/
	function c_toMenu(){
		$this->display('my-menu');
	}

	/**
	 *我的采购申请页面
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_myApplyList () {
		$this->display('my-apply-list');
	}

	/**
	 *研发采购申请确认列表
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toConfirmList () {
		$this->view('rd-confirm-list');
	}
	/**
	 *研发采购申请确认页面
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toConfirm () {
//		$this->permCheck ();//安全校验
		$id=isset($_GET['id'])?$_GET['id']:null;
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if($plan['isPlan']==1){
			$this->assign('isPlanYes','checked');
		}else{
			$this->assign('isPlanNo','checked');
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'list', $this->service->showEdit_s ( $plan ["childArr"] ) );
		$this->assign('invnumber',count( $plan ["childArr"] ));
		$this->view('rd-confirm');
	}

	/**
	 * 我的已完成采购计划列表
	 */
	function c_myPlanEndList() {
//		$searchvalue= isset ( $_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//搜索值
//		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
//		if ($searchvalue!= "") {
//			$searchArr [$searchCol] =$searchvalue;
//		}
//		$searchArr ['createId'] = $_SESSION ['USER_ID'];
//		$searchArr ['stateInArr'] = $this->service->stateToSta ( "end" ) . "," . $this->service->stateToSta ( "close" );
//		$service = $this->service;
//		$service->getParam ( $_GET );
//		$service->searchArr=$searchArr;
//		$rows = $service->pageEndPlan_d ();
//		$rows = $this->sconfig->md5Rows ( $rows );
//		//分页
//		$this->pageShowAssign ();
//		$this->assign ( 'searchvalue', $searchvalue );
//		$this->assign ( 'searchCol', $searchCol );
//		$this->assign ( 'list', $service->showMyPlanEndlist_s ( $rows ) );
//		$this->display ( 'my-list-end-plan' );
//		unset ( $this->show );
		$this->display('my-close-list');
	}

	/**
	 * 我的变更的采购申请列表
	 */
	function c_myPlanChangeList () {
		$this->display ( 'my-change-list' );
	}
		/**
	 * @description 生产批次号
	 */
	function c_batchNumbPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$service->groupBy = "c.batchNumb";
		$rows = $service->pageBySqlId ("select_batchnumb");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 我的申请变更列表
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 我的申请列表
	 */
	function c_myListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//调用接口组合数组将获取一个objAss的子数组
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 待物料分配的采购申请
	 */
	function c_myConfirmListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['productSureUserIdUnion'] = $_SESSION ['USER_ID'];
//		$service->searchArr ['productSureStatusArr'] ='0,2';
		//$service->searchArr ['ExaStatusArr'] = array("完成","部门审批","物料");

		$service->asc = true;
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->pageBySqlId ("plan_list_union");
		$rows = $this->sconfig->md5Rows ( $rows );
		//调用接口组合数组将获取一个objAss的子数组
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
//				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 资产采购申请列表
	 */
	function c_assetListPageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//调用接口组合数组将获取一个objAss的子数组
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 研发采购确认列表
	 */
	function c_rdConfirmJson() {
		$service = $this->service;
//		$service->searchArr['purchType']="rdproject";
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$rows = $service->pageBySqlId ("plan_list_page");
		$rows = $this->sconfig->md5Rows ( $rows );
		//调用接口组合数组将获取一个objAss的子数组
		$interfObj = new model_common_interface_obj();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->service->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*****************************************采购管理-采购计划********************************************/

	/**
	 * 采购计划-统计列表
	 */
	function c_planList() {
		$searchvalue= isset($_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//搜索值
		$idsArry = isset($_GET ['idsArry'] ) ? $_GET ['idsArry'] : "";
		$purchType = isset($_GET['purchType']) ? $_GET['purchType'] : "";
		$searchCol = isset($_GET['searchCol']) ? $_GET['searchCol'] : "";//搜索字段
		if ($searchvalue != "") {
			$searchArr[$searchCol] = $searchvalue;
		}
		if($purchType=="contract_sales") {
			$searchArr['purchTypeArrUnion'] = "HTLX-XSHT,HTLX-ZLHT,HTLX-FWHT,HTLX-YFHT";
		}else if($purchType=="borrow_present") {//借试用采购
			$searchArr['purchTypeArrUnion'] = "oa_borrow_borrow,oa_present_present";
		}else if($purchType=="") {//显示全部采购类型的采购申请
		} else{
			$searchArr['purchTypeArrUnion'] = $purchType;
		}
		$searchArr['ExaStatusUnionArr'] = array("完成","物料确认打回");
//		$searchArr['sureStatusNo']="0";//过滤未确认的研发采购
		$searchArr ['stateUnionArr'] ='0';
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr = $searchArr;

		$rows = $service->pageListUnion_d ();

		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();
		$this->assign ( 'searchvalue', $searchvalue );
		$this->assign ( 'searchCol', $searchCol );
		$this->assign ( 'idsArry', $idsArry );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $this->service->showPlanlist_s ( $rows ) );
		$this->display ( 'list-plan' );
		unset ( $this->show );
	}

	/**
	 * 检查申请单下达任务时的数据是否满足指的的规则
	 */
	function c_chkDataAvailable(){
		$service = $this->service;
		$ids = isset($_REQUEST['ids'])? $_REQUEST['ids'] : '';
		$assetIds = '';
		$idsArr = explode(",",$ids);
		foreach ($idsArr as $k => $v){
			$assetIdArr = explode('asset',$v);
			if($v == ''){
				unset($idsArr[$k]);
			}else if(isset($assetIdArr[1])){
				unset($idsArr[$k]);
				$assetIds .= $assetIdArr[1].',';
			}
		}
		$ids = implode($idsArr , ',');
		$assetIds = trim($assetIds,',');

		$result = array();
		if($ids != '' || $assetIds != ''){
			$noPassNum = 0;$businessBelong=$purchType = '';
			if($assetIds != ''){
				$sql = "select b.id,b.formBelong,b.formBelongName,b.businessBelong,b.businessBelongName from oa_asset_purchase_apply_item e left join oa_asset_purchase_apply b ON e.applyId = b.id where e.id in({$assetIds}) ;";
				$data = $service->_db->getArray($sql);
			}else if($ids != ''){
				$sql = "select b.id,e.purchType,b.formBelong,b.formBelongName,b.businessBelong,b.businessBelongName from oa_purch_plan_equ e left join oa_purch_plan_basic b ON e.basicId = b.id where e.id in({$ids}) ;";
				$data = $service->_db->getArray($sql);
			}else{
				$data = array();
			}
			if($data){
				foreach ($data as $k => $v){
					// 检查类型是否一致
//					$purchType = ($purchType == '')? $v['purchType'] : $purchType;
//					if($v['purchType'] != $purchType && $purchType!=''){
//						$noPassNum+=1;
//					}

					// 检查归属公司是否一致
					$result['businessBelong'] = (!isset($result['businessBelong']) || (isset($result['businessBelong']) && $result['businessBelong'] == ''))? $v['businessBelong'] : $result['businessBelong'];
					if($v['businessBelong'] != $result['businessBelong'] && $result['businessBelong']!=''){
						$result['errorType'] = 'businessBelong';
						echo json_encode($result);exit();
					}else{
						$result['businessBelong'] = ($ids != '' && $assetIds != '')? 'dl' : $result['businessBelong'];
						$result['errorType'] = '';
					}
				}
			}else{
				$result['errorType'] = 'emptydata';
			}
		}else{
			$result['errorType'] = 'emptyIds';
		}
		echo json_encode($result);
	}

	/**
	 * 已完成采购计划列表
	 */
	function c_planEndList() {
		$searchvalue= isset ( $_GET ['searchvalue'] ) ? $_GET ['searchvalue'] : "";//搜索值
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		if ($searchvalue!= "") {
			$searchArr [$searchCol] =$searchvalue;
		}
		if($purchType=="contract_sales"){
			$searchArr['purchTypeArrUnion'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//借试用采购
			$searchArr['purchTypeArrUnion'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//显示所有类型的采购申请
			//$searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else{
			$searchArr['purchTypeArrUnion']=$purchType;
		}
		$searchArr ['stateUnionArr'] = $this->service->stateToSta ( 'end' ) . ',' . $this->service->stateToSta ( 'close' );
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->searchArr=$searchArr;
//		$service->sort='dateEnd';
		$rows = $service->pageEndListUnion_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign ();	//分页
		$this->assign ( 'searchvalue', $searchvalue );
		$this->assign ( 'searchCol', $searchCol );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $service->showPlanEndlist_s ( $rows ) );
		$this->display (  'list-end-plan' );
		unset ( $this->show );
	}

	/**资产采购申请审批TAB页
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditTab(){
		$this->display('asset-audit-tab');
	}

	/**资产采购申请未审批列表
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditNo(){
		$this->display('asset-audit-no');
	}

	/**资产采购申请已审批列表
	*author can
	*2011-7-6
	*/
	function c_toAssetAuditYes(){
		$this->display('asset-audit-yes');
	}

	/**根据源单ID查找采购申请列表
	*author can
	*2011-7-6
	*/
	function c_toSourceList(){
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:null;
		$sourceId=isset($_GET['sourceId'])?$_GET['sourceId']:null;//源单ID
		$this->assign('purchType',$purchType);
		$this->assign('sourceId',$sourceId);
		$this->display('source-list');
	}

	/**
	 * @desription 资产采购待审批列表数据过滤方法
	 * @qiaolong
	 */
	function c_myAuditPj() {
		$interfObj = new model_common_interface_obj();
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$val['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型中文名称
				array_push($newRows,$val);
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**资产采购已审批列表
	*/
	function c_pageJsonAuditYes(){
		$interfObj = new model_common_interface_obj();
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				$val['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型中文名称
				array_push($newRows,$val);
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**研发采购申请审批TAB页
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditTab(){
		$this->display('rdproject-audit-tab');
	}

	/**资产采购申请未审批列表
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditNo(){
		$this->display('rdproject-audit-no');
	}

	/**资产采购申请已审批列表
	*author can
	*2011-7-6
	*/
	function c_toRdprojectAuditYes(){
		$this->display('rdproject-audit-yes');
	}

	/**生产采购申请审批TAB页
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditTab(){
		$this->display('produce-audit-tab');
	}

	/**生产采购申请未审批列表
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditNo(){
		$this->display('produce-audit-no');
	}

	/**生产采购申请已审批列表
	*author can
	*2011-7-6
	*/
	function c_toProduceAuditYes(){
		$this->display('produce-audit-yes');
	}

	/*****************************************增删改查方法********************************************/

	/**
	 * 新增采购计划操作
	 */
	function c_add() {
		$object = $this->service->add_d ( $_POST [$this->objName] );
		if ($object) {
			showmsg ( "添加采购计划成功！" );
		} else {
			showmsg ( "添加失败！" );
		}
	}

	/**
	 * 采购计划统计页--跳转到新增采购任务页面
	 * 通过计划来下达采购任务
	 */
	function c_toAddTask() {
		$idsArry = isset ( $_GET ['idsArry'] ) ? substr ( $_GET ['idsArry'], 1 ) : exit ();
		$businessBelong = isset ( $_GET ['businessBelong'] ) ? $_GET ['businessBelong'] : 'dl';
		$this->service->getParam ( $_GET );
		$obj = new model_purchase_plan_equipment ();
		$listEqu = $obj->getEquForTask_d ( $idsArry );

//		echo "<pre>";
//		print_r($listEqu);
		$this->assign('sendTime',date("Y-m-d"));
		$infoRow=$this->service->getRemarkInfo_d($listEqu);//获取采购申请备注信息
		$this->assign('instruction',$infoRow['instruction']);

		$branchDao = new model_deptuser_branch_branch();
		$businessBelongArr = $branchDao->getByCode($businessBelong);
		if($businessBelongArr && !empty($businessBelongArr)){
			$businessBelongName = $businessBelongArr['NameCN'];
		}else{
			$businessBelong = 'dl';
			$businessBelongName = '世纪鼎利';
		}

		$this->assign('formBelong',$businessBelong);
		$this->assign('formBelongName',$businessBelongName);
		$this->assign('businessBelong',$businessBelong);
		$this->assign('businessBelongName',$businessBelongName);

		$this->assign('remark','');
		//获取最小希望完成时间
//		$minHopeDate=$obj->getMinHopeDate_d($idsArry);
		$this->assign('dateHope',date("Y-m-d"));
		if ($listEqu) {
			// 获取升级单据归属公司信息
			foreach ($listEqu as $k => $v){
				$listEqu[$k]['formBelong'] = $businessBelong;
				$listEqu[$k]['formBelongName'] = $businessBelongName;
				$listEqu[$k]['businessBelong'] = $businessBelong;
				$listEqu[$k]['businessBelongName'] = $businessBelongName;
			}

			$this->show->assign ( 'list', $obj->newTask ( $listEqu ) );

			$taskNumb = "ptask-" . date ( "YmdHis" ) . rand ( 10, 99 );
			$this->assign ( 'taskNumb', $taskNumb );
			$this->display ( 'task-add' );
		} else {
			showmsg ( '错误', 'temp', 'button' );
		}
		unset ( $this->show );
	}

	/**
	 * 跳转到确认tab页面
	 */
	function c_tabpage() {
		$this->display("confim-tabs");
	}

	/**
	 * 查看方法
	 */
	function c_read() {
		$testTypeArr = array(
			'0'=>'全检',
			'1'=>'免检',
			'2'=>'抽检',
		);

		$this->permCheck ();//安全校验
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		if(is_array( $plan['childArr'] )&&count( $plan['childArr'] )>0){
			foreach( $plan['childArr'] as $key => $val ){
				if( $val['testType']!='' ){
					$testType = $val['testType'];
					$plan['childArr'][$key]['testType']=$testTypeArr[$testType];
				}
			}
		}
		//判断是否显示搜索采购申请按钮
		if($_GET['show']){
			$this->assign('show', $_GET['show']);
		}

//		echo "<pre>";
//		print_R($plan);
		//获取物料的执行情况
		$equipmentDao = new model_purchase_plan_equipment ();
//		$executRows=$equipmentDao->getEquExecute_d($id);
//		$this->assign ( 'listExecute', $this->service->showExecute_s ($executRows));
		$this->assign ( 'listEquExecute', $this->service->showEquExecuteList_s ( $plan ["childArr"] ));
		//
		$purchType=$plan['purchType'];
		$this->assign (  'readType',$readType );
		if($plan['isPlan']==1){
			$plan['isPlan']="是";
		}else{
			$plan['isPlan']="否";
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($plan['purchType'] == "oa_borrow_borrow"){
			$this->assign ( "purchTypeCName", "借试用合同" );
		}
//		if($purchTypeCon=="contract_sales"){
			switch($plan[purchType]){
			case"oa_sale_order":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_order_order');$this->assign ( 'contractdao', 'projectmanagent_order_order');break;
			case"oa_sale_lease":$skey=$this->md5Row($plan['sourceID'],'contract_rental_rentalcontract');$this->assign ( 'contractdao','contract_rental_rentalcontract' );break;
			case"oa_sale_service":$skey=$this->md5Row($plan['sourceID'],'engineering_serviceContract_serviceContract');$this->assign ( 'contractdao', 'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$skey=$this->md5Row($plan['sourceID'],'rdproject_yxrdproject_rdproject');$this->assign ( 'contractdao','rdproject_yxrdproject_rdproject' );break;
			case"oa_borrow_borrow":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_borrow_borrow');$this->assign ( 'contractdao','projectmanagent_borrow_borrow' );break;
			case"oa_present_present":$skey=$this->md5Row($plan['sourceID'],'projectmanagent_present_present');$this->assign ( 'contractdao','projectmanagent_present_present' );break;
		}
//		}
		$this->assign ( 'list', $this->service->showRead_s ( $plan ["childArr"] ) );
		if($purchType=="oa_sale_order"||$purchType=="oa_sale_lease"||$purchType=="oa_sale_service"||$purchType=="oa_sale_rdproject"){
			switch($purchType){
			case"oa_sale_order":$this->assign ( 'contractdao', 'projectmanagent_order_order');break;
			case"oa_sale_lease":$this->assign ( 'contractdao','contract_rental_rentalcontract' );break;
			case"oa_sale_service":$this->assign ( 'contractdao', 'engineering_serviceContract_serviceContract');break;
			case"oa_sale_rdproject":$this->assign ( 'contractdao','rdproject_yxrdproject_rdproject' );break;
			}
		}
		//新合同采购
		if($purchType=="HTLX-XSHT"||$purchType=="HTLX-ZLHT"||$purchType=="HTLX-FWHT"||$purchType=="HTLX-YFHT"){
			$skey=$this->md5Row($plan['sourceID'],'contract_contract_contract');
			$this->assign ( 'contractdao','contract_contract_contract' );
		}
		switch($purchType){
			case"oa_sale_order":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_lease":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_service":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_sale_rdproject":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_borrow_borrow":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"oa_present_present":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case"assets":
			$this->assign ( 'list', $this->service->showAssetRead_s ( $plan ["childArr"] ) );
            $this->display ('assets-view' );break;
			case"stock":$skey=$this->md5Row($plan['sourceID'],'stock_fillup_fillup');$this->assign('skey',$skey);
				$this->assign ( 'list', $this->service->showStockRead_s ( $plan ["childArr"] ) );
				$this->display ( 'stock-view' );break;

			case"rdproject":
			$this->assign ( 'list', $this->service->showRdRead_s ( $plan ["childArr"] ) );
			$this->display ( 'rdproject-view' );break;
			case"produce":
					$equRows=$equipmentDao->getAllEqu_d($id);
					$this->assign ( 'listWithType', $this->service->showReadType_s ( $equRows ) );
					//显示附件信息
					$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
					if($plan['batchNumb']!=""){
						$batchEquRows=$equipmentDao->getBatchEqu_d($id,$plan['batchNumb']);
						$this->assign ( 'batchEquList', $equipmentDao->batchEquList ( $batchEquRows));
					}else{
						$this->assign ( 'batchEquList', "");
					}
					$this->display ( 'produce-view' );
					break;
			case "HTLX-XSHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-ZLHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-FWHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			case "HTLX-YFHT":$this->assign('skey',$skey);$this->display ( 'contract-view' );break;
			default:$this->display ( 'read' );break;
		}
	}

	/**
	 * @exclude 完成采购计划
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午07:40:19
	 */
	function c_end() {
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$val = $this->service->end_d ( $id );
		if ($val == 1) {
			msgGo ( "操作成功" );
		} else if ($val == 2) {
			msgGo ( "存在为未完成的设备，不可完成" );
		} else {
			msgGo ( "操作失败！！可能是服务器错误，请稍后再试" );
		}
	}
	/**
	 *跳动到关闭页面
	 *
	 */
	 function c_toClose(){
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] :"";
		$purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null;
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		//获取物料的执行情况
		$equipmentDao = new model_purchase_plan_equipment ();
		$purchType=$plan['purchType'];
		$this->assign (  'readType',$readType );
		if($plan['isPlan']==1){
			$plan['isPlan']="是";
		}else{
			$plan['isPlan']="否";
		}
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$testTypeArr = array(
			'0'=>'全检',
			'1'=>'免检',
			'2'=>'抽检',
		);
		if(is_array( $plan['childArr'] )&&count( $plan['childArr'] )>0){
			foreach( $plan['childArr'] as $key => $val ){
				if( $val['testType']!='' ){
					$testType = $val['testType'];
					$plan['childArr'][$key]['testType']=$testTypeArr[$testType];
				}
			}
		}
		$this->assign ( 'list', $this->service->showRead_s ( $plan ["childArr"] ) );

		$this->display ( 'close' );

	 }

	/**
	 * @exclude 关闭采购任务
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 上午09:58:09
	 */
	function c_close() {
		if ($this->service->dealClose_d ( $_POST ['basic'] )) {
			msgBack2 ( "关闭成功" );
		} else {
			msgBack2 ( "关闭失败！！可能是服务器错误，请稍后再试" );
		}
	}

    /**
     * 资产采购 邮件反馈
     */
     function c_toFeedback(){
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] :"";
		$purchTypeCon=isset($_GET['purchType'])?$_GET['purchType']:null;
		$readType=isset($_GET['actType'])?$_GET['actType']:null;
		$plan = $this->service->getPlan_d ( $id );
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->view ( 'feedback' );
	 }
	 /**
	  * 资产采购邮件反馈发送
	  */
    function c_feedbackpush() {
		$rows = $_POST['objInfo'];
        $emailDao = new model_common_mail();
		$content = $rows['content'];
		$emailInfo = $emailDao->batchEmail("1",$_SESSION['USERNAME'],$_SESSION['EMAIL'],'assetPurchase_feedback','发送了',null,$rows['TO_ID'],$content);
	    msgBack2 ( "发送成功" );
	}
	/*****************************************变更处理********************************************/

	/**
	 * @exclude 变更跳转方法
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 下午03:54:10
	 */
	function c_toChange() {
		$this->permCheck ();//安全校验
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$productIds = isset ( $_GET ['productIds'] ) ? $_GET ['productIds'] : "";
		$productArr = explode ( ',', $productIds );
		//获取采购计划详细信息
		$plan = $this->service->getPlan_d ( $id );
		foreach ( $plan as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'list', $this->service->showChange_s ( $plan ["childArr"] ) );
		$this->display ( 'change' );
	}

	/**
	 * 变更保存方法
	 */
	function c_change() {
		$val = $this->service->change_d ( $_POST ['basic'] );
		if ($val) {
			msgBack2 ( "变更成功！");
		}
		else {
			msgBack2 ( "变更失败" );
		}
	}
	/**
	 * 删除采购申请
	 */
	function c_deletesInfo() {
		$deleteId=isset($_POST['id'])?$_POST['id']:exit;
	    $delete=$this->service->deletesInfo_d ($deleteId);
	    //如果删除成功输出1，否则输出0
         if($delete){
			echo 1;
    	}else{
    		echo 0;
    	}
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
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
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
	/**跳转到资产采购列表
	 * */
	function c_assetList () {
		$this->display('list-asset');
	}

	/**
	 * 资产采购下达
	 *
	 */
	function c_pushPurch () {
		$id=$_POST['id'];
		$applyNumb=$_POST['applyNumb'];
		$applyObj=array("id"=>$id,
							"ExaStatus"=>"完成");
		//更新状态
		$flag=$this->service->updateById($applyObj);
		if($flag){
			//发送邮件通知采购部负责人
			$mailArr=$this->service->pushPurch;  //获取默认收件人数组
			$this->service->sendEmail_d($id,$applyNumb,$mailArr);
			echo 1;
		}else{
			echo 0;
		}

	}

	/**
	 * 资产采购审批通过后发送邮件通知相关负责人
	 *
	 */
	 function c_emailNotice(){
		if (! empty ( $_GET['spid'] )) {
			//审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}

	 }

	/**
	 * 变更审批通过处理方法
	 *
	 */
	 function c_dealChange(){
		if (! empty ( $_GET ['spid'] )) {
			//审批流回调方法
            $this->service->workflowCallBack_change($_GET['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}

	 }

	 /**
	 * 批量更新采购申请的状态为完成
	 *
	 */
	 function c_updateStateEnd(){
		$searchArr ['state'] = $this->service->statusDao->statusEtoK ( "execute" );
		$service = $this->service;
		$service->searchArr=$searchArr;
		$flag = $service->updateData_d ();
		if($flag){
			echo "<script>alert('更新成功');this.location='?model=purchase_plan_basic&action=planList'</script>";
		}else{
			echo "<script>alert('更新失败');this.location='?model=purchase_plan_basic&action=planList'</script>";
		}


	 }

	/*****************************************显示分割线********************************************/

	/********************************************导出************************************************/

	/**
	 * 跳转到我的采购申请导出页面
	 */
	 function c_toExport(){
	 	$this->view('my-apply-export');
	 }

	/**
	 * 我的采购申请导出
	 */
	 function c_myPlanExport(){
//	 	echo '<pre>';
//	 	print_R($_POST['data']);
	 	$object = $_POST[$this->objName];
	 	$state = substr($object['stateVal'],0,-1);
	 	$this->service->searchArr['preDateHope'] = $object['beginDate'];
	 	$this->service->searchArr['afterDateHope'] = $object['endDate'];
	 	$this->service->searchArr['stateInArr'] = $state;
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		$this->service->searchArr['createId'] = $_SESSION['USER_ID'];
		$planEquRows = $this->service->listBySqlId('plan_export_list');
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['contractCode']=$planEquRows[$key]['sourceNumb'];
			$exportData[$key]['contractName']=$planEquRows[$key]['contractName'];
			$exportData[$key]['purchaseCode']=$planEquRows[$key]['basicNumb'];
			$exportData[$key]['issuedDate']=$planEquRows[$key]['dateIssued'];
			$exportData[$key]['hopeDate']=$planEquRows[$key]['dateHope'];
			$exportData[$key]['prodectName']=$planEquRows[$key]['productName'];
			$exportData[$key]['number']=$planEquRows[$key]['amountAll'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
		}
		return model_purchase_plan_purchaseExportUtil::export2ExcelUtil ( $exportData );
	 }

	/**
	 * 跳转到我的采购申请导出导出实效表页面
	 */
	 function c_toExportAging(){
	 	$this->assign('beginDate',date('Y-m') . '-01');
	 	$this->assign('endDate',day_date);
	 	$this->view('my-apply-exportaging');
	 }

	/**
	 * 采购实效导出
	 */
	function c_myPlanExportAging(){
	 	$object = $_POST[$this->objName];

		$rs = $this->service->myPlanExportAging_d($object);
		return model_purchase_plan_purchaseExportUtil::exportAging_e ( $rs );
	}
	/********************************************end************************************************/


	/**
	 * 确认采购申请物料
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_confirmProduct () {
		$object=$this->service->confirmProduct_d($_POST['basic']);
		if($object){
			msgGo('确认成功','index1.php?model=purchase_plan_basic&action=toConfirmProductList');
		}else{
			msgGo('确认失败','index1.php?model=purchase_plan_basic&action=toConfirmProductList');

		}
	}

	/**
	 * 确认采购物料分配人
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_confirmProductUser () {
		$object=$this->service->confirmProductUser_d($_POST['basic']);
		if($object){
			msgGo('下达成功','index1.php?model=purchase_plan_basic&action=toTabList');
		}else{
			msgGo('下达失败','index1.php?model=purchase_plan_basic&action=toTabList');

		}
	}

	/**
	 * 物料确认打回给申请人
	 *add by chengl 2012-04-07
	 * @param tags
	 * @return return_type
	 */
	function c_backBasicToApplyUser() {
		$object=$this->service->backBasicToApplyUser_d($_POST['basic']);
		if($object){
			msgGo('打回成功','index1.php?model=purchase_plan_basic&action=toTabList');
		}else{
			msgGo('打回失败','index1.php?model=purchase_plan_basic&action=toTabList');

		}
	}

	/**
	 * 跳转到待确认的采购申请
	 */
	function c_toConfirmProductList(){
		$this->view("confirm-list");
	}

	/**
	 * 采购申请审批通过后处理
	 */
	function c_confirmAudit(){
		if (! empty ( $_GET ['spid'] )) {
			$this->service->confirmAudit($_GET ['spid'] );
		}
		$urlType = isset ( $_GET ['urlType'] ) ? $_GET ['urlType'] : null;
		//防止重复刷新
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			echo "<script>this.location='?model=purchase_plan_basic&action=toAssetAuditTab'</script>";
		}
	}

	/**
	 * 导出生产采购申请物料页面
	 *
	 */
	 function c_toExportProduceEqu(){
		if(!$this->service->this_limit['导出批次物料']){
			echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
			exit();
		}
		$this->display('produceequ');
	 }

	/**
	 * 导出生产采购申请物料页面
	 *
	 */
	 function c_exportProduceEqu(){
	 	$object = $_POST[$this->objName];
		//获取同批次物料
		$equipmentDao = new model_purchase_plan_equipment ();
		$rows = $equipmentDao->getBatchEquWithBatch_d($object['batchNumb']);
		//导出批次物料获取入库数量
		$rows= $this->service->getStockNumbTotal($rows);
		//导出批次物料获取收料数量
		$receiveNum = new model_purchase_task_equipment();
		$rows = $receiveNum->getReceiveNum($rows);
		return model_purchase_plan_purchaseExportUtil::exportProduceEqu_e ( $rows,$object['batchNumb']);
	 }

	/**
	 * 导出采购申请物料页面
	 *
	 */
	 function c_toExportPlanEqu(){
		if(!$this->service->this_limit['导出申请物料']){
			echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
			exit();
		}
		$this->display('planequ');
	 }

	/**
	 * 导出采购申请物料
	 *
	 */
	 function c_exportPlanEqu(){
	 	$object = $_POST[$this->objName];
		$searchArr = array ();
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['purchType']!=""){
				$searchArr['purchType']=$object['purchType'];;
			}
		}
		//获取同批次物料
		$equipmentDao = new model_purchase_plan_equipment ();
		$rows = $equipmentDao->getPlanEquList_d($searchArr);
		return model_purchase_plan_purchaseExportUtil::exportPlanEqu_e ( $rows);
	 }

	 	/**
	 * @exclude 重新启动采购任务
	 * @param
	 */
	function c_startPlan() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->startPlan_d($id) ){
			msgGo("启动成功");
		}else{
			msgGo("启动失败！！可能是服务器错误，请稍后再试");
		}
	}

		 	/**
	 * @exclude 重新启动采购任务
	 * @param
	 */
	function c_startApply() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$applyDao=new model_asset_purchase_apply_apply();
		if($applyDao->startApply_d($id) ){
			msgGo("启动成功");
		}else{
			msgGo("启动失败！！可能是服务器错误，请稍后再试");
		}
	}

		/**跳转到生产采购申请页面
	*author can
	*2011-7-5
	*/
	function c_toProduceByMaterial(){
		$parentProductID= isset($_GET['parentProductID'])? $_GET['parentProductID']:"";
		$neednum= isset($_GET['needNum'])? $_GET['needNum']:1;
		$materialDao=new model_stock_material_material();
		$materialRows=$materialDao->getMaterialList($parentProductID);
		if(is_array($materialRows)){
			$rows=$materialDao->treeCondList($materialRows,-1,$neednum);
			$leafRows=$materialDao->dealMaterialList($rows);
//			print_r($leafRows);
			//获取叶子节点
			$allLeafList=$materialDao->getMaterialLeafList($parentProductID);
			$purchList=$materialDao->dealPurchList($leafRows,$allLeafList);
			$this->assign ( 'listWithType', $this->service->showProductApply_s( $purchList) );
		}else{
			$this->assign ( 'listWithType', '');
		}


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
		$this->show->display('purchase_external_produce-material-add');
	}

	/*
	 *
	 * 补库/生产采购申请审批界面
	 *
	 * */
	function c_toProAppList(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$appTypeI=array(1=>'生产采购',2=>'补库',3=>'销售采购',4=>'服务采购',5=>'租赁采购',6=>'研发采购',7=>'补库(借试用)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'所 有 ','planNumb'=>'单据编号','productNumb'=>'物料编码','productName'=>'物料名称','updateName'=>'申请人');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppList() );
		$this->display('produce-prAppList');
	}

	/**
	 *生产采购及补库合并审批
	 *
	 */
	 function c_toAuditTab(){
	 	$this->view('audit-tab');
	 }

	/*
	 *
	 * 补库/生产采购申请审批界面
	 *
	 * */
	function c_toProAppCloseList(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$appTypeI=array(1=>'生产采购',2=>'补库',3=>'销售采购',4=>'服务采购',5=>'租赁采购',6=>'研发采购',7=>'补库(借试用)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'所 有 ','planNumb'=>'单据编号','productNumb'=>'物料编码','productName'=>'物料名称','updateName'=>'申请人');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppCloseList() );
		$this->display('produce-close-list');
	}
	/*
	 * 补库/生产采购通过审批界面
	 *
	 **/
	function c_toApproved(){
		$appType=($_POST['appType']?$_POST['appType']:$_GET['appType']);
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);

		$appTypeI=array(1=>'生产采购',2=>'补库',3=>'销售采购',4=>'服务采购',5=>'租赁采购',6=>'研发采购',7=>'补库(借试用)');
		$keyTypeI=array('planNumb,productNumb,productName,updateName'=>'所 有 ','planNumb'=>'单据编号','productNumb'=>'物料编码','productName'=>'物料名称','updateName'=>'申请人');
		foreach($appTypeI as $key=>$val){
			$appTypeStr.="<option value='$key'".($key==$appType?'selected':'')." >$val</option>";
		}
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'appType', $appTypeStr);
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->ApprovedList() );
		$this->pageShowAssign();
		$this->display('approved-list');
	}
	/*
	 *
	 * 补库/生产采购申请生成数据页面
	 *
	 * */
	function c_inProAppList(){
		// 补库/生产审批邮件通知
		$this->service->sendNotification($_POST['basic']);
		if($_POST['basic']&&$this->service->inProAppList($_POST['basic'])==1){
			echo "<script>alert('操作成功!');window.location='?model=purchase_plan_basic&action=toProAppList';</script>";
		}else{
			echo "<script>alert('操作失败!');window.location='?model=purchase_plan_basic&action=toProAppList';</script>";
		}
	}

		/*
	 *
	 * 补库/生产采购申请生成数据页面(重启)
	 *
	 * */
	function c_inProAppClose(){
		if($_POST['basic']&&$this->service->inProAppClose($_POST['basic'])==1){
			echo "<script>alert('操作成功!');window.location='?model=purchase_plan_basic&action=toProAppCloseList';</script>";
		}else{
			echo "<script>alert('操作失败!');window.location='?model=purchase_plan_basic&action=toProAppCloseList';</script>";
		}
	}

	/**
	 *
	 * 搜索申请采购信息页面
	 */
	function c_searchPage(){
		$this->view('search');
	}

	/**
	 *
	 * 搜索申请采购信息
	 */
	function c_searchList(){
		$arr = $this->service->search_d($_POST);
		$arr = $this->sconfig->md5Rows ( $arr );
		echo util_jsonUtil::encode ( $arr );
	}
}
?>
