<?php
/**
 * @author Administrator
 * @Date 2012年10月16日 星期二 9:21:33
 * @version 1.0
 * @description:招聘计划控制层
 */
class controller_hr_recruitplan_plan extends controller_base_action {

	function __construct() {
		$this->objName = "plan";
		$this->objPath = "hr_recruitplan";
		parent::__construct ();
	}

	/**
	 * 跳转到招聘计划列表
	 */
	function c_page() {
		$this->assign('type', $_GET['type']); //根据type在页面显示内容
		$this->view('list');
	}

	/**
	 * 我的列表Json
	 */
	function c_MyPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->searchArr ['formManId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
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
	 * 重写pageJSOn方法
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
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
	 * 跳转到新增招聘计划页面
	 */
	function c_toAdd() {
		$area = new includes_class_global();

		$this->show->assign('area_select',$area->area_select());
		$this->assign( 'formManId',$_SESSION['USER_ID'] );
		$this->assign( 'formManName',$_SESSION['USERNAME'] );
		$this->assign( 'resumeToId',$_SESSION['USER_ID'] );
		$this->assign( 'resumeToName',$_SESSION['USERNAME'] );

		//获取部门ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('deptName' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
		$this->assign('deptId' , $_SESSION['DEPT_ID']);
		$sendTime = date("Y-m-d");
		$this->assign( 'formDate',$sendTime );
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//增员类型
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ));//建议补充方式
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ));//用工类型
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ));//婚姻
		$this->showDatadicts ( array ('education' => 'HRZYXL' ));//学历
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//职位类型
		$this->view ('add' ,true);
	}

	/**
	 *新增或者提交审批
	 */
	function c_add(){
		$this->checkSubmit(); //检查是否重复提交
		$obj=$_POST[$this->objName];
		$id=$this->service->add_d($obj);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if($id){
			if ("audit" == $actType) {//提交工作流
				succ_show ( 'controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$obj['deptId']);
			} else {
				msg('保存成功');
			}
		}else{
			msg('保存失败');
		}
	}

	/**
	 * 编辑保存
	 */
	function c_edit(){
		$this->checkSubmit(); //检查是否重复提交
		$re=$this->service->edit_d($_POST[$this->objName]);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$id=$_POST[$this->objName]['id'];
		if($re){
			if ("audit" == $actType) {//提交工作流
				succ_show ( 'controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_POST[$this->objName]['deptId']);
			} else {
				msg('保存成功');
			}
		} else
			msgGo('保存失败');
	}

	/**
	 * 跳转到编辑招聘计划表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '男') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == '女') {
			$this->assign ( 'womanCheck', 'checked' );
		} else{
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$obj['addTypeCode']);//增员类型
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ),$obj['addModeCode']);//建议补充方式
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ),$obj['employmentTypeCode']);//用工类型
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ),$obj['maritalStatus']);//婚姻
		$this->showDatadicts ( array ('education' => 'HRZYXL' ),$obj['education']);//学历
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$obj['postType']);//职位类型
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view ('edit' ,true);
	}
	/**
	 * 跳转到查看聘计划页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		} else  {
			$this->assign ( 'projectType', '' );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'view' );
	}

	/**
	 * 跳转到编辑招聘计划页面
	 */
	function c_toAuditEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '男') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == '女') {
			$this->assign ( 'womanCheck', 'checked' );
		} else{
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$obj['addTypeCode']);//增员类型
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ),$obj['addModeCode']);//建议补充方式
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ),$obj['employmentTypeCode']);//用工类型
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ),$obj['maritalStatus']);//婚姻
		$this->showDatadicts ( array ('education' => 'HRZYXL' ),$obj['education']);//学历
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$obj['postType']);//职位类型
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view ('audit-edit' ,true);
	}

	/**
	 * 审批后修改增员申请
	 */
	function c_auditEdit(){
		$this->checkSubmit(); //检查是否重复提交
		$oldRow=$this->service->get_d($_POST[$this->objName]['id']);
		if($oldRow['needNum']!=$_POST[$this->objName]['needNum']||strlen($oldRow['positionLevel'])!=strlen($_POST[$this->objName]['positionLevel'])){
			$_POST[$this->objName]['needNumEdit']=$oldRow['needNum'];
			$_POST[$this->objName]['positionLevelEdit']=$oldRow['positionLevel'];
			$id=$this->service->edit_d($_POST[$this->objName]);
			if($id){
				succ_show ( 'controller/hr/recruitplan/ewf_edit_index.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);
			}else{
				msgGo('修改失败',"?model=hr_recruitment_apply&action=mypage");
			}
		}else{
			$id=$this->service->edit_d($_POST[$this->objName]);
			if($id){
				msgGo('修改成功',"?model=hr_recruitment_apply&action=mypage");
			}else{
				msgGo('修改失败',"?model=hr_recruitment_apply&action=mypage");
			}
		}
	}

	/**
	 * 招聘管理中招聘计划列表
	 */
	function c_recruit() {
		$this->view('recruit-list');
	}

	/**
	 * 转向到excel导入页面
	 */
	function c_toImport(){
		$this->view('excelin');
	}

	/**
	 * excel导入操作
	 */
	function c_excelIn(){
		set_time_limit(0);
		$actionType='';
		if($_POST['actionType']=='1')
		$actionType='网优';
		$resultArr = $this->service->addExecelData_d ($actionType);
		$title = '增员申请信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 改变状态
	 */
	function c_tochangeState(){
		$this->checkSubmit(); //检查是否重复提交
		$id=$_GET['id'];
		$state=$_GET['state'];
		if($this->service->changeState($id,$state)){
			msg('变更成功');
		}else
		msg('变更失败');
	}

	/**
	 * 改变状态
	 * ajax
	 */
	function c_changeState(){
		$id=$_REQUEST['id'];
		$state=$_REQUEST['state'];
		if($this->service->changeState($id,$state)){
			echo 1;
		}else
		echo 0;
	}

	/**
	 * 分配负责人
	 */
	function c_toGive() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		}
		if ($obj ['positionLevel'] =="1") {
			$this->assign ( 'positionLevel', '初级' );
		} else if ($obj ['positionLevel'] == "2") {
			$this->assign ( 'positionLevel', '中级' );
		}else if ($obj ['positionLevel'] == "3") {
			$this->assign ( 'positionLevel', '高级' );
		}
		$datestr = date('Y-m-d');
		$this->assign("assignedManName",$_SESSION['USERNAME']);
		$this->assign("assignedManId",$_SESSION['USER_ID']);
		$this->assign("assignedDate",$datestr);
		$this->view ('give' ,true);
	}

}
?>