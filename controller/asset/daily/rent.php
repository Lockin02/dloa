<?php

/**
 * 资产租赁控制层类
 *  @author chenzb
 */
class controller_asset_daily_rent extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "rent";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}
	/**
	 * 跳转到用信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
  /**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	/**
	 * 初始化对象
	 */
	function c_init() {
		//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if(isset($_GET['btn'])){
				$this->assign('showBtn',1);
			}else{
				$this->assign('showBtn',0);
			}

		foreach ( $obj as $key => $val ) {
				$this->show->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			//echo"1111";
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

/**
	  * ajax删除租赁主表及租赁清单从表信息
	  */
	  function c_deletes(){
		$message = "";
		try {
            $rentObj = $this->service->get_d ( $_GET ['id'] );
			$rentitemDao = new model_asset_daily_rentitem();
	  		$condition = array(
	  			'rentId'=>$rentObj['id']
	  		);
	  		$rentitemDao->delete($condition);
			$this->service->deletes_d ( $_GET ['id'] );

			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}

		msg('删除成功！');


	  }



}
?>