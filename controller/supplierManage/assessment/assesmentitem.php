<?php
/**
 * @author Administrator
 * @Date 2012年1月11日 16:58:43
 * @version 1.0
 * @description:供应商评估明细控制层
 */
class controller_supplierManage_assessment_assesmentitem extends controller_base_action {

	function __construct() {
		$this->objName = "assesmentitem";
		$this->objPath = "supplierManage_assessment";
		parent::__construct ();
	 }

	/*
	 * 跳转到供应商评估明细列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增供应商评估明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑供应商评估明细页面
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
	 * 跳转到查看供应商评估明细页面
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
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ("select_schemeItem");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * 获取所有数据返回json(个人评分)
     */
    function c_assesListJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->searchArr ['assesManId'] = $_SESSION ['USER_ID'];
        $service->sort='id';
        $service->asc=false;
        $rows = $service->list_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }
 }
?>