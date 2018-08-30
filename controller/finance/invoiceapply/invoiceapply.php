<?php

/**
 * 开票申请控制层类
 */
class controller_finance_invoiceapply_invoiceapply extends controller_base_action
{

    function __construct() {
        $this->objName = "invoiceapply";
        $this->objPath = "finance_invoiceapply";
        parent::__construct();
    }

    /************************************列表部分*****************************/

    /**
     * 财务显示所有待处理的发票申请
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * 我的开票申请
     */
    function c_myInvoiceApply() {
        $this->display('my');
    }

    /***************2011-03-07修改 抽象入口业务 ************************/

    /**
     * 新增
     */
    function c_toAdd() {
        $thisObj = $_GET[$this->objName];

        //获取对应业务的剩余可申请金额
        $applyedMoney = $this->service->getMaxInvoiceMoney_d($thisObj);
        //调用策略
        $newClass = $this->service->getClass($thisObj['objType']);
        $initObj = new $newClass();
        //获取对应业务信息
        $rs = $this->service->getObjInfo_d($thisObj, $initObj);
        if (isset($rs['conCanApply'])) {
            $lastCanInvoice = $rs['conCanApply'];
        } else {
            $lastCanInvoice = $rs['money'];
        }
        if ($lastCanInvoice <= $applyedMoney) {
            msgRf('本合同申请开票金额(' . $applyedMoney . ')已达到上限!不能再进行开票申请');
            exit();
        }

        //获取归属公司名称
        $rs['formBelong'] = !isset($rs['formBelong']) || empty($rs['formBelong']) ? $_SESSION['USER_COM'] : $rs['formBelong'];
        $rs['formBelongName'] = !isset($rs['formBelongName']) || empty($rs['formBelongName']) ? $_SESSION['USER_COM_NAME'] : $rs['formBelongName'];
        $rs['businessBelong'] = !isset($rs['businessBelong']) || empty($rs['businessBelong']) ? $_SESSION['USER_COM'] : $rs['businessBelong'];
        $rs['businessBelongName'] = !isset($rs['businessBelongName']) || empty($rs['businessBelongName']) ? $_SESSION['USER_COM_NAME'] : $rs['businessBelongName'];

        //获取物流公司
        $logisticsArr = $this->service->getDefaultExpressCom_d();
        $this->assign('expressCompany', $logisticsArr['companyName']);
        $this->assign('expressCompanyId', $logisticsArr['id']);

        //查询同一源单类型、客户、申请人的最近一次申请单的联系人-电话-开票单位地址-开票单位电话-纳税人识别号-开户银行-银行账号信息
        $this->assign('getLastInfoUrl', "customerId={$rs['customerId']}&createId={$_SESSION['USER_ID']}&objType={$thisObj['objType']}"); //ID2245 2016-11-30
        $countInfo = $this->service->find(
            array('customerId' => $rs['customerId'], 'createId' => $_SESSION['USER_ID'], 'objType' => $thisObj['objType']),
            'id desc', 'linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
        );
        if (!$countInfo)
            $countInfo = array('linkMan' => '', 'linkPhone' => '', 'unitAddress' => '', 'phoneNo' => '',
                'taxpayerId' => '', 'bank' => '', 'bankCount' => '',
            );

        //渲染主表单
        $this->assignFunc($rs);

        $canApply = bcsub($lastCanInvoice, $applyedMoney, 2);
        $this->assign('canApply', $canApply);

        //渲染关联信息
        $this->assignFunc($thisObj);
        $this->assignFunc($countInfo);

        //权限控制
        if (empty($this->service->this_limit['控制申请'])) {
            $this->showDatadicts(array('invoiceTypeSelect' => 'XSFP'), null, false, array('expand3No' => '0', 'expand4No' => 1));
        } else {
            $this->showDatadicts(array('invoiceTypeSelect' => 'XSFP'), null, false, array('expand3No' => '0'));
        }
        $this->showDatadicts(array('customerTypeView' => 'KHLX'), null, true);
        $this->assign('objTypeName', $this->getDataNameByCode($thisObj['objType']));

        $this->assign('skey', $this->md5Row($thisObj['objId'], $this->service->rtTypeClass($thisObj['objType']), null));
        $this->assign('currency', isset($rs['currency']) ? $rs['currency'] : '人民币');
        $this->assign('rate', isset($rs['rate']) ? $rs['rate'] : 1);

        //默认邮件设置
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);
        $this->display('business-add');
    }

    //查询同一源单类型、客户、申请人的最近一次申请单的联系人-电话-开票单位地址-开票单位电话-纳税人识别号-开户银行-银行账号信息 ID2245 2016-11-30
    function c_getLastInfoJson(){
        $objType = $_REQUEST['objType'];
        $customerId = $_REQUEST['customerId'];
        $createId = $_REQUEST['createId'];
        $invoiceType = $_REQUEST['invoiceType'];
        $objCode = $_REQUEST['objCode'];
        $countInfo = $this->service->find(// 先查询该客户在对应的开票类型以及单据编号下的开票信息
            array('customerId' => $customerId, 'createId' => $createId, 'objType' => $objType, 'invoiceType' => $invoiceType, 'objCode' => $objCode),
            'id desc', 'invoiceType,linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
        );

        if(empty($countInfo)){////如果对应的发票类型和单据没有历史记录，则选此单据最新一条
            $countInfo = $this->service->find(// 先查询该客户在对应的开票类型以及单据编号下的开票信息
                array('customerId' => $customerId, 'createId' => $createId, 'objType' => $objType, 'objCode' => $objCode),
                'id desc', 'invoiceType,linkMan,linkPhone,unitAddress,phoneNo,taxpayerId,bank,bankCount'
            );
        }

        if(empty($countInfo)){//如果以上都没有，则选所有历史中最新一条
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
     * 独立新增
     */
    function c_toAddIndep() {
        //权限控制
        if (empty($this->service->this_limit['控制申请'])) {
            $this->showDatadicts(array('invoiceType' => 'XSFP'), null, false, array('expand3No' => '0', 'expand4No' => 1));
        } else {
            $this->showDatadicts(array('invoiceType' => 'XSFP'), null, false, array('expand3No' => '0'));
        }

        //获取物流公司
        $logisticsArr = $this->service->getDefaultExpressCom_d();
        $this->assign('expressCompany', $logisticsArr['companyName']);
        $this->assign('expressCompanyId', $logisticsArr['id']);

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->showDatadicts(array('objType' => 'KPRK'));
        $this->showDatadicts(array('customerType' => 'KHLX'));
        $this->assign('applyName', $_SESSION['USERNAME']);
        $this->assign('currency', '人民币');
        $this->assign('rate', 1);

        $this->assign('getLastInfoUrl', "createId={$_SESSION['USER_ID']}");
        //默认邮件设置
        $mailInfo = $this->service->getMailInfo_d();
        $this->assignFunc($mailInfo);
        $this->display('add');
    }

    /**
     * 新增开票申请
     */
    function c_add() {
        //获取动作
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
                msgRf('保存成功！');
            } else {
                msgRf($id);
            }
        } else {
            if (is_numeric($id)) {
                if ($object['isOffSite'] == 1) {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&formName=异地开票申请');
                } else {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $id);
                }
            } else {
                msgRf($id);
            }
        }
    }

    /**
     * 重写初始化方法
     */
    function c_init() {
        //URL权限控制
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
            //附件添加{file}
            $this->assign('file', $this->service->getFilesByObjId($apply ['id'], false, $this->service->tbl_name));

            //判断是否隐藏关闭按钮
            if (isset($_GET['hideBtn'])) {
                $this->assign('hideBtn', 1);
            } else {
                $this->assign('hideBtn', 0);
            }

            $this->display('view');
        } else {
            $canApply = 0;
            //附件
            $this->assign('file', $this->service->getFilesByObjId($apply ['id'], true, $this->service->tbl_name));
            if (!empty($apply['objType']) && !empty($apply['objId']) && $apply['objId'] != 0) {
                $applyedMoney = $this->service->getMaxInvoiceMoney_d($apply);
                if ($applyedMoney != 0) {
                    $canApply = bcsub($apply['contAmount'], bcsub($applyedMoney, $apply['invoiceMoney'], 2), 2);
                } else {
                    $canApply = $apply['contAmount'];
                }
            }//获取对应业务的剩余可申请金额

            $this->assign('canApply', $canApply);

            //权限控制
            if (empty($this->service->this_limit['控制申请'])) {
                $this->showDatadicts(array('invoiceType' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0', 'expand4No' => 1));
            } else {
                $this->showDatadicts(array('invoiceType' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0'));
            }
            $this->display('edit');
        }
    }

    /**
     * 审批中查看开票申请
     */
    function c_initAuditing() {
        //URL权限控制
        $this->permCheck();
        $apply = $this->service->get_d($_GET ['id'], 'audit');

        if (!empty($apply['objType']) && $apply['objId'] != 0 && !empty($apply['objId'])) {
            $moneyRow = $this->service->getContractInfo_d($apply['objId'], $this->service->rtTableName($apply['objType']));
            $this->assign('contractInfo', $this->service->showContractInfo($moneyRow, $apply));
        } else {
            $this->assign('contractInfo', '<tr><td colspan="6">无合同相关信息</td><tr>');
        }

        $this->assignFunc($apply);
        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($apply ['id'], false, $this->service->tbl_name));
        $this->assign('objSkey', $this->md5Row($apply['objId'], $this->service->rtTypeClass($apply['objType']), null));
        $this->assign('isOffSite', $this->service->getMailStatus($apply['isOffSite']));
        $this->assign('isMail', $this->service->getMailStatus($apply['isMail']));
        $this->display('auditing');
    }

    /**
     * 审批完成后处理盖章的方法
     */
    function c_dealAfterAudit() {
        $this->service->dealAfterAudit_d($_GET ['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 打印开票申请
     */
    function c_printInvoiceApply() {
        //URL权限控制
        $this->permCheck();
        $apply = $this->service->get_d($_GET ['id'], 'view');

        $this->assignFunc($apply);
        $this->assign('invoiceTypeCN', $this->getDataNameByCode($apply['invoiceType']));
        $this->display('print');
    }

    /**
     * 编辑开票申请
     */
    function c_edit() {
        $act = isset($_GET ['act']) ? $_GET ['act'] : 'edit';
        $object = $_POST [$this->objName];
        $rs = $this->service->edit_d($object, $act);
        if ($act == 'audit') {
            if (is_numeric($rs)) {
                if ($object['isOffSite'] == 1) {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&formName=异地开票申请');
                } else {
                    succ_show('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id']);
                }
            } else {
                msgRf($rs);
            }
        } else {
            if (is_numeric($rs)) {
                msgRf('保存成功！');
            } else {
                msgRf($rs);
            }
        }
    }

    /**
     * 根据开票申请进行开票登记
     */
    function c_toregister() {
        //URL权限控制
        $this->permCheck();
        $id = $_GET ['id'];
        $apply = $this->service->get_d($id, 'audit');
        $this->assignFunc($apply);

        //获取开票申请关联的开票信息
        $invoicesStr = $this->service->getInvoicesByApplyId_d($id);

        $this->assign('invoices', $invoicesStr[0]);
        $this->assign('payedAmount', $invoicesStr[1]);

        //如果开票申请中金额和开票记录中的不相等，则重新计算
        if ($apply['payedAmount'] == $invoicesStr[1]) {
            $remainMoney = bcsub($apply['invoiceMoney'], $apply['payedAmount'], 2);
        } else {
            //显示页面时计算金额
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

    /*************************************************新修改合同开票部分 2010-11-10*****************************************/

    /**
     * 我的开票申请
     * author can
     * 2011-1-17
     */
    function c_myPageJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = true;
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $service->setCompany(0);//无需公司过滤
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 开票申请
     */
    function c_pageJson() {
        $service = $this->service;
        if ($_POST['moneyStatus'] == 'done') {
            $_POST['done'] = 1;
        } else if ($_POST['moneyStatus'] == 'undo') {
            $_POST['undo'] = 1;
        }
        unset($_POST['moneyStatus']);
        $service->getParam($_POST); //设置前台获取的参数信息
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
     * 根据传入值指向URL － 查看合同/订单
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
                echo '<script>alert("未定义的类型");window.close();</script>';
        }
    }

    /**
     * 根据传入的信息获取开票申请记录
     */
    function c_getInvoiceapplyList() {
        $this->assignFunc($_GET['obj']);
        $this->display('detail-list');
    }

    /**
     * 合同查看开票申请
     */
    function c_objPageJson() {
        $service = $this->service;
        $_POST['objTypes'] = $service->rtPostVla2($_POST['objType']);
        unset($_POST['objType']);
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->setCompany(0); // 关闭公司过滤

        $rows = $service->page_d();
        //RUL过滤
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 获取已开票申请金额
     */
    function c_getApplyedMoney() {
        $object = array('objId' => $_POST['objId'], 'objType' => $_POST['objType']);
        //获取对应业务的剩余可申请金额
        echo $this->service->getMaxInvoiceMoney_d($object);
    }

    /**
     * 获取邮件配置信息
     */
    function c_getMailInfo() {
        echo util_jsonUtil::encode($this->service->getMailInfo_d($_POST['thisVal']));
    }
}