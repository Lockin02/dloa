<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include(WEB_TOR . 'model/finance/compensate/icompensate.php');

/**
 * ������Ŀ����
 */
class model_finance_compensate_strategy_sborrowreturn implements icompensate
{
    //��Ӧҵ����
    private $thisClass = 'model_projectmanagent_borrowreturn_borrowreturn';
    //��ϸ
    private $detailClass = 'model_projectmanagent_borrowreturn_borrowreturnequ';

    //���ϸ����ݻ�ȡ��
    private $failureitemClass = 'model_produce_quality_failureitem';

    /**
     * ҵ�����ݻ�ȡ
     */
    function businessGet_i($relId, $mainDao)
    {
        //��Ӧҵ��
        $thisDao = new $this->thisClass();
        $thisObj = $thisDao->get_d($relId);

        $thisObj['dutyType'] = $thisObj['borrowLimit'] == 'Ա��' ? 'PCZTLX-01' : 'PCZTLX-02';
        $thisObj['objCode'] = $thisObj['borrowCode'];
        $thisObj['objId'] = $thisObj['borrowId'];
        $thisObj['objType'] = 'PCYWLX-01';
        $thisObj['chargerName'] = $thisObj['salesName'];
        $thisObj['chargerId'] = $thisObj['salesId'];
        $thisObj['relDocCode'] = $thisObj['Code'];

        //�ӽ������ǻ�ȡһЩ��Ϣ
        $borrowDao = new model_projectmanagent_borrow_borrow();
        $borrowObj = $borrowDao->find(array('id' => $thisObj['borrowId']), null, 'chanceId,chanceCode,chanceName,salesNameId,salesName');
        //�̻���Ϣ
        $thisObj['chanceId'] = $borrowObj['chanceId'];
        $thisObj['chanceCode'] = $borrowObj['chanceCode'];
        $thisObj['chanceName'] = $borrowObj['chanceName'];
        $thisObj['salemanId'] = $borrowObj['salesNameId'];
        $thisObj['salemanName'] = $borrowObj['salesName'];
        $thisObj['province'] = '';
        $thisObj['qualityObjType'] = "ZJSQYDGH";

        //���̻���ȡ
        if ($borrowObj['chanceId']) {
            $chanceDao = new model_projectmanagent_chance_chance();
            $chanceObj = $chanceDao->find(array('id' => $borrowObj['chanceId']), null, 'Province');
            $thisObj['province'] = $chanceObj['Province'];
        }
        return $thisObj;
    }

    /**
     * ��ȡ�⳥����ϸ
     */
    function businessGetDetail_i($condition, $mainDao)
    {
        $borrowreturnDao = new $this->detailClass();

        //���������黹
        if ($condition['applyType'] == 'JYGHSQLX-01') {
            $failureitemDao = new $this->failureitemClass();
            $rows = $failureitemDao->getUnCompensateDetail_d($condition['relDocId'], 'ZJSQYDGH');

            // ��ѯ�𻵷��еĲ���
            $qualityApplyDao = new model_produce_quality_qualityapply();
            $damageItem = $qualityApplyDao->getDamagePassItem($condition['relDocId']);

            // ѭ��
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
        } else { //������ʧ
            $detailRows = $borrowreturnDao->getDetail_d($condition['relDocId']);
            $rows = array(); // ��������
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
     * ��ȡ�⳥����ϸ
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
     * ����ҵ����
     */
    function businessAdd_i($obj, $detail, $mainDao)
    {
        //����Դ��״̬Ϊ ִ����
        $detailDao = new $this->detailClass();
        //��Ӧҵ��
        $thisDao = new $this->thisClass();
        //�ʼ첻�ϸ���ϸ
        $failureitemDao = new $this->failureitemClass();

        try {
            $detailDao->start_d();

            //����Դ���������
            foreach ($detail as $val) {
                //�����Ѿ��´��⳥������
                $detailDao->editCompensateNumber($val['returnequId'], $val['number']);
                //�����Ƿ����⳥
                if ($val['qualityequId']) {
                    $failureitemDao->setCompensated_d($val['qualityequId']);
                }
            }

            //��������ҵ��
            $thisDao->updateStateAuto_d($obj['relDocId']);

            $detailDao->commit_d();
            return true;
        } catch (Exception $e) {
            $detailDao->rollBack();
            throw $e;
        }
    }
}