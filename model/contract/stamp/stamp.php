<?php
/**
 * @author Show
 * @Date 2011��12��16�� ������ 11:24:28
 * @version 1.0
 * @description:���¼�¼(oa_sale_stamp) Model��
 */
class model_contract_stamp_stamp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_stamp";
		$this->sql_map = "contract/stamp/stampSql.php";
		parent::__construct ();
	}

	/********************** ���Բ��� **********************/

	//����������
	//��ͬ����������������,������Ҫ���������׷��
	private $relatedStrategyArr = array (
		'HTGZYD-01' => 'model_contract_stamp_strategy_outsourcing', //�����ͬ
		'HTGZYD-02' => 'model_contract_stamp_strategy_other', //������ͬ
		'HTGZYD-03' => 'model_contract_stamp_strategy_purcontract', //�ɹ�����
		'HTGZYD-04' => 'model_contract_stamp_strategy_contract', //������ͬ
        'HTGZYD-05' => 'model_contract_stamp_strategy_notcontract', //�Ǻ�ͬ��
        'HTGZYD-06' => 'model_contract_stamp_strategy_invoiceapply', //��Ʊ����
        'HTGZYD-07' => 'model_contract_stamp_strategy_rentcar' //�⳵��ͬ
	);

	/**
	 * �����������ͷ�����
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	//��Ӧҵ�����
	private $relatedCode = array (
		'HTGZYD-01' => 'outsourcing', //�����ͬ
		'HTGZYD-02' => 'other', //������ͬ
		'HTGZYD-03' => 'purcontract', //�ɹ�����
		'HTGZYD-04' => 'contract', //������ͬ
        'HTGZYD-05' => 'notcontract', //�Ǻ�ͬ��
        'HTGZYD-06' => 'invoiceapply', //��Ʊ����
        'HTGZYD-07' => 'rentcar' //�⳵��ͬ
	);

	/**
	 * �������ͷ���ҵ������
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	//��Ӧ�鿴ҳ���ַ
	private $relatedType = array (
			'HTGZYD-01' => 'contract_outsourcing_outsourcing', //�����ͬ
			'HTGZYD-02' => 'contract_other_other', //������ͬ
			'HTGZYD-03' => 'purchase_contract_purchasecontract', //�ɹ�����
			'HTGZYD-04' => 'contract_contract_contract', //������ͬ
			'HTGZYD-05' => 'contract_stamp_stampapply', //�Ǻ�ͬ��
			'HTGZYD-06' => 'finance_invoiceapply_invoiceapply', //��Ʊ����
			'HTGZYD-07' => 'outsourcing_contract_rentcar' //�⳵��ͬ
	);

	/**
	 * �����������ͷ���url
	 */
	public function getType($objType){
		return $this->relatedType[$objType];
	}

    /**
	 * ��ȡ����ҵ����Ϣ
	 */
	public function getObjInfo_d($obj,istamp $strategy){
		//��ȡ����
		$rs = $strategy->getObjInfo_i($obj);

		return $rs;
	}

    /**
	 * ��ȡ������Ϣ - ����ȷ��ʱ��Ⱦ����
	 */
	public function initStamp_d($obj,istamp $strategy){
		//��ȡ����
		$rs = $strategy->initStamp_i($obj);

		return $rs;
	}

	/**
	 * �ص�����ȷ�Ϲ���
	 */
	public function updateContract_d($obj,istamp $strategy){
		//��ȡ����
		$rs = $strategy->updateContract_i($obj);

		return $rs;
	}

    /**
     * �ص�����ȷ�Ϲ���
     */
    public function editStampType_d($obj,istamp $strategy){
        //��ȡ����
        $rs = $strategy->editStampType_i($obj);

        return $rs;
    }

    /**
     * �޸ĸ��������Լ�״̬
     */
    public function editStampTypeAndStatus_d($obj,istamp $strategy){
        //��ȡ����
        $rs = $strategy->editStampTypeAndStatus_i($obj);

        return $rs;
    }

    /**
     * ��ֵ����״̬
     */
    public function resetStampType_d($obj,istamp $strategy){
        //��ȡ����
        $rs = $strategy->resetStampType_i($obj);

        return $rs;
    }

	/**
	 * ����״̬
	 */
	public function rtStampType_d($status){
		if($status == 1){
			return '�Ѹ���';
		}elseif($status == 0){
			return 'δ����';
		}else{
			return '�ѹر�';
		}
	}

	/********************** ҵ��ӿ� ************************/

    /**
     * �༭��������
     */
    function editWithBusiness_d($object){
        try{
            $this->start_d();//������
            $this->edit_d($object);

            //��ȡ���ڵ�ȫ���������ͣ�����ϳ��ַ���
            $allStampType = $this->get_table_fields($this->tbl_name," contractType = '".$object['contractType']."' and contractId = ".$object['contractId'] . " and batchNo = ".$object['batchNo'] , "group_concat(stampType)" );
			$object['stampType'] = $allStampType;

            //���¸�����Ϣ
            $newClass = $this->getClass($object['contractType']);
            $initObj = new $newClass();
            $rs = $this->editStampType_d($object,$initObj);

            $this->commit_d();//�ύ����
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }

    }

	/**
	 * ����ȷ�ϲ���
	 */
	function confirmStamp_d($object){
		try{

			$this->start_d();//������

			//����ȷ��
			$object['stampUserId'] = $_SESSION['USER_ID'];
			$object['stampUserName'] = $_SESSION['USERNAME'];
			$object['stampDate'] = day_date;
			$object['status'] = 1;
			$this->edit_d($object);

            // PMS 557 ������һ��������롾Ͷ���걨��,ֻ����OA�ӿڻ�����,����Ҫ��������Դ������
            if($object['contractType'] != 'HTGZYD-TB'){
                $rs = $this->find(array('contractId' => $object['contractId'],'contractType'=>$object['contractType'],'status' => 0),null,'id');
                if(!is_array($rs)){
                    //���¸�����Ϣ
                    $newClass = $this->getClass($object['contractType']);
                    $initObj = new $newClass();
                    $rs = $this->updateContract_d($object,$initObj);
                }

                //��������
                if(!empty($object['applyId'])){
                    $appRs = $this->find(array('applyId' => $object['applyId'],'status' => 0),null,'id');
                    if(!is_array($appRs)){
                        //���º�ͬ������Ϣ
                        $newClass = $this->getClass($object['contractType']);
                        $initObj = new $newClass();
                        $rs = $this->updateContract_d($object,$initObj);
                    }
                }
            }

			//ȷ�ϸ�������
			if(!empty($object['applyId'])){
				$obj['id'] = $object['applyId'];
				$obj['stampUserId'] = $_SESSION['USER_ID'];
				$obj['stampUserName'] = $_SESSION['USERNAME'];
				$obj['stampDate'] = day_date;
				$obj['status'] = 1;
				//ҵ�񾭰�����Ϣ
				$obj['attnId'] = $_SESSION['USER_ID'];
				$obj['attn'] = $_SESSION['USERNAME'];
				$obj['attnDeptId'] = $_SESSION['DEPT_ID'];
				$obj['attnDept'] = $_SESSION['DEPT_NAME'];
				$stampapplyDao = new model_contract_stamp_stampapply();
				$stampapplyDao->edit_d($obj);			
			}
			$this->commit_d();//�ύ����
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ȷ�ϸ��²���
	 */
	 function confirmedSealed_d( $id ){
	 	try{
			$this->start_d();//������

			//����ȷ��
			$updateArr = array(
				'id' => $id,
				'status' => 1,
				'stampUserId' => $_SESSION['USER_ID'],
				'stampUserName' => $_SESSION['USERNAME'],
				'stampDate' => day_date
			);
			$this->edit_d($updateArr);

			//��ȡ�����µ�����
			$obj = $this->find(array('id' => $id),null,'stampType,contractId,contractType,applyId');

			$rs = $this->find(array('contractId' => $obj['contractId'],'contractType'=>$obj['contractType'],'status' => 0),null,'id');
			if(!is_array($rs)){
				//���º�ͬ������Ϣ
				$newClass = $this->getClass($obj['contractType']);
				$initObj = new $newClass();
				$rs = $this->updateContract_d($obj,$initObj);
			}

			//��������
			if(!empty($obj['applyId'])){
				$appRs = $this->find(array('applyId' => $obj['applyId'],'status' => 0),null,'id');
				if(!is_array($appRs)){
					//���º�ͬ������Ϣ
					$newClass = $this->getClass($obj['contractType']);
					$initObj = new $newClass();
					$rs = $this->updateContract_d($obj,$initObj);
				}
			}

			$this->commit_d();//�ύ����
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	 }

	/**
	 * ��������
	 */
	function batchStamp_d($object){
		try{
			if(is_array($object)){
				foreach($object as $key => $val){
					$this->confirmedSealed_d($val);
				}
			}
			return true;
		}catch(Exception $e){
			return false;
		}
  	}

  	/**
  	 * ��ȡ��Ӧ�����������
  	 */
  	function getStampTypeList_d(){
		$stampConfigDao = new model_system_stamp_stampconfig();
		$rs = $stampConfigDao->getStampTypeList_d($_SESSION['USER_ID']);

		$newArr = array();
		if(is_array($rs)){
			foreach($rs as $key => $val){
				array_push($newArr,$val['name']);
			}
		}
		return $newArr;
  	}

  	/**
  	 * ���������
  	 */
  	function addStamps_d($object){
  		if(empty($object['attnId'])){
  			$object['attn'] = $object['applyUserName'];
  			$object['attnId'] = $object['applyUserId'];
  		}
  		if(strpos($object['stampType'],',') === FALSE){
			return $this->add_d($object);
  		}else{
			try{
				$this->start_d();

				$stampTypes = $object['stampType'];
				$stampArr = explode(',',$stampTypes);
				foreach($stampArr as $key => $val){
					if(!empty($val)){
						$object['stampType'] = $val;
						$this->add_d($object);
					}
				}

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				return true;
			}
  		}
  	}

  	/**
  	 * �ر����������еĸ���
  	 */
  	function closeWaiting_d($contractId,$contractType){
		try{
			$this->update(array('contractId' => $contractId,'contractType' => $contractType,'status' => 0),array('status' => 2));
			return true;
		}catch(Exception $e){
			return false;
		}
  	}

  	/**
  	 * �رո�������
  	 */
  	function close_d($id){
		$obj = $this->get_d($id);

		//���ú�ͬ����
		$newClass = $this->getClass($obj['contractType']);
		$initObj = new $newClass();

		try{
			$this->start_d();

			//�رղ���
			parent::edit_d(array('id' => $id ,'status' => 2));

			//�����²�ѯsql
			$sql = "select GROUP_CONCAT(c.stampType) as stampType from oa_sale_stamp c where c.contractId = '".$obj['contractId']."' and c.contractType = '".$obj['contractType']."' and c.batchNo = ".$obj['batchNo']." and c.status <> 2 group by c.contractId";
			$rs = $this->_db->getArray($sql);

			//����ԭʼ����Ϣ
			$obj['orgStampType'] = $obj['stampType'];

			if(!empty($rs)){
				//��ѯ�����µ�״̬
				$sql = "select c.id from oa_sale_stamp c where c.contractId = '".$obj['contractId']."' and c.contractType = '".$obj['contractType']."' and c.batchNo = ".$obj['batchNo']." and c.status = 0";
				$rs2 = $this->_db->getArray($sql);

				if(!empty($rs2)){
//					print_r($obj);
					$obj['stampType'] = $rs[0]['stampType'];
					$this->editStampTypeAndStatus_d($obj,$initObj);
				}else{
					//���¸�����Ϣ
					$this->editStampType_d($obj,$initObj);
				}

			}else{
				//���¸�����Ϣ
				$this->resetStampType_d($obj,$initObj);
			}

			$this->commit_d();

			$this->closeMail_d($obj);

			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
  	}

  	/**
  	 * ���¹ر��ʼ����͹���
  	 */
  	function closeMail_d($object){
  		//��֯�ʼ�����
    	$datadictDao = new model_system_datadict_datadict();
    	$contractType = $datadictDao->getDataNameByCode($object['contractType']);
        $str = $_SESSION['USERNAME'].'�Ѿ��ر��� '.$contractType .' : ' .$object['contractCode'] . ' ��[ ' . $object['orgStampType']. ' ] ��������';

		//��ȡ�ʼ�������Ϣ
//		include (WEB_TOR."model/common/mailConfig.php");
//		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : '';
		//��ѯ���¶�Ӧ������
		$stampConfigDao = new model_system_stamp_stampconfig();
		$stampCongigArr = $stampConfigDao->find(array('stampName' => $object['orgStampType']),null,'principalId');

		if(empty($stampCongigArr)){
			return false;
		}

        $emailDao = new model_common_mail();
		$emailInfo = $emailDao->mailClear('�رո�������',$stampCongigArr['principalId'],$str);

  	}
  	/**
  	 *
  	 * ����Ƿ���ڸ��£�����δȷ�ϸ���
  	 * @param $obj
  	 */
  	function checkStamp($obj){
  		$sql="status !='1' and contractId='{$obj['contractId']}' and contractType='{$obj['contractType']}'";
  		if(!$this->findCount($sql)) {   //����������򵽸����������
  			$stampapplyModel=new model_contract_stamp_stampapply();
  			$sql="ExaStatus !='���' and contractId='{$obj['contractId']}' and contractType='{$obj['contractType']}'";
  			return $stampapplyModel->findCount($sql);
  		}else{
  			return 1;
  		}
  	}
}