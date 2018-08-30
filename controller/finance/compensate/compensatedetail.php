<?php
/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单明细控制层
 */
class controller_finance_compensate_compensatedetail extends controller_base_action {

	function __construct() {
		$this->objName = "compensatedetail";
		$this->objPath = "finance_compensate";
		parent :: __construct();
	}

	/**
	 * 跳转到赔偿单明细列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
        $proInfoDao = new model_stock_productinfo_productinfo();

        // 检查并补充相应的物料净值
        foreach ($rows as $k => $row){
            if($row['productNo'] != ''){
                $baseInfo = $proInfoDao->getProByCode($row['productNo']);
                $rows[$k]['price'] = ($rows[$k]['price'] <= 0)? ($baseInfo['priCost']*$row['number']) : $row['price'];
            }
        }
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * 更新赔偿金额
     */
    function c_updateCompensateMoney(){
        echo $this->service->updateCompensateMoney_d($_POST['id'],$_POST['detailCompensateMoney'],$_POST['mainId'],$_POST['compensateMoney']);
    }
}