<?php
/**
 * @author zengzx
 * @Date 2011年5月5日 14:36:49
 * @version 1.0
 * @description:发货通知单 Model层
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
        $this->relatedStrategyArr = array( //不同类型出库申请策略类,根据需要在这里进行追加
            "oa_contract_contract" => "model_stock_outplan_strategy_contractplan", //销售发货
            "oa_borrow_borrow" => "model_stock_outplan_strategy_borrowplan", //借用发货
            "oa_present_present" => "model_stock_outplan_strategy_presentplan", //赠送发货
            "oa_contract_exchangeapply" => "model_stock_outplan_strategy_exchangeplan", //退货发货
        );
    }

    //公司权限处理
    protected $_isSetCompany = 1;

    /*===================================页面模板======================================*/
    /**
     * @description 发货计划列表显示模板
     * @param $rows
     */
    function showList($rows, planStrategy $istrategy)
    {
        $istrategy->showList($rows);
    }

    /**
     * @description 新增发货计划时，清单显示模板
     * @param $rows
     */
    function showItemAdd($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemAdd($rows);
    }

    /**
     * @description 修改发货计划时，清单显示模板
     * @param $rows
     */
    function showItemEdit($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemEdit($rows);
    }

    /**
     * @description 变更发货计划时，清单显示模板
     * @param $rows
     */
    function showItemChange($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemChange($rows);
    }

    /**
     * @description 查看发货计划时，清单显示模板
     * @param $rows
     */
    function showItemView($rows, planStrategy $istrategy)
    {
        return $istrategy->showItemView($rows);
    }

    /**
     * @description 发货计划查看，带出转销售物料
     * @param $rows
     */
    function shwoBToOEquView($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEquView($rows, $rowNum);
    }

    /**
     * @description 发货计划变更，带出转销售物料
     * @param $rows
     */
    function shwoBToOEquChange($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEquChange($rows, $rowNum);
    }

    /**
     * @description 发货计划编辑，带出转销售物料
     * @param $rows
     */
    function shwoBToOEqu($rows, $rowNum, planStrategy $istrategy)
    {
        return $istrategy->shwoBToOEqu($rows, $rowNum);
    }

    /**
     * 查看相关业务信息
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false, $skey = false, planStrategy $istrategy)
    {
        return $istrategy->viewRelInfo($paramArr, $skey);
    }

    /**
     * 下推获取源单数据方法
     */
    function getDocInfo($id, planStrategy $strategy)
    {
        $rows = $strategy->getDocInfo($id);
        return $rows;
    }

    /**
     * 获取合同负责人方法
     */
    function getSaleman($id, planStrategy $strategy)
    {
        $salemanArr = $strategy->getSaleman($id);
        return $salemanArr;
    }


    /**
     * 获取合同负责人方法
     */
    function getCreateman($id, planStrategy $strategy)
    {
        $createmanArr = $strategy->getCreateman($id);
        return $createmanArr;
    }

    /**
     * 新增发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtAdd(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
    }

    /**
     * 新增发货计划时邮件处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealMailAtAdd(planStrategy $istrategy, $object = false)
    {
        return $istrategy->dealMailAtAdd($object);
    }


    /**
     * 变更发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtChange(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtChange($paramArr, $relItemArr);
    }

    /**
     * 修改发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtEdit(planStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
    }

    /**
     * 删除发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtDel(planStrategy $istrategy, $id)
    {
        return $istrategy->dealRelInfoAtDel($id);
    }

    /**
     * 添加对象
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $codeRuleDao = new model_common_codeRule();
            $object['planCode'] = $codeRuleDao->sendPlanCode($this->tbl_name);
            //处理借用转销售物料
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
            if ($docType) { //存在发货计划类型
                $outStrategy = $this->relatedStrategyArr[$docType];
                if ($outStrategy) {
                    $paramArr = array( //单据主表参数
                        'mainId' => $id,
                        'docId' => $object['docId'],
                        'docCode' => $object['docCode'],
                        'docType' => $object['docType'],
                    ); //...可以继续追加
                    if (is_array($object['productsdetail'])) {
                        $relItemArr = $object['productsdetail']; //单据清单信息
                        foreach ($relItemArr as $k => $v) {
                            if ($v['isDelTag'] == '1') {
                                unset ($relItemArr[$k]);
                            }
                        }
                        $prod = new model_stock_outplan_outplanProduct();
                        $mainIdArr = array('mainId' => $paramArr['docId']);
                        $prod->createBatch($relItemArr, $paramArr);
                    } else {
                        throw new Exception("单据信息不完整，请确认!");
                    }
                    //统一选择策略，进入各自的业务处理
                    $storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $paramArr, $relItemArr);
                } else {
                    throw new Exception("该类型发货申请暂未开放，请联系开发人员!");
                }
//				$this->setHasBorrowStatus($id);
//				$addMsg = $this->getAddMes($object);
                $this->mailTo($object);
                $this->updateBusinessByShip($id);

                //如果计划发货日期大于希望发货日期则发送邮件通知合同负责人去进行发货计划的确认
//				if($object['isNeedConfirm'] == 1){
//					$contractDao = new model_contract_contract_contract();
//					$contractArr = $contractDao->find(array('id'=>$object['docId']));
//					$this->mailDeal_d('confirmOutPlan',$contractArr['prinvipalId'],Array('name' => $_SESSION['USERNAME'],
//							'planCode' =>$object['planCode'],'overTimeReason' => $object['overTimeReason']));
//				}
            } else {
                throw new Exception("单据信息不完整，请确认!");
            }
            /*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 根据id获出库申请单所有信息  -- 过滤借试用转销售物料
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
     * 根据id获出库申请单所有信息
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
     * 添加等级信息
     */
    function edit_star($object, $isEditInfo = false)
    {
        return parent::edit_d($object, $isEditInfo);
    }

    /**
     * 根据id获出库申请单所有信息
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
     * 编辑对象
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $id = parent::edit_d($object, true);
            $docType = isset ($object['docType']) ? $object['docType'] : null;
            if ($docType) { //存在发货计划类型
                $outStrategy = $this->relatedStrategyArr[$docType];
                if ($outStrategy) {
                    $paramArr = array( //单据主表参数
                        'mainId' => $object['id'],
                        'docId' => $object['docId'],
                        'docCode' => $object['docCode'],
                        'docType' => $object['docType'],
                    ); //...可以继续追加
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
                        $relItemArr = $object['productsdetail']; //单据清单信息
                        foreach ($relItemArr as $key => $val) {
                            unset($relItemArr[$key]['issuedShipNum']);
                        }
                        $prod = new model_stock_outplan_outplanProduct();
                        $mainIdArr = array('mainId' => $object['id']);
                        $storageproId = $this->ctDealRelInfoAtDel(new $outStrategy (), $object['id']);
                        $prod->delete($mainIdArr);
                        $prod->createBatch($relItemArr, $paramArr);
                    } else {
                        throw new Exception("单据信息不完整，请确认!");
                    }
                    //统一选择策略，进入各自的业务处理
                    $relItemArr = $object['productsdetail']; //单据清单信息
                    $storageproId = $this->ctDealRelInfoAtChange(new $outStrategy (), $paramArr, $relItemArr);
                    $this->setHasBorrowStatus($id);
                } else {
                    throw new Exception("该类型出库申请暂未开放，请联系开发人员!");
                }
            } else {
                throw new Exception("单据信息不完整，请确认!");
            }
            /*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 反馈
     */
    function feedback_d($object)
    {
        $id = parent::edit_d($object, true);
        $docType = isset ($object['docType']) ? $object['docType'] : null;
        return $id;
    }


    /*****************************************出库相关 start****************************************************/
    /**
     * 更新发货计划发货状态
     */
    function updatePlanOutStatus($id)
    {

        //查看发货计划未发货数量
        $planRemainSql = "select count(*) as countNum,(select  sum(o.executedNum) from oa_stock_outplan_product o
		where o.mainId=" . $id . " and o.isDelete=0) as executedNum from (select  (o.number-o.executedNum)
		 as remainNum,o.executedNum from oa_stock_outplan_product o  where o.mainId=" . $id . " and o.isDelete=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($planRemainSql);
        //查看计划发货日期
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
     * 根据出库单修改发货计划及合同的产品数量
     * $rows 目前含 'relDocId':发货计划Id. 'productId':产品Id。 'outNum':出库数量
     * 根据发货计划Id和产品Id可找到该发货计划中对应的产品，并修改其数量。
     */
    function updateAsOut($rows)
    {
        //更新发货计划出库数量及状态
        $planSql = " update oa_stock_outplan_product set executedNum = executedNum + " . $rows['outNum'] . " where mainId= " . $rows['relDocId'] . " and id=" . $rows['relDocItemId'];
        $this->_db->query($planSql);
        $this->updatePlanOutStatus($rows['relDocId']);

        //更新合同出库数量及状态
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $outplaninfo = $this->findBy('id', $rows['relDocId']);
        $docType = $outplaninfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOut($outplaninfo, $rows);

        if($rows['contractType'] == 'oa_contract_contract'){
            //更新合同项目执行进度
            $conProDao = new model_contract_conproject_conproject();
            $conProDao->updateConProScheduleByCid($rows['relDocId']);

            // 合同节点实例化
            $conNodeDao = new model_contract_connode_connode();
            $conNodeDao->autoNode_d($rows['contractId'], 'pro', $conProDao->getContractProjectProcess_d($rows['contractId']));
        }
    }


    /**
     * 反审出库单时修改发货计划及合同的产品数量
     * $rows 目前含 'relDocId':发货计划Id. 'productId':产品Id。 'outNum':出库数量
     * 根据发货计划Id和产品Id可找到该发货计划中对应的产品，并修改其数量。
     */
    function updateAsAutiAudit($rows)
    {
        $rows['outNum'] = $rows['outNum'] * (-1);

        //更新发货计划出库数量及状态
        $planSql = " update oa_stock_outplan_product set executedNum = executedNum + " . $rows['outNum'] . " where mainId= " . $rows['relDocId'] . " and id=" . $rows['relDocItemId'];
        $this->_db->query($planSql);
        $this->updatePlanOutStatus($rows['relDocId']);

        //更新合同出库数量及状态
        $planProDao = new model_stock_outplan_outplanProduct();
        $planProInfo = $planProDao->get_d($rows['relDocItemId']);
        $rows['contEquId'] = $planProInfo['contEquId'];
        $outplaninfo = $this->findBy('id', $rows['relDocId']);
        $docType = $outplaninfo['docType'];
        $contStr = $this->relatedStrategyArr[$docType];
        $planDao = new $contStr();
        $planDao->updateContNumAsOut($outplaninfo, $rows);

        if($rows['contractType'] == 'oa_contract_contract'){
            //更新合同项目执行进度
            $conProDao = new model_contract_conproject_conproject();
            $conProDao->updateConProScheduleByCid($rows['relDocId']);

            // 合同节点实例化
            $conNodeDao = new model_contract_connode_connode();
            $conNodeDao->autoNode_d($rows['contractId'], 'pro', $conProDao->getContractProjectProcess_d($rows['contractId']));
        }
    }

    /**
     * 红色出库单审核
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
     * 红色出库单审核
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
     * 根据发货情况修改合同及发货计划状态
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
     * 根据发货计划id及明细id获取合同为出库数量
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
     * 填写出库单时，根据发货计划获取物料显示模板
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
     * 根据发货计划id、出库仓库id、产品id获取锁定记录
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
     * 根据合同id及类型获取发货计划id数组
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

    /*****************************************出库相关 end****************************************************/

    /**************************************************合同设备统计操作 start****************************************************/
    /**
     * 采购设备-计划数组
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


    /**采购管理-采购计划-设备清单显示模板
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
                            $chdVal['tablename'] = '销售发货';
                            break;
                        case 'oa_sale_lease':
                            $chdVal['tablename'] = '租赁发货';
                            break;
                        case 'oa_sale_rdproject':
                            $chdVal['tablename'] = '研发发货';
                            break;
                        case 'oa_sale_service':
                            $chdVal['tablename'] = '服务发货';
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
				        	<div class="readThisTable"><单击展开物料具体信息></div>
				        </td>
				    </tr>
EOT;
            }
        } else {
            $str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /**************************************************合同设备统计操作 end****************************************************/
    /******************************************************************************************/
    /**
     * @exclude 申请变更添加方法
     * @author zengzx
     * @version 2011年7月26日 15:27:29
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
            //变更记录,拿到变更的临时主对象id
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
                    //如果变更数量是0，删除计划设备清单
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
                $paramArr = array( //单据主表参数
                    'mainId' => $obj['id'],
                    'docId' => $obj['docId'],
                    'docType' => $obj['docType'],
                );
                $relItemArr = $obj['details']; //单据清单信息
                //统一选择策略，进入各自的业务处理
                $storageproId = $this->ctDealRelInfoAtChange(new $outStrategy (), $paramArr, $relItemArr);
                $this->setStatus($obj['id']);
                $this->setHasBorrowStatus($obj['id']);
                //发送邮件 ,当操作为提交时才发送
                if ($obj['ismail'] == 1 && !empty($emailArr['TO_ID'])) {
                    $emailDao = new model_common_mail();
                    $addmsg = "变更原因如下：<br/>" . $obj['changeReason'];
                    $emailInfo = $emailDao->batchEmail($obj['ismail'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '变更', '编号为:' . $obj['planCode'], $emailArr['TO_ID'], $addmsg, 1);
                }

                //发送邮件提醒确认发货计划
                if ($obj['isNeedConfirm'] == 1) {
                    $contractDao = new model_contract_contract_contract();
                    $contractArr = $contractDao->find(array('id' => $obj['docId']));
                    $this->mailDeal_d('confirmOutPlan', $contractArr['prinvipalId'], Array('name' => $_SESSION['USERNAME'],
                        'planCode' => $obj['planCode'], 'overTimeReason' => $obj['overTimeReason']));
                }

            } else {
                throw new Exception("单据信息不完整，请确认!");
            }
            $this->commit_d();
            return $obj['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }
    }


    /**
     * 发货计划下达后邮寄
     * TODO:@param mailman string 额外邮寄人（待拓展）
     */
    function mailTo($object, $mailman = false)
    {
        if (is_array($this->mailArr)) {
            $mailArr = $this->mailArr;
        } else {
            $mailArr = array();
        }
        $docType = isset ($object['docType']) ? $object['docType'] : null;
        if ($docType) { //存在发货计划类型
            $outStrategy = $this->relatedStrategyArr[$docType];
//			$salemanArr = $this->getSaleman($object['docId'],new $outStrategy());
//			array_push($mailArr,$salemanArr);
            $createmanArr = $this->getCreateman($object['docId'], new $outStrategy());
            array_push($mailArr, $createmanArr);
            foreach ($mailArr as $key => $val) {
                $nameArr[$key] = $val['responsible'];
                $idArr[$key] = $val['responsibleId'];
            }
            $objCode = $object['docCode'];// 源单编号
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

            // 配置中的补贴名称
            $otherDatasDao = new model_common_otherdatas();
            $extSendUIds = $otherDatasDao->getConfig('extSendUIds_for_outplan');// 新增收件人 PMS 254
            $extSendUIds = ($extSendUIds == "")? $extSendUIds : ",".$extSendUIds;

            $outmailStr = implode(',', $idArr);
            $outmailStr .= $extSendUIds;
            $addMsg = $this->getAddMes($object);
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '下达', $object['planCode'], $outmailStr, $addMsg, '1',null,$objCode.'-');
        }
    }

    /**
     * 填写发货单后对应的业务操作
     * 1.修改发货计划单据状态
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
     * 设置发货计划状态 --- 变更
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
     * 合同设备总汇 分页
     * 2011年10月19日 16:24:57
     */
    function getPagePlan($sql)
    {
        $sql = $this->sql_arr [$sql];
        $countsql = "select count(0) as num " . substr($sql, strrpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //构建排序信息
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " group by productId order by " . $this->sort . " " . $asc;
        //构建获取记录数
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
        return $this->_db->getArray($sql);
    }

    /**
     * 待调拨的发货计划数据获取
     */
    function getPageBySelectAllocation()
    {
        $sql = $this->sql_arr ['select_allocation'];
        $countsql = "select count(0) as num " . substr($sql, strrpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //构建排序信息
        $groupBy = $this->groupBy;
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " $groupBy order by " . $this->sort . " " . $asc;
        //构建获取记录数
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
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //构建排序信息
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " $groupBy order by " . $this->sort . " " . $asc;
        //构建获取记录数
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
        return $this->_db->getArray($sql);
    }


    /**
     * 根据源单号，源单类型，发货计划Id
     * @author zengzx
     * @param bigint $orderId 源单ID
     * @param string $type 源单类型
     * 2011年11月7日 14:22:18
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
     * 变更字段高亮显示
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
            if ($val['detailType'] == 'details' && $val['newValue'] == '【删除】') {
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
     * 根据发货计划Id获取发货计划源单信息
     */
    function getPlanRel_d($planId)
    {
        $condition = array('id' => $planId);
        $fields = "docType,docId,docCode";
        $relDocInfo = $this->find($condition, 'id', $fields);
        return $relDocInfo;
    }

    /**
     * 未执行的发货需求
     */
    function getNotRunOutNum($condition = null)
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where docStatus='WFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * 执行中的发货需求
     */
    function getRunningOutNum($condition = null)
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where docStatus='BFFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * 星级的发货需求
     */
    function getReterStarOutNum()
    {
        $sql = "SELECT COUNT(*) as number,reterStart FROM oa_stock_outplan where isTemp=0 GROUP BY reterStart";
        return $this->listBySql($sql);
    }

    /**
     * 延期的发货需求
     */
    function getdelayOutNum($condition = "")
    {
        $sql = "select count(id) as num from oa_stock_outplan " .
            "where isOnTime=1 " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * portlet发货需求-计划页面渲染
     */
    function showCountShipPage_d()
    {
        $str = ""; //返回的模板字符串
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
            $starTypeStr .= "<li>$k.[$i]星的发货计划：<a id='starOutNumHref_$i' href='javascript:void(0)' >
				<span id='starOutNum_$i'></span>$starNum</a>个。
				<input type='hidden' id='v_starOutNum_$i' value='$i'/></li>";
        }
        $starTypeStr .= "</ul>";
        $str .= <<<EOT
			<ul>
				<li>一、未执行的发货计划<a id="NotRunOutNumHref" href="javascript:void(0)" ><span id="notRunOutNum">$noRunNum</span></a>个。
				</li>
				<li>二、执行中的发货计划<a id="RunningOutNumHref" href="javascript:void(0)" ><span id="runningOutNum">$runningNum</span></a>个。
				</li>
				<li>三、按星级统计发货计划$starTypeStr
				</li>
				<li>四、延期的发货计划<a id="delayOutNumHref" href="javascript:void(0)" ><span id="delayOutNum">$delayNum</span></a>个。
				</li>
			</ul>
EOT;
        return $str;
    }

    /**
     * 设置发货计划是否包含借试用转销售物料
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
     * 邮件中附加物料信息
     */
    function getAddMes($object)
    {
        $addmsg = "";
        if (is_array($object ['productsdetail'])) {
            $j = 0;
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>物料编号</td><td>物料名称</td><td>规格型号</td><td>单位</td><td>计划数量</td></tr>";
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
                "<br><span color='red'>以上列表若有背景色为绿色的物料，说明该物料是借试用转销售的。</span></br>";
        }
        return $addmsg;
    }

    //确认发货计划
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
     * 单独附件上传
     */
    function uploadfile_d($row)
    {
        try {
            //处理附件名称和Id
            $this->updateObjWithFile($row['serviceId']);
            return true;
        } catch (exception $e) {
            return false;
        }
    }
}

?>