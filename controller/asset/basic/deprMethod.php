<?php
/**
 *
 * 折旧方式控制层类
 * @author fengxw
 *
 */
class controller_asset_basic_deprMethod extends controller_base_action {

	function __construct() {
		$this->objName = "deprMethod";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/*
	 * 跳转到折旧方式
	 */
    function c_page() {
      $this->view( 'list' );
    }

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 跳转到折旧方式导入页面
	 * @create 2012年1月13日 10:17:25
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * 折旧方式导入
	 * @create 2012年1月13日 11:16:32
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'code',
			1 => 'name',
			2 => 'describes',
			3 => 'expression',
			4 => 'remark'
		); //字段数组
		$resultArr = $this->service->import_d ( $objKeyArr );
		$title = '邮寄费用信息导入结果列表';
		$thead = array( '邮寄单号','导入结果' );
//		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


}

?>