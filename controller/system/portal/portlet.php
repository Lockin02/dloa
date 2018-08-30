<?php

/**
 * portlet控制层
 */
class controller_system_portal_portlet extends controller_base_action {

	function __construct() {
		$this->objName = "portlet";
		$this->objPath = "system_portal";
		parent :: __construct();
	}

	function c_list() {
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:'';
		$parentName = isset($_GET['parentName'])?$_GET['parentName']:'';
		$this->assign('parentId',$parentId);
		$this->assign('parentName',$parentName);
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$typeId = isset($_GET['typeId'])?$_GET['typeId']:'';
		$typeName = isset($_GET['typeName'])?$_GET['typeName']:'';
		$this->assign('typeId',$typeId);
		$this->assign('typeName',$typeName);
		$this->view ( 'add' );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			if($key=="isPerm"){
				$val=($val==1?"checked":"");
			}
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 重写编辑
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if(empty($object['isPerm'])){
			$object['isPerm']=0;
		}
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 重写新增
	 */
	function c_add($isAddInfo = false) {
		$object = $_POST [$this->objName];
		if(empty($object['isPerm'])){
			$object['isPerm']=0;
		}
		$id = $this->service->add_d ( $object, $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 登陆用户门户首页
	 */
	function c_portal(){
		$sql="select max(portletOrder) as num from oa_portal_portlet_user where userId='".$_SESSION['USER_ID']."'";
		$maxOrder=$this->service->queryCount($sql);
		$this->view ( 'portal' );
	}

	/**
	 * 选择portlet
	 */
	function c_selectPortlet(){
		$this->view ( 'select' );
	}


	/**
	 * 获取当前登录人拥有权限的portlets分页
	 */
	function c_getPermPortlets(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->getUserRolePerm ($_SESSION["USER_ID"],$_SESSION["USER_JOBSID"]);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

}
?>