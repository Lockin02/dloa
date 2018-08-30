<?php

/**
 * @author Administrator
 * @Date 2012-06-18 16:25:39
 * @version 1.0
 * @description:�������� Model��
 */
class model_projectmanagent_trialproject_extension extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_trialproject_extension";
        $this->sql_map = "projectmanagent/trialproject/extensionSql.php";
        parent::__construct();
    }

    //���ݲ�ͬ��ִ������,������Ӧ���ʼ�������
    public $toMailArr = array(
        'GCSCX-01' => 'stone.zhang', //�����Ǳ����񲿣����ڣ��з�����
// 		'GCSCX-02' => 'jianping.luo',//������������޽�ƽ
// 		'GCSCX-04' => 'minliang.yu,ziyi.guo',//ͨ�ŷ�����ҵ������������������
// 		'GCSCX-05' => 'jianwei.su',//�����飺�ս���
// 		'GCSCX-06' => 'green.wang',//�з��ۺϲ�������Ԫ
// 		'GCSCX-07' => 'jianwei.su',//�����ࣺ�ս���
// 		'GCSCX-08' => 'yule.shao',//����ר����������
// 		'GCSCX-09' => 'xiwei.zhang'//����ר������ϲΰ
        'GCSCX-08' => 'dongsheng.wang', //��������������
        'GCSCX-09' => 'dongsheng.wang', //��������������
        'GCSCX-10' => 'dongsheng.wang', //��������������
        'GCSCX-11' => 'dongsheng.wang', //��������������
        'GCSCX-12' => 'dongsheng.wang', //��������������
        'GCSCX-13' => 'dongsheng.wang', //��������������
        'GCSCX-14' => 'dongsheng.wang', //��������������
        'GCSCX-15' => 'dongsheng.wang', //ϵͳ������������
        'GCSCX-17' => 'jianping.luo,heng.yin', //�����ݲ����޽�ƽ������
    );

    /**
     * ��дedit_d
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //���ø���༭
            parent :: edit_d($object, true);
            $this->commit_d();
            //���������ƺ�Id
            $this->updateObjWithFile($object['id']);
            return true;
        } catch (exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * ����������ĿId ���� ���һ����������ID
     */
    function findExtensionId($trialprojectId)
    {
        $sql = "select max(id) as extensionId from oa_trialproject_extension where trialprojectId = '" . $trialprojectId . "'";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['extensionId'];

    }

    //��ȡ�����������
    function getExtTime_d($tpid)
    {
        $sql = "select max(extensionTime) as extensionTime from oa_trialproject_extension where trialprojectId = '$tpid'";
        $result = $this->_db->get_one($sql);
        return $result['extensionTime'];
    }
    /**
     * �������ص�����
     */
    function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        if (!empty ($objId)) {
            $rows = $this->get_d($objId);
            if ($rows ['ExaStatus'] == "���") {
                // ��ѯ����ƿװ
                $extensionSql = !empty($rows['extensionDate']) ? "',closeDate = '" . $rows['extensionDate'] : "";
                $newProjectDaysSql = !empty($rows['newProjectDays']) ? "',projectDays = projectDays + '" . $rows['newProjectDays'] : "";
                $trialprojectId = $rows['trialprojectId'];
                //����ͨ��������������Ŀ�Ľ���ʱ��
                $updateSql = "update oa_trialproject_trialproject set serCon = '1',affirmMoney='" .
                    $rows['affirmMoney'] . $extensionSql . $newProjectDaysSql . "' where id=" . $trialprojectId;
                $this->query($updateSql);

                // ������Ŀ����
                $projectDao = new model_engineering_project_esmproject();
                $projectDao->updateTriEstimates_d($rows['trialprojectId'], 'GCXMYD-04', $rows['affirmMoney']);

                //��ȡ�ʼ�����������
                $trialDao = new model_projectmanagent_trialproject_trialproject();
                $trialArr = $trialDao->get_d($trialprojectId);
                if (!empty($trialArr['productLine'])) {
                    $emailDao = new model_common_mail();
                    $contractCode = $rows['trialprojectCode'];
                    $project_sql = "select * from oa_esm_project where contractCode ='{$contractCode}'";
                    $projectArr = $this->_db->getArray($project_sql);
                    $projectCode = $projectArr[0]['projectCode'];
                    $newProLine = $projectArr[0]['newProLine'];

                    $toMailId = 'dongsheng.wang'; //PK��Ŀ������������ͨ�����ʼ�֪ͨ����ͬ��Ӧ��Ŀ����Ŀ�����������������

                    if($newProLine == "HTCPX-DSJ"){// ���ִ�������Ǵ����ݵĻ�,ȡ���´���ķ����ܼ� PMS 686
                        $chkSql = "select GROUP_CONCAT(mainManagerId) as mainManagerId from oa_esm_office_baseinfo where productLine = 'GCSCX-17';";
                        $officeManagerId = $this->_db->get_one($chkSql);
                        $toMailId .= ($officeManagerId['mainManagerId'] != '') ? ',' . $officeManagerId['mainManagerId'] : '';
                    }else{
                        $managerDao = new model_engineering_officeinfo_manager();
                        $provinceObj = $managerDao->getManagerForMail_d($projectArr[0]['province'], $projectArr[0]['businessBelong']);
                        $toMailId .= ($provinceObj['areaManagerId'] != '') ? ',' . $provinceObj['areaManagerId'] : ''; //ִ�����������Ҫ������
                    }

                    $toMailId .= ($projectArr[0]['areaManagerId'] != '') ? ',' . $projectArr[0]['areaManagerId'] : ''; //��Ŀ�ڷ�����
                    $toMailId .= ($projectArr[0]['managerId'] != '') ? ',' . $projectArr[0]['managerId'] : ''; //��Ŀ����

                    // ��ֹ�ռ��˳����ظ��������ظ��ʼ�
                    $arr = array_unique(explode(',', $toMailId));
                    $toMailId = implode(',', $arr);

                    $content_msg = "��ã�{$contractCode} ��Ŀ�����Ѿ�ͨ������������Ŀ�����յ����ʼ����һʱ�����OA��Ŀ {$projectCode} �������Ŀ���ڡ���Ŀ�ƻ�����ĿԤ�㣩��";
                    $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject_delay", $rows['trialprojectCode'], $toMailId, $content_msg); //������Ŀ��������ͨ�����ʼ�֪ͨ
                }
            } else if($rows['ExaStatus'] == BACK) { // ������غ󣬽�������Ŀ����״̬���³����
                $trialprojectId = $rows['trialprojectId'];
                // ������ͨ������������Ŀ�������Ϊ����������
                $updateSql = "update oa_trialproject_trialproject set serCon = '4' where id=" . $trialprojectId;
                $this->query($updateSql);
            }
        }
    }
}