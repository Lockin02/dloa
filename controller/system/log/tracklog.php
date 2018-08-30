<?php
/**
 * 业务对象操作轨迹控制层
 */
class controller_system_log_tracklog extends controller_base_action {

	function __construct() {
		$this->objName = "tracklog";
		$this->objPath = "system_log";
		parent::__construct ();
	 }

	function c_list() {
		if($_GET['objType']){
			$this->assign('objType',$_GET['objType']);
		}
		if($_GET['objId']){
			$this->assign('objId',$_GET['objId']);
		}
		$this->view ( 'list' );
	}


	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort="createTime";
		$service->asc=false;
		$rows = $service->page_d ();
		//数据加入安全码
		foreach($rows as $k=>$v){
			$sconfig=new model_common_securityUtil ($v['reObjType']);
			$newV=array("id"=>$v['reObjId']);
			$rows[$k]['skey_']=$sconfig->md5Row($newV);
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


}
?>