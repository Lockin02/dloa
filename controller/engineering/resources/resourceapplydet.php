<?php
/**
 * @author show
 * @Date 2013年11月15日 16:10:52
 * @version 1.0
 * @description:项目设备申请明细控制层
 */
class controller_engineering_resources_resourceapplydet extends controller_base_action {

	function __construct() {
		$this->objName = "resourceapplydet";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}

	/**
	 * 获取设备申请个下达任务的设备明细
	 */
	function c_listJsonForTask() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if($rows){
			foreach($rows as &$v){
				$v['needExeNum'] = $v['number'] - $v['exeNumber'];
				$v['thisExeNum'] = 0;
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取单据设备金额
	 */
	function c_getDetailAmount(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_count');
		if($rows[0]['amount']){
			echo $rows[0]['amount'];
		}else{
			echo 0;
		}
		exit;
	}
}