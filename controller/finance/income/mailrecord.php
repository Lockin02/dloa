<?php
/**
 * @author Show
 * @Date 2011年9月22日 星期四 10:30:00
 * @version 1.0
 * @description:到款邮件记录控制层
 */
class controller_finance_income_mailrecord extends controller_base_action {

	function __construct() {
		$this->objName = "mailrecord";
		$this->objPath = "finance_income";
		parent::__construct ();
	}

	/*
	 * 跳转到到款邮件记录
	 */
    function c_page() {
		$this->assign("hasTimeTask",$this->service->getHasTimetask_d());
   		$this->display('list');
    }

    /**
     * 状态修改
     */
    function c_changeStatus(){
		if($this->service->edit_d($_POST)){
			echo 1;
		}else{
			echo 0;
		}
    }
}
?>