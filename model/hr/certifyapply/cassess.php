<?php
/**
 * @author Show
 * @Date 2012��8��23�� ������ 9:40:13
 * @version 1.0
 * @description:��ְ�ʸ�ȼ���֤���۱� Model��
 */
 class model_hr_certifyapply_cassess  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess";
		$this->sql_map = "hr/certifyapply/cassessSql.php";
		parent::__construct ();
	}

	/******************** ������Ϣ *************************/
	//״̬
	function rtStatus_c($status){
		switch($status){
			case 0 : return '����';break;
			case 1 : return '��֤׼����';break;
			case 2 : return '������';break;
			case 3 : return '��ɴ�����';break;
			case 4 : return '���������';break;
			case 5 : return 'ȷ�������';break;
			case 6 : return '��������';break;
			default : return $status;
		}
	}

	//�����Ƿ���
	function rtIsDeal_c($isDeal){
		if($isDeal == 0){
			return 'ok';
		}else{
			return 'no';
		}
	}

	//���������˺͸����ˣ�����ͳ���п�����
	function rtColspan_c($managerId,$memberId){
		$thisCols = 1;
		if($managerId){
			$thisCols ++ ;
		}
		if($memberId){
			$memberArr = explode(',',$memberId);
			$thisCols = $thisCols + count($memberArr);
		}
		return $thisCols;
	}

	/******************** �������ݻ�ȡ **********************/
	/**
	 * ��ȡ��ְ������Ϣ
	 */
	function getApplyInfo_d($applyId){
		$certifyapplyDao = new model_hr_personnel_certifyapply();
		return $certifyapplyDao->find(array('id' => $applyId));
	}

	/**
	 *  ��ȡ��ְ�ʸ�ƥ��ģ��id
	 */
	function getTemplate_d($careerDirection,$baseLevel,$baseGrade){
		$certifytemplateDao = new model_hr_baseinfo_certifytemplate();
		return $certifytemplateDao->find(
			array('careerDirection' => $careerDirection, 'baseLevel' => $baseLevel , 'baseGrade' => $baseGrade)
		);
	}


	/******************* ��ɾ�Ĳ� *********************************/

	//��дaddApply_d
	function add_d($object){
        //��ȡ��ΪҪ��
        $cdetail = $object['cdetail'];
        unset($object['cdetail']);

		try{
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			$object['ExaStatus'] = WAITAUDIT;

			$newId =  parent::add_d($object,true);

            //���������Ա
            $cdetailexpDao = new model_hr_certifyapply_cdetail();
            $cdetailexpDao->createBatch($cdetail,array('assessId' => $newId));

            //����������󣬸�����ְ�����״̬
			$certifyapplyDao = new model_hr_personnel_certifyapply();
			$certifyapplyDao->updateStatus_d($object['applyId'],3);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//edit
	function edit_d($object){
//		echo "<pre>";print_r($object);die();
        //��ȡ��ΪҪ��
        $cdetail = $object['cdetail'];
        unset($object['cdetail']);

		try{
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			parent::edit_d($object,true);

            //���������Ա
            $cdetailexpDao = new model_hr_certifyapply_cdetail();
            $cdetailexpDao->saveDelBatch($cdetail);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ָ����ί
	 */
	function setManager_d($object){
		$sql = "update ".$this->tbl_name." set managerId = '". $object['managerId']. "',managerName = '". $object['managerName']. "',memberId = '". $object['memberId']. "',memberName = '". $object['memberName']. "' where id in (". $object['id']. ") ";
		return $this->_db->query($sql);
	}

	/**
	 * ����¼��
	 */
	function inScore_d($object){
//		echo "<pre>";print_r($object);die();
		//���۱���ϸ
		$cdetail = $object['cdetail'];
		unset($object['cdetail']);

		//��ί������ϸ
		$scoredetail = $object['scoredetail'];
		unset($object['scoredetail']);

		try{
			$this->start_d();
			//�޸���ְ���۱�״̬Ϊ���������
			$object['status'] = '4';

			parent::edit_d($object,true);

			//������ϸ����
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->saveDelBatch($cdetail);

			//��ί������ϸ
			$scoredetailDao = new model_hr_certifyapply_scoredetail();
			//��ί��������
			$scoreDao = new model_hr_certifyapply_score();
			$cacheArr = array();
			foreach($scoredetail as $key => $val){
				$cacheArr = $val;
				$scoreArr = array_pop($cacheArr);

				//�����ϸ�����������ֱ�id�����½����ֱ�,����ֱ�Ӹ���
				if(empty($scoreArr['scoreId'])){
					$scoreDao->createScoreInfo_d($object,$val);
				}else{
					$scoredetailDao->saveDelBatch($val);
					//������Ӧ���ݵ��ܵ÷�
					$scoreDao->updateScore_d($scoreArr['scoreId']);
				}
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/********************** ҵ���߼� ********************/
	/**
	 * �ύ��֤׼��
	 */
	function handUp_d($id){
		try{
			$updateArr = array('status' => 1);
			$updateArr = $this->addUpdateInfo($updateArr);

			$this->update(array('id' => $id),$updateArr);
			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 * ��ȡѡ�е��û���Ϣ
	 */
	function getAssessUser_d($ids){
		$this->searchArr = array('ids' => $ids);
		$rs = $this->list_d();
		if($rs){

			//������Ա��Ϣ
			$idArr = array();
			$nameArr = array();
			foreach($rs as $key => $val){
				array_push($idArr,$val['userAccount']);
				array_push($nameArr,$val['userName']);
			}

			return array(
				'userName' => implode($nameArr,',')
			);
		}else{
			return array(
				'userName' => ''
			);
		}
	}

	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

	 		//���뵥ʵ����
	 		$certifyapplyDao = new model_hr_personnel_certifyapply();
	 		$certifyapplyDao->updateStatus_d($object['applyId'],6);

			return 1;
	 	}
	 	return 1;
	}

	/**
	 * ����id ��ȡ�б���Ϣ - ����֤������ʹ��
	 */
	function getListForAudit_d($ids){
		$this->searchArr = array(
			'ids' => $ids
		);
		return $this->list_d('select_forresult');
	}

	/**
	 * ��������״̬
	 */
	function updateStatus_d($id,$status){
		try{
			$updateArr = array('status' => $status);
			$updateArr = $this->addUpdateInfo($updateArr);

			$this->update(array('id'=>$id),$updateArr);
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/************************* ҳ����ʾ���� *****************/
	/**
	 * ��ͷ����
	 */
	function initHead_v($object){
		//��ȡ����ί����Ϣ
		$managerName = $object['managerName'];
		//��ȡ������ί��Ϣ
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;

		$beforeHead =<<<EOT
			<tr class='main_tr_header'>
				<th width='50'>���</th>
				<th width='150'>��ΪҪ��</th>
				<th width='80'>Ȩ��</th>
EOT;
		//��������ί
		if($managerName){
			$beforeHead .= "<th title='����ί'><span class='red'>$managerName</span></th>";
		}
		//������ί
		if($memberNameArr){
			foreach($memberNameArr as $val){
				$beforeHead .= "<th title='������ί'><span class='blue'>$val</span></th>";
			}
		}
		$afterHead =<<<EOT
			<th>����ί��<br/>ƽ���÷�</th><th>��Ȩ�÷�</th><th>��������ί<br/>ƽ���ֲַ�</th><th>��ί��<br/>��ֲ�</th><th>��ί���<br/>�ֲ��</th>
EOT;


		$head = $beforeHead . $afterHead.  "</tr>";

		return $head;
	}

	/**
	 *  �����ݹ���
	 */
	function initBody_v($object){
		//��ȡ��Ҫ����Ϣ
		$managerId = $object['managerId'];
		$managerName = $object['managerName'];
		//��ȡ��Ҫ����Ϣ
		$memberId = $object['memberId'];
		$memberIdArr = explode(',',$memberId) ;
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;
		$tBody = "";
		$i = 0;

		//ʵ�����÷ֻ������ϸ
		$cdetailDao = new model_hr_certifyapply_cdetail();
		$cdetailArr = $cdetailDao->getCdetail_d($object['id']);

		//ʵ���������ϸ
		$scoredetailDao = new model_hr_certifyapply_scoredetail();
		$scoredetailArr = $scoredetailDao->getScoreDetail_d($object['id']);

		if($cdetailArr){
			//�����������
			foreach($cdetailArr as $key => $val){
				$i++;
				$tr_class = $i%2 == 0 ? 'tr_odd' : 'tr_even';
				//ѡ���¼�����
				if($val['isDeal'] == 0){
					$selected1 = "";
					$selected0 = "selected";
				}else{
					$selected1 = "selected";
					$selected0 = "";
				}
				$tBody.=<<<EOT
					<tr class="$tr_class">
						<td width='50'>$i</td>
						<td width='150'>$val[detailName]</td>
						<td>$val[weights] %</td>
EOT;
				//��������ί
				if($managerId){
					//���ֻ�ȡ
					$score = isset($scoredetailArr[$managerId][$val['detailId']]) ? $scoredetailArr[$managerId][$val['detailId']]['score'] : 0;
					$scoredetailId = isset($scoredetailArr[$managerId][$val['detailId']]) ? $scoredetailArr[$managerId][$val['detailId']]['id'] : '';
					$scoreId = isset($scoredetailArr[$managerId][$val['detailId']]) ? $scoredetailArr[$managerId][$val['detailId']]['scoreId'] : '';

					if($scoredetailId){
						$tBody.=<<<EOT
							<td>
								<input type="text" name="cassess[scoredetail][$managerId][$val[detailId]][score]" id="mainScore$i" class="txtshort" value="$score" onblur="countAll($i)"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][id]" class="txtshort" value="$scoredetailId"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][scoreId]" class="txtshort" value="$scoreId"/>
							</td>
EOT;
					}else{
						$tBody.=<<<EOT
							<td>
								<input type="text" name="cassess[scoredetail][$managerId][$val[detailId]][score]" id="mainScore$i" class="txtshort" value="$score" onblur="countAll($i)"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][id]" class="txtshort" value="$scoredetailId"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][managerId]" class="txtshort" value="$managerId"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][managerName]" class="txtshort" value="$managerName"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][moduleId]" class="txtshort" value="$val[moduleId]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][moduleName]" class="txtshort" value="$val[moduleName]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][detailId]" class="txtshort" value="$val[detailId]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][detailName]" class="txtshort" value="$val[detailName]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][weights]" class="txtshort" value="$val[weights]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][cdetailId]" class="txtshort" value="$val[id]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][assessId]" class="txtshort" value="$object[id]"/>
								<input type="hidden" name="cassess[scoredetail][$managerId][$val[detailId]][scoreId]" class="txtshort" value="$scoreId"/>
							</td>
EOT;
					}
				}
				//������ί
				if($memberIdArr){
					foreach($memberIdArr as $k => $v){
						//���ֻ�ȡ
						$score = isset($scoredetailArr[$v][$val['detailId']]) ? $scoredetailArr[$v][$val['detailId']]['score'] : 0;
						$scoredetailId = isset($scoredetailArr[$v][$val['detailId']]) ? $scoredetailArr[$v][$val['detailId']]['id'] : '';
						$scoreId = isset($scoredetailArr[$v][$val['detailId']]) ? $scoredetailArr[$v][$val['detailId']]['scoreId'] : '';

						$scoreInputId = 'memberScore'.$i."_".$v;
						if($scoredetailId){
							$tBody.=<<<EOT
								<td>
									<input type="text" name="cassess[scoredetail][$v][$val[detailId]][score]" id="$scoreInputId" class="txtshort" value="$score" onblur="countAll($i)"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][id]" class="txtshort" value="$scoredetailId"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][scoreId]" class="txtshort" value="$scoreId"/>
								</td>
EOT;
						}else{
							$tBody.=<<<EOT
								<td>
									<input type="text" name="cassess[scoredetail][$v][$val[detailId]][score]" id="$scoreInputId" class="txtshort" value="$score" onblur="countAll($i)"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][id]" class="txtshort" value="$scoredetailId"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][managerId]" class="txtshort" value="$v"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][managerName]" class="txtshort" value="$memberNameArr[$k]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][moduleId]" class="txtshort" value="$val[moduleId]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][moduleName]" class="txtshort" value="$val[moduleName]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][detailId]" class="txtshort" value="$val[detailId]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][detailName]" class="txtshort" value="$val[detailName]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][weights]" class="txtshort" value="$val[weights]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][cdetailId]" class="txtshort" value="$val[id]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][assessId]" class="txtshort" value="$object[id]"/>
									<input type="hidden" name="cassess[scoredetail][$v][$val[detailId]][scoreId]" class="txtshort" value="$scoreId"/>
								</td>
EOT;
						}
					}
				}
				$tBody.=<<<EOT
						<td>
							<input type="text" name="cassess[cdetail][$i][averageScore]" id="averageScore$i" class="txtshort" value="$val[averageScore]"/>
						</td>
						<td>
							<input type="text" name="cassess[cdetail][$i][weightScore]" id="weightScore$i" class="txtshort" value="$val[weightScore]"/>
						</td>
						<td>
							<input type="text" name="cassess[cdetail][$i][averageDifference]" id="averageDifference$i" class="txtshort" value="$val[averageDifference]"/>
						</td>
						<td>
							<input type="text" name="cassess[cdetail][$i][maxDifference]" id="maxDifference$i" class="txtshort" value="$val[maxDifference]"/>
							<input type="hidden" name="cassess[cdetail][$i][id]" value="$val[id]"/>
							<input type="hidden" name="cassess[cdetail][$i][weights]" id="weights$i" value="$val[weights]"/>
						</td>
						<td>
							<select name="cassess[cdetail][$i][isDeal]" class="txtshort">
								<option value="0" $selected0>ok</option>
								<option value="1" $selected1>no</option>
							</select>
						</td>
					</tr>
