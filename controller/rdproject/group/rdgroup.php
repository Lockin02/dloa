<?php
/**
 * @description: 项目组合action
 * @date 2010-9-11 下午12:51:57
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_group_rdgroup extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:52:29
	 */
	function __construct() {
		$this->objName = "rdgroup";
		$this->objPath = "rdproject_group";
		$this->operArr = array ("groupName" => "项目组合名称", "simpleName" => "简称" ); //统一注册监控字段，如果不同方法有不同的监控字段，在各自方法里面更改此数组
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/
	/**
	 * 项目组合懒加载跳转页面
	 */
	function c_page() {
		$this->service->createTreeLRValue();//自动产生树左右值
		$this->show->display ( 'rdproject_project_rdproject-list' );
	}

	/**
	 * 项目组合非懒加载跳转页面
	 */
	function c_pageAll() {
		$this->show->display ( 'rdproject_project_rdproject-listAll' );
	}

	/**
	 * @desription 跳转添加方法action
	 * @param tags
	 * @date 2010-9-23 下午03:52:42
	 */
	function c_toAdd() {
		$parentId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "-1";
		$arr = $this->service->rgParentById_d ( $parentId );
		if($parentId != -1){
			foreach($arr as $key=>$val){
				$this->show->assign("id",$arr[$key]['id']);
				$this->show->assign("groupName",$arr[$key]['groupName']);
				$this->show->assign("groupCode",$arr[$key]['groupCode']);
			}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
		}else
		$this->show->display ( $this->objPath . '_' . $this->objName . '-upadd' );

	}

	/**
	 * @desription 添加保存项目组合action
	 * @date 2010-9-13 下午04:16:50
	 */
	function c_rgAdd() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['parentName'] == "请选择..." || $objArr ['parentId'] == null || $objArr ['parentId'] == "" || $objArr ['parentId'] == "-1") {
			$objArr ['parentName'] = "root";
			$objArr ['parentId'] = - 1;
			$objArr ['parentCode'] = "root";
		}
		$id = $this->service->rgAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加组合【" . $objArr ['groupName'] . "】";
			$this->behindMethod ( $objArr );
			msg ( '添加成功！' );
		}else{
			msg ( '添加失败！' );
		}

	}

	/**
	 * @desription 修改项目跳转action
	 * @date 2010-9-16 下午03:02:07
	 */
	function c_rgUpdateTo() {
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$arr = $this->service->rgArrById_d ( $gpId );
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-update' );

	}

	/**
	 * @desription TODO
	 * @date 2010-9-24 上午09:54:41
	 */
	function c_rgUpdate() {
		$rdgroup = $_POST ['rdgroup'];
//		echo "<pre>";
//		print_r($rdgroup);
		//操作前执行方法（操作及变更可用）
		$this->beforeMethod ( $rdgroup );
		if ($this->service->edit_d ( $rdgroup )) {
//			//操作记录
			$rdgroup ['operType_'] = "编辑组合【" . $rdgroup ['groupName'] . "】";
			$this->behindMethod ( $rdgroup );//$this->behindMethod ( $rdgroup,'change' );
			msg ( '编辑成功！' );
		}
	}

	/**
	 * @exclude 查看组合
	 * @version 2010-9-24 下午09:28:45
	 */
	function c_rgRead() {
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$arr = $this->service->rgArrById_d ( $gpId );
		$this->arrToShow ( $arr );
		$this->show->assign('gpId' , $gpId);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	function c_rgDel(){
		if(!$this->service->this_limit['组合删除']){
			echo "<script>alert('没有权限进行操作!');history.back(-1);</script>";
			exit();
		}
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$rdgroup = $this->service->rgArrById_d($gpId);
		$retVal = $this->service->rgDel_d($gpId);
		if($retVal){
			$rdgroup ['0']['operType_'] = "删除组合【" . $rdgroup ['0']['groupName'] . "】";
			$this->behindMethod ( $rdgroup['0'] );
			msgGo ( '删除成功！' );
		}else{
			msgGo ( '删除失败！' );
		}
	}

	/**
	 * 重写父类方法
	 */
	function arrToShow($arr) {
		$assignName = $this->objName;
		foreach ( $arr ["0"] as $key => $val ) {
			if (! is_array ( $val )) {
				if( $key=="parentName"&&$val=="root" ){
					$this->show->assign ( $assignName . "[$key]", "无" );
				}else{
					$this->show->assign ( $assignName . "[$key]", $val );
				}
			}
		}
	}

	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 根据上级获取项目组合列表（一般用于构造树）
	 * @date 2010-9-11 下午05:14:34
	 */
	function c_ajaxGroupByParent() {
		$service = $this->service;
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$service->searchArr = $searchArr;
		$service->sort = 'Id';
		$rows = $service->list_d ();
		//把是否叶子值0转成false，1转成true
		function toBoolean($row) {
			$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $rows ) );
	}

	/**
	 * @desription ajax判断项目组合名称是否重复
	 * @date 2010-9-13 下午02:22:04
	 */
	function c_ajaxGroupName() {
		$groupName = isset ( $_GET ['groupName'] ) ? $_GET ['groupName'] : false;
		$searchArr = array ("ajaxGroupName" => $groupName );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * @desription ajax判断项目组合名称是否重复UPDATE
	 * @date 2010-9-24 上午09:41:21
	 */
	function c_ajaxGroupNameUpdate() {
		$groupName = isset ( $_GET ['groupName'] ) ? $_GET ['groupName'] : false;
		$groupNameOld = isset ( $_GET ['groupNameOld'] ) ? $_GET ['groupNameOld'] : false;
		if ($groupName == $groupNameOld) {
			echo "1";
		} else {
			$searchArr = array ("ajaxGroupName" => $groupName );
			$isRepeat = $this->service->isRepeat ( $searchArr, "" );
			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
	}

	/*********************以下主要是树形表格用到的方法******************************/

	/**
	 * @desription 待审批项目 获取某一层项目组合下的组合分页
	 */
	function c_ajaxGroupByParentPending() {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$pjSeachArr = array (
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" )
		);
		$this->ajaxGroupByParent ( $searchArr,$pjSeachArr );
	}

	/**
	 * @desription 待审批项目 根据上级组合id获取组合下的项目组合跟项目列表
	 */
	function c_ajaxGroupAndProjectPending() {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"createUser" => $_SESSION['USER_ID'],
			"groupId" => $_GET ['parentId'],
			"statusArr" => $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" ) );
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription 已审批项目 获取某一层项目组合下的组合分页
	 */
	function c_ajaxGroupByParentApproved() {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$pjSeachArr = array (
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupByParent ( $searchArr,$pjSeachArr );
	}

	/**
	 * @desription 已审批项目 根据上级组合id获取组合下的项目组合跟项目列表
	 */
	function c_ajaxGroupAndProjectApproved() {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"groupId" => $_GET ['parentId'],
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription 项目中心 获取低一层组合分页
	 * @param tags
	 * @date 2010-9-29 下午09:15:34
	 */
	function c_ajaxGroupByParentCenter () {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$this->ajaxGroupByParent ( $searchArr );
	}

	/**
	 * @desription 项目中心 根据上级组合id获取组合下的项目组合跟项目列表
	 * @param tags
	 * @date 2010-9-29 下午09:18:52
	 */
	function c_ajaxGroupAndProjectCenter () {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"groupId" => $_GET ['parentId']
			,"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," .$statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
							. "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription 我的项目 获取低一层组合分页
	 * @param tags
	 * @date 2010-9-29 下午09:15:34
	 */
	function c_ajaxGroupByParentMine () {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"myPUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupByParent ( $searchArr ,$searchArrProject);
	}

	/**
	 * @desription 我的项目 根据上级组合id获取组合下的项目组合跟项目列表
	 * @param tags
	 * @date 2010-9-29 下午09:18:52
	 */
	function c_ajaxGroupAndProjectMine () {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"myPUser" => $_SESSION['USER_ID'],
			"groupId" => $_GET ['parentId'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," .
							$statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}



	/*********************以下主要是树形表格用到的公用方法******************************/

	/**
	 * @desription 返回默认项目组合树形列表第一层
	 * @date 2010-9-21 上午11:16:56
	 */
	function ajaxGroupByParent( $searchArr,$pjSeachArr=false ) {
		$service = $this->service;
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		if($pjSeachArr ){
			$searchArr['pjTree'] = $projectDao->rpGroupTreeSql_d ($pjSeachArr);
		}
		$service->getParam ( $_POST );
		$service->searchArr = $searchArr;
		$rows = $service->rgPage_d ();

		if (is_array ( $rows )) {
			$arr = array ();
			$arr ['data'] = $rows;
			$arr ['total'] = $service->count;
			$arr ['page'] = $service->page;
			//			echo "<pre>";
			//			print_r($arr);
			echo util_jsonUtil::encode ( $arr );
		} else {
			echo 0;
		}
	}

	/**
	 * @desription 根据上级组合id获取组合下的项目组合跟项目列表
	 * @param tags
	 * @date 2010-9-21 上午11:19:59
	 */
	function ajaxGroupAndProject($searchArrGroup, $searchArrProject) {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = $searchArrGroup;
		$groupRows = $service->rgList_d ();

		$projectDao = new model_rdproject_project_rdproject ();
		$projectDao->searchArr = $searchArrProject;
		$projectRows = $projectDao->rpList_d ();
		$projectRows = model_common_util::yx_array_merge ( $projectRows, $groupRows );
		//		echo "<pre>";
		//		print_r($projectRows);
		if (is_array ( $projectRows )) {
			echo util_jsonUtil::encode ( $projectRows );
		} else {
			echo "0";
		}
	}

	/***************************************************************************/

	/**
	 * @desription 获取某一层项目组合下的组合分页
	 */
	function c_ajaxPageGroupByParent() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_POST );
		$searchArr ["parentId"] = $_GET ['parentId'];
		$service->searchArr = $searchArr;
		$rows = $service->page_d ();

		//产生一个以g_为前缀的id，用以区分项目或组合,注意这里的前缀要跟下面获取组合跟项目的oParentId前缀一致
		function createOIdFn($row) {
			$row ['oid'] = "g_" . $row ['id']; //以g-为前缀表明为组合
			$row ['oParentId'] = "g_" . $row ['parentId'];
			$row ['icon'] = "group.gif"; //设置组合图标样式
			return $row;
		}
		$rows = array_map ( "createOIdFn", $rows );
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
		//echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 根据上级组合id获取组合下的项目组合跟项目列表
	 */
	function c_pageGroupAndProject() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$searchArr ["parentId"] = $_GET ['parentId'];
		$service->searchArr = $searchArr;
		$groupRows = $service->list_d ();

		$projectDao = new model_rdproject_project_rdproject ();
		$searchArr = array ("groupId" => $_GET ['parentId'] );
		$projectDao->searchArr = $searchArr;
		$projectRows = $projectDao->list_d ();
		$projectRows = model_common_util::yx_array_merge ( $projectRows, $groupRows );

		if (is_array ( $projectRows )) {
			//产生一个以g_或p_为前缀的id，用以区分项目或组合
			function createOIdFn($row) {
				if (isset ( $row ['projectName'] )) {
					$row ['oid'] = "p_" . $row ['id']; //以p-为前缀表明为项目
					$row ['oParentId'] = "g_" . $row ['groupId'];
					$row ['icon'] = "project.gif"; //设置项目图标样式
				} else {
					$row ['oid'] = "g_" . $row ['id']; //以g-为前缀表明为组合
					$row ['oParentId'] = "g_" . $row ['parentId'];
					$row ['icon'] = "group.gif"; //设置组合图标样式
				}
				return $row;
			}
			echo util_jsonUtil::encode ( array_map ( "createOIdFn", $projectRows ) );
		} else {
			echo "";
		}

	}

	/**
	 * @desription 根据项目组合分页，并设置组合下组合及项目
	 * 注意：此方法用于一次性获取项目组合跟项目返回前台解析
	 */
	function c_pageGroupAndProjectAll() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->pageAll_d ( $searchArr );

		//产生一个以g_或p_为前缀的id，用以区分项目或组合
		function createOIdFn($row) {
			if (isset ( $row ['projectName'] )) {
				$row ['oid'] = "p_" . $row ['id']; //以p-为前缀表明为项目
				$row ['oParentId'] = "g_" . $row ['groupId'];
				$row ['icon'] = "project.gif"; //设置项目图标样式
			} else {
				$row ['oid'] = "g_" . $row ['id']; //以g-为前缀表明为组合
				$row ['oParentId'] = "g_" . $row ['parentId'];
				$row ['icon'] = "group.gif"; //设置组合图标样式
			}
			return $row;
		}
		$rows = array_map ( "createOIdFn", $rows );
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
		//echo util_jsonUtil::encode ( $arr );
	//echo util_jsonUtil::encode ( $rows );
	}

}

?>
