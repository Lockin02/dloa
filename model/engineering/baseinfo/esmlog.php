<?php

/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:项目操作记录 Model层
 */
class model_engineering_baseinfo_esmlog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_log";
        $this->sql_map = "engineering/baseinfo/esmlogSql.php";
        parent::__construct();
    }

    // 已经格式化完成的数组
    private $formatedLog = array();

    /**
     * 添加操作记录
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
     * 添加操作记录 - 批量
     * @param $datas
     * @param $operationType
     * @param string $keyWork
     */
    function addLogBatch_d($datas, $operationType, $keyWork = 'projectId')
    {
        if ($datas) {
            $time = date('Y-m-d H:i:s');
            foreach ($datas as $k => $v) {
                $description = "【更新内容】 ";
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
        $html = ""; // 返回信息
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
                       【 $v[operationTime] 】  由  【 $v[userName] 】 操作成功
                       <br><br>
                       &nbsp;&nbsp;说明：$v[description]
                       <br><br><br><br><br><br><br><br><br>
                </div>
EOT;
        }
        return $html;
    }

    /**
     * 差异呈现
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
                $showArr[] = $v . "：" . $orgObj[$k] . ' => ' . $newObj[$k];
            }
        }
        return implode('；', $showArr);
    }
}