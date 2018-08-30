<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:51:20
 * @version 1.0
 * @description:供应商资质证书控制层
 */
class controller_outsourcing_supplier_certify extends controller_base_action {

	function __construct() {
		$this->objName = "certify";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * 跳转到供应商资质证书列表
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * 跳转到供应商资质证书信息列表
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * 跳转到供应商资质证书信息列表
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * 跳转到新增供应商资质证书页面
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //获取供应商信息
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
		$this->showDatadicts ( array ('typeCode' => 'WBZZZSLX' ));
    	 $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑供应商资质证书页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('typeCode' => 'WBZZZSLX' ),$obj['typeCode']);
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],true ));
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看供应商资质证书页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['certifyLevel']=="V"){
			$this->assign ( 'certifyLevel', '有' );
		}else if($obj['certifyLevel']=="X"){
			$this->assign ( 'certifyLevel', '无' );
		}else{
			$this->assign ( 'certifyLevel', '' );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
      $this->view ( 'view' );
   }
 }
?>