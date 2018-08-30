<?php

/**
 * @author Administrator
 * @Date 2012-05-15 14:04:07
 * @version 1.0
 * @description:������Ŀ Model��
 */
class model_projectmanagent_trialproject_trialproject extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_trialproject_trialproject";
        $this->sql_map = "projectmanagent/trialproject/trialprojectSql.php";
        parent::__construct();
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'module', 'customerType'
    );

    /**
     * ��Ӷ���
     */
    function add_d($object, $isAddInfo = false)
    {
        try {
            $this->start_d();
            $items = $object['product'];
            unset($object['product']);

            //���������ֵ��ֶ�
            $object = $this->processDatadict($object);

            //��� add by chengl
            $codeRule = new model_common_codeRule();
            $object['projectCode'] = $codeRule->pkCode($object);
            if ($isAddInfo) {
                $object = $this->addCreateInfo($object);
            }
            // ��Ʒ������
            $newProLineStr = "";
            //���Ҵ��� ��ѡ��Ʒ�� �������
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
            //���������ƺ�Id
            $this->updateObjWithFile($newId);
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��д�༭����
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $items = $object['product'];

            //���������ֵ��ֶ�
            $object = $this->processDatadict($object);

            // ��Ʒ������
            $newProLineStr = "";
            //���Ҵ��� ��ѡ��Ʒ�� �������
            foreach ($items as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    if ($v['id']) {
                        $proTypeIds[] = $v['id'];
                    }
                    $newProLineStr .= $v['newProLineCode'] . ",";
                }
            }
            $proTypeIds = implode(",", $proTypeIds);
            //��Ʒ��
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
            //�޸�������Ϣ
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
     * �ύ����
     */
    function subConproject_d($id)
    {
        try {
            $sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
            $this->_db->query($sql);
            $arr = $this->get_d($id);

            //��ȡĬ�Ϸ�����
            include(WEB_TOR . "model/common/mailConfig.php");
            $toMailId = isset($mailUser['trialprojectCon']) ?  $mailUser['trialprojectCon']['TO_ID'] : '';
            $emailDao = new model_common_mail();
            $emailDao->toStrialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_trialproject_trialproject", $arr['projectCode'], "ͨ��", $toMailId, "");

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���
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
     * ����������
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
     * ����������Ŀid ��ȡ��ͬ��Ϣ
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
     * �ر�������Ŀ
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
     * ���ݺ�ͬID ��ȡ������������ĿID
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
        //ȥ���ظ���id
        $tempArr = explode(",", $returnStr);
        $tempArr = array_flip($tempArr);
        $tempArr = array_flip($tempArr);

        $returnStr = implode(",", $tempArr);

        return $returnStr;

    }

    //���
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
            if ($rows ['ExaStatus'] == "���") {
                //��ȡĬ�Ϸ�����
                include(WEB_TOR . "model/common/mailConfig.php");
                //����ִ�в���д���ʼ������� -��ʱ
//                        $toMailArr = array(
//                            'GCSCX-01' => "stone",//�����Ǳ���ҵ��
//                            'GCSCX-02' => "heng.yin,jianping.luo",//���������
//                            'GCSCX-09' => "xiwei.zhang",//����ר��
//                            'GCSCX-08' => "yule.shao",//����ר��
//                            'GCSCX-04' => "minliang.yu",//ͨ�ŷ�����ҵ��
//                            'GCSCX-06' => "green.wang",//�з��ۺϲ�
//                        );
//                        $proLine = $rows['productLine'];

//                        $toMailId = $toMailArr[$proLine];
//						$toMailId = $mailUser['trialproject']['sendUserId']; //�ʼ�������ID
//                        $toMailId = "jianping.luo,dongsheng.wang";//pms2046 pk��Ŀ��������ͨ����֪ͨ��Ӧ�Ĳ��߳ɱ�ȷ���ˣ��������ͳ����������  �����ݣ��޽�ƽ
                $toMailId = ($rows['productLine'] == 'GCSCX-17') ? 'jianping.luo' : 'dongsheng.wang';//�����ݣ��޽�ƽ���������߶��� ������
                $emailDao = new model_common_mail();
                $content_msg = "��ã�{$rows['projectCode']} ��Ŀ�����Ѿ�ͨ�����������½OA�������̹���--��Ŀ����--������Ŀ�����";
                $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject", $rows['projectCode'], $toMailId, $content_msg);//"��������ͨ���ʼ�֪ͨ"
            } else if ($rows['ExaStatus'] == "���") {
                $sql = "update oa_trialproject_trialproject set serCon=2,status=0 where id = $objId";
                $this->query($sql);
            }
        }
    }
}