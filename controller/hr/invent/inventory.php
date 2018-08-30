<?php

/**
 * @author Administrator
 * @Date 2012年5月30日 14:38:15
 * @version 1.0
 * @description:员工盘点表控制层
 */
class controller_hr_invent_inventory extends controller_base_action {

	function __construct() {
		$this->objName = "inventory";
		$this->objPath = "hr_invent";
		parent :: __construct();
	}

	function c_list() {
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : '';
		$parentName = isset ($_GET['parentName']) ? $_GET['parentName'] : '';
		$this->assign('parentId', $parentId);
		$this->assign('parentName', $parentName);
		$this->view('list');
	}

	/*
	 * 跳转到员工盘点表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * 跳转到人员盘点个人列表
	 */
    function c_toPersonnelList() {
    	if(!$this->service->this_limit['盘点信息查看']){
			echo "<script>alert('没有权限!');</script>";
			exit();
		}
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }

	/**
	 * 获取分页数据转成Json -- 部门权限
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$otherdatasDao = new model_common_otherdatas();
		$personLimit = $otherdatasDao->getUserPriv('hr_personnel_personnel',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['JOB_ID']);
		//系统权限
		$sysLimit = $personLimit['部门权限'];

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
	 * 跳转到新增员工盘点表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑员工盘点表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看员工盘点表页面
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
	 * 列表高级查询
	 */
	function c_toSearch(){
        $this->view('search');
	}

	/*
	 * 跳转到导入页面
	 */
	function c_toImport() {
		$this->view('import');
	}

	/**
	 * 员工盘点信息导入
	 */
	function c_import() {
		$objKeyArr = array (
			0 => 'userNo',
			1 => 'userName',
			2 => 'deptNameS',
			3 => 'position',
			4 => 'inventoryDate',
			5 => 'entryDate',
			6 => 'alternative',
			7 => 'matching',
			8 => 'critical',
			9 => 'isCore',
			10 => 'recruitment',
			11 => 'performance',
			12 => 'examine',
			13 => 'preEliminated',
			14 => 'remark',
			15 => 'adjust'
		); //字段数组
		$resultArr = $this->service->import_d($objKeyArr);
	}

	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '人员盘点导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * 跳转到excel导出页面
	 */
	function c_toExcelOut() {
		$this->view('excelout');
	}

	/**
	 * excel导出
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['userNo'])) //员工编号
			$this->service->searchArr['userNoM'] = $formData['userNo'];
		if(!empty($formData['userName'])) //员工姓名
			$this->service->searchArr['userNameM'] = $formData['userName'];

		if(!empty($formData['deptName'])) //部门
			$this->service->searchArr['deptName'] = $formData['deptName'];
		if(!empty($formData['position'])) //职位
			$this->service->searchArr['positionSearch'] = $formData['position'];

		if(!empty($formData['entryDateBegin'])) //入职开始时间
			$this->service->searchArr['entryDateBegin'] = $formData['entryDateBegin'];
		if(!empty($formData['entryDateEnd'])) //入职结束时间
			$this->service->searchArr['entryDateEnd'] = $formData['entryDateEnd'];

		if(!empty($formData['inventoryDateBegin'])) //盘点开始时间
			$this->service->searchArr['inventoryDateBegin'] = $formData['inventoryDateBegin'];
		if(!empty($formData['inventoryDateEnd'])) //盘点结束时间
			$this->service->searchArr['inventoryDateEnd'] = $formData['inventoryDateEnd'];

		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $key => $val) {
			$rowData[$key]['userNo'] = $val['userNo'];
			$rowData[$key]['userName'] = $val['userName'];
			$rowData[$key]['deptNameS'] = $val['deptNameS'];
			$rowData[$key]['position'] = $val['position'];
			$rowData[$key]['inventoryDate'] = $val['inventoryDate'];
			$rowData[$key]['entryDate'] = $val['entryDate'];
			$rowData[$key]['alternative'] = $val['alternative'];
			$rowData[$key]['matching'] = $val['matching'];
			$rowData[$key]['isCritical'] = $val['isCritical'];
			$rowData[$key]['critical'] = $val['critical'];
			$rowData[$key]['isCore'] = $val['isCore'];
			$rowData[$key]['recruitment'] = $val['recruitment'];
			$rowData[$key]['performance'] = $val['performance'];
			$rowData[$key]['examine'] = $val['examine'];
			$rowData[$key]['preEliminated'] = $val['preEliminated'];
			$rowData[$key]['remark'] = $val['remark'];
			$rowData[$key]['adjust'] = $val['adjust'];
			$rowData[$key]['workQuality'] = $val['workQuality'];
			$rowData[$key]['workEfficiency'] = $val['workEfficiency'];
			$rowData[$key]['workZeal'] = $val['workZeal'];
		}

		$colArr  = array();
		$modelName = '人资-人员盘点信息';
		return model_hr_recruitment_importHrUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}
}
?>