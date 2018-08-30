<?php
/**
 * @author Administrator
 * @Date 2013年5月6日 星期一 13:34:00
 * @version 1.0
 * @description:离职审批交接清单控制层
 */
class controller_hr_leave_handitem extends controller_base_action {

	function __construct() {
		$this->objName = "handitem";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职审批交接清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增离职审批交接清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑离职审批交接清单页面
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
	 * 跳转到查看离职审批交接清单页面
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
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>