<?php
/**
 * @author Show
 * @Date 2012��11��28�� ������ 14:46:41
 * @version 1.0
 * @description:��ͬ����Ʊ��� Model��
 */
class model_contract_uninvoice_uninvoice extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_uninvoice";
		$this->sql_map = "contract/uninvoice/uninvoiceSql.php";
		parent :: __construct();
	}

	/********************�²��Բ���ʹ��************************/

	private $relatedStrategyArr = array (//��ͬ����������������,������Ҫ���������׷��
		'KPRK-09' => 'model_contract_uninvoice_strategy_uother', //������ͬ
		'KPRK-12' => 'model_contract_uninvoice_strategy_ucontract' //������ͬ
	);

	/**
	 * �����������ͷ�����
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/**
	 * ��ȡ������Ϣ
	 */
	public function getObjInfo_d($obj,iuninvoice $strategy){
		//��ȡ����
		$rs = $strategy->getObjInfo_d($obj);
		//��Ⱦ����
		return $rs;
	}

    /**
     * ��ȡ������Ϣ
     * @param $obj
     * @param $allUninvoiceMoney
     * @param iuninvoice $strategy
     * @param string $remarks
     * @return mixed
     */
	public function updateUninvoiceMoney_d($obj,$allUninvoiceMoney,iuninvoice $strategy,$remarks = '',$extRecord = array()){
		//��ȡ����
        if(!empty($extRecord)){
            $rs = $strategy->updateUninvoiceMoney_d($obj,$allUninvoiceMoney,$remarks,$extRecord);
        }else{
            $rs = $strategy->updateUninvoiceMoney_d($obj,$allUninvoiceMoney,$remarks);
        }

		//��Ⱦ����
		return $rs;
	}

	/*******************ҵ�����ݴ���*****************************/

    /**
     * ��дadd_d
     * @param $object
     * @return bool
     */
	function add_d($object){
		//�ʼ���Ϣ��ȡ
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		// ���Ӳ�����¼�ֶ�
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

			//������¼
			$newId = parent::add_d($object,true);

			//�������еĲ���Ʊ���
			$allUninvoiceMoney = $this->getAllUninvoiceMoney_d($object['objId'],$object['objType']);

			//���ò���
			$newClass = $this->getClass($object['objType']);
			$initObj = new $newClass();
			//��ȡ��Ӧҵ����Ϣ
			$rs = $this->updateUninvoiceMoney_d($object['objId'],$allUninvoiceMoney,$initObj,$object['descript'],$extRecord);

			//�����ʼ� ,������Ϊ�ύʱ�ŷ���
			if(isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
			//�����ͬȷ������
			$conFirmArr['type'] = '����Ʊ';
			$conFirmArr['money'] = $object['money'];
			$conFirmArr['state'] = 'δȷ��';
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

	//��ȡ����Ĳ���Ʊ���
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
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}

	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object,$thisAct = '����'){
		$money = $object['isRed'] == 1 ? - $object['money'] : $object['money'];
    	$str = $_SESSION['USERNAME'] ." �Ժ�ͬ " . $object['objCode']. "¼���˲���Ʊ��� ".$money . " ������Ϣ���£�<br/><br/>";
    	$str .= $object['descript'];

        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->mailClear('����Ʊ��Ϣ',$emailArr['TO_ID'],$str);
	}
}
?>