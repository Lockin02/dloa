<?php

/**
 * @author show
 * @Date 2014年12月19日 15:41:46
 * @version 1.0
 * @description:工程项目版本记录 Model层
 */
class model_engineering_records_esmrecord extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_records_project";
		$this->sql_map = "engineering/records/esmrecordSql.php";
		parent::__construct();

		// 实例化项目
		$this->esmProjectDao = new model_engineering_project_esmproject();
	}

	// 项目dao
	private $esmProjectDao;

	// 项目数据
	private $esmProjectCache = array();

	/**
	 * 获取项目数据 - 不包含决算信息
	 */
	function getEsmProjectInfo_d() {
		return $this->esmProjectDao->listBySqlId();
	}

	/**
	 * 保存项目数据
	 * @param $obj
	 * @param $versionInfo
	 * @param bool $isRealSave
	 */
	function saveRecord_d($obj, $versionInfo, $isRealSave = false) {
		// 格式化数据
		$obj = $this->esmProjectDao->feeDeal($obj);
		$obj['projectId'] = $obj['id'];
		unset($obj['id']);
		$obj = array_merge($obj, $versionInfo);

		$this->esmProjectCache[] = $obj;

		// 如果确实要保存，则将整个数据插入表中，并且清空cache
		if($isRealSave) {
			// do save
			$this->createBatch($this->esmProjectCache);

			// clean cache
			$this->esmProjectCache = array();
		}
	}

	/**
	 * 获取最大版本
	 */
	function getVersionInfo_d() {
		$versionInfo = array();

		// 查询当前最高版本
		$maxVersion = $this->find(null, 'version DESC', 'version');

		if ($maxVersion) {
			$today = date('Ymd');
			$versionDay = substr($maxVersion['version'], 0, 8);
			if ($today != $versionDay) {
				$versionInfo['version'] = $today . '01';
			} else {
				$versionInfo['version'] = intval($maxVersion['version']) + 1 ;
			}
			$versionInfo['maxVersion'] = $maxVersion['version'];
		} else {
			$versionInfo['version'] = date('Ymd') . '01';
			$versionInfo['maxVersion'] = 0;
		}
		// 年、月份
		$versionInfo['storeYear'] = date('Y');
		$versionInfo['storeMonth'] = date('m');

		return $versionInfo;
	}

	/**
	 * 获取年月包含的版本
	 * @param $storeYear
	 * @param $storeMonth
	 * @return mixed
	 */
	function getVersion_d($storeYear, $storeMonth) {
		$sql = "SELECT version,isUse FROM " . $this->tbl_name ." WHERE storeYear = " . $storeYear .
			" AND storeMonth = " . $storeMonth . " GROUP BY version ORDER BY version DESC";
		return $this->_db->getArray($sql);
	}

	/**
	 * 检测当前月份是否已经有在用版本
	 * @param $storeYearMonth
	 * @return int
	 */
	function checkIsUsing_d($storeYearMonth) {
		$storeYear = substr($storeYearMonth, 0, 4);
		$storeMonth = substr($storeYearMonth, -2);
		$sql = "SELECT id FROM " . $this->tbl_name ." WHERE storeYear = " . $storeYear .
			" AND storeMonth = " . $storeMonth . " AND isUse = 1";
		return $this->_db->getArray($sql) ? 1 : 0;
	}

	/**
	 * 启用版本
	 * @param $version
	 * @param $storeYearMonth
	 * @return int
	 */
	function setUsing_d($version, $storeYearMonth) {
		$storeYear = substr($storeYearMonth, 0, 4);
		$storeMonth = substr($storeYearMonth, -2);

		$versionInfo = $this->getVersionInfo_d();
		if (!$versionInfo['maxVersion']) {
			return -1;
		}

		try {
			$this->start_d();

			// 关闭在用版本
			$this->update(array('isUse' => 1, 'storeYear' => $storeYear, 'storeMonth' => $storeMonth),
				array('isUse' => 0));

			// 启用最新版本
			$this->update(array('version' => $version),
				array('isUse' => 1, 'storeYear' => $storeYear, 'storeMonth' => $storeMonth));

			$this->commit_d();
			return 1;
		} catch (Exception $e) {
			$this->rollBack();
			return 0;
		}
	}

	/**
	 * 获取销售区域负责人所负责的省份与客户类型
	 */
	function getProvincesAndCustomerType_d() {
		$salepersonDao = new model_system_saleperson_saleperson();
		$rs = $salepersonDao->getSaleArea($_SESSION['USER_ID']);
		if (is_array($rs)) {
			$pcArr = array();
			foreach ($rs as $val) {
				if ($val['isUse'] == 0) {
					array_push($pcArr, array('province' => $val['province'], 'customerType' => $val['customerTypeName']));
				}
			}
			return $pcArr;
		} else {
			return '';
		}
	}
}