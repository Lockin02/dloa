<?php

/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:01:27
 * @version 1.0
 * @description:工作日志(oa_esm_worklog)控制层 每日进展
 */
class controller_engineering_worklog_esmworklog extends controller_base_action
{

	function __construct() {
		$this->objName = "esmworklog";
		$this->objPath = "engineering_worklog";
		parent::__construct();
	}

	/**
	 * 跳转到工作日志
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到工作Tab日志
	 */
	function c_toDeptLog() {
		//加入权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['部门权限'];
		$deptIds = null;
		if (!strstr($deptLimit, ';;')) {
			if (empty($deptLimit)) {
				$deptIds = $_SESSION['DEPT_ID'];
			} else {
				$deptIds = $deptLimit . ',' . $_SESSION['DEPT_ID'];
			}
		}
		$this->assign('deptIds', $deptIds);
		$this->view('deptlog');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//处理一个特殊拼装条件
		if ($_POST['memberIdsSearch'] && $_POST['personType']) {
			$memberIds = util_jsonUtil::strBuild($_POST['memberIdsSearch']);
			$personType = util_jsonUtil::strBuild($_POST['personType']);
			$sqlStr = "sql: and createId in (select userAccount from oa_hr_personnel where userAccount in ($memberIds) and personnelType in ($personType))";
			$service->searchArr['mySearchCondition'] = $sqlStr;
		}

		$service->sort = 'c.executionDate asc,c.activityName';
		$rows = $service->list_d();
		foreach ($rows as &$val) {
			$val['workStatus'] = $this->getDataNameByCode($val['workStatus']);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_pageJsonWorkLog() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//处理一个特殊拼装条件
		if ($_POST['memberIdsSearch'] && $_POST['personType']) {
			$memberIds = util_jsonUtil::strBuild($_POST['memberIdsSearch']);
			$personType = util_jsonUtil::strBuild($_POST['personType']);
			$sqlStr = "sql: and createId in (select userAccount from oa_hr_personnel where userAccount in ($memberIds) and personnelType in ($personType))";
			$service->searchArr['mySearchCondition'] = $sqlStr;
		}

		$service->sort = 'c.executionDate asc,c.activityName';
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 根据传入的信息获取工作日志
	 */
	function c_getDetailList() {
		$obj = array(
			'beginDate' => isset($_GET['beginDate']) ? $_GET['beginDate'] : '',
			'endDate' => isset($_GET['endDate']) ? $_GET['endDate'] : '',
			'projectId' => isset($_GET['projectId']) ? $_GET['projectId'] : '',
			'createId' => isset($_GET['createId']) ? $_GET['createId'] : ''
		);
		$this->assignFunc($obj);
		$this->view('detail-list');
	}

	/**
	 *
	 * 跳转到项目日志列表
	 */
	function c_toProList() {
		$this->assign("projectId", $_GET['projectId']);

		//获取权限
		$otherDataDao = new model_common_otherdatas();
		$thisLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		$this->assign('unauditLimit', isset($thisLimitArr['日志反审核权限']) ? $thisLimitArr['日志反审核权限'] : 0);

		$this->view("project-list");
	}

	/*跳转到项目日志*/
	function c_toProLogTab() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("prolog-tab");
	}

	/*跳转到管理项目日志*/
	function c_toProLogManageTab() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("prologmanage-tab");
	}

	/**
	 * 跳转到日志审核页面
	 */
	function c_toAuditList() {
		$this->assign("projectId", $_GET['projectId']);
		$this->assign("activityId", $_GET['activityId']);

		//初始化员工属性
		$datadictDao = new model_system_datadict_datadict();
		$datadictArr = $datadictDao->getSpeDatadict_d('HRYGLX');
		$this->assign('personType', $datadictDao->datadictShow_d($datadictArr));

		$this->view("audit-list");
	}

