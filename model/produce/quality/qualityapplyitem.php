<?php

/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 10:47:46
 * @version 1.0
 * @description:质检申请单清单 Model层
 */
class model_produce_quality_qualityapplyitem extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_produce_qualityapply_item";
        $this->sql_map = "produce/quality/qualityapplyitemSql.php";
        parent :: __construct();
    }

    //状态数组
    public $statusArr = array(
        0 => '质检放行',
        1 => '部分处理',
        2 => '处理中',
        3 => '质检完成',
        4 => '未处理'
    );

    //邮件获取
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

            //更新此物料处理结果
            $this->updateStatus_d($ids, '0', $passReason);

            //数组处理
            $idArr = explode(',', $ids);
            $applyIdArr = array();
            //实例化申请单
            $qualityapplyDao = new model_produce_quality_qualityapply();

            foreach ($idArr as $val) {
                //本对象
                $obj = $this->getDetail_d($val);

                if (!in_array($obj['mainId'], $applyIdArr)) { //此处获取申请单信息,然后用于后面更新申请单状态
                    array_push($applyIdArr, $obj['mainId']);
                }

                //更新申请收料明细
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass(); //策略实例
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);
                $qualityapplyDao->ctDealRelItemPass($applyObj['relDocId'], $obj['relDocItemId'], $obj['qualityNum'], $relClassM);
                $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
            }

            //更新单据完成情况
            foreach ($applyIdArr as $applyId) {
                $qualityapplyDao->renewStatus_d($applyId);
            }

            //调用邮件发送
            if (!empty($to_id) && $issend == 'y') {
                //新邮件处理
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

            //更新此物料处理结果
            $this->updateStatus_d($ids, '5', $passReason);

            //数组处理
            $idArr = explode(',', $ids);
            $applyIdArr = array();
            //实例化申请单
            $qualityapplyDao = new model_produce_quality_qualityapply();

            foreach ($idArr as $val) {
                //本对象
                $obj = $this->getDetail_d($val);

                if (!in_array($obj['mainId'], $applyIdArr)) { //此处获取申请单信息,然后用于后面更新申请单状态
                    array_push($applyIdArr, $obj['mainId']);
                }

                //更新申请收料明细
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass(); //策略实例
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);
                $qualityapplyDao->ctDealRelItemDamagePass($applyObj['relDocId'], $obj['relDocItemId'],
                    $obj['qualityNum'], $relClassM);
                $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
            }

            //更新单据完成情况
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

    //获取质检申请明细
    function getDetail_d($id)
    {
        $this->searchArr = array('id' => $id);
        $rs = $this->list_d('select_confirmpass');
        return $rs[0];
    }

    //变更物料状态
    function updateStatus_d($ids, $status, $passReason)
    {
        $sql = "update " . $this->tbl_name .
            " set status = '" . $status . "',dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',passReason = '" . $passReason . "' where id in ($ids)";
        return $this->_db->query($sql);
    }

    //更新物料处理信息 -- 质检合格部分
    function updateDeal_d($id, $standardNum, $complatedNum)
    {
        $sql = "update " . $this->tbl_name .
            " set dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',standardNum = standardNum + $standardNum,complatedNum = complatedNum + $complatedNum,status = if(complatedNum = qualityNum,3,2) where id = $id";
        return $this->_db->query($sql);
    }

    //更新物料处理信息 -- 撤回合格
    function updateUndeal_d($id, $standardNum, $complatedNum)
    {
        $sql = "update " . $this->tbl_name .
            " set dealTime = '" . date("Y-m-d H:i:s") . "',dealUserName='" . $_SESSION['USERNAME'] . "',dealUserId='" . $_SESSION['USER_ID'] .
            "',standardNum = standardNum - $standardNum,complatedNum = complatedNum - $complatedNum,status = if(complatedNum = 0,0,2) where id = $id";
        return $this->_db->query($sql);
    }

    //更新物料下达数量 -- 申请下达任务的时候使用 -- 所以不会涉及到完成状态
    //@$assignNum 单次下达数量
    function updateAssignNum_d($id, $assignNum)
    {
        try {
            $this->start_d();

            //更新物料下达数量
            $sql = "update oa_produce_qualityapply_item set assignNum= assignNum+" . $assignNum . " where id='" . $id . "'";
            $this->_db->query($sql);

            //根据数量判断其状态
            $obj = $this->get_d($id);

            //根据对应数量判断明细状态
            if ($obj['qualityNum'] == $obj['assignNum']) {
                $status = "2";
            } else {
                $status = "1";
            }

            //更新状态
            $conditionArr = array("id" => $id);
            $updateArr = array("id" => $id, 'status' => $status);

            //如果做完了
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
     * 质检人员接收确认
     */
    function ajaxReceive_d($ids)
    {
        $sql = "update " . $this->tbl_name . " set receiveStatus = 1,receiveId = '" . $_SESSION['USER_ID'] . "',receiveName = '" . $_SESSION['USERNAME'] . "',receiveTime = '" . date('Y-m-d H:i:s') . "' where id in ($ids)";
        return $this->_db->query($sql);
    }

    /**
     * 检查选中的ID里面是否存在相同源单没有被选中的物料 PMS2386
     */
    function chkIsAllRelativeSelected($ids){
        $items = $this->findAll(" id in ({$ids})");
        $items = $this->applyItemsNumMatch($items);
        return $items;
    }

    // 把申请明细数组与源单关联数据相匹配 PMS2386
    function applyItemsNumMatch($itemsArr){
        $qualityapplyDao = new model_produce_quality_qualityapply();
        $groupArr = array();
        foreach($itemsArr as $item){
            if(array_key_exists($item['mainId'],$groupArr)){
                $groupArr[$item['mainId']]['itemIds'][] = $item['id'];
            }else{
                $applyArr = $qualityapplyDao->find(" id = {$item['mainId']}");
                $relativeItemsArr = $this->findAll(" mainId in ({$item['mainId']})");
                $groupArr[$item['mainId']]['docCode'] = $applyArr['docCode'];// 源单编号
                $groupArr[$item['mainId']]['totalItmesNum'] = count($relativeItemsArr);// 源单下所有申请明细的数量
                $groupArr[$item['mainId']]['itemIds'] = array();
                $groupArr[$item['mainId']]['itemIds'][] = $item['id'];
            }
        }
        return $groupArr;
    }

    /**
     * 质检人员打回
     */
    function ajaxBack_d($ids)
    {
        try {
            $this->start_d();

            //数组处理
            $idArr = explode(',', $ids);
            //实例化申请单
            $qualityapplyDao = new model_produce_quality_qualityapply();
            $applyIdArr = array();
            //邮件数组
            $mailArr = array();

            foreach ($idArr as $id) {
                //本对象
                $obj = $this->getDetail_d($id);

                if (!in_array($obj['mainId'], $applyIdArr)) { //此处获取申请单信息,用于后面更新申请单状态
                    array_push($applyIdArr, $obj['mainId']);
                }

                //删除本对象
                $this->deletes($id);

                //更新申请收料明细
                $relClass = $qualityapplyDao->getStrategy_d($obj['mainId']);
                $relClassM = new $relClass (); //策略实例
                $applyObj = $qualityapplyDao->get_d($obj['mainId']);

                $applyUserCode = $applyObj['applyUserCode']; //申请人id
                $relDocCode = $applyObj['relDocCode']; //源单编号
                if (!array_key_exists($applyUserCode, $mailArr)) { //此处构建申请人及源单信息,形式array(申请人id=>源单编号字符串),用于后面发送邮件
                    $mailArr[$applyUserCode] = $relDocCode;
                } elseif (strstr($mailArr[$applyUserCode], $relDocCode) == false) {
                    $mailArr[$applyUserCode] = $mailArr[$applyUserCode] . ',' . $relDocCode;
                }

                //换货归还处理源单信息
                if ($applyObj['relDocType'] == 'ZJSQYDHH' || $applyObj['relDocType'] == 'ZJSQYDGH' || $applyObj['relDocType'] == 'ZJSQYDSC') {
                    $qualityapplyDao->ctDealRelItemBack($applyObj['relDocId'], $obj['relDocItemId'], $obj['qualityNum'], $relClassM);
                    $qualityapplyDao->ctDealRelInfoBack($applyObj['relDocId'], $relClassM);
                } elseif ($applyObj['relDocType'] == 'ZJSQYDSL') { //收料处理源单信息
                    $equipmentDao = new model_purchase_arrival_equipment();
                    $equipmentDao->updateIsQualityBack($obj['relDocItemId'], 1);
                }
            }

            //删除或更新单据
            foreach ($applyIdArr as $applyId) {
                $rs = $this->find(array('mainId' => $applyId), null, 'id');
                $applyObj = $qualityapplyDao->get_d($applyId);
                //不存在明细，则删除申请单据
                if ($applyObj['relDocType'] == 'ZJSQDLBF'){
                    $qualityTaskDao = new model_produce_quality_qualitytask();
                    $qualityTaskItemDao = new model_produce_quality_qualitytaskitem();
                    $qualityReportDao = new model_produce_quality_qualityereport();
                    $qualityReportEquItemDao = new model_produce_quality_qualityereportequitem();
                    $qualityReportItemDao = new model_produce_quality_qualityereportitem();

                    // 删除质检申请单
                    $qualityapplyDao->delete(" id={$applyId}");
                    // 删除质检申请单剩余明细
                    if(empty($rs)){
                        $this->delete(" mainId={$applyId}");
                    }

                    // 删除关联质检任务以及明细
                    $relativeTasksArr = $qualityTaskDao->findAll(" applyId = {$applyId}");
                    foreach($relativeTasksArr as $val){
                        $qualityTaskItemDao->delete(" mainId={$val['id']}");
                    }
                    $qualityTaskDao->delete(" applyId = {$applyId}");

                    // 删除关联质检报告以及明细
                    $relativeReportsArr = $qualityReportDao->findAll(" applyId = {$applyId}");
                    foreach($relativeReportsArr as $val){
                        $qualityReportEquItemDao->delete(" mainId={$val['id']}");
                        $qualityReportItemDao->delete(" mainId={$val['id']}");
                    }
                    $qualityReportDao->delete(" applyId = {$applyId}");

                    // 更新其他出库单
                    $blockeququalityapplyDao = new model_produce_quality_strategy_blockeququalityapply ();
                    $blockeququalityapplyDao->dealRelItemBack($applyObj['relDocId']);
                    $blockeququalityapplyDao->dealRelInfoBack($applyObj['relDocId']);

                }elseif (empty($rs)) {
                    $qualityapplyDao->deletes($applyId);
                } else {
                    $qualityapplyDao->renewStatus_d($applyId);
                }
            }

            //调用邮件发送
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
     * 根据关联物料ID获取信息
     */
    function getApplyItem_d($relDocItemId)
    {
        $this->searchArr = array('relDocItemId' => $relDocItemId);
        $data = $this->listBySqlId();
        return $data['0'];
    }
}