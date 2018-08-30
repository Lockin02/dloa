<?php

/**
 * @author show
 * @Date 2015��2��6�� 9:52:48
 * @version 1.0
 * @description:��Ŀ�ر���ϸ Model��
 */
class model_engineering_close_esmclosedetail extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_close_detail";
		$this->sql_map = "engineering/close/esmclosedetailSql.php";
		parent::__construct();
	}

	/**
	 * ������Ӷ���
	 * @param $objArr
	 * @param null $appendInfo
	 * @return bool
	 * @throws Exception
	 */
	function addBatch_d($objArr, $appendInfo = null) {
		if ($objArr) {
			try {
				foreach ($objArr as $v) {
					if (!isset($v['ruleId']) || empty($v['ruleId'])) {
						continue;
					}
					if ($appendInfo) {
						$v = array_merge($v, $appendInfo);
					}
					$this->add_d($v);
				}
				return true;
			} catch (Exception $e) {
				throw $e;
			}
		} else {
			return true;
		}
	}

	/**
	 * ��ȡĬ�ϵĹ���
	 */
	function getDefaultRule_d() {
		$esmCloseRuleDao = new model_engineering_baseinfo_esmcloserule();
		return $esmCloseRuleDao->getDefaultRule_d();
	}

	/**
	 * ������
	 * @param $rows
	 * @return mixed
	 */
	function dealRule_d($rows) {
		$esmCloseRuleDao = new model_engineering_baseinfo_esmcloserule();
		return $esmCloseRuleDao->dealRule_d($rows);
	}

	/**
	 * �����Ƿ�ȫ������
	 * @param $projectId
	 * @return bool
	 */
	function isAllDeal_d($projectId) {
		return $this->find(array('projectId' => $projectId, 'status' => 0), null, 'id') ? false : true;
	}

	/**
	 * ȷ�ϵ���
	 * @param $ids
	 */
	function confirm_d($ids) {
		return $this->_db->query("UPDATE " . $this->tbl_name . " SET status = 1, confirmId = '" . $_SESSION['USER_ID'] .
			"', confirmName = '" . $_SESSION['USERNAME'] . "', confirmTime = '" . date('Y-m-d H:i:s') .
			"' WHERE id IN(" . $ids . ")");
	}
}