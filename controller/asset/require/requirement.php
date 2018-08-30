<?php

/**
 * @author zengzx
 * @Date 2012年5月11日 11:41:37
 * @version 1.0
 * @description:资产需求申请控制层
 */
class controller_asset_require_requirement extends controller_base_action {

	function __construct() {
		$this->objName = "requirement";
		$this->objPath = "asset_require";
		parent :: __construct();
	}

	/*
	 * 跳转到资产需求申请列表
	 */
	function c_page() {
		//获取地址栏中需求申请状态参数
		$this->assign('isRecognize', $_GET['isRecognize']);
		$this->view('list');
	}


	/*
	 * 跳转到资产需求申请列表
	 */
	function c_myPage() {
		$this->view('mylist');
	}


	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$this->service->searchArr['applyManId']=$_SESSION['USER_ID'];

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/*
	 * 跳转到资产需求申请列表
	 */
	function c_signPage() {
		$this->assign('requireId', $_GET['requireId']);
		$this->view('sign');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_signJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$this->service->searchArr['applyManId']=$_SESSION['USER_ID'];

		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_signview');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
		 * 跳转到新增资产需求申请页面
		 */
	function c_toAdd() {
		$this->showDatadicts(array (
			'useType' => 'ZCYT'
		));
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d('DL');
		$this->assign('applyDate', day_date);
		$this->assign('signId', $_SESSION['USER_ID']);
		$this->assign('signName', $_SESSION['USERNAME']);
		$this->assign('signDeptId', $_SESSION['DEPT_ID']);
		$this->assign('signDeptName', $_SESSION['DEPT_NAME']);
		$this->assign('userCompanyCode', $_SESSION['Company']);
		$this->assign('userCompanyName', $branchArr['NameCN']);
		$this->assign('sendUserId', $_SESSION['USER_ID']);
		$this->assign('sendName', $_SESSION['USERNAME']);
		$this->view('add');
	}

	/**
		 * 跳转到编辑资产需求申请页面
		 */
	function c_toEdit() {
		$this->showDatadicts(array (
			'useType' => 'ZCYT'
		));
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		if (isset ($_GET['btn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}

		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('sendUserId', $_SESSION['USER_ID']);
		$this->assign('sendName', $_SESSION['USERNAME']);
		$this->view('edit');
	}


	/**
	 * 跳转到查看资产需求申请页面
	 */
	function c_toViewTab() {
		$this->assign( 'requireId',$_GET['requireId'] );
		$this->assign( 'requireCode',$_GET['requireCode'] );
		$this->view('view-tab');
	}


	/**
	 * 跳转到查看资产需求申请页面
     */
	function c_toView() {
		$this->permCheck(); //安全校验
		if(is_numeric($_GET['id']) && strlen($_GET['id']) < 32){//旧OA单据
			$obj = $this->service->get_d($_GET['id']);
			if($obj['requireType']=='1'){
				$obj['requireType']='长期';
			}else{
				$obj['requireType']='短期';
			}
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			$this->view('view');
		}else{//新OA单据
			// 接入aws
			// 从aws获取资产需求申请数据
			$result = util_curlUtil::getDataFromAWS ( 'asset', 'getRequireInfo', array (
					"requireId" => $_GET['id']
			) );
			$requireInfo = util_jsonUtil::decode ( $result ['data'], true );
			// 主表数据处理
			$data = $requireInfo ['data'] ['requireInfo'];
			$user = util_jsonUtil::userBuild ( $data ['theuser'] );
			$this->assign ( 'requireCode', $data ['num'] );
			$this->assign ( 'userName', $user ['userName'] );
			$this->assign ( 'userDeptName', $data ['udepartment'] );
			$this->assign ( 'userCompanyName', $data ['ucompany'] );
			$this->assign ( 'beginDate', $data ['borrowdate'] );
			$this->assign ( 'returnDate', $data ['planreturndate'] );
			$this->assign ( 'expectAmount', $data ['money'] );
			$this->assign ( 'applyDate', $data ['processingdate'] );
			$this->assign ( 'recognizeAmount', $data ['confirmmoney'] );
			$this->assign ( 'address', $data ['address'] );
			$this->assign ( 'remark', $data ['remark'] );
			// 从表数据处理
			// 资产需求申请明细
			if (! empty ( $requireInfo ['data'] ['details'] )) {
				$requireDetails = array ();
				foreach ( $requireInfo ['data'] ['details'] as $k => $v ) {
					$v ['detailId'] = $k;
					array_push ( $requireDetails, $v );
				}
			}
			$this->assign ( 'requireDetails', util_jsonUtil::encode ( $requireDetails ) );

			$this->view('view-new');
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$requireObj = $_POST[$this->objName];
//		echo "<pre>";
//		print_R($requireObj);
		if( $requireObj['addressFlag']!='1' ){
			$filename = WEB_TOR . "view/template/asset/require/addressInfo.txt";
			$handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
	    	//通过filesize获得文件大小，将整个文件一下子读到一个字符串中
	    	$contents = fread($handle, filesize ($filename));
	    	fclose($handle);
	    	$requireObj['address']=$contents;
		}
		if( $requireObj['expectAmount']=='' ){
			$requireObj['expectAmount']=0;
		}
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			$requireObj['isSubmit'] = 1;
			$msgStr = '提交成功';
		}else{
			$msgStr = '保存成功';
			$requireObj['isRecognize'] = 2;
		}
		$id = $this->service->add_d($requireObj, $isAddInfo);
//		if ($id) {
//			if ("audit" == $actType) {
//				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $id
//				. '&flowMoney=' . $requireObj['expectAmount'] .'&billDept='. $requireObj['userDeptId']);
//			} else {
//				msgRf('添加成功');
//			}
//		}
		if($id){
			msgRf($msgStr);
		}else{
			msgRf('提交失败');
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST[$this->objName];
		if( $object['expectAmount']=='' ){
			$object['expectAmount']=0;
		}
		$object['ExaStatus']='';
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			$object['isSubmit'] = 1;
			$object['isRecognize'] =0;
		}
		$flag = $this->service->edit_d($object, $isEditInfo);
//		if ($flag) {
//			if ("audit" == $actType) {
//				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $object['id']
//				. '&flowMoney=' . $object['expectAmount'].'&billDept='. $object['userDeptId']);
//			} else {
//				msgRf('编辑成功');
//			}
//		}
		if ($flag) {
			if ("submit" == $actType) {
				msgRf('提交成功');
			} else {
				msgRf('编辑成功');
			}
		}else{
			msgRf('编辑失败');
		}
	}

	/**
		 * 跳转到查看资产需求 金额确认 页面
		 */
	function c_toRecognize() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('amounts');
	}

	/**
	 * 跳转到查看资产需求申请页面 金额确认 页面--审批
	*/
	function c_toRecognizeView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('amounts-view');
	}
	/**
	 * 修改对象
	 */
	function c_recognize($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST[$this->objName];
//		echo "<pre>";
//		print_R($object);
		$obj = $this->service->get_d($object['id']);
		if( $object['recognizeAmount']=='' ){
			$object['recognizeAmount']=0;
		}
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$flag = $this->service->edit_d($object, $isEditInfo);

		if ($flag) {
			if ("audit" == $actType) {
				succ_show('controller/asset/require/ewf_index_require.php?actTo=ewfSelect&billId=' . $object['id']
				. '&flowMoney=' . $object['recognizeAmount'] .'&billDept='. $object['userDeptId']);
			} else {
				msgRf('提交成功');
			}
		}
	}

	/**
	 * 固定资产日常操作审批过后执行方法
	 */
	function c_dealAfterAudit(){
		//审批流回调方法
        $this->service->workflowCallBack($_GET['spid']);
       	echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}


	/**
	 * 改变单据状态
	 */
	function c_reback() {
		try {
			$object = $_POST[$this->objName];
			$this -> service -> backDetail_d($object);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}

	/**
	 * 改变单据状态
	 */
	function c_rebackMail() {
		try {
			$id = isset($_GET['id']) ? $_GET['id'] : false;
			$this->service->sendMailAtBack($id);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}
	/**
	 * 打回单据
	 */
	function c_toBackDetail() {
		$obj = $this -> service -> get_d($_GET['id']);
		$this->assignFunc($obj);
		$this -> view('backdetail');
	}
	/**
	 * 撤回
	 */
	function c_backDetail(){
		$rs = $this->service->backDetail_d($_POST[$this->objName]);
		if($rs){
			msg('打回成功');
		}
	}

	/**
	 * 打回单据
	 */
	function c_toBackDetailView() {
		$obj = $this -> service -> get_d($_GET['id']);
		$this->assignFunc($obj);
		$this -> view('backdetailView');
	}

	/**
	 * 撤回
	 */
	function c_rollback() {
		try {
			$id = isset($_GET['id']) ? $_GET['id'] : false;
			$this->service->rollback_d($id);
			echo 1;
		} catch ( Exception $e ) {
			throw $e;
			echo 0;
		}
	}

}
?>