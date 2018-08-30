<?php

/**
 * @author show
 * @Date 2015年2月6日 9:52:48
 * @version 1.0
 * @description:项目关闭明细控制层
 */
class controller_engineering_close_esmclosedetail extends controller_base_action
{

	function __construct() {
		$this->objName = "esmclosedetail";
		$this->objPath = "engineering_close";
		parent::__construct();
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listRuleJson() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_rules');

		// 如果项目本身没有关闭规则，那么按照必须按规则来处理
		if (!$rows) {
			$rows = $this->service->getDefaultRule_d();
		}

		echo util_jsonUtil::encode($rows);
	}

	/**
	 * 获取确认数据
	 */
	function c_listConfirm() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_rules');

		// 如果项目本身没有关闭规则，那么按照必须按规则来处理
		if (!$rows) {
			$rows = $this->service->getDefaultRule_d();
			foreach ($rows as $k => $v) {
				$rows[$k]['projectId'] = $_REQUEST['projectId'];
                $rows[$k]['projectCode'] = $_REQUEST['projectCode'];
			}
		} else {
            foreach ($rows as $k => $v) {
                $rows[$k]['projectCode'] = $_REQUEST['projectCode'];
                if($v['ruleId'] == 7){// 规则7需要重新检查借款余额,并更新数据
                    $loanDao = new model_loan_loan_loan();
                    $NoPayLoanAmount = $loanDao->getNoPayLoanAmountByProjId($v['projectId']);
                    if($NoPayLoanAmount <= 0){
                        $updateArr['id'] =  $v['id'];
                        $rows[$k]['val'] = $updateArr['val'] =  "已完成";
                        $rows[$k]['status'] = $updateArr['status'] =  "1";
                        $this->service->updateById($updateArr);
                    }else{
                        $updateArr['id'] =  $v['id'];
                        $rows[$k]['val'] = $updateArr['val'] =  "当前仍有项目借款".$NoPayLoanAmount."元未还清，请归还借款或申请项目借款转移。";
                        $rows[$k]['status'] = $updateArr['status'] =  "0";
                        $this->service->updateById($updateArr);
                    }
                }
            }
        }

		// 逻辑规则处理
		echo util_jsonUtil::encode($this->service->dealRule_d($rows));
	}

	/**
	 * 确认
	 */
	function c_confirm() {
		echo $this->service->confirm_d($_POST['ids']) ? 1 : 0;
	}
}