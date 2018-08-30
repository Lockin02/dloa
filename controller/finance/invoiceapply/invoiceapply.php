<?php

/**
 * ��Ʊ������Ʋ���
 */
class controller_finance_invoiceapply_invoiceapply extends controller_base_action
{

    function __construct() {
        $this->objName = "invoiceapply";
        $this->objPath = "finance_invoiceapply";
        parent::__construct();
    }

    /************************************�б���*****************************/

    /**
     * ������ʾ���д�����ķ�Ʊ����
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * �ҵĿ�Ʊ����
     */
    function c_myInvoiceApply() {
        $this->display('my');
    }

    /***************2011-03-07�޸� �������ҵ�� ************************/

    /**
     * ����
     */
    function c_toAdd() {
        $thisObj = $_GET[$this->objName];

        //��ȡ��Ӧҵ���ʣ���������
        $applyedMoney = $this->service->getMaxInvoiceMoney_d($thisObj);
        //���ò���
        $newClass = $this->service->getClass($thisObj['objType']);
        $initObj = new $newClass();
        //��ȡ��Ӧҵ����Ϣ
        $rs = $this->service->getObjInfo_d($thisObj, $initObj);
        if (isset($rs['conCanApply'])) {
            $lastCanInvoice = $rs['conCanApply'];
        } else {
            $lastCanInvoice = $rs['money'];
        }
        if ($lastCanInvoice <= $applyedMoney) {
            msgRf('����ͬ���뿪Ʊ���(' . $applyedMoney . ')�Ѵﵽ����!�����ٽ��п�Ʊ����');
            exit();
        }

        //��ȡ������˾����
        $rs['formBelong'] = !isset($rs['formBelong']) || empty($rs['formBelong']) ? $_SESSION['USER_COM'] : $rs['formBelong'];
        $rs['formBelongName'] = !isset($rs['formBelongName']) || empty($rs['formBelongName']) ? $_SESSION['USER_COM_NAME'] : $rs['formBelongName'];
        $rs['businessBelong'] = !isset($rs['businessBelong']) || empty($rs['businessBelong']) ? $_SESSION['USER_COM'] : $rs['businessBelong'];
        $rs['businessBelongName'] = !isset($rs['businessBelongName']) || empty($rs['businessBelongName']) ? $_SESSION['USER_COM_NAME'] : $rs['businessBelongName'];

        //��ȡ������˾
        $logisticsArr = $this->service->getDefaultExpressCom_d();
        $this->assign('expressCompany', $logisticsArr['companyName']);
        $this->assign('expressCompanyId', $logisticsArr['id']);

        //��ѯͬһԴ�����͡��ͻ��������˵����һ�����뵥����ϵ��-�绰-��Ʊ��λ��ַ-��Ʊ��λ�绰-��˰��ʶ���-��������-�����˺���Ϣ
        $this->assign('getLastInfoUrl', "customerId={$rs['customerId']}&createId={$_SESSION['USER_ID']}&objType={$thisObj['objType']}"); //ID2245 2016-11-30
        $countInfo = $this->service->find(
            array('customerId' => $rs['customerId'], 'createId' => $_SESSION['USER_ID'], 'objType' => $thisObj['objType']),
            'id desc', 'linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
        );
        if (!$countInfo)
            $countInfo = array('linkMan' => '', 'linkPhone' => '', 'unitAddress' => '', 'phoneNo' => '',
                'taxpayerId' => '', 'bank' => '', 'bankCount' => '',
            );

        //��Ⱦ����
        $this->assignFunc($rs);

        $canApply = bcsub($lastCanInvoice, $applyedMoney, 2);
        $this->assign('canApply', $canApply);

        //��Ⱦ������Ϣ
        $this->assignFunc($thisObj);
        $this->assignFunc($countInfo);

        //Ȩ�޿���
        if (empty($this->service->this_limit['��������'])) {
            $this->showDatadicts(array('invoiceTypeSelect' => 'XSFP'), null, false, array('expand3No' => '0', 'expand4No' => 1));
        } else {
            $this->showDatadicts(array('invoiceTypeSelect' => 'XSFP'), null, false, array('expand3No' => '0'));
        }
        $this->showDatadicts(array('customerTypeView' => 'KHLX'), null, true);
        $this->assign('objTypeName', $this->getDataNameByCode($thisObj['objType']));

        $this->assign('skey', $this->md5Row($thisObj['objId'], $this->service->rtTypeClass($thisObj['objType']), null));
        $this->assign('currency', isset($rs['currency']) ? $rs['currency'] : '�����');
        $this->assign('rate', isset($rs['rate']) ? $rs['rate'] : 1);

        //Ĭ���ʼ�����
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);
        $this->display('business-add');
    }

    //��ѯͬһԴ�����͡��ͻ��������˵����һ�����뵥����ϵ��-�绰-��Ʊ��λ��ַ-��Ʊ��λ�绰-��˰��ʶ���-��������-�����˺���Ϣ ID2245 2016-11-30
    function c_getLastInfoJson(){
        $objType = $_REQUEST['objType'];
        $customerId = $_REQUEST['customerId'];
        $createId = $_REQUEST['createId'];
        $invoiceType = $_REQUEST['invoiceType'];
        $objCode = $_REQUEST['objCode'];
        $countInfo = $this->service->find(// �Ȳ�ѯ�ÿͻ��ڶ�Ӧ�Ŀ�Ʊ�����Լ����ݱ���µĿ�Ʊ��Ϣ
            array('customerId' => $customerId, 'createId' => $createId, 'objType' => $objType, 'invoiceType' => $invoiceType, 'objCode' => $objCode),
            'id desc', 'invoiceType,linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
        );

        if(empty($countInfo)){////�����Ӧ�ķ�Ʊ���ͺ͵���û����ʷ��¼����ѡ�˵�������һ��
            $countInfo = $this->service->find(// �Ȳ�ѯ�ÿͻ��ڶ�Ӧ�Ŀ�Ʊ�����Լ����ݱ���µĿ�Ʊ��Ϣ
                array('customerId' => $customerId, 'createId' => $createId, 'objType' => $objType, 'objCode' => $objCode),
                'id desc', 'invoiceType,linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
            );
        }

        if(empty($countInfo)){//������϶�û�У���ѡ������ʷ������һ��
            $countInfo = $this->service->find(
                array('customerId' => $customerId, 'createId' => $createId, 'objType' => $objType),
                'id desc', 'invoiceType,linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
            );
        }

        $backData['msg'] = '';
        $backData['data'] = array();
        if(!empty($countInfo)){
            $backData['msg'] = 'ok';
            $backData['data'] = $countInfo;
        }
        echo util_jsonUtil::encode($backData);
    }
    /**
     * ��������
     */
    function c_toAddIndep() {
        //Ȩ�޿���
        if (empty($this->service->this_limit['��������'])) {
            $this->showDatadicts(array('invoiceType' => 'XSFP'), null, false, array('expand3No' => '0', 'expand4No' => 1));
        } else {
            $this->showDatadicts(array('invoiceType' => 'XSFP'), null, false, array('expand3No' => '0'));
        }

        //��ȡ������˾
        $logisticsArr = $this->service->getDefaultExpressCom_d();
        $this->assign('expressCompany', $logisticsArr['companyName']);
        $this->assign('expressCompanyId', $logisticsArr['id']);

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->showDatadicts(array('objType' => 'KPRK'));
        $this->showDatadicts(array('customerType' => 'KHLX'));
        $this->assign('applyName', $_SESSION['USERNAME']);
        $this->assign('currency', '�����');
        $this->assign('rate', 1);

        $this->assign('getLastInfoUrl', "createId={$_SESSION['USER_ID']}");
        //Ĭ���ʼ�����
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);
        $this->display('add');
    }

    /**
     * ������Ʊ����
     */
    function c_add() {
        //��ȡ����
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $object = isset($_POST [$this->objName]) ? $_POST [$this->objName] : null;
        $obj = isset($object ['obj']) ? null : null;
        if ($obj) {
            $newClass = $this->service->getClass($obj['objType']);
            $id = $this->service->add_d($object, $act, new $newClass());
        } else {
            $id = $this->service->add_d($object, $act);
        }
        if ($act == 'edit') {
            if (is_numeric($id)) {
                msgRf('����ɹ���');
            } else {
                msgRf($id);
            }
        } else {
            if (is_numeric($id)) {
                if ($object['isOffSite'] == 1) {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&formName=��ؿ�Ʊ����');
                } else {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $id);
                }
            } else {
                msgRf($id);
            }
        }
    }

    /**
     * ��д��ʼ������
     */
    function c_init() {
        //URLȨ�޿���
        $this->permCheck();
        $perm = isset($_GET ['perm']) ? $_GET ['perm'] : null;
        $apply = $this->service->get_d($_GET ['id'], $perm);
        $this->assignFunc($apply);
        $this->assign('getLastInfoUrl', "customerId={$apply['customerId']}&createId={$_SESSION['USER_ID']}&objType={$apply['objType']}"); //ID2245 2016-12-07
        if ($perm == 'view') {
            $this->assign('skey', $this->md5Row($apply['objId'], $this->service->rtTypeClass($apply['objType']), null));
            $this->assign('isMail', $this->service->getMailStatus($apply['isMail']));
            $this->assign('isOffSite', $this->service->getMailStatus($apply['isOffSite']));
            $this->assign('isNeedStamp', $this->service->getStampStatus($apply['isNeedStamp']));
            //�������{file}
            $this->assign('file', $this->service->getFilesByObjId($apply ['id'], false, $this->service->tbl_name));

            //�ж��Ƿ����عرհ�ť
            if (isset($_GET['hideBtn'])) {
                $this->assign('hideBtn', 1);
            } else {
                $this->assign('hideBtn', 0);
            }

            $this->display('view');
        } else {
            $canApply = 0;
            //����
            $this->assign('file', $this->service->getFilesByObjId($apply ['id'], true, $this->service->tbl_name));
            if (!empty($apply['objType']) && !empty($apply['objId']) && $apply['objId'] != 0) {
                $applyedMoney = $this->service->getMaxInvoiceMoney_d($apply);
                if ($applyedMoney != 0) {
                    $canApply = bcsub($apply['contAmount'], bcsub($applyedMoney, $apply['invoiceMoney'], 2), 2);
                } else {
                    $canApply = $apply['contAmount'];
                }
            }//��ȡ��Ӧҵ���ʣ���������

            $this->assign('canApply', $canApply);

            //Ȩ�޿���
            if (empty($this->service->this_limit['��������'])) {
                $this->showDatadicts(array('invoiceType' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0', 'expand4No' => 1));
            } else {
                $this->showDatadicts(array('invoiceType' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0'));
            }
            $this->display('edit');
        }
    }

    /**
     * �����в鿴��Ʊ����
     */
    function c_initAuditing() {
        //URLȨ�޿���
        $this->permCheck();
        $apply = $this->service->get_d($_GET ['id'], 'audit');

        if (!empty($apply['objType']) && $apply['objId'] != 0 && !empty($apply['objId'])) {
            $moneyRow = $this->service->getContractInfo_d($apply['objId'], $this->service->rtTableName($apply['objType']));
            $this->assign('contractInfo', $this->service->showContractInfo($moneyRow, $apply));
        } else {
            $this->assign('contractInfo', '<tr><td colspan="6">�޺�ͬ�����Ϣ</td><tr>');
        }

        $this->assignFunc($apply);
        //�������{file}
        $this->assign('file', $this->service->getFilesByObjId($apply ['id'], false, $this->service->tbl_name));
        $this->assign('objSkey', $this->md5Row($apply['objId'], $this->service->rtTypeClass($apply['objType']), null));
        $this->assign('isOffSite', $this->service->getMailStatus($apply['isOffSite']));
        $this->assign('isMail', $this->service->getMailStatus($apply['isMail']));
        $this->display('auditing');
    }

    /**
     * ������ɺ�����µķ���
     */
    function c_dealAfterAudit() {
        $this->service->dealAfterAudit_d($_GET ['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ��ӡ��Ʊ����
     */
    function c_printInvoiceApply() {
        //URLȨ�޿���
        $this->permCheck();
        $apply = $this->service->get_d($_GET ['id'], 'view');

        $this->assignFunc($apply);
        $this->assign('invoiceTypeCN', $this->getDataNameByCode($apply['invoiceType']));
        $this->display('print');
    }

    /**
     * �༭��Ʊ����
     */
    function c_edit() {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $object = $_POST [$this->objName];
        $rs = $this->service->edit_d($object, $act);
        if ($act == 'audit') {
            if (is_numeric($rs)) {
                if ($object['isOffSite'] == 1) {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&formName=��ؿ�Ʊ����');
                } else {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
                }
            } else {
                msgRf($rs);
            }
        } else {
            if (is_numeric($rs)) {
                msgRf('����ɹ���');
            } else {
                msgRf($rs);
            }
        }
    }

    /**
     * ���ݿ�Ʊ������п�Ʊ�Ǽ�
     */
    function c_toregister() {
        //URLȨ�޿���
        $this->permCheck();
        $id = $_GET ['id'];
        $apply = $this->service->get_d($id, 'audit');
        $this->assignFunc($apply);

        //��ȡ��Ʊ��������Ŀ�Ʊ��Ϣ
        $invoicesStr = $this->service->getInvoicesByApplyId_d($id);

        $this->assign('invoices', $invoicesStr[0]);
        $this->assign('payedAmount', $invoicesStr[1]);

        //�����Ʊ�����н��Ϳ�Ʊ��¼�еĲ���ȣ������¼���
        if ($apply['payedAmount'] == $invoicesStr[1]) {
            $remainMoney = bcsub($apply['invoiceMoney'], $apply['payedAmount'], 2);
        } else {
            //��ʾҳ��ʱ������
            $apply['payedAmount'] = $invoicesStr[1];
            $remainMoney = bcsub($apply['invoiceMoney'], $apply['payedAmount'], 2);
            $this->service->updateField(array('id' => $id), 'payedAmount', $invoicesStr[1]);
        }
        $this->assign('remainMoney', $remainMoney);
        $this->assign('objSkey', $this->md5Row($apply['objId'], $this->service->rtTypeClass($apply['objType']), null));
        $this->assign('isMail', $this->service->getMailStatus($apply['isMail']));
        $this->assign('isOffSite', $this->service->getMailStatus($apply['isOffSite']));

        $this->display('register');
    }

    /*************************************************���޸ĺ�ͬ��Ʊ���� 2010-11-10*****************************************/

    /**
     * �ҵĿ�Ʊ����
     * author can
     * 2011-1-17
     */
    function c_myPageJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $service->setCompany(0);//���蹫˾����
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Ʊ����
     */
    function c_pageJson() {
        $service = $this->service;
        if ($_POST['moneyStatus'] == 'done') {
            $_POST['done'] = 1;
        } else if ($_POST['moneyStatus'] == 'undo') {
            $_POST['undo'] = 1;
        }
        unset($_POST['moneyStatus']);
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;

        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ���ݴ���ֵָ��URL �� �鿴��ͬ/����
     */
    function c_toViewObj() {
        $obj = $_GET[$this->objName];
        switch ($obj['objType']) {
            case 'KPRK-01' :
                succ_show('?model=projectmanagent_order_order&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-02' :
                succ_show('?model=projectmanagent_order_order&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-03' :
                succ_show('?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-04' :
                succ_show('?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-05' :
                succ_show('?model=contract_rental_rentalcontract&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-06' :
                succ_show('?model=contract_rental_rentalcontract&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-07' :
                succ_show('?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-08' :
                succ_show('?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-09' :
                succ_show('?model=contract_other_other&action=viewTab&fundType=KXXZA&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-10' :
                succ_show('?model=service_accessorder_accessorder&action=viewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-11' :
                succ_show('?model=service_repair_repairapply&action=viewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            case 'KPRK-12' :
                succ_show('?model=contract_contract_contract&action=toViewTab&id=' . $obj['objId'] . '&skey=' . $obj['skey']);
                break;
            default :
                echo '<script>alert("δ���������");window.close();</script>';
        }
    }

    /**
     * ���ݴ������Ϣ��ȡ��Ʊ�����¼
     */
    function c_getInvoiceapplyList() {
        $this->assignFunc($_GET['obj']);
        $this->display('detail-list');
    }

    /**
     * ��ͬ�鿴��Ʊ����
     */
    function c_objPageJson() {
        $service = $this->service;
        $_POST['objTypes'] = $service->rtPostVla2($_POST['objType']);
        unset($_POST['objType']);
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->setCompany(0); // �رչ�˾����

        $rows = $service->page_d();
        //RUL����
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ȡ�ѿ�Ʊ������
     */
    function c_getApplyedMoney() {
        $object = array('objId' => $_POST['objId'], 'objType' => $_POST['objType']);
        //��ȡ��Ӧҵ���ʣ���������
        echo $this->service->getMaxInvoiceMoney_d($object);
    }

    /**
     * ��ȡ�ʼ�������Ϣ
     */
    function c_getMailInfo() {
        echo util_jsonUtil::encode($this->service->getMailInfo_d($_POST['thisVal']));
    }
}