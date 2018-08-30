<?php

/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 16:46:27
 * @version 1.0
 * @description:���鱨�� Model��
 */
class model_produce_quality_qualityereport extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_produce_quality_ereport";
		$this->sql_map = "produce/quality/qualityereportSql.php";
		parent::__construct();
	}

	//��˾Ȩ�޴���
	protected $_isSetCompany = 1;

	/**
	 * �ʼ���ȡ
	 * @param $thisKey
	 * @return array
	 */
	function getMail_d($thisKey) {
		include(WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
			'sendName' => '');
		return $mailArr;
	}

	//״̬����
	function rtStatus($thisVal) {
		switch ($thisVal) {
			case "WTJ" :
				return "δ�ύ";
			case "WSH" :
				return "�����";
			case "YSH" :
				return "�ϸ�";
			case "RBJS" :
				return "�ò�����";
			case "BHG" :
				return "���ϸ�";
			case "BH" :
				return "����";
			case "BC" :
				return "����";
			default :
				return "�Ƿ�״̬";
		}
	}

	//�����ֵ�
	public $datadictFieldArr = array(
		'qualityType', 'relDocType'
	);

	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
        $ereportequitemProduceNum = 0;// ���ϸ���������
        $ereportequitemQualitedNum = 0;// �ϸ���������
        $hasEmptyQualitedNum = false;// �кϸ���Ϊ0������

		//�ʼ�����ȡ��
		if(isset($object ['items'])){
			$items = $object ['items'];
			unset($object ['items']);
		}

		//�ʼ�����ȡ��
		if(isset($object ['ereportequitem'])){
			$ereportequitem = $object ['ereportequitem'];
			unset($object ['ereportequitem']);
            foreach ($ereportequitem as $k => $v){// ��������ֶ�
                unset($ereportequitem[$k]['serialnoChkedNum']);
            }
		}

		//���ϸ��ʼ���ϸ
		if(isset($object['failureitem'])){
			$failureitem = $object['failureitem'];
			unset($object ['failureitem']);
		}
		try {
            //ʵ�����������
            $qualityTaskDao = new model_produce_quality_qualitytask();
            $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyItemDao = new model_produce_quality_qualityapplyitem();

			//Դ������Ϊ��������ģ��ʼ���������Ϊ��
			if ($object['relDocType'] != 'ZJSQYDSC' && !is_array($items)) {
				throw new Exception ("������Ϣ����������ȷ�ϣ�");
			}

			$this->start_d();

			//��������
			$codeDao = new model_common_codeRule ();
			$object ['docCode'] = $codeDao->stockCode("oa_produce_quality_ereport", "ZJBG");

			//�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
			if ($object['auditStatus'] == "WSH") {
				if ($object['produceNum'] == 0 && $object['relDocType'] != 'ZJSQDLBF') {
					//����ϸ��ֱ��ͨ����ˣ�����Ҫ�ٴ���
					$object['auditStatus'] = 'YSH';
					$object['ExaStatus'] = '���';
				} else if ($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 ���ϱ����ʼ���Ҫͨ������ύ����������,�����ԭ��������δͨ������,��˱���״̬Ϊ���
                    $object['auditStatus'] = 'YSH';
                    $object['ExaStatus'] = '���ύ';
                } else {
					$object['ExaStatus'] = '���ύ';
				}
			}
			if ($object['auditStatus'] == "BC") {
				//����Ǳ���״̬�����ʼ����������״̬���б��
				$object['ExaStatus'] = '���ύ';
			}
            if ($object['qualityType'] == 'ZJFSCJ' ) {
                unset($object['standardId']);
            }
			//����
			$object = $this->processDatadict($object);
			$id = parent::add_d($object, true);

			//�ʼ�����
			if($items){
				$qualityereportitemDao = new model_produce_quality_qualityereportitem();
				$items = util_arrayUtil::setItemMainId("mainId", $id, $items);
				$qualityereportitemDao->saveDelBatch($items);
			}

			//�ʼ��豸����
			if($ereportequitem){
				$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
				$ereportequitem = util_arrayUtil::setItemMainId("mainId", $id, $ereportequitem);
				$qualityereportequitemDao->saveDelBatch($ereportequitem);
			}

			//���ϸ��豸
			if ($failureitem) {
				$failureitemDao = new model_produce_quality_failureitem();
				$failureitem = util_arrayUtil::setItemMainId("mainId", $id, $failureitem);
				$failureitemDao->saveDelBatch($failureitem);
			}

			//�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
			if($ereportequitem){
				foreach ($ereportequitem as $val) {
					if ($val['isDelTag'] != "1") {
						//��������ʼ���
						$checkedNumArr = $qualityTaskItemDao->find(array("id" => $val['relItemId']), null, "checkedNum,assignNum,standardNum");
						$checkedNum = $checkedNumArr['checkedNum'];
						$checkedNum += $val['thisCheckNum'];
                        $standardNum = $checkedNumArr['standardNum'];
                        $standardNum += $val['qualitedNum'];
                        $qualitedNum = $standardNum;

						if ($object['auditStatus'] == "BC") {
							//����״̬
							$checkStatus = "";
						} elseif (($checkedNumArr['assignNum'] - $checkedNum) == 0) {
							//����״̬
							$checkStatus = "YJY";
						} else {
							$checkStatus = "BFJY";
						}

						$cklTypeUpdateArr = array();
                        $realCheckNum = $checkedNum;
                        if($object['qualityType'] == "ZJFSCJ"){
                            $realCheckNum = $val['samplingNum'];// ����ʵ�ʼ���������ڳ������
                            // $qualitedNum = bcsub($val['thisCheckNum'],$val['produceNum']);
                            $cklTypeUpdateArr['checkType'] = 'ZJFSCJ';
                            $cklTypeUpdateArr['checkTypeName'] = '���';
                        }else if($object['qualityType'] == "ZJFSQJ"){
                            $cklTypeUpdateArr['checkType'] = 'ZJFSQJ';
                            $cklTypeUpdateArr['checkTypeName'] = 'ȫ��';
                        }

						//����״̬�Լ��ϸ����������ʼ���
						$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' =>$qualitedNum, "realCheckNum" => $realCheckNum,"checkedNum" => $checkedNum, "thisCheckNum" => ($checkedNumArr['assignNum'] - $checkedNum));
                        if(!empty($cklTypeUpdateArr) && isset($cklTypeUpdateArr['checkType'])){
                            $qualityTaskItem['checkType'] = $cklTypeUpdateArr['checkType'];
                            $qualityTaskItem['checkTypeName'] = $cklTypeUpdateArr['checkTypeName'];
                        }
						$qualityTaskItemDao->updateById($qualityTaskItem);

                        $ereportequitemProduceNum += $val['produceNum'];
                        $ereportequitemQualitedNum += $val['qualitedNum'];
                        if($val['qualitedNum'] <= 0){
                            $hasEmptyQualitedNum = true;
                        }
					}
				}
			}

			//���¼�������״̬
			$qualityTaskDao->renewStatus_d($object['mainId']);

			//����ʼ��Ǻϸ�ģ���ʼ�����ϼ�����
            if($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 ����Ǵ��ϱ��������
                $qualityapplyArr = $qualityapplyDao->find(array("id" => $object['applyId']));
                if($ereportequitemQualitedNum <= 0){// ���ϸ�ֱ���ʼ����,����д�������ⵥ״̬δ���
                    $this->dealAfterDldfProcess_d($id,null,"zj_disPass",$object['auditStatus']);
                }else{
                    $this->dealAfterDldfProcess_d($id,null,"zj_pass",$object['auditStatus']);
                    // ȫ���ϸ��֪ͨ�ʼ챨����Ա��ʱ���
                    $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $id));
                }
            }elseif ($object['auditStatus'] == "YSH" && $object['produceNum'] == 0) {
				$this->dealAtConfirm_d($id, $object['mailInfo']);
			}else{//����ʼ첻�ϸ�֪ͨ�ʼ챨����Ա��ʱ���
                $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $id));
            }

			//���¸���������ϵ
			$this->updateObjWithFile($id,$object ['docCode']);

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �޸ı���
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
	    $ereportequitemProduceNum = 0;// ���ϸ���������
        $ereportequitemQualitedNum = 0;// �ϸ���������
        $hasEmptyQualitedNum = false;// �кϸ���Ϊ0������

		//�ʼ�����ȡ��
		if(isset($object ['items'])){
			$items = $object ['items'];
			unset($object ['items']);
		}

		//�ʼ�����ȡ��
		if(isset($object ['ereportequitem'])){
			$ereportequitem = $object ['ereportequitem'];
			unset($object ['ereportequitem']);
		}

		//���ϸ��ʼ���ϸ
		if(isset($object['failureitem'])){
			$failureitem = $object['failureitem'];
			unset($object ['failureitem']);
		}

		try {
            //ʵ�����������
            $qualityTaskDao = new model_produce_quality_qualitytask();
            $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyItemDao = new model_produce_quality_qualityapplyitem();

			//Դ������Ϊ��������ģ��ʼ���������Ϊ��
			if ($object['relDocType'] != 'ZJSQYDSC' && !is_array($items)) {
				throw new Exception ("������Ϣ����������ȷ�ϣ�");
			}

			$this->start_d();

			//�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
			if ($object['auditStatus'] == "WSH") {
				if ($object['produceNum'] == 0 && $object['relDocType'] != 'ZJSQDLBF') {
					//����ϸ��ֱ��ͨ����ˣ�����Ҫ�ٴ���
					$object['auditStatus'] = 'YSH';
					$object['ExaStatus'] = '���';
				} else if ($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 ���ϱ����ʼ���Ҫͨ������ύ����������,�����ԭ��������δͨ������,��˱���״̬Ϊ���
                    $object['auditStatus'] = 'YSH';
                    $object['ExaStatus'] = '���ύ';
                }else {
					$object['ExaStatus'] = '���ύ';
				}
			}
			if ($object['auditStatus'] == "BC") {
				//����Ǳ���״̬�����ʼ����������״̬���б��
			}

			//����
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//�ʼ�����
			if($items){
				$qualityereportitemDao = new model_produce_quality_qualityereportitem();
				$items = util_arrayUtil::setItemMainId("mainId", $object['id'], $items);
				$qualityereportitemDao->saveDelBatch($items);
			}

			//�ʼ��豸����
			if($ereportequitem){
				$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
				$ereportequitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $ereportequitem);
				$qualityereportequitemDao->saveDelBatch($ereportequitem);
			}

			//���ϸ��豸
			if ($failureitem || $object['isChangeItem'] == 1) {
				$failureitemDao = new model_produce_quality_failureitem();
				//ɾ�����豸
				if ($object['isChangeItem'] == 1) {
					$failureitemDao->delete(array('mainId' => $object['id']));
				}
				//�����ϸ��豸
				if ($failureitem) {
					$failureitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $failureitem);
					$failureitemDao->saveDelBatch($failureitem);
				}
			}

			//�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
			//���������嵥�ļ���״̬
			if($ereportequitem){
				foreach ($ereportequitem as $val) {
					if ($val['isDelTag'] != "1") {
						//��������ʼ���
						$checkedNumArr = $qualityTaskItemDao->find(array("id" => $val['relItemId']), null, "thisCheckNum");
                        $qualitedNum = $val['qualitedNum'];
						if ($object['auditStatus'] == "BC") {
							//����״̬
							$checkStatus = "";
						} elseif (($checkedNumArr['thisCheckNum']) == 0) {
							//����״̬
							$checkStatus = "YJY";
						} else {
							$checkStatus = "BFJY";
						}

                        $cklTypeUpdateArr = array();
                        $realCheckNum = $checkedNum;
                        if($object['qualityType'] == "ZJFSCJ"){
                            $realCheckNum = $val['samplingNum'];// ����ʵ�ʼ���������ڳ������
                            // $qualitedNum = bcsub($val['thisCheckNum'],$val['produceNum']);
                            $cklTypeUpdateArr['checkType'] = 'ZJFSCJ';
                            $cklTypeUpdateArr['checkTypeName'] = '���';
                        }else if($object['qualityType'] == "ZJFSQJ"){
                            $cklTypeUpdateArr['checkType'] = 'ZJFSQJ';
                            $cklTypeUpdateArr['checkTypeName'] = 'ȫ��';
                        }

						//����״̬�Լ��ϸ�����
						$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $qualitedNum,"realCheckNum" => $realCheckNum);
                        if(!empty($cklTypeUpdateArr) && isset($cklTypeUpdateArr['checkType'])){
                            $qualityTaskItem['checkType'] = $cklTypeUpdateArr['checkType'];
                            $qualityTaskItem['checkTypeName'] = $cklTypeUpdateArr['checkTypeName'];
                        }
						$qualityTaskItemDao->updateById($qualityTaskItem);

                        $ereportequitemProduceNum += $val['produceNum'];
                        $ereportequitemQualitedNum += $val['qualitedNum'];
                        if($val['qualitedNum'] <= 0){
                            $hasEmptyQualitedNum = true;
                        }
					}
				}
			}

			//���¼�������״̬
			$qualityTaskDao->renewStatus_d($object['mainId']);

			//����ʼ��Ǻϸ�ģ���ʼ�����ϼ�����
            if($object['relDocType'] == 'ZJSQDLBF'){// PMS2386 ����Ǵ��ϱ��������
                if($ereportequitemQualitedNum <= 0){// ���ϸ�ֱ�������ʼ첻�ϸ�������,����д�������ⵥ״̬�����ϱ���,���û�б�������,��Ϊ�ʼ첻�ϸ�
                    $this->dealAfterDldfProcess_d($object['id'],null,"zj_disPass",$object['auditStatus']);
                }else{
                    $this->dealAfterDldfProcess_d($object['id'],null,"zj_pass",$object['auditStatus']);
                    // ȫ���ϸ��֪ͨ�ʼ챨����Ա��ʱ���
                    $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
                }
            }else if ($object['auditStatus'] == "YSH" && $object['produceNum'] == 0) {
                $this->dealAtConfirm_d($object['id'], $object['mailInfo']);
			}else{//����ʼ첻�ϸ�֪ͨ�ʼ챨����Ա��ʱ���
                $this->mailDeal_d('qualityReport', $object['mailInfo']['TO_ID'], array('id' => $object['id']));
            }

			$this->commit_d();
			return $object['id'];
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ɾ��
	 * @param $id
	 * @return bool
	 */
	function deletes_d($id) {
		try {
			$this->start_d();

			//�Ȼ�ԭ�����¼
			$obj = $this->get_d($id);

			//���������嵥�ļ���״̬
			$qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

			$serialnoDao = new model_produce_quality_serialno();
			foreach ($obj['ereportequitem'] as $val) {
				$arr = $qualityTaskItemDao->find(array('id' => $val['relItemId']));
				$checkedNum = $arr['checkedNum'] - $val['thisCheckNum'];
				$thisCheckNum = $arr['thisCheckNum'] + $val['thisCheckNum'];
				$standardNum = $arr['standardNum'] - $val['qualitedNum'];
				//����״̬
				$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => "", "checkedNum" => $checkedNum, "thisCheckNum" => $thisCheckNum, 'standardNum' => $standardNum);
				$qualityTaskItemDao->updateById($qualityTaskItem);

				//ɾ�����ϸ����к�
				$del = 'relDocId = "' . $val['relItemId'] . '" and relDocType ="oa_produce_quality_serialno"';
				$serialnoDao->delete($del);
			}

			//ɾ������
			parent::deletes_d($id);

			//Դ������Ϊ��������ģ�����ɾ������
			if($obj['relDocType'] == 'ZJSQYDSC'){
				$managementDao = new model_file_uploadfile_management();
				$managementDao->delete(array('serviceId' => $id,'serviceType' => 'oa_produce_quality_ereport'));
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @param $id
	 * @return bool|mixed
	 */
	function get_d($id) {
		$object = parent::get_d($id);
		$qualityereportitemDao = new model_produce_quality_qualityereportitem();
		$qualityereportitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $qualityereportitemDao->listBySqlId();

		$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
		$qualityereportequitemDao->searchArr ['mainId'] = $id;
		$object ['ereportequitem'] = $qualityereportequitemDao->listBySqlId();
		//add chenrf
		$standardModel = new model_produce_quality_standard();
		$uploadFile = new model_file_uploadfile_management ();
		$files = $uploadFile->getFilesByObjId($object['standardId'], $standardModel->tbl_name);
		if (is_array($files) && !empty($files))
			$object['fileImage'] = '<a href="?model=file_uploadfile_management&action=toDownFileById&fileId=' . $files[0]['id'] . '" taget="_blank" title="�������"><img src="images/icon/icon103.gif" /></a>';
		else
			$object['fileImage'] = '';
		return $object;
	}

	/**
	 * �����ɺ���ҵ��
	 * @param $id
	 * @param null $mailArr
	 * @return bool
	 * @throws Exception
	 */
	function dealAtConfirm_d($id, $mailArr = null) {
		try {
			//��ȡ��������
			$obj = $this->get_d($id);

			//�����ʼ�����
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//�����ʼ�����
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//���¶�Ӧ����Դ��ҵ��
			$applyDao = new model_produce_quality_qualityapply();
			//�������뵥����
			$applyArr = array();

			//ѭ������
			foreach ($obj['ereportequitem'] as $val) {
				//��ѯ�����Դ��id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//�����ʼ�������ϸ����
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $val['qualitedNum'], $val['thisCheckNum']);

				//Դ������
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//���뵥Դ������
				$relClass = $applyDao->getStrategy_d($taskObj['applyId']);
				$relClassM = new $relClass ();//����ʵ��
				$applyObj = $applyDao->get_d($taskObj['applyId']);
				$applyDao->dealRelInfoAtConfirm($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum']);
				$applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
			}

			//��������������飬��ͳһ�������뵥״̬
			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//�����ʼ�����
				if (!empty($mailArr) && $mailArr['issend'] == "y") {
					$mailStr = "��ã��ʼ�Ա��" . $obj['examineUserName'] . "���Ѿ�¼�����ʼ챨�桾" . $obj['docCode'] . "������˽��Ϊ ��" . $this->rtStatus($obj['auditStatus'])
						. "��,�ʼ췽ʽΪ ��" . $obj['qualityTypeName'] . "��,�����豸��ϸ���£�" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>���ϱ���</td><td>��������</td><td>����ͺ�</td><td>��Ӧ��</td><td>��������</td><td>��λ</td><td>����ʱ��</td><td>����Դ����</td><td>������</td><td>�����̶�</td><td>�ϸ���</td><td>���ϸ���</td><td>��ע</td></tr>";
					foreach ($obj['ereportequitem'] as $key => $val) {
						$mailStr .= <<<EOT
							<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA֪ͨ���ʼ챨��', $mailArr['TO_ID'], $mailStr);
				}
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �����ɺ���ҵ��
	 * @param $object
	 * @return bool
	 */
	function confirm_d($object) {
		//�ʼ�����ȡ��
		$ereportequitem = $object ['ereportequitem'];
		unset($object ['ereportequitem']);

		try {
			$this->start_d();

			$object['auditorName'] = $_SESSION['USERNAME'];
			$object['auditorId'] = $_SESSION['USER_ID'];
			$object['auditDate'] = date('Y-m-d H:i:s');

			//������/�����ʼ�ֱ��ͨ����� PMS2386:���ϱ����ʼ����
			if ($object['relDocType'] != 'ZJSQYDSL' && $object['relDocType'] != 'ZJSQYDSC' && $object['relDocType'] != 'ZJSQDLBF') {
				$object['ExaStatus'] = AUDITED;
				$object['ExaDT'] = day_date;
			}

			//�����ʼ챨��
			$object = $this->processDatadict($object);
			parent::edit_d($object, true);

			//�ʼ��豸����
			$qualityereportequitemDao = new model_produce_quality_qualityereportequitem();
			$ereportequitem = util_arrayUtil::setItemMainId("mainId", $object['id'], $ereportequitem);
			$qualityereportequitemDao->saveDelBatch($ereportequitem);

			//������/�����ʼ�ֱ��ͨ����� PMS2386:���ϱ����ʼ����
			if ($object['relDocType'] != 'ZJSQYDSL' && $object['relDocType'] != 'ZJSQYDSC' && $object['relDocType'] != 'ZJSQDLBF') {
				$this->dealAtBack_d($object['id']);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����֮����÷���
	 * @param $spid
	 * @return bool|int
	 * @throws Exception
	 */
	function workflowCallBack($spid) {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo($spid);
		$objId = $folowInfo['objId'];
		$object = $this->get_d($objId);
        if($object['relDocType'] == 'ZJSQDLBF'){// ���ϱ���������ɺ�������
            $dispassNum = 0;
            $auditResult = $this->_db->getArray("select * from flow_step_partent where Wf_task_ID = {$folowInfo['task']};");
            foreach ($auditResult as $v){
                if($v['Result'] == 'no'){
                    $dispassNum += 1;
                }
            }
            return ($dispassNum > 0)? $this->dealAfterDldfProcess_d($objId, $object, 'sp_disPass') : $this->dealAfterDldfProcess_d($objId, $object, 'sp_pass');
//            return ($dispassNum <= 0 && $object['ExaStatus'] == AUDITED)? $this->dealAfterDldfProcess_d($objId, $object, 'sp_pass') : $this->dealAfterDldfProcess_d($objId, $object, 'sp_disPass');
        }else if ($object['ExaStatus'] == AUDITED) {
			if ($object['auditStatus'] == 'BHG') {
				return $this->dealAtBack_d($objId, $object);
			} else if ($object['auditStatus'] == 'RBJS') {
				return $this->dealAtReceive_d($objId, $object);
			}
		}
		return 1;
	}

    /**
     * �ʼ챨�治������,ֱ�Ӵ�����������
     * @param $objId
     * @return bool
     */
	function dealWithoutAudit($objId){
        $object = $this->get_d($objId);
        $today = date("Y-m-d");
        $this->updateById(array("id"=>$objId,"ExaStatus"=>"���","ExaDT"=>$today));
        if ($object['auditStatus'] == 'BHG') {
            return $this->dealAtBack_d($objId, $object);
        } else if ($object['auditStatus'] == 'RBJS') {
            return $this->dealAtReceive_d($objId, $object);
        }
    }

    /**
     * �����ʼ����뵥����ԭ�������������Ƿ��ʼ�ͨ����
     * @param $applyId
     * @return bool
     */
	function checkAllPass($applyId){
        // �ʼ�������ϸ
        $applyItemDao = new model_produce_quality_qualityapplyitem();
        $chekResultArr = $applyItemDao->_db->getArray("select sum(qualityNum) as qualityNum,sum(standardNum) as standardNum from oa_produce_qualityapply_item where mainId in ({$applyId}) ORDER BY id desc;");
        if($chekResultArr){
            if($chekResultArr[0]['qualityNum'] == $chekResultArr[0]['standardNum']){// ȫ������ԭ�������ʼ����
                return true;
            }else{// ���ֹ���ԭ�������ʼ����
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * �����ʼ����뵥����ԭ���Ƿ��ʼ�ͨ���� (�б���������ͨ��)
     * @param $applyId
     * @return bool
     */
    function checkDLBFPass($applyId){
        // �ʼ�������ϸ
        $applyItemDao = new model_produce_quality_qualityapplyitem();
        $chekResultArr = $applyItemDao->_db->getArray("select sum(i.produceNum) as produceNum,sum(i.qualitedNum) as qualitedNum from oa_produce_quality_ereportequitem i left join oa_produce_quality_ereport e on i.mainId = e.id where e.applyId = {$applyId};");
        if($chekResultArr){
            if($chekResultArr[0]['qualitedNum'] > 0){// �б��������ʼ����
                return true;
            }else{// ���ֹ���ԭ�������ʼ����
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * ���ϱ�������ͨ����������
     * @param $id
     * @param null $object
     * @param string $auditStatus
     * @return bool
     */
	function dealAfterDldfProcess_d($id, $object = null, $processType = 'zj_pass', $auditStatus = ''){
        if (empty($object)) {
            $object = $this->get_d($id);
        }
        try {
            $this->start_d();

            // �ʼ�������ϸ
            $taskItemDao = new model_produce_quality_qualitytaskitem();
            // �ʼ�������ϸ
            $applyItemDao = new model_produce_quality_qualityapplyitem();
            // �ʼ�����Դ��
            $qualityTaskDao = new model_produce_quality_qualitytask();
            // �ʼ�����Դ��
            $applyDao = new model_produce_quality_qualityapply();
            // ��������Դ��
            $stockoutDao = new model_stock_outstock_stockout();
            // �������뵥����
            $applyArr = array();

            $objOldArr = $this->find(array("id"=>$id));
            $qualityapplyArr = $applyDao->find(array("id" => $objOldArr['applyId']));
            if($auditStatus != 'BC') {// �㱣��Ĳ������´���
                // �ʼ�ͨ����Ҫ���Ƿ�ȫ��ͨ���ļ��鴦��
                if ($processType == "zj_pass" || $processType == "sp_pass") {// �ʼ�ͨ�� �� ����ͨ��
                    if ($processType == "zj_pass") {// ����Ƿ�ȫ���ʼ�ͨ��,�����������ⵥ״̬�����ʼ���
                        //ѭ������
                        foreach ($object['ereportequitem'] as $val) {
                            //��ѯ�����Դ��id
                            $taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
                            //�����ʼ�������ϸ���� ��qualitedNum: ��������,produceNum: ������������
                            $qualitedNum = ($val['qualitedNum'] > 0)? $val['qualitedNum'] + $val['produceNum'] : $val['qualitedNum'];// ���ϱ���,ֻҪ�б���������Ϊ�ʼ�ͨ��
                            $applyItemDao->updateDeal_d($taskObj['applyItemId'], $qualitedNum, $val['thisCheckNum']);

                            //Դ������
                            if (!in_array($taskObj['applyId'], $applyArr)) {
                                array_push($applyArr, $taskObj['applyId']);
                            }
                        }

                        //��������������飬��ͳһ�������뵥״̬
                        if ($applyArr) {
                            foreach ($applyArr as $v) {
                                $applyDao->renewStatus_d($v);
                            }
                        }

                        $chekResult = $this->checkDLBFPass($objOldArr['applyId']);
                        $docStatus = $chekResult ? "SPZ" : "ZJZ";
                        // �����������ⵥ
                        $stockoutUpdateArr = array("id" => $qualityapplyArr['relDocId'], "docStatus" => $docStatus);
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);

                    }else if($processType == "sp_pass"){// ����ͨ����,ʹ�ò��Դ������ҵ��
                        $qualityapplyDao = new model_produce_quality_qualityapply();
                        $relClass = $qualityapplyDao->getStrategy_d($objOldArr['applyId']);
                        $relClassM = new $relClass(); //����ʵ��
                        $applyObj = $qualityapplyDao->get_d($objOldArr['applyId']);
                        $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);

                        // ����ͨ�����Ͳ�����������Ϣ�ʼ�
                        if($object['ExaStatus'] == AUDITED){
                            $this->sendMailForUnblockEqus($id);
                        }
                    }

                } else {// ��ͨ��
                    switch ($processType) {
                        case 'zj_disPass'://�ʼ첻ͨ��,ͳһ����ԭ��������ȫ���ʼ�����,�ʼ챨��,�ʼ�����,�Լ�ԭ���ⵥ
                            //�����ʼ�����ԭ��
                            $workDetailMsg = $qualityapplyArr['workDetail'] . " (���ϱ����ʼ����,���в��ϸ�����,�ʼ첻ͨ����)";
                            $conditionArr = array("id" => $objOldArr['applyId']);
                            $updateArr = array("id" => $objOldArr['applyId'], "status" => 3, "workDetail" => $workDetailMsg);
                            $updateArr = $applyDao->addUpdateInfo($updateArr);
                            $updateArr['closeUserName'] = $_SESSION['USERNAME'];
                            $updateArr['closeUserId'] = $_SESSION['USER_ID'];
                            $updateArr['closeTime'] = date("Y-m-d H:i:s");
                            $applyDao->update($conditionArr, $updateArr);
                            // �����ʼ�������ϸ
                            $conditionArr = array("mainId" => $objOldArr['applyId']);
                            $updateArr = array("mainId" => $objOldArr['applyId'], "status" => 3, "passReason" => "���ϱ����ʼ첻ͨ��,ͳһ��ء�");
                            $applyItemDao->update($conditionArr, $updateArr);

                            // �ʼ�������� (PMS2386 ���ϱ����ʼ�����һ�����ϸ�,��ȫ������Ϊ�����)
                            $condition = "applyId={$objOldArr['applyId']}";// �����ʼ�������ϸ
                            $taskItemDao->update($condition, array('checkStatus' => 'YJY'));
                            $condition = "applyId={$objOldArr['applyId']} AND (acceptStatus <> 'YWC' OR complatedTime IS NULL)";// �����ʼ�����
                            $qualityTaskDao->update($condition, array('acceptStatus' => 'YWC', 'complatedTime' => date("Y-m-d H:i:s")));

                            // �ʼ챨����� (PMS2386 ���ϱ����ʼ�����һ�����ϸ�,��ȫ�����涼���ϸ�,������״̬Ϊ���ύ)
                            $conditionArr = array("applyId" => $objOldArr['applyId']);
                            $qualityResultArr = array("auditStatus" => "BHG", "ExaStatus" => "���ύ", "ExaDT" => '');
                            $this->update($conditionArr, $qualityResultArr);

                            // �ʼ첻ͨ��ʱ���Ͳ�����������Ϣ�ʼ�
                            $this->sendMailForUnblockEqus($id);

                            break;
                        case 'sp_disPass'://������ͨ��
                            //�����ʼ�����ԭ��
                            $workDetailMsg = $qualityapplyArr['workDetail'] . " (���ϱ����ʼ�������ͨ����)";
                            $conditionArr = array("id" => $objOldArr['applyId']);
                            $updateArr = array("id" => $objOldArr['applyId'], "status" => 3, "workDetail" => $workDetailMsg);
                            $updateArr = $applyDao->addUpdateInfo($updateArr);
                            $updateArr['closeUserName'] = $_SESSION['USERNAME'];
                            $updateArr['closeUserId'] = $_SESSION['USER_ID'];
                            $updateArr['closeTime'] = date("Y-m-d H:i:s");
                            $applyDao->update($conditionArr, $updateArr);
                            // �����ʼ�������ϸ
                            $conditionArr = array("mainId" => $objOldArr['applyId']);
                            $updateArr = array("mainId" => $objOldArr['applyId'], "status" => 3, "passReason" => "���ϱ����ʼ�������ͨ��,ͳһ��ء�");
                            $applyItemDao->update($conditionArr, $updateArr);

                            // �ʼ�������� (PMS2386 ���ϱ����ʼ��������,��ȫ������Ϊ�����)
                            $condition = "applyId={$objOldArr['applyId']}";// �����ʼ�������ϸ
                            $taskItemDao->update($condition, array('checkStatus' => 'YJY'));
                            $condition = "applyId={$objOldArr['applyId']} AND (acceptStatus <> 'YWC' OR complatedTime IS NULL)";// �����ʼ�����
                            $qualityTaskDao->update($condition, array('acceptStatus' => 'YWC', 'complatedTime' => date("Y-m-d H:i:s")));

                            // �ʼ챨����� (PMS2386 ���ϱ����ʼ��������,��ȫ�����涼���ϸ�,������״̬Ϊ�������)
                            $conditionArr = array("applyId" => $objOldArr['applyId']);
                            $qualityResultArr = array("auditStatus" => "BHG", "ExaStatus" => "�������", "ExaDT" => date("Y-m-d"));
                            $this->update($conditionArr, $qualityResultArr);
                            break;
                    }

                    // ��ش��ϱ���ԭ���ⵥ��ش���
                    $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();
                    $blockeququalityapplyDao->dealRelItemBack($qualityapplyArr['relDocId'],'','',$processType);
                    $blockeququalityapplyDao->dealRelInfoBack($qualityapplyArr['relDocId']);
                }
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ����һ��[�����������ⲻ����������Ϣ]�ʼ����г������嵥�� �������ⵥ�� ���ϱ��룬�������ƣ����������������������кţ����͸�����ԣ�¼���ʼ챨�������
     * @param $id
     */
    function sendMailForUnblockEqus($id){
        $chkSQL = "select ot.docCode,oi.id,oi.productCode,oi.productName,ei.qualitedNum,ei.produceNum,ei.serialnoId as removeSerialnoId,ei.serialnoName as removeSerialnoName,oi.actOutNum,oi.qualityNum,oi.serialnoId,oi.serialnoName from oa_produce_quality_ereportequitem ei left join oa_stock_outstock_item oi on oi.id = ei.mainDocItemId left join oa_stock_outstock ot on ot.id = oi.mainId where ei.mainId = '{$id}';";
        $result = $this->_db->getArray($chkSQL);
        $mainCode = "";$index = 1;
        if($result){
            $tableContent = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center >".
                "<tr bgcolor=#EAEAEA align=center ><th>���</th><th>���ϱ���</th><th>��������</th><th>����������</th><th>���������к�</th></tr>";
            foreach ($result as $k => $v){
                $productCode = $v["productCode"];
                $productName = $v["productName"];
                $produceNum = $v["produceNum"];
                $removeSerialnoNameStr = $v["removeSerialnoName"];
                $mainCode = ($mainCode == "")? $v["docCode"] : $mainCode;
                $produceNum = bcadd($produceNum,0,0);
                $tableContent .= "<tr><td>{$index}</td><td>{$productCode}</td><td>{$productName}</td><td>{$produceNum}</td><td>{$removeSerialnoNameStr}</td></tr>";
                $index += 1;
            }
            $tableContent .= "</table>";
            $ebody = "���ã������Ǵ����������ⵥ ".$mainCode." �Ĳ����������б�:<br/> ".$tableContent;
            $addresses="";
            $uids = "'honghui.liu','".$_SESSION['USER_ID']."'";
            $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in(".$uids.")";
            $adrsArr = $this->_db->getArray($sql);
            $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";

            if($addresses != "" && $mainCode != ""){
                $title = "�����������ⲻ����������Ϣ";
                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','$title','$ebody','$addresses','',NOW(),'','','1')";
                $this->_db->query($sql);
            }
        }
    }

	/**
	 * �˻ش���
	 * @param $id
	 * @param null $object
	 * @return bool
	 */
	function dealAtBack_d($id, $object = null) {
		if (empty($object)) {
			$object = $this->get_d($id);
		}
		try {
			$this->start_d();

			//�����ʼ�����
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//�����ʼ�����
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//���¶�Ӧ����Դ��ҵ��
			$applyDao = new model_produce_quality_qualityapply();
			//�������뵥����
			$applyArr = array();

			//����ɹ�ԱID
			$purchaserIdArr = array();

			//ѭ������
			foreach ($object['ereportequitem'] as $val) {
				//��ѯ�����Դ��id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//�����ʼ�������ϸ����
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $val['qualitedNum'], $val['thisCheckNum']);

				//Դ������
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//���뵥Դ������ - ������˻ش�������3Ϊ�ʼ�����
                if($object['relDocType'] != 'ZJSQDLBF') {// ���ϱ���������ͨ����������
                    $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                    $relClassM = new $relClass ();//����ʵ��
                    $applyObj = $applyDao->get_d($taskObj['applyId']);
                    $applyDao->dealRelInfoAtBack($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum'], $val['passNum'], $val['receiveNum'], $val['backNum']);
                    $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                }

				//������ɹ�Ա����
				if (!in_array($val['purchaserId'], $purchaserIdArr)) {
					array_push($purchaserIdArr, $val['purchaserId']);
				}
			}

			//��������������飬��ͳһ�������뵥״̬
			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//�����ʼ�����
				if (!empty($purchaserIdArr)) {
					$mailStr = "��ã��ʼ챨�桾" . $object['docCode'] . "����ͨ����������˽��Ϊ ��" . $this->rtStatus($object['auditStatus'])
						. "��,�ʼ췽ʽΪ ��" . $object['qualityTypeName'] . "��,������Ϊ ��" . $object['examineUserName'] . "�������豸��ϸ���£�" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>���ϱ���</td><td>��������</td><td>����ͺ�</td><td>��Ӧ��</td><td>��������</td><td>��λ</td><td>����ʱ��</td><td>����Դ����</td><td>������</td><td>�����̶�</td><td>�ϸ���</td><td>���ϸ���</td><td>�ò�������</td><td>�˻�����</td><td>��ע</td></tr>";
					foreach ($object['ereportequitem'] as $val) {
						$mailStr .= <<<EOT
							<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[receiveNum]</td><td>$val[backNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					//��ȡ�����ʼ�������
					$mailArr = $this->getMail_d('purchquality');
					$purchaserIdArr = array_merge($purchaserIdArr, explode(',', $mailArr['sendUserId']));

					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA֪ͨ���ʼ챨��', implode(',', $purchaserIdArr), $mailStr);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ò�����
	 * @param $id
	 * @param null $object
	 * @return bool
	 */
	function dealAtReceive_d($id, $object = null) {
		if (empty($object)) {
			$object = $this->get_d($id);
		}
		try {
			$this->start_d();

			//�����ʼ�����
			$taskItemDao = new model_produce_quality_qualitytaskitem();
			//�����ʼ�����
			$applyItemDao = new model_produce_quality_qualityapplyitem();
			//���¶�Ӧ����Դ��ҵ��
			$applyDao = new model_produce_quality_qualityapply();
			//�������뵥����
			$applyArr = array();

			//����ɹ�ԱID
			$purchaserIdArr = array();

			//ѭ������
			foreach ($object['ereportequitem'] as $val) {
				//�������� = �������� + �ò���������
				$canUseNum = bcadd($val['receiveNum'], $val['passNum']);

				//��ѯ�����Դ��id
				$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId');
				//�����ʼ�������ϸ����
				$applyItemDao->updateDeal_d($taskObj['applyItemId'], $canUseNum, $val['thisCheckNum']);

				//Դ������
				if (!in_array($taskObj['applyId'], $applyArr)) {
					array_push($applyArr, $taskObj['applyId']);
				}

				//���뵥Դ������ - �ò����մ�����ϸ�����Ϊ�ò���������
                if($object['relDocType'] != 'ZJSQDLBF'){// ���ϱ���������ͨ����������
                    $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                    $relClassM = new $relClass ();//����ʵ��
                    $applyObj = $applyDao->get_d($taskObj['applyId']);
                    $applyDao->dealRelInfoAtReceive($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum'],
                        $val['passNum'], $val['receiveNum'], $val['backNum']
                    );
                    $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                }

				//������ɹ�Ա����
				if (!in_array($val['purchaserId'], $purchaserIdArr)) {
					array_push($purchaserIdArr, $val['purchaserId']);
				}
			}

			if ($applyArr) {
				foreach ($applyArr as $v) {
					$applyDao->renewStatus_d($v);
				}

				//�����ʼ�����
				if (!empty($purchaserIdArr)) {
					$mailStr = "��ã��ʼ챨�桾" . $object['docCode'] . "����ͨ����������˽��Ϊ ��" . $this->rtStatus($object['auditStatus'])
						. "��,�ʼ췽ʽΪ ��" . $object['qualityTypeName'] . "��,������Ϊ ��" . $object['examineUserName'] . "�������豸��ϸ���£�" .
						"<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>���ϱ���</td><td>��������</td><td>����ͺ�</td><td>��Ӧ��</td><td>��������</td><td>��λ</td><td>����ʱ��</td><td>����Դ����</td><td>������</td><td>�����̶�</td><td>�ϸ���</td><td>���ϸ���</td><td>����������</td><td>�ò�������</td><td>�˻�����</td><td>��ע</td></tr>";
					foreach ($object['ereportequitem'] as $val) {
						$mailStr .= <<<EOT
                            <tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[passNum]</td><td>$val[receiveNum]</td><td>$val[backNum]</td><td>$val[remark]</td></tr>
EOT;
					}
					//��ȡ�����ʼ�������
					$mailArr = $this->getMail_d('purchquality');
					$purchaserIdArr = array_merge($purchaserIdArr, explode(',', $mailArr['sendUserId']));

					$emailDao = new model_common_mail();
					$emailDao->mailClear('OA֪ͨ���ʼ챨��', implode(',', $purchaserIdArr), $mailStr);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����У��
	 * @param $object
	 * @return bool
	 */
	function checkCanBack_d($object) {
		//���¶�Ӧ����Դ��ҵ��
		$applyDao = new model_produce_quality_qualityapply();
        $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();

        if($object['relDocType'] == 'ZJSQDLBF'){
            return $blockeququalityapplyDao->checkCanBack_d($object);
        }else{
            //��Ҫ����ҵ����� - ���������ϸ��
            $checkArr = array();
            //ѭ����ѯ����Դ��id
            foreach ($object['ereportequitem'] as $val) {
                if (isset($checkArr[$val['objType']])) {
                    array_push($checkArr[$val['objType']], $val['objItemId']);
                } else {
                    $checkArr[$val['objType']][0] = $val['objItemId'];
                }
            }

            return $applyDao->checkCanBack_d($checkArr);
        }
	}

	/**
	 * �����ʼ챨��
	 * @param $id
	 * @return bool|int
	 */
	function backReport_d($id) {
		//��ȡ��������
		$obj = $this->get_d($id);
		if ($obj['auditStatus'] != 'YSH' && $obj['auditStatus'] != 'WSH') {
			return false;
		}

		$checkCanBack = $this->checkCanBack_d($obj);
		if ($checkCanBack == false) {
			return -1;
		}

		try {
			$this->start_d();

			if ($obj['auditStatus'] == 'YSH') {
				parent::edit_d(array('id' => $id, 'auditStatus' => 'BC', 'ExaStatus' => WAITAUDIT, 'ExaDT' => ''));

				//�����ʼ�����
				$taskItemDao = new model_produce_quality_qualitytaskitem();
				//�����ʼ�����
				$applyItemDao = new model_produce_quality_qualityapplyitem();
				//���¶�Ӧ����Դ��ҵ��
				$applyDao = new model_produce_quality_qualityapply();
				//�������뵥����
				$applyArr = array();

				//����ɹ�ԱID
				$purchaserIdArr = array();

				//ѭ������
				foreach ($obj['ereportequitem'] as $val) {
					//��ѯ�����Դ��id
					$taskObj = $taskItemDao->find(array('id' => $val['relItemId']), null, 'applyItemId,applyId,checkedNum');
					//�����ʼ�������ϸ����
					$applyItemDao->updateUndeal_d($taskObj['applyItemId'], $val['thisCheckNum'], $val['thisCheckNum']);

					//Դ������
					if (!in_array($taskObj['applyId'], $applyArr)) {
						array_push($applyArr, $taskObj['applyId']);
					}

					//���뵥Դ������
                    if($obj['relDocType'] != 'ZJSQDLBF') {// ���ϱ���������ͨ����������
                        $relClass = $applyDao->getStrategy_d($taskObj['applyId']);
                        $relClassM = new $relClass ();//����ʵ��
                        $applyObj = $applyDao->get_d($taskObj['applyId']);
                        $applyDao->dealRelInfoAtUnconfirm($taskObj['applyId'], $taskObj['applyItemId'], $val['thisCheckNum']);
                        $applyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
                    }

					if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
						$checkStatus = "BFJY";
					} else {
						$checkStatus = "";
					}

					//����״̬�Լ��ϸ�����
					$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => 0);
					$taskItemDao->updateById($qualityTaskItem);

					//������ɹ�Ա����
					if (!in_array($val['purchaserId'], $purchaserIdArr)) {
						array_push($purchaserIdArr, $val['purchaserId']);
					}
				}

				//���¼�������״̬
				$qualityTaskDao = new model_produce_quality_qualitytask();
				$qualityTaskDao->renewStatus_d($obj['mainId']);

                if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:���ϱ��ϳ�������ͬʱ������������״̬Ϊ�ʼ���
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityapplyArr = $qualityapplyDao->find(array("id" => $obj['applyId']));
                    if($qualityapplyArr){
                        $stockoutUpdateArr = array("id"=>$qualityapplyArr['relDocId'],"docStatus"=>"ZJZ");
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);
                    }

                    // ����Ƿ���Ҫ����������¼
                    $auditTask = $this->_db->getArray("select task from wf_task where code='oa_produce_quality_ereport' and pid = {$id};");
                    if($auditTask){
                        // �����Ӧ�����������¼
                        $taskId = $auditTask[0]['task'];
                        $sql = "delete from wf_task where task = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step_partent where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                    }
                }

				//��������������飬��ͳһ�������뵥״̬
				if ($applyArr) {
					foreach ($applyArr as $v) {
						$applyDao->renewStatus_d($v);
					}

					//�����ʼ�����
					if (!empty($purchaserIdArr)) {
						$this->mailDeal_d('qualityapplyBack', implode(',', $purchaserIdArr), array('id' => $id, 'examineUserName' => $obj['examineUserName'], 'docCode' => $obj['docCode']));
					}
				}
			} else {
				//ɸ������
				$ereportequitem = $obj['ereportequitem'];
				unset($obj['ereportequitem']);

				unset($obj['ereportitem']);

				//���ش���
				$obj['auditStatus'] = 'BC';
				parent::edit_d($obj);

				//ʵ�����������
				$qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

				//�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
				//���������嵥�ļ���״̬
				foreach ($ereportequitem as $val) {
					$taskObj = $qualityTaskItemDao->find(array('id' => $val['relItemId']), null, 'checkedNum');
					if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
						$checkStatus = "BFJY";
					} else {
						$checkStatus = "";
					}
					//����״̬�Լ��ϸ�����
					$qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $val['qualitedNum']);
					$qualityTaskItemDao->updateById($qualityTaskItem);
				}

				//���¼�������״̬
				$qualityTaskDao = new model_produce_quality_qualitytask();
				$qualityTaskDao->renewStatus_d($obj['mainId']);

                if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:���ϱ��ϳ�������ʱͬ�¸�����������״̬Ϊ�ʼ���
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityapplyArr = $qualityapplyDao->find(array("id" => $obj['applyId']));
                    if($qualityapplyArr){
                        $stockoutUpdateArr = array("id"=>$qualityapplyArr['relDocId'],"docStatus"=>"ZJZ");
                        $stockoutDao = new model_stock_outstock_stockout();
                        $stockoutDao->updateById($stockoutUpdateArr);
                    }

                    // ����Ƿ���Ҫ����������¼
                    $auditTask = $this->_db->getArray("select task from wf_task where code='oa_produce_quality_ereport' and pid = {$id};");
                    if($auditTask){
                        // �����Ӧ�����������¼
                        $taskId = $auditTask[0]['task'];
                        $sql = "delete from wf_task where task = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step_partent where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                        $sql = "delete from flow_step where Wf_task_ID = {$taskId};";
                        $this->_db->query($sql);
                    }
                }
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ر���
	 * @param $id
	 * @param $reason
	 * @return bool
	 */
	function rejectReport_d($id, $reason) {
		try {
			$this->start_d();

			//��ȡ��������
			$obj = $this->get_d($id);

            if($obj['relDocType'] == 'ZJSQDLBF'){// PMS2386:���ϱ��ϲ��ر����൱��������ش���
                $this->dealAfterDldfProcess_d($id, $obj, 'sp_disPass');
            }else{
                //ɸ������
                $ereportequitem = $obj['ereportequitem'];
                unset($obj['ereportequitem']);

                unset($obj['ereportitem']);

                //���ش���
                $obj['auditStatus'] = 'BH';
                parent::edit_d($obj);

                //ʵ�����������
                $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();

                //�����ύֵ����״̬ -- �����ύ״̬�ĵ������⴦��
                //���������嵥�ļ���״̬
                foreach ($ereportequitem as $val) {
                    $taskObj = $qualityTaskItemDao->find(array('id' => $val['relItemId']), null, 'checkedNum');
                    if ($val['thisCheckNum'] != $taskObj['checkedNum']) {
                        $checkStatus = "BFJY";
                    } else {
                        $checkStatus = "";
                    }
                    //����״̬�Լ��ϸ�����
                    $qualityTaskItem = array("id" => $val['relItemId'], "checkStatus" => $checkStatus, 'standardNum' => $val['qualitedNum']);
                    $qualityTaskItemDao->updateById($qualityTaskItem);
                }

                //���¼�������״̬
                $qualityTaskDao = new model_produce_quality_qualitytask();
                $qualityTaskDao->renewStatus_d($obj['mainId']);

                //�����ʼ�����
                if ($obj['examineUserName']) {
                    $mailStr = "��ã���" . $_SESSION['USERNAME'] . "���Ѿ��������ʼ챨�桾" . $obj['docCode'] . "��������ԭ��Ϊ ��<br/>"
                        . $reason . "<br/>"
                        . "�����豸��ϸ���£�<br/>" .
                        "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>���ϱ���</td><td>��������</td><td>����ͺ�</td><td>��Ӧ��</td><td>��������</td><td>��λ</td><td>����ʱ��</td><td>����Դ����</td><td>������</td><td>�����̶�</td><td>�ϸ���</td><td>���ϸ���</td><td>��ע</td></tr>";
                    foreach ($ereportequitem as $val) {
                        $mailStr .= <<<EOT
						<tr><td>$val[productCode]</td><td>$val[productName]</td><td>$val[pattern]</td><td>$val[supplierName]</td><td>$val[supportNum]</td><td>$val[unitName]</td><td>$val[supportTime]</td><td>$val[objCode]</td><td>$val[purchaserName]</td><td>$val[priorityName]</td><td>$val[qualitedNum]</td><td>$val[produceNum]</td><td>$val[remark]</td></tr>
EOT;
                    }
                    $emailDao = new model_common_mail();
                    $emailDao->mailClear('OA֪ͨ���ʼ챨�沵��', $obj['examineUserId'], $mailStr);
                }
            }

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}