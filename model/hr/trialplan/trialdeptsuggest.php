<?php

/**
 * @author Show
 * @Date 2012年9月12日 星期三 16:25:18
 * @version 1.0
 * @description:员工使用部门建议表 Model层
 */
class model_hr_trialplan_trialdeptsuggest extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialdeptsuggest";
		$this->sql_map = "hr/trialplan/trialdeptsuggestSql.php";
		parent :: __construct();
	}

	//新增 - 用在试用员工列表填写部门建议中
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
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){
			//邮件发送部分
//			include (WEB_TOR."model/common/mailConfig.php");
//			$mailArr = isset($mailUser['deptsuggest']) ? $mailUser['deptsuggest'] : array('sendUserId'=>'','sendName'=>'');
//
//			$this->dismissMail_d($mailArr,$object);
			return 1;
	 	}
	 	return 1;
	}

	/**
	 * 部门建议-辞退邮件发送
	 */
	function dismissMail_d($emailArr,$object){
		$addMsg = $_SESSION['USERNAME'].' 已对 试用员工 【'.$object['userName'].'】录入部门建议 【'.$object['deptSuggestName'] .'】,';
		$addMsg .= '<br/>建议描述如下：'.$object['suggestion'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('OA-试用员工部门建议',$emailArr['TO_ID'],$addMsg);
	}
}
?>