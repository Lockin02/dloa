<?php

/**
 * @author show
 * @Date 2013��10��17�� 17:34:30
 * @version 1.0
 * @description:��Ŀ��Ա����־ Model��
 */
class model_engineering_weekreport_wpweeklog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_weeklog";
        $this->sql_map = "engineering/weekreport/wpweeklogSql.php";
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
            return $this->getNow_d($mainId);
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

        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $logs = $esmworklogDao->getAssessData_d(array(
            'beginDate' => $weekDateInfo['beginDate'],
            'endDate' => $weekDateInfo['endDate'],
            'projectId' => $projectId
        ));

        if ($logs) {
            //��ʱ���ڻ�ȡ����
            $sql = "select CostMan,sum(c.amount) as feeField from cost_summary_list c
				where replace(c.projectno,'-','')=replace('$projectCode','-','') and c.status <>'���' group by CostMan";
            $rs = $this->_db->getArray($sql);
            $feeArr = array();
            if ($rs[0]['CostMan']) {
                foreach ($feeArr as $v) {
                    $feeArr[$v['CostMan']] = $v['feeField'];
                }
            }

            foreach ($logs as &$v) {
                $v['memberId'] = $v['createId'];
                $v['memberName'] = $v['createName'];
                $v['isNew'] = 1;
                if (isset($feeArr[$v['memberId']])) {
                    $v['feeAll'] = $feeArr[$v['memberId']];
                } else {
                    $v['feeAll'] = 0;
                }
            }
        }
        return $logs;
    }

    /**
     * ��ȡ�Ѵ��ڵ���Ŀ��չ��Ϣ
     */
    function getNow_d($mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * ��ʾ���
     */
    function showWeek_d($data)
    {
        if (empty($data) || $data[0]['isNew']) {
            return $this->setTemplate_d($data);
        } else {
            return $this->setOldTemplate_d($data);
        }
    }

    /**
     * ��ģ��
     * @param $data
     * @return string
     */
    function setTemplate_d($data) {
        $headStr = <<<E
            <thead>
                <tr class="main_tr_header">
                    <th>����</th>
                    <th width="12%">����Ͷ��	</th>
                    <th width="12%">���˵÷�</th>
                    <th width="12%">ͳ������</th>
                    <th width="12%">����ϵ��</th>
                    <th width="12%">����ϵ��</th>
                    <th width="12%">���ݼ�����</th>
                    <th width="12%">����</th>
                </tr>
            </thead>
E;
        if ($data) {
            $tdStr = ""; //�����ַ���
            foreach ($data as $k => $v) {
                //�ж�����ʾЧ��
                $trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
                //���ַ���
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td>
							{$v['memberName']}
							<input type="hidden" name="statusreport[wpweeklog][$k][id]" value="{$v['id']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][memberName]" value="{$v['memberName']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][memberId]" value="{$v['memberId']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][inWorkRate]" value="{$v['inWorkRate']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][monthScore]" value="{$v['monthScore']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][countDay]" value="{$v['countDay']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][attendance]" value="{$v['attendance']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][assess]" value="{$v['assess']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][hols]" value="{$v['hols']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][feeAll]" value="{$v['feeAll']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][isNew]" value="{$v['isNew']}"/>
						</td>
						<td>{$v['inWorkRate']}</td>
						<td>{$v['monthScore']}</td>
						<td>{$v['countDay']}</td>
						<td>{$v['attendance']}</td>
						<td>{$v['assess']}</td>
						<td>{$v['hols']}</td>
						<td>{$v['feeAll']}</td>
					</tr>
EOT;
            }

            //��ͷ����
            $str = <<<EOT
				<table class="form_in_table" style="width:1000px;">
					$headStr
					$tdStr
				</table>
EOT;
        } else {
            //��ͷ����
            $str = <<<EOT
				<table class="form_in_table" style="width:1000px;">
                    $headStr
					<tr class="tr_odd">
						<td colspan="8">-- û�������Ϣ --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /**
     * ��ģ��
     * @param $data
     * @return string
     */
    function setOldTemplate_d($data) {
        $headStr = <<<E
            <thead>
                <tr class="main_tr_header">
                    <th>����</th>
                    <th width="16%">�˹�Ͷ��</th>
                    <th width="16%">����ϵ��</th>
                    <th width="16%">��Ŀ�����</th>
                    <th width="16%">��չϵ��</th>
                    <th width="16%">����</th>
                </tr>
            </thead>
E;
        if($data){
            $tdStr = null;//�����ַ���
            foreach($data as $key => $val){
                //�ж�����ʾЧ��
                $trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
                //���ַ���
                $tdStr.=<<<EOT
					<tr class="$trClass">
						<td>
							{$val['memberName']}
							<input type="hidden" name="statusreport[wpweeklog][$key][id]" value="{$val['id']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][memberName]" value="{$val['memberName']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][memberId]" value="{$val['memberId']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][inWorkRate]" value="{$val['inWorkRate']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][workCoefficient]" value="{$val['workCoefficient']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][projectProcess]" value="{$val['projectProcess']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][processCoefficient]" value="{$val['processCoefficient']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][feeAll]" value="{$val['feeAll']}"/>
						</td>
						<td>{$val['inWorkRate']}</td>
						<td>{$val['workCoefficient']}</td>
						<td>{$val['projectProcess']}</td>
						<td>{$val['processCoefficient']}</td>
						<td>{$val['feeAll']}</td>
					</tr>
EOT;
            }

            //��ͷ����
            $str =<<<EOT
				<table class="form_in_table" style="width:1000px;">
				    $headStr
					$tdStr
				</table>
EOT;
        }else{
            //��ͷ����
            $str =<<<EOT
				<table class="form_in_table" style="width:1000px;">
				    $headStr
					<tr class="tr_odd">
						<td colspan="6">-- û�������Ϣ --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /**
     * ���±��¼
     */
    function update_d($mainId, $weeklogInfo)
    {
        try {
            $this->start_d();
            foreach ($weeklogInfo as $v) {
                //�жϸü�¼�Ƿ���ڣ�������£���������
                $id = $this->find(array('mainId' => $mainId, 'memberId' => $v['memberId']), null, 'id');
                if (!empty($id)) {
                    $this->update(array('id' => $id), $v);
                } else {
                    $v['mainId'] = $mainId;
                    $this->create($v);
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }
}