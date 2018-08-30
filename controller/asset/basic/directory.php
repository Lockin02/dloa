<?php
/**
 * 资产分类控制层类
 * @linzx
 */
class controller_asset_basic_directory extends controller_base_action {

	function __construct() {
		$this->objName = "directory";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//$this->permCheck (); //安全校验
		$methodDao=new model_asset_basic_deprMethod();
		$names=$methodDao->list_d();
		$list=$this->service->showSelectList_d($names);
		$this->assign("listOption",$list);
       $this->view ('add' );
	}

	/*
	 * 跳转到编辑页面
	 * */
	function c_toEdit($isEditInfo = false) {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$methodDao=new model_asset_basic_deprMethod();
		$names=$methodDao->list_d();
		$list=$this->service->showSelectList_d($names);
		$this->assign("listOption",$list);
      	$this->view ('edit' );
	}


	/**
	 * 跳转到资产分类导入页面
	 * @create 2012年1月13日 10:17:25
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * 资产分类导入
	 * @create 2012年1月13日 11:16:32
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'name',
			1 => 'code',
			2 => 'limitYears',
			3 => 'salvage',
			4 => 'unit',
			5 => 'depr',
			6 => 'isDepr'
		); //字段数组
		$resultArr = $this->service->import_d ( $objKeyArr );
	}


	/**
	 * grid列表下拉过滤
	 */
	function c_getSelection(){
		$rows = $this->service->list_d();
		$datas = array();
		foreach( $rows as $key=>$val ){
			$datas[$key]['text']=$val['name'];
			$datas[$key]['value']=$val['id'];
		}
		echo util_jsonUtil::encode ( $datas );
	}

	function c_getRate(){
		$id = $_POST['id'];
		$obj = $this->service->get_d($id);
		echo util_jsonUtil::encode ( $obj );
	}

}
?>