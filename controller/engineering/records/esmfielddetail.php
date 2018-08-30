<?php

/**
 * @author show
 * @Date 2014年12月25日 15:53:13
 * @version 1.0
 * @description:项目决算明细控制层
 */
class controller_engineering_records_esmfielddetail extends controller_base_action
{

	function __construct() {
		$this->objName = "esmfielddetail";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * 保存历史数据
	 */
	function c_saveFeeVersion() {
		set_time_limit(0);
		echo $this->service->saveFeeVersion_d($_POST['budgetType'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
	}

	/**
	 * 查询差异
	 */
	function c_toViewHistory() {
		// 获取第一条和最后一条数据
		$all = $this->service->_db->getArray("SELECT DATE_FORMAT(thisDate,'%Y-%m') as thisDateShow,thisDate FROM oa_esm_records_fielddetail WHERE projectId = " .
			$_GET['projectId'] . " GROUP BY thisDate ORDER BY thisDate DESC");

        $versionOptionsStart = $versionOptionsEnd = "";
		foreach ($all as $k => $v) {
            $versionOptionsStart .= ($k == (count($all)-1))? "<option value='' selected>" . $v['thisDateShow'] . "</option>" : "<option value='" . $all[$k+1]['thisDate'] . "'>" . $v['thisDateShow'] . "</option>";
            $versionOptionsEnd .= "<option value='" . $v['thisDate'] . "'>" . $v['thisDateShow'] . "</option>";
		}
		$this->assign('versionOptionsStart', $versionOptionsStart);
        $this->assign('versionOptionsEnd', $versionOptionsEnd);

		$this->assignFunc($_GET);
		$this->view('viewHistory');
	}

	/**
	 * 查询差异
	 */
	function c_viewHistory() {
		echo util_jsonUtil::encode($this->service->viewHistory_d($_POST));
	}

	/**
	 * 获取日期
	 */
	function c_getDates() {
		// 获取第一条和最后一条数据
		$all = $this->service->_db->getArray("SELECT thisDate FROM oa_esm_records_fielddetail GROUP BY thisDate ORDER BY thisDate DESC");

		// 结果
		$result = array();

		if ($all) {
			foreach ($all as $v) {
				$result[] = array(
					'dataName' => $v['thisDate'],
					'dataCode' => $v['thisDate']
				);
			}
		}
		echo util_jsonUtil::encode($result);
	}
}