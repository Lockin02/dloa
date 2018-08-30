<?php
/**
 * @author Show
 * @Date 2011年12月3日 星期六 10:29:00
 * @version 1.0
 * @description:外包合同控制层
 */
class controller_contract_outsourcing_outsourcing extends controller_base_action {

	function __construct() {
		$this->objName = "outsourcing";
		$this->objPath = "contract_outsourcing";
		parent::__construct ();
	}

	/*
	 * 跳转到外包合同
	 */
    function c_page() {
       $this->view('list');
    }

    /**
     * 工程项目外包汇总表
     */
    function c_pageForESM(){
		$this->view('listforesm');
    }

    /**
     * 研发项目外包项目汇总表
     */
    function c_pageForRD(){
		$this->view('listforrd');
    }

    /**
     * 重写toadd
     */
    function c_toAdd() {
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//外包性质
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//合同外包方式

		//付款申请信息渲染
		$this->showDatadicts ( array ('payFor' => 'FKLX' ) );//付款类型
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//结算方式

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//盖章类型

		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$otherdatas = new model_common_otherdatas();
		$this->assign('deptName',$otherdatas->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME'));

		$this->assign('isSysCode',ORDERCODE_INPUT);//是否手工输入合同号
		$this->view ( 'add' );
	}

	/**
	 * 独立新增
	 */
	function c_toAddDept(){
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//合同外包性质
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//合同外包方式

		//付款申请信息渲染
		$this->showDatadicts ( array ('payFor' => 'FKLX' ) );//付款类型
		$this->showDatadicts ( array ('payType' => 'CWFKFS' ) );//结算方式

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//盖章类型


		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('principalId',$_SESSION['USER_ID']);
		$this->assign('principalName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);

		$otherdatas = new model_common_otherdatas();
		$this->assign('deptName',$otherdatas->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME'));

		$this->assign('isSysCode',ORDERCODE_INPUT);//是否手工输入合同号
		$this->view ( 'adddept' );
	}

     /**
     * 在建项目列表跳转
     */
    function c_addForProject() {
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ));//合同外包性质
		$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ) );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) );//合同外包方式

