<?php
/**
 * @description: 供应商联系人控制层类
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_temporary_stcontact extends controller_base_action{
  function __construct() {
	$this->objName = "stcontact";
	$this->objPath = "supplierManage_temporary";
	parent::__construct();
  }
 /**
 * @desription 跳转到联系人列表页面
 * @param tags
 * @date 2010-11-8 下午02:18:04
 */
	function c_tolinkmanlist () {
		$parentId = isset($_GET['parentId'])?$_GET['parentId'] : null;
		$parentCode = isset($_GET['parentCode'])?$_GET['parentCode'] : null;

		$this->assign('parentId',$parentId);
		$this->assign('parentCode',$parentCode);

		$this->display ( 'list' );
	}
 /**
 * @desription 跳转到联系人看列表页面
 * @param tags
 * @date 2010-11-8 下午02:18:04
 */
	function c_toRdconlist () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
		$parentId = isset($_GET['parentId'])?$_GET['parentId'] : null;
		$parentCode = isset($_GET['parentCode'])?$_GET['parentCode'] : null;
		$this->show->assign('parentId',$parentId);
		$this->show->assign('parentCode',$parentCode);

		$this->show->display ( $this->objPath . '_' . $this->objName . '-Rdconlist' );
	}
 /**
 * @desription 跳转到联系人看列表页面
 * @param tags
 * @date 2010-11-8 下午02:18:04
 */
	function c_toEdconlist () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
		$parentId = isset($_GET['parentId'])?$_GET['parentId'] : null;
		$parentCode = isset($_GET['parentCode'])?$_GET['parentCode'] : null;
		$this->show->assign('parentId',$parentId);
		$this->show->assign('parentCode',$parentCode);

		$this->show->display ( $this->objPath . '_' . $this->objName . '-Edconlist' );
	}
	function c_pageJson() {
		$service = $this->service;
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$this->searchVal('name');
		$service->asc = true;
//		$rows = $service->pageParentId_d ();
		$rows=$service->page_d();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
//		echo "<pre>";
//		print_r($_GET);
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId'] : null;
		$parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode'] : null;

		$this->assign("parentId",$parentId);
		$this->assign("parentCode",$parentCode);

		$sysCode = generatorSerial();
		$objCode = generatorSerial();
		$this->assign('objCode',$objCode);
		$this->assign('systemCode',$sysCode);
		$this->display ( 'add' );
	}
	/**
	 * 跳转到新增页面
	 */
	function c_tordAdd() {
//		echo "<pre>";
//		print_r($_GET);
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId'] : null;
		$parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode'] : null;

		$this->assign("parentId",$parentId);
		$this->assign("parentCode",$parentCode);

		$sysCode = generatorSerial();
		$objCode = generatorSerial();
		$this->assign('objCode',$objCode);
		$this->assign('systemCode',$sysCode);
		$this->display ( 'rdadd' );
	}

	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toEdit () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->display ( 'edit' );
	}
	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toEdEdit () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display ( $this->objPath . '_' . $this->objName . '-Ededit' );
	}
	/**
	 * @desription 跳转到查看页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
 	function c_toRead () {
		$rows = $this->service->get_d ( $_GET ['id'] );
//		echo "<pre>";
//		print_r($rows);
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	function c_toView () {
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$rows = $this->service->get_d ( $parentId );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}


	/**
     * 注册供应商信息保存
	 * @date 2010-9-20 下午02:06:22
	 */
	 function c_addlinkman() {
		$linkman = $_POST [$this->objName];

        if($_GET['id']){
        	$linkman['createId'] = $_SESSION['USER_ID'];
        	$linkman['createName'] = $_SESSION['USERNAME'];
        	$linkman ['defaultContact'] = $_SESSION ['USERNAME'];
        }
		$id = $this->service->add_d ( $linkman, true );

		if ($id) {
			msg( '新增联系人成功！' );
		}

	}
	/**
	 * @desription 跳到下一步
	 * @param tags
	 * @date 2010-11-11 上午10:07:20
	 */
	function c_stGo () {
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$parentCode = isset($_GET['parentCode'])?$_GET['parentCode']:null;
		$this->show->assign("parentId", $parentId );
		$this->show->assign("parentCode", $parentCode );
		$products = $_POST[$this->objName];
		$proInfo->show->display ( $this->objPath . 'stproduct'.'-add');
	}
}
?>
