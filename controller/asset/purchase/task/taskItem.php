<?php
/**
 *
 * �ʲ��ɹ�������ϸ����Ʋ���
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
	 * ��������ȡ��ҳ����ת��Json
	 */
	function c_getApplyItemPage() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
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