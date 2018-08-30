<?php

/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 10:47:46
 * @version 1.0
 * @description:�ʼ����뵥�嵥 Model��
 */
class model_produce_quality_qualityapplyitem extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_produce_qualityapply_item";
        $this->sql_map = "produce/quality/qualityapplyitemSql.php";
        parent :: __construct();
    }

    //״̬����
    public $statusArr = array(
        0 => '�ʼ����',
        1 => '���ִ���',
        2 => '������',
        3 => '�ʼ����',
        4 => 'δ����'
    );

    //�ʼ���ȡ
    function getMail_d($thisKey)
    {
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailArr = isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
            'sendName' => '');
        return $mailArr;
    }

    /**
     * @param $ids
     * @param string $issend
     * @param $to_id
     * @param $passReason
     * @return bool
     */
    function confirmPass_d($ids, $issend = 'n', $to_id, $passReason)
    {
        try {
            $this->start_d();

            //���´����ϴ�����
            $this->updateStatus_d($ids, '0', $passReason);

            //���鴦��
            $idArr = explode(',', $ids);
            $applyIdArr = array();
            //ʵ�������뵥
            $qualityapplyDao = new model_produce_quality_qualityapply();

            foreach ($idArr as $val) {
                //������
                $obj = $this->getDetail_d($val);

                if (!in_array($obj['mainId'], $applyIdArr)) { //�˴���ȡ���뵥��Ϣ,Ȼ�����ں���������뵥״̬
                    array_push($applyIdArr, $obj['mainId']);
                }

                //��������������ϸ
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass(); //����ʵ��
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);
                $qualityapplyDao->ctDealRelItemPass($applyObj['relDocId'], $obj['relDocItemId'], $obj['qualityNum'], $relClassM);
                $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
            }

            //���µ���������
            foreach ($applyIdArr as $applyId) {
                $qualityapplyDao->renewStatus_d($applyId);
            }

            //�����ʼ�����
            if (!empty($to_id) && $issend == 'y') {
                //���ʼ�����
                $this->mailDeal_d('qualityapplyPass', $to_id, array('ids' => $ids, 'passReason' => $passReason));
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $ids
     * @param string $issend
     * @param $to_id
     * @param $passReason
     * @return bool
     */
    function damagePass_d($ids, $issend = 'n', $to_id, $passReason)
    {
        try {
            $this->start_d();

            //���´����ϴ�����
            $this->updateStatus_d($ids, '5', $passReason);

            //���鴦��
            $idArr = explode(',', $ids);
            $applyIdArr = array();
            //ʵ�������뵥
            $qualityapplyDao = new model_produce_quality_qualityapply();

            foreach ($idArr as $val) {
                //������
                $obj = $this->getDetail_d($val);

                if (!in_array($obj['mainId'], $applyIdArr)) { //�˴���ȡ���뵥��Ϣ,Ȼ�����ں���������뵥״̬
                    array_push($applyIdArr, $obj['mainId']);
                }

                //��������������ϸ
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass(); //����ʵ��
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);
                $qualityapplyDao->ctDealRelItemDamagePass($applyObj['relDocId'], $obj['relDocItemId'],
                    $obj['qualityNum'], $relClassM);
                $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
            }

            //���µ���������
            foreach ($applyIdArr as $applyId) {
                $qualityapplyDao->renewStatus_d($applyId);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    //��ȡ�ʼ�������ϸ
    function getDetail_d($id)
    {
        $this->searchArr = array('id' => $id);
        $rs = $this->list_d('select_confirmpass');
        return $rs[0];
    }

    //�������״̬
    function updateStatus_d($ids, $status, $passReason)
    {
        $sql = "update " . $this->tbl_name .
            " set status = '" . $status . "',dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',passReason = '" . $passReason . "' where id in ($ids)";
        return $this->_db->query($sql);
    }

    //�������ϴ�����Ϣ -- �ʼ�ϸ񲿷�
    function updateDeal_d($id, $standardNum, $complatedNum)
    {
        $sql = "update " . $this->tbl_name .
            " set dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',standardNum = standardNum + $standardNum,complatedNum = complatedNum + $complatedNum,status = if(complatedNum = qualityNum,3,2) where id = $id";
        return $this->_db->query($sql);
    }

    //�������ϴ�����Ϣ -- ���غϸ�
    function updateUndeal_d($id, $standardNum, $complatedNum)
    {
        $sql = "update " . $this->tbl_name .
            " set dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',standardNum = standardNum - $standardNum,complatedNum = complatedNum - $complatedNum,status = if(complatedNum = 0,0,2) where id = $id";
        return $this->_db->query($sql);
    }

    //���������´����� -- �����´������ʱ��ʹ�� -- ���Բ����漰�����״̬
    //@$assignNum �����´�����
    function updateAssignNum_d($id, $assignNum)
    {
        try {
            $this->start_d();

            //���������´�����
            $sql = "update oa_produce_qualityapply_item set assignNum= assignNum+" . $assignNum . " where id='" . $id . "'";
            $this->_db->query($sql);

            //���������ж���״̬
            $obj = $this->get_d($id);

            //���ݶ�Ӧ�����ж���ϸ״̬
            if ($obj['qualityNum'] == $obj['assignNum']) {
                $status = "2";
            } else {
                $status = "1";
            }

            //����״̬
            $conditionArr = array("id" => $id);
            $updateArr = array("id" => $id, 'status' => $status);

            //���������
            $updateArr['dealUserName'] = $_SESSION['USERNAME'];
            $updateArr['dealUserId'] = $_SESSION['USER_ID'];
            $updateArr['dealTime'] = date("Y-m-d H:i:s");

            $this->update($conditionArr, $updateArr);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * �ʼ���Ա����ȷ��
     */
    function ajaxReceive_d($ids)
    {
        $sql = "update " . $this->tbl_name . " set receiveStatus = 1,receiveId = '" . $_SESSION['USER_ID'] . "',receiveName = '" . $_SESSION['USERNAME'] . "',receiveTime = '" . date('Y-m-d H:i:s') . "' where id in ($ids)";
        return $this->_db->query($sql);
    }

    /**
     * ���ѡ�е�ID�����Ƿ������ͬԴ��û�б�ѡ�е����� PMS2386
     */
    function chkIsAllRelativeSelected($ids){
        $items = $this->findAll(" id in ({$ids})");
        $items = $this->applyItemsNumMatch($items);
        return $items;
    }

    // ��������ϸ������Դ������������ƥ�� PMS2386
    function applyItemsNumMatch($itemsArr){
        $qualityapplyDao = new model_produce_quality_qualityapply();
        $groupArr = array();
        foreach($itemsArr as $item){
            if(array_key_exists($item['mainId'],$groupArr)){
                $groupArr[$item['mainId']]['itemIds'][] = $item['id'];
            }else{
                $applyArr = $qualityapplyDao->find(" id = {$item['mainId']}");
                $relativeItemsArr = $this->findAll(" mainId in ({$item['mainId']})");
                $groupArr[$item['mainId']]['docCode'] = $applyArr['docCode'];// Դ�����
                $groupArr[$item['mainId']]['totalItmesNum'] = count($relativeItemsArr);// Դ��������������ϸ������
                $groupArr[$item['mainId']]['itemIds'] = array();
                $groupArr[$item['mainId']]['itemIds'][] = $item['id'];
            }
        }
        return $groupArr;
    }

    /**
     * �ʼ���Ա���
     */
    function ajaxBack_d($ids)
    {
        try {
            $this->start_d();

            //���鴦��
            $idArr = explode(',', $ids);
            //ʵ�������뵥
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyIdArr = array();
            //�ʼ�����
            $mailArr = array();

            foreach ($idArr as $id) {
                //������
                $obj = $this->getDetail_d($id);

                if (!in_array($obj['mainId'], $applyIdArr)) { //�˴���ȡ���뵥��Ϣ,���ں���������뵥״̬
                    array_push($applyIdArr, $obj['mainId']);
                }

                //ɾ��������
                $this->deletes($id);

                //��������������ϸ
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass (); //����ʵ��
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);

                $applyUserCode = $applyObj['applyUserCode']; //������id
                $relDocCode = $applyObj['relDocCode']; //Դ�����
                if (!array_key_exists($applyUserCode, $mailArr)) { //�˴����������˼�Դ����Ϣ,��ʽarray(������id=>Դ������ַ���),���ں��淢���ʼ�
                    $mailArr[$applyUserCode] = $relDocCode;
                } elseif (strstr($mailArr[$applyUserCode], $relDocCode) == false) {
                    $mailArr[$applyUserCode] = $mailArr[$applyUserCode] . ',' . $relDocCode;
                }

                //�����黹����Դ����Ϣ
                if ($applyObj['relDocType'] == 'ZJSQYDHH' || $applyObj['relDocType'] == 'ZJSQYDGH' || $applyObj['relDocType'] == 'ZJSQYDSC') {
                    $qualityapplyDao->ctDealRelItemBack($applyObj['relDocId'], $obj['relDocItemId'], $obj['qualityNum'], $relClassM);
                    $qualityapplyDao->ctDealRelInfoBack($applyObj['relDocId'], $relClassM);
                } elseif ($applyObj['relDocType'] == 'ZJSQYDSL') { //���ϴ���Դ����Ϣ
                    $equipmentDao = new model_purchase_arrival_equipment();
                    $equipmentDao->updateIsQualityBack($obj['relDocItemId'], 1);
                }
            }

            //ɾ������µ���
            foreach ($applyIdArr as $applyId) {
                $rs = $this->find(array('mainId' => $applyId), null, 'id');
                $applyObj = $qualityapplyDao->get_d($applyId);
                //��������ϸ����ɾ�����뵥��
                if ($applyObj['relDocType'] == 'ZJSQDLBF'){
                    $qualityTaskDao = new model_produce_quality_qualitytask();
                    $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
                    $qualityReportDao = new model_produce_quality_qualityereport();
                    $qualityReportEquItemDao = new model_produce_quality_qualityereportequitem();
                    $qualityReportItemDao = new model_produce_quality_qualityereportitem();

                    // ɾ���ʼ����뵥
                    $qualityapplyDao->delete(" id={$applyId}");
                    // ɾ���ʼ����뵥ʣ����ϸ
                    if(empty($rs)){
                        $this->delete(" mainId={$applyId}");
                    }

                    // ɾ�������ʼ������Լ���ϸ
                    $relativeTasksArr = $qualityTaskDao->findAll(" applyId = {$applyId}");
                    foreach($relativeTasksArr as $val){
                        $qualityTaskItemDao->delete(" mainId={$val['id']}");
                    }
                    $qualityTaskDao->delete(" applyId = {$applyId}");

                    // ɾ�������ʼ챨���Լ���ϸ
                    $relativeReportsArr = $qualityReportDao->findAll(" applyId = {$applyId}");
                    foreach($relativeReportsArr as $val){
                        $qualityReportEquItemDao->delete(" mainId={$val['id']}");
                        $qualityReportItemDao->delete(" mainId={$val['id']}");
                    }
                    $qualityReportDao->delete(" applyId = {$applyId}");

                    // �����������ⵥ
                    $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();
                    $blockeququalityapplyDao->dealRelItemBack($applyObj['relDocId']);
                    $blockeququalityapplyDao->dealRelInfoBack($applyObj['relDocId']);

                }elseif (empty($rs)) {
                    $qualityapplyDao->deletes($applyId);
                } else {
                    $qualityapplyDao->renewStatus_d($applyId);
                }
            }

            //�����ʼ�����
            if (!empty($mailArr)) {
                foreach ($mailArr as $key => $val) {
                    $this->mailDeal_d('qualityapplyBackByManager', $key, array('relDocCodes' => $val));
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���ݹ�������ID��ȡ��Ϣ
     */
    function getApplyItem_d($relDocItemId)
    {
        $this->searchArr = array('relDocItemId' => $relDocItemId);
        $data = $this->listBySqlId();
        return $data['0'];
    }
}