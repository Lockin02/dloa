<?php
/**
 * @author Show
 * @Date 2012年8月28日 星期二 11:17:10
 * @version 1.0
 * @description:任职资格认证评价结果审核表 Model层
 */
 class model_hr_certifyapply_certifyresult  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyresult";
		$this->sql_map = "hr/certifyapply/certifyresultSql.php";
		parent::__construct ();
	}

	//状态
	function rtStatus_c($status){
		switch($status){
			case 0 : return '保存';break;
			case 1 : return '审批中';break;
			case 2 : return '完成';break;
			default : return $status;
		}
	}

	/************************外部数据获取 *****************/
	/**
	 * 获取其中一条认证审核表
	 */
	function getOneAssess_d($assessIds){
		//获取审核表id
		$assessIdArr = explode(',',$assessIds);

		//取数
		$assessDao = new model_hr_certifyapply_cassess();
		$assessArr = $assessDao->find(array('id' => $assessIdArr[0]));
		return $assessArr;
	}

	/********************* 增删改查 ***********************/

	//重写add_d
	function add_d($object){
//		echo "<pre>";print_r($object);die();
		//取出从表数据
		$certifyresultdetailArr = $object['certifyresultdetail'];
		unset($object['certifyresultdetail']);

		try{
			$this->start_d();
			//其余值设置
			$object['ExaStatus'] = WAITAUDIT;

			//新增
			$newId = parent::add_d($object,true);

			//从表数据插入
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

	//重写编辑
	function edit_d($object){
//		echo "<pre>";print_r($object);die();
		//取出从表数据
		$certifyresultdetailArr = $object['certifyresultdetail'];
		unset($object['certifyresultdetail']);

		try{
			$this->start_d();
			//其余值设置
			$object['ExaStatus'] = WAITAUDIT;

			//新增
			$newId = parent::edit_d($object,true);

			//从表数据插入
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
	 * 批量删除对象
	 */
	function deletes_d($id) {
		try {
			$this->start_d();

			//取数
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$certifyresultdetail = $certifyresultdetailDao->getList_d($id);

			//还原评价表状态
			$assessDao = new model_hr_certifyapply_cassess();
			foreach($certifyresultdetail as $key => $val){
				$assessDao->updateStatus_d($val['assessId'],4);
			}

			//删除记录
			$this->deletes ( $id );

			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	/************************ 业务逻辑 *********************/
	/**
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

	 		//实例化从表
			$certifyresultdetailDao = new model_hr_certifyapply_certifyresultdetail();
			$certifyresultdetail = $certifyresultdetailDao->getList_d($objId);


			try{
				$this->start_d();

				//更新自身状态
				$updateArr = array('status' => 2);
				$updateArr = $this->addUpdateInfo($updateArr);
				$this->update(array('id' => $objId),array('status' => 2));

				if($certifyresultdetail){
			 		//实例化人事档案信息
			 		$personnelDao = new model_hr_personnel_personnel();
			 		//实例化认证评价表
					$assessDao = new model_hr_certifyapply_cassess();
					//实例化称谓部分
					$certifyTitleDao = new model_hr_baseinfo_certifytitle();
					//任职资格申请
					$certifyApplyDao = new model_hr_personnel_certifyapply();

					//缓存数据
					$cerTitleArr = $cerTitleNameArr = array();

					foreach($certifyresultdetail as $key => $val){
						//评价表状态更新
						$assessDao->updateStatus_d($val['assessId'],6);

						//称谓获取
						if(isset($cerTitleArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']])){
							$thisCerTitle = $cerTitleArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']];
							$thisCerTitleName = $cerTitleNameArr[$val['nowDirection']][$val['finalLevel']][$val['finalGrade']];
						}else{
							$cerTitleArr = $certifyTitleDao->find(array('careerDirection' => $val['nowDirection'],'baseLevel'=>$val['finalLevel'],'baseGrade'=>$val['finalGrade']),null,'id,titleName');
							$thisCerTitleName = $cerTitleArr['titleName'];
							$thisCerTitle = $cerTitleArr['id'];
						}

						//任职资格申请状态更新
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

						//人事信息更新
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

	/************************ 页面渲染 *********************/
	/**
	 * 渲染审核起始页面
	 */
	function initBodyAdd_v($assessIds){
		//取数
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
	 * 渲染审核编辑页
	 */
	function initBodyEdit_v($id){
		//取数
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
	 * 渲染审核起始页面
	 */
	function initBodyView_v($id){
		//取数
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