<?php

/**
 * @author Show
 * @Date 2012年6月1日 星期五 14:51:13
 * @version 1.0
 * @description:招聘管理-面试评估表控制层
 */
class controller_hr_recruitment_interview extends controller_base_action {

	function __construct() {
		$this->objName = "interview";
		$this->objPath = "hr_recruitment";
		parent :: __construct();
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 面试评估表--简历查看tab页
	 */
	function c_viewList() {
		$this->assign('resumeId',$_GET['resumeId']);
		$this->view('viewList');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_applyPage() {
		$this->assign("applyid",$_GET['applyid']);
		$this->assign("interviewtype",$_GET['type']);
		$this->view('applylist');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toDeptPage() {
		$this->assign("userid",$_SESSION['DEPT_ID']);
		$this->view('deptlist');
	}

	/**
	 * 跳转到招聘管理-面试评估表（服务经理）列表
	 */
	function c_toManagerPage() {
		$this->assign("userid",$_SESSION['DEPT_ID']);
		$this->view('manager-list');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toLastPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('lastlist');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_recuitPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('recuitlist');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toDeptView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('deptview');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toRecuitView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('recuitview');
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toAdd() {
		$this->permCheck(); //安全校验
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//职位类型
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ) ); //工资级别
		$this->view('add' ,true);
	}

	/**
	 * 面试评估表(简历)
	 */
	function c_toAddByResume() {
		$this->permCheck(); //安全校验
		$resumeDao = new model_hr_recruitment_resume();
		$resumeRow = $resumeDao->get_d($_GET['resumeId']);
		foreach ($resumeRow as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$resumeRow['post']);
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName" ,$select);
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->showDatadicts(array('hrSourceType1Name' => 'HRBCFS'));
		$this->showDatadicts(array('useHireType' => 'HRLYXX') ,'HRLYXX-01');
		$this->showDatadicts(array('postType' => 'YPZW') ,$resumeRow['post']);//职位类型
		$this->showDatadicts(array('controlPostCode' => 'HRGLGW')); // 管理岗位

		$this->assign("useManagerId" ,$_SESSION['USER_ID']);
		$this->assign("useManager" ,$_SESSION['USERNAME']);
		$this->assign("useSignDate" ,date('Y-m-d'));
		$this->assign("hrSourceType2Name" ,$resumeRow['sourceB']);
		$this->assign("sexy" ,$resumeRow['sex']);
		$this->showDatadicts(array('hrSourceType1' => 'JLLY' ) ,$resumeRow['sourceA']);

		$this->assign("managerId",$_SESSION['USER_ID']);
		$this->assign("manager",$_SESSION['USERNAME']);
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX'));//增员类型
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB')); //工资级别
		$this->view('resume-add' ,true);
	}

	/**
	 * 面试评估表(职位申请)
	 */
	function c_toAddByEmployment() {
		$this->permCheck(); //安全校验
		$employmentDao = new model_hr_recruitment_employment();
		$employmentRow = $employmentDao->get_d($_GET['employmentId']);
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//职位类型
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ) ); //工资级别
		foreach ($employmentRow as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('employment-add' ,true);
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toDeptEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//获取评论
		$str1 = $this->service->getInterComment(1,$obj['invitationId']);
		$str2 = $this->service->getUseComment(1,$obj['invitationId']);

		$this->assign("useWriteEva",$str2);
		$this->assign("useInterviewEva",$str1);
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();

		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$this->assign("useManagerId",$_SESSION['USER_ID']);
		$this->assign("useManager",$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType']);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //工资级别
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->view('deptedit' ,true);
	}

	/**
	 * 跳转到招聘管理-面试评估表列表
	 */
	function c_toDeptAdd() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType'] ,true);
		$this->showDatadicts ( array ('hrHireType' => 'HRLYXX' ), $obj ['hrHireType'] ,true);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //工资级别
		$this->view('deptadd' ,true);
	}

	function c_toLastEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if(!empty($obj['hrSourceType1'])){
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$obj['hrSourceType1']);
		}else if($obj['resumeId']>0){
			$resumeDao=new model_hr_recruitment_resume();
			$resumeRow=$resumeDao->get_d($obj['resumeId']);
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$resumeRow['sourceA']);
			$this->assign("hrSourceType2Name",$resumeRow['sourceB']);
		}else{
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		}
		//获取评论
		if($obj['invitationId'] > 0){
			$str1 = $this->service->getInterComment(1,$obj['invitationId']);
			$str2 = $this->service->getUseComment(1,$obj['invitationId']);
			$str3 = $this->service->getInterComment(2,$obj['invitationId']);
		}else{
			$str1 = $this->service->getInterviewComment('1',$_GET['id']);
			$str2 = $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3 = $this->service->getInterviewComment('2',$_GET['id']);
		}
		if($obj['managerId']==''){
			$this->assign("managerId",$_SESSION['USER_ID']);
			$this->assign("manager",$_SESSION['USERNAME']);
		}else{
			$this->assign("managerId",$obj['managerId']);
			$this->assign("manager",$obj['manager']);
		}
		$this->assign("SignDate",date('Y-m-d'));
		$this->assign("useWriteEva",$str2);
		$this->assign("useInterviewEva",$str1);
		$this->assign("hrInterviewList",$str3);

		if($obj['useInterviewResult'] == '1') {
			$this->assign("useInterviewResult", "立即录用");
		} else {
			$this->assign("useInterviewResult", "储备人才");
		}
		$this->showDatadicts(array('hrHireType' => 'HRPYLX'));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX' ), $obj['addTypeCode']);//增员类型
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //工资级别
		$this->view('lastedit' ,true);
	}

	/**
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead(){
		$this->view('listforread');
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
			$_POST['deptIds'] = $sysLimit;
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
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 由面试通知生成面试评估
	 */
	function c_toAddByNotice() {
		$invitationId=isset($_GET['invitationId'])?$_GET['invitationId']:"";
		$invitationDao=new model_hr_recruitment_invitation();
		$invitationRow=$invitationDao->get_d($invitationId);
		foreach ($invitationRow as $key => $val) {
			$this->assign($key, $val);
		}
		if ($invitationRow ['positionLevel'] =="1") {
			$this->assign ( 'positionLevelName', '初级' );
		} else if ($invitationRow ['positionLevel'] == "2") {
			$this->assign ( 'positionLevelName', '中级' );
		}else if ($invitationRow ['positionLevel'] == "3") {
			$this->assign ( 'positionLevelName', '高级' );
		}else {
			$this->assign ( 'positionLevelName',$invitationRow ['positionLevel']);
		}
		$this->view('invitation-add' ,true);
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
			}
		}
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
	function c_pageForManager() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$provinceDao=new model_system_procity_province();
		$provinceStr=$provinceDao->getProvinceByUser_d($_SESSION ['USER_ID']);
		$service->searchArr ['provinceArr'] = $provinceStr;

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$deptDao=new model_deptuser_dept_dept();
		$newRow=array();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
				$deptRow=$deptDao->getSuperiorDeptById_d($rows[$key]['deptId']);
				$rows[$key]['parentDeptId']=$deptRow['deptId'];
				if($deptRow['deptId']=='35'){
					array_push($newRow,$rows[$key]);
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRow;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRow ? count ( $newRow ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 修改对象
	 */
	function c_addByNotice() {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];
		if ($this->service->add_d ( $object )) {
			msg( '提交成功！' );
		}else{
			msg( '提交失败！' );
		}
	}

	/**
	 * 跳转到编辑招聘管理-面试评估表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType'] ,true);
		$this->showDatadicts ( array ('hrHireType' => 'HRLYXX' ), $obj ['hrHireType'] ,true);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //工资级别
		$this->view('edit' ,true);
	}

	/**
	 * 跳转到编辑招聘管理-面试评估表页面
	 */
	function c_toManagerEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);

		foreach ($obj as $key => $val) {
			if ($key == 'isCompanyStandard') {
				if ($val == '0') {
					$this->assign("check" ,"checked");
				} else {
					$this->assign("check1" ,"checked");
				}
			} else {
				$this->assign($key, $val);
			}
		}
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']); // 应聘职位
		$this->showDatadicts(array('controlPostCode' => 'HRGLGW') ,$obj['controlPostCode']); // 管理岗位
		$branchDao = new model_deptuser_branch_branch();
		$area = new includes_class_global();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName" ,$select);
		$this->assign('area_select' ,$area->area_select());
		$this->showDatadicts(array('useHireType' => 'HRLYXX') ,$obj['useHireType']); //录用形式
		if(!empty($obj['hrSourceType1'])) {
			$this->showDatadicts (array('hrSourceType1' => 'JLLY') ,$obj['hrSourceType1']);
		} else if ($obj['resumeId'] > 0) {
			$resumeDao = new model_hr_recruitment_resume();
			$resumeRow = $resumeDao->get_d($obj['resumeId']);
			$this->showDatadicts(array('hrSourceType1' => 'JLLY') ,$resumeRow['sourceA']);
			$this->assign("hrSourceType2Name" ,$resumeRow['sourceB']);
		} else {
			$this->showDatadicts(array('hrSourceType1' => 'JLLY'));
		}

		if($obj['managerId'] == '') {
			$this->assign("managerId" ,$_SESSION['USER_ID']);
			$this->assign("manager" ,$_SESSION['USERNAME']);
		} else {
			$this->assign("managerId" ,$obj['managerId']);
			$this->assign("manager" ,$obj['manager']);
		}
		$this->assign("invitationId" ,$obj['invitationId']);
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//增员类型
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB') ,$obj ['wageLevelCode'] ); //工资级别

		if ($_GET['changeHire']) { //判断是否要转为立即录用
			$this->assign("changeHire" ,1);
		}

		if ($_GET['isCopy']) { //判断是否为复制新评估
			$this->assign("isCopy" ,1);
		}

		if ($_GET['audit']) { //判断是否为审批后的修改
			$this->assign("audit" ,1);
		}

		$this->view('manager-edit' ,true);
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msgRf ( '编辑成功！' );
		}else{
			msgRf ( '编辑失败！' );
		}
	}

	/**
	 * 修改对象
	 */
	function c_deptedit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$editType=isset($_GET['editType'])?$_GET['editType']:"";
		$object = $_POST [$this->objName];
		$branchDao = new model_deptuser_branch_branch();
		$branchCN = $branchDao->get_d($object['sysCompanyId']);
		$object['sysCompanyName'] = $branchCN['NameCN'];
		if($editType!='edit'){
			$object['deptState'] = 1;
		}
		$query = $this->service->db->query("select Name from area where ID = ".$object['useAreaId']);
		$get = $this->service->db->fetch_array($query);
		$object['useAreaName'] = $get['Name'];
		if ($this->service->edit_d ( $object )) {
			if($editType!='edit'){
				msgRf ( '确认成功！' );
			}else{
				msgRf ( '保存成功！' );
			}
		}else{
			if($editType!='edit'){
				msgRf ( '确认失败！' );
			}else{
				msgRf ( '保存失败！' );
			}
		}
	}

	/**
	 * 修改对象
	 */
	function c_lastedit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$editType=isset($_GET['editType'])?$_GET['editType']:"";
		$object = $_POST [$this->objName];
		if($editType!='edit'){
			$object['hrState'] = 1;
		}
		$object['state'] = $this->service->statusDao->statusEtoK("noview");
		$datadict = new model_system_datadict_datadict();
		$object['hrHireTypeName'] = $datadict->getDataNameByCode($object['hrHireType']);
		$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1Name']);
		$object['addType'] = $datadict->getDataNameByCode($object['addTypeCode']);
		$object['wageLevelName'] = $datadict->getDataNameByCode($object['wageLevelCode']);

		if ($this->service->edit_d ( $object )) {
			if($editType!='edit'){
				msgRf ( '确认成功！' );
			}else{
				msgRf ( '保存成功！' );
			}
		}else{
			if($editType!='edit'){
				msgRf ( '确认失败！' );
			}else{
				msgRf ( '保存失败！' );
			}
		}
	}

	//编辑面试评估
	function c_managerEdit($isEditInfo = false) {
		// $this->checkSubmit(); //检查是否重复提交
		$object = $_POST[$this->objName];
		$service = $this->service;
		//如果是操作转为立即录用的编辑
		if ($_POST['changeHire'] == 1) {
			$oldObj = $service->get_d($object['id']);
		}

		if(isset($object['formCode'])) {
			unset($object['formCode']);
		}
		$branchDao = new model_deptuser_branch_branch();
		$branchCN = $branchDao->get_d($object['sysCompanyId']);
		$object['sysCompanyName'] = $branchCN['NameCN'];
		$object['deptState'] = 1;
		$query = $service->db->query("select Name from area where ID = ".$object['useAreaId']);
		$get = $service->db->fetch_array($query);
		$object['useAreaName'] = $get['Name'];
		$object['hrState'] = 1;
		$datadict = new model_system_datadict_datadict();
		$object['hrHireTypeName'] = $datadict->getDataNameByCode($object['hrHireType']);
		$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1Name']);
		$object['wageLevelName'] = $datadict->getDataNameByCode($object['wageLevelCode']);
		if ($service->managerEdit_d($object)) {
			$newObj = $service->get_d($object['id']);
			if ($this->c_isNeedExa($newObj ,$oldObj)) { //判断是否需要重新提交审批
				$newObj['parentDeptId'] = $service->get_table_fields('department' ,'DEPT_ID='.$newObj['deptId'] ,'pdeptid');

				//部门已确认HR未确认（试点专区、服务执行区）
				if ($newObj['hrState'] == 0 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY') {
					succ_show('controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				//部门已确认并审批HR未确认（试点专区、服务执行区）
				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY' && $newObj['state'] == '') {
					succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						|| ($newObj['parentDeptId'] == '130' && $newObj['postType'] != 'YPZW-WY')) {
					if(($newObj['parentDeptId'] == '130'|| $newObj['parentDeptId'] == '131')
							&& $newObj['postType'] != 'YPZW-WY') {
						succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					} else {
						succ_show('controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					}
				}

			} else {
				msgRf ( '保存成功！' );
			}
		} else {
			msgRf ( '保存失败！' );
		}
	}

	/**
	 * 判断是否需要重新提交审批
	 */
	function c_isNeedExa($newObj ,$oldObj) {
		if ($newObj['ExaStatus'] != '完成') { //还未完成审批的则不需重新提交审批
			return false;
		}
		$compare = array(
			'useTrialWage' //试用期基本工资
			,'useFormalWage' //转正基本工资
			,'phoneSubsidy' //'电话费补助（试用期）
			,'phoneSubsidyFormal' //'电话费补助（转正）
			,'levelSubsidy' //'技术级别补助（试用期）
			,'levelSubsidyFormal' //'技术级别补助（转正）
			,'tripSubsidy' //'出差补助上限值（试用期）
			,'tripSubsidyFormal' //'出差补助上限值（转正）
			,'workBonus' //'工作奖金（试用期）
			,'workBonusFormal' //'工作奖金（转正）
			,'computerSubsidy' //'电脑补助（试用期）
			,'computerSubsidyFormal' //'电脑补助（转正）
			,'otherSubsidy' //'其他补贴（试用期）
			,'otherSubsidyFormal' //'其他补贴（转正）
			,'areaSubsidy' //'区域补助（试用期）
			,'areaSubsidyFormal' //'区域补助（转正）
			,'bonusLimit' //'奖金上限值（试用期）
			,'bonusLimitFormal' //'奖金上限值（转正）
			,'manageSubsidy' //'管理津贴（试用期）
			,'manageSubsidyFormal' //'管理津贴（转正）
			,'accommodSubsidy' //'临时住宿补助（试用期）
			,'accommodSubsidyFormal' //'临时住宿补助（转正）
			,'internshipSalaryType' //'实习工资类型
			,'internshipSalary' //'实习工资
		);
		foreach ($compare as $key => $val) {
			if ($newObj[$val] != $oldObj[$val]) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 消除字重复评论
	 */
	function eliminate($obj){
		$data = explode("<br>",$obj);	//转换成数组
		$data = array_filter($data);
		$data = array_unique($data); 	//消除重复
		foreach($data as $key=>$val){
			$newData .= $val."<br>";
		}
		return $newData;
	}

	/**
	 * 跳转到查看招聘管理-面试评估表页面
	 */
	function c_toView() {
		if($_GET['id'] != '*') {
			$investigationDao = new model_hr_recruitment_investigation();
			$investigationArr = $investigationDao->find(array("parentId"=>$_GET['id']));
			if($investigationArr){
				$this->assign("investigationId",$investigationArr['id']);
				$this->assign("investigationFormCode",$investigationArr['formCode']);
			}else{
				$this->assign("investigationId" ,0);
			}
			$this->permCheck(); //安全校验
			$obj = $this->service->get_d($_GET['id']);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			if($obj['postTypeName'] == '网优'){
				$level = new model_hr_basicinfo_level();
				$WYlevel = $level->get_d($obj['positionLevel']);
				$this->assign('positionLevel', $WYlevel['personLevel']);
			}else{
				switch ($obj['positionLevel']){
					case '1' :$this->assign('positionLevel', '初级');break;
					case '2' :$this->assign('positionLevel', '中级');break;
					case '3' :$this->assign('positionLevel', '高级');break;
				}
			}
			//获取评论
			if($obj['invitationId'] > 0) {
				$str1 = $this->service->getInterComment(1,$obj['invitationId']);
				$str2 = $this->service->getUseComment(1,$obj['invitationId']);
				$str3 = $this->service->getInterComment(2,$obj['invitationId']);
			}
			$str1.= $this->service->getInterviewComment('1',$_GET['id']);
			$str2.= $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3.= $this->service->getInterviewComment('2',$_GET['id']);

			//消除重复评论
			$str1=$this->eliminate($str1);
			$str2=$this->eliminate($str2);
			$str3=$this->eliminate($str3);

			$this->assign("useWriteEva" ,rtrim($str2,'<br>'));
			$this->assign("useInterviewEva" ,rtrim($str1,'<br>'));
			$this->assign("hrInterviewList" ,rtrim($str3,'<br>'));

			if($obj['useInterviewResult'] == '1') {
				$this->assign("useInterviewResult", "立即录用");
			} else {
				$this->assign("useInterviewResult", "储备人才");
			}

			if($obj['isCompanyStandard'] == '1') {
				$this->assign("isCompanyStandard", "是");
			}else{
				$this->assign("isCompanyStandard", "否");
			}
			$this->view('view');
		}else{
			msg("没有面试评估信息！");
		}
	}

	/*
	 * 跳转到背景调查页面
	 */
	function c_toInvestigation(){
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view('investigation');
	}

	/**
	 * 修改录用结果状态
	 */
	function c_change() {
		$this -> permCheck();
		//安全校验
		if($this->service->change_d())
			echo 1;
		else
			echo 0;
	}

	/**
	 * 跳转到查看招聘管理-面试评估表页面
	 */
	function c_toRead() {
		$investigationDao = new model_hr_recruitment_investigation();
		$investigationArr = $investigationDao->find(array("parentId"=>$_GET['id']));
		if($investigationArr){
			$this->assign("investigationId" ,$investigationArr['id']);
			$this->assign("investigationFormCode" ,$investigationArr['formCode']);
		}else{
			$this->assign("investigationId" ,0);
		}
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['postTypeName'] == '网优'){
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevel', $WYlevel['personLevel']);
		}else{
			switch ($obj['positionLevel']) {
				case '1' :$this->assign('positionLevel', '初级');break;
				case '2' :$this->assign('positionLevel', '中级');break;
				case '3' :$this->assign('positionLevel', '高级');break;
			}
		}
		//获取评论
		if($obj['invitationId'] > 0) {
			$str1 = $this->service->getInterComment(1,$obj['invitationId']);
			$str2 = $this->service->getUseComment(1,$obj['invitationId']);
			$str3 = $this->service->getInterComment(2,$obj['invitationId']);
		}else{
			$str1 = $this->service->getInterviewComment('1',$_GET['id']);
			$str2 = $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3 = $this->service->getInterviewComment('2',$_GET['id']);
		}
		$this->assign("useWriteEva",rtrim($str2,'<br>'));
		$this->assign("useInterviewEva",rtrim($str1,'<br>'));
		$this->assign("hrInterviewList",rtrim($str3,'<br>'));
		if($obj['useInterviewResult'] == '1') {
			$this->assign("useInterviewResult" ,"立即录用");
		} else {
			$this->assign("useInterviewResult" ,"储备人才");
		}

		if($obj['isCompanyStandard'] == '1') {
			$this->assign("isCompanyStandard", "是");
		}else{
			$this->assign("isCompanyStandard", "否");
		}
		//部门统计人数信息
		$html = $this->service->getDeptPer_d($_GET['id']);
		$this->assign('points' ,$html);
		$this->view('read');
	}

	/**
	 * 人员档案查看面试评估信息
	 */
	function c_toViewForPerson(){
		$obj = $this->service->find(array('userAccount' => $_GET['userAccount']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin' ,true);
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$this->checkSubmit(); //验证是否重复提交
		$resultArr = $this->service->addExecelData_d ();

		$title = '面试评估表导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/

/*************************kindeditor*************************************************************************/

	/**
	 * 面试通知
	 */
	function c_InterviewNotice(){
		//获取简历信息
		$dao=new model_hr_recruitment_resume();
		$resumeinfo = $dao->get_d($_GET['id']);
		$this->assign("resumeId",$_GET['id']);
		$this->assign("toMail",$resumeinfo['email']);
		$this->assign("toMailName",$resumeinfo['applicantName']);
		$this->assign("user",$_SESSION['USERNAME']);
		$this->assign("userId",$_SESSION['USER_ID']);
		//自定义-职位申请表
		$aa = WEB_TOR;
		$aa.="view\\template\hr\\recruitment\\resume-add.htm";
		$this->assign("jobUrl",$aa);
		$this->view("interviewNotice");
	}

	/**
	 *  面试通知发送邮件
	 */
	function c_interviewMail(){
		$info = $_POST ['interMail'];
		$this->service->thisMail_d($info);
	}

	/*
	 * duanlh2013-04-01
	 * 添加面试评估
	 */
	function c_toInterviewAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->showDatadicts(array('hrSourceType1Name' => 'HRBCFS'));
		$this->showDatadicts(array('useHireType'       => 'HRLYXX') ,'HRLYXX-01');
		$this->showDatadicts(array('postType'          => 'YPZW'));   // 职位类型
		$this->showDatadicts(array('controlPostCode'   => 'HRGLGW')); // 管理岗位
		$this->assign("useManagerId" ,$_SESSION['USER_ID']);
		$this->assign("useManager" ,$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->assign("managerId" ,$_SESSION['USER_ID']);
		$this->assign("manager" ,$_SESSION['USERNAME']);
		$this->assign("SignDate" ,date('Y-m-d'));
		$this->showDatadicts(array('addTypeCode'   => 'HRZYLX')); //增员类型
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB')); //工资级别
		$this->view("interview-add" ,true);
	}

	function c_interviewAdd() {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];

		if ($_POST['isCopy'] == 1) { //如果为复制新评估，把主表和从表的id都删除掉
			unset($object['id']);
			if(is_array($object['items'])){
				foreach($object['items'] as $key => $value) {
					unset($object['items'][$key]['id']);
					if ($value['isDelTag'] == 1) { //把编辑过程中的假删除也删除掉
						unset($object['items'][$key]);
					}
				}
			}
			if(is_array($object['humanResources'])){
				foreach($object['humanResources'] as $key => $value){
					unset($object['humanResources'][$key]['id']);
					if ($value['isDelTag'] == 1) { //把编辑过程中的假删除也删除掉
						unset($object['humanResources'][$key]);
					}
				}
			}
		}

		if ($this->service->addInterview_d( $object )) {
			if ($_GET['staging'] == 'true') {
				msg( '保存成功！' );
			} else {
				msg( '提交成功！' );
			}
		} else {
			if ($_GET['staging'] == 'true') {
				msg( '保存失败！' );
			} else {
				msg( '提交失败！' );
			}
		}
	}

	/**判断是否已提交职位申请
	 *author can
	 *2010-12-29
	 */
	function c_isAdded() {
		$resumeId=isset($_POST['resumeId'])?$_POST['resumeId']:'';
		$id =$this->service->get_table_fields($this->service->tbl_name, "resumeId='".$resumeId."'", 'id');
		//如果删除成功输出1，否则输出0
		if($id > 0) {
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * 跳转excel导出页面
	 * add chenrf
	 * 20130517
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * excel导出
	 */
	function c_excelOut(){
		set_time_limit(0);
		$param = array_filter($_POST[$this->objName]);
		$useInterviewResult = $_POST[$this->objName]['useInterviewResult'];
		if($useInterviewResult != '' && $useInterviewResult == 0) {
			$param['useInterviewResult'] = '0';
		}
		$this->service->searchArr = $param;
		$row = $this->service->list_d('select_excelOut');
		$this->service->excelOut($row);
	}

	/**
	 * 跳转高级搜索页面
	 */
	function c_toSearch(){
		$this->permCheck(); //安全校验
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->view('search');
	}

	/**
	 * 审批处理
	 */
	function c_dealAfterAuditPass() {
	 	if (!empty($_GET['spid'])) {
	 		$service = $this->service;
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
			$service->dealAfterAuditIng_d($folowInfo['objId'] ,$folowInfo['taskId']); //每一步的审批操作
			if($folowInfo['examines'] == "ok") {  //审批通过
				$service->dealAfterAuditPass_d($folowInfo['objId']);
				$count = $service->countWorkFlow($folowInfo['objId']);
				if($count > 1){//获取当前单据的审批次数,大于1表示为审批后再次修改走审批,即新增走审批后不发送此邮件
					//发送相关邮件通知人事组
					$service->sendMailByEdit_d($folowInfo['objId']);
				}
			} else if ($folowInfo['examines'] == "no") { //审批不通过
				$service->dealAfterAuditFail_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 编辑审批完成的面试评估
	 */
	function c_editAuditFinish() {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST[$this->objName];
		$service = $this->service;
		$oldObj = $service->get_d($object['id']);
		$affectedRows = $service->editAuditFinish_d($object);
		if (!is_null($affectedRows)) {
			$newObj = $service->get_d($object['id']);
			if ($this->c_isNeedExa($newObj ,$oldObj)) { //判断是否需要重新提交审批
				$newObj['parentDeptId'] = $service->get_table_fields('department' ,'DEPT_ID='.$newObj['deptId'] ,'pdeptid');

				//部门已确认HR未确认（试点专区、服务执行区）
				if ($newObj['hrState'] == 0 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY') {
					succ_show('controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				//部门已确认并审批HR未确认（试点专区、服务执行区）
				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY' && $newObj['state'] == '') {
					succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						|| ($newObj['parentDeptId'] == '130' && $newObj['postType'] != 'YPZW-WY')) {
					if(($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
							&& $newObj['postType'] != 'YPZW-WY') {
						succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					} else {
						succ_show('controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=面试评估审批'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					}
				}
			} else {
				$service->updateById(array('id' => $newObj['id'] ,'changeTip' => 0)); //不需要审批的把变更标识改回来
				if($affectedRows != 0){
					//发送相关邮件通知人事组
					$service->sendMailByEdit_d($object['id'],array('socialPlace'));
				}
				msgRf ( '保存成功！' );
			}
		} else {
			msgRf ( '保存失败！' );
		}
	}
}