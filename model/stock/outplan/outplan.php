<?php
/**
 * @author zengzx
 * @Date 2011��5��5�� 14:36:49
 * @version 1.0
 * @description:����֪ͨ�� Model��
 */
header("Content-type: text/html; charset=gb2312");

class model_stock_outplan_outplan extends model_base
{

    function __construct()
    {
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->tbl_name = "oa_stock_outplan";
        $this->sql_map = "stock/outplan/outplanSql.php";
        $this->mailArr = isset($mailUser) ? $mailUser[$this->tbl_name] : "";
        parent::__construct();
        $this->relatedStrategyArr = array( //��ͬ���ͳ������������,������Ҫ���������׷��
            "oa_contract_contract" => "model_stock_outplan_strategy_contractplan", //���۷���
            "oa_borrow_borrow" => "model_stock_outplan_strategy_borrowplan", //���÷���
            "oa_present_present" => "model_stock_outplan_strategy_presentplan", //���ͷ���
            "oa_contract_exchangeapply" => "model_stock_outplan_strategy_exchangeplan", //�˻�����
        );
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    /*===================================ҳ��ģ��======================================*/
    /**
     * @description �����ƻ��б���ʾģ��
     * @param $rows
     */
    function showList($rows, planStrategy $istrategy)
    {
        $istrategy->showList($rows);
    }

    /**
     * @description ���������ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemAdd($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemAdd($rows);
    }

    /**
     * @description �޸ķ����ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemEdit($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemEdit($rows);
    }

    /**
     * @description ��������ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemChange($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemChange($rows);
    }

    /**
     * @description �鿴�����ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemView($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemView($rows);
    }

    /**
     * @description �����ƻ��鿴������ת��������
     * @param $rows
     */
    function shwoBToOEquView($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEquView($rows, $rowNum);
    }

    /**
     * @description �����ƻ����������ת��������
     * @param $rows
     */
    function shwoBToOEquChange($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEquChange($rows, $rowNum);
    }

    /**
     * @description �����ƻ��༭������ת��������
     * @param $rows
     */
    function shwoBToOEqu($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEqu($rows, $rowNum);
    }

    /**
     * �鿴���ҵ����Ϣ
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false, $skey = false, planStrategy $istrategy)
    {
        return $istrategy->viewRelInfo($paramArr, $skey);
    }

    /**
     * ���ƻ�ȡԴ�����ݷ���
     */
    function getDocInfo($id, planStrategy $strategy)
    {
        $rows = $strategy->getDocInfo($id);
        return $rows;
    }

    /**
     * ��ȡ��ͬ�����˷���
     */
    function getSaleman($id, planStrategy $strategy)
    {
        $salemanArr = $strategy->getSaleman($id);
        return $salemanArr;
    }


    /**
     * ��ȡ��ͬ�����˷���
     */
    function getCreateman($id, planStrategy $strategy)
    {
        $createmanArr = $strategy->getCreateman($id);
        return $createmanArr;
    }

    /**
     * ���������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtAdd(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
    }

    /**
     * ���������ƻ�ʱ�ʼ�����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealMailAtAdd(planStrategy $istrategy, $object = false)
    {
        return $istrategy->dealMailAtAdd($object);
    }


    /**
     * ��������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtChange(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtChange($paramArr, $relItemArr);
    }

    /**
     * �޸ķ����ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtEdit(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
    }

    /**
     * ɾ�������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtDel(planStrategy $istrategy, $id)
    {
        return $istrategy->dealRelInfoAtDel($id);
    }

    /**
     * ��Ӷ���
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $codeRuleDao = new model_common_codeRule();
            $object['planCode'] = $codeRuleDao->sendPlanCode($this->tbl_name);
            //�������ת��������
            if (!empty($object['productsdetailBo'])) {
                if (!empty($object['productsdetail'])) {
                    $object['productsdetail'] = array_merge($object['productsdetail'], $object['productsdetailBo']);
                } else {
                    $object['productsdetail'] = $object['productsdetailBo'];
                }

                unset($object['productsdetailBo']);
            }
            $id = parent::add_d($object, true);
            $docType = isset ($object['docType']) ? $object['docType'] : null;
            if ($docType) { //���ڷ����ƻ�����
                $outStrategy = $this->relatedStrategyArr[$docType];
                if ($outStrategy) {
                    $paramArr = array( //�����������
                        'mainId' => $id,
                        'docId' => $object['docId'],
                        'docCode' => $object['docCode'],
                        'docType' => $object['docType'],
                    ); //...���Լ���׷��
                    if (is_array($object['productsdetail'])) {
                        $relItemArr = $object['productsdetail']; //�����嵥��Ϣ
                        foreach ($relItemArr as $k => $v) {
                            if ($v['isDelTag'] == '1') {
                                unset ($relItemArr[$k]);
                            }
                        }
                        $prod = new model_stock_outplan_outplanProduct();
                        $mainIdArr = array('mainId' => $paramArr['docId']);
                        $prod->createBatch($relItemArr, $paramArr);
                    } else {
                        throw new Exception("������Ϣ����������ȷ��!");
                    }
                    //ͳһѡ����ԣ�������Ե�ҵ����
                    $storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $paramArr, $relItemArr);
                } else {
                    throw new Exception("�����ͷ���������δ���ţ�����ϵ������Ա!");
                }
//				$this->setHasBorrowStatus($id);
//				$addMsg = $this->getAddMes($object);
                $this->mailTo($object);
                $this->updateBusinessByShip($id);

                //����ƻ��������ڴ���ϣ���������������ʼ�֪ͨ��ͬ������ȥ���з����ƻ���ȷ��
//				if($object['isNeedConfirm'] == 1){
//					$contractDao = new model_contract_contract_contract();
//					$contractArr = $contractDao->find(array('id'=>$object['docId']));
//					$this->mailDeal_d('confirmOutPlan',$contractArr['prinvipalId'],Array('name' => $_SESSION['USERNAME'],
//							'planCode' =>$object['planCode'],'overTimeReason' => $object['overTimeReason']));
//				}
            } else {
                throw new Exception("������Ϣ����������ȷ��!");
            }
            /*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ����id��������뵥������Ϣ  -- ���˽�����ת��������
     */
    function get_d($id)
    {
        $outplanInfo = parent :: get_d($id);
        $itemDao = new model_stock_outplan_outplanProduct();
        $searchArr = array(
            'mainId' => $id,
            'isDelete' => 0,
            'BToOTips' => 0
        );
        $outplanInfo['details'] = $itemDao->findAll($searchArr);
        return $outplanInfo;
    }


    /**
     * ����id��������뵥������Ϣ
     */
    function getToView_d($id)
    {
        $outplanInfo = parent :: get_d($id);
        $itemDao = new model_stock_outplan_outplanProduct();
        $searchArr = array(
            'mainId' => $id,
            'BToOTips' => 0
        );
        $outplanInfo['details'] = $itemDao->findAll($searchArr, 'isDelete');
        return $outplanInfo;
    }

    /**
     * ��ӵȼ���Ϣ
     */
    function edit_star($object, $isEditInfo = false)
    {
        return parent::edit_d($object, $isEditInfo);
    }

    /**
     * ����id��������뵥������Ϣ
     */
    function getToShip_d($id)
    {
        $outplanInfo = parent :: get_d($id);
        $itemDao = new model_stock_outplan_outplanProduct();
        $searchArr = array(
            'mainId' => $id
        );
        $outplanInfo['details'] = $itemDao->findAll($searchArr, 'isDelete');
        return $outplanInfo;
    }

    /**
     * �༭����
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $id = parent::edit_d($object, true);
            $docType = isset ($object['docType']) ? $object['docType'] : null;
            if ($docType) { //���ڷ����ƻ�����
                $outStrategy = $this->relatedStrategyArr[$docType];
                if ($outStrategy) {
                    $paramArr = array( //�����������
                        'mainId' => $object['id'],
                        'docId' => $object['docId'],
                        'docCode' => $object['docCode'],
                        'docType' => $object['docType'],
                    ); //...���Լ���׷��
                    $bItemArr = $object['bItem'];
                    unset($object['bItem']);
                    if (is_array($bItemArr)) {
                        if (!is_array($object['productsdetail'])) {
                            $object['productsdetail'] = array();
                        }
                        foreach ($bItemArr as $key => $val) {
                            array_push($object['productsdetail'], $val);
                        }
                    }
                    if (is_array($object['productsdetail'])) {
                        $relItemArr = $object['productsdetail']; //�����嵥��Ϣ
                        foreach ($relItemArr as $key => $val) {
                            unset($relItemArr[$key]['issuedShipNum']);
                        }
                        $prod = new model_stock_outplan_outplanProduct();
                        $mainIdArr = array('mainId' => $object['id']);
                        $storageproId = $this->ctDealRelInfoAtDel(new $outStrategy (), $object['id']);
                        $prod->delete($mainIdArr);
                        $prod->createBatch($relItemArr, $paramArr);
                    } else {
                        throw new Exception("������Ϣ����������ȷ��!");
                    }
                    //ͳһѡ����ԣ�������Ե�ҵ����
                    $relItemArr = $object['productsdetail']; //�����嵥��Ϣ
                    $storageproId = $this->ctDealRelInfoAtChange(new $outStrategy (), $paramArr, $relItemArr);
                    $this->setHasBorrowStatus($id);
                } else {
                    throw new Exception("�����ͳ���������δ���ţ�����ϵ������Ա!");
                }
            } else {
                throw new Exception("������Ϣ����������ȷ��!");
            }
            /*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ����
     */
    function feedback_d($object)
    {
        $id = parent::edit_d($object, true);
        $docType = isset ($object['docType']) ? $object['docType'] : null;
        return $id;
    }


    /*****************************************������� start****************************************************/
    /**
     * ���·����ƻ�����״̬
     */
    function updatePlanOutStatus($id)
    {

        //�鿴�����ƻ�δ��������
        $planRemainSql = "select count(*) as countNum,(select  sum(o.executedNum) from oa_stock_outplan_product o
		where o.mainId=" . $id . " and o.isDelete=0) as executedNum from (select  (o.number-o.executedNum)
		 as remainNum,o.executedNum from oa_stock_outplan_product o  where o.mainId=" . $id . " and o.isDelete=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($planRemainSql);
        //�鿴�ƻ���������
        $shipPlanDateSql = " select shipPlanDate from oa_stock_outplan where id=" . $id;
        $shipPlanDate = $this->_db->getArray($shipPlanDateSql);

        $isOnTime = 0;
        $isOnTime = 0;
        if ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executedNum'] == 0) {
            $docStatus = 'WFH';
            $shipPlanDateTemp = strtotime($shipPlanDate[0]['shipPlanDate']) * 1;
            $shipDateTemp = strtotime("now") * 1;
            if ($shipPlanDateTemp - $shipDateTemp >= 0) {
                $isShipped = 1;
                $isOnTime = 1;
            } else {
                $isShipped = 0;
                $isOnTime = 0;
            }
        } elseif ($remainNum[0]['countNum'] <= 0) {
            $docStatus = 'YWC';
            $shipPlanDateTemp = strtotime($shipPlanDate[0]['shipPlanDate']) * 1;
            $shipDateTemp = strtotime("now") * 1;
            if ($shipPlanDateTemp - $shipDateTemp >= 0) {
                $isShipped = 1;
                $isOnTime = 1;
            } else {
                $isShipped = 0;
                $isOnTime = 0;
            }
        } else {
            $docStatus = 'BFFH';
        }
        $statusInfo = array(
            'id' => $id,
            'docStatus' => $docStatus,
            'isShipped' => $isShipped,
            'isOnTime' => $isOnTime
        );
        $this->updateById($statusInfo);
    }


