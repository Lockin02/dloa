<?php

/**
 * @author Administrator
 * @Date 2011年5月30日 19:42:14
 * @version 1.0
 * @description:销售退货 Model层
 */
class model_projectmanagent_return_return extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_return";
        $this->sql_map = "projectmanagent/return/returnSql.php";
        parent::__construct();
    }

    //公司权限处理
    protected $_isSetCompany = 1;

    /**
     * 生成出库单时，根据收料单ID获取物料显示模板
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
     * 重写add_d
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            //插入主表信息
            $newId = parent::add_d($object, true);
            //插入从表信息
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
     * 重写get_d
     */
    function get_d($id, $selection = null)
    {
        //提取主表信息
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
     * 根据ID 获取全部信息  明细
     * $returnId : 退货ID
     * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
     *       returnequ 从表
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
     * 渲染方法 - 查看
     */
    function initView($object)
    {

        if (!empty($object['equipment'])) {

            $equDao = new model_projectmanagent_return_returnequ();
            $object['equipment'] = $equDao->initTableView($object['equipment']);
        } else {
            $object['equipment'] = '<tr><td colspan="10">暂无相关信息</td></tr>';
        }
//		echo "<pre>";
//		print_r ($object);
        return $object;
    }


    /**
     * 渲染方法 - 编辑
     */
    function initEdit($object)
    {


        //设备
        $tentalcontractequDao = new model_projectmanagent_return_returnequ();
        $rows = $tentalcontractequDao->initTableEdit($object['equipment']);
        $object['EquNum'] = $rows[0];
        $object['equipment'] = $rows[1];

        return $object;
    }


    /**
     * 重写编辑方法
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //修改主表信息
            parent::edit_d($object, true);

            $returnId = $object['id'];
            //插入从表信息

            //设备
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
     * 根据合同ID 类型 获取合同信息
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
     * 获取合同相关物料的信息
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
     * 红字出库审核处理方法
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
        //更新退货申请单入库状态
        $this->updateInstockStatus($id);

    }

    /**
     * 红字出库反审处理方法
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
        //更新退货申请单入库状态
        $this->updateInstockStatus($id);
    }

    /**
     * 更新退货申请单入库状态
     */
    function updateInstockStatus($id)
    {
        $sql = "SELECT SUM(number) AS number,SUM(executedNum) AS executedNum FROM oa_contract_return_equ WHERE returnId = " . $id;
        $equInfo = $this->_db->getArray($sql);
        $number = $equInfo[0]['number'];
        $executedNum = $equInfo[0]['executedNum'];
        $instockStatus = 0; //未入库
        if ($executedNum > 0) {
            if ($executedNum < $number) { //部分入库
                $instockStatus = 1;
            } elseif ($executedNum == $number) { //已入库
                $instockStatus = 2;
            }
        }
        $this->update(array('id' => $id), array('instockStatus' => $instockStatus));
    }
    /*************************************************************************/
    /**
     * 根据退货单从表id 更新合同的状态和 数量
     */
    function updateContractinfo($returnItemId, $num, $as)
    {
        //根据退货从表id获取合同id 和从表id
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
     * 审批后的确认操作
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
                if ($contract['ExaStatus'] == "完成") {
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
     * 更新 质检状态
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
     * 审核入库时进行更新收料通知单信息
     * @param  $id   收料通知单ID
     * @param  $equId   物料清单ID
     * @param  $productId   物料ID
     * @param  $proNum    入库数量
     */
    function updateInStock($id, $equId, $productId, $proNum)
    {
        try {
//			$this->start_d();
            $noticeequDao = new model_projectmanagent_return_returnequ();
            $noticeequDao->updateNumb_d($id, $equId, $proNum); //更新收料的入库数量

            //更新状态
            $this->updateInstockStatus($id);
            $this->updateContractinfo($equId, $proNum, 0);

//			$this->commit_d();
        } catch (Exception $e) {
//			$this->rollBack();
            return null;
        }
    }

    /**
     * 反审核入库时进行更新收料通知单信息
     * @param  $id   收料通知单ID
     * @param  $equId   物料清单ID
     * @param  $productId   物料ID
     * @param  $proNum    入库数量
     */
    function updateInStockCancel($id, $equId, $productId, $proNum)
    {
        try {
//			$this->start_d();
            $noticeequDao = new model_projectmanagent_return_returnequ();
            $noticeequDao->updateNumb_d($id, $equId, -$proNum);

            //更新状态
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