EOT;
			}
		}

		return array($tBody,$i);
	}

	/**
	 *  �����ݹ���
	 */
	function initBodyView_v($object){
		//��ȡ��Ҫ����Ϣ
		$managerId = $object['managerId'];
		$managerName = $object['managerName'];
		//��ȡ��Ҫ����Ϣ
		$memberId = $object['memberId'];
		$memberIdArr = explode(',',$memberId) ;
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;
		$tBody = "";
		$i = 0;

		//ʵ�����÷ֻ������ϸ
		$cdetailDao = new model_hr_certifyapply_cdetail();
		$cdetailArr = $cdetailDao->getCdetail_d($object['id']);

		//ʵ���������ϸ
		$scoredetailDao = new model_hr_certifyapply_scoredetail();
		$scoredetailArr = $scoredetailDao->getScoreDetail_d($object['id']);

		if($cdetailArr){
			//�����������
			foreach($cdetailArr as $key => $val){
				$i++;
				$tr_class = $i%2 == 0 ? 'tr_odd' : 'tr_even';

				$isDeal = $this->rtIsDeal_c($val['isDeal']);
				$tBody.=<<<EOT
					<tr class="$tr_class">
						<td width='50'>$i</td>
						<td width='150'>$val[detailName]</td>
						<td>$val[weights] %</td>
EOT;
				//��������ί
				if($managerId){
					//���ֻ�ȡ
					$score = isset($scoredetailArr[$managerId][$val['detailId']]) ? $scoredetailArr[$managerId][$val['detailId']]['score'] : 0;
					$tBody.=<<<EOT
						<td width="8%">
							$score
						</td>
EOT;
				}
				//������ί
				if($memberIdArr){
					foreach($memberIdArr as $k => $v){
						//���ֻ�ȡ
						$score = isset($scoredetailArr[$v][$val['detailId']]) ? $scoredetailArr[$v][$val['detailId']]['score'] : 0;
						$tBody.=<<<EOT
							<td width="70">
								$score
							</td>
EOT;
					}
				}
				$tBody.=<<<EOT
						<td>
							$val[averageScore]
						</td>
						<td>
							$val[weightScore]
						</td>
						<td>
							$val[averageDifference]
						</td>
						<td>
							$val[maxDifference]
						</td>
						<td>
							$isDeal
						</td>
					</tr>
EOT;
			}
		}

		return $tBody;
	}
}
?>