<?php
/**
 * ҵ���������켣���Ʋ�
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
		//���ݼ��밲ȫ��
		foreach($rows as $k=>$v){
			$sconfig=new model_common_securityUtil ($v['reObjType']);
			$newV=array("id"=>$v['reObjId']);
			$rows[$k]['skey_']=$sconfig->md5Row($newV);
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


}
?>