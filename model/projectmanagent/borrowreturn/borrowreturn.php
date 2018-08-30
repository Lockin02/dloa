<?php

/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:�����ù黹���� Model��
 */
class model_projectmanagent_borrowreturn_borrowreturn extends model_base
{
	function __construct() {
		$this->tbl_name = "oa_borrow_return";
		$this->sql_map = "projectmanagent/borrowreturn/borrowreturnSql.php";
		parent:: __construct();
	}

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

	//�����ֵ��ֶδ���
	public $datadictFieldArr = array(
		'applyType'
	);

	/**
	 * ����״̬
	 * @param $v
	 * @return string
	 */
	function rtDisposeState_d($v) {
		switch ($v) {
			case '0' :
				return '������';
				break; //��ʼ״̬
			case '1' :
				return '�ʼ���';
				break; //�ʼ���߲����´�
			case '2' :
				return '�Ѵ���';
				break; //ȫ���´�
			case '3' :
				return '�ʼ����';
				break; //ȫ���´�
			case '8' :
				return '���';
				break; //���
			case '9' :
				return '����ȷ��';
				break; //����ȷ��
			default :
				return '--';
		}
	}

	/**
	 * �⳥״̬
	 * @param $v
	 * @return string
	 */
	function rtState_d($v) {
		switch ($v) {
			case '0' :
				return '����';
				break;
			case '1' :
				return '�������⳥��';
				break;
			case '2' :
				return '�������⳥��';
				break;
			default :
				return '--';
		}
	}

