<?php
/**
 * @author Show
 * @Date 2012��1��5�� ������ 16:06:21
 * @version 1.0
 * @description:���Կ�ʹ�ü�¼(oa_cardsys_cardrecords) Model��
 */
 class model_cardsys_cardrecords_cardrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_cardsys_cardrecords";
		$this->sql_map = "cardsys/cardrecords/cardrecordsSql.php";
		parent::__construct ();
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
			$object['ownerId'] = $_SESSION['USER_ID'];
			$object['ownerName'] = $_SESSION['USERNAME'];
			//��������
			$newId = parent::add_d($object,true);

			//��ȡ��ǰ���Կ��ۼƽ��
			$allMoney = $this->getAllMoney_d($object['cardId']);

			//���²��Կ��ۼƽ��
			$cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
			$cardsinfoDao->updateAllMoney_d($object['cardId'],$allMoney);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    //�������� - ������־ʹ��
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
        //���Կ�����
        $cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
        try{
            $this->start_d();
            //��������
            $object = util_arrayUtil::setArrayFn(array('ownerId' => $_SESSION['USER_ID'],'ownerName' => $_SESSION['USERNAME']),$object);
            $obj = $this->saveDelBatch($object);

            //����¼����
            $countMoney = 0;
            //�������е��ۼƽ��
            foreach($object as $key => $val){
                //��ȡ��ǰ���Կ��ۼƽ��
                $allMoney = $this->getAllMoney_d($val['cardId']);

                //���²��Կ��ۼƽ��
                $cardsinfoDao->updateAllMoney_d($val['cardId'],$allMoney);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //���㱾��¼���ܽ��
                $countMoney = bcadd($countMoney,$val['openMoney'],2);
                $countMoney = bcadd($countMoney,$val['rechargerMoney'],2);
            }

            $this->commit_d();
            return $countMoney;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

	/**
	 * ���Կ������������� �� ����־ʹ��
	 */
	function addCardRecords ($cardrecordArr,$id) {
		try {
			foreach ( $cardrecordArr as $key => $val ) {
				if (empty($val ['projectCode']) && empty($val ['cardNo'])) {
					unset($cardrecordArr[$key]);
				}
			}
			$itemsArr = $this->setItemMainId("worklogId", $id, $cardrecordArr);
			$carrecordsdetailObj = $this->saveDelBatch($itemsArr);
		} catch ( exception $e ) {
			return null;
		}
	}

	/**
	* ���ù����ӱ��id��Ϣ
	*/
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	//��дedit_d
	function edit_d($object){
		try{
			$this->start_d();
			$orgCardId = $object['orgCardId'];
			unset($object['orgCardId']);

			//��������
			$newId = parent::edit_d($object,true);

			//��ȡ��ǰ���Կ��ۼƽ��
			$allMoney = $this->getAllMoney_d($object['cardId']);

			//���²��Կ��ۼƽ��
			$cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
			$cardsinfoDao->updateAllMoney_d($object['cardId'],$allMoney);

			if($orgCardId != $object['cardId']){
				//��ȡ��ǰ���Կ��ۼƽ��
				$allMoney = $this->getAllMoney_d($orgCardId);

				//���²��Կ��ۼƽ��
				$cardsinfoDao->updateAllMoney_d($orgCardId,$allMoney);
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
				$obj = $this->find(array('id' => $val),null,'cardId');

				//ɾ����¼
				$this->deletes ( $val );

				//��ȡ��ǰ���Կ��ۼƽ��
				$allMoney = $this->getAllMoney_d($obj['cardId']);

				//���²��Կ��ۼƽ��
				$cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
				$cardsinfoDao->updateAllMoney_d($obj['cardId'],$allMoney);
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
	function getAllMoney_d($cardId){
		$this->searchArr = array('cardId' => $cardId);
		$rs = $this->list_d('select_allmoney');
		if(is_array($rs)){
			return $rs[0]['allMoney'];
		}else{
			return 0;
		}
	}
}
?>