<?php

/**
 * @author show
 * @Date 2014年12月19日 15:41:46
 * @version 1.0
 * @description:工程项目版本记录控制层
 */
class controller_engineering_records_esmrecord extends controller_base_action
{

	function __construct() {
		$this->objName = "esmrecord";
		$this->objPath = "engineering_records";
		parent::__construct();
	}

	/**
	 * 跳转到工程项目版本记录列表
	 */
	function c_page() {
		$this->assignFunc($this->service->getVersionInfo_d());
		$this->view('list');
	}

	/**
	 * 在建项目列表
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = null;
		$service->setCompany(1);# 设置此列表启用公司
		# 默认指向表的别称是
		$service->setComLocal(array(
			"c" => $service->tbl_name
		));

		// 获取工程权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		//办事处权限部分
		$officeArr = array();
		$sysLimit = $sysLimit['办事处'];

		//省份权限
		$proLimit = $sysLimit['省份权限'];

		//服务经理权限
		$manArr = array();

		//销售负责人权限
		$saleArr = array();

		//办事处 － 全部 处理
		if (strstr($sysLimit, ';;') !== false || strstr($proLimit, ';;') !== false) {
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->pageBySqlId();
		} else {//如果没有选择全部，则进行权限查询并赋值
			if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
			//办事处经理权限
			$officeInfoDao = new model_engineering_officeinfo_officeinfo();
			$officeIds = $officeInfoDao->getOfficeIds_d();
			if (!empty($officeIds)) {
				array_push($officeArr, $officeIds);
			}
			//服务经理权限
			$managerDao = new model_engineering_officeinfo_manager();
			$manager = $managerDao->getProvincesAndLines_d();
			if (!empty($manager)) {
				$manArr = $manager;
			}
			//销售区域负责人权限
			$saleInfo = $service->getProvincesAndCustomerType_d();
			if (!empty($saleInfo)) {
				$saleArr = $saleInfo;
			}
			if (!empty($officeArr) || !empty($manArr) || !empty($saleArr)) {
				$service->getParam($_POST); //设置前台获取的参数信息

				$sqlStr = "sql: and (";
				//办事处脚本构建
				if ($officeArr) {
					$sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
				}
				//省份脚本构建(经理或销售区域负责人)
				if ($manArr || $saleArr) {
					if ($officeArr) $sqlStr .= " or ";
					if (!empty($proLimit)) {//判断是否有省份权限
						$proArr = explode(",", $proLimit);
						$proStr = "";
						foreach ($proArr as $val) {
							$proStr .= "'" . $val . "',";
						}
						$proStr = substr($proStr, 0, strlen($proStr) - 1);
						if (!empty($manArr)) {//存在经理权限
							foreach ($manArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
								}
							}
						}
						if (!empty($saleArr)) {//存在销售区域负责人权限
							foreach ($saleArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$customerTypeArr = explode(",", $val['customerType']);
									$customerTypeStr = "";
									foreach ($customerTypeArr as $value) {
										$customerTypeStr .= "'" . $value . "',";
									}
									$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
									if ($val['province'] == "全国") {
										$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
									} else {
										$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
									}
								}
							}
						}
						$sqlStr .= "(c.province in (" . $proStr . "))";
					} else {
						if (!empty($manArr)) {//存在经理权限
							foreach ($manArr as $val) {
								$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
							}
						}
						if (!empty($saleArr)) {//存在销售区域负责人权限
							foreach ($saleArr as $val) {
								$customerTypeArr = explode(",", $val['customerType']);
								$customerTypeStr = "";
								foreach ($customerTypeArr as $value) {
									$customerTypeStr .= "'" . $value . "',";
								}
								$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
								if ($val['province'] == "全国") {
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

		//扩展数据处理
		if ($rows) {
			//加密部分
			$rows = $this->sconfig->md5Rows($rows);
			//列表金额处理
			$rows = $this->filterWithoutFieldRebuild('金额权限', $rows, 'list');
		}
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * 项目导出
	 */
	function c_exportExcel() {
		set_time_limit(0); // 设置不超时
		$service = $this->service;
		$rows = null;
		$service->setCompany(1);# 设置此列表启用公司
		# 默认指向表的别称是
		$service->setComLocal(array(
			"c" => $service->tbl_name
		));

		// 获取工程权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		//办事处权限部分
		$officeArr = array();
		$sysLimit = $sysLimit['办事处'];

		//省份权限
		$proLimit = $sysLimit['省份权限'];

		//服务经理权限
		$manArr = array();

		//销售负责人权限
		$saleArr = array();

		// 数据查询条件
		$condition = array(
			'version' => $_GET['version']
		);

		//办事处 － 全部 处理
		if (strstr($sysLimit, ';;') || strstr($proLimit, ';;')) {
			$service->getParam($condition); //设置前台获取的参数信息
			$rows = $service->list_d();
		} else {
			//如果没有选择全部，则进行权限查询并赋值
			if (!empty($sysLimit)) array_push($officeArr, $sysLimit);
			//办事处经理权限
			$officeInfoDao = new model_engineering_officeinfo_officeinfo();
			$officeIds = $officeInfoDao->getOfficeIds_d();
			if (!empty($officeIds)) {
				array_push($officeArr, $officeIds);
			}
			//服务经理权限
			$managerDao = new model_engineering_officeinfo_manager();
			$manager = $managerDao->getProvincesAndLines_d();
			if (!empty($manager)) {
				$manArr = $manager;
			}
			//销售区域负责人权限
			$saleInfo = $service->getProvincesAndCustomerType_d();
			if (!empty($saleInfo)) {
				$saleArr = $saleInfo;
			}
			if (!empty($officeArr) || !empty($manArr) || !empty($saleArr)) {
				$service->getParam($condition); //设置前台获取的参数信息

				$sqlStr = "sql: and (";
				//办事处脚本构建
				if ($officeArr) {
					$sqlStr .= " c.officeId in (" . implode(array_unique(explode(",", implode($officeArr, ','))), ',') . ") ";
				}
				//省份脚本构建(经理或销售区域负责人)
				if ($manArr || $saleArr) {
					if ($officeArr) $sqlStr .= " or ";
					if (!empty($proLimit)) {//判断是否有省份权限
						$proArr = explode(",", $proLimit);
						$proStr = "";
						foreach ($proArr as $val) {
							$proStr .= "'" . $val . "',";
						}
						$proStr = substr($proStr, 0, strlen($proStr) - 1);
						if (!empty($manArr)) {//存在经理权限
							foreach ($manArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
								}
							}
						}
						if (!empty($saleArr)) {//存在销售区域负责人权限
							foreach ($saleArr as $val) {
								if (!in_array($val['province'], $proArr)) {
									$customerTypeArr = explode(",", $val['customerType']);
									$customerTypeStr = "";
									foreach ($customerTypeArr as $value) {
										$customerTypeStr .= "'" . $value . "',";
									}
									$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
									if ($val['province'] == "全国") {
										$sqlStr .= " (c.customerType in (" . $customerTypeStr . ")) or ";
									} else {
										$sqlStr .= " (c.province = '" . $val['province'] . "' and c.customerTypeName in (" . $customerTypeStr . ")) or ";
									}
								}
							}
						}
						$sqlStr .= "(c.province in (" . $proStr . "))";
					} else {
						if (!empty($manArr)) {//存在经理权限
							foreach ($manArr as $val) {
								$sqlStr .= " (c.province = '" . $val['province'] . "' and c.productLine = '" . $val['productLine'] . "') or ";
							}
						}
						if (!empty($saleArr)) {//存在销售区域负责人权限
							foreach ($saleArr as $val) {
								$customerTypeArr = explode(",", $val['customerType']);
								$customerTypeStr = "";
								foreach ($customerTypeArr as $value) {
									$customerTypeStr .= "'" . $value . "',";
								}
								$customerTypeStr = substr($customerTypeStr, 0, strlen($customerTypeStr) - 1);
								if ($val['province'] == "全国") {
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
			//列表金额处理 -- 如果存在区域权限，则要做金额权限验证
			$rows = $this->filterWithoutFieldRebuild('金额权限', $rows, 'list');
		}
		model_engineering_util_esmexcelutil::exportProject($rows);
	}

	/**
	 * 重写权限过滤数组 - 因为有一个非系统的字段要过滤
	 * 字段权限控制:用于对字段的过滤 - 包括列表和表单
	 * @param string $key 第一个参数是权限名称
	 * @param array $rows 第二个参数是需要过滤的数组
	 * @param string $type 第三个参数是过滤类型： form => 表单(默认) ，list => 列表
	 * @return mixed
	 */
	function filterWithoutFieldRebuild($key, $rows, $type = 'form') {
		//加入一个判断,如果当前登录人是服务经理、区域经理，则不过滤此内容
		$rangeDao = new model_engineering_officeinfo_range();
		if ($rangeDao->userIsManager_d($_SESSION['USER_ID'])) {
			return $rows;
		}

		//定义过滤的数组字段
		$filterArr = array(
			'earnedValue', 'perEarnedValue',
			'budgetAll', 'budgetField', 'budgetOutsourcing', 'budgetOther',
			'budgetPerson', 'budgetPeople', 'budgetDay', 'budgetEqu', 'budgetPK',
			'feeAll', 'feeField', 'feeOutsourcing', 'feeOther', 'feeFieldImport', 'feeFlights', 'feePK',
			'feePerson', 'feePeople', 'feeDay', 'feeEqu', 'contractMoney'
		);
		//update by chengl 2012-02-01 加入另外模块权限判断
		$otherDataDao = new model_common_otherdatas();
		$limit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$limitArr = isset($limit[$key]) ? explode(',', $limit[$key]) : array();
		if ($type == 'form') {
			//如果是项目经理,则不需要过滤此权限,但是要对合同金额做特殊处理
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
	 * 更新数据 - 将最新的项目数据更新到当前版本
	 */
	function c_updateRecord() {
        $data = $this->service->getEsmProjectInfo_d();echo"<prE>";print_r($data);exit();
		set_time_limit(0);

		//清空并关闭输出缓存
		ob_end_clean();

		// 首先输出页面
		echo file_get_contents(TPL_DIR . '/engineering/records/esmrecord-update.htm');

		flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。

		// 这里加载数据
		$data = $this->service->getEsmProjectInfo_d();
		$dataLength = count($data);
		$dataKeyLength = $dataLength - 1;

		// 版本数据获取
		$versionInfo = $this->service->getVersionInfo_d();

		foreach ($data as $k => $v) {
			// 条件判断 - 100个进行一次输出
			$isRealSave = ($k != 0 && $k % 50 == 0) || $k == $dataKeyLength;

			// 补齐并且将数据插入表
			$this->service->saveRecord_d($v, $versionInfo, $isRealSave);

			if ($isRealSave) {

				$i = $k + 1;
				$length = round($i / $dataLength * 500);
				echo <<<E
                    <script type="text/javascript">
                        updateProgress("($i/$dataLength)操作完成！", $length);
                    </script>
E;
				flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
			}
		}
	}

	/**
	 * 跳转到保存界面
	 */
	function c_toSetUsing() {
		$this->assign('nowVersion', $_GET['nowVersion']);
		$this->view('setusing');
	}

	/**
	 * 将当前的数据保存一份作为最新发布的数据
	 */
	function c_setUsing() {
		echo $this->service->setUsing_d($_POST['version'], $_POST['storeYearMonth']);
	}

	/**
	 * 检查版本是否已有
	 */
	function c_checkIsUse() {
		echo $this->service->checkIsUsing_d($_POST['storeYearMonth']);
	}

	/**
	 * 根据 年，月 获取版本数据
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
					$tepC = "(存)";
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