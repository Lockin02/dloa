<?php

/**
 * @author chenrf
 * @description:����������÷�̯��ϸ�� Model��(��ʱ������������ͬδ�ύǰ��ŷ�̯����)
 */
class model_contract_other_payablescost extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payablesapply_cost_temp";
		$this->sql_map = "contract/other/payablescostSql.php";
		parent :: __construct();
	}

	/********************* S ���Բ��� ***********************/
	//���ò��Եķ��÷�̯����
	private $initStrategy = array('CWFYFT-03');

	//����ʵ������
	private $relatedCode = array(
		'CWFYFT-01' => '', //��Ա
		'CWFYFT-02' => '', //����
		'CWFYFT-03' => 'esmproject', //������Ŀ
		'CWFYFT-04' => ''  //�з���Ŀ
	);

	/**
	 * �������ͷ���ҵ������
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	//��ͬ��̯���͵��õĲ����ļ�
	private $relatedStrategyArr = array (
		'CWFYFT-01' => '', //��Ա
		'CWFYFT-02' => '', //����
		'CWFYFT-03' => 'esmproject', //������Ŀ
		'CWFYFT-04' => ''  //�з���Ŀ
	);

	/**
	 * �����������ͷ�����
	 */
	public function getClass($objType){
		$rs = isset($this->relatedStrategyArr[$objType]) ? $this->relatedStrategyArr[$objType] : null;
		return $rs;
	}

	/********************* E ���Բ��� ***********************/

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'shareType'
    );

    //���÷�̯��¼״̬
    private $statusArr = array(
    	'δ����','����'
    );

    //���ط�̯��¼״̬
    function rtStatus($val){
		if(isset($this->statusArr[$val])){
			return $this->statusArr[$val];
		}else{
			return $val;
		}
    }

	/**
	 * ���÷�̯����
	 * TODO ���������Ե���δ��ȫʵ�֣�Ŀǰֻʵ�ֹ��̲���
	 */
	function share_d($object){
		if(empty($object)){
			return 'û��¼���̯����';
		}else{
			//����ĸ�������id�ֶ�
			$payapplyId = $object['payapplyId'];
			//����ĸ���������
			$payapplyCode = $object['payapplyCode'];
			//��ȡ����������Ϣ
			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$isEffective = $payablesapplyDao->isEffective_d($payapplyId);
			//id����
			$idArr = array();

			//������Ŀ����
			$esmprojectArr = array();

			try{
				$this->start_d();

				foreach($object['detail'] as $key => $val){
					//������Ϣ
					$object['detail'][$key] = $this->valueDeal_d($object['detail'][$key]);
					$object['detail'][$key] = $this->processDatadict($object['detail'][$key]);
					$object['detail'][$key]['payapplyId'] = $payapplyId;
					$object['detail'][$key]['payapplyCode'] = $payapplyCode;
					$object['detail'][$key]['status'] = $isEffective ? 1 : 0;

					//������ڹ�����Ŀ������Ŀ�������飬�ȴ�����
					if($val['shareType'] == 'CWFYFT-03' && !in_array($val['shareObjCode'],$esmprojectArr)){
						array_push($esmprojectArr,$val['shareObjCode']);
					}

					//�������ԭ��Ŀ��ţ�������Ŀ�������飬�ȴ�����
					if(!empty($val['orgProjectCode'])  && !in_array($val['orgProjectCode'],$esmprojectArr)){
						array_push($esmprojectArr,$val['orgProjectCode']);
					}
				}
				//������������
				$this->saveDelBatch($object['detail']);

				//��ȡ���������̯�����з���
				$allShareMoney = $this->getAllShareMoney_d($payapplyId);

				//�������뵥���ִ���
				$payablesapplyDao->updateShareInfo_d($payapplyId,$allShareMoney);

				//���������Ŀ���鲻Ϊ��,���¸���Ŀ�ķ�̯����
				if(!empty($esmprojectArr)){
					$esmprojectRows = $this->getEffectShareMoney_d($esmprojectArr);
					//ʵ����������Ŀ��
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($esmprojectRows as $key => $val){
						$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);

						$k = array_search( $val['shareObjCode'] ,$esmprojectArr);
						unset($esmprojectArr[$k]);
					}
					//�����Ŀ�������鲻Ϊ�գ�����Ŀ�ķ�̯���ø���Ϊ0
					if(!empty($esmprojectArr)){
						foreach($esmprojectArr as $key => $val){
							$esmprojectDao->updateFeePayables_d($val,0);
						}
					}
				}

				$this->commit_d();
				return true;
			}catch(exception $e){
				$this->rollBack();
				throw $e;
				return false;
			}
		}
	}

	/**
	 * ��Ӧ������ֵ����
	 */
	function valueDeal_d($object){
		if(isset($object['shareType'])){

			switch ($object['shareType']) {
				case 'CWFYFT-01'://����Ϊ��Ա
					$object['userName'] = $object['shareObjName'];
					$object['userId'] = $object['shareObjCode'];
					$object['deptName'] = '';
					$object['deptId'] = '';
					$object['projectId'] = '';
					$object['projectName'] = '';
					$object['projectCode'] = '';
					break;
				case 'CWFYFT-02'://����Ϊ����
					$object['deptId'] = $object['shareObjId'];
					$object['deptName'] = $object['shareObjName'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['projectId'] = '';
					$object['projectName'] = '';
					$object['projectCode'] = '';
					break;
				case 'CWFYFT-03'://������Ŀ
					$object['projectId'] = $object['shareObjId'];
					$object['projectName'] = $object['shareObjName'];
					$object['projectCode'] = $object['shareObjCode'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['deptId'] = '';
					$object['deptName'] = '';
					break;
				case 'CWFYFT-04'://�з���Ŀ
					$object['projectId'] = $object['shareObjId'];
					$object['projectName'] = $object['shareObjName'];
					$object['projectCode'] = $object['shareObjCode'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['deptId'] = '';
					$object['deptName'] = '';
					break;

				default:break;
			}

			return $object;
		}else{
			return $object;
		}
	}

	/**
	 * ��ȡ������������з��÷�̯���
	 */
	function getAllShareMoney_d($payapplyId){
		$this->searchArr = array('payapplyId' => $payapplyId);
		$rs = $this->list_d('count_all');
		if(is_array($rs)){
			return $rs[0]['shareMoney'];
		}else{
			return 0;
		}
	}

	/**
	 * ��ȡ��Ч�ķ��÷�̯��� - ������Ŀ���� (���ݹ�����Ŀ�������)
	 * param array('AA','BB')
	 */
	function getEffectShareMoney_d($projectCodes,$isStatus = true){
		if(is_array($projectCodes)){
			$projectCodes = implode($projectCodes,',');
		}
		$this->searchArr = array(
			'projectCodeArr' => $projectCodes ,
			'shareType' => 'CWFYFT-03'
		);
		if($isStatus){
			$this->searchArr['status'] = 1;
		}

		$this->groupBy = 'projectCode';
		return $this->listBySqlId('count_shareObj');
	}

	/**
	 * ��������ر�ʱ��ʹ���÷�̯��ϢʧЧ
	 */
	function closeShare_d($payapplyId){
		try{
			$this->start_d();

			//��ȡ���������ڵ���Ŀ��
			$esmprojectCodeStr = $this->get_table_fields($this->tbl_name,'shareType ="CWFYFT-03" and payapplyId = '. $payapplyId ,'group_concat(projectCode)');
			$esmprojectArr = explode(',',$esmprojectCodeStr);

			//���÷��÷�̯��ϸ
			$this->update(array('payapplyId' => $payapplyId),array('status' => 0));

			if($esmprojectCodeStr){
				$rs = $this->getEffectShareMoney_d($esmprojectCodeStr);
//				echo "<pre>";
//				print_r($rs);
//				print_r($esmprojectArr);
				if(!empty($rs)){
					//ʵ����������Ŀ��
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($rs as $key => $val){
						$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);

						$k = array_search( $val['shareObjCode'] ,$esmprojectArr);
						unset($esmprojectArr[$k]);
					}
					//�����Ŀ�������鲻Ϊ�գ�����Ŀ�ķ�̯���ø���Ϊ0
					if(!empty($esmprojectArr)){
						foreach($esmprojectArr as $key => $val){
							$esmprojectDao->updateFeePayables_d($val,0);
						}
					}
				}
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * ���ݸ�������id��ȡ���÷�̯��Ϣ
	 */
	function getShareInfo_d($payapplyId){
		$this->searchArr = array('payapplyId' => $payapplyId);
		$this->asc = false;
		return $this->list_d();
	}

	/**************************** ҳ����ʾ���� ******************************/
	/**
	 * �༭���÷�̯
	 */
	function initShareEdit_v($rows){
		if(!empty($rows)){
			$i = 0;
			//���ص��ַ���
			$str = null;
			$datadictArr = $this->getDatadicts ( 'CWFYFT' );

			//��ȡ��������
			$otherdatasDao = new model_common_otherdatas();

			foreach($rows as $key => $val){
				$i ++;
//				print_r($val);
				//��̯����
				$productTypeStr = $this->getDatadictsStr ( $datadictArr ['CWFYFT'],$val['shareType']);

				$feeTypeHidden = 'feeType' . $i . 'Hidden';
				$str.=<<<EOT
					<tr align="center">
						<td>$i</td>
						<td>
					    	<select name="payablescost[detail][$i][shareType]" id="shareType$i" class="txtmiddle" onchange="changeShareType($i,this.value)">$productTypeStr</select>
						</td>
						<td>
							<input type="text" class="txt" name="payablescost[detail][$i][shareObjName]" id="shareObjName$i" value="{$val['shareObjName']}" readonly="readonly"/>
							<input type="text" class="txt" name="payablescost[detail][$i][shareObjCode]" id="shareObjCode$i" value="{$val['shareObjCode']}" readonly="readonly" style="display:none"/>
							<input type="hidden" name="payablescost[detail][$i][shareObjId]" id="shareObjId$i" value="{$val['shareObjId']}"/>
							<input type="hidden" name="payablescost[detail][$i][orgProjectCode]" value="{$val['projectCode']}"/>
						</td>
						<td>
							<select name="payablescost[detail][$i][feeType]" id="feeType$i" class="txtmiddle"><option></option></select>
							<input type="hidden" id="$feeTypeHidden" value="{$val['feeType']}"/>
						</td>
						<td>
							<input type="text" class="txtmiddle formatMoney" name="payablescost[detail][$i][shareMoney]" id="shareMoney$i" value="{$val['shareMoney']}" onblur="reCalMoney()"/>
							<input type="hidden" name="payablescost[detail][$i][id]" id="id$i" value="{$val['id']}"/>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����"/>
						</td>
					</tr>
EOT;
			}
			return $str;
		}else{
			return false;
		}
	}

	/**
	 * �鿴���÷�̯
	 */
	function initShareView_v($rows){
		if(!empty($rows)){
			$i = 0;
			//���ص��ַ���
			$str = null;
			$shareMoneyCount = 0;
			foreach($rows as $key => $val){
				$i ++;
				$shareMoneyCount = bcadd($val['shareMoney'],$shareMoneyCount,2);
				//update chenrf �ӳ�����
				if(trim($val['shareType'])=='�ۺ����')
					$val['shareObjName']='<a href="?model=contract_contract_contract&action=toViewTab&id='.$val['shareObjId'].'" target="_blank">'.$val['shareObjName'].'</a>';
				$str.=<<<EOT
					<tr align="center">
						<td>$i</td>
						<td>{$val['shareTypeName']}</td>
						<td>
							{$val['shareObjName']}
						</td>
						<td>
							{$val['feeType']}
						</td>
						<td>
							<span class="formatMoney">{$val['shareMoney']}</span>
						</td>
					</tr>
EOT;
			}
			$str.=<<<EOT
					<tr class="tr_count" align="center">
						<td></td>
						<td>�ϼ�</td>
						<td>
						</td>
						<td>
						</td>
						<td>
							<span class="formatMoney">$shareMoneyCount</span>
						</td>
					</tr>
EOT;

			return $str;
		}else{
			return "<tr align='center'><td colspan='5'>���޷�̯��ϸ��Ϣ</td></tr>";
		}
	}

	/**************************** S ���뵼������ ***********************/
	/**
	 * ����
	 * @param 1�� id ��2�Ǳ��
	 */
	function excelIn_d($checkType){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();//�����������
		$payablesapplyArr = array();//�������뻺������
		$payablesapplyIndexArr = array();//����������������
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();//�����ֵ����
		$otherdatasDao = new model_common_otherdatas();//�������ݶ���
		$deptArr = array();//���Ż���
		$userArr = array();//��Ա����
		$rdprojectArr = array();//�з���Ŀ����
		$feeTypeArr = array();//�������ͻ���
		$esmprojectDao = new model_engineering_project_esmproject();//������Ŀ����
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[0] = trim($val[0]);
					$val[1] = trim($val[1]);
					$val[2] = trim($val[2]);
					$val[3] = trim($val[3]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3])){
						continue;
					}else{
						echo "<pre>";
//						print_r($val);
						//���ò�������
						$inArr = array();

						//�������������ֶ�
						if($val[0]){
							if(!isset($payablesapplyArr[$val[0]])){
								if($checkType == 1){
									$rs = $payablesapplyDao->find(array('id' => $val[0]));
								}else{
									$rs = $payablesapplyDao->find(array('formNo' => $val[0]));
								}
								if($rs){
									$payablesapplyArr[$val[0]] = $rs;
									array_push($payablesapplyIndexArr,$val[0]);
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�����ڵĸ�������';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							if($payablesapplyArr[$val[0]]['ExaStatus'] != AUDITED || $payablesapplyArr[$val[0]]['status'] == 'FKSQD-04'){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�������뵥״̬��������÷�̯����';
								array_push( $resultArr,$tempArr );
								continue;
							}
							if($payablesapplyArr[$val[0]]['isRed'] == 1){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�˿����벻�ܽ��з��÷�̯';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['payapplyId'] = $payablesapplyArr[$val[0]]['id'];
							$inArr['payapplyCode'] = $payablesapplyArr[$val[0]]['formNo'];
							$inArr['status'] = 1;
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�������������ֶα���';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��̯����
						if($val[1]){
							if(!isset($datadictArr[$val[1]])){
								$rs = $datadictDao->getCodeByName('CWFYFT',$val[1]);
								if(!empty($rs)){
									$shareType = $datadictArr[$val[1]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵķ�̯����:'.$val[1].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$shareType = $datadictArr[$val[1]]['code'];
							}
							$inArr['shareType'] = $shareType;
							$inArr['shareTypeName'] = $val[1];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�������̯����';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��̯����
						if($val[2]){
							switch ($inArr['shareType']) {
								case 'CWFYFT-01':
									if(!isset($userArr[$val[2]])){
										$rs = $otherdatasDao->getUserInfo($val[2]);
										if(!empty($rs)){
											$userArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '��' . $actNum .'������';
											$tempArr['result'] = '����ʧ��!�����ڵ��û�����';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['userName'] = $val[2];
									$inArr['shareObjCode'] = $inArr['userId'] = $userArr[$val[2]]['USER_ID'];
									break;
								case 'CWFYFT-02' :
									if(!isset($deptArr[$val[2]])){
										$rs = $otherdatasDao->getDeptInfo_d($val[2]);
										if(!empty($rs)){
											$deptArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '��' . $actNum .'������';
											$tempArr['result'] = '����ʧ��!�����ڵĲ�������';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['deptName'] = $val[2];
									$inArr['shareObjId'] = $inArr['deptId'] = $deptArr[$val[2]]['DEPT_ID'];
									break;
								case 'CWFYFT-03' :
									if(!isset($esmprojectArr[$val[2]])){
										$rs = $esmprojectDao->find(array('projectCode'=> $val[2]),null,'projectName,id,projectCode');
										if(!empty($rs)){
											$esmprojectArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '��' . $actNum .'������';
											$tempArr['result'] = '����ʧ��!�����ڵĹ�����Ŀ';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['projectName'] = $esmprojectArr[$val[2]]['projectName'];
									$inArr['shareObjCode'] = $inArr['projectCode'] = $val[2];
									$inArr['shareObjId'] = $inArr['projectId'] = $esmprojectArr[$val[2]]['id'];

									break;
								case 'CWFYFT-04' :
									if(!isset($rdprojectArr[$val[2]])){
										$rs = $otherdatasDao->getRdproject($val[2]);
										if(!empty($rs)){
											$rdprojectArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '��' . $actNum .'������';
											$tempArr['result'] = '����ʧ��!�����ڵ��з���Ŀ';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['projectName'] = $rdprojectArr[$val[2]]['projectName'];
									$inArr['shareObjCode'] = $inArr['projectCode'] = $val[2];
									$inArr['shareObjId'] = $inArr['projectId'] = $rdprojectArr[$val[2]]['projectId'];

									break;
								default:break;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�������̯����';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��������
						if($val[3]){
							if(!isset($feeTypeArr[$val[3]])){
								$rs = $otherdatasDao->issetFeeType($val[3]);
								if($rs){
									$feeTypeArr[$val[3]] = $val[3];
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!��ʹ�ñ���ϵͳ�еķ�������';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['feeType'] = $val[3];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�������������';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��̯����
						$shareMoney = $val[4] === '' ? 'NONE' : sprintf("%f",abs(trim($val[4])));
						if($shareMoney != 'NONE'&& $shareMoney != 0){
							$inArr['shareMoney'] = $shareMoney;
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!��̯���ò���Ϊ�ջ�0';
							array_push( $resultArr,$tempArr );
							continue;
						}

						print_r($inArr);

						try{
							$this->start_d();
							$thisShareMoney = bcadd($payablesapplyArr[$val[0]]['shareMoney'],$shareMoney,2);

							if($payablesapplyArr[$val[0]]['payMoney'] < $thisShareMoney){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!��̯�����ѳ�������������';
								array_push( $resultArr,$tempArr );
								continue;
							}

							//��������
							$this->add_d($inArr,true);
							//���¸�������ķ�̯״̬�ͽ��
							if($checkType == 1){
								$payablesapplyDao->updateShareInfo_d($val[0],$thisShareMoney);
							}else{
								$payablesapplyDao->updateShareInfo_d($val[0],$thisShareMoney,'formNo');
							}
							//����̯�����뻺��
							$payablesapplyArr[$val[0]]['shareMoney'] = $thisShareMoney;

							if($inArr['shareType'] == 'CWFYFT-03'){
								$rs = $this->getEffectShareMoney_d($val[2]);
								$esmprojectDao->updateFeePayables_d($val[2],$val['shareMoney']);

								$esmprojectRows = $this->getEffectShareMoney_d($val[2]);
								//ʵ����������Ŀ��
								foreach($esmprojectRows as $key => $val){
									$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);
								}
							}

							$this->commit_d();

							$tempArr['result'] = '����ɹ�';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}catch(exception $e){
							$this->rollBack();
							$tempArr['result'] = '����ʧ��';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}
						array_push( $resultArr,$tempArr );

					}
				}
//				print_r($payablesapplyIndexArr);
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
	}
	/**************************** E ���뵼������ ***********************/
	/**
	 *
	 * ��̯��ϸ��������ʱ������Ϣ��
	 */
	function listCost($payapplyId,$createId){
		$sql=' payapplyId="'.$payapplyId.'" or payapplyId is null and createId= "'.$createId.'"';
		return $this->findAll($sql);
	}
}
?>