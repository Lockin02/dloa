<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:借试用归还管理 Model层
 */
class model_projectmanagent_borrowreturn_borrowreturnDis extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_borrow_return_dispose";
        $this->sql_map = "projectmanagent/borrowreturn/borrowreturnDisSql.php";
        parent::__construct();
    }

    //公司权限处理
    protected $_isSetCompany = 1;

    /**
     *编号自动生成
     */
    function returnDisCode() {
        $billCode = "GHCL" . date("Ymd");
        $sql = "select max(RIGHT(c.Code,4)) as maxCode,left(c.Code,10) as _maxbillCode " .
            "from oa_borrow_return_dispose c group by _maxbillCode having _maxbillCode='" . $billCode . "'";

        $resArr = $this->findSql($sql);
        $res = $resArr[0];
        if (is_array($res)) {
            $maxCode = $res['maxCode'];
            $maxBillCode = $res['maxbillCode'];
            $newNum = $maxCode + 1;
            switch (strlen($newNum)) {
                case 1:
                    $codeNum = "000" . $newNum;
                    break;
                case 2:
                    $codeNum = "00" . $newNum;
                    break;
                case 3:
                    $codeNum = "0" . $newNum;
                    break;
                case 4:
                    $codeNum = $newNum;
                    break;
            }
            $billCode .= $codeNum;
        } else {
            $billCode .= "0001";
        }

        return $billCode;
    }

    /**
     * 重写add_d方法

     */
    function add_d($object) {
//		var_dump($object);
//		die();
        try {
            $this->start_d();

            //插入主表信息
            $object['Code'] = $this->returnDisCode();
            $newId = parent::add_d($object, true);

            //插入从表信息
            if (!empty ($object['product'])) {
                //实例化借用归还物料信息
                $borrowReturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
                $isOut = false;
                foreach ($object['product'] as &$v) {
                    $equId = $v['id'];
                    unset($v['id']);
                    $v['returnequId'] = $equId;
                    //反写处理数量
                    $borrowReturnEquDao->editDisposeNumber($equId, $v['disposeNum']);
                    if ($v['outNum']) {
                        //反写出库数量
                        $borrowReturnEquDao->editOutNumber($equId, $v['outNum']);
                        $isOut = true;
                    }
                }

                //如果存在出库数量,则更新出库状态
                if ($isOut == true) {
                    $this->update(array('id' => $newId), array('disposeState' => 1));
                }

                //归还确认单物料插入
                $equDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
                $equDao->createBatch($object['product'], array(
                    'returnId' => $object['borrowreturnId'], 'disposeId' => $newId, 'borrowId' => $object['borrowId']
                ), 'productName');
            }

            //调用归还单状态更新方法
            $borrowReturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
            $borrowReturnDao->updateReturnState_d($object['borrowreturnId']);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写编辑方法
     */
    function edit_d($object) {
        try {
            $this->start_d();
            //修改主表信息
            parent::edit_d($object, true);

            $cId = $object['id'];
            //插入从表信息
            //产品
            $productDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
            $productDao->delete(array('returnId' => $cId));
            foreach ($object['product'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['product'][$k]);
                }
            }
            $productDao->createBatch($object['product'], array(
                'returnId' => $cId
            ), 'productName');

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 列表页提交
     */
    function ajaxSub_d($id) {
        $sql = "update oa_borrow_return set state = '1' where id = '" . $id . "'";
        $this->query($sql);
    }

    /**
     * 确认方法
     */
    function comSub_d($id) {

        try {
            $this->start_d();
            $sql = "update oa_borrow_return_dispose set state = '3',ExaStatus = '未审批' where id = '" . $id . "'";
            $this->query($sql);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 提交赔偿单
     */
    function comfirmMoney_d($rows) {
        //剔除主表无关信息
        $items = $rows['product'];
        unset($rows['product']);
        try {
            $this->start_d();
            $sql = "update oa_borrow_return_dispose set money = '" . $rows['money'] . "' where id = '" . $rows['id'] . "'";
            $this->query($sql);
            foreach ($items as $k => $v) {
                $sql = "update oa_borrow_return_dispose_equ set money = '" . $v['money'] . "' where id = '" . $v['id'] . "'";
                $this->query($sql);
            }
            $this->commit_d();
            return $rows['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据审批人处理单ID 查找 借用人部门
     */
    function findUserDept($id) {
        $sql = "select u.DEPT_ID as deptId from oa_borrow_return_dispose d
			left join oa_borrow_borrow b on b.id = d.borrowId
			left join user u on u.USER_ID = if(b.limits='客户',b.salesNameId,b.createId) where d.id = '" . $id . "'";
        $deptArr = $this->_db->getArray($sql);
        return $deptArr[0]['deptId'];
    }

    function findUserInfo($id) {
        $sql = "select u.DEPT_ID,if(b.limits='客户',b.salesName,b.createName) as userName from oa_borrow_return_dispose d
			left join oa_borrow_borrow b on b.id = d.borrowId
			left join user u on u.USER_ID = if(b.limits='客户',b.salesNameId,b.createId) where d.id = '" . $id . "'";
        $deptArr = $this->_db->getArray($sql);
        return $deptArr[0];
    }

    /**
     * 财务处理单据
     */
    function ajaxDisposeCom_d($id) {
        try {
            $sql = "update oa_borrow_return_dispose set compensateState = '1' where id = $id ";
            $this->_db->query($sql);
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//获取默认发送人
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "通过", $mailUser['subProBorrow']['subNameId']);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    ///////////////////////////////////////////////////////////////////

    /**
     * 归还审核时处理方法
     */
    function updateAsOut($rows) {
        $dao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
        $Info = $dao->get_d($rows['relDocItemId']);

        //反写 处理单归还个数
        $sqlA = "update oa_borrow_return_dispose_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $rows['relDocItemId'] . " ";
        $this->query($sqlA);
        //反写 归还单归还个数
        $sqlB = "update oa_borrow_return_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['returnequId'] . " ";
        $this->query($sqlB);
        //反写 借用单归还个数
        $sqlC = "update oa_borrow_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['equId'] . " ";
        $this->query($sqlC);
        //处理单 归还状态
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * 归还反审核时处理方法
     */
    function updateAsAutiAudit($rows) {
        $rows['outNum'] = $rows['outNum'] * (-1);
        $dao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
        $Info = $dao->get_d($rows['relDocItemId']);

        //反写 处理单归还个数
        $sqlA = "update oa_borrow_return_dispose_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $rows['relDocItemId'] . " ";
        $this->query($sqlA);
        //反写 归还单归还个数
        $sqlB = "update oa_borrow_return_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['returnequId'] . " ";
        $this->query($sqlB);
        //反写 借用单归还个数
        $sqlC = "update oa_borrow_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['equId'] . " ";
        $this->query($sqlC);
        //处理单 归还状态
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * 根据源单、从表ID 获取未执行数量
     */
    function getDocNotExeNum($docId, $docItemId) {
        $sql = "select (number - backNum) as nonexecutionNum from oa_borrow_return_dispose_equ where id=" . $docItemId . "";
        $numarr = $this->_db->getArray();
        return $numarr[0]['nonexecutionNum'];
    }

    /**
     * 根据处理单ID判断处理单归还状态
     */
    function updateState_d($id) {
        $sql = "select sum(disposeNum) - sum(backNum) as num,sum(number) as allNum,
				sum(outNum) as allOut,sum(outNum) - sum(executedNum) as notOut
			from oa_borrow_return_dispose_equ where disposeId = '" . $id . "'";
        $numArr = $this->_db->getArray($sql);
        if ($numArr[0]['num'] == '0') {
            $state = '2';
        } else {
            if ($numArr[0]['num'] == $numArr[0]['allNum']) {
                $state = '0';
            } else {
                $state = '1';
            }
        }

        $disposeState = 0;
        if ($numArr[0]['allOut']) {
            if ($numArr[0]['notOut'] != 0) {
                $disposeState = '1';
            } else {
                $disposeState = '2';
            }
        }

        $updateSql = "update oa_borrow_return_dispose set state = '" . $state . "',disposeState = '$disposeState' where id = '" . $id . "'";
        $this->query($updateSql);
    }

    /*************************************************************************/
    /**
     * 蓝字出库审核
     */
    function updateAsOutStock($rows) {
        $backNum = $rows['outNum'];
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        //处理单 归还状态
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * 蓝字出库反审处理方法
     */
    function updateAsAutiAuditStock($rows) {
        $backNum = $rows['outNum'] * (-1);
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        //处理单 归还状态
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * 红字出库审核处理方法
     */
    function updateAsRedOutStock($rows) {
        $backNum = $rows['outNum'];
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
    }

    /**
     * 红字出库反审处理方法
     */
    function updateAsRedAutiAuditStock($rows) {
        $backNum = $rows['outNum'] * (-1);
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
    }
}