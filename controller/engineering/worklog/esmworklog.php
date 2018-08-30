<?php

/**
 * @author Show
 * @Date 2011��12��14�� ������ 10:01:27
 * @version 1.0
 * @description:������־(oa_esm_worklog)���Ʋ� ÿ�ս�չ
 */
class controller_engineering_worklog_esmworklog extends controller_base_action
{

	function __construct() {
		$this->objName = "esmworklog";
		$this->objPath = "engineering_worklog";
		parent::__construct();
	}

	/**
	 * ��ת��������־
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Tab��־
	 */
	function c_toDeptLog() {
		//����Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
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
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//����һ������ƴװ����
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
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_pageJsonWorkLog() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//����һ������ƴװ����
		if ($_POST['memberIdsSearch'] && $_POST['personType']) {
			$memberIds = util_jsonUtil::strBuild($_POST['memberIdsSearch']);
			$personType = util_jsonUtil::strBuild($_POST['personType']);
			$sqlStr = "sql: and createId in (select userAccount from oa_hr_personnel where userAccount in ($memberIds) and personnelType in ($personType))";
			$service->searchArr['mySearchCondition'] = $sqlStr;
		}

		$service->sort = 'c.executionDate asc,c.activityName';
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ���ݴ������Ϣ��ȡ������־
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
	 * ��ת����Ŀ��־�б�
	 */
	function c_toProList() {
		$this->assign("projectId", $_GET['projectId']);

		//��ȡȨ��
		$otherDataDao = new model_common_otherdatas();
		$thisLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

		$this->assign('unauditLimit', isset($thisLimitArr['��־�����Ȩ��']) ? $thisLimitArr['��־�����Ȩ��'] : 0);

		$this->view("project-list");
	}

	/*��ת����Ŀ��־*/
	function c_toProLogTab() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("prolog-tab");
	}

