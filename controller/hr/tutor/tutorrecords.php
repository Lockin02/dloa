<?php

/**
 * @author Show
 * @Date 2012年5月26日 星期六 11:40:48
 * @version 1.0
 * @description:导师经历信息表控制层
 */
class controller_hr_tutor_tutorrecords extends controller_base_action {

	function __construct() {
		$this->objName = "tutorrecords";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/*
	 * 跳转到导师经历信息表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到导师经历信息表列表--个人
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/**
	 * 导师管理----学院直接上级列表
	 */
	function c_studentSuperiorList(){
        $this->assign( 'userId', $_SESSION['USER_ID']);
		$this->view('studentSuperior');
	}

    /**
     * 导师管理--部门列表
     */
    function c_tutorDeptlist(){
    	$this->assign("deptId",$_SESSION['DEPT_ID']);
        $this->view('tutordeptlist');
    }

    /**
     * 导师管理---个人列表
     */
    function c_tutorPerson(){
    	$this->assign("userId",$_SESSION['USER_ID']);
        $this->view('tutorperson');
    }

    function c_personJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->getParam($_POST); //设置前台获取的参数信息
		$service->groupBy = 'c.id';

		$rows = $service->pageBySqlId('ExaStatus');
		$coachplanDao=new model_hr_tutor_coachplan();
		$schemeinfoDao=new model_hr_tutor_schemeinfo();

		if(is_array($rows)){
	        //循环判断角色是导师还是学员
	        foreach($rows as $k => $v){
	           if($v['userAccount'] == $_SESSION['USER_ID']){
	                $rows[$k]['role'] = "导师";
	           }else if($v['studentAccount'] == $_SESSION['USER_ID']){
	                $rows[$k]['role'] = "学员";
	           }
               $rows[$k]['isAddPlan'] = $coachplanDao->isAddPlan_d($v['id']);
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$v['schemeId']));
				if($schemeinfo['selfgraded']>0){
					$rows[$k]['tutorScore']=1;//导师已评分
				}else{
					$rows[$k]['tutorScore']=0;
				}
				if($schemeinfo['staffgraded']>0){
					$rows[$k]['staffScore']=1;//学员已评分
				}else{
					$rows[$k]['staffScore']=0;
				}
//               $rewardDao=new model_hr_tutor_rewardinfo();
//               $rows[$k]['isPublish']=$rewardDao->getIsPublish_d($v['userAccount'],$v['studentAccount']);
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
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * 跳转到编辑 是否需要制定辅导计划 是否需要按照HR的模式提交周报 页面
	 */
	function c_toEditModel(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('editmodel' ,true);
	}

	/**
	 * 编辑 是否需要制定辅导计划 是否需要按照HR的模式提交周报
	 */
	function c_editModel(){
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST [$this->objName];
		if ($this->service->editModel_d($object)) {
			msg ( '提交成功！' );
		}else{
			msg('提交失败！');
		}
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
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 关闭导师记录，保存关闭备注
	 */
	 function c_close(){
	 	$object = $_POST [$this->objName];
		if ($this->service->close_d( $object)) {
			msg ( '关闭成功！' );
		}else{
			msg('关闭失败！');
		}
	 }

	/**
	 * 编辑考核分数
	 */
	 function c_editScore(){
		$this->checkSubmit(); //检查是否重复提交
	 	$object = $_POST [$this->objName];
		if ($this->service->editScore_d( $object)) {
			msg ( '编辑考核分数成功！' );
		}else{
			msg('编辑考核分数失败！');
		}
	 }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('ExaStatus');
		if($_REQUEST['userNo2']){
			$personnel =new model_hr_personnel_personnel();
			$dataArr = $personnel->find(array('userNo'=>$_REQUEST['userNo2']));
			if(is_array($rows)){
		        //循环判断角色是导师还是学员
		        foreach($rows as $k => $v){
		           if($v['userAccount'] == $dataArr['userAccount']){
		                $rows[$k]['role'] = "导师";
		           }else if($v['studentAccount'] == $dataArr['userAccount']){
		                $rows[$k]['role'] = "学员";
		           }
		        }
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$schemeDao=new model_hr_tutor_scheme();
		$schemeinfoDao=new model_hr_tutor_schemeinfo();
		for($i=0;$i<count($rows);$i++){
			$scheme=$schemeDao->find(array("tutorId"=>$rows[$i]['id']));
			if($scheme){
				$rows[$i]['sign']=1;
			}else
				$rows[$i]['sign']=0;
			$scheme="";
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$rows[$i]['schemeId']));
				if($schemeinfo['superiorgraded']>0){
					$rows[$i]['supScore']=1;//上级已评分
				}else{
					$rows[$i]['supScore']=0;
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
	function c_pageJsonForDept() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//系统权限
		$sysLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')){

			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();
		}

		//$service->asc = false;
		$rows = $service->pageBySqlId('ExaStatus');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$schemeDao=new model_hr_tutor_scheme();
       $schemeinfoDao=new model_hr_tutor_schemeinfo();
		for($i=0;$i<count($rows);$i++){
			$scheme=$schemeDao->find(array("tutorId"=>$rows[$i]['id']));
			if($scheme){
				$rows[$i]['sign']=1;
			}else
				$rows[$i]['sign']=0;
			$scheme="";
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$rows[$i]['schemeId']));
				if($schemeinfo['assistantgraded']>0){
					$rows[$i]['assistantScore']=1;//部门已评分
				}else{
					$rows[$i]['assistantScore']=0;
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
	 * 跳转到新增导师经历信息表页面
	 */
	function c_toAdd() {
		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);
		$this->view('add' ,true);
	}

	/**
	 * 跳转到编辑导师经历信息表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('edit' ,true);
	}

	/**
	 * 跳转到更换领导页面
	 */
	function c_toEditLeader() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('edit-leader' ,true);
	}

	/**
	 * 跳转到查看导师经历信息表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /**
     * 跳转到关闭导师记录的页面
     */
	function c_toCloseTutorrecords(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('close' ,true);
	}

	/**
	 * 跳转到编辑考核分数的页面
	 */
	function c_toEditScore(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		if(!empty($obj)){
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
		}
		$this->view('editscore' ,true);
	}

	/**
	 * 不指定导师的新增方法
	 */
	function c_newadd(){
		$this->checkSubmit(); //检查是否重复提交
		$id=$this->service->newadd_d($_POST[$this->objName]);
		if($id){
			msg("保存成功");
		}
	}

	/**
	 * 完成一条导师记录
	 */
	function c_complete(){
		$this->checkSubmit(); //检查是否重复提交
		try{
			$this->service->complete_d($_POST['id']);
			echo 1;
		}catch(exception $e){
			echo 0;
		}
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}


	/****************** 人事指定导师 **************************/
	/**
	 * 指定导师
	 */
	function c_toSetTutor(){
		//人资信息渲染
		$personnelInfo = $this->service->getPersonnelInfo_d($_GET['userAccount']);
		$this->assignFunc($personnelInfo);

		//邮件信息渲染，通过直属部门性质抄送人不同
		if($personnelInfo['deptId']==32){
			$mailArr = $this->service->getMailInfoSpecial_d();
			$this->assignFunc($mailArr);
		}else{
			$mailArr = $this->service->getMailInfo_d();
			$this->assignFunc($mailArr);
		}
		$this->view('settutor' ,true);
	}

	/*
	 * 不指定导师，发邮件
	 */
	function c_toUnsetTutor(){
		//人资信息渲染
		$personnelInfo = $this->service->getPersonnelInfo_d($_GET['userAccount']);
		$this->assignFunc($personnelInfo);

		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);
		$this->assign("perid",$_GET['id']);
		$this->view('unsettutor' ,true);
	}


