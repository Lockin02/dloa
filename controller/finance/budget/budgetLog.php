<?php
/**
 * @author zengqin
 * @Date 2015-3-11
 * @version 1.0
 * @description:����Ԥ����ϸ�޸ļ�¼
 */
class controller_finance_budget_budgetLog extends controller_base_action{

		function __construct() {
	        $this->objName = "budgetLog";
	        $this->objPath = "finance_budget";
	        parent::__construct();
    	}
	/**
	 * �鿴�޸���ʷ��¼
	 */
	 function c_toView(){
		$detailId = $_GET ['detailId'];
		$this->assign ( 'detailId',$detailId);
		$budgetDetail = $this->service->getDetailById($detailId);
		foreach ( $budgetDetail as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('view',true);
	 }
}
 ?>