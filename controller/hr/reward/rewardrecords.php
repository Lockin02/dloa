<?php

/**
 * @author Show
 * @Date 2012年5月24日 星期四 10:00:14
 * @version 1.0
 * @description:薪资信息控制层
 */
class controller_hr_reward_rewardrecords extends controller_base_action {

	function __construct() {
		$this->objName = "rewardrecords";
		$this->objPath = "hr_reward";
		parent :: __construct();
	}

	/**
	 * 跳转到薪资信息列表
	 */
	function c_page() {
		$this->view('list');
	}
	/**
	 * 跳转到薪资信息列表--个人
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$rows = $service->page_d ();

		//其余信息加载
		if(!empty($rows)){
			$rows = $this->sconfig->md5Rows ( $rows );

			//合计列生成
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '合计';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//系统权限
		$sysLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')){

			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();
		}

		//其余信息加载
		if(!empty($rows)){
			$rows = $this->sconfig->md5Rows ( $rows );

			//合计列生成
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '合计';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增薪资信息页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑薪资信息页面
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
	 * 跳转到查看薪资信息页面
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
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '薪资信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E 导入导出系列 ************************/
}
?>