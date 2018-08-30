<?php
/**
 * @author Administrator
 * @Date 2013年10月9日 9:41:20
 * @version 1.0
 * @description:生产能力表控制层
 */
class controller_report_report_produce extends controller_base_action {

	function __construct() {
		$this->objName = "produce";
		$this->objPath = "report_report";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产能力表列表
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * 查看列表
     */
    function c_viewlist(){

    	return model_contract_common_contExcelUtil :: produceViewList();
//    	$this->view('viewlist');
    }

   /**
	 * 跳转到新增生产能力表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑生产能力表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看生产能力表页面
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
	 * 上传EXCEL
	 */
	function c_upExcel() {
		set_time_limit(0);

		$uploaddir = WEB_TOR . 'upfile/';
		$uploadfile = $uploaddir. $_FILES['inputExcel']['name'];
		print "<pre>";
		if (move_uploaded_file($_FILES['inputExcel']['tmp_name'], $uploaddir . $_FILES['inputExcel']['name'])) {
		   msgGo("上传成功", '?model=report_report_produce');
		} else {
		    print "失败败败败败";
		    print_r($_FILES);
		}
		print "</pre>";


	}
 }
?>