	/****************** 入职通知指定导师 **************************/
	/**
	 * 指定导师
	 */
	function c_toSetEntryTutor(){

		//入职信息渲染
		$personnelInfo = $this->service->getEntryInfo_d($_GET['entryId']);
		$this->assignFunc($personnelInfo);

		//导师相关信息渲染
		$recordsInfo = $this->service->find(array('userAccount'=>$personnelInfo['tutorId']));
		$this->assign("userNo",$recordsInfo['userNo']);
		$this->assign("deptName",$recordsInfo['deptName']);
		$this->assign("deptId",$recordsInfo['deptId']);
		$this->assign("jobName",$recordsInfo['jobName']);
		$this->assign("jobId",$recordsInfo['jobId']);

		//入职日期(页面隐藏域)
		$this->assign("entryDate",$_GET['entryDate']);
		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('setentrytutor' ,true);
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '导师经历信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/**
	 * 导出excel
	 */
	function c_export(){
		if($_GET['deptid']){
			$this->service->searchArr['deptId']=$_GET['deptid'];
		}
		print_r($this->service->searchArr);
		$planEquRows=$this->service->list_d();
		$exportData = array();
		if($planEquRows){
			foreach ( $planEquRows as $key => $val ){
				$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
				$exportData[$key]['userName']=$planEquRows[$key]['userName'];
				$exportData[$key]['jobName']=$planEquRows[$key]['jobName'];
				$exportData[$key]['studentNo']=$planEquRows[$key]['studentNo'];
				$exportData[$key]['studentName']=$planEquRows[$key]['studentName'];
				$exportData[$key]['studentDeptName']=$planEquRows[$key]['studentDeptName'];
				$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
				$exportData[$key]['becomeDate']=$planEquRows[$key]['becomeDate'];
				$exportData[$key]['status']=$this->service->statusDao->statusKtoC($planEquRows[$key]['status']);
				$exportData[$key]['assessmentScore']=$planEquRows[$key]['assessmentScore'];
				$exportData[$key]['rewardPrice']=$planEquRows[$key]['rewardPrice'];
				$exportData[$key]['closeReason']=$planEquRows[$key]['closeReason'];
				$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			}
		}
		return $this->service->export($exportData);
	}



	/******************* E 导入导出系列 ************************/
}
?>