<?php

/**
 * @author show
 * @Date 2013��12��6�� 16:31:26
 * @version 1.0
 * @description:��Ŀ�ܱ��澯��¼�� Model��
 */
class model_engineering_weekreport_weekwarning extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_weekwarning";
        $this->sql_map = "engineering/weekreport/weekwarningSql.php";
        parent :: __construct();
    }

    /**
     * ��ȡ
     */
    function getWeek_d($projectId = null, $projectCode = null, $weekNo = null, $mainId = null)
    {
        if ($mainId) {
            $obj = $this->find(array('mainId' => $mainId));
            if (empty($obj)) {
                return $this->getNew_d($projectId, $projectCode, $weekNo);
            }
            return $this->getNow_d($projectId, $projectCode, $weekNo, $mainId);
        } else {
            return $this->getNew_d($projectId, $projectCode, $weekNo);
        }
    }

    /**
     * ��ȡ�¼�¼
     */
    function getNew_d($projectId, $projectCode, $weekNo)
    {
        //��ȡʵ���ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        //��������
        $arr = array();

        //����Ԥ������
        array_push($arr, $this->getRule005_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule002_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule003_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule004_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule001_d($projectId, $projectCode, $weekDateInfo));

        return $arr;
    }

    /**
     * ��ȡ�Ѵ��ڵ���Ŀ��չ��Ϣ
     */
    function getNow_d($projectId, $projectCode, $weekNo, $mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * ��ʾ���
     */
    function showWeek_d($object, $weekNo, $prevFourWeeks)
    {
        $str = "";
        if ($object) {
            $tdStr = null; //�����ַ���
            $max = 0; //�澯�ȼ�
            $warningNum = 0; // Ԥ������
            $exgross = 0; // ë����

            // ǰ���ܵı�ͷ����
            $prevFourWeeksArray = $this->prevFourView_d($prevFourWeeks);

            foreach ($object as $key => $val) {
                //�ж�����ʾЧ��
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                //�жϸ澯�ȼ�
                $max = max($val['warningLevelId'], $max);
                //�澯ͼƬ
                $img = $this->waringImg[$val['warningLevelId']];
                //�澯ֵ
                $warningLevel = $this->warningLevel[$val['warningLevelId']];
                // �Ƿ���Ҫ��д�������
                $feedbackStr = $val['warningLevelId'] == 3 ?
                    '<input type="hidden" id="needFeedback' . $key . '" value="' . $key . '"/>' : "";
                // ��¼Ԥ������
                if ($val['warningLevelId'] == 3) {
                    $warningNum++;
                }
                // ë���ʴ���
                if (isset($val['exgross'])) {
                    $exgross = $val['exgross'];
                }
                //���ַ���
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td title="{$val['ruleDesc']}">
							{$val['warningItem']}
							<input type="hidden" name="statusreport[weekwarning][$key][id]" value="{$val['id']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningItem]" value="{$val['warningItem']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningItemId]" value="{$val['warningItemId']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningLevel]" value="$warningLevel"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningLevelId]" value="{$val['warningLevelId']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][coefficient]" value="{$val['coefficient']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][analysis]" value="{$val['analysis']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][ruleDesc]" value="{$val['ruleDesc']}"/>
							$feedbackStr
						</td>
						<td>$img</td>
						<td>{$val['coefficient']}</td>
                        {$prevFourWeeksArray[$val['warningItemId']]}
						<td>
						    <input class="txtlong" style="width:300px;"  id="feedback$key"
						           name="statusreport[weekwarning][$key][feedback]" value="{$val['feedback']}">
                        </td>
						<td></td>
					</tr>
EOT;
            }
            //��ͷ����
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">Ԥ����</th>
							<th width="5%">���س̶�</th>
							<th width="10%">$weekNo</th>
							{$prevFourWeeksArray['head']}
							<th style="width:300px;">
								�������
								<input type="hidden" name="statusreport[warningLevel]" value="{$this->warningLevel[$max]}"/>
								<input type="hidden" name="statusreport[warningLevelId]" value="$max"/>
								<input type="hidden" name="statusreport[warningNum]" value="$warningNum"/>
								<input type="hidden" name="statusreport[exgross]" value="$exgross"/>
							</th>
							<th></th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
        }
        return $str;
    }

    /**
     * ǰ����Ԥ������
     * @param $prevFourWeeks
     * @return array
     */
    function prevFourView_d($prevFourWeeks) {
        // ��Ҫ���ص��ַ�������
        $strArray = array();
        $i = 0;

        foreach ($prevFourWeeks as $k => $v) {
            $i++;
            if ($i > 4) {
                continue;
            }
            $strArray['head'] .= '<th width="10%">' . $k . '</th>';
            foreach ($v as $ki => $vi) {
                $strArray[$ki] .= '<td>' . $vi . '</td>';
            }
        }

        return $strArray;
    }

    /**
     * ��ʾ��� - �鿴
     */
    function viewWeek_d($object, $weekNo, $prevFourWeeks)
    {
        if ($object) {
            $tdStr = null; //�����ַ���

            // ǰ���ܵı�ͷ����
            $prevFourWeeksArray = $this->prevFourView_d($prevFourWeeks);

            foreach ($object as $key => $val) {
                //�ж�����ʾЧ��
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                //�澯ͼƬ
                $img = $this->waringImg[$val['warningLevelId']];
                //���ַ���
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td title="{$val['ruleDesc']}">
							{$val['warningItem']}
						</td>
						<td>$img</td>
						<td>{$val['coefficient']}</td>
                        {$prevFourWeeksArray[$val['warningItemId']]}
						<td align="left">{$val['feedback']}</td>
						<td align="left"></td>
					</tr>
EOT;
            }
            //��ͷ����
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">Ԥ����</th>
							<th width="5%">���س̶�</th>
							<th width="10%">$weekNo</th>
							{$prevFourWeeksArray['head']}
							<th style="width:300px;">�������</th>
							<th></th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
        } else {
            //��ͷ����
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">Ԥ����</th>
							<th width="5%">���س̶�</th>
							<th width="10%">$weekNo</th>
							<th style="width:300px;">�������</th>
							<th></th>
						</tr>
					</thead>
					<tr class="tr_odd">
						<td colspan="5">-- û�и澯��Ϣ --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /************************** �澯������� *********************/
    /**
     * �������
     */
    public $daoCache = array();

    /**
     * ����ʵ������Ŀ
     */
    function initObj($className)
    {
        if (isset($this->daoCache[$className])) {
            return $this->daoCache[$className];
        } else {
            return $this->daoCache[$className] = new $className();
        }
    }

    /**
     * �澯�ȼ�����
     */
    public $warningLevel = array(
        0 => '',
        1 => '����',
        2 => 'Ԥ��',
        3 => '�澯',
        4 => '����'
    );

    /**
     * �澯ͼ��
     */
    public $waringImg = array(
        0 => '',
        1 => '<img src="images/icon/cicle_green.png" title="����"/>',
        2 => '<img src="images/icon/cicle_yellow.png" title="Ԥ��"/>',
        3 => '<img src="images/icon/cicle_red.png" title="����"/>',
        4 => '<img src="images/icon/cicle_black.png" title="����"/>'
    );

    /**
     * �ط�Ԥ�������¼ģ��
     */
    function getRTArray_d($warningItemId, $warningItem, $ruleDesc)
    {
        return array(
            'warningItemId' => $warningItemId, 'warningItem' => $warningItem,
            'warningLevelId' => '', 'warningLevel' => '', 'ruleDesc' => $ruleDesc,
            'coefficient' => '0', 'analysis' => '', 'feedBack' => ''
        );
    }

    /**
     * �澯���� 1
     * ��Ŀ���� = ��ĿԤ�ƽ�������-��ǰ����+1
     * ��ɫ���壺�̣�>30���ƣ�(30 - 15]���죺(15 - 0]
     */
    function getRule001_d($projectId, $projectCode, $weekDateInfo)
    {
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);

        $rtArr = $this->getRTArray_d('1', '��Ŀ����', '��ĿԤ�ƽ�������-��ǰ����+1���̣�>30���ƣ�(30 - 15]���죺(15 - 0]��');

        //��������
        $rtArr['coefficient'] = (strtotime($esmprojectObj['planEndDate']) - strtotime(day_date)) / 86400 + 1;

        //Ԥ�������������˵��
        if ($rtArr['coefficient'] > 30) {
            $rtArr['warningLevelId'] = 1;
        } elseif ($rtArr['coefficient'] <= 30 && $rtArr['coefficient'] > 15) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * �澯���� 2
     * �ɱ���Ч = CPI = EV(���̽���*��Ԥ��)/AC(�ܾ���)
     * ��ɫ���壺�죺[0 - 0.8)���ƣ�[0.8 - 0.9)���̣�[0.9 - 1.1]���ƣ�(1.1 - 1.2]���죺(1.2 - ��)
     */
    function getRule002_d($projectId, $projectCode, $weekDateInfo)
    {
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);

        //��ȡ��ʱ��Ŀ����
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $projectProcess = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'], $esmprojectDao->isCategoryAProject_d($esmprojectObj));

        //��������
        $rtArr = $this->getRTArray_d('2', 'CPI', '���̽���*��Ԥ��/�ܾ��㡾�죺[0 - 0.8)���ƣ�[0.8 - 0.9)���̣�[0.9 - 1.1]���ƣ�(1.1 - 1.2]���죺(1.2 - ��)');

        //����ϵ��
        $rtArr['coefficient'] = $esmprojectObj['feeAll'] > 0 ?
            round(bcdiv(bcmul(bcdiv($projectProcess, 100, 6), $esmprojectObj['budgetAll'], 6), $esmprojectObj['feeAll'], 6), 1) :
            '-' ;

        //Ԥ�������������˵��
        if ($rtArr['coefficient'] == '-' || ($rtArr['coefficient'] >= 0.9 && $rtArr['coefficient'] <= 1.1)) {
            $rtArr['warningLevelId'] = 1;
        } elseif (($rtArr['coefficient'] >= 0.8 && $rtArr['coefficient'] < 0.9) || ($rtArr['coefficient'] > 0.8 && $rtArr['coefficient'] <= 1.2)) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * �澯���� 3
     * ���ȼ�Ч = SPI = ʵ�ʽ���/���۽���
     * ��ɫ���壺�죺[0 - 0.8)���ƣ�[0.8 - 0.9)���̣�[0.9 - 1.1]���ƣ�(1.1 - 1.2]���죺(1.2 - ��)
     */
    function getRule003_d($projectId, $projectCode, $weekDateInfo)
    {
        //��������
        $rtArr = $this->getRTArray_d('3', 'SPI', 'ʵ�ʽ���/���۽��ȡ��죺[0 - 0.8)���ƣ�[0.8 - 0.9)���̣�[0.9 - 1.1]���ƣ�(1.1 - 1.2]���죺(1.2 - ��)��');

        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        //�����A����Ŀ����ô���ȼ�ЧΪ1
        if ($esmprojectObj['category'] == 'XMLBA') {
            $rtArr['coefficient'] = 1;
            $rtArr['warningLevelId'] = 1;
            return $rtArr;
        }

        //��ȡ��ʱ��Ŀ����
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $projectProcess = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate']);

        //��ȡԤ����Ŀ����
        $weekDao = new model_engineering_baseinfo_week();
        $weekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $esmprojectObj['planEndDate']);
        $perWeekRate = round(100 / count($weekArr), 1); //��ƽ������
        $actWeekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $weekDateInfo['endDate']);
        $processPlan = round(count($actWeekArr) * $perWeekRate > 100 ? 100 : count($actWeekArr) * $perWeekRate, 1);

        //����ϵ��
        $rtArr['coefficient'] = $processPlan > 0 ? round(bcdiv($projectProcess, $processPlan, 2), 1) : 0;

        //Ԥ�������������˵��
        if ($rtArr['coefficient'] >= 0.9 && $rtArr['coefficient'] <= 1.1) {
            $rtArr['warningLevelId'] = 1;
        } elseif (($rtArr['coefficient'] >= 0.8 && $rtArr['coefficient'] < 0.9) || ($rtArr['coefficient'] > 0.8 && $rtArr['coefficient'] <= 1.2)) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * �澯���� 4
     * ȱ����־ = ��ǰȱ����־��������:��������Ŀ��Ա�ģ�ֻҪû�뿪��Ŀ����Ҫд��־����ʽ��Ӧ��д������-ʵ����д������
     * ��ɫ���壺�̣�0���ƣ�<=10���죺>10
     */
    function getRule004_d($projectId, $projectCode, $weekDateInfo)
    {
        //��������
        $rtArr = $this->getRTArray_d('4', 'ȱ����־', '��ǰȱ����־��������:��������Ŀ��Ա�ģ�ֻҪû�뿪��Ŀ����Ҫд��־����ʽ��Ӧ��д������-ʵ����д���������̣�0���ƣ�<=10���죺>10��');

        //��ȡ��Ŀ�������
        $esmworklogDao = $this->initObj('model_engineering_worklog_esmworklog');

        // ��ȡ�û�������
        $rows = $esmworklogDao->findMember_d($projectId);

        // ��ѯ�û�����
        $memberIdArr = array();
        foreach ($rows as $v) {
            $memberIdArr[] = $v['memberId'];
        }
        $memberIds = implode(',', $memberIdArr);

        // �������� - �����û��ӿ�ʼ����������������
        $weekDao = new model_engineering_baseinfo_week();
        $dateData = $weekDao->buildDateData($weekDateInfo['beginDate'], $weekDateInfo['endDate']);

        // �û���Ϣ - ����ְ
        $personInfo = $esmworklogDao->getPersonnelInfoMap_d($memberIds);
        // ���ݼ���Ϣ
        $hols = $esmworklogDao->getHolsHash_d(strtotime($weekDateInfo['beginDate']), strtotime($weekDateInfo['endDate']), $memberIds);

        // ������Ա���봦��
        $entryDao = new model_engineering_member_esmentry();
        // ��Ŀ��������
        $entryData = $entryDao->getEntryMap_d($projectId, $memberIds);

        // ��ȡ��Ա��־
        $logs = $esmworklogDao->getAssessmentLogMap_d($weekDateInfo['beginDate'], $weekDateInfo['endDate'], $memberIds);

        // ͳ������
        $countDay = count($dateData);
        $needNum = 0;

        foreach ($rows as $k => $v) {
            $innerData = $dateData;

            // ����ְ���ݴ���
            $innerData = $esmworklogDao->dealEntryQuit_d($innerData, '', $personInfo[$v['createId']]);

            // �������ݼ�����
            $innerData = $esmworklogDao->dealHolds_d($innerData, '', '', $v['createId'], $hols);

            // ��������
            $innerData = $entryDao->dealEntry_d($innerData, '', $v['createId'], $entryData);

            // ��Ա��־����
            $innerData = $esmworklogDao->dealLog_d($innerData, $projectId, $logs[$v['createId']]);

            // �ϼ�
            $sumRow = array('id' => 'noId', 'executionDate' => '�ϼ�');

            foreach ($innerData as $vi) {
                $sumRow['inWorkRate'] = bcadd($sumRow['inWorkRate'], $vi['inWorkRate'], 2);
                $sumRow['noNeed'] = bcadd($sumRow['noNeed'], $vi['noNeed'], 2);
            }

            $needNum += $countDay - $sumRow['noNeed'] - $sumRow['inWorkRate'];
        }
        //����ϵ��
        $rtArr['coefficient'] = $needNum;
        $rtArr['coefficient'] = $rtArr['coefficient'] < 0 ? 0 : $rtArr['coefficient'];

        //Ԥ�������������˵��
        if ($rtArr['coefficient'] == 0) {
            $rtArr['warningLevelId'] = 1;
        } elseif ($rtArr['coefficient'] <= 10) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * �澯���� 5
     * ë����
     * ��ɫ���壺�죺��ǰë���� < Ԥ��ë���ʣ��̣���������
     */
    function getRule005_d($projectId, $projectCode, $weekDateInfo)
    {
        //��������
        $rtArr = $this->getRTArray_d('5', 'ë����', '�죺��ǰë���� < Ԥ��ë���ʣ��̣���������');

        // ��Ŀ���ݻ�ȡ
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);

        //��ȡ��ʱ��Ŀ���ȣ����ҷ�����Ŀ��
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $esmprojectObj['projectProcess'] = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'],
            $esmprojectDao->isCategoryAProject_d($esmprojectObj));

        $esmprojectObj = $esmprojectDao->contractDeal($esmprojectObj);

        //��ѯ��ǰ����صı���������
        $rtArr['exgross'] = $rtArr['coefficient'] = $esmprojectObj['exgross'] . " %";
        $rtArr['warningLevelId'] = $esmprojectObj['exgross'] < $esmprojectObj['budgetExgross'] ? 3 : 1;

        return $rtArr;
    }

    /**
     * ���±��¼
     */
    function update_d($mainId, $weeklogInfo)
    {
        try {
            $this->start_d();
            foreach ($weeklogInfo as $val) {
                $this->update(array('mainId' => $mainId, 'warningItemId' => $val['warningItemId']), $val);
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * ����ǰ4�ܵ�����
     * @param $projectId
     * @param $weekNo
     * @return array
     */
    function findPrevFourWeeks_d($projectId, $weekNo) {
        // ���ؽ��
        $rst = array();

        // ��ѯ���������
        $sql = "SELECT *
            FROM oa_esm_project_weekwarning c LEFT JOIN oa_esm_project_statusreport s ON c.mainId = s.id
            WHERE s.projectId = " . $projectId . " AND s.weekNo < $weekNo ORDER BY s.weekNo DESC" ;
        $data = $this->_db->getArray($sql);

        // ����в�ѯ���������ݸĳ�ָ����ʽ
        if ($data) {
            foreach ($data as $v) {
                $rst[$v['weekNo']][$v['warningItemId']] = $v['coefficient'];
            }
        }
        return $rst;
    }
}