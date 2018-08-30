<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:11:59
 * @version 1.0
 * @description:人事管理-基础信息-工作经历控制层
 */
class controller_hr_personnel_work extends controller_base_action {

	function __construct() {
		$this->objName = "work";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * 跳转到人事管理-基础信息-工作经历列表
	 */
	function c_page() {
		$this->view('list');
	}


	/**
	 * 跳转到人事管理-基础信息-工作经历-个人列表
	 */
	function c_toPersonnelList() {
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //员工编号
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //员工账号
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->view('personnel-list');
	}


	/**
	 * 跳转到人事管理-基础信息-工作经历-个人列表(新增，修改)
	 */
	function c_toEidtList() {
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //员工编号
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //员工账号
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->view('personnel-editlist');
	}

	/**
	 * 跳转到新增人事管理-基础信息-工作经历页面
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao = new model_common_otherdatas(); //新建otherdatas对象
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
		$this->view ( 'add' );
	}

	/**
	 * 跳转到新增人事管理-基础信息-工作经历页面
	 */
	function c_toMyAdd() {
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //员工编号
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //员工账号
		$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->assign ( 'userName', $useName );
		$this->view ( 'my-add' );
	}

	/**
	 * 跳转到编辑人事管理-基础信息-工作经历页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//显示附件信息
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看人事管理-基础信息-工作经历页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'view' );
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

		$title = '工作经历信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导出excel
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * 导出excel
	 */
	function c_excelOut(){
		$object = $_POST[$this->objName];
		if(!empty($object['userId']))
			$this->service->searchArr['userNo'] = $object['userId'];
		if(!empty($object['company']))
			$this->service->searchArr['companySearch'] = $object['company'];
		if(!empty($object['dept']))
			$this->service->searchArr['deptSearch'] = $object['dept'];
		if(!empty($object['position']))
			$this->service->searchArr['positionSearch'] = $object['position'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDateSearch'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDateSearch'] = $object['closeDate'];
		if(!empty($object['isSeniority']))
			$this->service->searchArr['isSeniority'] = $object['isSeniority'];
		if(!empty($object['seniority']))
			$this->service->searchArr['seniority'] = $object['seniority'];

		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo'] = $planEquRows[$key]['userNo'];
			$exportData[$key]['userName'] = $planEquRows[$key]['userName'];
			$exportData[$key]['company'] = $planEquRows[$key]['company'];
			$exportData[$key]['dept'] = $planEquRows[$key]['dept'];
			$exportData[$key]['position'] = $planEquRows[$key]['position'];
			$exportData[$key]['treatment'] = $planEquRows[$key]['treatment'];
			$exportData[$key]['beginDate'] = $planEquRows[$key]['beginDate'];
			$exportData[$key]['closeDate'] = $planEquRows[$key]['closeDate'];
			$exportData[$key]['isSeniority'] = $planEquRows[$key]['isSeniority'];
			$exportData[$key]['seniority'] = $planEquRows[$key]['seniority'];
			$exportData[$key]['prove'] = $planEquRows[$key]['prove'];
			$exportData[$key]['leaveReason'] = $planEquRows[$key]['leaveReason'];
			$exportData[$key]['remark'] = $planEquRows[$key]['remark'];
			$exportData[$key]['fujian'] = $planEquRows[$key]['fujian'];
		}
		return $this->service->excelOut ( $exportData );
	 }

	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['work']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * 导出数据
	 */
	function c_selectExcelOut(){
		$rows=array();//数据集
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}

		$colNameArr = array();//列名数组
		include_once ("model/hr/personnel/workFieldArr.php");
		if(is_array($_POST['work'])){
			foreach($_POST['work'] as $key=>$val){
					foreach($workFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}

		$newColArr=array_combine($_POST['work'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['work']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutWork($newColArr,$dataArr);
	}

	/******************* E 导入导出系列 ************************/
 }
?>