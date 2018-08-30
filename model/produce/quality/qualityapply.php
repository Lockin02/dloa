<?php

/**
 * @author huangzf
 * @Date 2013��3��7�� ������ 10:47:28
 * @version 1.0
 * @description:�ʼ����뵥 Model��
 */
class model_produce_quality_qualityapply extends model_base
{
	public $applyStrategyArr = array();

	function __construct() {
		$this->tbl_name = "oa_produce_quality_apply";
		$this->sql_map = "produce/quality/qualityapplySql.php";
		//�������뵥����
		$this->applyStrategyArr = array(
			"ZJSQYDSL" => "model_produce_quality_strategy_purchqualityapply",//�ɹ�����֪ͨ
			"ZJSQYDHH" => "model_produce_quality_strategy_exchangequalityapply",//��������֪ͨ
			"ZJSQYDGH" => "model_produce_quality_strategy_returnqualityapply",//�黹����֪ͨ
			"ZJSQYDSC" => "model_produce_quality_strategy_producequalityapply",//��������֪ͨ
			"ZJSQYDTH" => "model_produce_quality_strategy_salereturnqualityapply",//t�˻�
            "ZJSQDLBF" => "model_produce_quality_strategy_blockeququalityapply"//���ϱ���֪ͨ��(PMS2386)
		);
		parent::__construct();
	}

	//��˾Ȩ�޴���
	protected $_isSetCompany = 1;

	/****************************** ���Բ��� *****************************/
	/**
	 * ����id��ȡ��Ӧ����
	 */
	function getStrategy_d($id) {
		$obj = $this->find(array('id' => $id), 'relDocType');
		return $this->applyStrategyArr[$obj['relDocType']];
	}

	//��ȡҵ������
	public function ctGetRelDocInfo($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->getRelDocInfo($relDocId);
	}

	//��ȡ�����Ӧ�ӱ�ҵ������
	public function ctGetRelDetailInfo($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->getRelDetailInfo($relDocId);
	}

	/**
	 * �����ʼ�����ʱԴ����ҵ����
	 * @param $istockin ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtAdd(iqualityapply $iqualityapply, $paramArr = false, $relItemArr = false) {
		return $iqualityapply->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * �����ʼ���ϸ - �ʼ����ʹ��
	 */
	function ctDealRelItemPass($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelItemPass($relDocId, $relDocItemId, $qualityNum);
	}

