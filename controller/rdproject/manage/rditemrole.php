<?php
/**
 * @description: 项目角色的类
 * @date 2010-9-28 上午10:35:02
 */
class controller_rdproject_manage_rditemrole extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-9-28 上午10:37:07
	 */
	function __construct () {
		$this->objName = "rditemrole";
		$this->objPath = "rdproject_manage";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/
	/*
	 * “项目角色”的显示列表方法
	 */
	function c_itemrolelist(){
		$service = $this->service;
		//搜索条件
//		$service->getParam($_GET);//设置前台获取的参数信息
		//分页
		$auditArr = array(
			"createId" => $_SESSION['USER_ID']
		);
//		$service->__SET("searchArr",$auditArr);

		//过滤条件
		if(!isset($_GET['itemType'])){
			$_GET['itemType'] = null;
		}
		$rows = $service->page_d( $_GET['itemType'] );

		$this->pageShowAssign();

		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->show->assign('list',$service->showitemrolelist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * @desription 通过<select>传过来的值对页面数据进行过滤
	 * @param tags
	 * @date 2010-9-29 上午10:01:23
	 */
	function c_rolefilterlist () {
		$service = $this->service;
		//专门用于过滤的SQL语句
		$sql = "select_filterrole";
//		$rows = $service->filterpage_d($_GET['itemType'],$sql);
		$rows = $service->filterpage_d($sql);

//		echo "<pre>";
//		print_r($rows);
		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->show->assign('list',$service->showitemrolelist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * @desription 跳转到角色增加页面
	 * @param tags
	 * @date 2010-9-28 下午02:28:05
	 */
	function c_toaddrole () {
		$service = $this->service;
		$this->showDatadicts(array('itemType' => 'YFXMGL'));
		$this->showDatadicts(array('ExRole'=>'XMJS'));
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/**
	 * @desription 角色增加函数
	 * @param tags
	 * @date 2010-9-28 下午06:39:37
	 */
	function c_addrole () {
		$roleid = $this->service->addrole_d($_POST [$this->objName],true);
//		echo "<pre>";
//		print_r($roleid);
		if($roleid){
			msg('添加角色成功');
		}
	}

	/**
	 * @desription 跳转到角色权限管理页面
	 * @param tags
	 * @date 2010-9-28 下午02:30:46
	 */
	function c_tolimitrole () {
		$service = $this->service;
		$this->showDatadicts(array('itemRole'=>'XMJS'));
		$this->show->display($this->objPath . '_' . $this->objName . '-limit');
	}

	/**
	 * @desription 基本权限显示页面
	 * @param tags
	 * @date 2010-9-28 下午04:25:22
	 */
	function c_basiclimit () {
		$this->show->display($this->objPath . '_' . 'rditemrolebasic');
	}

	/**
	 * @desription 文档权限显示页面
	 * @param tags
	 * @date 2010-9-28 下午04:27:16
	 */
	function c_filelimit () {
		$this->show->display($this->objPath . '_' . 'rditemrolefile');
	}




	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/



}


?>