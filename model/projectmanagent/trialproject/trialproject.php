<?php

/**
 * @author Administrator
 * @Date 2012-05-15 14:04:07
 * @version 1.0
 * @description:试用项目 Model层
 */
class model_projectmanagent_trialproject_trialproject extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_trialproject_trialproject";
        $this->sql_map = "projectmanagent/trialproject/trialprojectSql.php";
        parent::__construct();
    }

    //数据字典字段处理
    public $datadictFieldArr = array(
        'module', 'customerType'
    );

    /**
     * 添加对象
     */
    function add_d($object, $isAddInfo = false)
    {
        try {
            $this->start_d();
            $items = $object['product'];
            unset($object['product']);

            //处理数据字典字段
            $object = $this->processDatadict($object);

            //编号 add by chengl
            $codeRule = new model_common_codeRule();
            $object['projectCode'] = $codeRule->pkCode($object);
            if ($isAddInfo) {
                $object = $this->addCreateInfo($object);
            }
            // 产品线冗余
            $newProLineStr = "";
            //查找处理 所选产品的 最高类型
            foreach ($items as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $exeDeptNameStr[] = $v['exeDeptCode'];
                    $newProLineStr .= $v['newProLineCode'] . ",";
                } else if ($v['isDelTag'] == '1') {
                    unset ($items[$k]);
                }
            }
            $exeDeptNameStr = array_unique($exeDeptNameStr);
            $exeDeptNameStr = implode(",", $exeDeptNameStr);
            $object['productLine'] = $exeDeptNameStr;
            $object['newProLineStr'] = rtrim($newProLineStr, ',');
            $newId = $this->create($object);

            $trialprijectEquDao = new model_projectmanagent_trialproject_trialprojectEqu();
            $items = util_arrayUtil:: setArrayFn(array('trialprojectId' => $newId), $items);
            if ($items) {
                $trialprijectEquDao->saveDelBatch($items);
            }
            //处理附件名称和Id
            $this->updateObjWithFile($newId);
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 重写编辑方法
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $items = $object['product'];

            //处理数据字典字段
            $object = $this->processDatadict($object);

            // 产品线冗余
            $newProLineStr = "";
            //查找处理 所选产品的 最高类型
            foreach ($items as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    if ($v['id']) {
                        $proTypeIds[] = $v['id'];
                    }
                    $newProLineStr .= $v['newProLineCode'] . ",";
                }
            }
            $proTypeIds = implode(",", $proTypeIds);
            //产品线
            if (!empty($proTypeIds)) {
                $sqlf = "select exeDeptCode from oa_trialproject_trialproject_item where id in ($proTypeIds)";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                foreach ($exeDeptNameArr as $k => $v) {
                    $exeDeptNameStr[] = $v['exeDeptCode'];
                }
                $exeDeptNameStr = array_unique($exeDeptNameStr);
                $exeDeptNameStr = implode(",", $exeDeptNameStr);
                $object['productLine'] = $exeDeptNameStr;
            }
            $object['newProLineStr'] = rtrim($newProLineStr, ',');
            //修改主表信息
            parent:: edit_d($object, true);

            $pdi = $object['id'];
            $trialprijectEquDao = new model_projectmanagent_trialproject_trialprojectEqu();
            $items = util_arrayUtil:: setArrayFn(array('trialprojectId' => $pdi), $items);
            if ($items) {
                $trialprijectEquDao->saveDelBatch($items);
            }
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 提交方法
     */
    function subConproject_d($id)
    {
        try {
            $sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
            $this->_db->query($sql);
            $arr = $this->get_d($id);

            //获取默认发送人
            include(WEB_TOR . "model/common/mailConfig.php");
            $toMailId = isset($mailUser['trialprojectCon']) ?  $mailUser['trialprojectCon']['TO_ID'] : '';
            $emailDao = new model_common_mail();
            $emailDao->toStrialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_trialproject_trialproject", $arr['projectCode'], "通过", $toMailId, "");

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 打回
     */
    function backConproject_d($id)
    {
        try {
            $sql = "update oa_trialproject_trialproject set serCon=2 where id = $id ";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 延期申请打回
     */
    function backDelay_d($id)
    {
        try {
            $sql = "update oa_trialproject_trialproject set serCon=4 where id = $id ";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据试用项目id 获取合同信息
     */
    function getContractBytrId($tid)
    {
        if ($tid) {
            $sql = "select id,contractCode from oa_contract_contract " .
                "where trialprojectId = '" . $tid . "' or " .
                "chanceId = (select chanceId from oa_trialproject_trialproject where id = '" . $tid . "')";
            $Carr = $this->_db->getArray($sql);
            return $Carr;
        }
        return "";
    }

    /**
     * 关闭试用项目
     */
    function ajaxCloseTr_d($id)
    {
        try {
            $sql = "update oa_trialproject_trialproject set isFail = '2' where id = '" . $id . "' ";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据合同ID 获取关联的试用项目ID
     */
    function getTrialIdByconId($cid)
    {
        $sql = "select chanceId,trialprojectId  from oa_contract_contract where id = '" . $cid . "'";
        $arr = $this->_db->getArray($sql);

        $chanceId = $arr[0]['chanceId'];
        $trialprojectId = $arr[0]['trialprojectId'];
        if (!empty($chanceId)) {
            $sqlByChance = "select id from oa_trialproject_trialproject where chanceId = '" . $chanceId . "'";
            $arrA = $this->_db->getArray($sqlByChance);
        }
        $returnStr = "";
        if (!empty($arrA)) {
            foreach ($arrA as $k => $v) {
                $tid = $v['id'];
                $returnStr .= $tid . ",";
            }
        }
        if (!empty($trialprojectId)) {
            $returnStr .= $trialprojectId;
        }
        //去除重复的id
        $tempArr = explode(",", $returnStr);
        $tempArr = array_flip($tempArr);
        $tempArr = array_flip($tempArr);

        $returnStr = implode(",", $tempArr);

        return $returnStr;

    }

    //添加
    function addExeDeptName_d($objArr)
    {
        $trialEquDao = new model_projectmanagent_trialproject_trialprojectEqu();
        $resultArr = $trialEquDao->getTrialEqu_d($objArr['id']);
        foreach ($resultArr as $k => $v) {
            $sqlf = "select exeDeptCode from oa_goods_base_info where id = '" . $v['conProductId'] . "'";
            $exeDeptNameArr = $this->_db->get_one($sqlf);
            $exeDeptNameStr[] = $exeDeptNameArr['exeDeptCode'];
        }
//		$exeDeptNameStr = implode(",",$exeDeptNameStr);
        return $exeDeptNameStr;
    }

    /**
     * workflow callback
     */
    function workflowCallBack($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        if (!empty ($objId)) {
            $rows = $this->get_d($objId);
            if ($rows ['ExaStatus'] == "完成") {
                //获取默认发送人
                include(WEB_TOR . "model/common/mailConfig.php");
                //根据执行部门写死邮件推送人 -暂时
//                        $toMailArr = array(
//                            'GCSCX-01' => "stone",//仪器仪表事业部
//                            'GCSCX-02' => "heng.yin,jianping.luo",//解决方案部
//                            'GCSCX-09' => "xiwei.zhang",//西北专区
//                            'GCSCX-08' => "yule.shao",//华东专区
//                            'GCSCX-04' => "minliang.yu",//通信服务事业部
//                            'GCSCX-06' => "green.wang",//研发综合部
//                        );
//                        $proLine = $rows['productLine'];

//                        $toMailId = $toMailArr[$proLine];
//						$toMailId = $mailUser['trialproject']['sendUserId']; //邮件接收人ID
//                        $toMailId = "jianping.luo,dongsheng.wang";//pms2046 pk项目申请审批通过后，通知对应的产线成本确认人，可立项。传统服务：王东生  大数据：罗建平
                $toMailId = ($rows['productLine'] == 'GCSCX-17') ? 'jianping.luo' : 'dongsheng.wang';//大数据：罗建平，其他产线都是 王东生
                $emailDao = new model_common_mail();
                $content_msg = "你好！{$rows['projectCode']} 项目申请已经通过审批。请登陆OA至【工程管理--项目管理--试用项目】立项。";
                $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject", $rows['projectCode'], $toMailId, $content_msg);//"试用申请通过邮件通知"
            } else if ($rows['ExaStatus'] == "打回") {
                $sql = "update oa_trialproject_trialproject set serCon=2,status=0 where id = $objId";
                $this->query($sql);
            }
        }
    }
}