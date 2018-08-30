<?php
//����ӿ�
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';

/**
 *  �ɹ��ʼ�����
 */
class model_produce_quality_strategy_purchqualityapply extends model_base implements iqualityapply
{
    /**
     * ˽�г�Ա - ҵ�������
     */
    private $mainClass = "model_purchase_arrival_arrival";
    private $detailClass = "model_purchase_arrival_equipment";

    /**
     * �����ʼ�����ʱ�������ҵ����Ϣ
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
     * �޸��ʼ�����ʱԴ����ҵ����
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false, $lastItemArr = FALSE)
    {

    }

    /**
     * ����ʼ�����ʱԴ����ҵ����
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtConfirm($paramArr = false)
    {
        //�����ʼ��������
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);
        //���Ǹ����ʼ����ʱ��
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * ���֮���˻�ʱ�������ϵ�
     * �ʼ������ϸ�ʱ,�ʼ�����ȫ���˻�
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtBack($paramArr = false)
    {
        //�����ʼ��������
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityBackInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['backNum']);
        //���Ǹ����ʼ����ʱ��
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * ���֮���ò�����ʱ�������ϵ�
     * �ò�����ʱ,�ʼ����ϼ������Ϊ��ʵ�ʺϸ��� = �ϸ��� + �ò�������,ʵ�ʲ��ϸ��� = ���ϸ���
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtReceive($paramArr = false)
    {
        //ʵ�ʺϸ���
        $canUse = bcadd($paramArr['passNum'], $paramArr['receiveNum']);
        //�����ʼ��������
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityReceiceInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $canUse, $paramArr['backNum']);
        //���Ǹ����ʼ����ʱ��
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalDao->updateCompletionTime($paramArr['relDocId']);
    }

    /**
     * �����ʼ챨��
     */
    function dealRelInfoAtUnconfirm($paramArr = false)
    {
        //�����ʼ��������
        $arrivalItemDao = new model_purchase_arrival_equipment();
        $arrivalItemDao->editQualityUnconfirmInfo($paramArr['relDocId'], $paramArr['relDocItemId'], $paramArr['thisCheckNum']);
    }

    /**
     * ��ȡԴ���嵥��Ϣ
     */
    function getRelDocInfo($id)
    {
        $arrivalEqDao = new model_purchase_arrival_equipment ();
        return $arrivalEqDao->get_d($id);
    }

    /**
     * ��ȡԴ���嵥��Ϣ
     */
    function getRelDetailInfo($id)
    {
        $equipmentDao = new model_purchase_arrival_equipment ();
        $objs = $equipmentDao->findAll(array('arrivalId' => $id, 'isQualityBack' => 1)); // ��ȡ�ʼ������ص�����
        $rtArr = array();
        if (!empty($objs)) {
            //ʵ��������
            $productInfoDao = new model_stock_productinfo_productinfo();
            //�����ֵ�
            $datadictDao = new model_system_datadict_datadict();
            foreach ($objs as $key => $val) {
                $canQualityNum = $val['qualityNum'] - $val['qualityPassNum'];
                if ($canQualityNum <= 0) {
                    continue;
                }
                //�ʼ췽ʽ
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
     * �ж��Ƿ�ɳ���
     */
    function checkCanBack_d($itemIdArr)
    {
        $itemIds = implode(',', $itemIdArr);
        $arrivalEqDao = new model_purchase_arrival_equipment ();
        $sql = "select sum(storageNum) as storageNum from oa_purchase_arrival_equ where id in ($itemIds) ";
        $rs = $arrivalEqDao->_db->getArray($sql);
        //����Ѿ����������򷵻�false
        if ($rs[0]['storageNum']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * ͨ���ʼ���÷���
     * �黹����id
     * �黹��ϸid
     * ͨ������
     */
    function dealRelItemPass($relDocId, $relDocItemId, $qualityNum)
    {
//		ʵ����������ϸ
        $detailDao = new $this->detailClass();
//		������������
        return $detailDao->editQualityInfo("", $relDocItemId, $qualityNum);
    }

    /**
     * ͨ���ʼ���÷���
     * �黹����id
     * �黹��ϸid
     * ͨ������
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum)
    {

    }

    /**
     * ������ҵ��״̬���·��� -- �ɹ����ϲ�����������Ҫ����
     */
    function dealRelInfoCompleted($relDocId)
    {

    }
}