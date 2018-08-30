<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:08:18
 * @version 1.0
 * @description:供应商人员信息控制层
 */
class controller_outsourcing_supplier_personnel extends controller_base_action {

	function __construct() {
		$this->objName = "personnel";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到供应商人员信息列表
	 */
    function c_page() {
      $this->view('list');
    }
    /**
	 * 跳转到供应商外包人员信息列表
	 */
    function c_toPageList() {
		$suppCode = $_SESSION['USER_ID'];
		$suppId = $this->service->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$suppCode."'", 'id');
		if($suppId){
		$this->assign('suppCode',$suppCode);
		$this->assign('suppId',$suppId);
		$this->view('page-list');
		}
		else
			msg("非外包供应商人员！");
    }

	/**
	 * 跳转到供应商人员信息列表
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * 跳转到供应商人员信息列表
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * 跳转到新增供应商人员信息页面
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //获取供应商信息
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
//		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ));
//		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ));

    	 $this->view ( 'add' );
   }

      /**
	 * 跳转到新增供应商人员信息页面
	 */
	function c_toListAdd() {
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ));
		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ));

    	 $this->view ( 'list-add' );
   }

   /**
	 * 跳转到编辑供应商人员信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ),$obj['highEducation']);
		$this->showDatadicts ( array ('levelCode' => 'WBRYJB' ),$obj['levelCode']);
		$this->showDatadicts ( array ('skillTypeCode' => 'WBJNLX' ),$obj['skillTypeCode']);
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看供应商人员信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

   /**
    * 跳转到导出供应商人员页面
    */
    function c_toExcellOut() {
    	$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ));
		$this->view( 'excellout' );
    }

   /**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

      	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
		if ($id) {
			msg ( $msg );
		}
	}

		 /**
	 *信息修改
	 *
	 */
	 function c_edit(){
		$flag=$this->service->edit_d( $_POST [$this->objName]);
		if($flag){
			msgGo('保存成功');
		}else{
			msgGo('保存失败');

		}
	 }

	 	/**
	 * 导入excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();
		
		$title = '人员信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 导出excell
	 */
	 function c_excellOut() {
	 	set_time_limit(0);
	 	$formData = $_POST[$this->objName];
	 	if(!empty($formData['suppName'])) //外包供应商
			$this->service->searchArr['suppName'] = $formData['suppName'];

		if(!empty($formData['userName'])) //姓名
			$this->service->searchArr['userName'] = $formData['userName'];

		if(!empty($formData['age'])) //年龄
			$this->service->searchArr['age'] = $formData['age'];

		if(!empty($formData['mobile']))   //联系电话
			$this->service->searchArr['mobile'] = $formData['mobile'];

		if(!empty($formData['email']))  //邮箱
			$this->service->searchArr['email'] = $formData['email'];

		if(!empty($formData['highEducation'])) {   //学历
			$tmp_highEducation = $formData['highEducation'];
			for ($i = 0; $i < count($tmp_highEducation); $i++) {
				$tmp_highEducation[$i] = "'".$tmp_highEducation[$i]."'";
			}
			$this->service->searchArr['xueli'] = substr(implode(',' ,$tmp_highEducation),1,-1);
	 	}

		if(!empty($formData['highSchool'])) //毕业学校
			$this->service->searchArr['highSchool'] = $formData['highSchool'];

		if(!empty($formData['professionalName'])) //专业
			$this->service->searchArr['professionalName'] = $formData['professionalName'];

		if(!empty($formData['identityCard'])) //身份证号
			$this->service->searchArr['identityCard'] = $formData['identityCard'];

		if(!empty($formData['workBeginDate'])) //开始工作时间
			$this->service->searchArr['workBeginDate'] = $formData['workBeginDate'];

	 	if(!empty($formData['workYears'])) //从事网优工作年限
			$this->service->searchArr['workYears'] = $formData['workYears'];

		if(!empty($formData['tradeList'])) //厂商经验列举
			$this->service->searchArr['csjylj'] = $formData['tradeList'];

		if(!empty($formData['certifyList'])) { //所获资质
//			$tmp = explode(',', $formData['certifyList']);
//			for($i = 0; $i < count($tmp); $i++) {
//				$sql_tmp = " and certifyList like '%".$tmp[$i]."%') ";
//				$this->service->searchArr['certifyList'] = $tmp[$i];
//			}
//			$sql_tmp = "and  c.certifyList like '%".$tmp[0]."%'";
			$this->service->searchArr['certifyList'] = $formData['certifyList'];
			//var_dump($sql_tmp);exit();
		}

		if(!empty($formData['skillList']))  //技能类型
			$this->service->searchArr['jnlx'] = $formData['skillList'];

		$rows = $this->service->listBySqlId('select_excell');
		
		if (!$rows){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>" .
					"alert('没有记录!');self.parent.tb_remove();".
				 "</script>";
		}

		//表格头部名称数组，为空则使用模版
		$colArr  = array(
//			"suppName"=>"外包供应商",
//			"userName"=>"姓名",
//			"age"=>"年龄",
//			"mobile"=>"联系电话",
//			"email"=>"邮箱",
//			"highEducationName"=>"学历",
//			"highSchool"=>"毕业学校",
//			"professionalName"=>"专业",
//			"identityCard"=>"身份证号",
//			"workBeginDate"=>"开始工作时间",
//			"workYears"=>"从事网优工作年限",
//			"tradeList"=>"厂商经验列举",
//			"certifyList"=>"所获资质",
//			"skillList"=>"技能类型"
		);
		$modelName = '供应商人员信息';
	 	return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr, $rows, $modelName);
	 }
	 /*
	  * 跳转到外包，人员管理导入页面
	  */
	  function c_toExcelImport(){
	  	$this->display('excelImport');
	  }
	  /*
	   * 外包，人员管理导入
	   */
	  function c_excelImport(){
		set_time_limit(0);
		$resultArr = $this->service->excelImport_d ();
		$title = '人员信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	  }
	  /*
	  * 跳转到外包，人员管理导出页面
	  */
	  function c_toExcelExport(){
		$suppName = $this->service->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$_SESSION['USER_ID']."'", 'suppName');
	  	$this->assign ('suppName',$suppName);
	  	$this->view('excelExport');
	  }
 }
?>