	/**
	 * 成员统计审核信息
	 */
	function c_auditJsonForPerson() {
		$service = $this->service;
		$service->getParam($_POST);

		//处理一个特殊拼装条件
		if ($_POST['memberIdsSearch'] && $_POST['personType']) {
			$memberIds = util_jsonUtil::strBuild($_POST['memberIdsSearch']);
			$personType = util_jsonUtil::strBuild($_POST['personType']);
			$service->searchArr['mySearchCondition'] =
				"sql: and createId in (select userAccount from oa_hr_personnel where userAccount in ($memberIds) and personnelType in ($personType))";
		}

		$service->groupBy = 'c.createId,c.activityId';
		$rows = $service->page_d('search_json');
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 日志查询部分
	 */
	function c_toSearchList() {
		//周日期设置
		$this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
		$this->assign("projectId", $_GET['projectId']);
		$this->view("search-list");
	}

	/**
	 * 日志查询json
	 */
	function c_searchJson() {
		$service = $this->service;

		// 获取用户的数据
        $rows = $service->findMember_d($_POST['projectId']);
		$rows = $service->appendLogInfo_d($rows, '');

		// 查询用户数据
		$memberIdArr = array();
		foreach ($rows as $v) {
			$memberIdArr[] = $v['memberId'];
		}
		$memberIds = implode(',', $memberIdArr);

		// 日期数据 - 构建用户从开始到结束的日期数据
        $weekDao = new model_engineering_baseinfo_week();
        $dateData = $weekDao->buildDateData($_REQUEST['beginDate'], $_REQUEST['endDate']);

		// 用户信息 - 入离职
		$personInfo = $this->service->getPersonnelInfoMap_d($memberIds);
		// 请休假信息
		$hols = $this->service->getHolsHash_d(strtotime($_POST['beginDate']), strtotime($_POST['endDate']), $memberIds);

		// 加入人员出入处理
		$entryDao = new model_engineering_member_esmentry();
		// 项目出入数据
		$entryData = $entryDao->getEntryMap_d($_POST['projectId'], $memberIds);

		$logs = $this->service->getAssessmentLogMap_d($_POST['beginDate'], $_POST['endDate'], $memberIds);

		// 统计天数
		$countDay = count($dateData);

		foreach ($rows as $k => $v) {
			$innerData = $dateData;

			// 入离职数据处理
			$innerData = $this->service->dealEntryQuit_d($innerData, '', $personInfo[$v['createId']]);

			// 加入请休假数据
			$innerData = $this->service->dealHolds_d($innerData, '', '', $v['createId'], $hols);

			// 出入数据
			$innerData = $entryDao->dealEntry_d($innerData, '', $v['createId'], $entryData);

			// 人员日志附加
			$innerData = $this->service->dealLog_d($innerData, $_POST['projectId'], $logs[$v['createId']]);

//			echo util_jsonUtil::encode($logs);die;

			// 合计
			$sumRow = array('id' => 'noId', 'executionDate' => '合计');

			foreach ($innerData as $vi) {
				$sumRow['inWorkRate'] = bcadd($sumRow['inWorkRate'], $vi['inWorkRate'], 2);
				$sumRow['noNeed'] = bcadd($sumRow['noNeed'], $vi['noNeed'], 2);
				$sumRow['monthScore'] = bcadd($sumRow['monthScore'], $vi['monthScore'], 2);
				$sumRow['workloadDay'] = bcadd($sumRow['workloadDay'], $vi['workloadDay'], 2);
			}

			$rows[$k]['inWorkRate'] = $sumRow['inWorkRate'];
			$rows[$k]['monthScore'] = $sumRow['monthScore'];
			$rows[$k]['workloadDay'] = $sumRow['workloadDay'];
			$rows[$k]['noNeed'] = $sumRow['noNeed'];
			$rows[$k]['countDay'] = $countDay - $sumRow['noNeed'];
		}

		if (!empty($rows)) {
            //加载项目合计
            $objArr = $service->getNewSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '合 计';
                $objArr['createId'] = 'noId';
            }
			$rows[] = $objArr;
		}
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 日志详细
	 */
	function c_toSearchDetailList() {
		$this->assignFunc($_GET);
		$this->view("searchdetail-list");
	}

	/**
	 * 日志详细查询
	 */
	function c_searchDetailJson() {
		// 日期数据 - 构建用户从开始到结束的日期数据
        $weekDao = new model_engineering_baseinfo_week();
        $data = $weekDao->buildDateData($_POST['beginDateThan'], $_POST['endDateThan']);

		// 入离职数据处理
		$data = $this->service->dealEntryQuit_d($data, $_POST['createId']);

		// 加入请休假数据
		$data = $this->service->dealHolds_d($data, $_POST['beginDateThan'], $_POST['endDateThan'], $_POST['createId']);

		// 加入人员出入处理
		$entryDao = new model_engineering_member_esmentry();
		$data = $entryDao->dealEntry_d($data, $_POST['projectId'], $_POST['createId']);

		// 人员日志获取
		$logs = $this->service->getAssessmentLogMap_d($_POST['beginDateThan'], $_POST['endDateThan'], $_POST['createId']);

		// 人员日志附加
		$data = $this->service->dealLog_d($data, $_POST['projectId'], $logs[$_POST['createId']]);

		// 合计
		$sumRow = array('id' => 'noId', 'executionDate' => '合计');

		foreach ($data as $v) {
			$sumRow['inWorkRate'] = bcadd($sumRow['inWorkRate'], $v['inWorkRate'], 2);
			$sumRow['monthScore'] = bcadd($sumRow['monthScore'], $v['monthScore'], 2);
			$sumRow['workload'] = bcadd($sumRow['workload'], $v['workload'], 2);
		}
		$data[] = $sumRow;

        echo util_jsonUtil::encode($data);
	}

    /**
     * 日志详细
     */
    function c_toNewSearchDetailList() {
        $this->assignFunc($_GET);
        $this->view("newsearchdetail-list");
    }

    /**
     * 日志详细查询
     */
    function c_newSearchDetailJson() {
        $service = $this->service;

        // 提取过滤条件
        $searchItem = $_POST;

        // 提取项目ID
        $projectId = $searchItem['projectId'];
        unset($searchItem['projectId']);

        // 日志数据获取
        $rows = $service->getAssessment_d($searchItem);

        //识别未填处理
        $rows = $service->dealUnLog_d($rows, $searchItem['beginDate'], $searchItem['endDate'], $projectId);

        if (!empty($rows)) {
            $countArr = array(
                'id' => 'noId',
                'executionDate' => '合 计',
                'inWorkRate' => 0,
                'workloadDay' => 0
            );
            foreach ($rows as $v) {
                $countArr['inWorkRate'] = bcadd($v['inWorkRate'], $countArr['inWorkRate'], 2);
                $countArr['accessScore'] = bcadd($v['accessScore'], $countArr['accessScore'], 2);
                $countArr['workloadDay'] = bcadd($v['workloadDay'], $countArr['workloadDay'], 2);
            }
            $rows[] = $countArr;
        }
        echo util_jsonUtil::encode($rows);
    }

	/**
	 * 日志查询部分 ----------- 部门
	 */
	function c_toSearchDeptList() {
		//周日期设置
		$this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
		//设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
		$this->assignFunc($projectDao->getDefaultDept_d());
		$this->view("searchdept-list");
	}

	/**
	 * 日志查询 --------------- 部门
	 */
	function c_searchDeptJson() {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getSearchDeptList_d($_POST);
        $rows = $service->appendLogInfo_d($rows, $_POST); // 加载用户等级
        if (!empty($rows)) {
            //加载项目合计
            $objArr = $service->getSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '合 计';
                $objArr['createId'] = 'noId';
            }
            $rows[] = $objArr;
        }
        //这里转html
        echo util_jsonUtil::iconvGB2UTF($service->searchDeptHtml_d($rows));
    }

