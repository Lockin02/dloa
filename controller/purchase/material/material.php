<?php
/**
 * @author Show
 * @Date 2013年12月10日 星期二 17:12:46
 * @version 1.0
 * @description:物料协议价信息控制层
 */
class controller_purchase_material_material extends controller_base_action {

	function __construct() {
		$this->objName = "material";
		$this->objPath = "purchase_material";
		parent::__construct ();
	 }

	/**
	 * 跳转到物料协议价信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增物料协议价信息页面
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('protocolTypeCode' => 'XYLL' )); //协议类型
		$this->assign("createName",$_SESSION['USERNAME']);
		$this->assign("createId",$_SESSION['USER_ID']);
		$this->assign("createTime",date("Y-m-d H:i:s"));
		$this->view ( 'add' );
   }

   /**
	 * 跳转到编辑物料协议价信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('protocolTypeCode' => 'XYLL' ) ,$obj['protocolTypeCode']); //协议类型
		$this->view ( 'edit');
	}

   /**
	 * 跳转到查看物料协议价信息页面
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
    * 重写add
    */
	function c_add(){
		$this->permCheck (); //安全校验
		$materialId = $this->service->add_d($_POST['material']);
		if($materialId){
			msg ( '保存成功！' );
		}else{
			msg ( '保存失败！' );
		}
	}

	/*
	 * 删除
	 */
	function c_ajaxdeletes() {
		try {
			$flag = $this->service->deletes_d ( $_POST ['id'] );
			echo $flag;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

 }
?>