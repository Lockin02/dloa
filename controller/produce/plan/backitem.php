<?php
/**
 * @author Bingo
 * @Date 2015��5��11�� 
 * @version 1.0
 * @description:�����������뵥�嵥���Ʋ�
 */
class controller_produce_plan_backitem extends controller_base_action {

	function __construct() {
		$this->objName = "backitem";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }
	 
	 /**
	  * ��дlistJson
	  */
	 function c_listJson() {
	 	$service = $this->service;
	 	$service->getParam ( $_REQUEST );
	 	$rows = $service->list_d ();
	 	if (is_array($rows)) {
	 		$pickDao = new model_produce_plan_picking();
	 		foreach ($rows as $key => $val) {
	 			$numArr = $pickDao->getProductNum_d($val['productCode']);
	 			$rows[$key]['JSBC'] = $numArr['JSBC']; //���豸������
	 			$rows[$key]['KCSP'] = $numArr['KCSP']; //�����Ʒ������
	 			$rows[$key]['SCC']  = $numArr['SCC'];  //����������
	 		}
	 	}
	 	if(isset($_REQUEST['pickingId']) && $_REQUEST['type'] == 'edit'){//�༭�������Ƶ��������뵥ʱ����
	 		$rows = $service->dataProcessAtEdit_d ($rows,$_REQUEST['pickingId']);
	 	}
	 	//���ݼ��밲ȫ��
	 	$rows = $this->sconfig->md5Rows ( $rows );
	 	echo util_jsonUtil::encode ( $rows );
	 }
 }