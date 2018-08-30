<?php
/**
 * @author Show
 * @Date 2012年1月5日 星期四 10:00:48
 * @version 1.0
 * @description:测试卡信息(oa_cardsys_cardsinfo)控制层
 */
class controller_cardsys_cardsinfo_cardsinfo extends controller_base_action {

	function __construct() {
		$this->objName = "cardsinfo";
		$this->objPath = "cardsys_cardsinfo";
		parent::__construct ();
	 }

	/*
	 * 跳转到测试卡信息(oa_cardsys_cardsinfo)列表
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * 个人测试卡列表
     */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

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
		echo util_jsonUtil::encode ( $arr );
	}

    /*
	 * 跳转到测试卡使用记录列表
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }

    /*
	 * 跳转到测试卡使用记录列表
	 */
    function c_pageForProjectView() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforprojectview');
    }

    /**
	 * 跳转到新增测试卡信息(oa_cardsys_cardsinfo)页面
	 */
	function c_toAdd() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('userName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
		$this->showDatadicts ( array ('cardType' => 'GCXMCSK' ));
     	$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑测试卡信息(oa_cardsys_cardsinfo)页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('cardType' => 'GCXMCSK' ),$obj['cardType']);
		$this->view ( 'edit');
	}

   	/**
	 * 跳转到查看测试卡信息(oa_cardsys_cardsinfo)页面
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
     * 跳转释放测试卡
     */
    function c_toReleaseCar(){
    	$this->assign ( 'ownerId', $_SESSION['USER_ID'] );
		$this->view ( 'releasecar' );
    }

	 /**
	  * 释放测试卡操作
	  */
     function c_releaseCar(){
    	$objs = $_POST [$this->objName];
    	$obj = $objs['release'];
		if ($this->service->releaseCar_d ($obj)) {
			msg ( '编辑成功！' );
		}

	}
}
?>