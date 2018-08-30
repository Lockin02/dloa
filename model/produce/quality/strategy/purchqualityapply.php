<?php
//引入接口
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';

/**
 *  采购质检申请
 */
class model_produce_quality_strategy_purchqualityapply extends model_base implements iqualityapply
{
    /**
     * 私有成员 - 业务对象类
     */
    private $mainClass = "model_purchase_arrival_arrival";
    private $detailClass = "model_purchase_arrival_equipment";

    /**
     * 新增质检申请时处理相关业务信息
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false)
    {
        $arrivalEqDao = new model_purchase_arrival_equipment ();
        if (is_array($relItemArr)) {
            foreach ($relItemArr as $v) {
                $sql = "update oa_purchase_arrival_equ set isQualityBack=0 ,qualityNum=qualityNum+" .
                    $v['qualityNum'] . " where id=" . $v['relDocItemId'];
                //echo $sql;
                $arrivalEqDao->_db->query($sql);
            }
        }
    }

    /**
     * 修改质检申请时源单据业务处理
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false, $lastItemArr = FALSE)
    {

    }

    /**
     * 审核质检申请时源单据业务处理
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtConfirm($paramArr = false)
    {
        //更新质检完成数量
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);
        //这是更新质检完成时间
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * 审核之间退回时处理收料单
     * 质检结果不合格时,质检物料全数退回
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtBack($paramArr = false)
    {
        //更新质检完成数量
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityBackInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['backNum']);
        //这是更新质检完成时间
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * 审核之间让步接收时处理收料单
     * 让步接收时,质检物料计算规则为：实际合格数 = 合格数 + 让步接收数,实际不合格数 = 不合格数
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtReceive($paramArr = false)
    {
        //实际合格数
        $canUse = bcadd($paramArr['passNum'], $paramArr['receiveNum']);
        //更新质检完成数量
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityReceiceInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $canUse, $paramArr['backNum']);
        //这是更新质检完成时间
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * 撤销质检报告
     */
    function dealRelInfoAtUnconfirm($paramArr = false)
    {
        //更新质检完成数量
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityUnconfirmInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);
    }

    /**
     * 获取源单清单信息
     */
    function getRelDocInfo($id)
    {
        $arrivalEqDao = new model_purchase_arrival_equipment ();
        return $arrivalEqDao->get_d($id);
    }

    /**
     * 获取源单清单信息
     */
    function getRelDetailInfo($id)
    {
        $equipmentDao = new model_purchase_arrival_equipment ();
        $objs = $equipmentDao->findAll(array('arrivalId' => $id, 'isQualityBack' => 1)); // 获取质检申请打回的物料
        $rtArr = array();
        if (!empty($objs)) {
            //实例化物料
            $productInfoDao = new model_stock_productinfo_productinfo();
            //数据字典
            $datadictDao = new model_system_datadict_datadict();
            foreach ($objs as $key => $val) {
                $canQualityNum = $val['qualityNum'] - $val['qualityPassNum'];
                if ($canQualityNum <= 0) {
                    continue;
                }
                //质检方式
                $productInfo = $productInfoDao->get_d($val['productId']);
                array_push($rtArr, array(
                    'productId' => $val['productId'],
                    'productCode' => $val['sequence'],
                    'productName' => $val['productName'],
                    'pattern' => $val['pattem'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
                    'checkType' => $productInfo['checkType'],
                    'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
                ));
            }
        }
        return $rtArr;
    }

    /**
     * 判断是否可撤销
     */
    function checkCanBack_d($itemIdArr)
    {
        $itemIds = implode(',', $itemIdArr);
        $arrivalEqDao = new model_purchase_arrival_equipment ();
        $sql = "select sum(storageNum) as storageNum from oa_purchase_arrival_equ where id in ($itemIds) ";
        $rs = $arrivalEqDao->_db->getArray($sql);
        //如果已经有数量，则返回false
        if ($rs[0]['storageNum']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 通过质检调用方法
     * 归还申请id
     * 归还明细id
     * 通过数量
     */
    function dealRelItemPass($relDocId, $relDocItemId, $qualityNum)
    {
//		实例化收料明细
        $detailDao = new $this->detailClass();
//		更新收料数量
        return $detailDao->editQualityInfo("", $relDocItemId, $qualityNum);
    }

    /**
     * 通过质检调用方法
     * 归还申请id
     * 归还明细id
     * 通过数量
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum)
    {

    }

    /**
     * 调用主业务状态更新方法 -- 采购收料部分暂无内容要更新
     */
    function dealRelInfoCompleted($relDocId)
    {

    }
}