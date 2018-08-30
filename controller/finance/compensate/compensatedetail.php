<?php
/**
 * @author show
 * @Date 2013��10��24�� 19:30:28
 * @version 1.0
 * @description:�⳥����ϸ���Ʋ�
 */
class controller_finance_compensate_compensatedetail extends controller_base_action {

	function __construct() {
		$this->objName = "compensatedetail";
		$this->objPath = "finance_compensate";
		parent :: __construct();
	}

	/**
	 * ��ת���⳥����ϸ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
        $proInfoDao = new model_stock_productinfo_productinfo();

        // ��鲢������Ӧ�����Ͼ�ֵ
        foreach ($rows as $k => $row){
            if($row['productNo'] != ''){
                $baseInfo = $proInfoDao->getProByCode($row['productNo']);
                $rows[$k]['price'] = ($rows[$k]['price'] <= 0)? ($baseInfo['priCost']*$row['number']) : $row['price'];
            }
        }
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * �����⳥���
     */
    function c_updateCompensateMoney(){
        echo $this->service->updateCompensateMoney_d($_POST['id'],$_POST['detailCompensateMoney'],$_POST['mainId'],$_POST['compensateMoney']);
    }
}