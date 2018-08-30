<?php
/**
 *
 * 资产采购任务明细表控制层类
 * @author fengxw
 *
 */
class controller_asset_purchase_task_taskItem extends controller_base_action {

	function __construct() {
		$this->objName = "taskItem";
		$this->objPath = "asset_purchase_task";
		parent::__construct ();
	}

	/**
	 * 行政部获取分页数据转成Json
	 */
	function c_getApplyItemPage() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$rows=$applyItemDao->getItemByApplyId($_POST['applyId'],'0','0');
//		echo "<pre>";
//		print_R($rows);
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$rows[$key]['applyEquId']=$val['id'];
				unset($rows[$key]['id']);
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}

}

?>