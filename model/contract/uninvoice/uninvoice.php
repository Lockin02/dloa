<?php
/**
 * @author Show
 * @Date 2012年11月28日 星期三 14:46:41
 * @version 1.0
 * @description:合同不开票金额 Model层
 */
class model_contract_uninvoice_uninvoice extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_uninvoice";
		$this->sql_map = "contract/uninvoice/uninvoiceSql.php";
		parent :: __construct();
	}

	/********************新策略部分使用************************/

	private $relatedStrategyArr = array (//不同类型入库申请策略类,根据需要在这里进行追加
		'KPRK-09' => 'model_contract_uninvoice_strategy_uother', //其他合同
		'KPRK-12' => 'model_contract_uninvoice_strategy_ucontract' //鼎利合同
	);

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/**
	 * 获取数据信息
	 */
	public function getObjInfo_d($obj,iuninvoice $strategy){
		//获取内容
		$rs = $strategy->getObjInfo_d($obj);
		//渲染内容
		return $rs;
	}

    /**
     * 获取数据信息
     * @param $obj
     * @param $allUninvoiceMoney
     * @param iuninvoice $strategy
     * @param string $remarks
     * @return mixed
     */
	public function updateUninvoiceMoney_d($obj,$allUninvoiceMoney,iuninvoice $strategy,$remarks = '',$extRecord = array()){
		//获取内容
        if(!empty($extRecord)){
            $rs = $strategy->updateUninvoiceMoney_d($obj,$allUninvoiceMoney,$remarks,$extRecord);
        }else{
            $rs = $strategy->updateUninvoiceMoney_d($obj,$allUninvoiceMoney,$remarks);
        }

		//渲染内容
		return $rs;
	}

	/*******************业务数据处理*****************************/

    /**
     * 重写add_d
     * @param $object
     * @return bool
     */
	function add_d($object){
		//邮件信息获取
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		// 附加操作记录字段
        $extRecord = array();
		if(isset($object['extRecord'])){
            $extRecord = $object['extRecord'];
            $extRecord['isSendMail'] = $emailArr['issend'];
            $extRecord['sendMailToNames'] = $emailArr['TO_NAME'];
            $extRecord['sendMailToIds'] = $emailArr['TO_ID'];
            $extRecord['objType'] = $object['objType'];
            $extRecord['objCode'] = $object['objCode'];
            $extRecord['isRed'] = $object['isRed'];
            $extRecord['costAmount'] = $object['money'];

            unset($object['extRecord']);
        }

		try{
			$this->start_d();

			//新增记录
			$newId = parent::add_d($object,true);

			//计算所有的不开票金额
			$allUninvoiceMoney = $this->getAllUninvoiceMoney_d($object['objId'],$object['objType']);

			//调用策略
			$newClass = $this->getClass($object['objType']);
			$initObj = new $newClass();
			//获取对应业务信息
			$rs = $this->updateUninvoiceMoney_d($object['objId'],$allUninvoiceMoney,$initObj,$object['descript'],$extRecord);

			//发送邮件 ,当操作为提交时才发送
			if(isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
			//插入合同确认数据
			$conFirmArr['type'] = '不开票';
			$conFirmArr['money'] = $object['money'];
			$conFirmArr['state'] = '未确认';
			$conFirmArr['contractId'] = $object['objId'];
			$conFirmArr['contractCode'] = $object['objCode'];
			$confirmDao = new model_contract_contract_confirm();
			$confirmDao->add_d($conFirmArr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//获取对象的不开票金额
	function getAllUninvoiceMoney_d($objId,$objType){
		$this->searchArr = array(
			'objId' => $objId,
			'objType' => $objType
		);
		$this->groupBy = 'c.objId';
		$rs = $this->list_d('count_all');
		if($rs){
			return $rs[0]['uninvoiceMoney'];
		}else{
			return 0;
		}
	}

	/**
	 * 邮件配置获取
	 */
	function getMailInfo_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}

	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr,$object,$thisAct = '新增'){
		$money = $object['isRed'] == 1 ? - $object['money'] : $object['money'];
    	$str = $_SESSION['USERNAME'] ." 对合同 " . $object['objCode']. "录入了不开票金额 ".$money . " 描述信息如下：<br/><br/>";
    	$str .= $object['descript'];

        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->mailClear('不开票信息',$emailArr['TO_ID'],$str);
	}
}
?>