    /**
     * ���ݳ��ⵥ�޸ķ����ƻ�����ͬ�Ĳ�Ʒ����
     * $rows Ŀǰ�� 'relDocId':�����ƻ�Id. 'productId':��ƷId�� 'outNum':��������
     * ���ݷ����ƻ�Id�Ͳ�ƷId���ҵ��÷����ƻ��ж�Ӧ�Ĳ�Ʒ�����޸���������
     */
    function updateAsOut($rows)
    {
        //���·����ƻ�����������״̬
        $planSql = " update oa_stock_outplan_product set executedNum = executedNum + " . $rows['outNum'] . " where mainId= " . $rows['relDocId'] . " and id=" . $rows['relDocItemId'];
        $this->_db->query($planSql);
        $this->updatePlanOutStatus($rows['relDocId']);

        //���º�ͬ����������״̬
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $outplaninfo = $this->findBy('id', $rows['relDocId']);
        $docType = $outplaninfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOut($outplaninfo, $rows);

        if($rows['contractType'] == 'oa_contract_contract'){
            //���º�ͬ��Ŀִ�н���
            $conProDao = new model_contract_conproject_conproject();
            $conProDao->updateConProScheduleByCid($rows['relDocId']);

            // ��ͬ�ڵ�ʵ����
            $conNodeDao = new model_contract_connode_connode();
            $conNodeDao->autoNode_d($rows['contractId'], 'pro', $conProDao->getContractProjectProcess_d($rows['contractId']));
        }
    }


