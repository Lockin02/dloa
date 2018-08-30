<?php
/**
 * @author Administrator
 * @Date 2012��11��8�� 11:04:46
 * @version 1.0
 * @description:�ջ�֪ͨ�� Model��
 */
header("Content-type: text/html; charset=gb2312");

class model_stock_withdraw_withdraw extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_stock_withdraw";
        $this->sql_map = "stock/withdraw/withdrawSql.php";
        parent::__construct();
        $this->relatedStrategyArr = array( //��ͬ���ͳ������������,������Ҫ���������׷��
            "oa_contract_exchangeapply" => "model_stock_withdraw_strategy_exchangedraw", //�˻�����
        );
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    /*===================================ҳ��ģ��======================================*/
    /**
     * @description �����ƻ��б���ʾģ��
     * @param $rows
     */
    function showList($rows, drawStrategy $istrategy)
    {
        $istrategy->showList($rows);
    }

    /**
     * @description ���������ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemAdd($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemAdd($rows);
    }

    /**
     * @description �޸ķ����ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemEdit($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemEdit($rows);
    }

    /**
     * @description ��������ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemChange($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemChange($rows);
    }

    /**
     * @description �鿴�����ƻ�ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemView($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemView($rows);
    }

    /**
     * �鿴���ҵ����Ϣ
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false, $skey = false, drawStrategy $istrategy)
    {
        return $istrategy->viewRelInfo($paramArr, $skey);
    }

    /**
     * ���ƻ�ȡԴ�����ݷ���
     */
    function getDocInfo($id, drawStrategy $strategy)
    {
        $rows = $strategy->getDocInfo($id);
        return $rows;
    }

    /**
     * ���������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtAdd(drawStrategy $istrategy, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtAdd($relItemArr);
    }

    /**
     * ��������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtChange(drawStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtChange($paramArr, $relItemArr);
    }

    /**
     * �޸ķ����ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtEdit(drawStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
    }

    /**
     * ɾ�������ƻ�ʱԴ����ҵ����
     * @param $istorageapply ���Խӿ�
     * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
     * @param  $relItemArr �ӱ��嵥��Ϣ
     */
    function ctDealRelInfoAtDel(drawStrategy $istrategy, $id)
    {
        return $istrategy->dealRelInfoAtDel($id);
    }
    /*--------------------------------------------ҵ�����--------------------------------------------*/

    /**
     * ��������
     * @see model_base::add_d()
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                $object['issuedStatus'] = '1';
                $object['planIssuedDate'] = day_date;
                $codeRuleDao = new model_common_codeRule();
                $object['planCode'] = $codeRuleDao->sendDrawCode($this->tbl_name);
                $id = parent::add_d($object, true);
                $equDao = new model_stock_withdraw_equ();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $object ['items']);
                $itemsObj = $equDao->saveDelBatch($itemsArr);
                $object ['items'] = $itemsObj; //ֻ������Ч������
                $outStrategy = $this->relatedStrategyArr[$object['docType']];
                $storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $object);
            } else {
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * �޸ı���
     * @see model_base::edit_d()
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                $editResult = parent::edit_d($object, true);
                $equDao = new model_stock_withdraw_equ();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $object ['items']);
                $itemsObj = $equDao->saveDelBatch($itemsArr);
            } else {
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ͨ��id��ȡ��ϸ��Ϣ
     * @see model_base::get_d()
     */
    function get_d($id)
    {
        $object = parent::get_d($id);
        $equDao = new model_stock_withdraw_equ();
        $equDao->searchArr ['mainId'] = $id;
        $object ['items'] = $equDao->listBySqlId();
        return $object;
    }

    /**
     * ��д���������Ӧ��ҵ�����
     * 1.�޸ķ����ƻ�����״̬
     */
    function updateBusinessByNotice($id)
    {
        $proNumSql = "SELECT
				sum(op.number) AS number,
				sum(op.executedNum) AS executedNum
			FROM
				oa_stock_withdraw_equ op
			WHERE
				op.mainId = $id";
        $proNum = $this->_db->getArray($proNumSql);
        //��ִ���� = ������
        if ($proNum[0]['number'] == $proNum[0]['executedNum']) {
            $status = 'YZX';
        } else {
            $status = 'BFZX';
        }
        if (isset($status)) {
            return $this->update(array('id' => $id), array('status' => $status));
        } else {
            return true;
        }
    }

    /**
     * ����docStatus
     */
    function updateDocStatus_d($id, $state)
    {
        try {
            $sql = "update $this->tbl_name set docStatus='$state' where id = $id ";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    /**
     * ���ݹ黹��id �жϲ����� ����״̬
     */
    function updateStateAuto_d($id)
    {
        $sql = "select
                sum(number) as number,sum(qPassNum + qBackNum) as qNumber,
                sum(qBackNum) as qBackNum,sum(compensateNum) as compensateNum
            from oa_stock_withdraw_equ where mainId = '$id'";
        $numArr = $this->_db->getArray($sql);

        if ($numArr[0]['qBackNum'] == $numArr[0]['compensateNum'] && $numArr[0]['number'] == $numArr[0]['qNumber']) {
            $docStatus = '2';
        }
        //�õ�״̬�Ÿ���
        if (isset($docStatus)) {
            $this->update(array('id' => $id), array('docStatus' => $docStatus));
        }
    }

    /**
     * �ʼ������غ��Ӧ��ҵ�����
     */
    function updateBusinessByBack($id)
    {
        $proNumSql = "SELECT
    	sum(op.qualityNum) AS qualityNum
    	FROM
    	oa_stock_withdraw_equ op
    	WHERE
    	op.mainId = $id";
        $proNum = $this->_db->getArray($proNumSql);
        if ($proNum[0]['qualityNum'] == '0') {
            $status = 'WZX';
        } else {
            $status = 'BFZX';
        }
        if (isset($status)) {
            return $this->update(array('id' => $id), array('status' => $status));
        } else {
            return true;
        }
    }
}