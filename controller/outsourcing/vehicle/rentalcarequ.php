<?php
/**
 * @author Michael
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理从表控制层
 */
class controller_outsourcing_vehicle_rentalcarequ extends controller_base_action {

	function __construct() {
		$this->objName = "rentalcarequ";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车的申请与受理从表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增租车的申请与受理从表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑租车的申请与受理从表页面
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
	 * 跳转到查看租车的申请与受理从表页面
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
    * 根据主表ID查询供应商的ID并以字符串形式返回
    */
	function c_getSuppByParent() {
		$obj = $this->service->findAll(array('parentId'=>$_POST['parentId']) ,'' ,'suppId');
		foreach ( $obj as $key => $val ) {
			$idArr[$key] = $val['suppId'];
		}
		$ids = implode(',' ,$idArr);
		echo $ids;
	}
 }
?>