    /**
     * ������ⵥʱ�޸ķ����ƻ�����ͬ�Ĳ�Ʒ����
     * $rows Ŀǰ�� 'relDocId':�����ƻ�Id. 'productId':��ƷId�� 'outNum':��������
     * ���ݷ����ƻ�Id�Ͳ�ƷId���ҵ��÷����ƻ��ж�Ӧ�Ĳ�Ʒ�����޸���������
     */
    function updateAsAutiAudit($rows)
    {
        $rows['outNum'] = $rows['outNum'] * (-1);

        //���·����ƻ�����������״̬
        $planSql = " update oa_stock_outplan_product set executedNum = executedNum + " . $rows['outNum'] . " where mainId= " . $rows['relDocId'] . " and id=" . $rows['relDocItemId'];
        $this->_db->query($planSql);
        $this->updatePlanOutStatus($rows['relDocId']);

        //���º�ͬ����������״̬
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $outplaninfo = $this->findBy('id', $rows['relDocId']);
        $docType = $outplaninfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOut($outplaninfo, $rows);

        if($rows['contractType'] == 'oa_contract_contract'){
            //���º�ͬ��Ŀִ�н���
            $conProDao = new model_contract_conproject_conproject();
            $conProDao->updateConProScheduleByCid($rows['relDocId']);

            // ��ͬ�ڵ�ʵ����
            $conNodeDao = new model_contract_connode_connode();
            $conNodeDao->autoNode_d($rows['contractId'], 'pro', $conProDao->getContractProjectProcess_d($rows['contractId']));
        }
    }

