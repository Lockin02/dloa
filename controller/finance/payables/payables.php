<?php

/**
 * @author Show
 * @Date 2011��5��6�� ������ 16:17:38
 * @version 1.0
 * @description:Ӧ�����/Ӧ��Ԥ����/Ӧ���˿���Ʋ�
 */
class controller_finance_payables_payables extends controller_base_action
{
    private $unDeptExtFilter = "";// PMS377 ��ģ����Ҫ�������صĲ���ѡ��
    function __construct() {
        $this->objName = "payables";
        $this->objPath = "finance_payables";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = rtrim($unDeptExtFilterArr,",");
    }

    /**
     * ��ת��Ӧ�����/Ӧ��Ԥ����/Ӧ���˿
     */
    function c_page() {
        //���Ե�������ҳ��
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->assign('formType', $_GET['formType']);

        $this->display($thisObjCode . '-list');
    }

    /**
     * ����ҳ��
     */
    function c_toAdd() {
        //���Ե�������ҳ��//���Ե�������ҳ��
        $this->assign('formType', $_GET['formType']);
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->assign('formDate', day_date);

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('unDeptFilter', $this->unDeptExtFilter);

        $this->display($thisObjCode . '-add');
    }

    /**
     * ����ҳ�� - �Ӹ���������д
     */
    function c_toAddForApply() {
        //���Ե�������ҳ��
        $this->assign('formType', $_GET['formType']);
        $thisObjCode = $this->service->getBusinessCode($_GET['formType']);

        $newClass = $this->service->getClass($_GET['formType']);
        $obj = $this->service->getObjInfo_d($_GET['objId'], new $newClass());
        $this->assignFunc($obj);

        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->assign('formDate', day_date);

        //������չ������
        if (empty($obj['exaCode'])) {
            $this->assign('exaId', $obj['id']);
            $this->assign('exaCode', 'oa_finance_payablesapply');
        }

        $this->display($thisObjCode . '-addforapply');
    }

    /**
     * ����¼�븶��
     */
    function c_toAddInGroup() {
        $rows = $this->service->getPayapply_d($_GET['ids']);
        $this->assignFunc($rows);

        $this->display('addingroup');
    }

    /**
     * ����¼�븶��
     */
    function c_addInGroup() {
        if ($this->service->addInGroup_d($_POST[$this->objName])) {
            msgRf('��ӳɹ���');
        } else {
            msgRf('���ʧ�ܣ�');
        }
    }

    /**
     * ����¼�룬��ȷ��ҳ��
     */
    function c_addInGroupOneKey() {
        $object = $this->service->getPayapplyOneKey_d($_POST['ids']);
        echo $this->service->addInGroup_d($object[$this->objName], $_POST['isEntrust']) ? 1 : 0;
    }

    /**
     * ��дc_add
     */
    function c_add() {
        $object = $_POST[$this->objName];
        //���Ե�������ҳ��
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->add_d($object, new $thisClass())) {
            msg('��ӳɹ���');
        } else {
            msg('���ʧ�ܣ�');
        }
    }

    /**
     * ��дc_add
     */
    function c_addForApply() {
        $object = $_POST[$this->objName];
        //���Ե�������ҳ��
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->add_d($object, new $thisClass())) {
            msgRF('��ӳɹ���');
        } else {
            msgRF('���ʧ�ܣ�');
        }
    }

    /**
     * ��дc_init
     */
    function c_init() {
        //URLȨ�޿���
        $this->permCheck();

        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        $thisObjCode = $this->service->getBusinessCode($obj['formType']);

        if ($perm == 'view') {
            //�鿴ҳ���ȡ���������������Ϣ
            $payableapplyObj = $this->service->getPayapplyExaInfo_d($obj['payApplyId']);
            $this->assignFunc($payableapplyObj);

            $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
            $this->assign('detail', $detailObj);
            $this->assign('isEntrust', $this->service->rtYesOrNo_d($obj['isEntrust']));
            $this->display($thisObjCode . '-view');
        } else {
            $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->display($thisObjCode . '-edit');
        }
    }

    /**
     * ������ҳ��
     */
    function c_initWin() {
        //URLȨ�޿���
        $this->permCheck();

        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'view';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        //�鿴ҳ���ȡ���������������Ϣ
        $payableapplyObj = $this->service->getPayapplyExaInfo_d($obj['payApplyId']);
        $this->assignFunc($payableapplyObj);

        $thisObjCode = $this->service->getBusinessCode($obj['formType']);
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('detail', $detailObj);
        $this->display($thisObjCode . '-viewwin');
    }

    /**
     * ��дc_edit
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        //���Ե�������ҳ��
        $thisClass = $this->service->getClass($object['formType']);

        if ($this->service->edit_d($object, new $thisClass())) {
            msg('�༭�ɹ���');
        } else {
            msg('�༭ʧ�ܣ�');
        }
    }

    /**
     * ���
     */
    function c_audit() {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �����
     */
    function c_unaudit() {
        echo $this->service->unaudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �첽ɾ��
     */
    function c_ajaxDelForPayment() {
        try {
            echo $this->service->delForApply_d($_POST['id']);
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ������ʷ
     */
    function c_toHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], 'purchase_contract_purchasecontract');
        $this->assign('skey', $_GET['skey']);
        $this->assignFunc($obj);
        $this->display('history');
    }

    /**
     * ������ʷjson
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);//�����ù�˾Ȩ��
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->groupBy = 'c.id';
        $rows = $service->pageBySqlId('select_historyNew');
        unset($service->groupBy);
        if (!empty($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);
            $rsArr = array('money' => 0, 'amount' => 0);
            $rsArr['formNo'] = 'ѡ��ϼ�';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //�ܼ�������
            $service->groupBy = 'd.objId,d.objType';
            $objArr = $service->listBySqlId('count_money');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '�ϼ�';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ������ʷ
     */
    function c_toHistoryForObj() {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('historyforobj');
    }

    /**
     * ��Ӧ�� - �����¼
     */
    function c_toListBySupplier() {
        $obj = $_GET['obj'];
        $this->assignFunc($obj);
        $this->display('listbysupplier');
    }

    /**
     * ��������˿
     */
    function c_toAddRefund() {
        //URLȨ�޿���
        $this->permCheck();

        $obj = $this->service->getCanRefund_d($_GET['id'], 'detail', 'addRefund');

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //��Ⱦ��������
        $this->assignFunc($obj);

        $this->assign('formType', 'CWYF-03');

        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->assign('detail', $detailObj[0]);
        $this->assign('coutNumb', $detailObj[1]);
        $this->display('refund-payablesadd');
    }

    /**
     * ��������
     */
    function c_checkIsRefundAll() {
        echo $this->service->checkIsRefundAll_d($_POST['id']) ? 1 : 0;
    }
}