<?php
/**
 * @author Show
 * @Date 2013��5��23�� ������ 10:54:51
 * @version 1.0
 * @description:�ʼ챨���嵥���Ʋ�
 */
class controller_produce_quality_qualityereportequitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereportequitem";
		$this->objPath = "produce_quality";
		parent :: __construct();
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
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * �ʼ������ѯ
	 */
	function c_showQualityDetail(){
		if(empty($_REQUEST['objId'])) exit();
		$service = $this->service;
		$service->sort = "c.productCode";
		$service->asc = false;
		$service->groupBy = "c.objItemId";
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_docCount');
		echo util_jsonUtil::encode ( $rows );
	}
}