	/**
	 * 日志查询 - 导出
	 */
	function c_exportSearchDeptJson() {
        set_time_limit(0);
        $rows = $this->service->getSearchDeptList_d($_GET);
        $rows = $this->service->appendLogInfo_d($rows, $_GET); // 加载用户等级
        //定义表头
        $thArr = array('createName' => '姓名', 'deptName' => '部门名称', 'personLevel' => '人员等级', 'userNo' => '员工编号', 'projectCode' => '项目编号',
            'projectName' => '项目名称', 'inWorkRate' => '人力投入', 'auditScore' => '考核系数', 'perAuditScore' => '平均考核系数', 'monthAuditScore' => '月考核系数',
            'workCoefficient' => '工作系数', 'thisProjectProcess' => '项目完成量(%)', 'processCoefficient' => '进展系数', 'costMoney' => '费用'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '工程工作量统计');
    }

    /**
     * 日志查询部分 ----------- 部门（新）
     */
    function c_toNewSearchDeptList() {
        //周日期设置
        $this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
        $this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
        //设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
        $deptLimit = $projectDao->getDefaultDept_d(true);
        if ($deptLimit['deptId'] == 'all') {
            $this->assignFunc(array(
                'deptId' => '',
                'deptName' => ''
            ));
        } else {
            $this->assignFunc($deptLimit);
        }
        $this->assign('deptNameLimit', $deptLimit['deptName']);
        $this->view("newsearchdept-list");
    }

