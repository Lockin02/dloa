<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:11:48
 * @version 1.0
 * @description:人事管理-基础信息-教育经历控制层
 */
class controller_hr_personnel_education extends controller_base_action {

	function __construct() {
		$this->objName = "education";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * 跳转到人事管理-基础信息-教育经历列表
	 */
    function c_page() {
      $this->view('list');
    }


	/**
	 * 跳转到人事管理-基础信息-教育经历-个人列表
	 */
    function c_toPersonnelList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }


	/**
	 * 跳转到人事管理-基础信息-教育经历-个人列表(新增，修改)
	 */
    function c_toEidtList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-editlist');
    }

	/**
	 * 教育经历-个人列表Json
	 */
	function c_personnelPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->searchArr['userNo']=$_SESSION['USER_ID'];


		//$service->asc = false;
		$rows = $service->page_d ();
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
	 * 跳转到新增人事管理-基础信息-教育经历页面
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao=new model_common_otherdatas();     //新建otherdatas对象
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
		$this->showDatadicts ( array ('education' => 'HRJYXL' ));
    	$this->view ( 'add' );
   }
   /**
	 * 跳转到新增人事管理-基础信息-教育经历页面
	 */
	function c_toMyAdd() {
	  	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
	  	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
	  	$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
	  	$this->assign ( 'userNo', $userNo );
	  	$this->assign ( 'userAccount', $userAccount );
	  	$this->assign ( 'userName', $useName );
		$this->showDatadicts ( array ('education' => 'HRJYXL' ));
	     $this->view ( 'my-add' );
   }

   /**
	 * 跳转到编辑人事管理-基础信息-教育经历页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('education' => 'HRJYXL' ), $obj ['education'] );
		//显示附件信息
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
      	$this->view ( 'edit');
   }

   /**
	 * 跳转到查看人事管理-基础信息-教育经历页面
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

		$title = '教育经历信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * 导出excel
	 */
	 function c_toExcelOut(){
	 	$this->showDatadicts(array('education' => 'HRJYXL'),null,true);
		$this->view('excelout');
	}

	/*
	 * 导出excel
	 */
	 function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['organization']))
	 		$this->service->searchArr['organizationSearch'] = $object['organization'];
		if(!empty($object['content']))
			$this->service->searchArr['contentSearch'] = $object['content'];
		if(!empty($object['education']))
	 		$this->service->searchArr['education'] = $object['education'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDateSearch'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDateSearch'] = $object['closeDate'];
		//	print_R($this->service->searchArr);
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		if(is_array($planEquRows)){
			foreach ( $planEquRows as $key => $val ){
				$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
				$exportData[$key]['userName']=$planEquRows[$key]['userName'];
				$exportData[$key]['organization']=$planEquRows[$key]['organization'];
				$exportData[$key]['content']=$planEquRows[$key]['content'];
				$exportData[$key]['educationName']=$planEquRows[$key]['educationName'];
				$exportData[$key]['certificate']=$planEquRows[$key]['certificate'];
				$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
				$exportData[$key]['closeDate']=$planEquRows[$key]['closeDate'];
				$exportData[$key]['fujian']=$planEquRows[$key]['fujian'];
				$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			}
		}
		return $this->service->excelOut ( $exportData );
	}

		  	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['education']['listSql']))));
		$this->view('excelout-select');

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
//		echo "<pre>";
//		print_r($rows);
		$colNameArr=array();//列名数组
		include_once ("model/hr/personnel/educationFieldArr.php");
		if(is_array($_POST['education'])){
			foreach($_POST['education'] as $key=>$val){
					foreach($educationFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
//		print_r($_POST['contract']);
//		print_r($contractFieldArr);
		$newColArr=array_combine($_POST['education'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['education']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutEducation($newColArr,$dataArr);
	}
	/******************* E 导入导出系列 ************************/
 }
?>