//    	$this->permCheck (); //安全校验
		$obj = $this->service->getInfoProject_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), "HTWB03" );//外包性质

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', null , true , $stampArr);//盖章类型

		$this->assign('thisDate',day_date);
		$this->view ( 'addforproject' );
	}

	/*
	 * 跳转到Tab项目外包合同
	 */
    function c_listForProject() {
    	$this->assign('projectId',$_GET['projectId']);
       	$this->view('listforproject');
    }

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ($object);
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] ) ;
				}else{
					succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id. '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgRf('保存成功');
			}
		}else{
			msgRf('保存失败');
		}
	}

	/**
	 * 独立新增对象操作
	 */
	function c_addDept() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object );
		if($id){
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] );
				}else{
					succ_show('controller/contract/outsourcing/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgGo ( '保存成功！', '?model=contract_outsourcing_outsourcing&action=toAddDept' );
			}
		}else{
			msgGo ( '保存失败！', '?model=contract_outsourcing_outsourcing&action=toAddDept' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		$id = $this->service->editInfo_d ( $object);
		if ($id) {
			if($_GET['act']){
				if($object['isNeedPayapply'] == 1){
					succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney'].'&flowDept='.$object['payapply']['feeDeptId'] );
				}else{
					succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' .$object['orderMoney']);
				}
			}else{
				msgRf('保存成功');
			}
		}else{
			msgRf('保存失败');
		}
	}

	/**
	 * 跳转审批工作流的查看页面
	 */
	 function c_viewAccraditation(){
	 	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		//是否
		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		$this->view ( 'viewAccraditation' );
	 }

	 /**
	  * 查看页面 - 包含付款申请信息
	  */
	 function c_viewAlong(){
	 	$this->permCheck (); //安全校验

		//提交审批后查看单据时隐藏关闭按钮
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',1);
		}else{
			$this->assign('hideBtn',0);
		}

		$obj = $this->service->getInfo_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

		//是否
		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;
		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;

		//签收状态
		$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

		$this->assign ( 'payFor', $this->getDataNameByCode ( $obj ['payFor'] ) );
		$this->assign ( 'payType', $this->getDataNameByCode ( $obj ['payType'] ) );

		$this->view ( 'viewAlong' );
	 }

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$obj = $this->service->get_d ( $_GET ['id'] );
			$this->assignFunc($obj);

			//提交审批后查看单据时隐藏关闭按钮
			if(isset($_GET['viewBtn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}
			//附件添加{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

			//是否
			$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
			$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;

			//签收状态
			$this->assign('signedStatusCN',$this->service->rtIsSign_d($obj ['signedStatus'])) ;

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
			$this->view ( 'view' );
		} else {

			$obj = $this->service->getInfo_d ( $_GET ['id'] );
			$this->assignFunc($obj);

			//附件添加{file}
			$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
			$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
			$this->showDatadicts ( array ('outPayType' => 'HTFKFS' ), $obj ['outPayType'] );//合同付款方式
			$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//合同外包方式

			//设置盖章类型
//			$stampConfigDao = new model_system_stamp_stampconfig();
//			$stampArr = $stampConfigDao->getStampType_d();
//			$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//盖章类型

			//付款申请信息渲染
			$this->showDatadicts ( array ('payFor' => 'FKLX' ),$obj['payFor']);//付款类型
			$this->showDatadicts ( array ('payType' => 'CWFKFS' ),$obj['payType']);//结算方式

			$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
			$this->view ( 'edit' );
		}
	}

	/**
	 * 合同tab页
	 */
	function c_viewTab(){
		$this->permCheck (); //安全校验
		$this->assign('id',$_GET['id']);
		$this->display('viewtab');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->page_d ('select_info');

		if(is_array($rows)){
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );

			//合计加入
			$rows = $this->service->pageCount_d($rows);

			$objArr = $service->listBySqlId('count_list');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['createDate'] = '合计';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转申请盖章页面
	 */
	function c_toStamp(){
	 	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], true,$this->service->tbl_name )) ;
		$this->assign('applyDate',day_date);

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType', $obj ['stampType'] , true , $stampArr);//盖章类型

		//当前盖章申请人
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisUserName',$_SESSION['USERNAME']);

		$this->view ( 'stamp' );
	}

	/**
	 * 新增盖章信息操作
	 */
	function c_stamp(){
		$rs = $this->service->stamp_d($_POST[$this->objName]);
		if ($rs) {
			msg ( "申请成功！" );
		}else{
			msg ( "申请失败！" );
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAudit(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAudit_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 外包合同立项付款申请
	 */
	function c_dealAfterAuditPayapply(){
       	$this->service->dealAfterAuditPayapply_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 跳转个人外包合同
	 */
	function c_myOutsourcing(){
	 	$this->view('mylist');
	}

	/**
     * 我的外包合同
     */
    function c_myOutsourcingListPageJson() {
    	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['principalIdAndCreateId'] = $_SESSION['USER_ID'];
		$rows = $service->page_d ('select_info');
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
	 * 跳转个人外包合同
	 */
	function c_myStatusList(){
	 	$status = isset($_GET['status']) ? $_GET['status'] : 0 ;
	 	$this->assign('status',$status);
	 	$this->view('mystatuslist');
	}

	/**
	 * 关闭合同
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => '3'))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 附件上传
	 */
	function c_toUploadFile(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * 修改信息 - 当前用来修改备注
	 */
	function c_toUpdateInfo(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->view('updateinfo');
	}

	/**
	 * 修改对象
	 */
	function c_updateInfo() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object);
		if ($id) {
			msg('保存成功');
		}else{
			msg('保存失败');
		}
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}


	/***************** S 导入导出系列 *********************/
	/**
	 * 合同导入
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 合同导入操作
	 */
	function c_excelIn(){
		if($_POST['actionType'] == 0){
			$resultArr = $this->service->addExecelData_d ();
		}

		$title = '合同导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/***************** E 导入导出系列 *********************/


	/******************* S 变更系列 *********************/
	function c_toChange(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//合同外包方式

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//盖章类型

		$this->view('change');
	}

	/**
	 * 变更操作
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		try {
			$id = $this->service->change_d ( $_POST [$this->objName] );

			succ_show("controller/contract/outsourcing/ewf_change.php?actTo=ewfSelect&billId=" . $id);

		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
       	$this->service->dealAfterAuditChange_d($objId,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 变更查看tab
	 */
	function c_changeTab(){
		$this->permCheck (); //安全校验
		$newId = $_GET ['id'];
		$this->assign('id',$newId);

		$rs = $this->service->find(array('id' => $newId ),null,'originalId');
		$this->assign('originalId',$rs['originalId']);

		$this->display('changetab');
	}

	/**
	 * 变更查看合同  - 查看原合同
	 */
	function c_changeView(){
		$this->permCheck (); //安全校验
		$id = $_GET ['id'];

		$obj = $this->service->get_d ( $id );

		$this->assignFunc($obj);

		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $id, false,$this->service->tbl_name )) ;

		$this->assign ( 'stampType', $this->getDataNameByCode ( $obj ['stampType'] ) );

		$this->assign('isStamp',$this->service->rtYesOrNo_d($obj ['isStamp'])) ;
		$this->assign('isNeedStamp',$this->service->rtYesOrNo_d($obj ['isNeedStamp'])) ;
		$this->assign('isNeedRestamp',$this->service->rtYesOrNo_d($obj ['isNeedRestamp'])) ;

		$this->assign('createDate',date('Y-m-d',strtotime($obj['createTime']))) ;
		$this->view ( 'changeview' );
	}
	/******************* E 变更系列 *********************/

	/******************* S 签收系列 *********************/
	/**
	 * 合同签收 - 列表tab页
	 */
	function c_signTab(){
		$this->display('signTab');
	}

	/**
	 * 合同签收 - 待签收合同列表
	 */
	function c_signingList(){
		$this->view('signinglist');
	}

	/**
	 * 合同签收 - 已签收合同列表
	 */
	function c_signedList(){
		$this->view('signedlist');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_toSign(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//合同外包方式

		//设置盖章类型
//		$stampConfigDao = new model_system_stamp_stampconfig();
//		$stampArr = $stampConfigDao->getStampType_d();
//		$this->showSelectOption ( 'stampType',null, true , $stampArr);//盖章类型

		$this->view('sign');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_sign(){
		$object = $_POST [$this->objName];
		$id = $this->service->sign_d ( $object);
		if ($id) {
			msgRf('签收成功');
		}else{
			msgRf('签收失败');
		}
	}

	/******************* E 签收系列 *********************/
}
?>