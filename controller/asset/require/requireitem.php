<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 11:41:42
 * @version 1.0
 * @description:资产需求申请明细控制层
 */
class controller_asset_require_requireitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }

    /**
	 * 跳转到资产需求申请明细列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增资产需求申请明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑资产需求申请明细页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看资产需求申请明细页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   
	/**
	 * 获取所有数据返回json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 下达采购申请时获取所有数据返回json
	 */
	function c_requireJsonApply() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->requireJsonApply_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 下达物料转资产申请时获取所有数据返回json
	 */
	function c_requireinJsonApply() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->requireinJsonApply_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_pageByRequireJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );}
 }
?>