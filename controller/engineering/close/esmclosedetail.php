<?php

/**
 * @author show
 * @Date 2015��2��6�� 9:52:48
 * @version 1.0
 * @description:��Ŀ�ر���ϸ���Ʋ�
 */
class controller_engineering_close_esmclosedetail extends controller_base_action
{

	function __construct() {
		$this->objName = "esmclosedetail";
		$this->objPath = "engineering_close";
		parent::__construct();
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listRuleJson() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_rules');

		// �����Ŀ����û�йرչ�����ô���ձ��밴����������
		if (!$rows) {
			$rows = $this->service->getDefaultRule_d();
		}

		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ��ȡȷ������
	 */
	function c_listConfirm() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_rules');

		// �����Ŀ����û�йرչ�����ô���ձ��밴����������
		if (!$rows) {
			$rows = $this->service->getDefaultRule_d();
			foreach ($rows as $k => $v) {
				$rows[$k]['projectId'] = $_REQUEST['projectId'];
                $rows[$k]['projectCode'] = $_REQUEST['projectCode'];
			}
		} else {
            foreach ($rows as $k => $v) {
                $rows[$k]['projectCode'] = $_REQUEST['projectCode'];
                if($v['ruleId'] == 7){// ����7��Ҫ���¼�������,����������
                    $loanDao = new model_loan_loan_loan();
                    $NoPayLoanAmount = $loanDao->getNoPayLoanAmountByProjId($v['projectId']);
                    if($NoPayLoanAmount <= 0){
                        $updateArr['id'] =  $v['id'];
                        $rows[$k]['val'] = $updateArr['val'] =  "�����";
                        $rows[$k]['status'] = $updateArr['status'] =  "1";
                        $this->service->updateById($updateArr);
                    }else{
                        $updateArr['id'] =  $v['id'];
                        $rows[$k]['val'] = $updateArr['val'] =  "��ǰ������Ŀ���".$NoPayLoanAmount."Ԫδ���壬��黹����������Ŀ���ת�ơ�";
                        $rows[$k]['status'] = $updateArr['status'] =  "0";
                        $this->service->updateById($updateArr);
                    }
                }
            }
        }

		// �߼�������
		echo util_jsonUtil::encode($this->service->dealRule_d($rows));
	}

	/**
	 * ȷ��
	 */
	function c_confirm() {
		echo $this->service->confirm_d($_POST['ids']) ? 1 : 0;
	}
}