<?php
/**
 * @author Show
 * @Date 2011��9��22�� ������ 10:30:00
 * @version 1.0
 * @description:�����ʼ���¼���Ʋ�
 */
class controller_finance_income_mailrecord extends controller_base_action {

	function __construct() {
		$this->objName = "mailrecord";
		$this->objPath = "finance_income";
		parent::__construct ();
	}

	/*
	 * ��ת�������ʼ���¼
	 */
    function c_page() {
		$this->assign("hasTimeTask",$this->service->getHasTimetask_d());
   		$this->display('list');
    }

    /**
     * ״̬�޸�
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