<?php
/**
 * @author zengq
 * @Date 2012��10��7�� ������ 15:16:42
 * @version 1.0
 * @description:��ʦ���˷��� Model��
 */
 class model_hr_tutor_tutorScheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorscheme";
		$this->sql_map = "hr/tutor/tutorSchemeSql.php";
		parent::__construct ();
	}

	/**
	 * ��д��������
	 */
	 function add_d($object){
	 	try{
	 		$this->start_d();
			//�жϵ�ʦ���˷��������Ƿ��ظ�
	 		$schemeName = $object['schemeName'];
	 		$tutorScheme = $this->findBy('schemeName',$schemeName);
			if(!empty($tutorScheme)){
				throw new Exception("��ʦ���˷��������ظ�!");
			}

			//���������������
			$parentId=parent::add_d($object,true);

			//����ӱ�
			if(!empty($object['attrvals'])){
				$schemeDetailDao = new model_hr_tutor_schemeDetail();
				$schemeDetailDao->createBatch($object['attrvals'],array(
						'parentId'=>$parentId
					),'appraisal');
			}
			$this->commit_d();
			return true;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		throw $e;
	 		//return false;
	 	}
	 }
	 /**
	  * ��д�༭����
	  */
	  function edit_d($object){
	  		try{
	  			$this->start_d();

	  			//�༭��������
				parent :: edit_d($object, true);

				$parentId = $object['id'];
				//����ӱ�����
				$schemeDetailDao = new model_hr_tutor_schemeDetail();
				$mainArr=array("parentId"=>$parentId);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object['attrvals']);

				$schemeDetailDao->saveDelBatch($itemsArr);
	  			$this->commit_d();
	  			return true;
	  		}catch(Exception $e){
	  			$this->rollBack();
	  			return false;
	  		}
	  }
 }
?>