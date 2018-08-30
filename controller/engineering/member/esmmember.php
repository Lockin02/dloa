<?php

/**
 * @author Show
 * @Date 2011年12月20日 星期二 15:23:55
 * @version 1.0
 * @description:项目成员(oa_esm_project_member)控制层 成员类型
 * 0 内部
 * 1 外部
 *
 * 成员状态
 * 0 项目中
 * 1 已离开
 */
class controller_engineering_member_esmmember extends controller_base_action
{

	function __construct() {
		$this->objName = "esmmember";
		$this->objPath = "engineering_member";
		parent::__construct();
	}

	/******************* 列表部分 ***************************/

	/**
	 * 跳转到项目成员(oa_esm_project_member)列表
	 */
	function c_page() {
		$projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
		$this->assign('projectId', $projectId);
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonOrg() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//$service->asc = false;
		$rows = $service->page_d('select_listcount');
		if (is_array($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//$service->asc = false;
		$rows = $service->page_d('select_listcount');
		if (is_array($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);

			//加入权限
			$rows = $this->gridDateFilter($rows);

			//加载项目合计
			$service->sort = "";
			$service->searchArr = array('projectId' => $_POST['projectId']);
			$objArr = $service->listBySqlId('count_all');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['memberName'] = '项目合计';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 项目变更列表
	 */
	function c_pageForMember() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('list');
	}

	/**
	 * 项目人员现状
	 */
	function c_pageForMemberCurrent() {
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		//周日期设置
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('projectId', $projectId);

		$this->view('list-current');
	}

	/**
	 * 某段时间项目人员状况
	 */
	function c_ajaxManageCurrent() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//周日期设置
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		$data = array(
			array('级别' => '人数')
		);
		//列表内容渲染
		$pageInfo = $this->service->memberCurrent_d($projectId, $beginDate, $endDate, $condition);
		if (!empty($pageInfo)) {
			$amount = 0;
			foreach ($pageInfo as $key => $val) {
				if (empty($val['personLevel'])) {
					$data[0]['无'] = $val['count(*)'];
					$amount += $val['count(*)'];
				} else {
					$data[0][$val['personLevel']] = $val['count(*)'];
					$amount += $val['count(*)'];
				}
			}
			$data[0]['合计'] = $amount;
		}
		echo util_jsonUtil::encode($data);
	}

	/**
	 * 某段时间项目成员列表
	 */
	function c_memberListJson() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//周日期设置
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		//列表内容渲染
		$rows = $this->service->memberListJson_d($projectId, $beginDate, $endDate, $condition);
		foreach ($rows as $key => $val) {
			if (empty($val['personLevel'])) {
				$rows[$key]['personLevel'] = '无';
			}
		}
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 某段时间项目成员列表导出Excel
	 */
	function c_memberListExport() {
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		//周日期设置
		if ($_GET['beginDate']) {
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_GET['condition'];
		//列表内容渲染
		$rows = $this->service->memberListJson_d($projectId, $beginDate, $endDate, $condition);
		foreach ($rows as $key => $val) {
			if (empty($val['personLevel'])) {
				$rows[$key]['personLevel'] = '无';
			} elseif (!empty($val['thisProjectProcess'])) {
				$rows[$key]['thisProjectProcess'] = $val['thisProjectProcess'] . '%';
			}
		}
		return model_engineering_util_esmexcelutil::exportMemberList($rows);
	}

	/**
	 * 项目变更列表
	 */
	function c_pageForMemberView() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('listview');
	}

	/**
	 * 关闭项目
	 */
	function c_closeList() {
		$this->assign('projectId', $_GET['projectId']);
		$this->assign('read', isset($_GET['read']) ? $_GET['read'] : "0");
		$this->view('listclose');
	}

	/**
	 * 列表数据权限过滤
	 */
	function gridDateFilter($rows) {
		//人力预算单价权限点 2013-07-08
		$otherDataDao = new model_common_otherdatas();
		$esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		if (!$esmLimitArr['人力预算单价']) {
			foreach ($rows as $key => $val) {
				$rows[$key]['feePerson'] = '******';
			}
		}
		return $rows;
	}

	/******************** 个人费用信息列表 *******************/
	/**
	 * 我的费用
	 */
	function c_myCostMoney() {
		$this->view('listcostmoney');
	}

	/**
	 * 我的费用 - json
	 */
	function c_pageJsonCostMoney() {
		$service = $this->service;
		$_POST['memberId'] = $_SESSION['USER_ID'];
		$service->getParam($_POST);

		//$service->asc = false;
		$rows = $service->page_d('select_costMoney');
		if (is_array($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/************************* 增删改查 ***********************/
	/**
	 * 跳转到新增项目成员
	 */
	function c_toAdd() {
		$rs = $this->service->getObjInfo_d($_GET['id']);
		$this->assignFunc($rs);
		$this->assign('projectId', $_GET['id']);
		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		if ($this->service->add_d($_POST [$this->objName], true)) {
			msg('新增成功!');
		} else {
			msg('新增失败!');
		}
	}

	/**
	 * 跳转到编辑项目成员页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看项目成员页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/********************** 业务逻辑处理 *********************/
	/**
	 * 判断项目中成员是否已经完全录入离开日期
	 */
	function c_checkMemberAllLeave() {
		echo $this->service->checkMemberAllLeave_d($_POST['projectId']) ? 1 : 0;
	}

	/**
	 * 项目成员唯一验证
	 */
	function c_checkMemberRepeat() {
		$rs = $this->service->find(array('memberId' => $_POST['memberId'], 'projectId' => $_POST['projectId']));
		if (is_array($rs)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 改变启用状态
	 */
	function c_changeStatus() {
		echo $this->service->edit_d($_POST) ? 1 : 0;
	}

	/**
	 * 判断项目中是否存在人员
	 */
	function c_memberIsExsist() {
		echo util_jsonUtil::iconvGB2UTF($this->service->memberIsExsist_d($_POST['projectId'], $_POST['memberId']));
	}

	/**
	 * 判断成员是否可设置
	 */
	function c_memberCanSet() {
		echo util_jsonUtil::encode($this->service->memberCanSet_d($_POST['projectId'],
			$_POST['roleId'], $_POST['memberId'], $_POST['orgMemberId']));
	}

	/**
	 * 人力资源现状图表
	 */
	function c_getChart() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//周日期设置
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		//列表内容渲染
		$pageInfo = $this->service->memberCurrent_d($projectId, $beginDate, $endDate, $condition);
		//图表
		$chartDao = new model_common_fusionCharts ();

		$result = array();
		foreach ($pageInfo as $key => $val) {
			$tempVal = array();
			$tempVal [0] = "1";
			if (empty($val['personLevel'])) {
				$tempVal [1] = '无';
			} else {
				$tempVal [1] = $val ['personLevel'];
			}
			$tempVal [2] = $val ['count(*)'];
			array_push($result, $tempVal);
		}
		$chartConf = array('exportFileName' => "人力资源现状", 'caption' => "人力资源现状", 'exportAtClient' => 0, 'exportAction' => 'download');
		echo util_jsonUtil::iconvGB2UTF($chartDao->showCharts($result, "Pie3D.swf", $chartConf, 360, 280));
	}

	/**
	 * 判断修改的角色成员是否包含项目经理
	 */
	function c_checkHasManager() {
		echo $this->service->checkHasManager_d($_POST);
	}
}