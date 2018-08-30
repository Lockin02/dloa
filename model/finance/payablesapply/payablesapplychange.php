<?php
/**
 * @author tse
 * @Date 2014��3��14�� 10:43:27
 * @version 1.0
 * @description:��������ʱ������ Model�� 
 */
 class model_finance_payablesapply_payablesapplychange  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payablesapply_changeaudit";
		$this->sql_map = "finance/payablesapply/payablesapplychangeSql.php";
		parent::__construct ();
	}
	/**
	 * ��������������ں�ִ�з���
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