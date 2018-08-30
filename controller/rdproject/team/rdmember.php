<?php
/*
 * Created on 2010-9-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_rdproject_team_rdmember extends controller_base_action {
	function __construct() {
		$this->objName = "rdmember";
		$this->objPath = "rdproject_team";
		parent::__construct ();
	}
	/**
	 * 默认列表页面
	 * 显示人员
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$this->pageShowAssign ();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonProject() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.memberId';
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->page_d ('select_joinproject');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 添加项目成员
	 */
	function c_player() {
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]',$_GET ['pjId'] );
		$this->show->assign ( 'id', $_GET ['pjId'] );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * 我的项目-打开项目中显示成员
	 */
	function c_playerInOption() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]',$pjId );
		$this->show->assign ( 'id', $_GET ['pjId'] );
		if (! empty ( $_GET['pjId'] )) {
			//权限判断
			$isPerm = $this->isCurUserPermAjax ( $_GET['pjId'], 'project_teamMember_admin' );
		}
		$this->show->assign ( 'list', $service->showlist ( $rows ,$isPerm ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-addInOption' );
	}

	/**
	 * 显示添加内部成员
	 */
 	function c_toAddMember(){
 		$proCenter=$_GET['proCenter'];
		if (! empty ( $_GET['projectId'] )&&$proCenter!=1) {
			//权限判断
			$this->isCurUserPerm ( $_GET['projectId'], 'project_teamMember_admin' );
		}
 		$this->show->assign ( 'projectId', $_GET ['projectId'] );
 		$this->show->display ( $this->objPath . '_' . $this->objName . '-toAdd' );
 	}

	/**
	 * 添加内部成员+更新成员角色（这里可以分两个事务处理）
	 */
	function c_addByGroud() {
		if ($this->service->addByGroudIndpt ( $_POST [$this->objName] )) {
			msg ( '保存成功' );
		} else {
			msg ( '保存失败' );
		}
	}

	/**
	 * 添加外部成员-显示
	 */
	function c_inOutsideInfo() {
 		$proCenter=$_GET['proCenter'];
//		if (! empty ( $_GET['projectId'] )&&$proCenter!=1) {
//			//权限判断
//			$this->isCurUserPerm ( $_GET['projectId'], 'project_task' );
//		}
		$this->show->assign ( 'projectId', $_GET ['projectId'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-addoutside' );
	}

	/**
	 * 添加外部成员-插入
	 */
	function c_addOutside() {
		if ($this->service->add ( $_POST [$this->objName] )) {
			msg ( '添加成功' );
		} else {
			msg ( '添加失败' );
		}
	}

	/**
	 * 删除项目成员-确认
	 */
	function c_makeSureDelete() {
		showmsg ( '确认删除？', "location.href='?model=rdproject_team_rdmember&action=deleteThisMember&id=" . $_GET ['id'] . "'", 'button' );
	}

	/**
	 * 删除项目成员-删除
	 */
	function c_deleteThisMember() {
		//update by chengl 2011-07-29 直接从数据库删除数据
		if ($this->service->deletes ( $_GET ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 授予成员角色
	 */
	function c_saveRolesToMember() {
		$rolesId = $_POST ['rolesId'];
		$memberId = $_POST ['memberId'];
		if ($this->service->saveRolesToMember_d ( $rolesId, $memberId )) {
			echo "1";
		} else {
			echo "0";
		}
	}

	/**
	 * 项目成员查看页面
	 */
	function c_view(){
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		$service->searchArr ['isUsing'] = '1';
		$rows = $service->page_d ();

		$this->show->assign ( 'rdproject[id]', $_GET ['pjId'] );
		$this->show->assign ( 'list', $service->showviewlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}


	/*
	 * 判断是否有执行 -- ajax
	 * zengzx 2011年9月20日16:41:29
	 */
	function isCurUserPermAjax($projectId, $permCode) {
		//判断是否是项目负责人或者项目助理，是不进行权限判断
		$projectDao = new model_rdproject_project_rdproject ();
		$project = $projectDao->get_d ( $projectId );
		$curUserId = $_SESSION ['USER_ID'];
		$pos = strpos ( $project ['assistantId'], $curUserId . "," );
		if ($pos != - 1 && $project ['managerId'] != $curUserId && $project ['createId'] != $curUserId) {
			$roleDao = new model_rdproject_role_rdrole ();
			if (! $roleDao->isCurUserPerm ( $projectId, $permCode )) {
				return false;
			}
		}else{
			return true;
		}
	}

}
?>
