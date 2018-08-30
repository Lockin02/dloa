<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.0
 * @description:费用预算控制层
 */
class controller_finance_budget_budgetDetail extends controller_base_action{

	function __construct() {
        $this->objName = "budgetDetail";
        $this->objPath = "finance_budget";
        parent::__construct();
    }

	/**
	 *根据区域ID获取费用预算
	 *@param string areaId
	 *@return budgetDetail对象
	 */
	 function c_getByAreaId(){
		$areaId = $_POST['areaId'];
		$time = time();
		$year = date("Y",$time);
		$areaId = $this->service->checkArea($areaId);
		$condition = array("areaId"=>$areaId,"year"=>$year);
		$obj = $this->service->find($condition);
		echo util_jsonUtil::encode($obj);
	 }

	 /**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		$userId = $_SESSION['USER_ID']; //当前用户UserId
		if($userId){
			//获取当前所负责的区域，只能查看自己负责区域的预决算
			$regionDao = new model_system_region_region();
			$ids = $regionDao->getUserAreaId($userId,2);
			if(strlen(trim($ids))>0){
				$service->searchArr = array("areaIds"=>$ids);
			}
		}
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
 ?>