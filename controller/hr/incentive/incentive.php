<?php


/**
 * @author Show
 * @Date 2012年5月25日 星期五 14:55:28
 * @version 1.0
 * @description:奖惩管理控制层
 */
class controller_hr_incentive_incentive extends controller_base_action {

	function __construct() {
		$this->objName = "incentive";
		$this->objPath = "hr_incentive";
		parent :: __construct();
	}

	/**
	 * 跳转到奖惩管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到奖惩管理列表--个人
	 */
	function c_pageByPerson() {
		$this->assign('userAccount', $_GET['userAccount']);
		$this->assign('userNo', $_GET['userNo']);
		$this->view('listbyperson');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array ();

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$rows = $service->page_d();

		$arr = array ();
		$arr ['listSql'] = $service->listSql;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		//其余信息加载
		if (!empty ($rows)) {
			$rows = $this->sconfig->md5Rows($rows);

			//合计列生成
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '合计';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr['collection'] = $rows;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 查看页面 - 部门权限
	 */
	function c_pageForRead() {
		$this->view('listforread');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array ();

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$otherdatasDao = new model_common_otherdatas();
		$personLimit = $otherdatasDao->getUserPriv('hr_personnel_personnel', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['JOB_ID']);
		//		print_r($personLimit);
		//系统权限
		$sysLimit = $personLimit['部门权限'];

		//办事处 － 全部 处理
		if (strstr($sysLimit, ';;')) {

			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->page_d();

		} else
			if (!empty ($sysLimit)) { //如果没有选择全部，则进行权限查询并赋值
				$_POST['deptIds'] = $sysLimit;
				$service->getParam($_POST); //设置前台获取的参数信息
				$rows = $service->page_d();
			}

		//其余信息加载
		if (!empty ($rows)) {
			$rows = $this->sconfig->md5Rows($rows);

			//合计列生成
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '合计';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 跳转到新增奖惩管理页面
	*/
	function c_toAdd() {
		$this->showDatadicts(array (
			'incentiveType' => 'HRJLSS'
		), null, true);
		$this->assign('thisUserId', $_SESSION['USER_ID']);
		$this->assign('thisUser', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->view('add',true);
	}

	/**
	 * 跳转到编辑奖惩管理页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'], true));
		$this->showDatadicts(array (
			'incentiveType' => 'HRJLSS'
		), $obj['incentiveType']);
		$this->view('edit',true);
	}

	/**
	 * 跳转到查看奖惩管理页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'], false));
		$this->view('view');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits() {
		$limitName = util_jsonUtil :: iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 导入导出系列 ************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn() {
		$resultArr = $this->service->addExecelData_d();

		$title = '奖惩信息导入结果列表';
		$thead = array (
			'数据信息',
			'导入结果'
		);
		echo util_excelUtil :: showResult($resultArr, $title, $thead);
	}
	/******************* E 导入导出系列 ************************/

	/******************  导出 ***********************/
	/**
	* 导出数据
	*/
	function c_excelOutSelect() {
		$this->assign('listSql', str_replace("&nbsp;", " ", stripslashes(stripslashes($_POST['incentive']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * 导出数据
	 */
	function c_selectExcelOut() {
		//			set_time_limit(600);
		$rows = array (); //数据集
		//		echo "<pre>";
		//		print_R(stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		$listSql = str_replace("&nbsp;", " ", stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if (!empty ($listSql)) {
			$rows = $this->service->_db->getArray($listSql);
		}
		//		echo "<pre>";
		//		print_r($rows);
		$colNameArr = array (); //列名数组
		include_once ("model/hr/incentive/incentiveFieldArr.php");
		if (is_array($_POST['incentive'])) {
			foreach ($_POST['incentive'] as $key => $val) {
				foreach ($incentiveFieldArr as $fKey => $fVal) {
					if ($val == $fKey) {
						$colNameArr[$key] = $fVal;
					}
				}
			}
		}
//		print_r($_POST['contract']);
//		print_r($colNameArr);
		$newColArr = array_combine($_POST['incentive'], $colNameArr); //合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['incentive']);
		if (is_array($rows)) {
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
//				echo "<pre>";
//				print_R($dataArr);
		return model_hr_personnel_personnelExcelUtil :: excelOutIncentive($newColArr, $dataArr);
	}

}
?>