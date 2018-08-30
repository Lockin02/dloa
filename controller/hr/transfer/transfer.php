<?php

/**
 * @author Show
 * @Date 2012年5月28日 星期一 13:38:56
 * @version 1.0
 * @description:人员调用记录控制层
 */
class controller_hr_transfer_transfer extends controller_base_action {

	function __construct() {
		$this->objName = "transfer";
		$this->objPath = "hr_transfer";
		parent :: __construct();
	}

	/*
	 * 跳转到人员调用记录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * 跳转到人员调用记录列表--个人
	 */
	function c_pageByPerson() {
		$this->assign('thisUser' ,$_SESSION['USERNAME'] );
		$this->assign('thisUserId' ,$_SESSION['USER_ID'] );
		$this->assign('userNo' ,$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/*
	 * 跳转到调动管理(部门)
	 */
	function c_toTransferManage(){
		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->view('manage-list');
	}


	/**
	 * 跳转到员工调岗申请页面
	 */
	function c_toAddJobTran(){
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$personDao = new model_hr_personnel_personnel();
		$row = $personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('thisUser' ,$row['staffName']);
		$this->assign('userNo' ,$row['userNo']);
		$this->assign('prePersonClassCode' ,$row['personnelClass']);
		$this->assign('prePersonClass' ,$row['personnelClassName']);
		$otherDao = new model_common_otherdatas();     //新建otherdatas对象
		$thisUserInfo = $otherDao->getUserInfoByUserNo($otherDao->getUserCard($_SESSION['USER_ID']));

		$date = date('Y-m-d');
		$this->assign('today' ,$date);
		$arr = $otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt = "";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
		}
		$area = new includes_class_global();
		$this->show->assign('areaOpt' ,$area->area_select());  //将所有区域添加到select标签
		$this->assign('companyOpt' ,$companyOpt);     //将所有公司添加到select标签
		$this->view('jobtran-add' ,true);
	}

	/**
	 * 员工调岗申请新增方法
	 */
	function c_add(){
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if ($obj['status'] == '') {
			$obj['status'] = 0;
		}
		if($obj['isCompanyChange'] == 1) {
			$obj['transferTypeName'].="公司变动  ";
		}
		if($obj['isAreaChange'] == 1) {
			$obj['transferTypeName'].="区域变动  ";
		}
		if($obj['isDeptChange'] == 1) {
			$obj['transferTypeName'].="部门变动 ";
		}
		if($obj['isJobChange'] == 1) {
			$obj['transferTypeName'].="职位变动 ";
		}
		if($obj['isClassChange'] == 1) {
			$obj['transferTypeName'].="人员分类变动 ";
		}

		$id = $this->service->add_d($obj);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$addType = isset ( $_GET ['addType'] ) ? $_GET ['addType'] : null;
		if($id) {
			if ("audit" == $actType) {//提交工作流
				$preDeptId = $_POST[$this->objName]['preBelongDeptId'];
				$afterDeptId = $_POST[$this->objName]['afterBelongDeptId'];
				$deptIds = $preDeptId.",".$afterDeptId;
				succ_show ( 'controller/hr/transfer/ewf_hr_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$deptIds);
			} else {
				if ($obj['status'] != 1) {
					msg('保存成功');
				} else {
					msg('提交成功');
				}
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转到个人调岗申请列表(填单人)
	 */
	function c_toJobTranList(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view('jobtran-list');
	}

	/**
	 * 跳转到个人调岗申请列表
	 */
	function c_toPersonJobTranList(){
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$personDao=new model_hr_personnel_personnel();
		$row=$personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('userNo',$row['userNo']);
		$this->view('personjobtran-list');
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
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->page_d ();

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['stateC'] = $service->statusDao->statusKtoC($rows[$key]['status']);
				if($rows[$key]['isCompanyChange'] == 0) {
					$rows[$key]['isCompanyChangeC'] = '否';
				} else {
					$rows[$key]['isCompanyChangeC'] = '是';
				}

				if($rows[$key]['isDeptChange'] == 0) {
					$rows[$key]['isDeptChangeC'] = '否';
				} else {
					$rows[$key]['isDeptChangeC'] = '是';
				}

				if($rows[$key]['isJobChange'] == 0) {
					$rows[$key]['isJobChangeC'] = '否';
				} else {
					$rows[$key]['isJobChangeC'] = '是';
				}

				if($rows[$key]['isAreaChange'] == 0) {
					$rows[$key]['isAreaChangeC'] = '否';
				} else {
					$rows[$key]['isAreaChangeC'] = '是';
				}

				if($rows[$key]['isClassChange'] == 0) {
					$rows[$key]['isClassChangeC'] = '否';
				} else {
					$rows[$key]['isClassChangeC'] = '是';
				}
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

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增人员调用记录页面
	 */
	function c_toAdd() {
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$personDao = new model_hr_personnel_personnel();
		$row = $personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('thisUser' ,$_SESSION['USERNAME']);
		$this->assign('prePersonClassCode' ,$row['personnelClass']);
		$this->assign('prePersonClass' ,$row['personnelClassName']);
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL'));
		$otherDao = new model_common_otherdatas();     //新建otherdatas对象
		$date = date(Y."-".m."-".d);
		$this->assign('today' ,$date);
		$arr = $otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt = "";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
		}
		$area = new includes_class_global();
		$this->show->assign('areaOpt' ,$area->area_select());  //将所有区域添加到select标签
		$this->assign('companyOpt' ,$companyOpt);     //将所有公司添加到select标签
		$this->view('add' ,true);
	}



	/**
	 * 跳转到编辑人员调用记录页面
	 */
	function c_toEdit() {
//		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);

		$this->view('edit');
	}

	/**
	 * 跳转到编辑人员调用记录页面
	 */
	function c_toEditTran() {
//		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);

		$this->view('tran-edit');
	}

	/*
	 * 部门审查员工调岗方法
	 */
	function c_approval(){
		$this->checkSubmit();
		$_POST [$this->objName]['status']=4;
		$id = $this->service->edit_d($_POST [$this->objName]) ;
		if($id){
			if($_POST['type']=='hrmanager'){
				msgGo('确认成功',"?model=hr_transfer_transfer");
			}else{
				msgGo('确认成功',"?model=hr_transfer_transfer&action=toTransferManage");
			}
		}else{
			if($_POST['type']=='hrmanager'){
				msgGo('确认失败',"?model=hr_transfer_transfer");
			}else{
				msgGo('确认失败',"?model=hr_transfer_transfer&action=toTransferManage");
			}
		}
	}

	/**
	 * 跳转到编辑员工调岗记录页面
	 */
	function c_toEditJobTran(){
	    $addType = isset ( $_GET ['addType'] ) ? $_GET ['addType'] : "";
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('addType',$addType);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);
		$otherDao = new model_common_otherdatas();
		$areaDao = new model_common_otherdatas();
		$arr = $otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt = "";
		$areaOpt = "";
		$company = $obj['afterUnitName'];
		$area = $obj['afterUseAreaName'];
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			if($arr[$i]['NameCN'] == $company) {
				$companyOpt = $companyOpt."<option selected value='$id'>".$arr[$i]['NameCN']."</option>";
			} else {
				$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
			}
		}
		$areaArr = $areaDao->getArea();
		foreach($areaArr as $k => $v){
			$id = $v['ID'];
			if($area == $v['Name']) {
				$areaOpt = $areaOpt."<option selected value='$id'>".$v['Name']."</option>";
			} else {
				$areaOpt = $areaOpt."<option value='$id'>".$v['Name']."</option>";
			}
		}
		$this->show->assign('areaOpt',$areaOpt);  //将所有区域添加到select标签
		$this->assign('companyOpt',$companyOpt);     //将所有公司添加到select标签
		$this->view('jobtran-edit' ,true);
	}

	/**
	 * 编辑员工调岗方法
	 */
	function c_editJobTran(){
		$id = $this->service->edit_d($_POST [$this->objName]) ;
		if ($id) {
			msgGo('保存成功',"index1.php?model=hr_transfer_transfer&action=toJobTranList");
		}
	}

	/**
	 * 员工调岗编辑保存
	 */
	function c_edit(){
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if($obj['isCompanyChange'] == 1) {
			$obj['transferTypeName'].="公司变动  ";
		}
		if($obj['isAreaChange'] == 1) {
			$obj['transferTypeName'].="区域变动  ";
		}
		if($obj['isDeptChange'] == 1) {
			$obj['transferTypeName'].="部门变动 ";
		}
		if($obj['isJobChange'] == 1) {
			$obj['transferTypeName'].="职位变动 ";
		}
		if($obj['isClassChange'] == 1) {
			$obj['transferTypeName'].="人员分类变动 ";
		}

		$id = $this->service->edit_d( $obj );
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	    if($id){
			if ("audit" == $actType) {//提交工作流
				$preDeptId=$_POST[$this->objName]['preBelongDeptId'];
				$afterDeptId=$_POST[$this->objName]['afterBelongDeptId'];
				$deptIds=$preDeptId.",".$afterDeptId;
				if($_POST['addType'] == 'addEdit') {
					succ_show ( 'controller/hr/transfer/ewf_manager_index.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&billDept='.$deptIds);
				}else{
					succ_show ( 'controller/hr/transfer/ewf_manager_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&billDept='.$deptIds);
				}
			} else {
				if($_POST['addType'] == 'addEdit') {
					msgGo('保存成功',"?model=hr_transfer_transfer&action=toEditJobTran&addType=addEdit&id=".$_POST[$this->objName]['id']);
				}else{
					if ($_POST[$this->objName]['status'] == 1) {
						msgGo('提交成功',"?model=hr_transfer_transfer&action=toJobTranList");
					}else {
						msgGo('保存成功',"?model=hr_transfer_transfer&action=toJobTranList");
					}
				}
			}
	    }else{
			if($_POST['addType']=='addEdit'){
			msgGo('保存失败',"?model=hr_transfer_transfer&action=toEditJobTran&addType=addEdit&id=".$_POST[$this->objName]['id']);
			}else{
				msgGo('保存失败',"?model=hr_transfer_transfer&action=toJobTranList");
			}
	    }
   }

	/**
	 * 员工调岗填写意见和是否同意
	 */
	function c_opinionEdit(){
		$this->checkSubmit();
		$_POST [$this->objName]['status'] = 3;
		$id = $this->service->opinionEdit_d($_POST [$this->objName]);
		if ($id) {
			msgGo('保存成功',"index1.php?model=hr_transfer_transfer&action=toJobTranList");
		}
	}


	/**
	 * 跳转到查看调岗人员记录页面
	 */
	function c_toViewJobTran(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['employeeOpinion'] == 0) {
			$opt = "不同意";
		}
		if($obj['employeeOpinion'] == 1) {
			$opt = "同意";
		}
		if($obj['employeeOpinion'] == 2) {
			$opt = "";
		}
		$this->assign("opinion" ,$opt);
		$this->view('jobtran-view');
	}

	/**
	 * 跳转到查看人员调用记录页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$this->assign("audit" ,$actType);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 部门领导审批员工调岗申请
	 */
	function c_toLeaderView(){
		$type=isset($_GET['type'])?$_GET['type']:'';
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$date=date(Y."-".m."-".d);
		$this->assign('today',$date);
		$this->assign('type',$type);
		$this->view('manage-approval' ,true);
	}

	/**
	 * 跳转到员工是否同意调岗页面
	 */
	function c_toOpinionView(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('personjobtran-view' ,true);
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * 判断公司类型
	 */
	function c_companyType(){
		$id = $_POST['id'];
		$branchDao = new  model_deptuser_branch_branch();
		$aboutinfo = $branchDao->find(array('ID'=>$id));
			if($aboutinfo['type'] == 0) {
				$typeinfo = '子公司';
			}else if($aboutinfo['type'] == 1) {
				$typeinfo = '集团';
			}
		echo $typeinfo;
	}

	/**
	 * 更新员工档案信息
	 */
	function c_updatePersonInfo() {
		$idsArr = $_POST ['transferIds'];
		$flag = $this->service->updatePersonInfo_d($idsArr);
		if($flag) {
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
     * 审批后处理
     */
     function c_dealTransfer(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok"){  //审批通过
				$row=$this->service->get_d($folowInfo['objId']);
				if($row['managerId'] == $row['userAccount']) {//如果是员工本人申请，则自动设置状态为同意
					$obj['id'] = $folowInfo['objId'];
					$obj['employeeOpinion'] = 1;
					$obj['status'] = '3';
					$this->service->edit_d($obj,true);
				}
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
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

		$title = '调动信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导出excel
	 */
	function c_toExport(){
		$this->showDatadicts(array('transferType' => 'HRDDLX'),null,true);
		$otherDao=new model_common_otherdatas(); //新建otherdatas对象
		$arr=$otherDao->getCompanyAndAreaInfo(); //获得所有公司和公司所属区域
		$companyOpt = "<option value=''></option>";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>"; //拼凑option标签
		}
		$this->assign('companyOpt' ,$companyOpt); //将所有公司添加到select标签
		$this->showDatadicts(array('prePersonClassCode' => 'HRRYFL')); //调动前人员分类
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL')); //调动后人员分类
		$this->view('exportview');
	}

	/**
	 * 导出excel
	 */
	function c_export(){
		$object = $_POST[$this->objName];

		if(!empty($object['beginDate']))
	 		$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['endDate']))
	 		$this->service->searchArr['endDate'] = $object['endDate'];
		if(!empty($object['formCode']))
			$this->service->searchArr['formCode'] = $object['formCode'];
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['preBelongDeptId']))
			$this->service->searchArr['preBelongDeptId'] = $object['preBelongDeptId'];
		if(!empty($object['afterBelongDeptId']))
			$this->service->searchArr['afterBelongDeptId'] = $object['afterBelongDeptId'];
		if(!empty($object['preUnitId']))
			$this->service->searchArr['preUnitId'] = $object['preUnitId'];
		if(!empty($object['afterUnitId']))
			$this->service->searchArr['afterUnitId'] = $object['afterUnitId'];
		if(!empty($object['preJobId']))
			$this->service->searchArr['preJobId'] = $object['preJobId'];
		if(!empty($object['afterJobId']))
			$this->service->searchArr['afterJobId'] = $object['afterJobId'];
		if(!empty($object['prePersonClassCode']))
			$this->service->searchArr['prePersonClassCode'] = $object['prePersonClassCode'];
		if(!empty($object['afterPersonClassCode']))
			$this->service->searchArr['afterPersonClassCode'] = $object['afterPersonClassCode'];

		$this->service->searchArr['ExaStatusArr'] = '部门审批,打回,完成';

		$rows = $this->service->list_d();
		foreach($rows as $key => $val) {
			if($rows[$key]['isCompanyChange'] == 0) {
				$rows[$key]['isCompanyChangeC'] = '否';
			} else {
				$rows[$key]['isCompanyChangeC'] = '是';
			}

			if($rows[$key]['isDeptChange'] == 0) {
				$rows[$key]['isDeptChangeC'] = '否';
			} else {
				$rows[$key]['isDeptChangeC'] = '是';
			}

			if($rows[$key]['isJobChange'] == 0) {
				$rows[$key]['isJobChangeC'] = '否';
			} else {
				$rows[$key]['isJobChangeC'] = '是';
			}

			if($rows[$key]['isAreaChange'] == 0) {
				$rows[$key]['isAreaChangeC'] = '否';
			} else {
				$rows[$key]['isAreaChangeC'] = '是';
			}

			if($rows[$key]['isClassChange'] == 0) {
				$rows[$key]['isClassChangeC'] = '否';
			} else {
				$rows[$key]['isClassChangeC'] = '是';
			}
		}

		$exportData = array();
		if($rows){
			foreach ( $rows as $key => $val ){
				$exportData[$key]['userNo'] = $rows[$key]['userNo'];
				$exportData[$key]['userName'] = $rows[$key]['userName'];
				$exportData[$key]['transferDate'] = $rows[$key]['transferDate'];
				$exportData[$key]['isCompanyChangeC'] = $rows[$key]['isCompanyChangeC'];
				$exportData[$key]['isDeptChangeC'] = $rows[$key]['isDeptChangeC'];
				$exportData[$key]['isJobChangeC'] = $rows[$key]['isJobChangeC'];
				$exportData[$key]['isAreaChangeC'] = $rows[$key]['isAreaChangeC'];
				$exportData[$key]['isClassChangeC'] = $rows[$key]['isClassChangeC'];
				$exportData[$key]['preUnitTypeName'] = $rows[$key]['preUnitTypeName'];
				$exportData[$key]['preUnitName'] = $rows[$key]['preUnitName'];
				$exportData[$key]['preBelongDeptName'] = $rows[$key]['preBelongDeptName'];
				$exportData[$key]['preDeptNameS'] = $rows[$key]['preDeptNameS'];
				$exportData[$key]['preDeptNameT'] = $rows[$key]['preDeptNameT'];
				$exportData[$key]['preDeptNameF'] = $rows[$key]['preDeptNameF'];
				$exportData[$key]['afterUnitTypeName'] = $rows[$key]['afterUnitTypeName'];
				$exportData[$key]['afterUnitName'] = $rows[$key]['afterUnitName'];
				$exportData[$key]['afterBelongDeptName'] = $rows[$key]['afterBelongDeptName'];
				$exportData[$key]['afterDeptNameS'] = $rows[$key]['afterDeptNameS'];
				$exportData[$key]['afterDeptNameT'] = $rows[$key]['afterDeptNameT'];
				$exportData[$key]['afterDeptNameF'] = $rows[$key]['afterDeptNameF'];
				$exportData[$key]['preJobName'] = $rows[$key]['preJobName'];
				$exportData[$key]['afterJobName'] = $rows[$key]['afterJobName'];
				$exportData[$key]['preUseAreaName'] = $rows[$key]['preUseAreaName'];
				$exportData[$key]['afterUseAreaName'] = $rows[$key]['afterUseAreaName'];
				$exportData[$key]['prePersonClass'] = $rows[$key]['prePersonClass'];
				$exportData[$key]['afterPersonClass'] = $rows[$key]['afterPersonClass'];
				$exportData[$key]['managerName'] = $rows[$key]['managerName'];
				$exportData[$key]['fujian'] = $rows[$key]['fujian'];
				$exportData[$key]['reason'] = $rows[$key]['reason'];
				$exportData[$key]['remark'] = $rows[$key]['remark'];
			}
		}

		return $this->service->export($exportData);
	}
	/******************* E 导入导出系列 ************************/

	/**
	 * 审批时更新数据
	 */
	function c_updateData() {
		$id = $_POST['id'];
		$reportDate = $_POST['reportDate'];
		$handoverDate = $_POST['handoverDate'];
		$handoverRemark = iconv("utf-8","gb2312",$_POST['handoverRemark']);//解决乱码
		$this->service->updateById(array('id' => $id, 'reportDate' => $reportDate, 'handoverDate' => $handoverDate, 'handoverRemark' => $handoverRemark));
	}

	/**
	 * 根据ID提交调动申请
	 */
	function c_ajaxSubmit() {
		$obj['id'] = $_POST['id'];
		$obj['status'] = 1;
		if($this->service->updateById($obj)){
			$this->service->mailToHr_d($obj['id']);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 跳转到确认提交审批页面
	 */
	function c_toConfirm() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX') ,$obj['transferType']);
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL') ,$obj['afterPersonClassCode']);
		$otherDao = new model_common_otherdatas();
		$areaDao = new model_common_otherdatas();
		$arr = $otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt = "";
		$areaOpt = "";
		$company = $obj['afterUnitName'];
		$area = $obj['afterUseAreaName'];
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			if($arr[$i]['NameCN'] == $company) {
				$companyOpt = $companyOpt."<option selected value='$id'>".$arr[$i]['NameCN']."</option>";
			} else {
				$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
			}
		}
		$areaArr = $areaDao->getArea();
		foreach($areaArr as $k => $v){
			$id = $v['ID'];
			if($area == $v['Name']) {
				$areaOpt =$areaOpt."<option selected value='$id'>".$v['Name']."</option>";
			} else {
				$areaOpt =$areaOpt."<option value='$id'>".$v['Name']."</option>";
			}
		}
		$this->show->assign('areaOpt' ,$areaOpt); //将所有区域添加到select标签
		$this->assign('companyOpt' ,$companyOpt); //将所有公司添加到select标签
		$this->view('confirm' ,true);
	}
}
?>