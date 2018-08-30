<?php

/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��Ŀ������¼ Model��
 */
class model_engineering_baseinfo_esmlog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_log";
        $this->sql_map = "engineering/baseinfo/esmlogSql.php";
        parent::__construct();
    }

    // �Ѿ���ʽ����ɵ�����
    private $formatedLog = array();

    /**
     * ��Ӳ�����¼
     * @param $projectId
     * @param $operationType
     * @param null $description
     * @return bool
     */
    function addLog_d($projectId, $operationType, $description = null)
    {
        $obj = array(
            'projectId' => $projectId, 'operationType' => $operationType, 'description' => $description,
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'operationTime' => date('Y-m-d H:i:s')
        );
        return $this->add_d($obj);
    }

    /**
     * ��Ӳ�����¼ - ����
     * @param $datas
     * @param $operationType
     * @param string $keyWork
     */
    function addLogBatch_d($datas, $operationType, $keyWork = 'projectId')
    {
        if ($datas) {
            $time = date('Y-m-d H:i:s');
            foreach ($datas as $k => $v) {
                $description = "���������ݡ� ";
                $projectId = '';
                foreach ($v as $ka => $va) {
                    if ($ka != $keyWork)
                        $description .= $ka . " => " . $va . " ; ";
                    else
                        $projectId = $va;
                }
                $this->formatedLog[$k] = array(
                    'projectId' => $projectId, 'operationType' => $operationType, 'description' => $description,
                    'userId' => $_SESSION['USER_ID'],
                    'userName' => $_SESSION['USERNAME'],
                    'operationTime' => $time
                );
            }
            $this->createBatch($this->formatedLog);
        }
    }

    /**
     * @param $objects
     * @return string
     */
    function leftCycle_d($objects)
    {
        $html = ""; // ������Ϣ
        foreach ($objects as $k => $v) {
            $rowNo = $k + 1;
            $html .= <<<EOT
                <h2 class="headline-1">
                    <a class="anchor-1" name="$rowNo"></a>
                    <span class="headline-1-index">$rowNo</span>
                    <span class="headline-content">$v[operationType]</span>
                </h2>
                <div class="para">
                       <br>
                       �� $v[operationTime] ��  ��  �� $v[userName] �� �����ɹ�
                       <br><br>
                       &nbsp;&nbsp;˵����$v[description]
                       <br><br><br><br><br><br><br><br><br>
                </div>
EOT;
        }
        return $html;
    }

    /**
     * �������
     * @param $keyArr
     * @param $orgObj
     * @param $newObj
     * @return string
     */
    function showDiff_d($keyArr, $orgObj, $newObj)
    {
        $showArr = array();
        foreach ($keyArr as $k => $v) {
            if ($orgObj[$k] != $newObj[$k]) {
                $showArr[] = $v . "��" . $orgObj[$k] . ' => ' . $newObj[$k];
            }
        }
        return implode('��', $showArr);
    }
}