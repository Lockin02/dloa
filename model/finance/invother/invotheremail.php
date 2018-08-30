<?php

class model_finance_invother_invotheremail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invother";
		$this->sql_map = "finance/invother/invotherSql.php";
		parent::__construct ();
	}
	
	/**
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d(){
	
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser['invother']) ? $mailUser['invother'] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}
	
	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object){
		$thisMoney = empty($object['formCount']) ? $object['amount'] : $object['formCount'];
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $_SESSION['USERNAME'].' �ѷ��ͷ�Ʊ '.$object['invoiceNo'].' ,��Ӧ������Ϊ: '.$object['supplierName'].' ,���Ϊ��'.$thisMoney;
	
		$emailDao = new model_common_mail();
		$emailDao->mailClear('������Ʊ',$emailArr['TO_ID'],$addMsg);
	}
	
	function email_d($object){
		$codeRuleDao = new model_common_codeRule();
	
		if(isset($object['mail'])){
			$emailArr = $object['mail'];
			unset($object['mail']);
		}
	
		try{
			$this->start_d ();
			if($emailArr){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
		}
		catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		//		$invotherdetailDao = new model_finance_invother_invotherdetail();
		//		$invotherdetailDao->searchArr ['mainId'] = $id;
		//		$object ['items'] = $invotherdetailDao->listBySqlId ();
		return $object;
	}
}
?>