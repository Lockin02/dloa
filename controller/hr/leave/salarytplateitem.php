<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 15:29:33
 * @version 1.0
 * @description:工资清单模板清单控制层 
 */
class controller_hr_leave_salarytplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "salarytplateitem";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到工资清单模板清单列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增工资清单模板清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑工资清单模板清单页面
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
	 * 跳转到查看工资清单模板清单页面
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
	* 获取生产清单editgrid数据
	*/
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>