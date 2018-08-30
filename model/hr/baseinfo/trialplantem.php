<?php
/**
 * @author Show
 * @Date 2012��8��30�� ������ 14:37:54
 * @version 1.0
 * @description:Ա��������ѵ�ƻ�ģ�� Model��
 */
 class model_hr_baseinfo_trialplantem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem";
		$this->sql_map = "hr/baseinfo/trialplantemSql.php";
		parent::__construct ();
	}

	/*********************** ��ɾ�Ĳ� **************/
	//��дadd_d
	function add_d($object){
		$trialplantemdetail = $object['trialplantemdetail'];
		unset($object['trialplantemdetail']);

		try{
			$this->start_d();

			//����
			$newId = parent::add_d($object,true);

			//�����ӱ���Ϣ
			$trialplantemdetailDao = new model_hr_baseinfo_trialplantemdetail();
			$trialplantemdetail = $trialplantemdetailDao->batchDeal_d($trialplantemdetail,array('planId' => $newId));
			$trialplantemdetailDao->saveDelBatch($trialplantemdetail);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	//��дedit_d
	function edit_d($object){
		$trialplantemdetail = $object['trialplantemdetail'];
		unset($object['trialplantemdetail']);

//		echo "<pre>";print_r($trialplantemdetail);die();
		try{
			$this->start_d();

			//����
			parent::edit_d($object,true);

			//�����ӱ���Ϣ
			$trialplantemdetailDao = new model_hr_baseinfo_trialplantemdetail();
			$trialplantemdetail = $trialplantemdetailDao->batchDeal_d($trialplantemdetail);
			$trialplantemdetailDao->saveDelBatch($trialplantemdetail);

			$this->commit_d();
			return true;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}
}
?>