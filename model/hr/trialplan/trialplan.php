<?php

/**
 * @author Show
 * @Date 2012��8��31�� ������ 14:53:01
 * @version 1.0
 * @description:Ա��������ѵ�ƻ� Model��
 */
class model_hr_trialplan_trialplan extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan";
		$this->sql_map = "hr/trialplan/trialplanSql.php";
		parent :: __construct();
	}

	/**************** ��ɾ�Ĳ� ****************/
	/**
	 * ����
	 */
	function add_d($object){
		//��ȡ�ӱ���Ϣ
		$trialpalndetail = $object['trialpalndetail'];
		unset($object['trialpalndetail']);

		try{
			$this->start_d();
			//����
			$newId = parent::add_d($object,true);

			//�ӱ���
			$trialpalndetailDao = new model_hr_trialplan_trialplandetail();
			$rtArr = $trialpalndetailDao->batchAdd_d($trialpalndetail,array('planId' => $newId,'memberName' => $object['memberName'],'memberId' => $object['memberId']));

			//������Ϣ����
			$personnelDao = new model_hr_personnel_personnel();
			$personnelArr = array(
				'trialPlanId' => $newId,
				'trialPlan' => $object['planName'],
				'trialTaskId' => $rtArr['id'],
				'trialTask' => $rtArr['taskName'],
				'baseScore' => $object['baseScore'],
				'trialPlanProcess' => 0
			);
			$personnelDao->updatePersonnel_d($object['memberId'],$personnelArr);


			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}
}
?>