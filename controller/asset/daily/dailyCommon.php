<?php

/**
 * �̶��ʲ��ճ��������ÿ��Ʋ���
 *  @author zengzx
 *  @since 1.0 - 2011-11-29
 */
class controller_asset_daily_dailyCommon extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "dailyCommon";
		$this->objPath = "asset_daily";
		parent::__construct ();
	}

	/**
	 * �̶��ʲ��ճ�������������ִ�з���
	 */
	function c_dealAfterAudit(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
		if($folowInfo ['examines'] != "no"){
      	 	$this->service->ctUpdateRelInfoAtAudit($_GET['rows'],$_GET['docType']);
		}
		if($folowInfo ['examines'] == "ok"){
      	 	$this->service->ctDealRelInfoAtAudit($objId,$_GET['docType']);
		}
       	echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}

}
?>