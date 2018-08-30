<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:�����ù黹���� Model��
 */
class model_projectmanagent_borrowreturn_borrowreturnDis extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_borrow_return_dispose";
        $this->sql_map = "projectmanagent/borrowreturn/borrowreturnDisSql.php";
        parent::__construct();
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    /**
     *����Զ�����
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
     * ��дadd_d����

     */
    function add_d($object) {
//		var_dump($object);
//		die();
        try {
            $this->start_d();

            //����������Ϣ
            $object['Code'] = $this->returnDisCode();
            $newId = parent::add_d($object, true);

            //����ӱ���Ϣ
            if (!empty ($object['product'])) {
                //ʵ�������ù黹������Ϣ
                $borrowReturnEquDao = new model_projectmanagent_borrowreturn_borrowreturnequ();
                $isOut = false;
                foreach ($object['product'] as &$v) {
                    $equId = $v['id'];
                    unset($v['id']);
                    $v['returnequId'] = $equId;
                    //��д��������
                    $borrowReturnEquDao->editDisposeNumber($equId, $v['disposeNum']);
                    if ($v['outNum']) {
                        //��д��������
                        $borrowReturnEquDao->editOutNumber($equId, $v['outNum']);
                        $isOut = true;
                    }
                }

                //������ڳ�������,����³���״̬
                if ($isOut == true) {
                    $this->update(array('id' => $newId), array('disposeState' => 1));
                }

                //�黹ȷ�ϵ����ϲ���
                $equDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
                $equDao->createBatch($object['product'], array(
                    'returnId' => $object['borrowreturnId'], 'disposeId' => $newId, 'borrowId' => $object['borrowId']
                ), 'productName');
            }

            //���ù黹��״̬���·���
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
     * ��д�༭����
     */
    function edit_d($object) {
        try {
            $this->start_d();
            //�޸�������Ϣ
            parent::edit_d($object, true);

            $cId = $object['id'];
            //����ӱ���Ϣ
            //��Ʒ
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
     * �б�ҳ�ύ
     */
    function ajaxSub_d($id) {
        $sql = "update oa_borrow_return set state = '1' where id = '" . $id . "'";
        $this->query($sql);
    }

    /**
     * ȷ�Ϸ���
     */
    function comSub_d($id) {

        try {
            $this->start_d();
            $sql = "update oa_borrow_return_dispose set state = '3',ExaStatus = 'δ����' where id = '" . $id . "'";
            $this->query($sql);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �ύ�⳥��
     */
    function comfirmMoney_d($rows) {
        //�޳������޹���Ϣ
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
     * ���������˴���ID ���� �����˲���
     */
    function findUserDept($id) {
        $sql = "select u.DEPT_ID as deptId from oa_borrow_return_dispose d
			left join oa_borrow_borrow b on b.id = d.borrowId
			left join user u on u.USER_ID = if(b.limits='�ͻ�',b.salesNameId,b.createId) where d.id = '" . $id . "'";
        $deptArr = $this->_db->getArray($sql);
        return $deptArr[0]['deptId'];
    }

    function findUserInfo($id) {
        $sql = "select u.DEPT_ID,if(b.limits='�ͻ�',b.salesName,b.createName) as userName from oa_borrow_return_dispose d
			left join oa_borrow_borrow b on b.id = d.borrowId
			left join user u on u.USER_ID = if(b.limits='�ͻ�',b.salesNameId,b.createId) where d.id = '" . $id . "'";
        $deptArr = $this->_db->getArray($sql);
        return $deptArr[0];
    }

    /**
     * ��������
     */
    function ajaxDisposeCom_d($id) {
        try {
            $sql = "update oa_borrow_return_dispose set compensateState = '1' where id = $id ";
            $this->_db->query($sql);
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//��ȡĬ�Ϸ�����
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "ͨ��", $mailUser['subProBorrow']['subNameId']);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    ///////////////////////////////////////////////////////////////////

    /**
     * �黹���ʱ������
     */
    function updateAsOut($rows) {
        $dao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
        $Info = $dao->get_d($rows['relDocItemId']);

        //��д �����黹����
        $sqlA = "update oa_borrow_return_dispose_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $rows['relDocItemId'] . " ";
        $this->query($sqlA);
        //��д �黹���黹����
        $sqlB = "update oa_borrow_return_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['returnequId'] . " ";
        $this->query($sqlB);
        //��д ���õ��黹����
        $sqlC = "update oa_borrow_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['equId'] . " ";
        $this->query($sqlC);
        //���� �黹״̬
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * �黹�����ʱ������
     */
    function updateAsAutiAudit($rows) {
        $rows['outNum'] = $rows['outNum'] * (-1);
        $dao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
        $Info = $dao->get_d($rows['relDocItemId']);

        //��д �����黹����
        $sqlA = "update oa_borrow_return_dispose_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $rows['relDocItemId'] . " ";
        $this->query($sqlA);
        //��д �黹���黹����
        $sqlB = "update oa_borrow_return_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['returnequId'] . " ";
        $this->query($sqlB);
        //��д ���õ��黹����
        $sqlC = "update oa_borrow_equ set backNum = backNum + " . $rows['outNum'] . " where id= " . $Info['equId'] . " ";
        $this->query($sqlC);
        //���� �黹״̬
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * ����Դ�����ӱ�ID ��ȡδִ������
     */
    function getDocNotExeNum($docId, $docItemId) {
        $sql = "select (number - backNum) as nonexecutionNum from oa_borrow_return_dispose_equ where id=" . $docItemId . "";
        $numarr = $this->_db->getArray();
        return $numarr[0]['nonexecutionNum'];
    }

    /**
     * ���ݴ���ID�жϴ����黹״̬
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
     * ���ֳ������
     */
    function updateAsOutStock($rows) {
        $backNum = $rows['outNum'];
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        //���� �黹״̬
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * ���ֳ��ⷴ������
     */
    function updateAsAutiAuditStock($rows) {
        $backNum = $rows['outNum'] * (-1);
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        //���� �黹״̬
        $this->updateState_d($rows['relDocId']);
    }

    /**
     * ���ֳ�����˴�����
     */
    function updateAsRedOutStock($rows) {
        $backNum = $rows['outNum'];
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
    }

    /**
     * ���ֳ��ⷴ������
     */
    function updateAsRedAutiAuditStock($rows) {
        $backNum = $rows['outNum'] * (-1);
        $sql = "update oa_borrow_return_dispose_equ set executedNum = executedNum + " . $backNum
            . " where disposeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
    }
}