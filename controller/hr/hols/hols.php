<?php
/**
 * @author Administrator
 * @Date 2012年8月25日 星期六 10:54:13
 * @version 1.0
 * @description:考勤控制层
 */
class controller_hr_hols_hols extends controller_base_action {

	function __construct() {
		$this->objName = "hols";
		$this->objPath = "hr_hols";
		parent::__construct ();
	 }

	/**
	 * 跳转到考勤列表
	 */
    function c_page() {
      $this->assign('UserId',$_GET['userNo']);
      $this->view('list');
    }

   /**
	 * 跳转到新增考勤页面
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑考勤页面
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
	 * 跳转到查看考勤页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$personnelInfo = $this->service->getPersonnelByUserId($obj['UserId']);
		$this->assign ('userNo', $personnelInfo['userNo']);
		$this->assign ('userName', $personnelInfo['userName']);
		$this->assign ('companyName', $personnelInfo['companyName']);
		$this->assign ('deptNameS', $personnelInfo['deptNameS']);
		$this->assign ('deptNameT', $personnelInfo['deptNameT']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>