<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请明细控制层
 */
class controller_asset_require_requireinitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireinitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }

	/**
	 * 跳转到物料转资产申请明细列表
	 */
    function c_page() {
    	$this->assign('requireId',$_GET['requireId']);
    	$this->view('list');
    }
    
	/**
	 * 获取所有数据返回json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if($_REQUEST['type'] == 'add'){
			$service->searchArr['numCondition'] = 'sql: and c.number-c.executedNum > 0';
		}
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 下推出库时添加从表信息
	 */
	function c_getOutStockDetail() {
		$service = $this->service;

		$rows = $service->getOutStockDetail_d(isset($_POST['requireId'])?$_POST['requireId']:null);
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
		echo util_jsonUtil::iconvGB2UTF ($service->showProAtEdit($rows));
	}
	
	/**
	 * 根据mainId检验是否有生成的卡片存在-有则不允许下推红字出库
	 */
	function c_isCardExist(){
		if($this->service->isCardExist_d($_POST['mainId'])){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * 下推验收单时添加从表信息
	 */
	function c_getReceiveDetail() {
		$service = $this->service;
	
		$rows = $service->getReceiveDetail_d(isset($_POST['requireId'])?$_POST['requireId']:null);
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 资产出库时，获取实际可入库的物料数量
	 */
	function c_getNumAtInStock(){
		$rs = $this->service->find(array('id' => $_POST['id']),null,'number,executedNum');
		echo $rs['number'] - $rs['executedNum'];
	}
 }