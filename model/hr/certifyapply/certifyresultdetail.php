<?php

/**
 * @author Show
 * @Date 2012��8��28�� ���ڶ� 11:32:28
 * @version 1.0
 * @description:��ְ�ʸ���֤���۽����������ϸ Model��
 */
class model_hr_certifyapply_certifyresultdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyresult_detail";
		$this->sql_map = "hr/certifyapply/certifyresultdetailSql.php";
		parent :: __construct();
	}

	/******************* ������Ϣ ***********************/
    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'baseLevel','baseGrade','finalLevel','finalGrade'
    );

	/******************* ��ɾ�Ĳ� ***********************/

	//��дadd_d
	function add_d($object){
		//�����ֵ䴦��
		$object = $this->processDatadict($object);

		return parent::add_d($object);
	}

	//��дadd_d
	function edit_d($object){
		//�����ֵ䴦��
		$object = $this->processDatadict($object);

		return parent::edit_d($object);
	}

	/**
	 * ��������
	 */
	function batchAdd_d($object,$addArr){
		//ʵ�������۱����
		$assessDao = new model_hr_certifyapply_cassess();
 		//���뵥ʵ����
 		$certifyapplyDao = new model_hr_personnel_certifyapply();

		try{
			$this->start_d();

			foreach($object as $key => $val){

				//�����ֵ䴦��
				$val = $this->processDatadict($val);
				//��չ��Ϣ�ϲ�
				$val = array_merge($val,$addArr);

				parent::add_d($val,true);

				//Դ��ҵ����
				$assessDao->updateStatus_d($val['assessId'],5);

				//�������뵥״̬
				$certifyapplyDao->updateStatus_d($val['applyId'],7);
			}

			$this->commit_d();
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/********************* ҵ���߼����� *********************/
	//�Դӱ���������
	function batchProcess_d($object){
		foreach($object as $key => $val){
			$object[$key] = $this->processDatadict($val);
		}
		return $object;
	}

	/**
	 * ������˱��ȡ��ϸ
	 */
	function getList_d($resultId){
		$this->searchArr = array(
			'resultId' => $resultId
		);
		$this->asc = false;
		return $this->list_d();
	}
}
?>