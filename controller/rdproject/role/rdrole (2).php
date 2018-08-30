<?php
/**
 * @description: 团队角色action
 * @author chengl
 * @version V1.0
 */
class controller_rdproject_role_rdrole extends controller_base_action {
	function __construct() {
		$this->objName = "rdrole";
		$this->objPath = "rdproject_role";
		parent::__construct ();
	}

	/*
	 * @desription 根据项目跳转到角色列表页
	 */
	function c_toTemplateRolePage() {
		$this->showDatadicts ( array ('itemType' => 'YFXMGL' ) );
		$this->show->display ( 'rdproject_role_rdrole-listtemplate' );
	}

	/*
	 * @desription 根据项目类型跳转到角色列表页
	 */
	function c_toProjectRolePage() {
		$this->show->assign ( "projectId", $_GET ['pjId'] );
		$this->show->display ( $this->objPath .'_'.$this->objName .'-list' );
	}

	/**
	 * 我的项目-打开项目-项目角色
	 */
	function c_rolePageInOption() {
		$this->show->assign ( "projectId", $_GET ['pjId'] );
		$this->show->display ( $this->objPath .'_'.$this->objName .'-listinoption' );
	}

	/**
	 * @desription 获取某页角色及其子孙角色信息
	 */
	function c_pageAll() {
		$service = $this->service;
		if (! isset ( $_POST ['projectType'] )) {
			//树条件，treeNode会根据条件字段自动构造多棵树
			$service->treeCondFields = array ('isTemplate', 'projectId' );
		}
		$searchArr = $service->getParam ( $_POST ); //设置前台获取的参数信息
		if (! isset ( $_POST ['projectId'] )) { //如果没有传项目id，则为模板页面
			$searchArr ['isTemplate'] = 1;
		}
		//print_r($searchArr);
		$rows = $service->pageAll_d ( $searchArr );

		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 根据项目类型/项目id添加项目角色
	 */
	function c_toAdd() {
		$projectType = $_GET ['projectType'];
		$projectId = $_GET ['projectId'];
		if (! empty ( $projectId )) {
			//权限判断
			$this->isCurUserPerm ( $projectId, 'project_teamMember_admin' );
		}

		$this->showDatadicts(array('projectType' => 'YFXMGL'));
//		$this->show->assign ( "projectType1", $projectType );
		$this->show->assign ( "projectId", $projectId );

		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * desription 新增角色
	 */
	function c_add() {
		$role = $_POST ['rdrole'];
		if (! empty ( $role ['projectId'] )) {

			$role ['isTemplate'] = 0;
			$this->service->treeCondFields = array ('projectId' ); //只需根据项目id进行树的构造
		} else {
			unset ( $role ['projectId'] );
			$role ['isTemplate'] = 1;
		}
		$id = $this->service->add_d ( $role, true );
		if ($id) {
			msg ( '添加成功！' );
		}
		//$this->listDataDict();
	}

	/**
	 * @desription 根据项目类型及父亲获取角色列表(用于Ext下拉树数据)
	 */
	function c_ajaxRoleByParent() {
		if (isset ( $_GET ['projectType'] )) {
			$this->service->searchArr = array ("projectType" => $_GET ['projectType'], "isTemplate" => 1, "parentId" => $_GET ['parentId'] );
		} else if (isset ( $_GET ['projectId'] )) {
			$this->service->searchArr = array ("projectId" => $_GET ['projectId'], "isTemplate" => 0, "parentId" => $_GET ['parentId'] );
		}
		$roles = $this->service->list_d ();
		//把是否叶子值0转成false，1转成true
		function toBoolean($row) {
			$row ['leaf'] = ($row ['leaf'] == 0 ? false : true);
			//$row ['checked']=true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $roles ) );
		//echo util_jsonUtil::encode ( $roles );
	}

	/**
	 * @desription 跳转到授予某个角色权限
	 */
	function c_authorize() {
		$roleId = $_GET ['id'];
		//获取角色关联的权限信息
		$perms = $this->service->getAuthorizeByRoleId_d ( $roleId );
		$permstr = "";
		if (is_array ( $perms )) {
			foreach ( $perms as $value ) {
				$permstr .= "," . $value ['permCode'];
			}
			if (! empty ( $permstr )) {
				$permstr = substr ( $permstr, 1 );
			}
		}
		//include (CONFIG_SQL . "rdproject/role/permContants.php");
		//print_r ( $perm_arr );
		$this->show->assign ( "permstr", $permstr );
		$this->show->assign ( "roleId", $roleId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-authorize' );
	}

	/**
	 * @desription 保存角色权限关联
	 */
	function c_saveAuthorize() {
		$perms = $_POST ['perms']; //权限数组
		$roleId = $_POST ['roleId']; //角色id
		if ($this->service->saveAuthorize_d ( $roleId, $perms )) {
			echo 1;
		} else
			echo 0;
	}

	/**
	 * ajax批量删除对象
	 */
	function c_ajaxdeletes() {
		//获取删除的记录，如果关联项目，则需要重新设置构建树条件
		$role = $this->service->get_d ( $_POST ['id'] );
		if (! empty ( $role ['projectId'] )) {
			$this->service->treeCondFields = array ('isTemplate', 'projectId' );
		}
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

}
?>
