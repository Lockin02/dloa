<?php

/**
 * @author Show
 * @Date 2010��12��29�� ������ 19:31:43
 * @version 1.0
 * @description:������ϵ���� Model�� ֻ�й����ͷ���,���޸Ĳ���
 */
class model_finance_related_baseinfo extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_related";
        $this->sql_map = "finance/related/baseinfoSql.php";
        parent::__construct();
    }

    /**
     * ��������
     */
    function hookAdd_d($object)
    {
        $relateDetailDao = new model_finance_related_detail();
        try {
            $this->start_d();
            //��ӹ�������
            $newId = $this->add_d(array(
                'years' => date('Y'),
                'status' => 'CGFPZT-YGJ',
                'supplierId' => $object['supplierId'],
                'supplierName' => $object['supplierName'],
                'shareType' => $object['shareType']), true);

            //��ӹ����ӱ�,�޸ı�������Ŀ���ݣ��޸Ĳɹ���ⵥ��Ŀ����
            if (isset($object['purchType']) && $object['purchType'] == 'assets' && isset($object['checkCards'])) {
                $relateDetailDao->addInvpurDetail_d($object['invpurdetail'], $newId, $object['storage'], 0, $object['shareType'], $object['checkCards']);
            } else {
                $relateDetailDao->addInvpurDetail_d($object['invpurdetail'], $newId, $object['storage'], 0, $object['shareType']);
            }

            $this->commit_d();
//			$this->rollBack();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���ݲɹ���Ʊ��ȡ��������id��
     */
    function getIds_d($invPurId)
    {
        $relateDetailDao = new model_finance_related_detail();
        $rows = $relateDetailDao->getRelatedIds_d($invPurId);

        $issetArr = array();
        foreach ($rows as $key => $val) {
            if (!in_array($val['relatedId'], $issetArr)) {
                array_push($issetArr, $val['relatedId']);
            }
        }
        $ids = implode($issetArr, ',');
        return $ids;
    }


    /********************����������***************************/

    /**
     * ������
     */
    function unhook_d($id)
    {
        $relateDetailDao = new model_finance_related_detail();
        try {
            $this->start_d();
            //�������ɹ���Ʊ
            $relateDetailDao->unhookInvPur_d($id);

            //�������ɹ���ⵥ
            $relateDetailDao->unhookStorage_d($id);

            //ɾ��������¼����
            $this->deletes_d($id);

            $this->commit_d();
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
        return true;
    }

    /**
     * ���ݲɹ���Ʊid���з�����
     */
    function unhookByInv_d($invPurId)
    {
        //��ȡ�������
        $id = $this->getIds_d($invPurId);
        $rs = $this->unhook_d($id);
        if (!$rs) {
            return false;
        }
        return true;
    }

    /********************��������**********************************/
    /**
     * ��дget_d
     */
    function get_d($id)
    {
        $relateDetailDao = new model_finance_related_detail();
        $relateRows = $relateDetailDao->findAll(array('relatedId' => $id, 'hookObj' => 'storage'), null, 'productId,productNo,productName,amount,number,firstAmount,firstPrice,price,hookMainId,hookObjCode');
        $relateRows = $relateDetailDao->showDetailInInit($relateRows);
        $rows = $this->find(array("id" => $id));
        $rows['detailRows'] = $relateRows;
        return $rows;
    }

    /**********************�ݹ����********************************/
    function releaseAdd_d($object)
    {
        // ����
    }
}