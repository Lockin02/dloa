<?php
/**
 * @author Show
 * @Date 2012��8��28�� ���ڶ� 11:17:10
 * @version 1.0
 * @description:��ְ�ʸ���֤���۽����˱� Model��
 */
 class model_hr_certifyapply_certifyresult  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyresult";
		$this->sql_map = "hr/certifyapply/certifyresultSql.php";
		parent::__construct ();
	}

	//״̬
	function rtStatus_c($status){
		switch($status){
			case 0 : return '����';break;
			case 1 : return '������';break;
			case 2 : return '���';break;
			default : return $status;
		}
	}

	/************************�ⲿ���ݻ�ȡ *****************/
	/**
	 * ��ȡ����һ����֤��˱�
	 */
	function getOneAssess_d($assessIds){
		//��ȡ��˱�id
		$assessIdArr = explode(',',$assessIds);

		//ȡ��
		$assessDao = new model_hr_certifyapply_cassess();
		$assessArr = $assessDao->find(array('id' => $assessIdArr[0]));
		return $assessArr;
	}

	/********************* ��ɾ�Ĳ� ***********************/

	//��дadd_d
	function add_d($object){
//		echo "<pre>";print_r($object);die();
		//ȡ���ӱ�����
		$certifyresultdetailArr = $object['certifyresultdetail'];
		unset($object['certifyresultdetail']);

		try{
			$this->start_d();
			//����ֵ����
			$object['ExaStatus'] = WAITAUDIT;

			//����
			$newId = parent::add_d($object,true);

			//�ӱ����ݲ���
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$addInfo = array('resultId' => $newId,'nowDirectionName' =>$object['careerDirectionName'] ,'nowDirection' => $object['careerDirection']);
			$certifyresultdetailDao->batchAdd_d($certifyresultdetailArr,$addInfo);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	//��д�༭
	function edit_d($object){
//		echo "<pre>";print_r($object);die();
		//ȡ���ӱ�����
		$certifyresultdetailArr = $object['certifyresultdetail'];
		unset($object['certifyresultdetail']);

		try{
			$this->start_d();
			//����ֵ����
			$object['ExaStatus'] = WAITAUDIT;

			//����
			$newId = parent::edit_d($object,true);

			//�ӱ����ݲ���
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$certifyresultdetailDao->saveDelBatch($certifyresultdetailArr);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ɾ������
	 */
	function deletes_d($id) {
		try {
			$this->start_d();

			//ȡ��
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$certifyresultdetail = $certifyresultdetailDao->getList_d($id);

			//��ԭ���۱�״̬
			$assessDao = new model_hr_certifyapply_cassess();
			foreach($certifyresultdetail as $key => $val){
				$assessDao->updateStatus_d($val['assessId'],4);
			}

			//ɾ����¼
			$this->deletes ( $id );

			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	/************************ ҵ���߼� *********************/
	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

	 		//ʵ�����ӱ�
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$certifyresultdetail = $certifyresultdetailDao->getList_d($objId);


			try{
				$this->start_d();

				//��������״̬
				$updateArr = array('status' => 2);
				$updateArr = $this->addUpdateInfo($updateArr);
				$this->update(array('id' => $objId),array('status' => 2));

				if($certifyresultdetail){
			 		//ʵ�������µ�����Ϣ
			 		$personnelDao = new model_hr_personnel_personnel();
			 		//ʵ������֤���۱�
					$assessDao = new model_hr_certifyapply_cassess();
					//ʵ������ν����
					$certifyTitleDao = new model_hr_baseinfo_certifytitle();
					//��ְ�ʸ�����
					$certifyApplyDao = new model_hr_personnel_certifyapply();

					//��������
					$cerTitleArr = $cerTitleNameArr = array();

					foreach($certifyresultdetail as $key => $val){
						//���۱�״̬����
						$assessDao->updateStatus_d($val['assessId'],6);

						//��ν��ȡ
						if(isset($cerTitleArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']])){
							$thisCerTitle = $cerTitleArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']];
							$thisCerTitleName = $cerTitleNameArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']];
						}else{
							$cerTitleArr = $certifyTitleDao->find(array('careerDirection' => $val['nowDirection'],'baseLevel'=>$val['finalLevel'],'baseGrade'=>$val['finalGrade']),null,'id,titleName');
							$thisCerTitleName = $cerTitleArr['titleName'];
							$thisCerTitle = $cerTitleArr['id'];
						}

						//��ְ�ʸ�����״̬����
						$certifyApply = array(
							'finalTitle' => $thisCerTitle,
							'finalTitleName' => $thisCerTitleName,
							'finalScore' => $val['score'],
							'status' => 10,
							'finalCareer' => $val['nowDirection'],
							'finalCareerName' => $val['nowDirectionName'],
							'finalLevel' => $val['finalLevel'],
							'finalLevelName' => $val['finalLevelName'],
							'finalGrade' => $val['finalGrade'],
							'finalGradeName' => $val['finalGradeName'],
							'finalDate' => day_date,
							'id' => $val['applyId']
						);
						$certifyApplyDao->editConfirm_d($certifyApply);

						//������Ϣ����
						$personelArr = array(
							'nowTitle' => $thisCerTitle,
							'nowTitleName' => $thisCerTitleName,
							'nowDirectionName' => $val['nowDirectionName'],
							'nowDirection' => $val['nowDirection'],
							'nowLevel' => $val['finalLevel'],
							'nowLevelName' => $val['finalLevelName'],
							'nowGrade' => $val['finalGrade'],
							'nowGradeName' => $val['finalGradeName'],
							'lastCertifyDate' => day_date
						);
						$personnelDao->updatePersonnel_d($val['userAccount'],$personelArr);

//						echo "<pre>";
//						print_r($personelArr);
//						die();
					}
					$this->commit_d();
				}
			}catch(exception $e){
				$this->rollBack();
			}
//            die();
			return 1;
	 	}
	 	return 1;
	}

	/************************ ҳ����Ⱦ *********************/
	/**
	 * ��Ⱦ�����ʼҳ��
	 */
	function initBodyAdd_v($assessIds){
		//ȡ��
		$assessDao = new model_hr_certifyapply_cassess();
		$assessArr = $assessDao->getListForAudit_d($assessIds);
//		echo "<pre>";
//		print_r($assessArr);

		if($assessArr){
			$str = "";
			$i = 0;
			$datadictLevel = $this->getDatadicts ( 'HRRZJB' );
			$datadictGrade = $this->getDatadicts ( 'HRRZZD' );
			foreach($assessArr as $key => $val){
				$i++;
				$thisGrade = $this->getDatadictsStr ( $datadictGrade ['HRRZZD'],$val['baseGrade']);
				$thisLevel = $this->getDatadictsStr ( $datadictLevel ['HRRZJB'],$val['baseLevel']);
				$str.=<<<EOT
					<tr>
						<tr>
							<td>$i</td>
							<td width='80'>
								$val[userName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][applyId]" value="$val[applyId]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][assessId]" value="$val[id]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userName]" value="$val[userName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userAccount]" value="$val[userAccount]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userNo]" value="$val[userNo]"/>
							</td>
							<td width='80'>
								$val[deptName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][deptName]" value="$val[deptName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][deptId]" value="$val[deptId]"/>
							</td>
							<td width='80'>
								$val[jobName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][jobName]" value="$val[jobName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][jobId]" value="$val[jobId]"/>
							</td>
							<td width='80'>
								$val[highEducationName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][highEducationName]" value="$val[highEducationName]"/>
							</td>
							<td width='80'>
								$val[graduationDate]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][graduationDate]" value="$val[graduationDate]"/>
							</td>
							<td width='80'>$val[entryDate]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][entryDate]" value="$val[entryDate]"/>
							</td>
							<td width='50'>$val[nowLevelName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowLevelName]" value="$val[nowLevelName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowLevel]" value="$val[nowLevel]"/>
							</td>
							<td width='50'>$val[nowGradeName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowGradeName]" value="$val[nowGradeName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowGrade]" value="$val[nowGrade]"/>
							</td>
							<td width='60'>$val[scoreAll]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][score]" value="$val[scoreAll]"/>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][baseLevel]">
									$thisLevel
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][baseGrade]">
									$thisGrade
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][finalLevel]">
									$thisLevel
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][finalGrade]">
									$thisGrade
								</select>
							</td>
							<td>
								<input type="text" class="txtmiddle" name="certifyresult[certifyresultdetail][$i][remark]"/>
							</td>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * ��Ⱦ��˱༭ҳ
	 */
	function initBodyEdit_v($id){
		//ȡ��
		$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
		$certifyresultdetail = $certifyresultdetailDao->getList_d($id);
//		echo "<pre>";
//		print_r($assessArr);

		if($certifyresultdetail){
			$str = "";
			$i = 0;
			$datadictLevel = $this->getDatadicts ( 'HRRZJB' );
			$datadictGrade = $this->getDatadicts ( 'HRRZZD' );
			foreach($certifyresultdetail as $key => $val){
				$i++;
//				echo "<pre>";
//				print_r($val);
				$baseLevel = $this->getDatadictsStr ( $datadictLevel ['HRRZJB'],$val['baseLevel']);
				$baseGrade = $this->getDatadictsStr ( $datadictGrade ['HRRZZD'],$val['baseGrade']);
				$finalLevel = $this->getDatadictsStr ( $datadictLevel ['HRRZJB'],$val['finalLevel']);
				$finalGrade = $this->getDatadictsStr ( $datadictGrade ['HRRZZD'],$val['finalGrade']);
				$str.=<<<EOT
					<tr>
						<tr>
							<td>$i</td>
							<td width='80'>
								$val[userName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][id]" value="$val[id]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][applyId]" value="$val[applyId]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][assessId]" value="$val[assessId]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userName]" value="$val[userName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userAccount]" value="$val[userAccount]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][userNo]" value="$val[userNo]"/>
							</td>
							<td width='80'>
								$val[deptName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][deptName]" value="$val[deptName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][deptId]" value="$val[deptId]"/>
							</td>
							<td width='80'>
								$val[jobName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][jobName]" value="$val[jobName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][jobId]" value="$val[jobId]"/>
							</td>
							<td width='80'>
								$val[highEducationName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][highEducationName]" value="$val[highEducationName]"/>
							</td>
							<td width='80'>
								$val[graduationDate]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][graduationDate]" value="$val[graduationDate]"/>
							</td>
							<td width='80'>$val[entryDate]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][entryDate]" value="$val[entryDate]"/>
							</td>
							<td width='50'>$val[nowLevelName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowLevelName]" value="$val[nowLevelName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowLevel]" value="$val[nowLevel]"/>
							</td>
							<td width='50'>$val[nowGradeName]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowGradeName]" value="$val[nowGradeName]"/>
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][nowGrade]" value="$val[nowGrade]"/>
							</td>
							<td width='60'>$val[score]
								<input type="hidden" name="certifyresult[certifyresultdetail][$i][score]" value="$val[score]"/>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][baseLevel]">
									$baseLevel
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][baseGrade]">
									$baseGrade
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][finalLevel]">
									$finalLevel
								</select>
							</td>
							<td width='50'>
								<select class="txtshort" name="certifyresult[certifyresultdetail][$i][finalGrade]">
									$finalGrade
								</select>
							</td>
							<td>
								<input type="text" class="txtmiddle" name="certifyresult[certifyresultdetail][$i][remark]" value="$val[remark]"/>
							</td>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * ��Ⱦ�����ʼҳ��
	 */
	function initBodyView_v($id){
		//ȡ��
		$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
		$certifyresultdetail = $certifyresultdetailDao->getList_d($id);
//		echo "<pre>";
//		print_r($assessArr);

		if($certifyresultdetail){
			$str = "";
			$i = 0;
			foreach($certifyresultdetail as $key => $val){
				$i++;
				$str.=<<<EOT
					<tr>
						<tr>
							<td>$i</td>
							<td width='80'>
								$val[userName]
							</td>
							<td width='80'>
								$val[deptName]
							</td>
							<td width='80'>
								$val[jobName]
							</td>
							<td width='80'>
								$val[highEducationName]
							</td>
							<td width='80'>
								$val[graduationDate]
							</td>
							<td width='80'>$val[entryDate]
							</td>
							<td width='50'>$val[nowLevelName]
							</td>
							<td width='50'>$val[nowGradeName]
							</td>
							<td width='60'>$val[score]
							</td>
							<td width='50'>
								$val[baseLevelName]
							</td>
							<td width='50'>
								$val[baseGradeName]
							</td>
							<td width='50'>
								$val[finalLevelName]
							</td>
							<td width='50'>
								$val[finalGradeName]
							</td>
							<td>
								$val[remark]
							</td>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}
}
?>