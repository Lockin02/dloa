<?php
/**
 * @author Administrator
 * @Date 2012-12-24 14:48:23
 * @version 1.0
 * @description:销售负责人管理控制层
 */
class controller_system_saleperson_saleperson extends controller_base_action {

	function __construct() {
		$this->objName = "saleperson";
		$this->objPath = "system_saleperson";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售负责人管理列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增销售负责人管理页面
	 */
	function c_toAdd() {
	 $this->assign('businessBelong',$_SESSION['COM_BRN_PT']);
	 $this->assign('businessBelongName',$_SESSION['COM_BRN_CN']);
	 $this->assign('formBelong',$_SESSION['COM_BRN_PT']);
     $this->assign('formBelongName',$_SESSION['COM_BRN_CN']);
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑销售负责人管理页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $obj['customerType']);
		$this->view ( 'edit');
   }

   /**
	 * 跳转到查看销售负责人管理页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "否";
		}else{
			$obj['isDirector'] = "是";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
   }
   /*
    * 查看--根据负责人
    */

   function c_toViewall() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "否";
		}else{
			$obj['isDirector'] = "是";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->assign("ids",$_GET['ids']);
		$this->view ( 'viewall' );
   }
   /*
    * 编辑--根据负责人
    */

   function c_toEditall() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isDirector'] == '0'){
			$obj['isDirector'] = "否";
		}else{
			$obj['isDirector'] = "是";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->assign("ids",$_GET['ids']);
		$this->view ( 'editall' );
   }
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
	/**
	 * 修改对象-- 根据负责人
	 */
	function c_editall($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->editall_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}


  /**
   * 合并查看列表
   */
  function c_mergeList(){
     $this->view("mergelist");
  }

	/**
	 * 获取分页数据转成Json
	 */
	function c_mergeJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$service->groupBy = 'c.personName,c.businessBelongName';
		$service->searchArr['isUse'] = "0";
		$rows = $service->pageBySqlId('select_merge');
	    //处理合并后的数据
	   foreach($rows as $key => $val){
	   	 $rows[$key]['country'] = implode(",",array_flip(array_flip(explode(",",$val['country']))));
	   	 $rows[$key]['province'] = implode(",",array_flip(array_flip(explode(",",$val['province']))));
	   	 $rows[$key]['city'] = implode(",",array_flip(array_flip(explode(",",$val['city']))));
	   	 $rows[$key]['customerTypeName'] = implode(",",array_flip(array_flip(explode(",",$val['customerTypeName']))));
	   }
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
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
	 * 根据省份 - 城市 - 客户类型确认费用归属人
	 */
	function c_getSalePerson(){
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);
		$city = util_jsonUtil::iconvUTF2GB($_POST['city']);
		$customerTypeName = util_jsonUtil::iconvUTF2GB($_POST['customerTypeName']);

		//获取费用归属人
		$rs = $this->service->getDirector_d($province,$city,$customerTypeName);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
		exit();
	}
	/*
	 * ajax 根据负责人删除数据
	 */
	function c_ajaxdeletesPerson() {
		try {
			$this->service->deletesAll_d ( $_POST ['personId'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 根据省份 - 单个 查询销售负责人
	 */
	function c_getPersonByProvince(){
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);

		//获取费用归属人
		$rs = $this->service->getPersonByProvince_d($province);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
		exit();
	}

/*************************导入***********************************************/

	/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}
	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		$objNameArr = array (
		    0 => 'areaName', //区域负责人
			1 => 'personName', //负责人名称
			2 => 'deptName', //负责人部门
			3 => 'country', //国家
			4 => 'province', //省份
			5 => 'city', //城市
			6 => 'businessBelongName',//归属公司
			7 => 'customerTypeName', //客户类型
			8 => 'isUse', //是否启用
			9 => 'isDirector' //行业总监
		);
		$this->c_addExecel($objNameArr);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecel($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式

			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$isUseArr = array("启用"=>"0","禁用"=>"1");//是否启用
				$isDirectorArr = array("否"=>"0","是"=>"1");//是否启用
				$arrinfo = array();//导出数据
                //循环处理插入数组
                foreach($objectArr as $key => $val){
                   $personArr = $this->service->userArr($val['personName']);
                   $DirectorArr = $this->service->userArr($val['areaName']);
                    if(empty($personArr)){
                      array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,销售负责人不存在" ) );
                    }else{
                        $objectArr[$key]['personId'] = $personArr['USER_ID'];
                        $objectArr[$key]['deptName'] = $personArr['DEPT_NAME'];
                        $objectArr[$key]['deptId'] = $personArr['DEPT_ID'];

                        $objectArr[$key]['areaNameId'] = $personArr['USER_ID'];

                        //判断国家
                        $countryId = $this->service->isArea("country",$val['country']);
                        if(empty($countryId)){
                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,国家错误" ) );
                        }else{
                        	$objectArr[$key]['countryId'] = $countryId;
                        	$provinceId = $this->service->isArea("province",$val['province']);
	                        if(empty($provinceId) && $val['province'] != "全国"){
	                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,省份错误" ) );
	                        }else{
	                        	$objectArr[$key]['provinceId'] = $provinceId;
	                        	$cityId = $this->service->isArea("city",$val['city'],$provinceId);
		                        if(empty($cityId)  && $val['city'] != "全国"){
		                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,城市错误" ) );
		                        }else{
		                        	if($val['city'] == "全部"){
		                        		$objectArr[$key]['city'] = $this->service->allCity($cityId);
		                        	}
		                        	$objectArr[$key]['cityId'] = $cityId;
		                        	//客户类型
		                        	$customerType = $this->service->customerTypeStr($val['customerTypeName']);
			                        if(empty($customerType)){
				                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,客户类型错误" ) );
				                    }else{
				                    	$objectArr[$key]['customerType'] = $customerType;

				                    	$obj = $this->service->businessBelongNameStr($val['businessBelongName']);
				                    	 $businessBelongName = $obj['NameCN'];
				                    	 $businessBelong = $obj['NamePT'];
				                    	if(empty($businessBelongName)){
				                    		array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败,归属公司不存在" ) );
				                    	}
				                    	else{
				                    		$objectArr[$key]['businessBelongName']= $businessBelongName;
				                    		$objectArr[$key]['businessBelong'] = $businessBelong;
				                    		$isUse = $val['isUse'];
				                    		$isDirector = $val['isDirector'];
				                    		$objectArr[$key]['isUse'] = $isUseArr[$isUse];
				                    		$objectArr[$key]['isDirector'] = $isDirectorArr[$isDirector];

				                    		$customerTypeNameStr = explode(",", $val['customerTypeName']);
				                    		$customerTypeNames = "";
				                    		foreach ($customerTypeNameStr as $k =>$v){
				                    			$customerTypeNames .= "'".$v."',";
				                    		}
				                    		$customerTypeNames = rtrim($customerTypeNames, ",");
	                                       //判断是否有重复数据
							       	 		$sql = "select count(*) as num from oa_system_saleperson where " .
							       	 			   "province = '".$val['province']."' and city = '".$val['city']."'" .
							       	 			   " and customerTypeName in (".$customerTypeNames.")".
							       	 			   " and businessBelongName = '".$val['businessBelongName']."'";
							       	 	   $isRepeatArr = $this->service->_db->getArray($sql);

							             if($isRepeatArr[0]['num'] != '0' && $val['province'] != "全国" && $val['city'] != "全国" ){
							             	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败，存在相同的省/市/类型/公司" ) );
							             }else{
							             	$add = $this->service->importAdd_d($objectArr[$key]);
					                        if($add){
					                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入成功！" ) );
					                        }else{
					                        	array_push ( $arrinfo, array ("orderCode" => $val['personName'],"cusName" => $val['province']."(".$val['city'].")","result" => "导入失败！" ) );
					                        }
							              }
				                    	}
				                    }
		                        }
	                        }
                        }
                    }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "导入结果", array ("负责人名称","省（市）", "结果" ) );
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}

/*************************导入******end*****************************************/
 }
?>