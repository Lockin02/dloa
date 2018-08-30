<?php

/**
 * @author Show
 * @Date 2012年5月31日 星期四 17:39:42
 * @version 1.0
 * @description:任职资格信息控制层
 */
class controller_hr_personnel_certifyapply extends controller_base_action {

	function __construct() {
		$this->objName = "certifyapply";
		$this->objPath = "hr_personnel";
		parent :: __construct();
	}

	/***************** 列表部分 **********************/

	/*
	 * 跳转到任职资格信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到任职资格信息列表--个人
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/**
	 * 个人列表
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['userAccount'] = $_SESSION['USER_ID'];
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
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

		/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//系统权限
		$sysLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')){
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
			$_POST['deptId'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();
		}
		//其余信息加载
		if(!empty($rows)){
			$rows = $this->sconfig->md5Rows ( $rows );
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/******************** 增删改查 ******************/

	/**
	 * 跳转到新增任职资格信息页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//申请级等

		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),null,true);//认证发展通道
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),null,true);//认证级别
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),null,true);//认证级等
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),null,true);//认证称谓

		$this->view('add',true);
	}

	/**
	 * 跳转到编辑任职资格信息页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('baseResultHidden',$obj['baseResult']);
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection'],true);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel'],true);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade'],true);//申请级等

		$this->assign('finalResultHidden',$obj['finalResult']);
		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),$obj['finalCareer'],true);//认证发展通道
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),$obj['finalLevel'],true);//认证级别
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),$obj['finalGrade'],true);//认证级等
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),$obj['finalTitle'],true);//认证称谓
		$this->view('edit',true);
	}

	/**
	 * 跳转到查看任职资格信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['baseScore']==0){
			$this->assign('baseScore' ,'');
		}
		if($obj['finalScore']==0){
			$this->assign('finalScore' ,'');
		}
		$this->assign('baseResult',$this->service->rtIsPass_d($obj['baseResult']));
		$this->assign('finalResult',$this->service->rtIsPass_d($obj['finalResult']));
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));

		//初始化认证评价表
		$cassessArr = $this->service->getAssess_d($obj['id']);
		$this->assign('cassessId' ,$cassessArr['id']);

		//初始化得分换算表
		$scoreArr = $this->service->getScore_d($cassessArr['id']);
		$this->assign('scoreId' ,$scoreArr['id']);

		$this->view('view');
	}

	/*********************** 正常流程部分 ***************************/

