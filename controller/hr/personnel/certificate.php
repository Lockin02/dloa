<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:12:08
 * @version 1.0
 * @description:人事管理-基础信息-资格证书控制层
 */
class controller_hr_personnel_certificate extends controller_base_action {

	function __construct() {
		$this->objName = "certificate";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * 跳转到人事管理-基础信息-资格证书列表
	 */
    function c_page() {
      $this->view('list');
    }


	/**
	 * 跳转到人事管理-基础信息-资格证书-个人列表
	 */
    function c_toPersonnelList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }


	/**
	 * 跳转到人事管理-基础信息-资格证书-个人列表(新增，修改)
	 */
    function c_toEidtList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-editlist');
    }

   /**
	 * 跳转到新增人事管理-基础信息-资格证书页面
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao=new model_common_otherdatas();     //新建otherdatas对象
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
     	$this->view ( 'add' );
   }
   /**
	 * 跳转到新增人事管理-基础信息-资格证书页面
	 */
	function c_toMyAdd() {
	  	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
	  	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
	  	$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
	  	$this->assign ( 'userNo', $userNo );
	  	$this->assign ( 'userAccount', $userAccount );
	  	$this->assign ( 'userName', $useName );
	     $this->view ( 'my-add' );
   }

   /**
	 * 跳转到编辑人事管理-基础信息-资格证书页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//显示附件信息
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看人事管理-基础信息-资格证书页面
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

		$title = '资格证书信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * 导出excel
	 */
	 function c_toExcelOut(){
		$this->view('excelout');
	}

	/*
	 * 导出excel
	 */
	 function c_excelOut(){
	 	$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['DateBegin']))
	 		$this->service->searchArr['DateBegin'] = $object['DateBegin'];
		if(!empty($object['DateEnd']))
	 		$this->service->searchArr['DateEnd'] = $object['DateEnd'];
		if(!empty($object['userNo']))
			$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['certifying']))
	 		$this->service->searchArr['certifyingSearch'] = $object['certifying'];
		if(!empty($object['certificates']))
			$this->service->searchArr['certificatesSearch'] = $object['certificates'];
		if(!empty($object['level']))
			$this->service->searchArr['level'] = $object['level'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['certificates']=$planEquRows[$key]['certificates'];
			$exportData[$key]['level']=$planEquRows[$key]['level'];
			$exportData[$key]['certifying']=$planEquRows[$key]['certifying'];
			$exportData[$key]['certifyingDate']=$planEquRows[$key]['certifyingDate'];
			$exportData[$key]['fujian']=$planEquRows[$key]['fujian'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
		}
		return $this->service->excelOut ( $exportData );
	}

		  	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['certificate']['listSql']))));
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
		include_once ("model/hr/personnel/certificateFieldArr.php");
		if(is_array($_POST['certificate'])){
			foreach($_POST['certificate'] as $key=>$val){
					foreach($certificateFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
//		print_r($_POST['contract']);
//		print_r($societyFieldArr);
		$newColArr=array_combine($_POST['certificate'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['certificate']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutCertificate($newColArr,$dataArr);
	}

	/******************* E 导入导出系列 ************************/
 }
?>