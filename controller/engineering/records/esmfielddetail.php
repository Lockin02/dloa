<?php

/**
 * @author show
 * @Date 2014��12��25�� 15:53:13
 * @version 1.0
 * @description:��Ŀ������ϸ���Ʋ�
 */
class controller_engineering_records_esmfielddetail extends controller_base_action
{

	function __construct() {
		$this->objName = "esmfielddetail";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * ������ʷ����
	 */
	function c_saveFeeVersion() {
		set_time_limit(0);
		echo $this->service->saveFeeVersion_d($_POST['budgetType'], $_POST['year'], $_POST['month'], $_POST['projectCode']);
	}

	/**
	 * ��ѯ����
	 */
	function c_toViewHistory() {
		// ��ȡ��һ�������һ������
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
	 * ��ѯ����
	 */
	function c_viewHistory() {
		echo util_jsonUtil::encode($this->service->viewHistory_d($_POST));
	}

	/**
	 * ��ȡ����
	 */
	function c_getDates() {
		// ��ȡ��һ�������һ������
		$all = $this->service->_db->getArray("SELECT thisDate FROM oa_esm_records_fielddetail GROUP BY thisDate ORDER BY thisDate DESC");

		// ���
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