	/**
	 * 跳转到新增任职资格信息页面
	 */
	function c_toAddApply() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//申请级等
		$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),null,true);//申请认证方向

		$rs = $this->service->getPersonnelInfo_d($_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);
		$this->assign('thisYear',date("Y"));

		if($rs){
			$this->assignFunc($rs);

			$this->showDatadicts(array('nowDirection'=>'HRZYFZ'),$rs['nowDirection']);//申请发展通道
			$this->showDatadicts(array('nowLevel' => 'HRRZJB'),$rs['nowLevel']);//申请级别
			$this->showDatadicts(array('nowGrade' => 'HRRZZD'),$rs['nowGrade']);//申请级等
			$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),null,true);//申请认证方向

			$this->assign('deptId',$_SESSION['DEPT_ID']);
			$this->assign('deptName',$_SESSION['DEPT_NAME']);
		}else{
			echo "没有对应的档案信息，请告知HR把档案信息补充完整";
			die();
		}

		$this->view('addapply',true);
	}

	/**
	 * 新增对象操作
	 */
	function c_addApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj = $_POST [$this->objName];
		if ($_GET['act'] == "app") {//如果传了参数act=app,则状态为提交
			$obj['status']=1;
		}
		$id = $this->service->addApply_d ($obj , $isAddInfo );
		if ($id && $_GET['act'] == "app") {//如果传了参数act=app,则直接提交审批
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
			msgRf( $msg );
		} else
		if ($id) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
			msgRf( $msg );
		}
	}

	/**
	 * 跳转到新增任职资格信息页面
	 */
	function c_toEditApply() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection'],true);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel'],true);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade'],true);//申请级等
		$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),$obj['certifyDirection'],true);//认证方向

		$this->showDatadicts(array('nowDirection'=>'HRZYFZ'),$obj['nowDirection']);//申请发展通道
		$this->showDatadicts(array('nowLevel' => 'HRRZJB'),$obj['nowLevel']);//申请级别
		$this->showDatadicts(array('nowGrade' => 'HRRZZD'),$obj['nowGrade']);//申请级等
		$this->assign('thisYear',date("Y"));

		$this->view('editapply',true);
	}

	/**
	 * 跳转到打回任职资格信息页面
	 */
	function c_toBackApply() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->view('backapply',true);
	}

	/**
	 * 打回
	 */
	function c_backApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj =  $_POST [$this->objName];
		$res = $this->service->backApply_d ($obj, $isAddInfo);
		if ($res) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '打回成功！';
			msg( $msg );
		}
	}

	/**
	 * 编辑
	 */
	function c_editApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj =  $_POST [$this->objName];
		if ($_GET['act'] == "app") {//如果传了参数act=app,则状态为提交
			$obj['status']=1;
			$obj['ExaStatus'] = AUDITING;
		}
		$res = $this->service->editApply_d ($obj, $isAddInfo);
		if ($res && $_GET['act'] == "app") {//如果传了参数act=app,则直接提交审批
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
			msgRf( $msg );
		} else
		if ($res) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
			msgRf( $msg );
		}
	}

	/**
	 * 跳转到查看任职资格信息页面
	 */
	function c_toViewApply() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('actType' ,$_GET['actType']);
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));
		$this->view('viewapply');
	}

		/**
	 * 跳转到查看任职资格信息页面（员工查看）
	 */
	function c_toViewApplyPerson() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('actType' ,$_GET['actType']);
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));
		$this->view('person-view');
	}

	/****************** 业务逻辑 ***********************/

	/**
	 * 审批完成后回调方法
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * 认证申请提交
	 */
	function c_submitApply() {
			$flag=$this->service->submitApply_d ( $_POST ['id']);
			if($flag){
				echo 1;
			}else{
				echo 0;
			}
	}

	/**
	 * 员工认证申请审批通过
	 **/
	function c_aduitPass(){
	 	$idsArr=$_POST ['applyIds'];
	 	$flag=$this->service->aduitPass_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}
	/**
	 * 导入excel更新
	 */
	function c_toExcelUpdate(){
		$this->display('excel-update');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$val=$_POST['actionType'];
		if($val['value']==1){
			$resultArr = $this->service->addExecelData_d ();
		}else{
			$resultArr = $this->service->updataExecelData_d ();
		}

		$title = '任职资格信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导入excel
	 */
	function c_excelUpdate(){
		$resultArr = $this->service->updataCertifyapplyData_d ();
		$title = '任职资格信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/
	//add chenrf 20130524
	/**
	 *
	 * 转向excel导出页面
	 */
	function c_toExcelout(){
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//申请级等

		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),null,true);//认证发展通道
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),null,true);//认证级别
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),null,true);//认证级等
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),null,true);//认证称谓

		$this->view('excelout');
	}
	/**
	 *
	 * excel导出
	 */
	function c_excelout(){
		set_time_limit(0);
		$param=array_filter($_POST[$this->objName]);
		$baseResult=$_POST[$this->objName]['baseResult'];
		$finalResult=$_POST[$this->objName]['finalResult'];
		if($baseResult!=''&&$baseResult==0)
			$param['baseResult']='0';
		if($finalResult!=''&&$finalResult==0)
			$param['finalResult']='0';
		$this->service->searchArr=$param;
		$row = $this->service->list_d('select_excelOut');
		$this->service->excelOut($row);
	}
}
?>