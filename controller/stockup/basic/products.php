<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:05:02
 * @version 1.0
 * @description:备货产品信息表控制层
 */
class controller_stockup_basic_products extends controller_base_action {

	function __construct() {
		$this->objName = "products";
		$this->objPath = "stockup_basic";
		parent::__construct ();
	 }

	/**
	 * 跳转到备货产品信息表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增备货产品信息表页面
	 */
	function c_toAdd() {
     $this->view ( 'add',true);
   }
	/**
	 * 新增提交验证
	 */
	 function c_add($isAddInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
            msg("保存成功");
		} else {
			msg("保存失败");
		}
	}
   /**
	 * 跳转到编辑备货产品信息表页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit',true);
   }
   /**
    * 编辑提交验证
    */
    function c_edit(){
    	$this->checkSubmit(); //验证是否重复提交
    	$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if ($rs) {
            msg("保存成功");
		} else {
			msg('保存失败');
		}
    }
     /**
	 * 弹出表格
	 */
   	function c_pageSelect(){
   		$this->view('listselect');
	}
	/**
	 * 关闭开启
	 */
	 function c_updateStatus(){
	   	if($_POST ['id']&&$_POST ['flag']){
	   		if($this->service->updateObjStatus((int)$_POST ['id'],'isClose',(int)$_POST ['flag'])==true){
	   			echo 1;
	   		}else{
	   			echo 2;
	   		}
	   	}
   }
   /**
	 * 表格方法
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //设置前台获取的参数信息
		$projectName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] :  $_POST['productName'];
		$this->service->searchArr['productSearch'] = $projectName;
		$rows = $service->pageBySqlId('pageSelect');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
   /**
	 * 跳转到查看备货产品信息表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			if($key=='isClose'&&$val=='1'){
				$this->assign ( $key, '开启' );
			}elseif($key=='isClose'&&$val=='2'){
				$this->assign ( $key, '关闭' );
			}else{
				$this->assign ( $key, $val );
			}

		}
      $this->view ( 'view' );
   }
         /**
		 * @ ajax判断项
		 *
		 */
	    function c_ajaxProductName() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['productName'] ) ? $_GET ['productName'] : false;

			$searchArr = array ("productName" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
		/**
		 * @ ajax判断项
		 *
		 */
	    function c_ajaxProductCode() {
	    	$service = $this->service;
			$projectName = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : false;

			$searchArr = array ("productCode" => $projectName );

			$isRepeat =$service->isRepeat ( $searchArr, "" );

			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}


	/*********************** 日志导入 **********************/
	/**
	 * 日志导入
	 */
	function c_toEportExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 日志导入
	 */
	function c_eportExcelIn(){
		$resultArr = $this->service->eportExcelIn_d ();
		$title = '备货导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/*********************** 日志导出 **********************/
	/**
	 * 日志导出
	 */
	function c_toOutExcel(){
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$beginDate = $weekBEArr['beginDate'];
		$endDate = $weekBEArr['endDate'];
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',$endDate);
		$this->assign('deptIds',$_GET['deptIds']);
		$this->view('outExcel');
	}

	/**
	 * 日志导出方法
	 */
	function c_exportSearhDeptJson(){
		set_time_limit(0);
		$service = $this->service;
		$deptIds = $_GET['deptIds'];
		unset($_GET['deptIds']);
		if(!empty($deptIds) && !empty($_GET['deptId'])){//判断传入权限
			$_GET['deptId'] = implode(',',array_intersect(explode(',',$_GET['deptId']),explode(',',$deptIds)));
		}elseif(!empty($deptIds) && empty($_GET['deptId'])){
			$_GET['deptId'] = $deptIds;
		}
//		print_r($_GET);die();
		$service->getParam ( $_GET );
		$service->sort = 'c.executionDate asc,c.deptId asc,c.createId';
		$rows = $service->list_d ();
		//定义表头
		$thArr = array('createName' => '填写人','executionDate' => '日期','projectCode' => '项目编号',
			'activityName' => '任务', 'workloadDay' => '工作量', 'workloadUnitName' => '单位', 'thisActivityProcess' =>'任务进展%',
			'thisProjectProcess' => '项目进展%', 'processCoefficient' => '进展系数',
			'inWorkRate' => '人工投入占比%', 'workCoefficient' => '工作系数', 'costMoney' => '费用', 'description' => '情况描述',
			'assessResultName' => '考核结果', 'feedBack' => '回复', 'deptName' =>'部门'
		);

		model_engineering_util_esmexcelutil::exportSearchDept($thArr,$rows,'部门日志');
	}

 }
?>