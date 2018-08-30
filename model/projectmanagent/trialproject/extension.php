<?php

/**
 * @author Administrator
 * @Date 2012-06-18 16:25:39
 * @version 1.0
 * @description:延期申请 Model层
 */
class model_projectmanagent_trialproject_extension extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_trialproject_extension";
        $this->sql_map = "projectmanagent/trialproject/extensionSql.php";
        parent::__construct();
    }

    //根据不同的执行区域,配置相应的邮件接收人
    public $toMailArr = array(
        'GCSCX-01' => 'stone.zhang', //仪器仪表事务部：张磊（研发部）
// 		'GCSCX-02' => 'jianping.luo',//解决方案部：罗建平
// 		'GCSCX-04' => 'minliang.yu,ziyi.guo',//通信服务事业部：俞敏良、郭梓熠
// 		'GCSCX-05' => 'jianwei.su',//工作组：苏健威
// 		'GCSCX-06' => 'green.wang',//研发综合部：王周元
// 		'GCSCX-07' => 'jianwei.su',//其他类：苏健威
// 		'GCSCX-08' => 'yule.shao',//华东专区：邵玉乐
// 		'GCSCX-09' => 'xiwei.zhang'//西北专区：张喜伟
        'GCSCX-08' => 'dongsheng.wang', //华东区域：王东生
        'GCSCX-09' => 'dongsheng.wang', //西北区域：王东生
        'GCSCX-10' => 'dongsheng.wang', //东北区域：王东生
        'GCSCX-11' => 'dongsheng.wang', //华中区域：王东生
        'GCSCX-12' => 'dongsheng.wang', //西南区域：王东生
        'GCSCX-13' => 'dongsheng.wang', //华南区域：王东生
        'GCSCX-14' => 'dongsheng.wang', //集团区域：王东生
        'GCSCX-15' => 'dongsheng.wang', //系统商区域：王东生
        'GCSCX-17' => 'jianping.luo,heng.yin', //大数据部：罗建平、尹恒
    );

    /**
     * 重写edit_d
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //调用父类编辑
            parent :: edit_d($object, true);
            $this->commit_d();
            //处理附件名称和Id
            $this->updateObjWithFile($object['id']);
            return true;
        } catch (exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 根据试用项目Id 查找 最近一条延期申请ID
     */
    function findExtensionId($trialprojectId)
    {
        $sql = "select max(id) as extensionId from oa_trialproject_extension where trialprojectId = '" . $trialprojectId . "'";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['extensionId'];

    }

    //获取延期申请次数
    function getExtTime_d($tpid)
    {
        $sql = "select max(extensionTime) as extensionTime from oa_trialproject_extension where trialprojectId = '$tpid'";
        $result = $this->_db->get_one($sql);
        return $result['extensionTime'];
    }
    /**
     * 审批流回调方法
     */
    function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        if (!empty ($objId)) {
            $rows = $this->get_d($objId);
            if ($rows ['ExaStatus'] == "完成") {
                // 查询条件瓶装
                $extensionSql = !empty($rows['extensionDate']) ? "',closeDate = '" . $rows['extensionDate'] : "";
                $newProjectDaysSql = !empty($rows['newProjectDays']) ? "',projectDays = projectDays + '" . $rows['newProjectDays'] : "";
                $trialprojectId = $rows['trialprojectId'];
                //审批通过，更新试用项目的结束时间
                $updateSql = "update oa_trialproject_trialproject set serCon = '1',affirmMoney='" .
                    $rows['affirmMoney'] . $extensionSql . $newProjectDaysSql . "' where id=" . $trialprojectId;
                $this->query($updateSql);

                // 更新项目概算
                $projectDao = new model_engineering_project_esmproject();
                $projectDao->updateTriEstimates_d($rows['trialprojectId'], 'GCXMYD-04', $rows['affirmMoney']);

                //获取邮件接收人数组
                $trialDao = new model_projectmanagent_trialproject_trialproject();
                $trialArr = $trialDao->get_d($trialprojectId);
                if (!empty($trialArr['productLine'])) {
                    $emailDao = new model_common_mail();
                    $contractCode = $rows['trialprojectCode'];
                    $project_sql = "select * from oa_esm_project where contractCode ='{$contractCode}'";
                    $projectArr = $this->_db->getArray($project_sql);
                    $projectCode = $projectArr[0]['projectCode'];
                    $newProLine = $projectArr[0]['newProLine'];

                    $toMailId = 'dongsheng.wang'; //PK项目延期申请审批通过后，邮件通知给合同对应项目的项目经理，服务经理和王东生

                    if($newProLine == "HTCPX-DSJ"){// 如果执行区域是大数据的话,取办事处里的服务总监 PMS 686
                        $chkSql = "select GROUP_CONCAT(mainManagerId) as mainManagerId from oa_esm_office_baseinfo where productLine = 'GCSCX-17';";
                        $officeManagerId = $this->_db->get_one($chkSql);
                        $toMailId .= ($officeManagerId['mainManagerId'] != '') ? ',' . $officeManagerId['mainManagerId'] : '';
                    }else{
                        $managerDao = new model_engineering_officeinfo_manager();
                        $provinceObj = $managerDao->getManagerForMail_d($projectArr[0]['province'], $projectArr[0]['businessBelong']);
                        $toMailId .= ($provinceObj['areaManagerId'] != '') ? ',' . $provinceObj['areaManagerId'] : ''; //执行区域身份主要服务经理
                    }

                    $toMailId .= ($projectArr[0]['areaManagerId'] != '') ? ',' . $projectArr[0]['areaManagerId'] : ''; //项目内服务经理
                    $toMailId .= ($projectArr[0]['managerId'] != '') ? ',' . $projectArr[0]['managerId'] : ''; //项目经理

                    // 防止收件人出现重复，导致重复邮件
                    $arr = array_unique(explode(',', $toMailId));
                    $toMailId = implode(',', $arr);

                    $content_msg = "你好！{$contractCode} 项目延期已经通过审批。请项目经理收到本邮件后第一时间进行OA项目 {$projectCode} 变更（项目周期、项目计划、项目预算）。";
                    $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject_delay", $rows['trialprojectCode'], $toMailId, $content_msg); //试用项目延期申请通过后邮件通知
                }
            } else if($rows['ExaStatus'] == BACK) { // 审批打回后，将试用项目申请状态更新橙完成
                $trialprojectId = $rows['trialprojectId'];
                // 审批不通过，将试用项目申请更新为延期申请打回
                $updateSql = "update oa_trialproject_trialproject set serCon = '4' where id=" . $trialprojectId;
                $this->query($updateSql);
            }
        }
    }
}