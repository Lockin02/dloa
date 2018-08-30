<?php
/**
 * @author Show
 * @Date 2012年3月31日 星期六 11:13:43
 * @version 1.0
 * @description:非销售类合同付款申请信息控制层
 */
class controller_contract_otherpayapply_otherpayapply extends controller_base_action {

	function __construct() {
		$this->objName = "otherpayapply";
		$this->objPath = "contract_otherpayapply";
		parent::__construct ();
	}

	/**
	 * 跳转到非销售类合同付款申请信息列表
	 */
    function c_page() {
		$this->view('list');
    }

	/**
	 * 跳转到新增非销售类合同付款申请信息页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

  	/**
	 * 跳转到编辑非销售类合同付款申请信息页面
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
	 * 跳转到查看非销售类合同付款申请信息页面
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
	 * 根据类型和id返回申请对应归属部门id
	 */
	function c_getFeeDeptId(){
		$contractId = $_POST['contractId'];
		$contractType = $_POST['contractType'];
		$obj = $this->service->find(array('contractId' => $contractId ,'contractType' => $contractType),null,'feeDeptId');
//		print_r($obj);
		if(is_array($obj)){
			echo $obj['feeDeptId'];
		}else{
			echo 0;
		}
	}
}
?>