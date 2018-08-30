<?php
/**
 * @author tse
 * @Date 2014年3月14日 10:43:27
 * @version 1.0
 * @description:审批付款时间变更表 Model层 
 */
 class model_finance_payablesapply_payablesapplychange  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payablesapply_changeaudit";
		$this->sql_map = "finance/payablesapply/payablesapplychangeSql.php";
		parent::__construct ();
	}
	/**
	 * 变更审批付款日期后执行方法
	 * @param unknown $spid
	 * @return number
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ( $spid );
		$condition = array('id' => $folowInfo ['objId']);
		$obj = $this->get_d( $folowInfo ['objId']);
		$payablesapplyDao  = new  model_finance_payablesapply_payablesapply();
		try{
			$this->start_d();
			$payablesapplyDao->update(array('id' => $obj['purOrderId']),array('auditDate' => $obj['newAuditDate']));
			$this->commit_d();
			return 1;
		}catch(Exception $e){
			$this->rollBack();
			return 1;
		}
	}
 }
?>