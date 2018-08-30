<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include(WEB_TOR . 'model/finance/compensate/icompensate.php');

/**
 * 试用项目策略
 */
class model_finance_compensate_strategy_sborrowreturn implements icompensate
{
    //对应业务类
    private $thisClass = 'model_projectmanagent_borrowreturn_borrowreturn';
    //明细
    private $detailClass = 'model_projectmanagent_borrowreturn_borrowreturnequ';

    //不合格内容获取类
    private $failureitemClass = 'model_produce_quality_failureitem';

    /**
     * 业务数据获取
     */
    function businessGet_i($relId, $mainDao)
    {
        //对应业务
        $thisDao = new $this->thisClass();
        $thisObj = $thisDao->get_d($relId);

        $thisObj['dutyType'] = $thisObj['borrowLimit'] == '员工' ? 'PCZTLX-01' : 'PCZTLX-02';
        $thisObj['objCode'] = $thisObj['borrowCode'];
        $thisObj['objId'] = $thisObj['borrowId'];
        $thisObj['objType'] = 'PCYWLX-01';
        $thisObj['chargerName'] = $thisObj['salesName'];
        $thisObj['chargerId'] = $thisObj['salesId'];
        $thisObj['relDocCode'] = $thisObj['Code'];

        //从借试用那获取一些信息
        $borrowDao = new model_projectmanagent_borrow_borrow();
        $borrowObj = $borrowDao->find(array('id' => $thisObj['borrowId']), null, 'chanceId,chanceCode,chanceName,salesNameId,salesName');
        //商机信息
        $thisObj['chanceId'] = $borrowObj['chanceId'];
        $thisObj['chanceCode'] = $borrowObj['chanceCode'];
        $thisObj['chanceName'] = $borrowObj['chanceName'];
        $thisObj['salemanId'] = $borrowObj['salesNameId'];
        $thisObj['salemanName'] = $borrowObj['salesName'];
        $thisObj['province'] = '';
        $thisObj['qualityObjType'] = "ZJSQYDGH";

        //从商机获取
        if ($borrowObj['chanceId']) {
            $chanceDao = new model_projectmanagent_chance_chance();
            $chanceObj = $chanceDao->find(array('id' => $borrowObj['chanceId']), null, 'Province');
            $thisObj['province'] = $chanceObj['Province'];
        }
        return $thisObj;
    }

    /**
     * 获取赔偿单明细
     */
    function businessGetDetail_i($condition, $mainDao)
    {
        $borrowreturnDao = new $this->detailClass();

        //如果是申请归还
        if ($condition['applyType'] == 'JYGHSQLX-01') {
            $failureitemDao = new $this->failureitemClass();
            $rows = $failureitemDao->getUnCompensateDetail_d($condition['relDocId'], 'ZJSQYDGH');

            // 查询损坏放行的部分
            $qualityApplyDao = new model_produce_quality_qualityapply();
            $damageItem = $qualityApplyDao->getDamagePassItem($condition['relDocId']);

            // 循环
            $damageRelDocItemId = array();
            if (!empty($damageItem)) {
                foreach ($damageItem as $v) {
                    $damageRelDocItemId[] = $v['relDocItemId'];
                }
            }
            $detailRows = $borrowreturnDao->getDetail_d($condition['relDocId']);
            foreach ($detailRows as $v) {
                if (in_array($v['id'], $damageRelDocItemId)) {
                    $rows[] = array(
                        'returnequId' => $v['id'],
                        'borrowequId' => $v['equId'],
                        'productId' => $v['productId'],
                        'productNo' => $v['productNo'],
                        'productName' => $v['productName'],
                        'productModel' => $v['productModel'],
                        'unitName' => $v['unitName'],
                        'number' => $v['number'],
                        'serialNos' => $v['serialName']
                    );
                }
            }
        } else { //申请遗失
            $detailRows = $borrowreturnDao->getDetail_d($condition['relDocId']);
            $rows = array(); // 返回数组
            foreach ($detailRows as $v) {
                $rows[] = array(
                    'returnequId' => $v['id'],
                    'borrowequId' => $v['equId'],
                    'productId' => $v['productId'],
                    'productNo' => $v['productNo'],
                    'productName' => $v['productName'],
                    'productModel' => $v['productModel'],
                    'unitName' => $v['unitName'],
                    'number' => $v['number'],
                    'serialNos' => $v['serialName']
                );
            }
        }
        if (is_array($rows)) {
            $productDao = new model_stock_productinfo_productinfo();
            foreach ($rows as $key => $val) {
                if($val['productId'] <= 0 || empty($val['productId'])){
                    $productRow = $productDao->getProByCode($val['productNo']);
                }else{
                    $productRow = $productDao->get_d($val['productId']);
                }

                $rows[$key]['price'] = $productRow['priCost'] * $val['number'];
            }
        }
        return $rows;
    }

    /**
     * 获取赔偿单明细
     */
    function getSerialNos_i($condition, $mainDao)
    {
        $detailDao = new $this->detailClass();
        $detailObj = $detailDao->find(array(
            'id' => $condition['returnequId']
        ), null, 'serialName,serialId');
        return $detailObj;
    }

    /**
     * 新增业务处理
     */
    function businessAdd_i($obj, $detail, $mainDao)
    {
        //更新源单状态为 执行中
        $detailDao = new $this->detailClass();
        //对应业务
        $thisDao = new $this->thisClass();
        //质检不合格明细
        $failureitemDao = new $this->failureitemClass();

        try {
            $detailDao->start_d();

            //更新源单相关数量
            foreach ($detail as $val) {
                //更新已经下达赔偿的数量
                $detailDao->editCompensateNumber($val['returnequId'], $val['number']);
                //更新是否已赔偿
                if ($val['qualityequId']) {
                    $failureitemDao->setCompensated_d($val['qualityequId']);
                }
            }

            //更新主表业务
            $thisDao->updateStateAuto_d($obj['relDocId']);

            $detailDao->commit_d();
            return true;
        } catch (Exception $e) {
            $detailDao->rollBack();
            throw $e;
        }
    }
}