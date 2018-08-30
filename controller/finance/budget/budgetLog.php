<?php
/**
 * @author zengqin
 * @Date 2015-3-11
 * @version 1.0
 * @description:费用预算明细修改记录
 */
class controller_finance_budget_budgetLog extends controller_base_action{

		function __construct() {
	        $this->objName = "budgetLog";
	        $this->objPath = "finance_budget";
	        parent::__construct();
    	}
	/**
	 * 查看修改历史记录
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