    /**
     * �𻵷���
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     * @param iqualityapply $iqualityapply
     */
    function ctDealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
        return $iqualityapply->dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum);
    }

	/**
	 * ����Դ��״̬ - �ʼ����ʱʹ��,��Ҫͨ���ж�������Ϣ����״̬��Ϣ
	 */
	function ctDealRelInfoCompleted($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelInfoCompleted($relDocId);
	}

	/**
	 * �ʼ����ʱ�������ҵ��
	 * �ʼ�ϸ�ʱ,�ʼ��������ڵ��μ������,����ֱ���õ��ʼ���������и���
	 */
	function dealRelInfoAtConfirm($id, $itemId, $thisCheckNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode']
		);
		return $istrategy->dealRelInfoAtConfirm($paramArr);
	}

	/**
	 * �ʼ��˻�ʱ�������ϵ�
	 */
	function dealRelInfoAtBack($id, $itemId, $thisCheckNum, $passNum, $receiveNum, $backNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode'],
			'passNum' => $passNum, 'receiveNum' => $receiveNum, 'backNum' => $backNum
		);
		return $istrategy->dealRelInfoAtBack($paramArr);
	}

	/**
	 * �ʼ��˻�ʱ�������ϵ�
	 */
	function dealRelInfoAtReceive($id, $itemId, $thisCheckNum, $passNum, $receiveNum, $backNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode'],
			'passNum' => $passNum, 'receiveNum' => $receiveNum, 'backNum' => $backNum
		);
		return $istrategy->dealRelInfoAtReceive($paramArr);
	}

	/**
	 * �ʼ쳷��
	 * �ʼ�ϸ�ʱ,�ʼ��������ڵ��μ������,����ֱ���õ��ʼ���������и���
	 */
	function dealRelInfoAtUnconfirm($id, $itemId, $thisCheckNum) {
		$applyObj = $this->get_d($id);
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->get_d($itemId);

		$strategeName = $this->applyStrategyArr[$applyObj['relDocType']];
		$istrategy = new $strategeName ();
		$paramArr = array("relDocId" => $applyObj['relDocId'], "relDocItemId" => $applyItemObj['relDocItemId'],
			"thisCheckNum" => $thisCheckNum, "docCode" => $applyObj['docCode']
		);
		return $istrategy->dealRelInfoAtUnconfirm($paramArr);
	}

	/**
	 * �����ʼ���ϸ - �ʼ�������ʱʹ��
	 */
	function ctDealRelItemBack($relDocId, $relDocItemId, $qualityNum, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelItemBack($relDocId, $relDocItemId, $qualityNum);
	}

	/**
	 * ����Դ��״̬ - �ʼ�������ʱʹ��,��Ҫͨ���ж�������Ϣ����״̬��Ϣ
	 */
	function ctDealRelInfoBack($relDocId, iqualityapply $iqualityapply) {
		return $iqualityapply->dealRelInfoBack($relDocId);
	}
	/****************************** ��Ŀ���� *****************************/

	/**
	 * ״̬����
	 */
	public $statusArr = array(
		'0' => '��ִ��',
		'1' => '����ִ��',
		'2' => 'ִ����',
		'3' => '�ѹر�'
	);

	//���ⲿ����תΪ�ڲ�����
	public function rtStatus($value) {
		if (isset($this->statusArr[$value])) {
			return $this->statusArr[$value];
		} else {
			return $value;
		}
	}

	//�ʼ���ȡ
	function getMail_d($thisKey) {
		include(WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
			'sendName' => '');
		return $mailArr;
	}

	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			if (is_array($object ['items'])) {
				$this->start_d();
				//�ʼ����뵥������Ϣ
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode("oa_produce_quality_apply", "ZJSQ");

				$id = parent::add_d($object, true);
				$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
				$itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $object ['items']);
				$itemsObjs = $qualityapplyitemDao->saveDelBatch($itemsArr);

				//��Դ����Ϣ�ģ����д������ҵ��
				if (!empty ($object ['relDocType']) && !empty ($object ['relDocId'])) {
					$relDocDaoName = $this->applyStrategyArr [$object ['relDocType']];
					$relArr = array("id" => $id, "relDocId" => $object ['relDocId'], "relDocCode" => $object ['relDocCode']);

					$this->ctDealRelInfoAtAdd(new $relDocDaoName (), $relArr, $itemsObjs);
				}

				$this->commit_d();
				return true;
			} else {
				throw new Exception ("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array($object ['items'])) {
				$this->start_d();
				$editResult = parent::edit_d($object, true);
				$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
				$itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $object ['items']);
				$qualityapplyitemDao->saveDelBatch($itemsArr);
				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception ("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d($id);
		$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
		$qualityapplyitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $qualityapplyitemDao->listBySqlId();
		return $object;
	}

	/**
	 * ����Դ������Ƿ�����ʼ�����
	 */
	function checkExsitQuality($relDocId, $relDocType) {
		$this->searchArr = array("relDocType" => $relDocType,
			"relDocId" => $relDocId);
		$row = $this->listBySqlId();
		if (is_array($row)) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * ���¼������뵥��״̬ -- ����������Ϣ����
	 * @param $id
	 * @return mixed
	 */
	function renewStatus_d($id) {
		//��ѯ�ʼ�����
		$qualityapplyitemDao = new model_produce_quality_qualityapplyitem();
		$qualityapplyitemArr = $qualityapplyitemDao->findAll(array('mainId' => $id), null, 'id,status');

		$doingArr = array(); //�ڽ��ļ�¼
		$waitingArr = array(); //�ȴ�����ļ�¼
		$doneArr = array(); //������ϵļ�¼
		//ѭ���ж�״̬
		foreach ($qualityapplyitemArr as $key => $val) {
			switch ($val['status']) {
				case "0" :
					array_push($doneArr, $val['id']);
					break;
				case "1" :
					array_push($doingArr, $val['id']);
					break;
				case "2" :
					array_push($doingArr, $val['id']);
					break;
				case "3" :
					array_push($doneArr, $val['id']);
					break;
				case "4" :
					array_push($waitingArr, $val['id']);
					break;
			}
		}

		$status = null;

		//����δ����
		if (count($waitingArr) == count($qualityapplyitemArr)) {
			$status = "0";
		} elseif (count($doneArr) == count($qualityapplyitemArr)) {//�����Ѵ���
			$status = "3";
		} elseif (count($waitingArr) > 0) {
			$status = "1";
		} else {
			$status = "2";
		}

		//��������
		$conditionArr = array("id" => $id);
		$updateArr = array("id" => $id, 'status' => $status);
		$updateArr = $this->addUpdateInfo($updateArr);

		//���������
		if ($status == "3") {
			$updateArr['closeUserName'] = $_SESSION['USERNAME'];
			$updateArr['closeUserId'] = $_SESSION['USER_ID'];
			$updateArr['closeTime'] = date("Y-m-d H:i:s");
		} else {
			$updateArr['closeUserName'] = '';
			$updateArr['closeUserId'] = '';
			$updateArr['closeTime'] = '';
		}

		return $this->update($conditionArr, $updateArr);
	}

	/**
	 * ��ѯ�ʼ�������Ϣ
	 */
	function findQuality_d($relDocItemId) {
		//ʵ������ϸ
		$applyItemDao = new model_produce_quality_qualityapplyitem();
		$applyItemObj = $applyItemDao->find(array("relDocItemId" => $relDocItemId));
		if ($applyItemObj['status'] != "3") {
			return array('thisType' => 'apply', 'mainId' => $applyItemObj['mainId']);
		} else {
			//�ʼ�����
			$qualitytaskitemDao = new model_produce_quality_qualitytaskitem();
			$qualitytaskitemObj = $qualitytaskitemDao->find(array("applyItemId" => $applyItemObj['id']));

			//�ʼ챨��
			$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
			$qualityereportequitemObj = $qualityereportequitemDao->find(array("relItemId" => $qualitytaskitemObj['id']));

			return array('thisType' => 'report', 'mainId' => $qualityereportequitemObj['mainId']);
		}
	}

	/**
	 * �ж��Ƿ���Գ���
	 */
	function checkCanBack_d($object) {
		$rs = true;
		//ѭ���ж� - ��Ը����б���ʹ��
		foreach ($object as $key => $val) {
			$strategeName = $this->applyStrategyArr[$key];
			if (!$strategeName) continue; //���û��Դ������Ĭ��Ϊ��
			$istrategy = new $strategeName ();

			if (!$istrategy->checkCanBack_d($val)) {
				$rs = false;
				break;
			}
		}
		return $rs;
	}

    /**
     * ��ȡ�𻵷��ε���ϸ
     */
    function getDamagePassItem($relDocId) {
        $this->searchArr = array('relDocId' => $relDocId, 'iStatus' => 5);
        return $this->listBySqlId('select_detail');
    }
}