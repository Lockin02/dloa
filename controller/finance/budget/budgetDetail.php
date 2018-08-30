<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.0
 * @description:����Ԥ����Ʋ�
 */
class controller_finance_budget_budgetDetail extends controller_base_action{

	function __construct() {
        $this->objName = "budgetDetail";
        $this->objPath = "finance_budget";
        parent::__construct();
    }

	/**
	 *��������ID��ȡ����Ԥ��
	 *@param string areaId
	 *@return budgetDetail����
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
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );

		$userId = $_SESSION['USER_ID']; //��ǰ�û�UserId
		if($userId){
			//��ȡ��ǰ�����������ֻ�ܲ鿴�Լ����������Ԥ����
			$regionDao = new model_system_region_region();
			$ids = $regionDao->getUserAreaId($userId,2);
			if(strlen(trim($ids))>0){
				$service->searchArr = array("areaIds"=>$ids);
			}
		}
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
 ?>