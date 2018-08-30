<?php
/**
 * @author Administrator
 * @Date 2011年11月23日 9:51:30
 * @version 1.0
 * @description:变动记录控制层
 */
class controller_asset_change_assetchange extends controller_base_action {

	function __construct() {
		$this->objName = "assetchange";
		$this->objPath = "asset_change";
		parent::__construct ();
	 }

	/*
	 * 跳转到变动记录
	 */
    function c_page() {
    	$this->assign('assetId',$_GET['assetId']);
    	$this->assign('assetCode',$_GET['assetCode']);
      $this->view('list');
    }

	/**
	 * 卡片变动记录
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $service->pageBySqlId ("select_changeRecord");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * 跳转到变动记录
	 */
    function c_changeRecordPage() {
      $this->view('record');
    }

	/**
	 * 卡片变动记录
	 */
	function c_changeRecordJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->pageBySqlId ("select_changeRecord");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>