	/**
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();

			//��������
			$codeRuleDao = new model_common_codeRule();
			$object['Code'] = $codeRuleDao->commonCode('�����ù黹����', $this->tbl_name, 'JYGH');

			//����������Ϣ
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			//���Ϊ��̨����Ĺ黹��������������ȷ�ϣ��������̲���
// 			if ($object['salesId'] != $_SESSION['USER_ID']) {
// 				$object['disposeState'] = 9;
// 			}
			$newId = parent:: add_d($object, true);
			//����ӱ���Ϣ
			if (!empty ($object['product'])) {
				$borrowEquDao = new model_projectmanagent_borrow_borrowequ();
				foreach ($object['product'] as $k => $v) {
					if ($v['productId'] == -1) {
						continue;
					}
					$equId = $v['id'];
					unset ($object['product'][$k]['id']);
					$object['product'][$k]['equId'] = $equId;

					//���¹黹��������
					$borrowEquDao->updateApplyBackNum($equId, $v['number']);
				}
				$orderequDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
				$orderequDao->createBatch($object['product'], array(
					'returnId' => $newId,
					'borrowId' => $object['borrowId']
				), 'productName');
			}

			//���Ϊ��̨����Ĺ黹�������ʼ�֪ͨ���۽���ȷ�ϣ������͸���̨������Ա
// 			if ($object['salesId'] != $_SESSION['USER_ID']) {
// 				$this->mailDeal_d('borrowreturnAddByManage', $object['salesId'], array('id' => $newId, $_SESSION['USER_ID']));
// 			}else{//����ֱ�ӷ��ʼ�֪ͨ�黹ҵ����Ա���к�����������������ͨ���ʼ�����
// 				$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $newId));
// 			}

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			//�޸�������Ϣ
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			//���Ϊ��̨����Ĺ黹��������������ȷ�ϣ��������̲���
// 			if ($object['salesId'] != $object['createId']) {
// 				$object['disposeState'] = 9;
// 			}
			parent:: edit_d($object, true);

			//��Ʒ
			$productDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
			$productDao->saveDelBatch($object['product']);

			//���¼����豸����
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//ʵ��������������
			$borrowEquArr = $borrowEquDao->getDetail_d($object['borrowId']);
			foreach ($borrowEquArr as $v) {
				if ($v['productId'] == -1) {
					continue;
				}
				//����ͳ������
				$num = $productDao->getNumByEquId_d($v['id']);
				//���¹黹��������
				$borrowEquDao->updateApplyBackNumEqu($v['id'], $num);
			}

			//���Ϊ��̨����Ĺ黹�������ʼ�֪ͨ���۽���ȷ�ϣ������͸���̨������Ա
// 			if ($object['salesId'] != $object['createId']) {
// 				$this->mailDeal_d('borrowreturnAddByManage', $object['salesId'], array('id' => $object['id'], $object['createId']));
// 			}else{//����ֱ�ӷ��ʼ�֪ͨ�黹ҵ����Ա���к�����������������ͨ���ʼ�����
// 				$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $object['id']));
// 			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function editManage_d($object) {
		try {
			$this->start_d();
			//�޸�������Ϣ
			$object = $this->processDatadict($object);
			if ($object['applyType'] == "JYGHSQLX-02") {
				$object['state'] = 1;
			}
			parent:: edit_d($object, true);

			//��Ʒ
			$productDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
			$productDao->saveDelBatch($object['product']);

			//���¼����豸����
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//ʵ��������������
			$borrowEquArr = $borrowEquDao->getDetail_d($object['borrowId']);
			foreach ($borrowEquArr as $v) {
				if ($v['productId'] == -1) {
					continue;
				}
				//����ͳ������
				$num = $productDao->getNumByEquId_d($v['id']);
				//���¹黹��������
				$borrowEquDao->updateApplyBackNumEqu($v['id'], $num);
			}

			//�ʼ�֪ͨ
			if ($object['mailInfo']['issend'] == 'y') {
				$this->mailDeal_d('borrowreturnEditByManage', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * ��дɾ��
	 */
	function deletes($ids) {
		if (!$ids) return true;
		try {
			$this->start_d();

			$idArr = explode(',', $ids);
			$borrowEquDao = new model_projectmanagent_borrow_borrowequ();//ʵ��������������
			$borrowreturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();//ʵ���������ù黹����
			foreach ($idArr as $v) {
				//��ѯ�������к�
				$borrowreturnEquArr = $borrowreturnEquDao->getDetail_d($v);
				foreach ($borrowreturnEquArr as $va) {
					if ($v['productId'] == -1) {
						continue;
					}
					//���¹黹��������
					$borrowEquDao->updateApplyBackNum($va['equId'], -$va['number']);
				}
			}

			//����ɾ��
			parent::deletes($ids);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �б�ҳ�ύ
	 */
	function ajaxSub_d($id) {
		return $this->query("update oa_borrow_return set disposeState = '0' where id = '$id'");
	}

	/**
	 * ȷ�Ϸ���
	 */
	function confirmEdit_d($object) {
		try {
			$this->start_d();
			//�޸�������Ϣ
			parent:: edit_d($object, true);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݹ黹��id �жϲ����� ����״̬
	 * @param $id
	 * @return string
	 */
	function updateReturnState_d($id) {
		$obj = $this->find(array('id' => $id), null, 'applyType');//��ȡ��������
		$sql = "SELECT
                SUM(number) AS number,sum(disposeNumber) AS disposeNumber,
                SUM(qualityNum) AS qualityNum,SUM(qPassNum) AS qPassNum,SUM(qBackNum) AS qBackNum
            FROM
                oa_borrow_return_equ
            WHERE returnId = '$id' AND productId <> -1";
		$numArr = $this->_db->getArray($sql);
		if ($obj['applyType'] == 'JYGHSQLX-01') {//�豸�黹
			// ����ʼ�ͨ���������ʼ첻�ϸ����� ���� ����������˵���ʼ������
			if ($numArr[0]['qPassNum'] + $numArr[0]['qBackNum'] == $numArr[0]['number']) {
				// �������������´����������㴦����ɣ�����Ϊ�ʼ����
				if ($numArr[0]['number'] == $numArr[0]['disposeNumber']) {
					$disposeState = 2;
				} else {
					$disposeState = 3;
				}
			} else if ($numArr[0]['qualityNum'] == 0) { // ����ʼ���������Ϊ0����״̬Ϊ������
				$disposeState = 0;
			} else if ($numArr[0]['qualityNum'] != $numArr[0]['qPassNum'] + $numArr[0]['qBackNum']) {
				$disposeState = 1;
			}
		}else{//�豸��ʧ�������ʼ�
			// �������������´����������㴦����ɣ�����Ϊ������
			if ($numArr[0]['number'] == $numArr[0]['disposeNumber']) {
				$disposeState = 2;
			} else {
				$disposeState = 0;
			}
		}
		$this->update(array('id' => $id), array('disposeState' => $disposeState));
		return $disposeState;
	}

    /**
     * ��������Ƿ�Ϊ������
     * @param $id
     * @throws Exception
     */
    function isMainItem_d($id) {
        try {
            $sql = "select productId from oa_borrow_return_equ where id = '$id'";
            $productId = $this->_db->getArray($sql);
            return $productId[0]['productId'];
        } catch (Exception $e) {
            throw $e;
        }
    }

	/**
	 * ���´���״̬
	 * @param $id
	 * @param $disposeState
	 * @throws Exception
	 */
	function updateDisposeState_d($id, $disposeState) {
		try {
			return $this->_db->query("update oa_borrow_return set disposeState='$disposeState' where id = $id");
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *��ص���
	 */
	function disposeback_d($object) {
		try {
			//����״̬
			$object['disposeState'] = '8';
			parent:: edit_d($object, true);

			//�ʼ�֪ͨ
			if ($object['mailInfo']['issend'] == 'y') {
				$this->mailDeal_d('borrowreturnBackByManage', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
			}

			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �����⳥״̬
	 */
	function updateState_d($id, $state) {
		try {
			$this->_db->query("update oa_borrow_return set state='$state' where id = $id ");
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ���ݹ黹��id �жϲ����� ����״̬
	 */
	function updateStateAuto_d($id) {
		$obj = $this->find(array('id' => $id), null, 'applyType');//��ȡ��������
		$sql = "select
				sum(number) as number,sum(qPassNum + qBackNum) as qNumber,
				sum(qBackNum) as qBackNum,sum(compensateNum) as compensateNum
			from oa_borrow_return_equ where returnId = '$id'";
		$numArr = $this->_db->getArray($sql);

		//������豸�黹
		if ($obj['applyType'] == 'JYGHSQLX-01') {
			//���ϸ��� ���� �⳥�� && ����黹�� ���� �����ʼ���
			if ($numArr[0]['qBackNum'] == $numArr[0]['compensateNum'] && $numArr[0]['number'] == $numArr[0]['qNumber']) {
				$state = '2';
			}
		} else {//�豸��ʧ
			//����黹�� ���� �⳥��
			if ($numArr[0]['number'] == $numArr[0]['compensateNum']) {
				$state = '2';
			}
		}
		//�õ�״̬�Ÿ���
		if (isset($state)) {
			$this->update(array('id' => $id), array('state' => $state));
		}
	}

	/**
	 * ��֤�Ƿ�����ʱ���к�
	 */
	function checkHasTempSno_d($object) {
		if (!$object['product']) return false;

		//ʵ�������к���
		$serialnoDao = new model_stock_serialno_serialno();
		foreach ($object['product'] as $v) {
			if ($v['serialId']) {
				if ($serialnoDao->checkTemp_d($v['serialId'])) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * ����֮����
	 */
	function workflowCallBack($spid) {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo($spid);
		$objId = $folowInfo ['objId'];
		//��ѯ�������к�
		$borrowreturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
		$borrowreturnEquArr = $borrowreturnEquDao->getDetail_d($objId);

		//ʵ�������к���
		$serialnoDao = new model_stock_serialno_serialno();
		try {
			$this->start_d();
			//����ʱ���к�ת��
			foreach ($borrowreturnEquArr as $v) {
				if ($v['serialId']) {
					$serialnoDao->updateTempToFormal_d($v['serialId']);
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
		return true;
	}

	/**
	 * �ֿ���Ա����ȷ��
	 */
	function ajaxReceive_d($id) {
		$sql = "update " . $this->tbl_name . " set receiveStatus = 1,receiveId = '" .
			$_SESSION['USER_ID'] . "',receiveName = '" . $_SESSION['USERNAME'] . "',receiveTime = '" .
			date('Y-m-d H:i:s') . "' where id = " . $id;
		return $this->_db->query($sql);
	}

	/**
	 * �ʼ������غ��Ӧ��ҵ�����
	 */
	function updateBusinessByBack($id) {
		$proNumSql = "SELECT
		sum(op.qualityNum) AS qualityNum
		FROM
		oa_borrow_return_equ op
		WHERE
		op.returnId = $id";
		$proNum = $this->_db->getArray($proNumSql);
		if ($proNum[0]['qualityNum'] == '0') {
			$disposeState = '0';
		} else {
			$disposeState = '1';
		}
		if (isset($disposeState)) {
			return $this->update(array('id' => $id), array('disposeState' => $disposeState));
		} else {
			return true;
		}
	}

	/**
	 * ����ȷ��-ȷ��/��ص���
	 * @param $object
	 */
	function saleConfirm_d($object) {
		try {
			//����״̬
			$object['disposeState'] = '0';
			parent:: edit_d($object, true);
			//����ȷ�Ϻ󣬷����ʼ�֪ͨ�黹ҵ����Ա���к�������
			$this->mailDeal_d('borrowreturnConfirmedBySale', null, array('id' => $object['id']));

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}
}