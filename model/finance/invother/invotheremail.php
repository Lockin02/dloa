<?php

class model_finance_invother_invotheremail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invother";
		$this->sql_map = "finance/invother/invotherSql.php";
		parent::__construct ();
	}
	
	/**
	 * 邮件配置获取
	 */
	function getMailInfo_d(){
	
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser['invother']) ? $mailUser['invother'] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}
	
	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr,$object){
		$thisMoney = empty($object['formCount']) ? $object['amount'] : $object['formCount'];
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $_SESSION['USERNAME'].' 已发送发票 '.$object['invoiceNo'].' ,供应商名称为: '.$object['supplierName'].' ,金额为：'.$thisMoney;
	
		$emailDao = new model_common_mail();
		$emailDao->mailClear('其他发票',$emailArr['TO_ID'],$addMsg);
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
	 * 通过id获取详细信息
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