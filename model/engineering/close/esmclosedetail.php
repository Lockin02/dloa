<?php

/**
 * @author show
 * @Date 2015年2月6日 9:52:48
 * @version 1.0
 * @description:项目关闭明细 Model层
 */
class model_engineering_close_esmclosedetail extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_close_detail";
		$this->sql_map = "engineering/close/esmclosedetailSql.php";
		parent::__construct();
	}

	/**
	 * 批量添加对象
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
	 * 获取默认的规则
	 */
	function getDefaultRule_d() {
		$esmCloseRuleDao = new model_engineering_baseinfo_esmcloserule();
		return $esmCloseRuleDao->getDefaultRule_d();
	}

	/**
	 * 规则处理
	 * @param $rows
	 * @return mixed
	 */
	function dealRule_d($rows) {
		$esmCloseRuleDao = new model_engineering_baseinfo_esmcloserule();
		return $esmCloseRuleDao->dealRule_d($rows);
	}

	/**
	 * 规则是否全部处理
	 * @param $projectId
	 * @return bool
	 */
	function isAllDeal_d($projectId) {
		return $this->find(array('projectId' => $projectId, 'status' => 0), null, 'id') ? false : true;
	}

	/**
	 * 确认单据
	 * @param $ids
	 */
	function confirm_d($ids) {
		return $this->_db->query("UPDATE " . $this->tbl_name . " SET status = 1, confirmId = '" . $_SESSION['USER_ID'] .
			"', confirmName = '" . $_SESSION['USERNAME'] . "', confirmTime = '" . date('Y-m-d H:i:s') .
			"' WHERE id IN(" . $ids . ")");
	}
}