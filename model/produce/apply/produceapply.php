<?php
/**
 * @author huangzf
 * @Date 2012��5��11�� ������ 13:40:44
 * @version 1.0
 * @description:�������뵥 Model��
 */
class model_produce_apply_produceapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_produceapply";
		$this->sql_map = "produce/apply/produceapplySql.php";
		//�������뵥����
		$this->applyStrategyArr = array(
			"CONTRACT" => array( //��ͬ��������
				"relDocTypeName" => "��ͬ",
				"model" => "model_produce_apply_strategy_contractapply"
			),
			"BORROW" => array( //��ʽ����������
				"relDocTypeName" => "��ʽ��",
				"model" => "model_produce_apply_strategy_borrowapply"
			),
			"PRESENT" => array( //������������
				"relDocTypeName" => "����",
				"model" => "model_produce_apply_strategy_presentapply"
			)
		);

		parent::__construct ();
	}

	/**
	 * ����Դ�����ͷ��ء���ͬ�����ߡ�Դ����
	 */
	function getShowRelDoc_d($relDocTypeCode) {
		$contractTypeArr = array('HTLX-XSHT', 'HTLX-FWHT', 'HTLX-ZLHT', 'HTLX-YFHT');
		if (in_array($relDocTypeCode, $contractTypeArr)) {
			return '��ͬ';
		} else {
			return 'Դ��';
		}
	}

	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			if (is_array($object["items"])) {
				$this->start_d ();

				//��ȡ������˾����
				$object['formBelong'] = $_SESSION['USER_COM'];
				$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
				$object['businessBelong'] = $_SESSION['USER_COM'];
				$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

				//�������뵥������Ϣ
				$codeDao = new model_common_codeRule ();
				$object ['docCode'] = $codeDao->stockCode("oa_produce_produceapply" ,"SCSQ");
				if ($object ['ExaStatus'] == "���") {
					$object ['ExaDT'] = date ( "Y-m-d" );
				}
				$id = parent::add_d($object ,true);

				if ($id) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					foreach($object["items"] as $key => $val) {
						if ($val['isDelTag'] != 1) {
							//�����������ID
							$val['relDocItemId'] = $val["id"];
							unset($val["id"]);

							//�����ƷID
							$val['goodsId'] = $val['conProductId'];
							unset($val['conProductId']);

							//�������ͺ�
							$val['pattern'] = $val['productModel'];
							unset($val['productModel']);

							//������������
							$val['needNum'] = $val['number'];
							unset($val['number']);

							//�������´�����
							$val['exeNum'] = $val['issuedProNum'];
							unset($val['issuedProNum']);

							//����license
							$val['licenseConfigId'] = $val['license'];
							unset($val['license']);

							$val['relDocId'] = $object['relDocId']; //Դ��ID
							$val['relDocCode'] = $object['relDocCode']; //Դ�����
							$val['weekly'] = $this->getWeekly_d($object['applyDate']); //��ȡ��ǰ�ܴ�
							$val['mainId'] = $id;
							$produceapplyitemDao->add_d($val ,true);

							//���·����嵥���´�����
							$equDao = new model_contract_contract_equ();
							$equDao->updateById(
								array(
									"id" => $val['relDocItemId'] ,
									'issuedProNum' => ($val['exeNum'] + $val['produceNum'])
								)
							);
						}
					}

					$this->applyMail_d($id);
				}

				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * ������������
	 */
	function addDepartment_d($obj) {
		try {
			$this->start_d ();

			//��ȡ������˾����
			$obj['formBelong'] = $_SESSION['USER_COM'];
			$obj['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$obj['businessBelong'] = $_SESSION['USER_COM'];
			$obj['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$codeDao = new model_common_codeRule ();
			$obj['docCode'] = $codeDao->stockCode("oa_produce_produceapply" ,"SCSQ"); //���ݱ��

			$datadictDao = new model_system_datadict_datadict();
			$obj['relDocType'] = $datadictDao->getDataNameByCode($obj['relDocTypeCode']); //Դ������

			$id = parent::add_d($obj ,true);

			if ($id) {
				if (is_array($obj["items"])) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					foreach($obj["items"] as $key => $val) {
						$val['relDocId'] = $obj['relDocId']; //Դ��ID
						$val['relDocCode'] = $obj['relDocCode']; //Դ�����
						$val['weekly'] = $this->getWeekly_d($obj['applyDate']); //��ȡ��ǰ�ܴ�
						$val['mainId'] = $id;
						$produceapplyitemDao->add_d($val ,true);
					}
				}
			}

			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array($object["items"])) {
				$this->start_d ();
				$editResult = parent::edit_d($object ,true);

				if ($editResult) {
					$produceapplyitemDao = new model_produce_apply_produceapplyitem();
					$equDao = new model_contract_contract_equ();
					foreach($object["items"] as $key => $val) {
						$produceapplyitemObj = $produceapplyitemDao->get_d($val["id"]);
						$equObj = $equDao->get_d($val['relDocItemId']);
						if ($val['isDelTag'] != 1) {
							$val['state'] = 0;
							$produceapplyitemDao->edit_d($val ,true);
							//���·����嵥���´�����
							$equDao->updateById(
								array(
									"id" => $val['relDocItemId'] ,
									'issuedProNum' => ($equObj['issuedProNum'] + $val['produceNum'])
								)
							);
						} else {
							$produceapplyitemDao->deleteByPk($val["id"]);
						}
					}

					$this->applyMail_d($object["id"]);
				}

				$this->commit_d ();
				return $object["id"];
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �������ű༭����
	 */
	function editDepartment_d($obj) {
		try {
			$this->start_d ();

			$datadictDao = new model_system_datadict_datadict();
			$obj['relDocType'] = $datadictDao->getDataNameByCode($obj['relDocTypeCode']); //Դ������

			$editResult = parent::edit_d($obj ,true);

			if ($editResult) {
				$produceapplyitemDao = new model_produce_apply_produceapplyitem();
				foreach($obj["items"] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['weekly'] = $this->getWeekly_d($obj['applyDate']); //��ȡ��ǰ�ܴ�
						if ($val["id"] > 0) {
							$val['state'] = 0;
							$produceapplyitemDao->edit_d($val ,true);
						} else {
							$val['relDocId'] = $obj['relDocId']; //Դ��ID
							$val['relDocCode'] = $obj['relDocCode']; //Դ�����
							$val['mainId'] = $obj["id"];
							$produceapplyitemDao->add_d($val ,true);
						}
					} else {
						$produceapplyitemDao->deleteByPk($val["id"]);
					}
				}
			}

			$this->commit_d ();
			return $obj["id"];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �������
	 */
	function change_d($object){
		try{
			$this->start_d();

			$changeLogDao = new model_common_changeLog('produceapply'); //ʵ���������

			//�ӱ���
			if (is_array($object['items'])) {
				foreach ($object['items'] as $key => $val) {
					if ($val['id']) {
						$object['items'][$key]['oldId'] = $val['id'];
					} else {
						$object['items'][$key]['relDocId'] = $object['relDocId'];
						$object['items'][$key]['relDocCode'] = $object['relDocCode'];
						$object['items'][$key]['weekly'] = $this->getWeekly_d($object['applyDate']); //��ȡ��ǰ�ܴ�
					}
				}
			}

			$tempObjId = $changeLogDao->addLog($object); //���������Ϣ

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �����������뵥
	 */
	function openApply($id) {
		$this->updateDocStatus ( $id );
		return true;
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$produceapplyitemDao = new model_produce_apply_produceapplyitem ();
		$produceapplyitemDao->sort="id";
		$produceapplyitemDao->searchArr ['mainId'] = $id;
		$object ["items"] = $produceapplyitemDao->listBySqlId ();
		return $object;
	}

	/**
	 * ��ȡ����Դ����Ϣ ���Կ���
	 * @param  $id
	 */
	function ctGetRelDocInfo($id, iproduceapply $iproduceapply) {
		return $iproduceapply->getRelDocInfo ( $id );
	}

	/**
	 * �´���������,�������������Ϣ ģ�帳ֵ  ���Կ���
	 * @param  $obj
	 */
	function ctAssignBaseAtApply($obj, iproduceapply $iproduceapply, show $show) {
		$show->assign ( "applyDate", date ( "Y-m-d" ) );
		$show->assign ( "applyUserCode", $_SESSION ['USER_ID'] );
		$show->assign ( "applyUserName", $_SESSION ['USERNAME'] );
		return $iproduceapply->assignBaseAtApply ( $obj, $show );
	}

	/**
	 * @description �´���������,�嵥��ʾģ����Կ���
	 * @param $rows
	 */
	function ctShowItemAtApply($rows, iproduceapply $iproduceapply) {
		return $iproduceapply->showItemAtApply ( $rows );
	}

	/**
	 * ������������ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($relArr, $relItemArr, iproduceapply $iproduceapply) {
		return $iproduceapply->dealRelInfoAtAdd ( $relArr, $relItemArr );
	}

	/**
	 * �޸���������ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($relArr, $relItemArr,$lastItemArr, iproduceapply $iproduceapply) {
		return $iproduceapply->dealRelInfoAtEdit ( $relArr, $relItemArr,$lastItemArr );
	}

	/**
	 * �������뵥�´�״̬
	 * @param  $id
	 */
	function updateDocStatus($id) {
		$sql = "select sum(produceNum) as produceNum,sum(exeNum) as exeNum  from oa_produce_produceapply_item  where mainId=$id ;";
		$result = $this->findSql ( $sql );
		if ($result [0] ['produceNum'] > $result [0] ['exeNum']) {
			if ($result [0] ['exeNum'] > 0) {
				$this->updateById ( array ("id" => $id, "docStatus" => "1" ) );
			} else {
				$this->updateById ( array ("id" => $id, "docStatus" => "0" ) );
			}
		} else {
			$this->updateById ( array ("id" => $id, "docStatus" => "2" ) );
		}
	}

	/**
	 * ���
	 */
	function back_d( $obj ) {
		try {
			$this->start_d ();

			$oldObj = $this->get_d($obj["id"]);
			$back['reason'] = $obj['backReason']; //��ǰ�Ĵ��ԭ��
			$back['name'] = $_SESSION['USERNAME']; //��ǰ�Ĵ����
			$obj['backReason'] = $_SESSION['USERNAME'].'&nbsp;&nbsp;'.date('Y-m-d H:i:s').'<br>'.$obj['backReason'];
			if ($oldObj['backReason']) {
				$obj['backReason'] = $oldObj['backReason'].'<breakpoint>'.$obj['backReason']; //ƴ�ӵ���һ�ε�ԭ��
			}

			$itemDao = new model_produce_apply_produceapplyitem();
			if (is_array($obj['items'])) {
				$equDao = new model_contract_contract_equ();
				foreach ($obj['items'] as $key => $val) {
					if ($val['state'] == '0') {
						$val['state'] = 2;
						$itemDao->updateById($val);
						$itemObj = $itemDao->get_d($val['id']);
						$equObj  = $equDao->get_d($itemObj['relDocItemId']);
						//���·����嵥���´�����
						$equDao->updateById(
							array(
								"id" => $itemObj['relDocItemId'] ,
								'issuedProNum' => ($equObj['issuedProNum'] - $itemObj['produceNum'])
							)
						);
					}
				}
			}

			$items = $itemDao->findAll(array('mainId' => $obj['id'] ,'state' => 0));
			if (!empty($items)) {
				$obj['docStatus'] = 9; // ���û��ȫ����أ�����µ���״̬Ϊ���ִ��
			}

			$this->updateById($obj);
			$this->backApplyMail_d($obj["id"] ,$back);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �ر�
	 */
	function close_d( $obj ) {
		try {
			$this->start_d ();

			$this->updateById($obj);

			if (is_array($obj["items"])) {
				$itemDao = new model_produce_apply_produceapplyitem();
				$closeNum = 0; //�رյ�������ʼ��Ϊ0
				$equIdArr = array();
				foreach ($obj["items"] as $key => $val) {
					if (isset($val['state'])) {
						if ($val['state'] == 0) { //���ιرյ�
							array_push($equIdArr ,$val["id"]);
							$val['state'] = 1;
							$itemDao->updateById($val);
						}
						$closeNum++;
					}
				}

				if ($closeNum == count($obj["items"])) { //ȫ���ر���ѵ���״̬��Ϊ�ر�
					$this->updateById(array("id" => $obj["id"] ,'docStatus' => 3));
				}

				if (!empty($equIdArr)) {
					$this->closeApplyMail_d($obj["id"] ,implode(',' ,$equIdArr));
				}
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * ����ͨ��
	 */
	function dealAfterAudit_d($id) {
		try {
			$this->start_d ();

			$this->applyMail_d($id); //�ʼ�֪ͨ

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * ���������ɺ�
	 */
	function dealAfterAuditChange_d($objId ,$userId){
		$obj = $this->get_d($objId);
		if($obj['ExaStatus'] == '���') {
			try{
				$this->start_d();

				$changeLogDao = new model_common_changeLog('produceapply');
				$changeLogDao->confirmChange_d( $obj );

				$this->updateById(array('id' => $obj['originalId'] ,'docStatus' => 1));
				$this->dealDocStatus_d($obj['originalId']); //������״̬

				$this->commit_d();
				return true;
			} catch(Exception $e) {
				$this->rollBack();
				return false;
			}
		} else {
			try{
				$this->start_d();

				$this->updateById(array('id' => $obj['originalId'] ,'docStatus' => 1 ,'ExaStatus' => '���'));

				$this->commit_d();
				return true;
			}catch(Exception $e){
				$this->rollBack();
				return false;
			}
		}
	}

	/**
	 * �´����������ʼ�֪ͨ
	 */
	function applyMail_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('produceapplyApply' ,null ,array("id" => $id));
	}

	/**
	 * ������������ʼ�֪ͨ
	 */
	function backApplyMail_d($id ,$back) {
		$obj = $this->get_d( $id );
		$exaInfo = array(
			"id" => $id
			,'backReason' => $back['reason']
			,'backName' => $back['name']
		);
		$this->mailDeal_d('produceapplyBack' ,$obj['createId'] ,$exaInfo);
	}

	/**
	 * �ر��������������ʼ�֪ͨ
	 */
	function closeApplyMail_d($id ,$equIds) {
		$obj = $this->get_d( $id );
		$exaInfo = array(
			"id"      => $id
			,'equIds' => $equIds
		);
		$this->mailDeal_d('produceapplyClose' ,$obj['createId'] ,$exaInfo);
	}

	/**
	 * �������ڻ�ȡ�����·ݵ��ܴΣ���1��ʼ��
	 */
	function getWeekly_d ($date) {
		$firstDay = date('Y-m-01' ,strtotime($date)); //��ȡ��ǰ�·ݵĵ�һ������
		$dayNum   = date('j' ,strtotime($date)); //�����Ǽ���
		$week     = date('w' ,strtotime($firstDay)); //��ȡ��ǰ�·ݵ�һ�������ڼ���0������-6��������
		$weekly = ceil(($dayNum + $week) / 7);
		return $weekly;
	}

	/**
	 * ����id������״̬(��������ı�״̬������������)
	 */
	function dealDocStatus_d($id) {
		$itemDao = new model_produce_apply_produceapplyitem();
		$itemObjs = $itemDao->findAll(array('mainId' => $id ,'state' => 0));
		if (is_array($itemObjs)) {
			$rs = true;
			foreach ($itemObjs as $key => $val) {
				if ($val['produceNum'] != $val['exeNum']) {
					$rs = false;
					break;
				}
			}
			if ($rs) {
				$this->updateById(array("id" => $id ,'docStatus' => 2));
			}
		}
	}

	/**
	 * ��ȡָ�������������ڵĿ�ʼ������������ڵ�ʱ��
	 * return array('startDate' ,'endDate')
	 */
	function getWeekDate_d($date = '') {
		$date = empty($date) ? date('Y-m-d') : $date;
		$timestamp = strtotime($date);
		$N = date('N' ,$timestamp); //1-7��ʾ����һ��������
		$dateArr = array();
		$dateArr['startDate'] = date('Y-m-d' ,$timestamp - ($N - 1) * 86400);
		$dateArr['endDate'] = date('Y-m-d' ,$timestamp + (7 - $N) * 86400);
		return $dateArr;
	}


	/**
	 * �޸Ĳ��ִ��
	 */
	function editBack_d($obj) {
		try {
			$this->start_d ();
			$editResult = parent::edit_d($obj ,true);

			if ($editResult && is_array($obj['items'])) {
				$itemDao = new model_produce_apply_produceapplyitem();
				$equDao = new model_contract_contract_equ();
				foreach($obj["items"] as $key => $val) {
					$equObj = $equDao->get_d($val['relDocItemId']);
					if ($val['isDelTag'] != 1) {
						$val['state'] = 0;
						$itemDao->edit_d($val ,true);
						//���·����嵥���´�����
						$equDao->updateById(
							array(
								"id" => $val['relDocItemId'] ,
								'issuedProNum' => ($equObj['issuedProNum'] + $val['produceNum'])
							)
						);
					} else {
						$itemDao->deleteByPk($val["id"]);
					}
				}

				$this->applyMail_d($obj["id"]);
			}

			$this->commit_d ();
			return $obj["id"];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/**
	 * �´�����ʱ��֤
	 * @param $id
	 * @param $itemIds �ӱ�id,Ĭ��Ϊ��
	 * @return array
	 */
	function taskCheck_d($id,$itemIds = null) {
		// ������Ϣ
		$obj = $this->get_d($id);
		// ��֤�����´�״̬
		if ($obj['docStatus'] != '0' && $obj['docStatus'] != '1' && $obj['docStatus'] != '9') {
			return array('pass' => 0, 'msg' => "ֻ���´�״̬Ϊδ�´�/�����´�/���ִ�صĵ��ݲ����´���������!");
		} else {
			$itemDao = new model_produce_apply_produceapplyitem();
			if(!is_null($itemIds)){
				$rs = $itemDao->findAll("id in(".$itemIds.")",null,'productCode,produceNum,exeNum,proType');
			}else{
				$rs = $itemDao->findAll(array('mainId' => $id),null,'productCode,produceNum,exeNum,proType');
			} 
			if (!empty($rs)) {
				$proType = '';
				$proTypeId = 0;
				foreach ($rs as $v){
// 					if($proType == ''){
// 						$proType = $v['proType'];
// 						$proTypeId = $v['proTypeId'];
// 					}else{
// 						if($proType != $v['proType']){
// 							return array('pass' => 0, 'msg' => "��ͬ�������͵����ϲ��ܺϲ�����һ�ŵ��ݣ�������ѡ��!");
// 							break;
// 						}
// 					}
					if($v['exeNum'] >= $v['produceNum']){
						return array('pass' => 0, 'msg' => "���ϱ��Ϊ��".$v['productCode']."���������Ѿ��´����,�����ظ��´�!");
						break;
					}
				}
			}else{
				return array('pass' => 0, 'msg' => "û�п����´������!");
			}
		}
		return array('pass' => 1, 'msg' => '', 'proType' => $proType, 'proTypeId' => $proTypeId);
	}
}