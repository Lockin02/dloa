<?php
/**
 * @author Administrator
 * @Date 2011��11��23�� 9:51:30
 * @version 1.0
 * @description:�䶯��¼���Ʋ�
 */
class controller_asset_change_assetchange extends controller_base_action {

	function __construct() {
		$this->objName = "assetchange";
		$this->objPath = "asset_change";
		parent::__construct ();
	 }

	/*
	 * ��ת���䶯��¼
	 */
    function c_page() {
    	$this->assign('assetId',$_GET['assetId']);
    	$this->assign('assetCode',$_GET['assetCode']);
      $this->view('list');
    }

	/**
	 * ��Ƭ�䶯��¼
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $service->pageBySqlId ("select_changeRecord");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * ��ת���䶯��¼
	 */
    function c_changeRecordPage() {
      $this->view('record');
    }

	/**
	 * ��Ƭ�䶯��¼
	 */
	function c_changeRecordJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->pageBySqlId ("select_changeRecord");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

 }
?>