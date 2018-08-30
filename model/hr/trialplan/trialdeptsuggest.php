<?php

/**
 * @author Show
 * @Date 2012��9��12�� ������ 16:25:18
 * @version 1.0
 * @description:Ա��ʹ�ò��Ž���� Model��
 */
class model_hr_trialplan_trialdeptsuggest extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialdeptsuggest";
		$this->sql_map = "hr/trialplan/trialdeptsuggestSql.php";
		parent :: __construct();
	}

	//���� - ��������Ա���б���д���Ž�����
	function addInPersonnel_d($object){
		$object['ExaStatus'] = WAITAUDIT;
		try{
			$newId = parent::add_d($object,true);

			return $newId;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}


	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){
			//�ʼ����Ͳ���
//			include (WEB_TOR."model/common/mailConfig.php");
//			$mailArr = isset($mailUser['deptsuggest']) ? $mailUser['deptsuggest'] : array('sendUserId'=>'','sendName'=>'');
//
//			$this->dismissMail_d($mailArr,$object);
			return 1;
	 	}
	 	return 1;
	}

	/**
	 * ���Ž���-�����ʼ�����
	 */
	function dismissMail_d($emailArr,$object){
		$addMsg = $_SESSION['USERNAME'].' �Ѷ� ����Ա�� ��'.$object['userName'].'��¼�벿�Ž��� ��'.$object['deptSuggestName'] .'��,';
		$addMsg .= '<br/>�����������£�'.$object['suggestion'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('OA-����Ա�����Ž���',$emailArr['TO_ID'],$addMsg);
	}
}
?>