	/*��ת��������Ŀ��־*/
	function c_toProLogManageTab() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("prologmanage-tab");
	}

	/**
	 * ��ת����־���ҳ��
	 */
	function c_toAuditList() {
		$this->assign("projectId", $_GET['projectId']);
		$this->assign("activityId", $_GET['activityId']);

		//��ʼ��Ա������
		$datadictDao = new model_system_datadict_datadict();
		$datadictArr = $datadictDao->getSpeDatadict_d('HRYGLX');
		$this->assign('personType', $datadictDao->datadictShow_d($datadictArr));

		$this->view("audit-list");
	}

	/**
	 * ��Աͳ�������Ϣ
	 */
	function c_auditJsonForPerson() {
		$service = $this->service;
		$service->getParam($_POST);

		//����һ������ƴװ����
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
	 * ��־��ѯ����
	 */
	function c_toSearchList() {
		//����������
		$this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
		$this->assign("projectId", $_GET['projectId']);
		$this->view("search-list");
	}

	/**
	 * ��־��ѯjson
	 */
	function c_searchJson() {
		$service = $this->service;

		// ��ȡ�û�������
        $rows = $service->findMember_d($_POST['projectId']);
		$rows = $service->appendLogInfo_d($rows, '');

		// ��ѯ�û�����
		$memberIdArr = array();
		foreach ($rows as $v) {
			$memberIdArr[] = $v['memberId'];
		}
		$memberIds = implode(',', $memberIdArr);

		// �������� - �����û��ӿ�ʼ����������������
        $weekDao = new model_engineering_baseinfo_week();
        $dateData = $weekDao->buildDateData($_REQUEST['beginDate'], $_REQUEST['endDate']);

		// �û���Ϣ - ����ְ
		$personInfo = $this->service->getPersonnelInfoMap_d($memberIds);
		// ���ݼ���Ϣ
		$hols = $this->service->getHolsHash_d(strtotime($_POST['beginDate']), strtotime($_POST['endDate']), $memberIds);

		// ������Ա���봦��
		$entryDao = new model_engineering_member_esmentry();
		// ��Ŀ��������
		$entryData = $entryDao->getEntryMap_d($_POST['projectId'], $memberIds);

		$logs = $this->service->getAssessmentLogMap_d($_POST['beginDate'], $_POST['endDate'], $memberIds);

		// ͳ������
		$countDay = count($dateData);

		foreach ($rows as $k => $v) {
			$innerData = $dateData;

			// ����ְ���ݴ���
			$innerData = $this->service->dealEntryQuit_d($innerData, '', $personInfo[$v['createId']]);

			// �������ݼ�����
			$innerData = $this->service->dealHolds_d($innerData, '', '', $v['createId'], $hols);

			// ��������
			$innerData = $entryDao->dealEntry_d($innerData, '', $v['createId'], $entryData);

			// ��Ա��־����
			$innerData = $this->service->dealLog_d($innerData, $_POST['projectId'], $logs[$v['createId']]);

//			echo util_jsonUtil::encode($logs);die;

			// �ϼ�
			$sumRow = array('id' => 'noId', 'executionDate' => '�ϼ�');

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
            //������Ŀ�ϼ�
            $objArr = $service->getNewSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '�� ��';
                $objArr['createId'] = 'noId';
            }
			$rows[] = $objArr;
		}
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ��־��ϸ
	 */
	function c_toSearchDetailList() {
		$this->assignFunc($_GET);
		$this->view("searchdetail-list");
	}

	/**
	 * ��־��ϸ��ѯ
	 */
	function c_searchDetailJson() {
		// �������� - �����û��ӿ�ʼ����������������
        $weekDao = new model_engineering_baseinfo_week();
        $data = $weekDao->buildDateData($_POST['beginDateThan'], $_POST['endDateThan']);

		// ����ְ���ݴ���
		$data = $this->service->dealEntryQuit_d($data, $_POST['createId']);

		// �������ݼ�����
		$data = $this->service->dealHolds_d($data, $_POST['beginDateThan'], $_POST['endDateThan'], $_POST['createId']);

		// ������Ա���봦��
		$entryDao = new model_engineering_member_esmentry();
		$data = $entryDao->dealEntry_d($data, $_POST['projectId'], $_POST['createId']);

		// ��Ա��־��ȡ
		$logs = $this->service->getAssessmentLogMap_d($_POST['beginDateThan'], $_POST['endDateThan'], $_POST['createId']);

		// ��Ա��־����
		$data = $this->service->dealLog_d($data, $_POST['projectId'], $logs[$_POST['createId']]);

		// �ϼ�
		$sumRow = array('id' => 'noId', 'executionDate' => '�ϼ�');

		foreach ($data as $v) {
			$sumRow['inWorkRate'] = bcadd($sumRow['inWorkRate'], $v['inWorkRate'], 2);
			$sumRow['monthScore'] = bcadd($sumRow['monthScore'], $v['monthScore'], 2);
			$sumRow['workload'] = bcadd($sumRow['workload'], $v['workload'], 2);
		}
		$data[] = $sumRow;

        echo util_jsonUtil::encode($data);
	}

    /**
     * ��־��ϸ
     */
    function c_toNewSearchDetailList() {
        $this->assignFunc($_GET);
        $this->view("newsearchdetail-list");
    }

    /**
     * ��־��ϸ��ѯ
     */
    function c_newSearchDetailJson() {
        $service = $this->service;

        // ��ȡ��������
        $searchItem = $_POST;

        // ��ȡ��ĿID
        $projectId = $searchItem['projectId'];
        unset($searchItem['projectId']);

        // ��־���ݻ�ȡ
        $rows = $service->getAssessment_d($searchItem);

        //ʶ��δ���
        $rows = $service->dealUnLog_d($rows, $searchItem['beginDate'], $searchItem['endDate'], $projectId);

        if (!empty($rows)) {
            $countArr = array(
                'id' => 'noId',
                'executionDate' => '�� ��',
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
	 * ��־��ѯ���� ----------- ����
	 */
	function c_toSearchDeptList() {
		//����������
		$this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
		//����Ĭ��ѡ����
        $projectDao = new model_engineering_project_esmproject();
		$this->assignFunc($projectDao->getDefaultDept_d());
		$this->view("searchdept-list");
	}

	/**
	 * ��־��ѯ --------------- ����
	 */
	function c_searchDeptJson() {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getSearchDeptList_d($_POST);
        $rows = $service->appendLogInfo_d($rows, $_POST); // �����û��ȼ�
        if (!empty($rows)) {
            //������Ŀ�ϼ�
            $objArr = $service->getSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '�� ��';
                $objArr['createId'] = 'noId';
            }
            $rows[] = $objArr;
        }
        //����תhtml
        echo util_jsonUtil::iconvGB2UTF($service->searchDeptHtml_d($rows));
    }

	/**
	 * ��־��ѯ - ����
	 */
	function c_exportSearchDeptJson() {
        set_time_limit(0);
        $rows = $this->service->getSearchDeptList_d($_GET);
        $rows = $this->service->appendLogInfo_d($rows, $_GET); // �����û��ȼ�
        //�����ͷ
        $thArr = array('createName' => '����', 'deptName' => '��������', 'personLevel' => '��Ա�ȼ�', 'userNo' => 'Ա�����', 'projectCode' => '��Ŀ���',
            'projectName' => '��Ŀ����', 'inWorkRate' => '����Ͷ��', 'auditScore' => '����ϵ��', 'perAuditScore' => 'ƽ������ϵ��', 'monthAuditScore' => '�¿���ϵ��',
            'workCoefficient' => '����ϵ��', 'thisProjectProcess' => '��Ŀ�����(%)', 'processCoefficient' => '��չϵ��', 'costMoney' => '����'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '���̹�����ͳ��');
    }

    /**
     * ��־��ѯ���� ----------- ���ţ��£�
     */
    function c_toNewSearchDeptList() {
        //����������
        $this->assign('beginDate', $_GET['beginDate'] ? $_GET['beginDate'] : date('Y-m-01', strtotime('-1 month')));
        $this->assign('endDate', $_GET['endDate'] ? $_GET['endDate'] : date('Y-m-t', strtotime('-1 month')));
        //����Ĭ��ѡ����
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
     * ��־��ѯ --------------- ���ţ��£�
     */
    function c_newSearchDeptJson() {
        set_time_limit(0);
        $service = $this->service;
        $rows = $service->getAssessData_d($_POST);
        $rows = $service->appendLogInfo_d($rows, $_POST['deptId']); // �����û��ȼ�
        if (!empty($rows)) {
            //������Ŀ�ϼ�
            $objArr = $service->getNewSearchDeptCount_d($rows);
            if (is_array($objArr)) {
                $objArr['createName'] = '�� ��';
                $objArr['createId'] = 'noId';
            }
            $rows[] = $objArr;
        }
        //����תhtml
        echo util_jsonUtil::iconvGB2UTF($service->newSearchDeptHtml_d($rows));
    }

    /**
     * ��־��ѯ - ����
     */
    function c_exportNewSearchDept() {
        set_time_limit(0);
        $rows = $this->service->getAssessData_d($_GET);
        $rows = $this->service->appendLogInfo_d($rows, $_GET['deptId']); // �����û��ȼ�
        //�����ͷ
        $thArr = array('createName' => '����', 'userNo' => 'Ա�����', 'deptName' => '��������',
            'projectCode' => '��Ŀ���', 'inWorkRate' => '����Ͷ��', 'monthScore' => '���˵÷�',
            'countDay' => 'ͳ������', 'attendance' => '����ϵ��', 'assess' => '����ϵ��',
            'hols' => '���ݼ�����'
        );
        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '���̹�����ͳ��');
    }
	/************************ �ҵĹ������� *************************/
	/**
	 * �ҵĹ���
	 */
	function c_toMyJobs() {
		$this->view('myjobs');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam($_POST);

		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * �����ҵ���־
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
	 * ����ҳ�� - ��
	 */
	function c_toAdd() {
		$rs = $this->service->getLastInfo_d();
		$rs ['thisDate'] = day_date;

		$this->assignFunc($rs);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $rs['workStatus'], false);

		//��������������
		$countInfo = $this->service->getDayUserCountInfo_d(day_date);
		$this->assign('maxInWorkRate', bcsub(100, $countInfo['inWorkRate'], 2));

		$this->assign("userId", $_SESSION['USER_ID']);
		$this->assign("createName", $_SESSION['USERNAME']);

		//��������λ
		$this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), null, true);
		$this->view('add', true);
	}

	/**
	 * �������� - ��
	 */
	function c_add() {
		$this->checkSubmit();
		if ($this->service->add_d($_POST[$this->objName])) {
			msgRf('����ɹ�');
		}
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toEdit() {
		$object = $this->service->get_d($_GET['id']);
		$this->assignFunc($object);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $object['workStatus'], false);

		$this->assign('invbody', $this->service->initCost_d($_GET['id']));

		//��������������
		$countInfo = $this->service->getDayUserCountInfo_d($object['executionDate']);
		$this->assign('maxInWorkRate', bcAdd(bcsub(100, $countInfo['inWorkRate'], 2), $object['inWorkRate'], 2));

		//��������λ
		$this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $object['workloadUnit'], true);
		$this->assign('workloadUnitDefault', $object['workloadUnit']);

		$this->view('edit', true);
	}

	/**
	 * �޸���־
	 * @see controller_base_action::c_edit()
	 */
	function c_edit() {
		$this->checkSubmit();
		if ($this->service->edit_d($_POST[$this->objName])) {
			msgRf('����ɹ�');
		}
	}

	/**
	 * ���²鿴ҳ��
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
	 * ��������ҳ��
	 */
	function c_toBatchAdd() {
		$this->assign("userId", $_SESSION['USER_ID']);

		//��������������
		$countInfo = $this->service->getDayUserCountInfo_d(day_date);
		$this->assign('maxInWorkRate', bcsub(100, $countInfo['inWorkRate'], 2));

		$rs = $this->service->getLastInfo_d();
		$rs['thisDate'] = day_date;

		$this->assignFunc($rs);
		$this->showDatadicts(array('workStatus' => 'GXRYZT'), $rs['workStatus'], false);

        // ��ȡ��ְ����
        $personnelInfo = $this->service->getPersonnelInfo_d();
        $this->assign('entryDate', $personnelInfo['entryDate']);

		$this->view('addbatch', true);
	}

	/**
	 * �������� - ��
	 */
	function c_batchAdd() {
		$this->checkSubmit();
		$rs = $this->service->batchAdd_d($_POST[$this->objName]);
		if ($rs === true) {
			msgRf('����ɹ�');
		} else {
			if ($rs) {
				msgRf($rs);
			}
		}
	}

	/**
	 * ��־���
	 */
	function c_auditLog() {
		echo $this->service->auditLog_d($_POST['id'], $_POST['assessResult'],
			util_jsonUtil::iconvUTF2GB($_POST['feedBack'])) ? 1 : 0;
	}

	/**
	 * ��־��� -- ����Ա����
	 */
	function c_auditLogForPerson() {
		echo $this->service->auditLogForPerson_d($_POST) ? 1 : 0;
	}

	/**
	 * �����־
	 */
	function c_backLog() {
		echo $this->service->backLog_d($_POST['id'], $_POST['assessResult'],
			util_jsonUtil::iconvUTF2GB($_POST['feedBack'])) ? 1 : 0;
	}

	/**
	 * �����־ -- ����Ա����
	 */
	function c_backLogForPerson() {
		echo $this->service->backLogForPerson_d($_POST) ? 1 : 0;
	}

	/**
	 * �������־
	 */
	function c_unauditLog() {
		$rs = $this->service->unauditLog_d($_POST['id']);
		if (is_numeric($rs)) {
			echo 1;
		} else {
			echo util_jsonUtil::iconvGB2UTF($rs);
		}
	}

	//�����������־
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
		msgRf('ȡ���ɹ�' . $success . "��" . "��ȡ��ʧ��" . $fail . "��");
	}

	/************************ ������־���˳��� add chenrf**************************/
	/**
	 * ��ת��־���˳����б�
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
	 * ��־���˳����б�
	 *
	 */
	function c_assessment() {
        $service = $this->service;

        //����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
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
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
	}

    /**
     * ��ת��־���˳����б��£�
     *
     */
    function c_toNewAssessment() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
        $this->assign('beginDate', $weekBEArr['beginDate']);
        $this->assign('endDate', $weekBEArr['endDate']);

        //����Ĭ��ѡ����
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d(true));
        $this->view('newassessment-list');
    }

    /**
     * ��־���˳����б�
     *
     */
    function c_newAssessment() {
        //����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
        if (!strstr($deptLimit, ';;')) {
            if (empty($deptLimit)) {
                $_REQUEST['deptId'] = $_SESSION['DEPT_ID'];
            } else {
                $_REQUEST['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
            }
        }
        $rows = $this->service->getAssessment_d($_REQUEST);

        // �����д���
        if ($rows) {
            // �ϼ���
            $countRow = array(
                'projectCode' => '�ϼ�',
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

	/*********************�澯��ͼ**************************************/
	/**
	 * ��ת���澯��ͼ
	 * Enter description here ...
	 */
	function c_toWarnView() {
		$this->assign('beginDate', date('Y-m-01', strtotime('-1 month')));
		$this->assign('endDate', date('Y-m-t', strtotime('-1 month')));

        //����Ĭ��ѡ����
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 'feeDeptId' => '', 't' => '', 'createId' => 'createId'
        ), $_GET));
		$this->view('warnView-list');
	}

	/**
	 *
	 * �澯��ͼ��������
	 */
	function c_warnView() {
		echo util_jsonUtil::encode($this->service->warnView_d($_POST));
	}

    /**
     * �����澯��ͼ����
     */
    function c_toWarnSummary() {
        $this->assign('beginDate', date('Y-m-01', strtotime('-1 month')));
        $this->assign('endDate', date('Y-m-t', strtotime('-1 month')));

        //����Ĭ��ѡ����
        $projectDao = new model_engineering_project_esmproject();
        $this->assignFunc($projectDao->getDefaultDept_d());
        $this->assignFunc(array_merge(array(
            'year' => '', 'month' => '', 'feeDeptId' => '', 't' => ''
        ), $_GET));
        $this->view('warnSummary-list');
    }

    /**
     * �澯��ͼ����
     */
    function c_warnSummary() {
        echo util_jsonUtil::encode($this->service->warnSummary_d($_POST));
    }

    /**
     * ��ȡ��־�澯������
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
	 * ������־�澯��ͼ
	 */
	function c_exportLogEmergencyJson() {
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->warnView_d($_GET);
		//�����ͷ
		$thArr = array(
			'executionDate' => '����', 'createName' => '����', 'belongDeptName' => '��������',
            'jobName' => 'ְλ(����)', 'msg' => '�澯'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '��־�澯');
	}

	/********************* �����ѯ���� ********************/
	/**
	 * �����Ա��־
	 */
	function c_toSuppUserLog() {
		$this->view('suppuserlog-list');
	}

	/**
	 * �����Ա��־
	 */
	function c_suppUserLogJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$personnelDao = new model_outsourcing_supplier_personnel();
		$userStr = $personnelDao->getPersonIdList($_SESSION['USER_ID']);
		$service->searchArr['createIdArr'] = $userStr;

		//$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}
	/*********************** ¼����־ - �������� ********************/
	/**
	 * �鿴ҳ��
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
	 * ���±༭ҳ��
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

	/*********************** �������� ****************************/

    /**
     * �����Ŀ�Ƿ�δ��ֹ��д��־
     */
    function c_checkProjectWithoutDeadline() {
        echo $this->service->checkProjectWithoutDeadline_d($_POST['projectIds'], $_POST['executionDate']) ? 1 : 0;
    }

	/**
	 * ͨ�������˼���־���ڼ���ж��Ƿ��Ѵ�����־
	 */
	function c_checkExistLog() {
		//���ݵ�½��ID �� ��дʱ����Ҽ�¼
		$createId = $_SESSION['USER_ID'];
		$executionDate = $_POST['executionDate'];
		$this->service->searchArr = array("createId" => $createId, "executionDate" => $executionDate);
		$weeklogIDArr = $this->service->listBySqlId();
		if (is_array($weeklogIDArr)) {
			echo $weeklogIDArr[0]['id']; //����
		} else {
			echo 0;
		}
	}

	/**
	 *
	 * �ж��Ƿ��Ѿ���д��־����Ŀ��
	 */
	function c_checkExistLogPro() {
		echo $this->service->checkExistLogPro($_POST['userId'], $_POST['projectId']) ? 1 : 0; //����
	}

	/**
	 *
	 * �ж��Ƿ��Ѿ���д��־����Ŀ��
	 */
	function c_checkExistLogUsers() {
		echo util_jsonUtil::iconvGB2UTF($this->service->checkExistLogUsers_d($_POST['userId'], $_POST['projectId']));
	}

	/**
	 * �������ǰ�Ƿ�����д��־
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
			echo 0; //δ��
	}

	/**
	 * ��������Ƿ��Ѿ�¼����־ - ȫ��֤
	 */
	function c_checkActLog() {
		echo $this->service->find(array('activityId' => $_POST['activityId'])) ? 1 : 0;
	}

	/**
	 * ��������Ƿ��Ѿ�¼����־ - �˺���־
	 */
	function c_checkActLogUser() {
		echo $this->service->find(array('activityId' => $_POST['activityId'], 'createId' => $_POST['userId'])) ? 1 : 0;
	}

	/**
	 * �����ܱ�id�ж��Ƿ�����־δ������
	 */
	function c_checkCostAllAudit() {
		echo $this->service->checkCostAllAudit_d($_POST['weekId']) ? 1 : 0;
	}

	/**
	 * �ж����� �Լ��������� �Ƿ������־ - ����������ɾ��
	 */
	function c_checkActAndParentLog() {
		echo $this->service->checkActAndParentLog_d($_POST['activityId']) ? 1 : 0;
	}

	/**
     * ��ȡ���������������
     */
	function c_getMaxInWorkRate() {
		//��������������
		$countInfo = $this->service->getDayUserCountInfo_d(isset($_POST['executionDate']) ? $_POST['executionDate'] : day_date);
		echo bcsub(100, $countInfo['inWorkRate'], 2);
	}
	/************************ Ʊ�������� ****************************/
	/**
	 * Ʊ�����������޸�
	 */
	function c_toCheckEdit() {
		//��ǰ��������ϸid
		$costdetailId = $_GET['costdetailId'];

		//��ȡ������Ϣ
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		//��Ⱦ��������
		$this->assign('invbody', $this->service->initCheckEdit_d($_GET['id'], $costdetailId));

		//�����ֵ���Ⱦ
		$this->assign('workStatus', $this->getDataNameByCode($obj['workStatus']));
		$this->assign('expenseId', $_GET['expenseId']);
		$this->assign('allcostdetailId', $_GET['allcostdetailId']);
		$this->assign('costdetailId', $_GET['costdetailId']);

		$this->view('editcheck');
	}

	/**
	 * Ʊ�����������޸�
	 */
	function c_editCheck() {
		if ($this->service->editCheck_d($_POST[$this->objName])) {
			$this->msgRf2('����ɹ�');
		}
	}

	/**
	 * �������ڹر�ˢ��ҳ��
	 * @param $title
	 */
	function msgRf2($title) {
		exit("<script>alert('" . $title . "');if( window.opener != undefined ){self.opener.show_page2(1);} self.close();</script>");
	}

	/*********************** ��־���� **********************/
	/**
	 * ��־����
	 */
	function c_toExcelIn() {
		$this->display('excelin', true);
	}

	/**
	 * ��־����
	 */
	function c_excelIn() {
        $this->checkSubmit();
		$resultArr = $this->service->excelIn_d();
		$title = '��Ŀ�������б�';
		$thead = array('������Ϣ', '������');
		echo util_excelUtil::showResult($resultArr, $title, $thead);
	}

	/*********************** ��־���� **********************/
	/**
	 * ��־����
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
	 * ��־��������
	 */
	function c_outExcel() {
		set_time_limit(0);
		$service = $this->service;
		$deptIds = $_GET['deptIds'];
		unset($_GET['deptIds']);
		if (!empty($deptIds) && !empty($_GET['deptId'])) {//�жϴ���Ȩ��
			$_GET['deptId'] = implode(',', array_intersect(explode(',', $_GET['deptId']), explode(',', $deptIds)));
		} elseif (!empty($deptIds) && empty($_GET['deptId'])) {
			$_GET['deptId'] = $deptIds;
		}
		//		print_r($_GET);die();
		$service->getParam($_GET);
		$service->sort = 'c.executionDate asc,c.deptId asc,c.createId';
		$rows = $service->list_d();
		//�����ͷ
		$thArr = array('createName' => '��д��', 'executionDate' => '����', 'projectCode' => '��Ŀ���', 'projectName' => '��Ŀ����',
			'activityName' => '����', 'workloadDay' => '������', 'workloadUnitName' => '��λ', 'thisActivityProcess' => '�����չ%',
			'thisProjectProcess' => '��Ŀ��չ%', 'processCoefficient' => '��չϵ��',
			'inWorkRate' => '�˹�Ͷ��ռ��%', 'workCoefficient' => '����ϵ��', 'costMoney' => '����', 'description' => '�������',
			'assessResultName' => '���˽��', 'feedBack' => '�ظ�', 'deptName' => '����'
		);

		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '������־');
	}

    /**
     * ������־����
     */
    function c_exportLogAssessJson() {
        set_time_limit(0);
        $service = $this->service;

        //����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
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
        //�����ͷ
        $thArr = array('createName' => '��д��', 'executionDate' => '����', 'createTime' => '��־¼������', 'confirmDate' => '��־�������',
            'projectCode' => '��Ŀ���', 'province' => 'ʡ��', 'city' => '����', 'activityName' => '����', 'workloadDay' => '������', 'workloadUnitName' => '��λ',
            'thisActivityProcess' => '�����չ%', 'thisProjectProcess' => '��Ŀ��չ%', 'processCoefficient' => '��չϵ��',
            'inWorkRate' => '�˹�Ͷ��ռ��%', 'workCoefficient' => '����ϵ��', 'costMoney' => '����', 'description' => '�������',
            'assessResultName' => '���˽��', 'feedBack' => '�ظ�'
        );

        model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '��־����');
    }

	/**
	 * ������־����
	 */
	function c_exportNewAssessment() {
		set_time_limit(0);
		//����Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
		if (!strstr($deptLimit, ';;')) {
			if (empty($deptLimit)) {
				$_GET['deptId'] = $_SESSION['DEPT_ID'];
			} else {
				$_GET['deptId'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
			}
		}
        $rows = $this->service->getAssessment_d($_GET);

        // �����ֵ�ת��
        if (!empty($rows)) {
            // ��ȡ����״̬��Ӧ�������ֵ�
            $datadictDao = new model_system_datadict_datadict();
            $workStatusDatadict = $datadictDao->getDataDictList_d('GXRYZT');

            foreach ($rows as $k => $v) {
                $rows[$k]['workStatus'] = isset($workStatusDatadict[$v['workStatus']]) ?
                    $workStatusDatadict[$v['workStatus']] : $v['workStatus'];
            }
        }

		//�����ͷ
		$thArr = array('createName' => '��д��', 'executionDate' => '����', 'projectCode' => '��Ŀ���', 'province' => 'ʡ��',
			'city' => '����', 'inWorkRate' => '����Ͷ�루%��','assessResultName' => '��־����',
            'score' => '��Ŀ����', 'accessScore' => '���˵÷�', 'workStatus' => '����״̬',
            'workloadDay' => '������', 'workloadUnitName' => '��λ',
			 'createTime' => '��־¼������', 'confirmDate' => '�����־����'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '��־����');
	}

	/**
	 * ת������ȡ������ҳ��
	 */
	function c_toBatchUnaudit() {
		$this->assign("projectId", $_GET['projectId']);
		$this->view("batchUnaudit");
	}

	/*********************** ������־���� **********************/
	/**
	 * ת��������־����
	 */
	function c_toCostExcelIn() {
		$this->display('costExcelin');
	}

	/**
	 * ������־����
	 */
	function c_costExcelIn() {
		$resultArr = $this->service->costExcelIn_d();
		$title = '��Ŀ�������б�';
		$head = array('������Ϣ', '������');
		echo util_excelUtil::showResult($resultArr, $title, $head);
	}

	/*********************** ����ɾ����־ **********************/
	/**
	 * ����ɾ����־
	 */
	function c_toDeleteLog() {
		$this->assign('today', day_date);
		$this->view('deleteLog');
	}

	//��ѯɾ������
	function c_searchLog() {
		echo $this->service->searchLog_d($_POST['beginDate'], $_POST['endDate']);
	}

	/**
	 * ����ɾ����־
	 */
	function c_deleteLog() {
		echo $this->service->deleteLog_d($_POST['beginDate'], $_POST['endDate']) ? 1 : 0;
	}

	/************************����ϵ����ѯ**************************/
	/**
	 * ��ת������ϵ����ѯ
	 */
	function c_toAssessCoefficien() {
        $weekDao = new model_engineering_baseinfo_week();
        $weekBEArr = $weekDao->getWeekRange();
		$this->assign('beginDate', $weekBEArr['beginDate']);
		$this->assign('endDate', $weekBEArr['endDate']);

		$this->view('assessCoefficien-list');
	}

	/**
	 * ����ϵ����ѯ-�����б�
	 */
	function c_assessCoefficien() {
		set_time_limit(0);
		$service = $this->service;
		//post�����������ַ�������utf8תΪgbk
		if (!empty($_POST['userName'])) {
			$_POST['userName'] = util_jsonUtil::iconvUTF2GB($_POST['userName']);
		}
		$rows = $service->getSearchCoefficienList_d($_POST);
		//ֻ��Ը����г�������Ϣ
		if (!empty($rows) && !empty($_POST['userName'])) {
			//���ػ�����Ϣ
			$objArr = $service->getSearchCoefficienCount_d($rows);
			if (is_array($objArr)) {
				$objArr['createName'] = '������Ϣ';
				$objArr['createId'] = 'noId';
			}
			$rows[] = $objArr;
		}
		//����תhtml
		$rows = $service->searchCoefficientHtml_d($rows);
		echo util_jsonUtil::iconvGB2UTF($rows);
	}

	/**
	 * ����ϵ����ѯ-����
	 */
	function c_toExportAssessCoefficien() {
		set_time_limit(0);
		$service = $this->service;
		$rows = $service->getSearchCoefficienList_d($_GET);
		//ֻ��Ը����г�������Ϣ
		if (!empty($rows) && !empty($_GET['userName'])) {
			//���ػ�����Ϣ
			$objArr = $service->getSearchCoefficienCount_d($rows);
			if (is_array($objArr)) {
				$objArr['createName'] = '������Ϣ';
			}
			$rows[] = $objArr;
		}
		//�����ͷ
		$thArr = array('createName' => '����', 'deptName' => '��������', 'userNo' => 'Ա�����', 'projectCode' => '��Ŀ���',
			'projectName' => '��Ŀ����', 'inWorkRate' => '��������', 'workCoefficient' => '����ϵ��', 'workCoefficientAvg' => 'ƽ������ϵ��'
		);
		model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '���̿���ϵ����ѯ');
	}

	/**
	 * ��־���ܱ���ϸ
	 */
	function c_toWorklogAndWeeklogDetailList() {
		$this->assignFunc($_GET);
		$this->view("worklogandweeklogdetail-list");
	}

	/**
	 * �ж���־�����Ƿ�������ݼټ�¼
	 */
	function c_isInHols() {
		if($this->service->isInHols_d($_POST['executionDate']) == ''){
			echo 0;
		}else{
			echo 1;
		}
	}

    /**
     * ��ȡ������� - ������
     */
    function c_getAssessInfo() {
        var_dump($this->service->getAssessInfo_d(isset($_GET['year']) ? $_GET['year'] : 2016, isset($_GET['month']) ? $_GET['month'] : 6));
    }

	/**
	 * ��ȡ������� - ������
	 */
	function c_getAssessInfoByCond() {
		echo "<pre>";
		print_r($this->service->getAssessInfoByCond_d('2017-01-01', '2017-12-31', 'admin'));
	}

    /**************�ֻ�����Ҫ����*************************************************************/
    /**
	 * �������� - �ֻ���
	 */
	function c_addMobile() {
		if ($this->service->add_d(util_jsonUtil::iconvUTF2GBArr($_POST))) {
			echo 1;
		}
	}

	/**
	 * �鿴ҳ��ȡ���ݷ���
	 */
    function c_getView(){
    	$service = $this->service;
		$obj = $service->get_d($_REQUEST['id']);
		$obj['workStatus'] = $this->getDataNameByCode($obj['workStatus']);
		echo util_jsonUtil::encode($obj);
    }
}