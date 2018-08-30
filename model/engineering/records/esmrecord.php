<?php

/**
 * @author show
 * @Date 2014��12��19�� 15:41:46
 * @version 1.0
 * @description:������Ŀ�汾��¼ Model��
 */
class model_engineering_records_esmrecord extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_records_project";
		$this->sql_map = "engineering/records/esmrecordSql.php";
		parent::__construct();

		// ʵ������Ŀ
		$this->esmProjectDao = new model_engineering_project_esmproject();
	}

	// ��Ŀdao
	private $esmProjectDao;

	// ��Ŀ����
	private $esmProjectCache = array();

	/**
	 * ��ȡ��Ŀ���� - ������������Ϣ
	 */
	function getEsmProjectInfo_d() {
		return $this->esmProjectDao->listBySqlId();
	}

	/**
	 * ������Ŀ����
	 * @param $obj
	 * @param $versionInfo
	 * @param bool $isRealSave
	 */
	function saveRecord_d($obj, $versionInfo, $isRealSave = false) {
		// ��ʽ������
		$obj = $this->esmProjectDao->feeDeal($obj);
		$obj['projectId'] = $obj['id'];
		unset($obj['id']);
		$obj = array_merge($obj, $versionInfo);

		$this->esmProjectCache[] = $obj;

		// ���ȷʵҪ���棬���������ݲ�����У��������cache
		if($isRealSave) {
			// do save
			$this->createBatch($this->esmProjectCache);

			// clean cache
			$this->esmProjectCache = array();
		}
	}

	/**
	 * ��ȡ���汾
	 */
	function getVersionInfo_d() {
		$versionInfo = array();

		// ��ѯ��ǰ��߰汾
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
		// �ꡢ�·�
		$versionInfo['storeYear'] = date('Y');
		$versionInfo['storeMonth'] = date('m');

		return $versionInfo;
	}

	/**
	 * ��ȡ���°����İ汾
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
	 * ��⵱ǰ�·��Ƿ��Ѿ������ð汾
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
	 * ���ð汾
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

			// �ر����ð汾
			$this->update(array('isUse' => 1, 'storeYear' => $storeYear, 'storeMonth' => $storeMonth),
				array('isUse' => 0));

			// �������°汾
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
	 * ��ȡ�������������������ʡ����ͻ�����
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