<?php
/**
 * @description: 项目action
 * @date 2010-9-13 下午05:21:37
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_project_rdproject extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-13 下午05:22:39
	 */
	function __construct() {
		$this->objName = "rdproject";
		$this->objPath = "rdproject_project";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/

	/****************************工作流********************************/
	/**
	 * @desription 审批跳转页面
	 * @param tags
	 * @date 2010-9-26 下午06:45:20
	 */
	function c_rpToWork() {
		$this->show->display ( 'common_myApproval' );
	}

	/**
	 * @desription 审批跳转页面
	 * @param tags
	 * @date 2010-9-26 下午06:45:20
	 */
	function c_menu() {
		$this->show->display ( 'common_myApprovalMenu' );
	}

	/**
	 * @desription 待审批
	 * @param tags
	 * @date 2010-9-26 下午07:14:14
	 */
	function c_rpApprovalNo() {
		$seachPjName = isset ( $_GET ['searchPjName'] ) ? $_GET ['searchPjName'] : "";
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		if ($seachPjName) {
			$service->searchArr ['seachProjectName'] = $seachPjName;
		}
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpApprovalPage_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'searchPjName', $seachPjName );
		$this->show->assign ( 'list', $service->rpApprovalNo_s ( $rows ) );
		$this->display ( 'my-ApprovalNo' );
	}

	/**
	 * @desription 已审批
	 * @param tags
	 * @date 2010-9-26 下午07:14:14
	 */
	function c_rpApprovalYes() {
		$seachPjName = isset ( $_GET ['searchPjName'] ) ? $_GET ['searchPjName'] : "";
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		if ($seachPjName) {
			$service->searchArr ['seachProjectName'] = $seachPjName;
		}
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $this->service->tbl_name;
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpApprovalPage_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'searchPjName', $seachPjName );
		$this->show->assign ( 'list', $service->rpApprovalYes_s ( $rows ) );
		$this->display ( 'my-ApprovalYes' );
	}

	/*****************************************************************/

	/**
	 * @desription 跳转添加项目action
	 * @date 2010-9-19 上午11:22:49
	 */
	function c_rpToAdd() {
		$pjId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "";

		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		if ($pjId != "") {
			$arr = $this->service->rpParentById_d ( $pjId );
			foreach ( $arr as $key => $val ) {
				$this->show->assign ( "id", $arr [$key] ['id'] );
				$this->show->assign ( "groupName", $arr [$key] ['groupName'] );
				$this->show->assign ( "groupCode", $arr [$key] ['groupCode'] );
			}
			$this->display ( 'add' );
		} else {
			$this->display ( 'upadd' );
		}
	}

	/**
	 * @desription 跳转添加项目action（不经过审批）
	 * @date 2010-9-19 上午11:22:49
	 */
	function c_toAddNoApproval() {
			if(!$this->service->this_limit['添加项目']){
				echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
				exit();
			}
		$pjId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "";

		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		if ($pjId != "") {
			$arr = $this->service->rpParentById_d ( $pjId );
			foreach ( $arr as $key => $val ) {
				$this->show->assign ( "id", $arr [$key] ['id'] );
				$this->show->assign ( "groupName", $arr [$key] ['groupName'] );
				$this->show->assign ( "groupCode", $arr [$key] ['groupCode'] );
			}
			$this->display ( 'noapproval-add' );
		} else {
			$this->display ( 'noapproval-upadd' );
		}
	}

	/**
	 * @desription 添加保存项目action
	 * @date 2010-9-15 下午07:19:11
	 */
	function c_rpAdd() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['groupSName'] == "root" || $objArr ['groupSName'] == "") {
			$objArr ['groupSName'] = "root";
			$objArr ['groupId'] = - 1;
		}
		$objArr ['businessCode'] = $this->businessCode ();
		$objArr ['status'] = $this->service->statusDao->statusEtoK ( 'save' );
		$objArr ['effortRate'] = 0;
		$objArr ['warpRate'] = 0;
		$id = $this->service->rpAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加项目【" . $objArr ['projectName'] . "】"; //操作类型
			$this->behindMethod ( $objArr );
			msgGo ( '添加成功！', "?model=rdproject_project_rdproject&action=addSuccessTo&pjId=$id" );
		} else {
			msgGo ( '添加失败！' );
		}
	}

	/**
	 * @desription 添加保存项目action(不经审批)
	 * @date 2010-9-15 下午07:19:11
	 */
	function c_rpAddNoApproval() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['groupSName'] == "root" || $objArr ['groupSName'] == "") {
			$objArr ['groupSName'] = "root";
			$objArr ['groupId'] = - 1;
		}
		$objArr ['businessCode'] = $this->businessCode ();
		$objArr ['status'] = $this->service->statusDao->statusEtoK ( 'execute' );
		$objArr ['ExaStatus'] ="完成";
		$objArr ['effortRate'] = 0;
		$objArr ['warpRate'] = 0;
		$id = $this->service->rpAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加项目【" . $objArr ['projectName'] . "】"; //操作类型
			$this->behindMethod ( $objArr );
			msg ( '添加成功！' );
		} else {
			msg ( '添加失败！' );
		}
	}

	/**
	 * @desription 新增项目后跳转临时页面，进行表格刷新及跳转到修改页面
	 */
	function c_addSuccessTo() {
		$this->show->assign ( "id", $_GET ['pjId'] );
		$this->display ( 'addsuccess' );
	}

	/**
	 * @desription 修改项目优先级跳转
	 * @author zengzx
	 * @date 2011年10月25日 15:43:36
	 * TODO:
	 */
	function c_rpModifyLevel() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->display ( 'modifylevel' );

	}

	/**
	 * @desription 修改项目优先级
	 * @author zengzx
	 * @date 2011年10月25日 15:43:36
	 */
	function c_rpUpdateLevel() {
		$project = $_POST [$this->objName];
		$projectObj = $this->service->rpArrById_d($project['id']);
		if ($this->service->edit_d ( $project, true )) {
			$projectTemp[0] = $project;
			$projectTemp = $this->service->datadictArrName ( $projectTemp, 'projectLevel', 'projectLevelC', 'XMYXJ' );
			$project = $projectTemp[0];
			$project ['operType_'] = "修改项目【" . $project ['projectName'] . "】优先级"; //操作类型
			$project ['operateLog_'] = "将项目优先级由【".$projectObj[0]['projectLevelC']."】修改成【".$project['projectLevelC']."】"; //操作类型
			$this->behindMethod ( $project );
			echo "<script>alert('保存成功');parent.location.reload();</script>";
		} else {
			msg ( "保存失败" );
		}
	}
	/**
	 * @desription 修改项目跳转action
	 * @date 2010-9-16 下午03:02:07
	 */
	function c_rpUpdateTo() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->show->assign ( "groupId", $arr [0] ['groupId'] );
		$this->show->assign ( "groupName", $arr [0] ['groupSName'] );
		$this->display ( 'update' );

	}

	/**
	 * @desription 修改项目跳转action
	 * @date 2010-9-16 下午03:02:07
	 */
	function c_toEditProject() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] :null;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->display ( 'edit' );

	}

	/**
	 * @desription 修改审批后项目基本信息
	 */
	function c_editAfterAduit() {
		$project = $_POST [$this->objName];

		if ($this->service->edit_d ( $project, true )) {
			msg( "保存成功" );
		} else {
			msg ( "保存失败" );
		}
	}

	/**
	 * @desription 修改项目action
	 * @date 2010-9-16 下午03:02:07
	 */
	function c_rpUpdate() {
		$project = $_POST [$this->objName];
		//权限判断
		$this->isCurUserPerm ( $project ["id"], 'project_baseInfo_update' );

		if ($this->service->edit_d ( $project, true )) {
			$project ['operType_'] = "修改项目【" . $project ['projectName'] . "】"; //操作类型
			$this->behindMethod ( $project );
//			echo "<script>self.window.parent.show_page();</script>";
			echo "<script>if(self.window.opener){self.window.opener.show_page();}else{self.window.parent.show_page();}</script>";
			msgGo ( "保存成功" );
		} else {
			msgGo ( "保存失败" );
		}
	}

	/**
	 * @desription 跳转关闭项目action
	 * @param tags
	 * @date 2010-10-5 下午02:52:10
	 */
	function c_rpCloseTo() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( "dateTime", date ( "Y-m-d" ) );
		$this->show->assign ( "userName", $_SESSION ['USERNAME'] );
		$this->show->assign ( "pjId", $pjId );
		$this->display ( 'close' );
	}

	/**
	 * @desription 关闭项目
	 * @param tags
	 * @date 2010-10-5 下午03:19:55
	 */
	function c_rpClose() {
		$object = $_POST;
		$this->isCurUserPerm ( $object ["id"], 'project_close' );
		$updateArr = array ();
		if ($object ['closeType'] == 0) {
			$updateArr ['status'] = $this->service->statusDao->statusEtoK ( "end" );
		} else {
			$updateArr ['status'] = $this->service->statusDao->statusEtoK ( "close" );
		}
		$updateArr ['closeDescription'] = $object ['closeText'];
		$updateArr ['actEndDate'] = date ( "Y-m-d" );
		$updateArr ['id'] = $object ['id'];
		if ($this->service->edit_d ( $updateArr, true )) {
			$object ['operType_'] = "关闭项目【" . $object ['projectName'] . "】"; //操作类型
			$this->behindMethod ( $object );
			msg ( "操作完成" );
		} else {
			msg ( "操作失败" );
		}
	}

	/**
	 * @desription 删除项目
	 * @param tags
	 * @date 2010-10-6 下午04:03:29
	 */
	function c_rpDel() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$this->isCurUserPerm ( $pjId, 'project_del' );
		$objArr = $this->service->rpArrById_d ( $pjId );
		if ($this->service->deletes ( $pjId )) {
			$objArr ['0'] ['operType_'] = "删除项目【" . $objArr ['0'] ['projectName'] . "】";
			$this->behindMethod ( $objArr ['0'] );
			msgGo ( '删除成功！' );
		} else {
			msgGo ( '删除失败！' );
		}
	}

	/**
	 * @desription 我的项目
	 * @date 2010-9-25 上午09:53:19
	 */
	function c_rpPageMy() {
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->searchArr ['managerUser'] = $_SESSION ['USER_ID'];
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$rows = $service->rpPage_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'my-list' );
	}

	/*******************************************/

	/**
	 * @desription 项目立项待审批列表
	 * @param tags
	 * @date 2010-9-20 上午09:55:13
	 */
	function c_rpPagePending() {
		$this->display ( 'listPending' );
	}

	/**
	 * @desription 待审批搜索页面
	 * @param tags
	 * @date 2010-10-2 上午11:31:12
	 */
	function rpPagePendingSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['createUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachPending' );
	}

	/*******************************************/

	/**
	 * @desription 项目立项已审批列表
	 * @param tags
	 * @date 2010-9-27 下午06:23:35
	 */
	function c_rpPageApproved() {
		$this->display ( 'listApproved' );
	}

	/**
	 * @desription 已审批搜索页面
	 * @param tags
	 * @date 2010-10-2 上午11:31:12
	 */
	function rpPageApprovedSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['createUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "close" ) . "," . $statusDao->statusEtoK ( "end" );
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachApproved' );
	}

	/*******************************************/


	/**
	 * @desription 跳转到项目列表页
	 */
	function c_rpPageCenter() {
		$this->assign('projectType',$_GET['projectType']);
		$this->assign('statusStr','');
		if(isset($_GET['ondoStatus'])){
			$this->assign('statusStr','ondoStatus');
		}
		if(isset($_GET['finishedStatus'])){
			$this->assign('statusStr','finishedStatus');
		}
		$this->display ( 'listCenter' );
	}

	/**
	 * @desription 项目列表搜索页面
	 * @param tags
	 * @date 2010-10-2 上午11:31:12
	 */
	function rpPageCenterSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachCenter' );
	}

	/*******************************************/

	/**
	 * @desription 跳转到我的项目列表页
	 */
	function c_rpPageMine() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('projectType',$_GET['projectType']);
		$this->assign('statusStr','');
		if(isset($_GET['ondoStatus'])){
			$this->assign('statusStr','ondoStatus');
		}
		if(isset($_GET['finishedStatus'])){
			$this->assign('statusStr','finishedStatus');
		}
		$this->display ( 'listMine' );
	}

	/**
	 * @desription 我的项目搜索页面
	 * @param tags
	 * @date 2010-10-2 上午11:31:12
	 */
	function rpPageMineSeach($seachArr) {
		$service = $this->service;
		$statusDao = $service->statusDao;
		$seachArr ['myPUser'] = $_SESSION ['USER_ID'];
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" );
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->sort = " c.createTime ";
		$service->asc = true;
		$service->groupBy = "";
		$service->searchArr = $seachArr;
		//		echo "<pre>";
		//		print_r($seachArr);
		$rows = $service->rpPage_d ();
		$this->pageShowAssign ( true ); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'seachMine' );
	}

	/*******************************************/

	/**
	 * @desription 跳转到项目列表页
	 */
	function c_page() {
		$this->display ( 'list' );
	}

	/**
	 * @desription 项目中心列表action
	 * @date 2010-9-17 上午10:15:37
	 */
	function c_rpPage() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->rpPage_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'list', $service->rpPage_s ( $rows ) );
		$this->display ( 'center-list' );
	}

	/**
	 * @desription 跳转高级搜索页面
	 * @date 2010-10-2 上午10:51:06
	 */
	function c_rpSeachAdvanced() {
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : exit ();
		$this->show->assign ( 'type', $type );
		$this->showDatadicts ( array ('projectType' => 'YFXMGL' ) );
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ) );
		$this->display ( 'seachAdvanced' );
	}

	/**
	 * @desription 搜索统一跳转列表
	 * @param tags
	 * @date 2010-10-2 上午11:37:26
	 */
	function c_rpSeachPage() {
		//		echo "<pre>";
		$type = isset ( $_POST ) ? $_POST ['type'] : exit ();
		$arr = isset ( $_POST ['rdproject'] ) ? $_POST ['rdproject'] : exit ();
		$seachArr = array ();
		if ($this->seachNull ( $arr ['groupSName'] ) && $this->seachNull ( $arr ['groupId'] )) {
			$seachArr ['seachGroupSName'] = $arr ['groupSName'];
		}

		if ($this->seachNull ( $arr ['projectCode'] )) {
			$seachArr ['seachProjectCode'] = $arr ['projectCode'];
		}

		if ($this->seachNull ( $arr ['projectName'] )) {
			$seachArr ['seachProjectName'] = $arr ['projectName'];
		}

		if ($this->seachNull ( $arr ['simpleName'] )) {
			$seachArr ['seachSimpleName'] = $arr ['simpleName'];
		}

		if ($this->seachNull ( $arr ['projectType'] )) {
			$seachArr ['seachProjectType'] = $arr ['projectType'];
		}

		if ($this->seachNull ( $arr ['projectLevel'] )) {
			$seachArr ['seachProjectLevel'] = $arr ['projectLevel'];
		}

		if ($this->seachNull ( $arr ['planDateStartS'] )) {
			$seachArr ['seachPlanDateStartS'] = $arr ['planDateStartS'];
		}

		if ($this->seachNull ( $arr ['planDateStartB'] )) {
			$seachArr ['seachPlanDateStartB'] = $arr ['planDateStartB'];
		}

		if ($this->seachNull ( $arr ['planDateCloseS'] )) {
			$seachArr ['seachPlanDateCloseS'] = $arr ['planDateCloseS'];
		}

		if ($this->seachNull ( $arr ['planDateCloseB'] )) {
			$seachArr ['seachPlanDateCloseB'] = $arr ['planDateCloseB'];
		}

		if ($this->seachNull ( $arr ['managerName'] )) {
			$seachArr ['seachManagerName'] = $arr ['managerName'];
		}

		if ($this->seachNull ( $arr ['assistantName'] )) {
			$seachArr ['seachAssistantName'] = $arr ['assistantName'];
		}

		if ($this->seachNull ( $arr ['description'] )) {
			$seachArr ['seachDescription'] = $arr ['description'];
		}

		$this->show->assign ( 'backUrl', $_POST ['basicUrl'] );
		if ($type == "pending") {
			$this->rpPagePendingSeach ( $seachArr );
		} else if ($type == "approved") {
			$this->rpPageApprovedSeach ( $seachArr );
		} else if ($type == "mine") {
			$this->rpPageMineSeach ( $seachArr );
		} else if ($type == "center") {
			$this->rpPageCenterSeach ( $seachArr );
		}
	}

	function seachNull($val) {
		if (isset ( $val ) && $val != "") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @desription 查看项目
	 * @date 2010-9-17 上午11:41:59
	 */
	function c_rpRead() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( 'pjId', $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//操作页面(一般的查看页面、内嵌在审批表单中)
		$this->display ( 'read' );
	}

	/**
	 * @desription 查看项目基本信息
	 * @param tags
	 * @date 2010-10-5 下午05:19:50
	 */
	function c_rpBasicMsg() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		$this->show->assign ( 'pjId', $pjId );
		$actType=isset($_GET['readType'])?$_GET['readType']:null;
		$this->show->assign("actType",$actType);//操作页面(一般的查看页面、内嵌在审批表单中)
		$this->display ( 'basicMsg' );
	}

	/**
	 * @desription 打开管理项目
	 * @param tags
	 * @date 2010-10-6 下午02:40:09
	 */
	function c_rpOpenManage() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( 'pjId', $pjId );
		$this->display ( 'openManage' );
	}

	/**
	 * @desription 管理基本信息
	 * @param tags
	 * @date 2010-10-6 下午03:43:42
	 */
	function c_rpManageBasic() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		//权限判断
		$role = $this->service->isCurUserPerm (  $_GET ['pjId'], 'project_level_modify' );
		$modifyLevel = '';
		if($role){
			$modifyLevel = "<span id='modifyLevel' onclick='mLevelFun();'><font color=green>  修改</font></span>";
		}
		$this->assign( 'rdproject[projectLevelC]',$arr[0]['projectLevelC'].$modifyLevel );
		$this->show->assign ( 'pjId', $pjId );
		$this->display ( 'manageBasic' );
	}

	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ajax判断项目名称是否重复
	 * @date 2010-9-13 下午02:22:04
	 */
	function c_ajaxProjectName() {
		$projectName = isset ( $_GET ['projectName'] ) ? $_GET ['projectName'] : false;
		$searchArr = array ("ajaxProjectName" => $projectName );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**判断项目编号是否重复
	*author can
	*2011-7-28
	*/
	function c_checkProjectCode() {
		$projectCode = isset ( $_GET ['projectCode'] ) ? $_GET ['projectCode'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$searchArr = array ("yprojectCode" => $projectCode );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * @desription 我的进度计划-项目
	 * @date 2010-9-25 上午09:53:19
	 */
	function c_rpAjaxMyPlan() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取权限
		$service->searchArr = $this->service->filterCustom ( array ('项目类型', '计划优先级' ), array ('ft_projectType', 'ft_projectLevel' ), $service->searchArr );
		$service->searchArr ['myPUser'] = $_SESSION ['USER_ID'];
		$statusDao = $service->statusDao;
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "execute" );
		$rows = $service->rpPage_d ();
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_rpAjaxAllPlan() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//TODO:
		if (isset ( $func_limit ['项目类型'] )) {
			$service->searchArr ['projectType'] = $func_limit ['项目类型'];
		}
		$service->searchArr ['statusArr'] = $service->statusDao->statusEtoK ( "execute" );
		$rows = $service->rpPage_d ();
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_treeNodeWork() {
		echo "开始啦";
		$treeDao = new model_treeNode2 ();
		if ($treeDao->createTreeLRValue ()) {
			echo "<br>成功了";
		} else {
			echo "<br>失败了";
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	//	function c_pageJson() {
	//		$service = $this->service;
	//		$service->getParam ( $_POST ); //设置前台获取的参数信息
	//		$statusDao = $service->statusDao;
	//		$seachArr ['statusArr'] =  $statusDao->statusEtoK ( "execute" ) ;
	//		$serv xice->searchArr = $seachArr;
	//		$rows = $service->page_d ();
	//		$arr = array ();
	//		$arr ['collection'] = $rows;
	//		$arr ['totalSize'] = $service->count;
	//		echo util_jsonUtil::encode ( $arr );
	//	}


	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$statusDao = $service->statusDao;
		$seachArr ['statusArr'] = $statusDao->statusEtoK ( "execute" );
		$service->searchArr = $seachArr;
		$rows = $service->pageBySqlId ( "select_pjdata" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}


	function c_pageJson2() {   //combogrid下拉选择
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$statusDao = $service->statusDao;
		$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**一键通下拉列表pageJson
	 * add by suxc    2011-08-16
	 */
	function c_pageJsonByOnekey() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$statusDao = $service->statusDao;
		if($this->service->this_limit['选择项目']){
			$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );
		}else{
			$service->searchArr['statusArr'] = $statusDao->statusEtoK ( "execute" );
			$service->searchArr['myPUser'] = $_SESSION ['USER_ID'];
		}
		$rows = $service->pageBySqlId ( "select_pjdata" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 返回项目列表数据方法(包括没有项目组合的项目及一级项目组合)
	 * add by chengl 2011-04-07
	 */
	function c_projectAndGroup() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='c.type desc,c.statusGroup desc,c.createTime';
		$service->asc=true;
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}
	/**
	 * 返回我的项目列表数据方法(包括没有项目组合的项目及一级项目组合)
	 * add by chengl 2011-04-09
	 */
	function c_myProjectAndGroup() {
		$service = $this->service;
		$service->sort='c.type desc,c.statusGroup desc,c.createTime';
		$service->asc=true;
		$service->getParam ( $_REQUEST );
		$service->searchArr ['myPUser'] = $_SESSION ['USER_ID'];
//		$service->searchArr ['projectType'] = $_GET['projectType'];
		if(isset($_GET['ondoStatus'])){
			$service->searchArr ['ondoStatus'] = $_GET['ondoStatus'];
		}
		if(isset($_GET['finishedStatus'])){
			$service->searchArr ['finishedStatus'] = $_GET['finishedStatus'];
		}
		$rows = $service->projectAndGroup_d (true);
//		if ($_GET ['parentId'] == PARENT_ID) {//项目组合parentId=-1则为项目组合
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
//		} else {
//			echo util_jsonUtil::encode ( $rows );
//		}
	}

	/**
	 * 返回待审批项目列表数据方法(包括没有项目组合的项目及一级项目组合)
	 * add by chengl 2011-04-09
	 */
	function c_projectAndGroupPending() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$statusDao = $service->statusDao;
		$service->searchArr ['createUser'] = $_SESSION ['USER_ID'];
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" );
		$service->searchArr ['ortype'] = "(c.type=2 and c.parentId=" . $_GET ['parentId'] . ")";
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}

	/**
	 * 返回待审批项目列表数据方法(包括没有项目组合的项目及一级项目组合)
	 * add by chengl 2011-04-09
	 */
	function c_projectAndGroupApproved() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$statusDao = $service->statusDao;
		$searchArr = $service->searchArr;
		$service->searchArr ['createUser'] = $_SESSION ['USER_ID'];
		$service->searchArr ['statusArr'] = $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" );
		$service->searchArr ['ortype'] = "(c.type=2 and c.parentId=" . $_GET ['parentId'] . ")"; //or条件要放到最后
		$rows = $service->projectAndGroup_d ();
		if ($_GET ['parentId'] == PARENT_ID) {
			if (is_array ( $rows )) {
				$arr = array ();
				$arr ['data'] = $rows;
				$arr ['total'] = $service->count;
				$arr ['page'] = $service->page;
				echo util_jsonUtil::encode ( $arr );
			} else {
				echo 0;
			}
		} else {
			echo util_jsonUtil::encode ( $rows );
		}
	}



	/**
	 * @desription 修改项目跳转action
	 * @date 2011年6月28日 14:34:31
	 */
	function c_rpUpdateToOld() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$arr = $this->service->rpArrById_d ( $pjId );
		$this->arrToShow ( $arr );
		//$this->showDatadicts(array('projectType' => 'YFXMGL'));
		$this->showDatadicts ( array ('projectLevel' => 'XMYXJ' ), $arr ['0'] ['projectLevel'] );
		$this->show->assign ( "groupId", $arr [0] ['groupId'] );
		$this->show->assign ( "groupName", $arr [0] ['groupSName'] );
		$this->display ( 'updateOld' );

	}


	/**
	 * @desription 修改项目action
	 * @date 2011年6月28日 14:29:51
	 */
	function c_rpUpdateOld() {
		$project = $_POST [$this->objName];
		//权限判断
//		$this->isCurUserPerm ( $project ["id"], 'project_baseInfo_update' );

		if ($this->service->edit_d ( $project, false )) {
			echo "<script>if(self.window.opener){self.window.opener.show_page();}else{self.window.parent.show_page();}</script>";
			msgGo ( "保存成功" );
		} else {
			msgGo ( "保存失败" );
		}
	}

	/**
	 * 研发项目选择
	 */
	function c_pageForDL(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**
	 * 研发项目选择 - 从鼎利的表中选取数据
	 */
	function c_pageJsonForDL(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		//$service->asc = false;
		$rows = $service->page_d ('select_DL');
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
	 * 合同项目选择 - 包含合同类的工程项目和合同类的研发项目
	 */
	function c_pageForAll(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "selectall" );
	}

	/**
	 * 研发项目选择 - 从鼎利的表中选取数据
	 */
	function c_pageJsonForAll(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );

		//$service->asc = false;
		$rows = $service->page_d ('select_all');
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
