<?php

/**
 * @author Administrator
 * @Date 2011��5��30�� 19:42:14
 * @version 1.0
 * @description:�����˻� Model��
 */
class model_projectmanagent_return_return extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_return";
        $this->sql_map = "projectmanagent/return/returnSql.php";
        parent::__construct();
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    /**
     * ���ɳ��ⵥʱ���������ϵ�ID��ȡ������ʾģ��
     *
     */
    function getEquList_d($orderId)
    {
        $orderEquDao = new model_projectmanagent_order_orderequ();
        $rows = $orderEquDao->getItemByBasicIdId_d($orderId);
        $list = $orderEquDao->showAddList($rows);
        return $list;
    }

    /**
     * ��дadd_d
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            //����������Ϣ
            $newId = parent::add_d($object, true);
            //����ӱ���Ϣ
            if (!empty($object['equ'])) {
                $equDao = new model_projectmanagent_return_returnequ();
                $equDao->createBatch($object['equ'], array('returnId' => $newId), 'productName');

            }

            $this->commit_d();
//        $this->rollBack();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * ��дget_d
     */
    function get_d($id, $selection = null)
    {
        //��ȡ������Ϣ
        $rows = parent::get_d($id);
        if (empty($selection)) {
            $equDao = new model_projectmanagent_return_returnequ();
            $rows['equipment'] = $equDao->getDetail_d($id);
        } else if (is_array($selection)) {
            if (in_array('equipment', $selection)) {
                $equDao = new model_projectmanagent_return_returnequ();
                $rows['equipment'] = $equDao->getDetail_d($id);
            }
        }

        return $rows;
    }


    /**
     * ����ID ��ȡȫ����Ϣ  ��ϸ
     * $returnId : �˻�ID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('linkman','product') Ĭ��Ϊ�� ȡȫ��
     *       returnequ �ӱ�
     */
    function getDetailInfo($returnId, $getInfoArr = null)
    {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'returnequ',
            );
        }
        $daoArr = array(
            "returnequ" => "model_projectmanagent_return_returnequ",
        );
        $contractInfo = parent::get_d($returnId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            $contractInfo[$val] = $dao->getDetail_d($returnId);
        }
        return $contractInfo;
    }

    /**
     * ��Ⱦ���� - �鿴
     */
    function initView($object)
    {

        if (!empty($object['equipment'])) {

            $equDao = new model_projectmanagent_return_returnequ();
            $object['equipment'] = $equDao->initTableView($object['equipment']);
        } else {
            $object['equipment'] = '<tr><td colspan="10">���������Ϣ</td></tr>';
        }
//		echo "<pre>";
//		print_r ($object);
        return $object;
    }


    /**
     * ��Ⱦ���� - �༭
     */
    function initEdit($object)
    {


        //�豸
        $tentalcontractequDao = new model_projectmanagent_return_returnequ();
        $rows = $tentalcontractequDao->initTableEdit($object['equipment']);
        $object['EquNum'] = $rows[0];
        $object['equipment'] = $rows[1];

        return $object;
    }


    /**
     * ��д�༭����
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //�޸�������Ϣ
            parent::edit_d($object, true);

            $returnId = $object['id'];
            //����ӱ���Ϣ

            //�豸
            $equDao = new model_projectmanagent_return_returnequ();
            $equDao->delete(array('returnId' => $returnId));
            foreach ($object['equ'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['equ'][$k]);
                }
            }
            $equDao->createBatch($object['equ'], array('returnId' => $returnId), 'productName');

            $this->commit_d();
//			$this->rollBack();
            return true;
        } catch (exception $e) {

            return false;
        }
    }
    /*************************************************************************/
    /**
     * ���ݺ�ͬID ���� ��ȡ��ͬ��Ϣ
     */
    function getOrderInfo($orderId, $orderType)
    {
        switch ($orderType) {
            case "oa_sale_order" :
                $Dao = new model_projectmanagent_order_order();
                $rows = $Dao->get_d($orderId);
                break;
            case "oa_sale_service" :
                $Dao = new model_engineering_serviceContract_serviceContract();
                $rows = $Dao->get_d($orderId);
                break;
            case "oa_sale_lease" :
                $Dao = new model_contract_rental_rentalcontract();
                $rows = $Dao->get_d($orderId);
                break;
            case "oa_sale_rdproject" :
                $Dao = new model_rdproject_yxrdproject_rdproject();
                $rows = $Dao->get_d($orderId);
                break;
        }
        return $rows;
    }

    /**
     * ��ȡ��ͬ������ϵ���Ϣ
     */
    function getOrderProInfo($rosPro, $orderType)
    {
        $Dao = new model_projectmanagent_return_returnequ();
        switch ($orderType) {
            case "oa_sale_order" :
                $rows = $Dao->orderReturnEqu($rosPro['orderequ']);
                break;
            case "oa_sale_service" :
                $rows = $Dao->orderReturnEqu($rosPro['serviceequ']);
                break;
            case "oa_sale_lease" :
                $rows = $Dao->orderReturnEqu($rosPro['rentalcontractequ']);
                break;
            case "oa_sale_rdproject" :
                $rows = $Dao->orderReturnEqu($rosPro['rdprojectequ']);
                break;
        }
        return $rows;
    }
    /*************************************************************************/

    /**
     * ���ֳ�����˴�����
     */
    function updateAsRedOut($rows)
    {
        $backNum = $rows['outNum'];
        $id = $rows['relDocId'];
        $sql = "update oa_contract_return_equ set executedNum = executedNum + " . $backNum . "," .
            "qPassExeNum = qPassExeNum + " . $backNum . " " .
            "where returnId = " . $id . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        $this->updateContractinfo($rows['relDocItemId'], $rows['outNum'], 0);
        //�����˻����뵥���״̬
        $this->updateInstockStatus($id);

    }

    /**
     * ���ֳ��ⷴ������
     */
    function updateAsRedAutiAudit($rows)
    {
        $backNum = $rows['outNum'] * (-1);
        $id = $rows['relDocId'];
        $sql = "update oa_contract_return_equ set executedNum = executedNum + " . $backNum . "," .
            "qPassExeNum = qPassExeNum + " . $backNum . " " .
            "where returnId = " . $id . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        $this->updateContractinfo($rows['relDocItemId'], $rows['outNum'], 1);
        //�����˻����뵥���״̬
        $this->updateInstockStatus($id);
    }

    /**
     * �����˻����뵥���״̬
     */
    function updateInstockStatus($id)
    {
        $sql = "SELECT SUM(number) AS number,SUM(executedNum) AS executedNum FROM oa_contract_return_equ WHERE returnId = " . $id;
        $equInfo = $this->_db->getArray($sql);
        $number = $equInfo[0]['number'];
        $executedNum = $equInfo[0]['executedNum'];
        $instockStatus = 0; //δ���
        if ($executedNum > 0) {
            if ($executedNum < $number) { //�������
                $instockStatus = 1;
            } elseif ($executedNum == $number) { //�����
                $instockStatus = 2;
            }
        }
        $this->update(array('id' => $id), array('instockStatus' => $instockStatus));
    }
    /*************************************************************************/
    /**
     * �����˻����ӱ�id ���º�ͬ��״̬�� ����
     */
    function updateContractinfo($returnItemId, $num, $as)
    {
        //�����˻��ӱ�id��ȡ��ͬid �ʹӱ�id
        $conSql = "select contractId,contractequId from oa_contract_return_equ where id=$returnItemId";
        $conarr = $this->_db->getArray($conSql);
        if ($as == '0') {
            $backNum = $num;
        } else if ($as == '1') {
            $backNum = $num * (-1);
        }
        $sql = "update oa_contract_equ set backNum = backNum + " . $backNum
            . " where id= " . $conarr[0]['contractequId'] . " ";

        $dao = new model_contract_contract_contract();
        $this->_db->query($sql);
        $dao->updateOutStatus_d($conarr[0]['contractId']);
    }

    /**
     * �������ȷ�ϲ���
     */
    function workflowCallBack($spid)
    {
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $contract = $this->get_d($objId);
                if ($contract['ExaStatus'] == "���") {
                    $this->mailDeal_d('returnMail', $contract['saleUserId'], array('id' => $objId));
                }
            }
            $this->commit_d();
