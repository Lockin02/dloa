<?php
/**
 * @description: 团队角色action
 * @author chengl
 * @version V1.0
 */
class controller_rdproject_role_rdpermission extends controller_base_action {
	function __construct() {
		$this->objName = "rdpermission";
		$this->objPath = "rdproject_role";
		parent::__construct ();
	}


	function c_userlist(){
		$service = $this->service;
		$deptId = $_GET['deptId'];
		$userId = $_GET['userId'];
		$userRow = $service->page_d($deptId,$userId);
//		$userRow = $this->service->_db->getArray($userSql);
		$this->pageShowAssign();
//		//调用数据字典内容来显示项目类型的下拉框
		$this->assign('list',$service->showuserlist($userRow));
		$this->assign('deptName',$_GET['deptName']);
		$this->assign('userName',$_GET['userName']);
		$this->display('list');
	}


	/**
	 * 初始化对象
	 */
	function c_toAdd() {
		$condition = array ("userId" => $_GET['userId'] );
		$obj = $this->service->find ( $condition );
		if(is_array($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}
		$this->assign('userId', $_GET['userId']);
		$this->assign('userName', $_GET['userName']);
		if (!$_GET ['id']) {
			$this->display ( 'add' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 配置人员可见部门
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}else{
			msg ( '配置失败！' );
		}
	}

}
?>
