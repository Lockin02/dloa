<?php
/**
 * @author Show
 * @Date 2012��7��17�� ���ڶ� 19:13:24
 * @version 1.0
 * @description:��Ƹ��Ա��¼ Model��
 */
class model_engineering_tempperson_personrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_tempperson_records";
		$this->sql_map = "engineering/tempperson/personrecordsSql.php";
		parent :: __construct();
	}
    /*********************** �ⲿ��Ϣ��ȡ *************************/
    /**
     * ��ȡ��־��Ϣ
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }

	/***************** ��ɾ�Ĳ� ***************************/

	//��дadd_d
	function add_d($object){
		try{
			$this->start_d();
			//��������
			$newId = parent::add_d($object,true);

			//��ȡ��ǰ��Ƹ��Ա�ۼƽ��
			$countArr = $this->getCount_d($object['personId']);

			//������Ƹ��Ա�ۼƽ��
			$temppersonDao = new model_engineering_tempperson_tempperson();
			$temppersonDao->updateAllMoney_d($object['personId'],$countArr['allMoney'] ,$countArr['allDays']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    //��������
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();
        try{
            $this->start_d();
            //��������
            $obj = $this->saveDelBatch($object);

            //����¼����
            $countMoney = 0;
            //ʵ������Ƹ��Ա��
            $temppersonDao = new model_engineering_tempperson_tempperson();
            //�������е��ۼƽ��
            foreach($object as $key => $val){
                //��ȡ��ǰ��Ƹ��Ա�ۼƽ��
                $countArr = $this->getCount_d($val['personId']);
                //������Ƹ��Ա�ۼƽ��
                $temppersonDao->updateAllMoney_d($val['personId'],$countArr['allMoney'] ,$countArr['allDays']);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //���㱾��¼���ܽ��
                $countMoney = bcadd($countMoney,$val['money'],2);
            }

            $this->commit_d();
            return $countMoney;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

	//��дedit_d
	function edit_d($object){
		try{
			$this->start_d();
			$orgPersonId = $object['orgPersonId'];
			unset($object['orgPersonId']);

			//��������
			$newId = parent::edit_d($object,true);

			//��ȡ��ǰ���Կ��ۼƽ��
			$countArr = $this->getCount_d($object['personId']);

			//���²��Կ��ۼƽ��
			$temppersonDao = new model_engineering_tempperson_tempperson();
			$temppersonDao->updateAllMoney_d($object['personId'],$countArr['allMoney'] ,$countArr['allDays']);

			if($orgPersonId != $object['personId']){
				//��ȡ��ǰ���Կ��ۼƽ��
				$countArr = $this->getCount_d($orgPersonId);

				$temppersonDao->updateAllMoney_d($orgPersonId,$countArr['allMoney'] ,$countArr['allDays']);
			}

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дɾ��
	 */
	function deletes_d( $ids ){
		$idArr = explode(',',$ids);
		$formNoArr = array();
		try {
			$this->start_d();
			foreach($idArr as $key => $val){
				//��ȡʹ�ü�¼
				$obj = $this->find(array('id' => $val),null,'personId');

				//ɾ����¼
				$this->deletes ( $val );

				//��ȡ��ǰ���Կ��ۼƽ��
				$countArr = $this->getCount_d($obj['personId']);

				//���²��Կ��ۼƽ��
				$temppersonDao = new model_engineering_tempperson_tempperson();
				$temppersonDao->updateAllMoney_d($obj['personId'],$countArr['allMoney'] ,$countArr['allDays']);
			}
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/********************* ҵ���߼����� ***************************/
	/**
	 * ��ȡ��ǰ�����ۼƵĽ��
	 */
	function getCount_d($cardId){
		$this->searchArr = array('personId' => $cardId);
		$rs = $this->list_d('select_count');
		if(is_array($rs)){
			return $rs[0];
		}else{
			return array(
				'allMoney' => 0,
				'allDays' => 0
			);
		}
	}
}
?>