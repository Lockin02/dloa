<?php

/**
 * @author show
 * @Date 2014��12��19�� 15:41:46
 * @version 1.0
 * @description:������Ŀ�汾��¼���Ʋ�
 */
class controller_engineering_records_esmrecord extends controller_base_action
{

	function __construct() {
		$this->objName = "esmrecord";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * ��ת��������Ŀ�汾��¼�б�
	 */
	function c_page() {
		$this->assignFunc($this->service->getVersionInfo_d());
		$this->view('list');
	}

	/**
	 * �ڽ���Ŀ�б�
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = null;
		$service->setCompany(1);# ���ô��б����ù�˾
		# Ĭ��ָ���ı����
		$service->setComLocal(array(
			"c" => $service->tbl_name
		));

		// ��ȡ����Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		//���´�Ȩ�޲���
		$officeArr = array();
		$sysLimit = $sysLimit['���´�'];

		//ʡ��Ȩ��
		$proLimit = $sysLimit['ʡ��Ȩ��'];

		//������Ȩ��
		$manArr = array();

		//���۸�����Ȩ��
		$saleArr = array();

		//���´� �� ȫ�� ����
		if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false) {
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId();
		} else {//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
			//���´�����Ȩ��
			$officeInfoDao = new model_engineering_officeinfo_officeinfo();
			$officeIds = $officeInfoDao->getOfficeIds_d();
			if (!empty($officeIds)) {
				array_push($officeArr, $officeIds);
			}
			//������Ȩ��
			$managerDao = new model_engineering_officeinfo_manager();
			$manager = $managerDao->getProvincesAndLines_d();
			if (!empty($manager)) {
				$manArr = $manager;
			}
			//������������Ȩ��
			$saleInfo = $service->getProvincesAndCustomerType_d();
			if (!empty($saleInfo)) {
				$saleArr = $saleInfo;
			}
			if (!empty($officeArr) || !empty($manArr) || !empty($saleArr)) {
				$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

				$sqlStr = "sql: and (";
				//���´��ű�����
				if ($officeArr) {
					$sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
				}
				//ʡ�ݽű�����(�����������������)
				if ($manArr || $saleArr) {
					if ($officeArr) $sqlStr .= " or ";
					if (!empty($proLimit)) {//�ж��Ƿ���ʡ��Ȩ��
						$proArr = explode(",", $proLimit);
						$proStr = "";
						foreach ($proArr as $val) {
							$proStr .= "'" . $val . "',";
						}
						$proStr = substr($proStr, 0, strlen($proStr) - 1);
						if (!empty($manArr)) {//���ھ���Ȩ��
							foreach ($manArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
								}
							}
						}
						if (!empty($saleArr)) {//����������������Ȩ��
							foreach ($saleArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$customerTypeArr = explode(",", $val['customerType']);
									$customerTypeStr = "";
									foreach ($customerTypeArr as $value) {
										$customerTypeStr .= "'" . $value . "',";
									}
									$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
									if ($val['province'] == "ȫ��") {
										$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
									} else {
										$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
									}
								}
							}
						}
						$sqlStr .= "(c.province in (" . $proStr . "))";
					} else {
						if (!empty($manArr)) {//���ھ���Ȩ��
							foreach ($manArr as $val) {
								$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
							}
						}
						if (!empty($saleArr)) {//����������������Ȩ��
							foreach ($saleArr as $val) {
								$customerTypeArr = explode(",", $val['customerType']);
								$customerTypeStr = "";
								foreach ($customerTypeArr as $value) {
									$customerTypeStr .= "'" . $value . "',";
								}
								$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
								if ($val['province'] == "ȫ��") {
									$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
								} else {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
								}
							}
						}
						$sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 3);
					}
					$sqlStr .= " ";
				}

				$sqlStr .= " )";
				$service->searchArr['mySearchCondition'] = $sqlStr;

				$rows = $service->pageBySqlId();
			} else if (!empty($proLimit)) {
				$service->getParam($_POST);
				$service->searchArr['mySearchCondition'] = "sql: and c.province in (" . util_jsonUtil::strBuild($proLimit) . ")";
				$rows = $service->pageBySqlId();
			}
		}

		//��չ���ݴ���
		if ($rows) {
			//���ܲ���
			$rows = $this->sconfig->md5Rows($rows);
			//�б����
			$rows = $this->filterWithoutFieldRebuild('���Ȩ��', $rows, 'list');
		}
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * ��Ŀ����
	 */
	function c_exportExcel() {
		set_time_limit(0); // ���ò���ʱ
		$service = $this->service;
		$rows = null;
		$service->setCompany(1);# ���ô��б����ù�˾
		# Ĭ��ָ���ı����
		$service->setComLocal(array(
			"c" => $service->tbl_name
		));

		// ��ȡ����Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		//���´�Ȩ�޲���
		$officeArr = array();
		$sysLimit = $sysLimit['���´�'];

		//ʡ��Ȩ��
		$proLimit = $sysLimit['ʡ��Ȩ��'];

		//������Ȩ��
		$manArr = array();

		//���۸�����Ȩ��
		$saleArr = array();

		// ���ݲ�ѯ����
		$condition = array(
			'version' => $_GET['version']
		);

		//���´� �� ȫ�� ����
		if (strstr($sysLimit, ';;') || strstr($proLimit, ';;')) {
			$service->getParam($condition); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->list_d();
		} else {
			//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
			//���´�����Ȩ��
			$officeInfoDao = new model_engineering_officeinfo_officeinfo();
			$officeIds = $officeInfoDao->getOfficeIds_d();
			if (!empty($officeIds)) {
				array_push($officeArr, $officeIds);
			}
			//������Ȩ��
			$managerDao = new model_engineering_officeinfo_manager();
			$manager = $managerDao->getProvincesAndLines_d();
			if (!empty($manager)) {
				$manArr = $manager;
			}
			//������������Ȩ��
			$saleInfo = $service->getProvincesAndCustomerType_d();
			if (!empty($saleInfo)) {
				$saleArr = $saleInfo;
			}
			if (!empty($officeArr) || !empty($manArr) || !empty($saleArr)) {
				$service->getParam($condition); //����ǰ̨��ȡ�Ĳ�����Ϣ

				$sqlStr = "sql: and (";
				//���´��ű�����
				if ($officeArr) {
					$sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
				}
				//ʡ�ݽű�����(�����������������)
				if ($manArr || $saleArr) {
					if ($officeArr) $sqlStr .= " or ";
					if (!empty($proLimit)) {//�ж��Ƿ���ʡ��Ȩ��
						$proArr = explode(",", $proLimit);
						$proStr = "";
						foreach ($proArr as $val) {
							$proStr .= "'" . $val . "',";
						}
						$proStr = substr($proStr, 0, strlen($proStr) - 1);
						if (!empty($manArr)) {//���ھ���Ȩ��
							foreach ($manArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
								}
							}
						}
						if (!empty($saleArr)) {//����������������Ȩ��
							foreach ($saleArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$customerTypeArr = explode(",", $val['customerType']);
									$customerTypeStr = "";
									foreach ($customerTypeArr as $value) {
										$customerTypeStr .= "'" . $value . "',";
									}
									$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
									if ($val['province'] == "ȫ��") {
										$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
									} else {
										$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
									}
								}
							}
						}
						$sqlStr .= "(c.province in (" . $proStr . "))";
					} else {
						if (!empty($manArr)) {//���ھ���Ȩ��
							foreach ($manArr as $val) {
								$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
							}
						}
						if (!empty($saleArr)) {//����������������Ȩ��
							foreach ($saleArr as $val) {
								$customerTypeArr = explode(",", $val['customerType']);
								$customerTypeStr = "";
								foreach ($customerTypeArr as $value) {
									$customerTypeStr .= "'" . $value . "',";
								}
								$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
								if ($val['province'] == "ȫ��") {
									$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
								} else {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
								}
							}
						}
						$sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 3);
					}
					$sqlStr .= " ";
				}

				$sqlStr .= " )";
				$service->searchArr['mySearchCondition'] = $sqlStr;

				$rows = $service->list_d();
			} else if (!empty($proLimit)) {
				$service->getParam($condition);
				$service->searchArr['mySearchCondition'] = "sql: and c.province in (" . util_jsonUtil::strBuild($proLimit) . ")";
				$rows = $service->list_d();
			}
		}

		if (is_array($rows)) {
			//�б���� -- �����������Ȩ�ޣ���Ҫ�����Ȩ����֤
			$rows = $this->filterWithoutFieldRebuild('���Ȩ��', $rows, 'list');
		}
		model_engineering_util_esmexcelutil::exportProject($rows);
	}

	/**
	 * ��дȨ�޹������� - ��Ϊ��һ����ϵͳ���ֶ�Ҫ����
	 * �ֶ�Ȩ�޿���:���ڶ��ֶεĹ��� - �����б�ͱ�
	 * @param string $key ��һ��������Ȩ������
	 * @param array $rows �ڶ�����������Ҫ���˵�����
	 * @param string $type �����������ǹ������ͣ� form => ��(Ĭ��) ��list => �б�
	 * @return mixed
	 */
	function filterWithoutFieldRebuild($key, $rows, $type = 'form') {
		//����һ���ж�,�����ǰ��¼���Ƿ������������򲻹��˴�����
		$rangeDao = new model_engineering_officeinfo_range();
		if ($rangeDao->userIsManager_d($_SESSION['USER_ID'])) {
			return $rows;
		}

		//������˵������ֶ�
		$filterArr = array(
			'earnedValue', 'perEarnedValue',
			'budgetAll', 'budgetField', 'budgetOutsourcing', 'budgetOther',
			'budgetPerson', 'budgetPeople', 'budgetDay', 'budgetEqu', 'budgetPK',
			'feeAll', 'feeField', 'feeOutsourcing', 'feeOther', 'feeFieldImport', 'feeFlights', 'feePK',
			'feePerson', 'feePeople', 'feeDay', 'feeEqu', 'contractMoney'
		);
		//update by chengl 2012-02-01 ��������ģ��Ȩ���ж�
		$otherDataDao = new model_common_otherdatas();
		$limit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$limitArr = isset($limit[$key]) ? explode(',', $limit[$key]) : array();
		if ($type == 'form') {
			//�������Ŀ����,����Ҫ���˴�Ȩ��,����Ҫ�Ժ�ͬ��������⴦��
			$managerArr = explode(',', $rows['managerId']);
			if (in_array($_SESSION['USER_ID'], $managerArr)) {
				$rows['contractMoney'] = '******';
			} else {
				foreach ($rows as $k => $v) {
					if (in_array($k, $filterArr)) {
						if (!in_array($k, $limitArr)) {
							$rows[$k] = '******';
						}
					}

					if (!in_array('contractMoney', $limitArr)) {
						$rows['exgross'] = '******';
					}
				}
			}
		} elseif ($type == 'list') {
			$i = 0;
			foreach ($rows as $v) {
				foreach ($v as $myKey => $myVal) {
					if (in_array($myKey, $filterArr)) {
						if (!in_array($myKey, $limitArr)) {
							$rows[$i][$myKey] = '******';
						}
					}
				}
				$i++;
			}
		}
		return $rows;
	}

	/**
	 * �������� - �����µ���Ŀ���ݸ��µ���ǰ�汾
	 */
	function c_updateRecord() {
        $data = $this->service->getEsmProjectInfo_d();echo"<prE>";print_r($data);exit();
		set_time_limit(0);

		//��ղ��ر��������
		ob_end_clean();

		// �������ҳ��
		echo file_get_contents(TPL_DIR . '/engineering/records/esmrecord-update.htm');

		flush(); //��������͸��ͻ����������ʹ���������ִ�з������������ JavaScript ����

		// �����������
		$data = $this->service->getEsmProjectInfo_d();
		$dataLength = count($data);
		$dataKeyLength = $dataLength - 1;

		// �汾���ݻ�ȡ
		$versionInfo = $this->service->getVersionInfo_d();

		foreach ($data as $k => $v) {
			// �����ж� - 100������һ�����
			$isRealSave = ($k != 0 && $k % 50 == 0) || $k == $dataKeyLength;

			// ���벢�ҽ����ݲ����
			$this->service->saveRecord_d($v, $versionInfo, $isRealSave);

			if ($isRealSave) {

				$i = $k + 1;
				$length = round($i / $dataLength * 500);
				echo <<<E
                    <script type="text/javascript">
                        updateProgress("($i/$dataLength)������ɣ�", $length);
                    </script>
E;
				flush(); //��������͸��ͻ����������ʹ���������ִ�з������������ JavaScript ����
			}
		}
	}

	/**
	 * ��ת���������
	 */
	function c_toSetUsing() {
		$this->assign('nowVersion', $_GET['nowVersion']);
		$this->view('setusing');
	}

	/**
	 * ����ǰ�����ݱ���һ����Ϊ���·���������
	 */
	function c_setUsing() {
		echo $this->service->setUsing_d($_POST['version'], $_POST['storeYearMonth']);
	}

	/**
	 * ���汾�Ƿ�����
	 */
	function c_checkIsUse() {
		echo $this->service->checkIsUsing_d($_POST['storeYearMonth']);
	}

	/**
	 * ���� �꣬�� ��ȡ�汾����
	 */
	function c_getVersionArr() {
		$arr = $this->service->getVersion_d($_POST['storeYear'], $_POST['storeMonth']);
		if (!empty($arr)) {
			$versionOption = <<<EOT
                <option value="0" style="color:black">......</option>
EOT;
			foreach ($arr as $k => $v) {
				if ($v['isUse'] == '1') {
					$tep = "red";
					$tepC = "(��)";
				} else {
					$tep = "black";
					$tepC = "";
				}
				$versionOption .= <<<EOT
                    <option value="$v[version]" style="color:$tep">$v[version]$tepC</option>
EOT;
			}
			echo util_jsonUtil:: iconvGB2UTF($versionOption);
		} else {
			echo 0;
		}
	}
}