    /**
     * 日志查询 --------------- 部门（新）
     */
    function c_newSearchDeptJson() {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getAssessData_d($_POST);
        $rows = $service->appendLogInfo_d($rows, $_POST['deptId']); // 加载用户等级
        if (!empty($rows)) {
            //加载项目合计
            $objArr = $service->getNewSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '合 计';
                $objArr['createId'] = 'noId';
            }
            $rows[] = $objArr;
        }
        //这里转html
        echo util_jsonUtil::iconvGB2UTF($service->newSearchDeptHtml_d($rows));
    }

    /**
     * 日志查询 - 导出
     */
    function c_exportNewSearchDept() {
        set_time_limit(0);
        $rows = $this->service->getAssessData_d($_GET);
        $rows = $this->service->appendLogInfo_d($rows, $_GET['deptId']); // 加载用户等级
        //定义表头
        $thArr = array('createName' => '姓名', 'userNo' => '员工编号', 'deptName' => '所属部门',
            'projectCode' => '项目编号', 'inWorkRate' => '考勤投入', 'monthScore' => '考核得分',
            'countDay' => '统计天数', 'attendance' => '出勤系数', 'assess' => '考核系数',
            'hols' => '请休假天数'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '工程工作量统计');
    }
	/************************ 我的工作部分 *************************/
	/**
	 * 我的工作
	 */
	function c_toMyJobs() {
		$this->view('myjobs');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam($_POST);

		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 导出我的日志
	 */
	function c_toExportMyLog() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$beginDate = $weekBEArr['beginDate'];
		$endDate = $weekBEArr['endDate'];
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('userAccount', $_SESSION['USER_ID']);
		$this->view('myoutexcel');
	}

	/**
	 * 新增页面 - 新
	 */
	function c_toAdd() {
		$rs = $this->service->getLastInfo_d();
		$rs ['thisDate'] = day_date;

		$this->assignFunc($rs);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $rs['workStatus'], false);

		//工作量比例计算
		$countInfo = $this->service->getDayUserCountInfo_d(day_date);
		$this->assign('maxInWorkRate', bcsub(100, $countInfo['inWorkRate'], 2));

		$this->assign("userId", $_SESSION['USER_ID']);
		$this->assign("createName", $_SESSION['USERNAME']);

		//工作量单位
		$this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), null, true);
		$this->view('add', true);
	}

	/**
	 * 新增方法 - 新
	 */
	function c_add() {
		$this->checkSubmit();
		if ($this->service->add_d($_POST[$this->objName])) {
			msgRf('保存成功');
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toEdit() {
		$object = $this->service->get_d($_GET['id']);
		$this->assignFunc($object);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $object['workStatus'], false);

		$this->assign('invbody', $this->service->initCost_d($_GET['id']));

		//工作量比例计算
		$countInfo = $this->service->getDayUserCountInfo_d($object['executionDate']);
		$this->assign('maxInWorkRate', bcAdd(bcsub(100, $countInfo['inWorkRate'], 2), $object['inWorkRate'], 2));

		//工作量单位
		$this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $object['workloadUnit'], true);
		$this->assign('workloadUnitDefault', $object['workloadUnit']);

		$this->view('edit', true);
	}

	/**
	 * 修改日志
	 * @see controller_base_action::c_edit()
	 */
	function c_edit() {
		$this->checkSubmit();
		if ($this->service->edit_d($_POST[$this->objName])) {
			msgRf('保存成功');
		}
	}

	/**
	 * 重新查看页面
	 */
	function c_toView() {
		$costdetailId = isset($_GET['costdetailId']) ? $_GET['costdetailId'] : '';

		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('workStatus', $this->getDataNameByCode($obj['workStatus']));
		$this->assign('invbody', $this->service->initCostView_d($_GET['id']));

		//
		$this->assign('costdetailId', $costdetailId);
		$this->display('view');
	}

	/**
	 * 批量新增页面
	 */
	function c_toBatchAdd() {
		$this->assign("userId", $_SESSION['USER_ID']);

		//工作量比例计算
		$countInfo = $this->service->getDayUserCountInfo_d(day_date);
		$this->assign('maxInWorkRate', bcsub(100, $countInfo['inWorkRate'], 2));

		$rs = $this->service->getLastInfo_d();
		$rs['thisDate'] = day_date;

		$this->assignFunc($rs);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $rs['workStatus'], false);

        // 获取入职日期
        $personnelInfo = $this->service->getPersonnelInfo_d();
        $this->assign('entryDate', $personnelInfo['entryDate']);

		$this->view('addbatch', true);
	}

	/**
	 * 新增方法 - 新
	 */
	function c_batchAdd() {
		$this->checkSubmit();
		$rs = $this->service->batchAdd_d($_POST[$this->objName]);
		if ($rs === true) {
			msgRf('保存成功');
		} else {
			if ($rs) {
				msgRf($rs);
			}
		}
	}

	/**
	 * 日志审核
	 */
	function c_auditLog() {
		echo $this->service->auditLog_d($_POST['id'], $_POST['assessResult'],
			util_jsonUtil::iconvUTF2GB($_POST['feedBack'])) ? 1 : 0;
	}

	/**
	 * 日志审核 -- 按人员批量
	 */
	function c_auditLogForPerson() {
		echo $this->service->auditLogForPerson_d($_POST) ? 1 : 0;
	}

	/**
	 * 打回日志
	 */
	function c_backLog() {
		echo $this->service->backLog_d($_POST['id'], $_POST['assessResult'],
			util_jsonUtil::iconvUTF2GB($_POST['feedBack'])) ? 1 : 0;
	}

	/**
	 * 打回日志 -- 按人员批量
	 */
	function c_backLogForPerson() {
		echo $this->service->backLogForPerson_d($_POST) ? 1 : 0;
	}

	/**
	 * 反审核日志
	 */
	function c_unauditLog() {
		$rs = $this->service->unauditLog_d($_POST['id']);
		if (is_numeric($rs)) {
			echo 1;
		} else {
			echo util_jsonUtil::iconvGB2UTF($rs);
		}
	}

	//批量反审核日志
	function c_batchUnauditLog() {
		$this->service->getParam($_POST);
		$rows = $this->service->list_d();
		$success = 0;
		$fail = 0;
		foreach ($rows as $key => $val) {
			if ($val['confirmStatus'] == "1") {
				$rs = $this->service->unauditLog_d($val['id']);
				if ($rs) {
					$success++;
				} else {
					$fail++;
				}
			}
		}
		msgRf('取消成功' . $success . "个" . "，取消失败" . $fail . "个");
	}

	/************************ 单人日志考核呈现 add chenrf**************************/
	/**
	 * 跳转日志考核呈现列表
	 *
	 */
	function c_toAssessment() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$this->assign('beginDate', $weekBEArr['beginDate']);
		$this->assign('endDate', $weekBEArr['endDate']);

		$this->view('assessment-list');
	}

	/**
	 * 日志考核呈现列表
	 *
	 */
	function c_assessment() {
        $service = $this->service;

        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['部门权限'];
        if (!strstr($deptLimit, ';;')) {
            if (empty($deptLimit)) {
                $_REQUEST['deptId'] = $_SESSION['DEPT_ID'];
            } else {
                $_REQUEST['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
            }
        }

        $service->getParam($_REQUEST);
        $service->sort = 'c.executionDate';
        $service->asc = false;
        $rows = $service->list_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
	}

    /**
     * 跳转日志考核呈现列表（新）
     *
     */
    function c_toNewAssessment() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
        $this->assign('beginDate', $weekBEArr['beginDate']);
        $this->assign('endDate', $weekBEArr['endDate']);

        //设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d(true));
        $this->view('newassessment-list');
    }

    /**
     * 日志考核呈现列表
     *
     */
    function c_newAssessment() {
        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['部门权限'];
        if (!strstr($deptLimit, ';;')) {
            if (empty($deptLimit)) {
                $_REQUEST['deptId'] = $_SESSION['DEPT_ID'];
            } else {
                $_REQUEST['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
            }
        }
        $rows = $this->service->getAssessment_d($_REQUEST);

        // 汇总行处理
        if ($rows) {
            // 合计列
            $countRow = array(
                'projectCode' => '合计',
                'inWorkRate' => 0,
                'score' => 0,
                'accessScore' => 0
            );
            foreach ($rows as $v) {
                $countRow['inWorkRate'] = bcadd($countRow['inWorkRate'], $v['inWorkRate']);
                $countRow['score'] = bcadd($countRow['score'], $v['score']);
                $countRow['accessScore'] = bcadd($countRow['accessScore'], $v['accessScore'], 2);
            }
            $countRow['score'] = bcdiv($countRow['score'], count($rows), 2);
            $rows[] = $countRow;
        }

        echo util_jsonUtil::encode($rows);
    }

	/*********************告警视图**************************************/
	/**
	 * 跳转到告警视图
	 * Enter description here ...
	 */
	function c_toWarnView() {
		$this->assign('beginDate', date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', date('Y-m-t', strtotime('-1 month')));

        //设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 'feeDeptId' => '', 't' => '', 'createId' => 'createId'
        ), $_GET));
		$this->view('warnView-list');
	}

	/**
	 *
	 * 告警视图搜索数据
	 */
	function c_warnView() {
		echo util_jsonUtil::encode($this->service->warnView_d($_POST));
	}

    /**
     * 跳到告警视图汇总
     */
    function c_toWarnSummary() {
        $this->assign('beginDate', date('Y-m-01', strtotime('-1 month')));
        $this->assign('endDate', date('Y-m-t', strtotime('-1 month')));

        //设置默认选择部门
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 'feeDeptId' => '', 't' => ''
        ), $_GET));
        $this->view('warnSummary-list');
    }

    /**
     * 告警视图汇总
     */
    function c_warnSummary() {
        echo util_jsonUtil::encode($this->service->warnSummary_d($_POST));
    }

    /**
     * 获取日志告警的数量
     */
    function c_warnCount() {
        $waringRow = $this->service->warnView_d($_POST);
        echo util_jsonUtil::encode(array_merge(
            $_POST, array(
                'warningNum' => count($waringRow)
            )
        ));
    }

	/**
	 * 导出日志告警视图
	 */
	function c_exportLogEmergencyJson() {
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->warnView_d($_GET);
		//定义表头
		$thArr = array(
			'executionDate' => '日期', 'createName' => '姓名', 'belongDeptName' => '归属部门',
            'jobName' => '职位(级别)', 'msg' => '告警'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '日志告警');
	}

	/********************* 外包查询部分 ********************/
	/**
	 * 外包人员日志
	 */
	function c_toSuppUserLog() {
		$this->view('suppuserlog-list');
	}

	/**
	 * 外包人员日志
	 */
	function c_suppUserLogJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$personnelDao = new model_outsourcing_supplier_personnel();
		$userStr = $personnelDao->getPersonIdList($_SESSION['USER_ID']);
		$service->searchArr['createIdArr'] = $userStr;

		//$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}
	/*********************** 录入日志 - 包含费用 ********************/
	/**
	 * 查看页面
	 */
	function c_toConfirmNew() {
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('workStatus', $this->getDataNameByCode($obj['workStatus']));
		$this->assign('invbody', $this->service->initCostConfirm_d($_GET['id']));
		$this->display('confirmnew');
	}

	/**
	 * 重新编辑页面
	 */
	function c_toReeditNew() {
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('workStatus', $this->getDataNameByCode($obj['workStatus']));
		$this->assign('invbody', $this->service->initCostReedit_d($_GET['id']));
		$this->display('reeditnew');
	}

	/*********************** 其他方法 ****************************/

    /**
     * 检测项目是否未截止填写日志
     */
    function c_checkProjectWithoutDeadline() {
        echo $this->service->checkProjectWithoutDeadline_d($_POST['projectIds'], $_POST['executionDate']) ? 1 : 0;
    }

	/**
	 * 通过创建人及日志日期检查判断是否已存在日志
	 */
	function c_checkExistLog() {
		//根据登陆人ID 和 填写时间查找记录
		$createId = $_SESSION['USER_ID'];
		$executionDate = $_POST['executionDate'];
		$this->service->searchArr = array("createId" => $createId, "executionDate" => $executionDate);
		$weeklogIDArr = $this->service->listBySqlId();
		if (is_array($weeklogIDArr)) {
			echo $weeklogIDArr[0]['id']; //存在
		} else {
			echo 0;
		}
	}

	/**
	 *
	 * 判断是否已经填写日志在项目中
	 */
	function c_checkExistLogPro() {
		echo $this->service->checkExistLogPro($_POST['userId'], $_POST['projectId']) ? 1 : 0; //已填
	}

	/**
	 *
	 * 判断是否已经填写日志在项目中
	 */
	function c_checkExistLogUsers() {
		echo util_jsonUtil::iconvGB2UTF($this->service->checkExistLogUsers_d($_POST['userId'], $_POST['projectId']));
	}

	/**
	 * 检测任务当前是否已填写日志
	 */
	function c_checkActivityLog() {
		$userId = $_SESSION['USER_ID'];
		$activityId = $_POST['activityId'];
		$executionDate = $_POST['executionDate'];
		$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';
		$rs = $this->service->checkActivityLog_d($userId, $activityId, $executionDate, $searchType);
		if ($rs)
			echo util_jsonUtil::iconvGB2UTF($rs);
		else
			echo 0; //未填
	}

	/**
	 * 检测任务是否已经录入日志 - 全验证
	 */
	function c_checkActLog() {
		echo $this->service->find(array('activityId' => $_POST['activityId'])) ? 1 : 0;
	}

	/**
	 * 检测任务是否已经录入日志 - 人和日志
	 */
	function c_checkActLogUser() {
		echo $this->service->find(array('activityId' => $_POST['activityId'], 'createId' => $_POST['userId'])) ? 1 : 0;
	}

	/**
	 * 根据周报id判断是否还有日志未完成审核
	 */
	function c_checkCostAllAudit() {
		echo $this->service->checkCostAllAudit_d($_POST['weekId']) ? 1 : 0;
	}

	/**
	 * 判断任务 以及子任务下 是否存在日志 - 用于任务变更删除
	 */
	function c_checkActAndParentLog() {
		echo $this->service->checkActAndParentLog_d($_POST['activityId']) ? 1 : 0;
	}

	/**
     * 获取可天最大工作量比例
     */
	function c_getMaxInWorkRate() {
		//工作量比例计算
		$countInfo = $this->service->getDayUserCountInfo_d(isset($_POST['executionDate']) ? $_POST['executionDate'] : day_date);
		echo bcsub(100, $countInfo['inWorkRate'], 2);
	}
	/************************ 票据整理部分 ****************************/
	/**
	 * 票据整理报销单修改
	 */
	function c_toCheckEdit() {
		//当前报销的明细id
		$costdetailId = $_GET['costdetailId'];

		//获取单据信息
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		//渲染费用内容
		$this->assign('invbody', $this->service->initCheckEdit_d($_GET['id'], $costdetailId));

		//数据字典渲染
		$this->assign('workStatus', $this->getDataNameByCode($obj['workStatus']));
		$this->assign('expenseId', $_GET['expenseId']);
		$this->assign('allcostdetailId', $_GET['allcostdetailId']);
		$this->assign('costdetailId', $_GET['costdetailId']);

		$this->view('editcheck');
	}

	/**
	 * 票据整理报销单修改
	 */
	function c_editCheck() {
		if ($this->service->editCheck_d($_POST[$this->objName])) {
			$this->msgRf2('保存成功');
		}
	}

	/**
	 * 弹出窗口关闭刷新页面
	 * @param $title
	 */
	function msgRf2($title) {
		exit("<script>alert('" . $title . "');if( window.opener != undefined ){self.opener.show_page2(1);} self.close();</script>");
	}

	/*********************** 日志导入 **********************/
	/**
	 * 日志导入
	 */
	function c_toExcelIn() {
		$this->display('excelin', true);
	}

	/**
	 * 日志导入
	 */
	function c_excelIn() {
        $this->checkSubmit();
		$resultArr = $this->service->excelIn_d();
		$title = '项目导入结果列表';
		$thead = array('数据信息', '导入结果');
		echo util_excelUtil::showResult($resultArr, $title, $thead);
	}

	/*********************** 日志导出 **********************/
	/**
	 * 日志导出
	 */
	function c_toOutExcel() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$beginDate = $weekBEArr['beginDate'];
		$endDate = $weekBEArr['endDate'];
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('deptIds', $_GET['deptIds']);
		$this->view('outExcel');
	}

	/**
	 * 日志导出方法
	 */
	function c_outExcel() {
		set_time_limit(0);
		$service = $this->service;
		$deptIds = $_GET['deptIds'];
		unset($_GET['deptIds']);
		if (!empty($deptIds) && !empty($_GET['deptId'])) {//判断传入权限
			$_GET['deptId'] = implode(',', array_intersect(explode(',', $_GET['deptId']), explode(',', $deptIds)));
		} elseif (!empty($deptIds) && empty($_GET['deptId'])) {
			$_GET['deptId'] = $deptIds;
		}
		//		print_r($_GET);die();
		$service->getParam($_GET);
		$service->sort = 'c.executionDate asc,c.deptId asc,c.createId';
		$rows = $service->list_d();
		//定义表头
		$thArr = array('createName' => '填写人', 'executionDate' => '日期', 'projectCode' => '项目编号', 'projectName' => '项目名称',
			'activityName' => '任务', 'workloadDay' => '工作量', 'workloadUnitName' => '单位', 'thisActivityProcess' => '任务进展%',
			'thisProjectProcess' => '项目进展%', 'processCoefficient' => '进展系数',
			'inWorkRate' => '人工投入占比%', 'workCoefficient' => '工作系数', 'costMoney' => '费用', 'description' => '情况描述',
			'assessResultName' => '考核结果', 'feedBack' => '回复', 'deptName' => '部门'
		);

		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '部门日志');
	}

    /**
     * 导出日志考核
     */
    function c_exportLogAssessJson() {
        set_time_limit(0);
        $service = $this->service;

        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['部门权限'];
        if (!strstr($deptLimit, ';;')) {
            if (empty($deptLimit)) {
                $_GET['deptId'] = $_SESSION['DEPT_ID'];
            } else {
                $_GET['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
            }
        }

        $service->getParam($_GET);
        $service->sort = 'c.executionDate asc,c.createId';
        $rows = $service->list_d();
        //定义表头
        $thArr = array('createName' => '填写人', 'executionDate' => '日期', 'createTime' => '日志录入日期', 'confirmDate' => '日志审核日期',
            'projectCode' => '项目编号', 'province' => '省份', 'city' => '城市', 'activityName' => '任务', 'workloadDay' => '工作量', 'workloadUnitName' => '单位',
            'thisActivityProcess' => '任务进展%', 'thisProjectProcess' => '项目进展%', 'processCoefficient' => '进展系数',
            'inWorkRate' => '人工投入占比%', 'workCoefficient' => '工作系数', 'costMoney' => '费用', 'description' => '情况描述',
            'assessResultName' => '考核结果', 'feedBack' => '回复'
        );

        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '日志考核');
    }

	/**
	 * 导出日志考核
	 */
	function c_exportNewAssessment() {
		set_time_limit(0);
		//加入权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['部门权限'];
		if (!strstr($deptLimit, ';;')) {
			if (empty($deptLimit)) {
				$_GET['deptId'] = $_SESSION['DEPT_ID'];
			} else {
				$_GET['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
			}
		}
        $rows = $this->service->getAssessment_d($_GET);

        // 数据字典转换
        if (!empty($rows)) {
            // 获取工作状态对应的数据字典
            $datadictDao = new model_system_datadict_datadict();
            $workStatusDatadict = $datadictDao->getDataDictList_d('GXRYZT');

            foreach ($rows as $k => $v) {
                $rows[$k]['workStatus'] = isset($workStatusDatadict[$v['workStatus']]) ?
                    $workStatusDatadict[$v['workStatus']] : $v['workStatus'];
            }
        }

		//定义表头
		$thArr = array('createName' => '填写人', 'executionDate' => '日期', 'projectCode' => '项目编号', 'province' => '省份',
			'city' => '城市', 'inWorkRate' => '考勤投入（%）','assessResultName' => '日志考核',
            'score' => '项目考核', 'accessScore' => '考核得分', 'workStatus' => '工作状态',
            'workloadDay' => '工作量', 'workloadUnitName' => '单位',
			 'createTime' => '日志录入日期', 'confirmDate' => '审核日志日期'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '日志考核');
	}

	/**
	 * 转到批量取消审批页面
	 */
	function c_toBatchUnaudit() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("batchUnaudit");
	}

	/*********************** 费用日志导入 **********************/
	/**
	 * 转到费用日志导入
	 */
	function c_toCostExcelIn() {
		$this->display('costExcelin');
	}

	/**
	 * 费用日志导入
	 */
	function c_costExcelIn() {
		$resultArr = $this->service->costExcelIn_d();
		$title = '项目导入结果列表';
		$head = array('数据信息', '导入结果');
		echo util_excelUtil::showResult($resultArr, $title, $head);
	}

	/*********************** 批量删除日志 **********************/
	/**
	 * 批量删除日志
	 */
	function c_toDeleteLog() {
		$this->assign('today', day_date);
		$this->view('deleteLog');
	}

	//查询删除数据
	function c_searchLog() {
		echo $this->service->searchLog_d($_POST['beginDate'], $_POST['endDate']);
	}

	/**
	 * 批量删除日志
	 */
	function c_deleteLog() {
		echo $this->service->deleteLog_d($_POST['beginDate'], $_POST['endDate']) ? 1 : 0;
	}

	/************************考核系数查询**************************/
	/**
	 * 跳转到考核系数查询
	 */
	function c_toAssessCoefficien() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$this->assign('beginDate', $weekBEArr['beginDate']);
		$this->assign('endDate', $weekBEArr['endDate']);

		$this->view('assessCoefficien-list');
	}

	/**
	 * 考核系数查询-呈现列表
	 */
	function c_assessCoefficien() {
		set_time_limit(0);
		$service = $this->service;
		//post过来的中文字符必须由utf8转为gbk
		if (!empty($_POST['userName'])) {
			$_POST['userName'] = util_jsonUtil::iconvUTF2GB($_POST['userName']);
		}
		$rows = $service->getSearchCoefficienList_d($_POST);
		//只针对个人列出汇总信息
		if (!empty($rows) && !empty($_POST['userName'])) {
			//加载汇总信息
			$objArr = $service->getSearchCoefficienCount_d($rows);
			if (is_array($objArr)) {
				$objArr['createName'] = '汇总信息';
				$objArr['createId'] = 'noId';
			}
			$rows[] = $objArr;
		}
		//这里转html
		$rows = $service->searchCoefficientHtml_d($rows);
		echo util_jsonUtil::iconvGB2UTF($rows);
	}

	/**
	 * 考核系数查询-导出
	 */
	function c_toExportAssessCoefficien() {
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->getSearchCoefficienList_d($_GET);
		//只针对个人列出汇总信息
		if (!empty($rows) && !empty($_GET['userName'])) {
			//加载汇总信息
			$objArr = $service->getSearchCoefficienCount_d($rows);
			if (is_array($objArr)) {
				$objArr['createName'] = '汇总信息';
			}
			$rows[] = $objArr;
		}
		//定义表头
		$thArr = array('createName' => '姓名', 'deptName' => '部门名称', 'userNo' => '员工编号', 'projectCode' => '项目编号',
			'projectName' => '项目名称', 'inWorkRate' => '考核天数', 'workCoefficient' => '考核系数', 'workCoefficientAvg' => '平均考核系数'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '工程考核系数查询');
	}

	/**
	 * 日志及周报详细
	 */
	function c_toWorklogAndWeeklogDetailList() {
		$this->assignFunc($_GET);
		$this->view("worklogandweeklogdetail-list");
	}

	/**
	 * 判断日志日期是否存在请休假记录
	 */
	function c_isInHols() {
		if($this->service->isInHols_d($_POST['executionDate']) == ''){
			echo 0;
		}else{
			echo 1;
		}
	}

    /**
     * 获取审核数据 - 调试用
     */
    function c_getAssessInfo() {
        var_dump($this->service->getAssessInfo_d(isset($_GET['year']) ? $_GET['year'] : 2016, isset($_GET['month']) ? $_GET['month'] : 6));
    }

	/**
	 * 获取审核数据 - 调试用
	 */
	function c_getAssessInfoByCond() {
		echo "<pre>";
		print_r($this->service->getAssessInfoByCond_d('2017-01-01', '2017-12-31', 'admin'));
	}

    /**************手机端需要方法*************************************************************/
    /**
	 * 新增方法 - 手机端
	 */
	function c_addMobile() {
		if ($this->service->add_d(util_jsonUtil::iconvUTF2GBArr($_POST))) {
			echo 1;
		}
	}

	/**
	 * 查看页获取数据方法
	 */
    function c_getView(){
    	$service = $this->service;
		$obj = $service->get_d($_REQUEST['id']);
		$obj['workStatus'] = $this->getDataNameByCode($obj['workStatus']);
		echo util_jsonUtil::encode($obj);
    }
}