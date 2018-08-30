<?php
/**
 * @author Show
 * @Date 2013年5月23日 星期四 10:54:51
 * @version 1.0
 * @description:质检报告清单控制层
 */
class controller_produce_quality_qualityereportequitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereportequitem";
		$this->objPath = "produce_quality";
		parent :: __construct();
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
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * 质检情况查询
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