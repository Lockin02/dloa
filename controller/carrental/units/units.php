<?php
/**
 * @author Show
 * @Date 2011年12月25日 星期日 14:36:05
 * @version 1.0
 * @description:租车单位(oa_carrental_units)控制层
 */
class controller_carrental_units_units extends controller_base_action {

	function __construct() {
		$this->objName = "units";
		$this->objPath = "carrental_units";
		parent::__construct ();
	 }

	/*
	 * 跳转到租车单位(oa_carrental_units)列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增租车单位(oa_carrental_units)页面
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('unitNature' => 'DWXZ' ));//使用状态 -- 数据字典
     	$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑租车单位(oa_carrental_units)页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
			$this->showDatadicts ( array ('unitNature' => 'DWXZ' ), $obj ['unitNature'] );
		$this->view ( 'edit');
   }

   /**
	 * 跳转到查看租车单位(oa_carrental_units)页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'unitNature', $this->getDataNameByCode ( $obj ['unitNature'] ));
			$this->view ( 'view' );
   }

   	/**
	 * 查看页面Tab
	 */
	function c_viewTab() {
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}


	/**
	 *
	 * 上传租车单位信息EXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	 /**
	 * 导入EXCEL中上传到查看租车单位(oa_carrental_units)页面
	 */
	function c_importUnits() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_carrental_units_importUnitsUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importUnitsInfo ( $excelData );

			if (is_array ( $resultArr )){
					$title = '出租单位导入结果列表';
					$thead = array( '数据信息','导入结果' );
					echo util_excelUtil::showResult ( $resultArr,$title,$thead );
				}else{
					echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
				}

		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
 }
?>