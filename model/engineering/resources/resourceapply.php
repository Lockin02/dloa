<?php

/**
 * @author Show
 * @Date 2012��11��7�� ������ 19:23:17
 * @version 1.0
 * @description:��Ŀ�豸����� Model��
 */
class model_engineering_resources_resourceapply extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_resource_apply";
		$this->sql_map = "engineering/resources/resourceapplySql.php";
		parent:: __construct();
	}

	/**
	 * �����ֵ䴦��
	 */
	public $datadictFieldArr = array(
		'applyType', 'getType'
	);

	/**
	 * ����״̬
	 */
	function rtStatus_d($thisVal) {
		switch ($thisVal) {
			case '0' :
				return 'δ�´�';
				break;
			case '1' :
				return '�����´�';
				break;
			case '2' :
				return '���´�';
				break;
			default :
				return $thisVal;
		}
	}

	/**
	 * ȷ��״̬
	 */
	function rtConfirmStatus_d($thisVal) {
		switch ($thisVal) {
			case '0' :
				return '����';
				break;
			case '1' :
				return '���ż��';
				break;
			case '2' :
				return '������';
				break;
			case '6' :
				return '���';
				break;
			case '3' :
				return '�ȴ�����';
				break;
			case '4' :
				return '������';
				break;
			case '7' :
				return '���ش�ȷ��';
				break;
			case '5' :
				return '���';
				break;
			default :
				return $thisVal;
		}
	}

	/*********************  ��ɾ�Ĳ� ********************/
	/**
	 * ��Ŀҳ�����������뵥
	 */
	function addInProject_d($object) {
		try {
			//�����ֵ�
			$object = $this->processDatadict($object);
			//�������뵥��
			$codeRuleDao = new model_common_codeRule();
			$formNo = $codeRuleDao->resourceapplyCode($this->tbl_name);
			$object['formNo'] = $formNo;

			//������Ϣ״̬�Զ�����
			$object['ExaStatus'] = AUDITED;
			$object['ExaDT'] = day_date;
			$object['status'] = 0;

			$newId = parent::add_d($object, true);

			return array('applyId' => $newId, 'applyNo' => $formNo);
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * add
	 */
	function add_d($object) {
		//��ȡ����
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		try {
			$this->start_d();
			//�����ֵ�
			$object = $this->processDatadict($object);
			//�������뵥��
			$codeRuleDao = new model_common_codeRule();
			$object['formNo'] = $codeRuleDao->resourceapplyCode($this->tbl_name);
			$object['status'] = 0;
			$object['ExaStatus'] = WAITAUDIT;

			$newId = parent::add_d($object, true);

			//�ӱ���Ϣ
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $newId), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			if($object['audit'] == '1'){
				//��¼������־
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($newId, '�ύ����');
				//�ʼ�֪ͨ
				$this->sendEmail_d($object, $newId);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * edit
	 */
	function edit_d($object) {
		//��ȡ����
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		$id = $object['id'];
		try {
			$this->start_d();

			//�����ֵ�
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//�ӱ���Ϣ
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $id), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			if($object['audit'] == '1'){//�ύʱִ��
				//��¼������־
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '�ύ����');
				//�ʼ�֪ͨ
				$this->sendEmail_d($object, $id);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ȷ������
	 */
	function editConfirm_d($object) {
		//��ȡ����
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		$id = $object['id'];
		$obj = $this->get_d($id); // ��ȡ������Ϣ
		if ($obj['confirmStatus'] == 2) return true; // ��̨У���Ѵ���

		try {
			$this->start_d();

			if ($object['audit'] == '1') {//�ύʱִ��
				$object['confirmId'] = $_SESSION['USER_ID'];
				$object['confirmName'] = $_SESSION['USERNAME'];
				$object['confirmTime'] = date('Y-m-d H:i:s');
				//�����ѹ�ѡ������ϸ��ȷ��״̬Ϊ1
				foreach ($resourceapplydet as $key => $val){
					if(!isset($val['isDelTag'])){
						if(!isset($val['isChecked'])){
							unset($resourceapplydet[$key]);
						}else{
							$resourceapplydet[$key]['status'] = 1;
						}
					}
				}
			}

			//�ӱ���Ϣ
			$detailDao = new model_engineering_resources_resourceapplydet();
			$resourceapplydet = util_arrayUtil::setArrayFn(array('mainId' => $id), $resourceapplydet);
			$detailDao->saveDelBatch($resourceapplydet);

			//������Ϣ
			$unConfirmNum = $detailDao->findCount(array('mainId' => $id,'status' => 0));//��ȡδȷ�ϵ���ϸ��¼��
			if($unConfirmNum == 0){
				$object['confirmStatus'] = 2;//������
			}else{
				$object['confirmStatus'] = 1;//���ż��
			}
			$object = $this->processDatadict($object);//�����ֵ�
			parent::edit_d($object, true);

			if ($object['audit'] == '1') {//�ύʱִ��
				//��¼������־
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '�豸ȷ��');
				if ($object['confirmStatus'] == '2'){
					$this->mailDeal_d('resourceApplyConfirm', $object['applyUserId'],
						array(
							'id'           => $id,
							'confirmUser'  => $_SESSION['USERNAME'],
							'formNo'       => $obj['formNo'],
							'changeReason' => $object['changeReason']
						)
					);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ȷ������
	 */
	function confirmDetail_d($object) {
		//��ȡ����
		$resourceapplydet = $object['resourceapplydet'];
		unset($object['resourceapplydet']);
		try {
			$this->start_d();

			//�ӱ���Ϣ
			$detailDao = new model_engineering_resources_resourceapplydet();
			$detailDao->saveDelBatch($resourceapplydet);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���
	 */
	function applyBack_d($id) {
		try {
			$this->start_d();
	
			//��ϸ��������ȷ�ϵ��豸״̬��Ϊ��ȷ��
			$detailDao = new model_engineering_resources_resourceapplydet();
			$detailDao->update(array('mainId' => $id,'status'=>1), array('status' => 0));
			//����״̬��Ϊ���
			$this->update(array('id' => $id), array('confirmStatus' => 6));
			//��¼������־
			$logDao = new model_engineering_baseinfo_resourceapplylog();
			$logDao->addLog_d($id, '���');
	
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}
	
	/**
	 * ����ȷ��״̬
	 */
	function confirmStatus_d($id, $confirmStatus) {
		$object = array(
			'id' => $id,
			'confirmStatus' => $confirmStatus
		);
		if ($confirmStatus == '2') {
			$object['confirmId'] = $_SESSION['USER_ID'];
			$object['confirmName'] = $_SESSION['USERNAME'];
			$object['confirmTime'] = date('Y-m-d H:i:s');
		}
		return parent::edit_d($object, true);
	}

	/**
	 * �Զ������´�״̬
	 */
	function updateSatusAuto_d($id, $applyDetailDao = null) {
		if (empty($applyDetailDao)) {
			$applyDetailDao = new model_engineering_resources_resourceapplydet();
		}
		//��ȡ�豸��Ϣ
		$datas = $applyDetailDao->findAll(array('mainId' => $id), null);
		$dealNum = 0;//��ִ������
		$applyNum = 0;//��������
		foreach ($datas as $v) {
			$dealNum += $v['exeNumber'];
			$applyNum += $v['number'];
		}
		if ($dealNum == 0) {
			$status = 0;//δ����
		} elseif ($dealNum != $applyNum) {
			$status = 1;//������
		} else {
			$status = 2;//�Ѵ���
		}
		return parent::edit_d(array('id' => $id, 'status' => $status), true);
	}

	/**
	 * ���µ���״̬
	 */
	function updateConfirmStatus_d($id) {
		//��ȡ��������
		$sql = "SELECT SUM(number-backNumber) AS number FROM oa_esm_resource_applydetail WHERE mainId = ".$id;
		$rs = $this->findSql($sql);
		$applyNum = $rs[0]['number'];//��������
		//��ȡ��������
		$sql = "SELECT SUM(backNumber) as backNumber FROM oa_esm_resource_applydetail WHERE status = '2' AND mainId = ".$id;
		$rs = $this->findSql($sql);
		$backNum = $rs[0]['backNumber'];//��������
		//��ȡ�ѳ�����ϸ����
		$sql = "SELECT SUM(exeNumber) AS exeNumber FROM oa_esm_resource_taskdetail WHERE
					taskId IN ( SELECT id FROM oa_esm_resource_task WHERE applyId = ".$id.")";
		$rs = $this->findSql($sql);
		$dealNum = $rs[0]['exeNumber'];//�ѳ�������
		if($backNum != 0){
			$confirmStatus = 7;//���ش�ȷ��
		} elseif ($dealNum == 0) {
			$confirmStatus = 3;//�ȴ�����
		} elseif ($dealNum != $applyNum) {
			$confirmStatus = 4;//������
		} else {
			$confirmStatus = 5;//���
		}
		return parent::edit_d(array('id' => $id, 'confirmStatus' => $confirmStatus), true);
	}
	/********************** �±���ȷ�ϲ��� - ������ɺ�ҵ���� **********************/
	/**
	 * ������ɺ�ҵ����
	 */
	function dealAfterAudit_d($spid) {
		//��ȡ��������Ϣ
		$otherdatas = new model_common_otherdatas ();
		$flowInfo = $otherdatas->getStepInfo($spid);
		$id = $flowInfo['objId'];

		//��¼������־
		$logDao = new model_engineering_baseinfo_resourceapplylog();
		$logDao->addLog_d($id, '����');

		return true;
	}

	//�����ʼ�
	function  sendEmail_d($object, $newId) {
		//�ʼ���Ϣ��ȡ
		if (isset($object['email'])) {
			$emailArr = $object['email'];
			unset($object['email']);
		}
		$obj = $this->find(array('id' => $newId));

		//�����ʼ� ,������Ϊ�ύʱ�ŷ���
		if (isset($emailArr)) {
			if (!empty($emailArr['TO_ID']) && $emailArr['issend'] == 'y') {
				$this->mailDeal_d('resourceapply', $emailArr['TO_ID'], array('id' => $newId, 'applyUser' => $obj['applyUser'],
					'formNo' => $obj['formNo']
				));
			}
		}
	}

	/**
	 * �����ʼ���Ĭ�Ͻ�����
	 */
	function  sendDefaultEmail_d($newId) {
		$obj = $this->find(array('id' => $newId));
		$mailDao = new model_system_mailconfig_mailconfig();
		$mailArr = $mailDao->find(array('objCode' => 'resourceapply'));
		if ($mailArr['defaultUserId']) {
			$this->mailDeal_d('resourceapply', $mailArr['defaultUserId'], array('id' => $newId, 'applyUser' => $obj['applyUser'],
				'formNo' => $obj['formNo']));
		}
	}

	/**
	 * ȷ�Ϸ��������������
	 */
	function confirmTaskNum_d($object) {
		try {
			$this->start_d();

			$id = $object['id'];
			//��ϸ����
			$detail = $object['detail'];
			if (is_array($detail)) {
				//ͳһʵ����
				$taskDao = new model_engineering_resources_task();//��������
				$taskdetailDao = new model_engineering_resources_taskdetail();//����������ϸ
				$taskIdArr = array();//��ʼ������id����
				foreach ($detail as $val) {
					//������������ϸ
					$awaitNumber = $val['awaitNumber'];
					if ($awaitNumber == 0) {//�����ȷ�Ϸ�������Ϊ0����ɾ����������ϸ
						$taskdetailDao->deletes($val['id']);
					} else {//�������������ϸ������������ȷ�Ϸ�������
						$taskdetailDao->update(array('id' => $val['id']), array('number' => $val['awaitNumber'], 'awaitNumber' => 'NULL'));
					}
					//����������
					$taskDao->update(array('id' => $val['taskId']), array('status' => 0));//���·������񵥾�״̬Ϊδ����
					//����������ϸ���´�����
					$num = $val['number'] - $awaitNumber;//ʵ���ϼ��ٵ��´�����
					$sql = "UPDATE oa_esm_resource_applydetail SET exeNumber = exeNumber - {$num} WHERE id = '{$val['applyDetailId']}';";
					$this->_db->query($sql);
					//��������id����
					array_push($taskIdArr, $val['taskId']);
				}
				//�������뵥�´�״̬
				$this->updateSatusAuto_d($id);
				//ת��������id�ַ���
				$taskIdStr = implode(",", $taskIdArr);
				//��¼������־
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id, '�����޸�ȷ��');
				//�ʼ�֪ͨ
				if ($object['mailInfo']['issend'] == 'y') {
					$this->mailDeal_d('esmResourcesTaskConfirmNum', $object['mailInfo']['TO_ID'], array('id' => $taskIdStr));
				}
			} else {
				throw new Exception ("������Ϣ������!");
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���������ڽ���豸����
	 */
	function getBorrowDeviceNum($userId) {
		$sql = "
			SELECT
				IFNULL(SUM(i.amount - i.return_num),0) as borrowDeviceNum
			FROM
				device_borrow_order_info i
			LEFT JOIN device_borrow_order o ON o.id = i.orderid
			WHERE
				i.amount > i.return_num
			AND o.userid = '".$userId."'";
		return $this->findSql($sql);
	}
	
	/**
	 * ȷ�ϳ����豸
	 */
	function confirmBack_d($object) {
		try {
			$this->start_d();
	
			$id = $object['id'];
			//��ϸ����
			$detail = $object['detail'];
			unset($object['detail']);
			parent::edit_d(array('id' => $id, 'backReason' => $object['backReason']), true);//���ӳ���ԭ��
			if (is_array($detail)) {
				$resourceapplydetDao = new model_engineering_resources_resourceapplydet();//������ϸ
				foreach ($detail as $v) {
					if(isset($v['isDelTag'])){
						$exeNumber = $v['exeNumber'] - $v['backNumber'];
						$resourceapplydetDao->update(array('id'=>$v['id']), array('exeNumber'=>$exeNumber,'backNumber'=> 0,'status'=>1));
					}else{
						$resourceapplydetDao->update(array('id'=>$v['id']), array('status'=>3));
					}
				}
				//�������뵥�´�״̬
				$this->updateSatusAuto_d($id);
				//���µ���״̬
				$this->updateConfirmStatus_d($id);
				//��¼������־
				$logDao = new model_engineering_baseinfo_resourceapplylog();
				$logDao->addLog_d($id,'����ȷ��');
			} else {
				throw new Exception ("������Ϣ������!");
			}
	
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}