<?php
/**
 *
 * �ʲ�������ϸ����Ʋ���
 * @author fengxw
 *
 */
class controller_asset_purchase_receive_receiveItem extends controller_base_action {

	function __construct() {
		$this->objName = "receiveItem";
		$this->objPath = "asset_purchase_receive";
		parent::__construct ();
	}

	/**
	 * ��ת���ɹ�����������ϸ��
	 */
    function c_page() {
    	$this->assign('receiveId',$_GET['receiveId']);
      	$this->view('list');
    }
    
    /**
     * ��ת������ת�ʲ�������ϸ��
     */
    function c_pageByRequirein() {
    	$this->assign('receiveId',$_GET['receiveId']);
    	$this->view('requirein-list');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_getApplyItemPage() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$rows=$applyItemDao->getDelItemByApplyId($_POST['applyId'],'0');
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$rows[$key]['applyEquId']=$val['id'];
				$equId=$val['id'];
				//����������ϵ�δ��������
				$service->searchArr=array('applyEquId'=>$equId);
				$list=$service->list_d();
				$hadCheckAmount=0;
				if(is_array($list)){
					foreach($list as $k=>$v){
						$hadCheckAmount+=$v['checkAmount'];
					}
				}
				$rows[$key]['checkAmount']=$val['applyAmount']-$hadCheckAmount;
				unset($rows[$key]['id']);
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�ɹ�����������ת�����յ�����
	 */
	function c_getPurchaseContractEqus(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
		$dao=new model_purchase_contract_equipment();
		$rows=$dao->getEqusByContractId($_POST['purchaseContractId']);
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				//$rows[$key]['purchAmount']=$val['amountAll'];
				$rows[$key]['contractEquId']=$val['id'];
				$equId=$val['id'];
				//����������ϵ�δ��������
				$service->searchArr=array('contractEquId'=>$equId);
				$list=$service->list_d();
				$hadCheckAmount=0;
				if(is_array($list)){
					foreach($list as $k=>$v){
						$hadCheckAmount+=$v['checkAmount'];
					}
				}
				$rows[$key]['checkAmount']=$val['amountAll']-$hadCheckAmount;
				unset($rows[$key]['id']);
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}
	/**
	 * ��ȡ����֪ͨ��������ת�����յ�����
	 */
	function c_getArrivalEqus(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//��ȡ�ɹ�������ϸ��Ϣ
		$dao=new model_purchase_arrival_equipment();
		$rows=$dao->getItemByBasicIdId_d($_POST['arrivalId']);
		$result=array();
		foreach ($rows as $key=>$value){
			if($value['arrivalNum']-$value['storageNum']>0){
				$value['arrivalNum']=$value['arrivalNum']-$value['storageNum'];
				array_push($result,$value);
			}
		}
		//print_r();
		echo util_jsonUtil::encode ( $result );
	}


	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_isCardJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)&&count($rows)>0){
			echo 1;
		}else{
			echo 0;
		}
	}
}

?>