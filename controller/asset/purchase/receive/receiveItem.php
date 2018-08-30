<?php
/**
 *
 * 资产验收明细表控制层类
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
	 * 跳转到采购申请验收明细表
	 */
    function c_page() {
    	$this->assign('receiveId',$_GET['receiveId']);
      	$this->view('list');
    }
    
    /**
     * 跳转到物料转资产验收明细表
     */
    function c_pageByRequirein() {
    	$this->assign('receiveId',$_GET['receiveId']);
    	$this->view('requirein-list');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_getApplyItemPage() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$rows=$applyItemDao->getDelItemByApplyId($_POST['applyId'],'0');
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$rows[$key]['applyEquId']=$val['id'];
				$equId=$val['id'];
				//计算出此物料的未验收数量
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
	 * 获取采购订单的物料转成验收单物料
	 */
	function c_getPurchaseContractEqus(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
		$dao=new model_purchase_contract_equipment();
		$rows=$dao->getEqusByContractId($_POST['purchaseContractId']);
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				//$rows[$key]['purchAmount']=$val['amountAll'];
				$rows[$key]['contractEquId']=$val['id'];
				$equId=$val['id'];
				//计算出此物料的未验收数量
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
	 * 获取收料通知单的物料转成验收单物料
	 */
	function c_getArrivalEqus(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//获取采购需求明细信息
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
	 * 获取所有数据返回json
	 */
	function c_isCardJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)&&count($rows)>0){
			echo 1;
		}else{
			echo 0;
		}
	}
}

?>