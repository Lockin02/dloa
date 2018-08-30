<?php

/**
 * @author show
 * @Date 2015年2月5日 15:49:55
 * @version 1.0
 * @description:项目关闭规则 Model层
 */
class model_engineering_baseinfo_esmcloserule extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_close_rule";
		$this->sql_map = "engineering/baseinfo/esmcloseruleSql.php";
		parent::__construct();
	}

	/**
	 * 获取默认的规则
	 */
	function getDefaultRule_d() {
		$this->searchArr = array(
			'isNeed' => 1
		);
		$this->asc = false;
		return $this->list_d('select_list');
	}

	/**
	 * 库存处理
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
	 * 规则定义
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
	 * 规则1 - 项目进度
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
	 * 规则2 - 人员离开
	 * @param $object
	 * @return mixed
	 */
	function rule02_d($object) {
        $esmMemberDao = new model_engineering_member_esmmember();

		// 使用人员出入表的信息更新人员进入项目、离开项目日期
        $esmEntryDao = new model_engineering_member_esmentry();
        $entryInfo = $esmEntryDao->getProjectMemberEntryList_d($object['projectId']);
        if ($entryInfo) {
            foreach ($entryInfo as $v) {
                $esmMemberDao->editByMemberId_d($v);
            }
        }

        // 判定人员是否已经全部离开
		$esmMemberInfo = $esmMemberDao->checkMemberAllLeave_d($object['projectId']);

		if ($esmMemberInfo) {
			$object['val'] = '未完成';
			$object['status'] = 0;
			$object['act'] = "<a href='javascript:void(0)' onclick='showMemberList(" . $object['projectId'] .
				")'>这里</a>录入相关信息<br/>";
		} else {
			$object['val'] = '已完成';
			$object['status'] = 1;
		}

		return $object;
	}

	/**
	 * 规则3 - 费用处理
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
	 * 规则4 - 设备归还
	 * @param $object
	 * @return mixed
	 */
	function rule04_d($object) {
//		$esmDeviceDao = new model_engineering_device_esmdevice();
//		$esmDeviceList = $esmDeviceDao->getMyEqu_d($object['projectId']);
        // 获取项目上的卡片
        $result = util_curlUtil::getDataFromAWS('asset', 'selectProjectCard', array(
            "projectCode" => $object['projectCode']
        ));
        if ($result['res'] == 0) {
            $object['val'] = '数据请求失败，请刷新页面重试';
            $object['status'] = 0;
            return $object;
        }
        $returnData = util_jsonUtil::decode($result['data'], true);

        if ($returnData['result'] == 'ok') {
            if (!isset($returnData['data']['cardList'])) {
                $object['val'] = '已完成';
                $object['status'] = 1;
            } else {
                $object['val'] = '未完成';
                $object['status'] = 0;
                $object['act'] = "请先归还设备";
            }
        } else {
            $object['val'] = '数据请求失败，请刷新页面重试';
            $object['status'] = 0;
        }

		return $object;
	}

	/**
	 * 规则5 - 文档归档
	 * @param $object
	 * @return mixed
	 */
	function rule05_d($object) {
		$esmFileTypeDao = new model_engineering_file_esmfiletype();
		$result = $esmFileTypeDao->checkFileSubmit_d($object);

		if ($result) {

			$object['val'] = '已完成';
			$object['status'] = 1;
		} else {

			$object['val'] = '未完成';
			$object['status'] = 0;
			$object['act'] = "<a href='javascript:void(0)' onclick='showFileEdit(" . $object['projectId'] .
				")'>这里</a>录入相关信息<br/>";
		}

		return $object;
	}

    /**
     * 规则7 - 借款归还
     * @param $object
     * @return mixed
     */
	function rule07_d($object){
	    $loanDao = new model_loan_loan_loan();
        $NoPayLoanAmount = $loanDao->getNoPayLoanAmountByProjId($object['projectId']);
        if($NoPayLoanAmount > 0){
            $object['val'] = "当前仍有项目借款".$NoPayLoanAmount."元未还清，请归还借款或申请项目借款转移。";
            $object['status'] = 1;
        }
        return $object;
    }

	/**
	 * 规则 - 自定义
	 * @param $object
	 * @return mixed
	 */
	function ruleCustom_d($object) {
		if ($object['status'] == 1) {

			$object['val'] = '已确认';
		} else {

			$object['val'] = '未确认';
		}
		return $object;
	}
}