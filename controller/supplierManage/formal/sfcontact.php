<?php
/**
 * @description: 供应商正式库联系人控制层类
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_formal_sfcontact extends controller_base_action{
  function __construct() {
	$this->objName = "sfcontact";
	$this->objPath = "supplierManage_formal";
	parent::__construct();
  }
  	/**
	 * @desription 跳转到查看页面
	 */
 	function c_toRead () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}
/*
 * 正式库联系人列表数据获取
 */
	function c_pageJson () {
		$service = $this->service;
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET['parentId'] : null;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$this->searchVal( 'name');
		$rows = $this->service->conInSupp($parentId);
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
}
/*
 *跳转到正式库联系人列表
  */
	function c_tosfconlist () {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//安全校验
		$this->show->assign('parentId',$_GET['parentId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
/*
 * 跳转到编辑功能联系人修改列表页面
 */

	 function c_toConEdit () {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//安全校验
	 	$this->show->assign('parentCode',$_GET['parentCode']);
//	 	echo $_GET['parentCode'];
		$this->show->assign('parentId', $_GET ['parentId']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-conE' );
	}
  	/**
	 * @desription 跳转到添加页面
	 */
 	function c_toAdd () {
		$sysCode = generatorSerial();
		$objCode = generatorSerial();

		$this->show->assign ( 'parentCode', $_GET['parentCode'] );
		$this->show->assign ( 'parentId', $_GET['parentId'] );
		$this->show->assign('objCode',$objCode);
		$this->show->assign('systemCode',$sysCode);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
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
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}
	/**
     * 修改供应商添加联系人信息保存
	 * @date 2010-9-20 下午02:06:22
	 */
	 	function c_addcontact() {
		$con = $_POST [$this->objName];
        if($_GET['id']){
        	$linkman['createId'] = $_SESSION['USER_ID'];
        	$linkman['createName'] = $_SESSION['USERNAME'];
        	$linkman ['defaultContact'] = $_SESSION ['USERNAME'];
        }
		$id = $this->service->add_d ( $con, true );

		if ($id) {
			msg( '新增联系人成功！' );
		}

	}
	/**
	 *获取第一个供应商联系人
	 *
	 */
	function c_getLinkmanInfo(){
		$suppId=isset($_POST['suppId'])?$_POST['suppId']:"";
		$rows=$this->service->conInSupp($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows['0'] );
		}else{
			echo "";
		}
	}
}
?>
