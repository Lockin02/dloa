<?php

/**
 * @author show
 * @Date 2015��2��5�� 15:49:55
 * @version 1.0
 * @description:��Ŀ�رչ��� Model��
 */
class model_engineering_baseinfo_esmcloserule extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_close_rule";
		$this->sql_map = "engineering/baseinfo/esmcloseruleSql.php";
		parent::__construct();
	}

	/**
	 * ��ȡĬ�ϵĹ���
	 */
	function getDefaultRule_d() {
		$this->searchArr = array(
			'isNeed' => 1
		);
		$this->asc = false;
		return $this->list_d('select_list');
	}

	/**
	 * ��洦��
	 * @param $rows
	 * @return mixed
	 */
	function dealRule_d($rows) {
		if ($rows) {
			foreach ($rows as $k => $v) {
				$rows[$k] = $this->dealRuleAuto_d($rows[$k], $k);
			}
		}
		return $rows;
	}

	/**
	 * ������
	 * @param $object
	 * @param $rowNum
	 * @return mixed
	 */
	function dealRuleAuto_d($object, $rowNum) {
		switch ($object['ruleId']) {
			case 1:
				$object = $this->rule01_d($object, $rowNum);
				break;
			case 2:
				$object = $this->rule02_d($object);
				break;
			case 3:
				$object = $this->rule03_d($object, $rowNum);
				break;
			case 4:
				$object = $this->rule04_d($object);
				break;
			case 5:
				$object = $this->rule05_d($object);
				break;
            case 7:
                $object = $this->rule07_d($object);
                break;
			default:
				$object = $this->ruleCustom_d($object);
		}
		return $object;
	}

	/**
	 * ����1 - ��Ŀ����
	 * @param $object
	 * @param $rowNum
	 * @return mixed
	 */
	function rule01_d($object, $rowNum) {
		$esmProjectDao = new model_engineering_project_esmproject();
		$projectInfo = $esmProjectDao->find(array('id' => $object['projectId']), null, 'projectProcess');

		$object['val'] = $projectInfo['projectProcess'] . ' %';
		$object['status'] = $projectInfo['projectProcess'] == 100 ? 1 : 0;
		if ($projectInfo['projectProcess'] < 100)
			$object['act'] = "<textarea rows='4' id='closeRules_cmp_reply" . $rowNum .
				"' name='esmclose[esmclosedetail][" . $rowNum . "][reply]'>" . $object['reply'] . "</textarea>";

		return $object;
	}

	/**
	 * ����2 - ��Ա�뿪
	 * @param $object
	 * @return mixed
	 */
	function rule02_d($object) {
        $esmMemberDao = new model_engineering_member_esmmember();

		// ʹ����Ա��������Ϣ������Ա������Ŀ���뿪��Ŀ����
        $esmEntryDao = new model_engineering_member_esmentry();
        $entryInfo = $esmEntryDao->getProjectMemberEntryList_d($object['projectId']);
        if ($entryInfo) {
            foreach ($entryInfo as $v) {
                $esmMemberDao->editByMemberId_d($v);
            }
        }

        // �ж���Ա�Ƿ��Ѿ�ȫ���뿪
		$esmMemberInfo = $esmMemberDao->checkMemberAllLeave_d($object['projectId']);

		if ($esmMemberInfo) {
			$object['val'] = 'δ���';
			$object['status'] = 0;
			$object['act'] = "<a href='javascript:void(0)' onclick='showMemberList(" . $object['projectId'] .
				")'>����</a>¼�������Ϣ<br/>";
		} else {
			$object['val'] = '�����';
			$object['status'] = 1;
		}

		return $object;
	}

	/**
	 * ����3 - ���ô���
	 * @param $object
	 * @param $rowNum
	 * @return mixed
	 */
	function rule03_d($object, $rowNum) {
		$esmProjectDao = new model_engineering_project_esmproject();
		$projectInfo = $esmProjectDao->find(array('id' => $object['projectId']), null, 'budgetAll,feeAll');

		$diff = abs(bcmul(bcdiv($projectInfo['feeAll'], $projectInfo['budgetAll'], 4), 100, 2) - 100);
		$object['val'] = $projectInfo['budgetAll'] . ' / ' . $projectInfo['feeAll'] . ' / ' . $diff . ' %';
		if ($diff > 10) {
			$object['act'] = "<textarea rows='4' id='closeRules_cmp_reply" . $rowNum .
				"' name='esmclose[esmclosedetail][" . $rowNum . "][reply]'>" . $object['reply'] . "</textarea>";
			$object['status'] = 0;
		} else {
			$object['status'] = 1;
		}

		return $object;
	}

	/**
	 * ����4 - �豸�黹
	 * @param $object
	 * @return mixed
	 */
	function rule04_d($object) {
//		$esmDeviceDao = new model_engineering_device_esmdevice();
//		$esmDeviceList = $esmDeviceDao->getMyEqu_d($object['projectId']);
        // ��ȡ��Ŀ�ϵĿ�Ƭ
        $result = util_curlUtil::getDataFromAWS('asset', 'selectProjectCard', array(
            "projectCode" => $object['projectCode']
        ));
        if ($result['res'] == 0) {
            $object['val'] = '��������ʧ�ܣ���ˢ��ҳ������';
            $object['status'] = 0;
            return $object;
        }
        $returnData = util_jsonUtil::decode($result['data'], true);

        if ($returnData['result'] == 'ok') {
            if (!isset($returnData['data']['cardList'])) {
                $object['val'] = '�����';
                $object['status'] = 1;
            } else {
                $object['val'] = 'δ���';
                $object['status'] = 0;
                $object['act'] = "���ȹ黹�豸";
            }
        } else {
            $object['val'] = '��������ʧ�ܣ���ˢ��ҳ������';
            $object['status'] = 0;
        }

		return $object;
	}

	/**
	 * ����5 - �ĵ��鵵
	 * @param $object
	 * @return mixed
	 */
	function rule05_d($object) {
		$esmFileTypeDao = new model_engineering_file_esmfiletype();
		$result = $esmFileTypeDao->checkFileSubmit_d($object);

		if ($result) {

			$object['val'] = '�����';
			$object['status'] = 1;
		} else {

			$object['val'] = 'δ���';
			$object['status'] = 0;
			$object['act'] = "<a href='javascript:void(0)' onclick='showFileEdit(" . $object['projectId'] .
				")'>����</a>¼�������Ϣ<br/>";
		}

		return $object;
	}

    /**
     * ����7 - ���黹
     * @param $object
     * @return mixed
     */
	function rule07_d($object){
	    $loanDao = new model_loan_loan_loan();
        $NoPayLoanAmount = $loanDao->getNoPayLoanAmountByProjId($object['projectId']);
        if($NoPayLoanAmount > 0){
            $object['val'] = "��ǰ������Ŀ���".$NoPayLoanAmount."Ԫδ���壬��黹����������Ŀ���ת�ơ�";
            $object['status'] = 1;
        }
        return $object;
    }

	/**
	 * ���� - �Զ���
	 * @param $object
	 * @return mixed
	 */
	function ruleCustom_d($object) {
		if ($object['status'] == 1) {

			$object['val'] = '��ȷ��';
		} else {

			$object['val'] = 'δȷ��';
		}
		return $object;
	}
}