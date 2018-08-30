<?php
//����ӿ�
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';

/**
 * �����ù黹�ʼ�����
 * @author kuangzw
 */
class model_produce_quality_strategy_returnqualityapply extends model_base implements iqualityapply
{
    /**
     * ˽�г�Ա - ҵ�������
     */
    private $mainClass = "model_projectmanagent_borrowreturn_borrowreturn";
    private $detailClass = "model_projectmanagent_borrowreturn_borrowreturnequ";

    /**
     * �����ʼ�����ʱ�������ҵ����Ϣ
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false)
    {
        $detailDao = new $this->detailClass();
        if (is_array($relItemArr)) {
            foreach ($relItemArr as $value) {
                $sql = "update $detailDao->tbl_name set  qualityNum=qualityNum+{$value['qualityNum']} where id={$value['relDocItemId']}";
                $detailDao->query($sql);
            }
            //�Ӹ�״̬���µķ��� - �´��ʼ��״̬��Ϊ�ʼ���
            $mainDao = new $this->mainClass();
            $mainDao->updateDisposeState_d($paramArr['relDocId'], 1);
        }
    }

    /**
     * �޸��ʼ�����ʱԴ����ҵ����
     * @param bool $paramArr
     * @param bool $relItemArr
     * @param bool $lastItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false, $lastItemArr = false)
    {

    }

    /**
     * ͨ���ʼ���÷���
     * �黹����id
     * �黹��ϸid
     * ͨ������
     */
    function dealRelItemPass($relDocId, $relDocItemId, $qualityNum)
    {
        // ʵ����������ϸ,������������
        $detailDao = new $this->detailClass();
        $detailDao->editQualityInfo("", $relDocItemId, $qualityNum);

        $mainDao = new $this->mainClass();
        // ����ͨ���������Ƿ�������
        $productId = $mainDao->isMainItem_d($relDocItemId);
        // �������ϵĲ��ı�黹��״̬
        if ($productId != '-1') {
            $mainDao->updateDisposeState_d($relDocId, 3); //�ʼ����
        }
    }

    /**
     * �𻵷��ε��÷���
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum) {
        // ʵ����������ϸ,������������
        $detailDao = new $this->detailClass();
        $detailDao->editQualityBackInfo("", $relDocItemId, 0, $qualityNum);

        $mainDao = new $this->mainClass();
        // ����ͨ���������Ƿ�������
        $productId = $mainDao->isMainItem_d($relDocItemId);
        // �������ϵĲ��ı�黹��״̬
        if ($productId != '-1') {
            $mainDao->updateDisposeState_d($relDocId, 3); //�ʼ����
        }
        // �����⳥״̬
        $mainDao->updateState_d($relDocId, 1);
    }

    /**
     * ����ʼ�����ʱԴ����ҵ����
     * @param bool $paramArr
     */
    function dealRelInfoAtConfirm($paramArr = false)
    {
        $detailDao = new $this->detailClass();
        $detailDao->editQualityInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);