    /**
     * ��ɫ���ⵥ���
     */
    function updateAsRedOut($rows)
    {
        $rows['outNum'] = $rows['outNum'] * (-1);
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $docType = $planProInfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOutRed($planProInfo, $rows);
    }

    /**
     * ��ɫ���ⵥ���
     */
    function updateAsRedAutiAudit($rows)
    {
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $docType = $planProInfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOutRed($planProInfo, $rows);
    }


    /**
     * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
     */
    function updatePlanStatus_d($id, $paramArr)
    {
        $docType = $paramArr['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $contDao = new $contStr();
        $planRemainSql = " select (sum(number)-sum(executedNum)) as remainNum,sum(number) as allNum from oa_stock_outplan_product where mainId=" . $id;
        $shipPlanDateSql = " select shipPlanDate from oa_stock_outplan where id=" . $id;
        $remainNum = $this->_db->getArray($planRemainSql);
        $shipPlanDate = $this->_db->getArray($shipPlanDateSql);
        if ($remainNum[0]['remainNum'] == $remainNum[0]['allNum']) {
            $docStatus = 'WFH';
        } elseif ($remainNum[0]['remainNum'] <= 0) {
            $docStatus = 'YWC';
            $shipDateSql = " select shipDate from oa_stock_ship where id=" . $paramArr['id'];
            $completeDate = $this->_db->getArray($shipDateSql);
            $shipPlanDateTemp = strtotime($shipPlanDate[0]['shipPlanDate']) * 1;
            $shipDateTemp = strtotime($completeDate[0]['shipDate']) * 1;
            if ($shipPlanDateTemp - $shipDateTemp >= 0) {
                $isShipped = 1;
                $isOnTime = 1;
            } else {
                $isShipped = 0;
                $isOnTime = 0;
            }
        } else
            $docStatus = 'BFFH';
        $statusInfo = array(
            'id' => $id,
            'docStatus' => $docStatus,
            'isShipped' => $isShipped,
            'isOnTime' => $isOnTime
        );
        $this->updateById($statusInfo);
    }

    /**
     * ���ݷ����ƻ�id����ϸid��ȡ��ͬΪ��������
     */
    function getDocNotExeNum($docId, $docItemId)
    {
        $planEquDao = new model_stock_outplan_outplanProduct();
        $equObj = $planEquDao->get_d($docItemId);
        $contEquInfo = array(
            'id' => $equObj['contEquId'],
            'docType' => $equObj['docType'],
        );
        $docType = $contEquInfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        return $planDao->getNotExeNum($contEquInfo);
    }


    /**
     * ��д���ⵥʱ�����ݷ����ƻ���ȡ������ʾģ��
     *
     */
    function getEquList_d($outplanId)
    {
        $planInfo = $this->get_d($outplanId);
        $outplanEquDao = new model_stock_outplan_outplanProduct ();
        $rows = $outplanEquDao->getItemByshipId_d($outplanId);
        if ($planInfo['docType'] == 'oa_borrow_borrow') {
            $list = $outplanEquDao->showAddList_borrow($rows);
        } else {
            $list = $outplanEquDao->showAddList($rows);
        }
        return $list;
    }


    /**
     * ���ݷ����ƻ�id������ֿ�id����Ʒid��ȡ������¼
     */
    function findLockNum($planId, $stockId, $productId)
    {
        $lockDao = new model_stock_lock_lock();
        $planObj = $this->get_d($planId);
        $lockDao->searchArr = array(
            "productId" => $productId,
            "objId" => $planObj['docId'],
            "objType" => $planObj['docType'],
            "stockId" => $stockId
        );
        $sql = " select sum(c.lockNum) as lockNum from oa_stock_lock c where 1=1 ";
        $lockArr = $lockDao->listBySql($sql);
        return $lockArr[0]['lockNum'];
    }

    /**
     * ���ݺ�ͬid�����ͻ�ȡ�����ƻ�id����
     */
    function getPlanByOrderId($orderId, $docRelType)
    {
        $searchArr = array(
            'docType' => $docRelType,
            'docId' => $orderId
        );
        $rows = $this->findAll($searchArr, null, 'id', null);
        $resultArr = array();
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                array_push($resultArr, $val['id']);
            }
        } else {
            return null;
        }
        return $resultArr;
    }

    /*****************************************������� end****************************************************/

    /**************************************************��ͬ�豸ͳ�Ʋ��� start****************************************************/
    /**
     * �ɹ��豸-�ƻ�����
     */
    function pageEqu_d()
    {
        $searchArr = $this->__GET("searchArr");
        $this->__SET('searchArr', $searchArr);
        $this->groupBy = 'productId';
        $rows = $this->getPagePlan($sql = "select_equ");
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $searchArr = $this->__GET("searchArr");
                $searchArr['productId'] = $val['productId'];
                $this->__SET('groupBy', "id");
                $this->__SET('sort', "id");
                $this->__SET('searchArr', $searchArr);
                $chiRows = $this->listBySqlId("select_cont");
                $rows[$i]['childArr'] = $chiRows;
                ++$i;
            }
            return $rows;
        } else {
            return false;
        }
    }


    /**�ɹ�����-�ɹ��ƻ�-�豸�嵥��ʾģ��
     *author can
     *2011-3-23
     */
    function showEqulist_s($rows)
    {
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $addAllAmount = 0;
                $strTab = "";
                foreach ($val['childArr'] as $chdKey => $chdVal) {
                    switch ($chdVal['tablename']) {
                        case 'oa_sale_order':
                            $chdVal['tablename'] = '���۷���';
                            break;
                        case 'oa_sale_lease':
                            $chdVal['tablename'] = '���޷���';
                            break;
                        case 'oa_sale_rdproject':
                            $chdVal['tablename'] = '�з�����';
                            break;
                        case 'oa_sale_service':
                            $chdVal['tablename'] = '���񷢻�';
                            break;
                    }
//					$i++;
                    $iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
//					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
//						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
//					}else{
//						$amountOk = $chdVal['amountAll'];
//					}
                    $addAllAmount += $chdVal['number'];
                    $inventoryNum = $rows[$key]['inventoryNum'];

//					if( $amountOk==0 || $amountOk=="" ){
//						$checkBoxStr =<<<EOT
//				        	$chdVal[basicNumb]
//EOT;
//					}else{
//						<input type="checkbox" class="checkChild" >
//						$checkBoxStr =<<<EOT
//				    	$chdVal[orderTempCode]<input type="hidden" class="hidden" value="$chdVal[orgid]"/>
//EOT;
//					}

                    $strTab .= <<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td width="20%">
						    	$chdVal[orderCode]
					        </td>
			        		<td width="20%">
						    	$chdVal[orderTempCode]
					        </td>
					        <td  width="10%">
					             $chdVal[tablename]
					        </td>
					        <td  width="8%">
					            $chdVal[number]
					        </td>
					        <td  width="8%">
					            $chdVal[onWayNum]
					        </td>
					        <td  width="8%">
					            $chdVal[executedNum]
					        </td>
		            	</tr>
EOT;
                }

                $str .= <<<EOT
					<tr class="$iClass">
				        <td    height="30" width="4%">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td >
				            $val[productNo]<br>$val[productName]
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllAmount</p>
				            </p>
				        </td>
				        <td width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$inventoryNum</p>
				            </p>
				        </td>
				        <td width="65%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;
            }
        } else {
            $str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
        }
        return $str;
    }

    /**************************************************��ͬ�豸ͳ�Ʋ��� end****************************************************/
    /******************************************************************************************/
    /**
     * @exclude ��������ӷ���
     * @author zengzx
     * @version 2011��7��26�� 15:27:29
     */
    function change_d($obj)
    {
        try {
            $this->start_d();
            $outplanequDao = new model_stock_outplan_outplanProduct();
            $changeLogDao = new model_common_changeLog ('outplan', false);
            $bItemArr = $obj['bItem'];
            unset($obj['bItem']);
            if (is_array($bItemArr)) {
                if (!is_array($obj['details'])) {
                    $obj['details'] = array();
                }
                foreach ($bItemArr as $key => $val) {
                    array_push($obj['details'], $val);
                }
            }
            //�����¼,�õ��������ʱ������id
            $tempObjId = $changeLogDao->addLog($obj);
            $docType = $obj['docType'];
            if ($docType) {
                $flag = 1;
                $outStrategy = $this->relatedStrategyArr[$docType];
                $obj['id'] = $obj['oldId'];
                $obj['isTemp'] = 1;

                $emailArr = $obj['email'];
                unset($obj['email']);
                $detail = $obj['details'];
                $obj['dongleRate'] = 0;
                $flag = parent::edit_d($obj);
                foreach ($obj['details'] as $key => $val) {
                    if ($val['productId'] == '') {
                        unset($obj['details'][$key]);
                        continue;
                    }
                    //������������0��ɾ���ƻ��豸�嵥
                    if ($val ['isDel'] == 1) {
                        $val['id'] = $val ['oldId'];
                        $val['isDelete'] = 1;
                        $val['changeTips'] = 3;
                        $outplanequDao->edit_d($val);
                        //					$outplanequDao->deletes_d ( $val ['oldId'] );
                    } elseif ($val ['oldId']) {
                        $val['id'] = $val ['oldId'];
                        $outplanequDao->edit_d($val);
                    } else {
                        $val['mainId'] = $obj['oldId'];
                        $val['docType'] = $obj['docType'];
                        $val['docId'] = $obj['docId'];
                        $val['changeTips'] = 2;
                        $outplanequDao->add_d($val);
                    }
                }
                $paramArr = array( //�����������
                    'mainId' => $obj['id'],
                    'docId' => $obj['docId'],
                    'docType' => $obj['docType'],
                );
                $relItemArr = $obj['details']; //�����嵥��Ϣ
                //ͳһѡ����ԣ�������Ե�ҵ����
                $storageproId = $this->ctDealRelInfoAtChange(new $outStrategy (), $paramArr, $relItemArr);
                $this->setStatus($obj['id']);
                $this->setHasBorrowStatus($obj['id']);
                //�����ʼ� ,������Ϊ�ύʱ�ŷ���
                if ($obj['ismail'] == 1 && !empty($emailArr['TO_ID'])) {
                    $emailDao = new model_common_mail();
                    $addmsg = "���ԭ�����£�<br/>" . $obj['changeReason'];
                    $emailInfo = $emailDao->batchEmail($obj['ismail'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '���', '���Ϊ:' . $obj['planCode'], $emailArr['TO_ID'], $addmsg, 1);
                }

                //�����ʼ�����ȷ�Ϸ����ƻ�
                if ($obj['isNeedConfirm'] == 1) {
                    $contractDao = new model_contract_contract_contract();
                    $contractArr = $contractDao->find(array('id' => $obj['docId']));
                    $this->mailDeal_d('confirmOutPlan', $contractArr['prinvipalId'], Array('name' => $_SESSION['USERNAME'],
                        'planCode' => $obj['planCode'], 'overTimeReason' => $obj['overTimeReason']));
                }

            } else {
                throw new Exception("������Ϣ����������ȷ��!");
            }
            $this->commit_d();
            return $obj['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }
    }


    /**
     * �����ƻ��´���ʼ�
     * TODO:@param mailman string �����ʼ��ˣ�����չ��
     */
    function mailTo($object, $mailman = false)
    {
        if (is_array($this->mailArr)) {
            $mailArr = $this->mailArr;
        } else {
            $mailArr = array();
        }
        $docType = isset ($object['docType']) ? $object['docType'] : null;
        if ($docType) { //���ڷ����ƻ�����
            $outStrategy = $this->relatedStrategyArr[$docType];
//			$salemanArr = $this->getSaleman($object['docId'],new $outStrategy());
//			array_push($mailArr,$salemanArr);
            $createmanArr = $this->getCreateman($object['docId'], new $outStrategy());
            array_push($mailArr, $createmanArr);
            foreach ($mailArr as $key => $val) {
                $nameArr[$key] = $val['responsible'];
                $idArr[$key] = $val['responsibleId'];
            }
            $objCode = $object['docCode'];// Դ�����
            $nameArr = array_flip($nameArr);
            $nameArr = array_flip($nameArr);
            $idArr = array_flip($idArr);
            $idArr = array_flip($idArr);
            $nameStr = implode(',', $nameArr);
            $idStr = implode(',', $idArr);
            foreach ($mailArr as $key => $val) {
                $nameArr[$key] = $val['responsible'];
                $idArr[$key] = $val['responsibleId'];
            }

            // �����еĲ�������
            $otherDatasDao = new model_common_otherdatas();
            $extSendUIds = $otherDatasDao->getConfig('extSendUIds_for_outplan');// �����ռ��� PMS 254
            $extSendUIds = ($extSendUIds == "")? $extSendUIds : ",".$extSendUIds;

            $outmailStr = implode(',', $idArr);
            $outmailStr .= $extSendUIds;
            $addMsg = $this->getAddMes($object);
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '�´�', $object['planCode'], $outmailStr, $addMsg, '1',null,$objCode.'-');
        }
    }

    /**
     * ��д���������Ӧ��ҵ�����
     * 1.�޸ķ����ƻ�����״̬
     */
    function updateBusinessByShip($planId)
    {
        $proNumSql = "SELECT
					count(0) AS countNum,
						(
							SELECT
								ifnull(sum(s1.number),0)
							FROM
								oa_stock_ship_product s1
							WHERE
								s1.planId = sub.planId
						)AS spNum
				FROM
					(
						SELECT
							op.`number` AS opNum,
							ifnull(sum(sp.`number`), 0) AS spNum,
							(
								op.`number` - ifnull(sum(sp.`number`), 0)
							) AS notExeNum,
							op.`mainId` AS planId,
							op.id AS planEqId
						FROM
							oa_stock_outplan_product op
						LEFT JOIN oa_stock_ship_product sp ON (op.id = sp.`planEquId`)
						WHERE
							op.isDelete = 0
						AND op.mainId = " . $planId . "
						GROUP BY
							op.id
					) sub
				WHERE
					notExeNum > 0
				GROUP BY
					planId";
        $proNum = $this->_db->getArray($proNumSql);
        if ($proNum[0]['spNum'] == 0 && $proNum[0]['countNum'] != 0) {
            $status = 'WZX';
        } elseif ($proNum[0]['countNum'] == 0) {
            $status = 'YZX';
        } else {
            $status = 'BFZX';
        }
        $condition = array('id' => $planId);
        $this->updateField($condition, 'status', $status);
    }

    /**
     * ���÷����ƻ�״̬ --- ���
     */
    function setStatus($id)
    {
        try {
            $this->start_d();
            $this->updateBusinessByShip($id);
            $planRemainSql = "select count(0) as countNum,(select sum(e.executedNum) as executedNum from oa_stock_outplan_product e where e.mainId=" . $id . " and e.isDelete=0) as executedNum
							from (select (o.number-o.executedNum) as remainNum,o.executedNum from oa_stock_outplan_product o
							where o.mainId=" . $id . " and o.isDelete=0) c where c.remainNum>0";
            $remainNum = $this->_db->getArray($planRemainSql);
            if ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executedNum'] == 0) {
                $docStatus = 'WFH';
            } elseif ($remainNum[0]['countNum'] <= 0) {
                $docStatus = 'YWC';
            } else {
                $docStatus = 'BFFH';
            }
            $statusInfo = array(
                'id' => $id,
                'docStatus' => $docStatus
            );
            $flag = $this->updateById($statusInfo);
            $this->commit_d();
            return $flag;
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    /**
     * ��ͬ�豸�ܻ� ��ҳ
     * 2011��10��19�� 16:24:57
     */
    function getPagePlan($sql)
    {
        $sql = $this->sql_arr [$sql];
        $countsql = "select count(0) as num " . substr($sql, strrpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //ƴװ��������
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //����������Ϣ
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " group by productId order by " . $this->sort . " " . $asc;
        //������ȡ��¼��
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
        return $this->_db->getArray($sql);
    }

    /**
     * �������ķ����ƻ����ݻ�ȡ
     */
    function getPageBySelectAllocation()
    {
        $sql = $this->sql_arr ['select_allocation'];
        $countsql = "select count(0) as num " . substr($sql, strrpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //ƴװ��������
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //����������Ϣ
        $groupBy = $this->groupBy;
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " $groupBy order by " . $this->sort . " " . $asc;
        //������ȡ��¼��
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
        return $this->_db->getArray($sql);
    }

    function getPageBySelectPlan()
    {
        $groupBy = $this->groupBy;
        $sql = $this->sql_arr ['select_plan'];
        $countsql = "select count(0) as num " . substr($sql, strrpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //ƴװ��������
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //����������Ϣ
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " $groupBy order by " . $this->sort . " " . $asc;
        //������ȡ��¼��
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
        return $this->_db->getArray($sql);
    }


    /**
     * ����Դ���ţ�Դ�����ͣ������ƻ�Id
     * @author zengzx
     * @param bigint $orderId Դ��ID
     * @param string $type Դ������
     * 2011��11��7�� 14:22:18
     */
    function getOutpId_d($orderId, $type)
    {
        $conditions = array(
            'docId' => $orderId,
            'docType' => $type
        );
        $ids = $this->find($conditions, $sort = null, 'id');
        return $ids;
    }

    /**
     * ����ֶθ�����ʾ
     */
    function setChangeKey_d($obj)
    {
        $condition = array(
            'objId' => $obj['id']
        );
        $changeLogObj = new model_common_changeLog('outplan');
        $changeLogObj->tbl_name = $changeLogObj->main_tbl_name;
        $changeRows = $changeLogObj->findAll($condition, 'id desc', null, 1);
        $condition['parentId'] = $changeRows[0]['id'];
        $changeLogObj->tbl_name = $changeLogObj->detail_tbl_name;
        $changeRows = $changeLogObj->findAll($condition);
        foreach ($changeRows as $key => $val) {
            if ($obj['changeTips'] == 0) {
                continue;
            }
            if ($val['detailType'] == 'outplan') {
                $changeField = $val['changeField'];
                $obj[$changeField] = "<font color='red'>" . $obj[$changeField] . "</font>";
            }
            if ($val['detailType'] == 'details' && $val['newValue'] == '��ɾ����') {
                unset($changeRows[$key]);
            }
        }
        foreach ($obj['details'] as $index => $row) {
            if ($obj['changeTips'] == 0) {
                continue;
            }
            foreach ($changeRows as $key => $val) {
                $changeField = $val['changeField'];
                if ($val['detailType'] = 'details' && $val['detailId'] == $row['id']) {
                    $obj['details'][$index][$changeField] = "<font color='red'>" . $obj['details'][$index][$changeField] . "</font>";
                }
            }
            if ($row['changeTips'] == 1 && $row['isDelete'] == 0) {
                $isAddFlag = true;
                foreach ($changeRows as $key => $val) {
                    if ($val['detailId'] == $row['id']) {
                        $isAddFlag = false;
                    }
                }
                if ($isAddFlag) {
                    $obj['details'][$index]['isRed'] = 1;
                }
            }
        }
        return $obj;
    }

    /**
     * ���ݷ����ƻ�Id��ȡ�����ƻ�Դ����Ϣ
     */
    function getPlanRel_d($planId)
    {
        $condition = array('id' => $planId);
        $fields = "docType,docId,docCode";
        $relDocInfo = $this->find($condition, 'id', $fields);
        return $relDocInfo;
    }

    /**
     * δִ�еķ�������
     */
    function getNotRunOutNum($condition = null)
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where docStatus='WFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * ִ���еķ�������
     */
    function getRunningOutNum($condition = null)
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where docStatus='BFFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * �Ǽ��ķ�������
     */
    function getReterStarOutNum()
    {
        $sql = "SELECT COUNT(*) as number,reterStart FROM oa_stock_outplan where isTemp=0 GROUP BY reterStart";
        return $this->listBySql($sql);
    }

    /**
     * ���ڵķ�������
     */
    function getdelayOutNum($condition = "")
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where isOnTime=1 " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * portlet��������-�ƻ�ҳ����Ⱦ
     */
    function showCountShipPage_d()
    {
        $str = ""; //���ص�ģ���ַ���
        $noRunNum = $this->getNotRunOutNum();
        $runningNum = $this->getRunningOutNum();
        $reterStarNumArr = $this->getReterStarOutNum();
        $delayNum = $this->getdelayOutNum();
        $starTypeStr = "<ul>";
        for ($i = 0, $star = 5; $i <= $star; $i++) {
            $starNum = 0;
            $k = $i + 1;
            foreach ($reterStarNumArr as $key => $val) {
                if ($val['reterStart'] == $i) {
                    $starNum = $val['number'];
                }
            }
            $starTypeStr .= "<li>$k.[$i]�ǵķ����ƻ���<a id='starOutNumHref_$i' href='javascript:void(0)' >
				<span id='starOutNum_$i'></span>$starNum</a>����
				<input type='hidden' id='v_starOutNum_$i' value='$i'/></li>";
        }
        $starTypeStr .= "</ul>";
        $str .= <<<EOT
			<ul>
				<li>һ��δִ�еķ����ƻ�<a id="NotRunOutNumHref" href="javascript:void(0)" ><span id="notRunOutNum">$noRunNum</span></a>����
				</li>
				<li>����ִ���еķ����ƻ�<a id="RunningOutNumHref" href="javascript:void(0)" ><span id="runningOutNum">$runningNum</span></a>����
				</li>
				<li>�������Ǽ�ͳ�Ʒ����ƻ�$starTypeStr
				</li>
				<li>�ġ����ڵķ����ƻ�<a id="delayOutNumHref" href="javascript:void(0)" ><span id="delayOutNum">$delayNum</span></a>����
				</li>
			</ul>
EOT;
        return $str;
    }

    /**
     * ���÷����ƻ��Ƿ����������ת��������
     */
    function setHasBorrowStatus($id)
    {
        $sql = "SELECT count(*) as num from oa_stock_outplan_product " .
            "op RIGHT JOIN oa_stock_outplan o on(o.id=op.mainId) WHERE op.BToOTips=1 and op.isDelete=0 and o.id="
            . $id . " GROUP BY mainId;";
        $borrowCount = $this->queryCount($sql);
        $isBorrow = 0;
        if ($borrowCount != 0) {
            $isBorrow = 1;
        }
        $hasBorrow = array(
            'id' => $id,
            'hasBorrow' => $isBorrow
        );
        $this->updateById($hasBorrow);
        return $isBorrow;
    }

    /**
     * �ʼ��и���������Ϣ
     */
    function getAddMes($object)
    {
        $addmsg = "";
        if (is_array($object ['productsdetail'])) {
            $j = 0;
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>���ϱ��</td><td>��������</td><td>����ͺ�</td><td>��λ</td><td>�ƻ�����</td></tr>";
            foreach ($object ['productsdetail'] as $key => $equ) {
                $j++;
                $productCode = $equ['productNo'];
                $productName = $equ['productName'];
                $productModel = $equ ['productModel'];
                $unitName = $equ ['unitName'];
                $number = $equ ['number'];
                if ($equ['isDelTag'] != 1) {
                    if ($equ['BToOTips'] == 1) {
                        $addmsg .= <<<EOT
								<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$unitName</td><td>$number</td></tr>
EOT;
                    } else {
                        $addmsg .= <<<EOT
								<tr align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$unitName</td><td>$number</td></tr>
EOT;
                    }
                }
            }
            $addmsg .= "</table>" .
                "<br><span color='red'>�����б����б���ɫΪ��ɫ�����ϣ�˵���������ǽ�����ת���۵ġ�</span></br>";
        }
        return $addmsg;
    }

    //ȷ�Ϸ����ƻ�
    function confirm_d($object)
    {
        try {
            $this->start_d();
            $this->update(array('id' => $object['id']), array('isNeedConfirm' => $object['isNeedConfirm']));
            if ($object['isNeedConfirm'] == 0) {
                $planArr = $this->get_d($object['id']);
                $this->mailDeal_d('argeeOutPlan', null, Array('name' => $_SESSION['USERNAME'],
                    'planCode' => $planArr['planCode']));
            } else if ($object['isNeedConfirm'] == 2) {
                $planArr = $this->get_d($object['id']);
                $this->mailDeal_d('disagreeOutPlan', null, Array('name' => $_SESSION['USERNAME'],
                    'planCode' => $planArr['planCode']));
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������ϴ�
     */
    function uploadfile_d($row)
    {
        try {
            //���������ƺ�Id
            $this->updateObjWithFile($row['serviceId']);
            return true;
        } catch (exception $e) {
            return false;
        }
    }
}

?>