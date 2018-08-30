<?php

/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息控制层
 */
class controller_hr_contract_contract extends controller_base_action {

	function __construct() {
		$this->objName = "contract";
		$this->objPath = "hr_contract";
		parent :: __construct();
	}

	/*
	 * 跳转到合同信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 基础信息Tab页列表
	 */
	function c_tabList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist');
	}

	/**
	 * 基础信息Tab页列表
	 */
	function c_tabEditList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist-edit');
	}

	/**
	 * 跳转到员工等级信息列表
	 */
	function c_toDegree(){
		$this->view('degree');
	}

	/**
	 * 跳转到合同信息(包括所有合同信息和已过期合同信息)
	 */
	function c_toContractTab(){
		$this->view('contracttablist');
	}

	/**
	 * 跳转到快到期合同页面
	 */
	function c_toExpireContract(){
		$y = date(Y);
		$m = date(m) + 1;
		if($m == 13) {
			$m = 1;
			$y++;
		}
		if($m > 10) {
			$date = $y.'-'.$m;
		}else{
			$date = $y.'-0'.$m;
		}
		$this->assign('date',$date);
		$this->view('expireing-contract');
	}

	/**
	 * 跳转到新增合同信息页面
	 */
	function c_toAdd() {
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));

		$this->view('add',true);
	}

	/**
	 * 跳转到新增合同信息页面
	 */
	function c_toAddEdit() {
		$userNo=$_GET['userNo'];
		//获取员工信息
		$personnelDao = new model_hr_personnel_personnel();
		$row=$personnelDao->getInfoByUserNo_d($userNo);
		if(!empty($row)){
			foreach ($row as $key => $val) {
				$this->assign($key, $val);
			}
		}else{
			$this->assign("staffName",'');
			$this->assign("userAccount",'');
			$this->assign("userNo",'');
			$this->assign("jobName",'');
			$this->assign("jobId",'');
			$this->assign("entryDate",'');
			$this->assign("becomeDate",'');
		}
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));

		$this->view('personnel-add',true);
	}

	/**
	 * 跳转到新增合同信息页面(入职通知入口)
	 */
	function c_toAddByExternal() {
		$entryNoticeDao=new model_hr_recruitment_entryNotice();
		$entryNoticeRow=$entryNoticeDao->get_d($_GET['entryId']);
		$this->assign("entryId",$entryNoticeRow['id']);
		$this->assign("jobName",$entryNoticeRow['hrJobName']);
		$this->assign("jobId",$entryNoticeRow['hrJobId']);
		$this->assign("userAccount",$entryNoticeRow['userAccount']);
		$this->assign("userName",$entryNoticeRow['userName']);
		$this->assign("userNo",$entryNoticeRow['userNo']);
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));
		$this->view('external-add',true);
	}

	/**
	 * 跳转到编辑合同信息页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('conType' => 'HRHTLX'),$obj['conType']);
		$this->showDatadicts(array('conState' => 'HRHTZT'),$obj['conState']);
		$this->showDatadicts(array('conNum' => 'HRHTCS'),$obj['conNum']);

		$this->assign ( "file", $this->service->getFilesByObjId ($_GET['id'], true ) );
		$this->view('edit',true);
	}

	/**
	 * 跳转到查看合同信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
	  	$this->assign ( "file", $this->service->getFilesByObjId ($_GET['id'], false ) );
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
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$title = '合同信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		$objNameArr = array (
			0 => 'userNo', //员工编号
			1 => 'userName', //姓名
			2 => 'conNo', //合同编号
			3 => 'conName', //合同名称
			4 => 'conTypeName', //合同类型
			5 => 'conStateName', //合同状态
			6 => 'beginDate', //合同开始时间
			7 => 'closeDate', //合同结束时间
			8 => 'trialBeginDate', //试用开始时间
			9 => 'trialEndDate', //试用结束时间
			10 => 'conNumName', //合同次数
			11 => 'conContent', //合同内容
		);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
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
	function c_excelUpdate(){
		set_time_limit(0);
		$resultArr = $this->service->updateExecelData_d ();

		$title = '合同信息导入更新结果列表';
		$thead = array('数据信息' ,'导入结果');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/*
	 * 导出excel
	 */
	 function c_toExcelOut(){
	 	$this->showDatadicts(array('conType' => 'HRHTLX'),null,true);
	 	$this->showDatadicts(array('conState' => 'HRHTZT'),null,true);
	 	$this->showDatadicts(array('conNum' => 'HRHTCS'),null,true);
	 	$this->view("excelout");
	 }

	 /*
	  * 导出excel
	  */
	  function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['jobId']))
	 		$this->service->searchArr['jobId'] = $object['jobId'];
		if(!empty($object['conType']))
			$this->service->searchArr['conType'] = $object['conType'];
		if(!empty($object['conState']))
	 		$this->service->searchArr['conState'] = $object['conState'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDate'] = $object['closeDate'];
		if(!empty($object['conNum']))
			$this->service->searchArr['conNum'] = $object['conNum'];
		//print_r($this->service->searchArr);
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['conNo']=$planEquRows[$key]['conNo'];
			$exportData[$key]['conName']=$planEquRows[$key]['conName'];
			$exportData[$key]['conTypeName']=$planEquRows[$key]['conTypeName'];
			$exportData[$key]['conStateName']=$planEquRows[$key]['conStateName'];
			$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
			$exportData[$key]['closeDate']=$planEquRows[$key]['closeDate'];
			$exportData[$key]['trialBeginDate']=$planEquRows[$key]['trialBeginDate'];
			$exportData[$key]['trialEndDate']=$planEquRows[$key]['trialEndDate'];
			$exportData[$key]['conNumName']=$planEquRows[$key]['conNumName'];
			$exportData[$key]['conContent']=$planEquRows[$key]['conContent'];
		}
		return $this->service->excelOut ( $exportData );
	  }
	  	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['contract']['listSql']))));
		$this->view('excelout-select');

	}	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		//判断员工是否有导师
        if(is_array($rows)){
        	$managmentDao=new model_file_uploadfile_management();
	        foreach($rows as $k=>$v){
				$filelist=$managmentDao->getFilesByObjId($v['id'],'oa_hr_personnel_contract');
				if(empty($filelist)){
					$rows[$k]['files']=0;
				}else{
					$rows[$k]['files']=1;
				}
	        }
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

	/**
	 * 导出数据
	 */
	function c_selectExcelOut(){
//			set_time_limit(600);
		$rows=array();//数据集
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$colNameArr=array();//列名数组
		include_once ("model/hr/contract/contractFieldArr.php");
		if(is_array($_POST['contract'])){
			foreach($_POST['contract'] as $key=>$val){
					foreach($contractFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr=array_combine($_POST['contract'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['contract']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutContract($newColArr,$dataArr);
	}
	/******************* E 导入导出系列 ************************/
}
?>