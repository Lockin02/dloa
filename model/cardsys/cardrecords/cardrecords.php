<?php
/**
 * @author Show
 * @Date 2012年1月5日 星期四 16:06:21
 * @version 1.0
 * @description:测试卡使用记录(oa_cardsys_cardrecords) Model层
 */
 class model_cardsys_cardrecords_cardrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_cardsys_cardrecords";
		$this->sql_map = "cardsys/cardrecords/cardrecordsSql.php";
		parent::__construct ();
	}
    /*********************** 外部信息获取 *************************/
    /**
     * 获取日志信息
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }
    /***************** 增删改查 ***************************/

	//重写add_d
	function add_d($object){
		try{
			$this->start_d();
			$object['ownerId'] = $_SESSION['USER_ID'];
			$object['ownerName'] = $_SESSION['USERNAME'];
			//新增方法
			$newId = parent::add_d($object,true);

			//获取当前测试卡累计金额
			$allMoney = $this->getAllMoney_d($object['cardId']);

			//更新测试卡累计金额
			$cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
			$cardsinfoDao->updateAllMoney_d($object['cardId'],$allMoney);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    //批量新增 - 最新日志使用
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
        //测试卡对象
        $cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
        try{
            $this->start_d();
            //新增方法
            $object = util_arrayUtil::setArrayFn(array('ownerId' => $_SESSION['USER_ID'],'ownerName' => $_SESSION['USERNAME']),$object);
            $obj = $this->saveDelBatch($object);

            //本次录入金额
            $countMoney = 0;
            //更新所有的累计金额
            foreach($object as $key => $val){
                //获取当前测试卡累计金额
                $allMoney = $this->getAllMoney_d($val['cardId']);

                //更新测试卡累计金额
                $cardsinfoDao->updateAllMoney_d($val['cardId'],$allMoney);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //计算本次录入总金额
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
	 * 测试卡批量新增方法 － 旧日志使用
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
	* 设置关联从表的id信息
	*/
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	//重写edit_d
	function edit_d($object){
		try{
			$this->start_d();
			$orgCardId = $object['orgCardId'];
			unset($object['orgCardId']);

			//新增方法
			$newId = parent::edit_d($object,true);

			//获取当前测试卡累计金额
			$allMoney = $this->getAllMoney_d($object['cardId']);

			//更新测试卡累计金额
			$cardsinfoDao = new model_cardsys_cardsinfo_cardsinfo();
			$cardsinfoDao->updateAllMoney_d($object['cardId'],$allMoney);

			if($orgCardId != $object['cardId']){
				//获取当前测试卡累计金额
				$allMoney = $this->getAllMoney_d($orgCardId);

				//更新测试卡累计金额
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
	 * 重写删除
	 */
	function deletes_d( $ids ){
		$idArr = explode(',',$ids);
		$formNoArr = array();
		try {
			$this->start_d();
			foreach($idArr as $key => $val){
				//获取使用记录
				$obj = $this->find(array('id' => $val),null,'cardId');

				//删除记录
				$this->deletes ( $val );

				//获取当前测试卡累计金额
				$allMoney = $this->getAllMoney_d($obj['cardId']);

				//更新测试卡累计金额
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

	/********************* 业务逻辑处理 ***************************/
	/**
	 * 获取当前卡号累计的金额
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