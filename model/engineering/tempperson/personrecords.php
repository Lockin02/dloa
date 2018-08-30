<?php
/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:24
 * @version 1.0
 * @description:临聘人员记录 Model层
 */
class model_engineering_tempperson_personrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_tempperson_records";
		$this->sql_map = "engineering/tempperson/personrecordsSql.php";
		parent :: __construct();
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
			//新增方法
			$newId = parent::add_d($object,true);

			//获取当前临聘人员累计金额
			$countArr = $this->getCount_d($object['personId']);

			//更新临聘人员累计金额
			$temppersonDao = new model_engineering_tempperson_tempperson();
			$temppersonDao->updateAllMoney_d($object['personId'],$countArr['allMoney'] ,$countArr['allDays']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    //批量新增
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();
        try{
            $this->start_d();
            //新增方法
            $obj = $this->saveDelBatch($object);

            //本级录入金额
            $countMoney = 0;
            //实例化临聘人员类
            $temppersonDao = new model_engineering_tempperson_tempperson();
            //更新所有的累计金额
            foreach($object as $key => $val){
                //获取当前临聘人员累计金额
                $countArr = $this->getCount_d($val['personId']);
                //更新临聘人员累计金额
                $temppersonDao->updateAllMoney_d($val['personId'],$countArr['allMoney'] ,$countArr['allDays']);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //计算本次录入总金额
                $countMoney = bcadd($countMoney,$val['money'],2);
            }

            $this->commit_d();
            return $countMoney;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

	//重写edit_d
	function edit_d($object){
		try{
			$this->start_d();
			$orgPersonId = $object['orgPersonId'];
			unset($object['orgPersonId']);

			//新增方法
			$newId = parent::edit_d($object,true);

			//获取当前测试卡累计金额
			$countArr = $this->getCount_d($object['personId']);

			//更新测试卡累计金额
			$temppersonDao = new model_engineering_tempperson_tempperson();
			$temppersonDao->updateAllMoney_d($object['personId'],$countArr['allMoney'] ,$countArr['allDays']);

			if($orgPersonId != $object['personId']){
				//获取当前测试卡累计金额
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
	 * 重写删除
	 */
	function deletes_d( $ids ){
		$idArr = explode(',',$ids);
		$formNoArr = array();
		try {
			$this->start_d();
			foreach($idArr as $key => $val){
				//获取使用记录
				$obj = $this->find(array('id' => $val),null,'personId');

				//删除记录
				$this->deletes ( $val );

				//获取当前测试卡累计金额
				$countArr = $this->getCount_d($obj['personId']);

				//更新测试卡累计金额
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

	/********************* 业务逻辑处理 ***************************/
	/**
	 * 获取当前卡号累计的金额
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