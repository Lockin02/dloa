<?php
/**
 * @author Show
 * @Date 2013年8月2日 星期五 14:41:22
 * @version 1.0
 * @description:物料模板配置表控制层 
 */
class controller_stock_template_protemplate extends controller_base_action {

	function __construct() {
		$this->objName = "protemplate";
		$this->objPath = "stock_template";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到物料模板配置表列表
	 */
    function c_page() {
     	$this->view('list');
    }
    
   /**
	 * 跳转到新增物料模板配置表页面
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
   }
   
   /**
    * 新增对象操作
    */
   function c_add($isAddInfo = false) {
	   	$this->checkSubmit();
	   	$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
	   	$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
	   	if ($id) {
	   		msg ( $msg );
   		}
   }
    
   /**
	 * 跳转到编辑物料模板配置表页面
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
	 * 跳转到查看物料模板配置表页面
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
    * 跳转到物料模板页面
    */
   function c_toProModel(){
   		$this->permCheck (); //安全校验
   		$this->view('protemplate');
   }
 }
?>