//			$this->rollBack();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * ���� �ʼ�״̬
     * @param $id
     * @param $disposeState
     * @throws Exception
     */
    function updateDisposeState_d($id, $disposeState)
    {
        try {
            return $this->_db->query("update oa_contract_return set qualityState='$disposeState' where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * ������ʱ���и�������֪ͨ����Ϣ
     * @param  $id   ����֪ͨ��ID
     * @param  $equId   �����嵥ID
     * @param  $productId   ����ID
     * @param  $proNum    �������
     */
    function updateInStock($id, $equId, $productId, $proNum)
    {
        try {
//			$this->start_d();
            $noticeequDao = new model_projectmanagent_return_returnequ();
            $noticeequDao->updateNumb_d($id, $equId, $proNum); //�������ϵ��������

            //����״̬
            $this->updateInstockStatus($id);
            $this->updateContractinfo($equId, $proNum, 0);

//			$this->commit_d();
        } catch (Exception $e) {
//			$this->rollBack();
            return null;
        }
    }

    /**
     * ��������ʱ���и�������֪ͨ����Ϣ
     * @param  $id   ����֪ͨ��ID
     * @param  $equId   �����嵥ID
     * @param  $productId   ����ID
     * @param  $proNum    �������
     */
    function updateInStockCancel($id, $equId, $productId, $proNum)
    {
        try {
//			$this->start_d();
            $noticeequDao = new model_projectmanagent_return_returnequ();
            $noticeequDao->updateNumb_d($id, $equId, -$proNum);

            //����״̬
            $this->updateInstockStatus($id);
            $this->updateContractinfo($equId, $proNum, 1);

//			$this->commit_d();
        } catch (Exception $e) {
//			$this->rollBack();
            return null;
        }
    }
}

?>