        //�ʼ����
        $mainDao = new $this->mainClass();
        // ����ͨ���������Ƿ�������
        $productId = $mainDao->isMainItem_d($paramArr['relDocItemId']);
        // �������ϵĲ��ı�黹��״̬
        if ($productId != '-1') {
            $mainDao->updateDisposeState_d($paramArr['relDocId'], 3);
        }
    }

    /**
     * ���֮���˻�ʱ�������ϵ�
     * @param bool $paramArr
     */
    function dealRelInfoAtBack($paramArr = false)
    {
        $detailDao = new $this->detailClass();
        $detailDao->editQualityBackInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['passNum'], $paramArr['receiveNum']);

        //�����⳥״̬
        $mainDao = new $this->mainClass();
        $mainDao->updateState_d($paramArr['relDocId'], 1);
        // ����ͨ���������Ƿ�������
        $productId = $mainDao->isMainItem_d($paramArr['relDocItemId']);
        // �������ϵĲ��ı�黹��״̬
        if ($productId != '-1') {
            $mainDao->updateDisposeState_d($paramArr['relDocId'], 3);
        }
    }

    /**
     * ���֮���ò�����ʱ�������ϵ�
     * @param bool $paramArr
     */
    function dealRelInfoAtReceive($paramArr = false)
    {
        $detailDao = new $this->detailClass();
        $detailDao->editQualityReceiceInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['passNum'], $paramArr['receiveNum']);

        //�����⳥״̬
        $mainDao = new $this->mainClass();
        $mainDao->updateState_d($paramArr['relDocId'], 1);
        // ����ͨ���������Ƿ�������
        $productId = $mainDao->isMainItem_d($paramArr['relDocItemId']);
        // �������ϵĲ��ı�黹��״̬
        if ($productId != '-1') {
            $mainDao->updateDisposeState_d($paramArr['relDocId'], 3);
        }
    }

    /**
     * �����ʼ챨��
     */
    function dealRelInfoAtUnconfirm($paramArr = false)
    {
        $detailDao = new $this->detailClass();
        $detailDao->editQualityUnconfirmInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);

        //�����⳥״̬
        $mainDao = new $this->mainClass();
        $mainDao->updateState_d($paramArr['relDocId'], 0);
        $mainDao->updateDisposeState_d($paramArr['relDocId'], 1); //�ʼ����
    }

    /**
     * ��ȡԴ���嵥��Ϣ
     */
    function getRelDocInfo($id)
    {
        $mainDao = new $this->mainClass();
        $mainObj = $mainDao->get_d($id);
        $rtObj = array(
            'supplierName' => $mainObj['customerName'],
            'supplierId' => $mainObj['customerId'],
            'applyUserCode' => $mainObj['salesId'],
            'applyUserName' => $mainObj['salesName']
        );
        return $rtObj;
    }

    /**
     * ��ȡ�ӱ�����
     */
    function getRelDetailInfo($id)
    {
        $detailDao = new $this->detailClass();
        $detailDao->searchArr = array('returnId' => $id);
        $detailArr = $detailDao->list_d();

        //��������
        $rtArr = array();
        if (!empty($detailArr)) {
            //ʵ��������
            $productInfoDao = new model_stock_productinfo_productinfo();
            //�����ֵ�
            $datadictDao = new model_system_datadict_datadict();
            foreach ($detailArr as $val) {
                $canQualityNum = $val['number'] - $val['qualityNum'];
                if ($canQualityNum <= 0) {
                    continue;
                }
                //�ʼ췽ʽ
                $productInfo = $productInfoDao->get_d($val['productId']);
                array_push($rtArr, array(
                    'productId' => $val['productId'],
                    'productCode' => $val['productNo'],
                    'productName' => $val['productName'],
                    'pattern' => $productInfo['pattern'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
                    'serialId' => $val['serialId'],
                    'serialName' => $val['serialName'],
                    'checkType' => $productInfo['checkType'],
                    'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
                ));
            }
        }
        return $rtArr;
    }

    /**
     * �ж��Ƿ�ɳ���
     */
    function checkCanBack_d($itemIdArr)
    {
        $itemIds = implode(',', $itemIdArr);
        $detailDao = new $this->detailClass();
        $sql = "select sum(disposeNumber) as disposeNumber from $detailDao->tbl_name where id in ($itemIds) ";
        $rs = $detailDao->_db->getArray($sql);
        //����Ѿ����������򷵻�false
        if ($rs[0]['disposeNumber']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * ������ҵ��״̬���·��� -- ���ʱֱ�Ӵ������,���������ݸ��´���
     */
    function dealRelInfoCompleted($relDocId)
    {
        return $relDocId;
    }

    /**
     * �ʼ������ص��÷���
     * Դ��id
     * Դ����ϸid
     * �������
     */
    function dealRelItemBack($relDocId, $relDocItemId, $qualityNum)
    {
        $detailDao = new $this->detailClass();
        return $detailDao->editQualityInfoAtBack("", $relDocItemId, $qualityNum);
    }

    /**
     * ������ҵ��״̬���·��� -�����ʼ�������
     */
    function dealRelInfoBack($relDocId)
    {
        try {
            //���µ���״̬
            $mainDao = new $this->mainClass();
            $mainDao->updateBusinessByBack($relDocId);
        } catch (Exception $e) {
            throw $e;
        }
    }
}