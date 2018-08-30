<?php
/**
 * @author show
 * @Date 2013��9��22�� 14:45:40
 * @version 1.0
 * @description:��Ŀ��������� Model��
 */
class model_engineering_weekreport_weektask extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_weektask";
		$this->sql_map = "engineering/weekreport/weektaskSql.php";
		parent :: __construct();
	}

	/**
	 * ��ȡ�������չ����
	 */
	function getWeekTask_d($projectId = null,$weekNo = null,$mainId = null){
		$obj = $this->find(array('mainId' => $mainId));
		if($mainId && !empty($obj)){
			return $this->getNowWeekTask_d($projectId,$weekNo,$mainId);
		}else{
			return $this->getNewWeekTask_d($projectId,$weekNo);
		}
	}

	/**
	 * ��ȡ�µ���Ŀ��չ
	 */
	function getNewWeekTask_d($projectId,$weekNo){
        $weekDao = new model_engineering_baseinfo_week();

		//��ȡ������Ϣ
		$esmactivityDao = new model_engineering_activity_esmactivity();
		$esmactivityArr = $esmactivityDao->getProjectActivity_d($projectId);

        //��ȡ��Ŀ��Ϣ
        $esmprojectDao = new model_engineering_project_esmproject();

		//����ѯ���Ľ�չ���ӵ�������
		if($esmactivityArr){
            //��ȡ�ܴεı�������
            $weekDate = $weekDao->findWeekDate($weekNo);
            $dateInfo = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);

            //��ȡ���ܵ�����
            $prevWeekDate = $weekDao->findWeekDate($weekNo-1);
            $prevDateInfo = $weekDao->getWeekRange($prevWeekDate['week'], $prevWeekDate['year']);

            //A����Ŀֻ�Խ��ȼ���
            if($esmprojectDao->isCategoryAProject_d($projectId)){
                foreach($esmactivityArr as &$val){//ѭ������
                    //���㱾�������
                    $thisWorkloadDone = $weekDao->dateDiff(max($dateInfo['beginDate'],$val['planBeginDate']),min($val['planEndDate'],$dateInfo['endDate']));
                    $val['thisWorkloadDone'] = $thisWorkloadDone < 0 ?  0 : $thisWorkloadDone;
                    $val['thisWeekProcess'] = $thisWorkloadDone < 0 ? 0 : bcmul(bcdiv($thisWorkloadDone,$val['days'],4),100,2);

                    //�������������
                    $prevWorkloadDone = $weekDao->dateDiff(max($prevDateInfo['beginDate'],$val['planBeginDate']),min($val['planEndDate'],$prevDateInfo['endDate']));
                    $val['prevWorkloadDone'] = $prevWorkloadDone < 0 ?  0 : $prevWorkloadDone;
                    $val['prevWeekProcess'] = $prevWorkloadDone < 0 ? 0 : bcmul(bcdiv($prevWorkloadDone,$val['days'],4),100,2);

                    //��������
                    $workloadDone = $weekDao->dateDiff($val['planBeginDate']);
                    $val['workloadDone'] = $workloadDone > $val['days'] ? $val['days'] : $workloadDone;
                    $val['workloadDone'] = $val['workloadDone'] < 0 ? 0 : $val['workloadDone']; # ����δ��ʼ������
                    $val['process'] = $workloadDone > $val['days'] ? 100 : bcmul(bcdiv($workloadDone,$val['days'],4),100,2);
                    $val['process'] = $val['process'] < 0 ? 0 : $val['process']; # ����δ��ʼ������

                    $val['activityId'] = $val['id'];
                    unset($val['id']);
                }
            }else{
                //��ȡ������� - ����
                $esmworklogDao = new model_engineering_worklog_esmworklog();
                //���ܹ������
                $thisWeekInfoArr = $esmworklogDao->getCountByDates_d($projectId,$dateInfo['beginDate'],$dateInfo['endDate']);

                //��ȡ���ܵ�����
                $prevWeekInfoArr = $esmworklogDao->getCountByDates_d($projectId,$prevDateInfo['beginDate'],$prevDateInfo['endDate']);
                foreach($esmactivityArr as &$val){//ѭ������
                    $val['thisWorkloadDone'] = $thisWeekInfoArr[$val['id']]['workloadDay'];
                    $val['thisWeekProcess'] = $thisWeekInfoArr[$val['id']]['thisActivityProcess'];
                    $val['prevWorkloadDone'] = $prevWeekInfoArr[$val['id']]['workloadDay'];
                    $val['prevWeekProcess'] = $prevWeekInfoArr[$val['id']]['thisActivityProcess'];
                    $val['activityId'] = $val['id'];
                    unset($val['id']);
                }
            }
		}

		return $esmactivityArr;
	}

	/**
	 * ��ȡ�Ѵ��ڵ���Ŀ��չ��Ϣ
	 */
	function getNowWeekTask_d($projectId,$weekNo,$mainId){
		return $this->findAll(array('mainId' => $mainId));
	}

	/**
	 * ��ʾ���
	 */
	function showWeekTask_d($object){
		$str= "";
		if($object){
			$mark = null;//��ʶλ
			$level = 0;//����
			$tdStr = null;//�����ַ���
//			echo "<pre>";
//			print_r($object);
			foreach($object as $key => $val){
				if($val['parentId'] == PARENT_ID || isset($val['id'])){
					//���ñ�־λ
					$mark = $val['id'];
					//���ü���
					$level = 0;
					$activityNameStr="{$val['activityName']}";
				}else if( $mark == $val['parentId']){
					$level ++;
					$activityNameStr="�� ".$val['activityName'];
				}else{
					$appendStr = $this->rtAppendStr_v($level);//����
					$activityNameStr = $appendStr."�� ".$val['activityName'];
				}

				//�ж�����ʾЧ��
				$trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
				if($val['rgt'] - $val['lft'] == 1 || !empty($val['isTask'])){
					$thisWorkloadDone = isset($val['thisWorkloadDone']) ? $val['thisWorkloadDone'] : 0;
					$thisWeekProcess = isset($val['thisWeekProcess']) ? $val['thisWeekProcess'] : 0;
					$prevWorkloadDone = isset($val['prevWorkloadDone']) ? $val['prevWorkloadDone'] : 0;
					$prevWeekProcess = isset($val['prevWeekProcess']) ? $val['prevWeekProcess'] : 0;
					//���ַ���
					$tdStr.=<<<EOT
						<tr class="$trClass">
							<td align="left">$activityNameStr</td>
							<td>$thisWorkloadDone</td>
							<td>$thisWeekProcess %</td>
							<td>$prevWorkloadDone</td>
							<td>$prevWeekProcess %</td>
							<td>{$val['workloadDone']}</td>
							<td>{$val['process']} %
								<input type="hidden" name="statusreport[weektask][$key][thisWorkloadDone]" value="$thisWorkloadDone"/>
								<input type="hidden" name="statusreport[weektask][$key][thisWeekProcess]" value="$thisWeekProcess"/>
								<input type="hidden" name="statusreport[weektask][$key][prevWorkloadDone]" value="$prevWorkloadDone"/>
								<input type="hidden" name="statusreport[weektask][$key][prevWeekProcess]" value="$prevWeekProcess"/>
								<input type="hidden" name="statusreport[weektask][$key][workloadDone]" value="{$val['workloadDone']}"/>
								<input type="hidden" name="statusreport[weektask][$key][process]" value="{$val['process']}"/>
								<input type="hidden" name="statusreport[weektask][$key][activityName]" value="{$activityNameStr}"/>
								<input type="hidden" name="statusreport[weektask][$key][activityId]" value="{$val['activityId']}"/>
								<input type="hidden" name="statusreport[weektask][$key][id]" value="{$val['id']}"/>
								<input type="hidden" name="statusreport[weektask][$key][isTask]" value="1"/>
							</td>
						</tr>
EOT;
				}else{
					//���ַ���
					$tdStr.=<<<EOT
						<tr class="$trClass">
							<td align="left">$activityNameStr</td>
							<td colspan="6">
								<input type="hidden" name="statusreport[weektask][$key][thisWorkloadDone]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][thisWeekProcess]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][prevWorkloadDone]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][prevWeekProcess]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][workloadDone]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][process]" value="0"/>
								<input type="hidden" name="statusreport[weektask][$key][activityName]" value="{$activityNameStr}"/>
								<input type="hidden" name="statusreport[weektask][$key][activityId]" value="{$val['activityId']}"/>
								<input type="hidden" name="statusreport[weektask][$key][id]" value="{$val['id']}"/>
								<input type="hidden" name="statusreport[weektask][$key][isTask]" value="0"/>
							</td>
						</tr>
EOT;
				}
			}

			//��ͷ����
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th rowspan="2" width="40%">��������</th>
							<th colspan="2">����</th>
							<th colspan="2">����</th>
							<th colspan="2">����</th>
						</tr>
						<tr class="main_tr_header">
							<th width="10%">�����</th><th width="10%">��չ</th>
							<th width="10%">�����</th><th width="10%">��չ</th>
							<th width="10%">�����</th><th width="10%">��չ</th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
		}
		return $str;
	}

	/**
	 * ��ʾ���
	 */
	function viewWeekTask_d($object){
		$str= "";
		if($object){
            $tdStr = '';
			foreach($object as $key => $val){
				$thisWorkloadDone = isset($val['thisWorkloadDone']) ? $val['thisWorkloadDone'] : 0;
				$thisWeekProcess = isset($val['thisWeekProcess']) ? $val['thisWeekProcess'] : 0;
				$prevWorkloadDone = isset($val['prevWorkloadDone']) ? $val['prevWorkloadDone'] : 0;
				$prevWeekProcess = isset($val['prevWeekProcess']) ? $val['prevWeekProcess'] : 0;
                //�ж�����ʾЧ��
                $trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
				//���ַ���
				$tdStr.=<<<EOT
					<tr class="$trClass">
						<td align="left">{$val['activityName']}</td>
						<td>$thisWorkloadDone</td>
						<td>$thisWeekProcess %</td>
						<td>$prevWorkloadDone</td>
						<td>$prevWeekProcess %</td>
						<td>{$val['workloadDone']}</td>
						<td>{$val['process']} %</td>
					</tr>
EOT;
			}

			//��ͷ����
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th rowspan="2" width="40%">��������</th>
							<th colspan="2">����</th>
							<th colspan="2">����</th>
							<th colspan="2">����</th>
						</tr>
						<tr class="main_tr_header">
							<th width="10%">�����</th><th width="10%">��չ</th>
							<th width="10%">�����</th><th width="10%">��չ</th>
							<th width="10%">�����</th><th width="10%">��չ</th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
		}
		return $str;
	}

	/**
	 * ����ǰ�ÿո�
	 */
	function rtAppendStr_v($level){
		if($level == 0){
			return "";
		}
		$str = "";
		for($i = 0 ; $i < $level ; $i++){
			$str.="&nbsp;";
		}
		return $str;
	}
	/**
	 * ���±��¼
	 */
	function update_d($mainId,$weekTaskInfo){
		try{
			$this->start_d();
			foreach ($weekTaskInfo as $val){
				$this->update(array('mainId' => $mainId,'activityId' => $val['activityId']),$val);
			}
			$this->commit_d();
		}catch (Exception $e){
			$this->rollBack();
		}
	}
}