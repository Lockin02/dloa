<?php
/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:13
 * @version 1.0
 * @description:任职资格等级认证评价表 Model层
 */
 class model_hr_certifyapply_cassess  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess";
		$this->sql_map = "hr/certifyapply/cassessSql.php";
		parent::__construct ();
	}

	/******************** 配置信息 *************************/
	//状态
	function rtStatus_c($status){
		switch($status){
			case 0 : return '保存';break;
			case 1 : return '认证准备中';break;
			case 2 : return '审批中';break;
			case 3 : return '完成待评分';break;
			case 4 : return '完成已评分';break;
			case 5 : return '确认审核中';break;
			case 6 : return '审核已完成';break;
			default : return $status;
		}
	}

	//返回是否处理
	function rtIsDeal_c($isDeal){
		if($isDeal == 0){
			return 'ok';
		}else{
			return 'no';
		}
	}

	//传入主审人和副审人，返回统计列跨列数
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

	/******************** 其他数据获取 **********************/
	/**
	 * 获取任职申请信息
	 */
	function getApplyInfo_d($applyId){
		$certifyapplyDao = new model_hr_personnel_certifyapply();
		return $certifyapplyDao->find(array('id' => $applyId));
	}

	/**
	 *  获取任职资格匹配模板id
	 */
	function getTemplate_d($careerDirection,$baseLevel,$baseGrade){
		$certifytemplateDao = new model_hr_baseinfo_certifytemplate();
		return $certifytemplateDao->find(
			array('careerDirection' => $careerDirection, 'baseLevel' => $baseLevel , 'baseGrade' => $baseGrade)
		);
	}


	/******************* 增删改查 *********************************/

	//重写addApply_d
	function add_d($object){
        //获取行为要项
        $cdetail = $object['cdetail'];
        unset($object['cdetail']);

		try{
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);

			$object['ExaStatus'] = WAITAUDIT;

			$newId =  parent::add_d($object,true);

            //处理任务成员
            $cdetailexpDao = new model_hr_certifyapply_cdetail();
            $cdetailexpDao->createBatch($cdetail,array('assessId' => $newId));

            //生成评估表后，更改任职申请表状态
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
        //获取行为要项
        $cdetail = $object['cdetail'];
        unset($object['cdetail']);

		try{
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);

			parent::edit_d($object,true);

            //处理任务成员
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
	 * 指定评委
	 */
	function setManager_d($object){
		$sql = "update ".$this->tbl_name." set managerId = '". $object['managerId']. "',managerName = '". $object['managerName']. "',memberId = '". $object['memberId']. "',memberName = '". $object['memberName']. "' where id in (". $object['id']. ") ";
		return $this->_db->query($sql);
	}

	/**
	 * 分数录入
	 */
	function inScore_d($object){
//		echo "<pre>";print_r($object);die();
		//评价表明细
		$cdetail = $object['cdetail'];
		unset($object['cdetail']);

		//评委评分明细
		$scoredetail = $object['scoredetail'];
		unset($object['scoredetail']);

		try{
			$this->start_d();
			//修改任职评价表状态为完成已评分
			$object['status'] = '4';

			parent::edit_d($object,true);

			//评分明细调整
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->saveDelBatch($cdetail);

			//评委评分明细
			$scoredetailDao = new model_hr_certifyapply_scoredetail();
			//评委评分主表
			$scoreDao = new model_hr_certifyapply_score();
			$cacheArr = array();
			foreach($scoredetail as $key => $val){
				$cacheArr = $val;
				$scoreArr = array_pop($cacheArr);

				//如果明细不存在主评分表id，则新建评分表,否则直接更新
				if(empty($scoreArr['scoreId'])){
					$scoreDao->createScoreInfo_d($object,$val);
				}else{
					$scoredetailDao->saveDelBatch($val);
					//更新相应单据的总得分
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

	/********************** 业务逻辑 ********************/
	/**
	 * 提交认证准备
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
	 * 获取选中的用户信息
	 */
	function getAssessUser_d($ids){
		$this->searchArr = array('ids' => $ids);
		$rs = $this->list_d();
		if($rs){

			//重整人员信息
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
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

	 		//申请单实例化
	 		$certifyapplyDao = new model_hr_personnel_certifyapply();
	 		$certifyapplyDao->updateStatus_d($object['applyId'],6);

			return 1;
	 	}
	 	return 1;
	}

	/**
	 * 根据id 获取列表信息 - 供认证结果审核使用
	 */
	function getListForAudit_d($ids){
		$this->searchArr = array(
			'ids' => $ids
		);
		return $this->list_d('select_forresult');
	}

	/**
	 * 更新自身状态
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

	/************************* 页面显示部分 *****************/
	/**
	 * 表头构建
	 */
	function initHead_v($object){
		//获取主评委的信息
		$managerName = $object['managerName'];
		//获取其他评委信息
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;

		$beforeHead =<<<EOT
			<tr class='main_tr_header'>
				<th width='50'>序号</th>
				<th width='150'>行为要项</th>
				<th width='80'>权重</th>
EOT;
		//加载主评委
		if($managerName){
			$beforeHead .= "<th title='主评委'><span class='red'>$managerName</span></th>";
		}
		//其余评委
		if($memberNameArr){
			foreach($memberNameArr as $val){
				$beforeHead .= "<th title='其他评委'><span class='blue'>$val</span></th>";
			}
		}
		$afterHead =<<<EOT
			<th>各评委的<br/>平均得分</th><th>加权得分</th><th>主管与评委<br/>平均分分差</th><th>评委最<br/>大分差</th><th>评委最大<br/>分差处理</th>
EOT;


		$head = $beforeHead . $afterHead.  "</tr>";

		return $head;
	}

	/**
	 *  表内容构建
	 */
	function initBody_v($object){
		//获取需要的信息
		$managerId = $object['managerId'];
		$managerName = $object['managerName'];
		//获取需要的信息
		$memberId = $object['memberId'];
		$memberIdArr = explode(',',$memberId) ;
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;
		$tBody = "";
		$i = 0;

		//实例化得分换算表明细
		$cdetailDao = new model_hr_certifyapply_cdetail();
		$cdetailArr = $cdetailDao->getCdetail_d($object['id']);

		//实例化审核明细
		$scoredetailDao = new model_hr_certifyapply_scoredetail();
		$scoredetailArr = $scoredetailDao->getScoreDetail_d($object['id']);

		if($cdetailArr){
			//缓存最大行数
			foreach($cdetailArr as $key => $val){
				$i++;
				$tr_class = $i%2 == 0 ? 'tr_odd' : 'tr_even';
				//选择事件处理
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
				//加载主评委
				if($managerId){
					//评分获取
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
				//其余评委
				if($memberIdArr){
					foreach($memberIdArr as $k => $v){
						//评分获取
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
	 *  表内容构建
	 */
	function initBodyView_v($object){
		//获取需要的信息
		$managerId = $object['managerId'];
		$managerName = $object['managerName'];
		//获取需要的信息
		$memberId = $object['memberId'];
		$memberIdArr = explode(',',$memberId) ;
		$memberName = $object['memberName'];
		$memberNameArr = explode(',',$memberName) ;
		$tBody = "";
		$i = 0;

		//实例化得分换算表明细
		$cdetailDao = new model_hr_certifyapply_cdetail();
		$cdetailArr = $cdetailDao->getCdetail_d($object['id']);

		//实例化审核明细
		$scoredetailDao = new model_hr_certifyapply_scoredetail();
		$scoredetailArr = $scoredetailDao->getScoreDetail_d($object['id']);

		if($cdetailArr){
			//缓存最大行数
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
				//加载主评委
				if($managerId){
					//评分获取
					$score = isset($scoredetailArr[$managerId][$val['detailId']]) ? $scoredetailArr[$managerId][$val['detailId']]['score'] : 0;
					$tBody.=<<<EOT
						<td width="8%">
							$score
						</td>
EOT;
				}
				//其余评委
				if($memberIdArr){
					foreach($memberIdArr as $k => $v){
						//评分获取
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