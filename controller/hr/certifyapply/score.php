<?php

/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:39
 * @version 1.0
 * @description:任职资格评委打分表 - 主表控制层
 */
class controller_hr_certifyapply_score extends controller_base_action {

	function __construct() {
		$this->objName = "score";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}
	/******************* 列表部分 ********************/

	/**
	 * 跳转到任职资格评委打分表 - 主表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 个人列表
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['assessUser'] = $_SESSION['USER_ID'];
		$_REQUEST['scoreUser'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$sql = "select
				s.id ,s.userName ,s.userAccount , s.managerName,s.managerId ,s.createId ,s.createName ,
				s.createTime ,s.updateId ,s.updateName ,s.updateTime ,s.sysCompanyName ,s.sysCompanyId,s.status,
				c.id as scoreId,ifnull(c.id,0) as scoreStatus,c.score,c.assessDate,c.managerId as scoreManagerId
			from
				oa_hr_certifyapplyassess s
					left join
				(select c.id,c.score,c.assessDate,c.managerId,c.cassessId from oa_hr_certifyapplyassess_score c where c.managerId = '".$_SESSION['USER_ID']."' ) c
					on s.id = c.cassessId
			where
				1";
		$rows = $service->pageBySql($sql);
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

	/******************* 增删改查 *******************/
	/**
	 * 跳转到新增任职资格评委打分表 - 主表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑任职资格评委打分表 - 主表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看任职资格评委打分表 - 主表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 新建评分
	 */
	function c_toScore(){
		//获取评价表信息
		$assessInfo = $this->service->getAssess_d($_GET['cassessId']);
		$this->assignFunc($assessInfo);

		$this->assign('thisUserName',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);

		$this->view('score');
	}
}
?>