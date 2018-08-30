<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:����ת�ʲ�������ϸ���Ʋ�
 */
class controller_asset_require_requireinitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireinitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }

	/**
	 * ��ת������ת�ʲ�������ϸ�б�
	 */
    function c_page() {
    	$this->assign('requireId',$_GET['requireId']);
    	$this->view('list');
    }
    
	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if($_REQUEST['type'] == 'add'){
			$service->searchArr['numCondition'] = 'sql: and c.number-c.executedNum > 0';
		}
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * ���Ƴ���ʱ��Ӵӱ���Ϣ
	 */
	function c_getOutStockDetail() {
		$service = $this->service;

		$rows = $service->getOutStockDetail_d(isset($_POST['requireId'])?$_POST['requireId']:null);
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
		echo util_jsonUtil::iconvGB2UTF ($service->showProAtEdit($rows));
	}
	
	/**
	 * ����mainId�����Ƿ������ɵĿ�Ƭ����-�����������ƺ��ֳ���
	 */
	function c_isCardExist(){
		if($this->service->isCardExist_d($_POST['mainId'])){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * �������յ�ʱ��Ӵӱ���Ϣ
	 */
	function c_getReceiveDetail() {
		$service = $this->service;
	
		$rows = $service->getReceiveDetail_d(isset($_POST['requireId'])?$_POST['requireId']:null);
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * �ʲ�����ʱ����ȡʵ�ʿ�������������
	 */
	function c_getNumAtInStock(){
		$rs = $this->service->find(array('id' => $_POST['id']),null,'number,executedNum');
		echo $rs['number'] - $rs['executedNum'];
	}
 }