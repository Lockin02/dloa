<?php

/**
 * ��Ʊ�Ǽǿ��Ʋ���
 */
class controller_finance_invoice_invoice extends controller_base_action
{

    function __construct() {
        $this->objName = "invoice";
        $this->objPath = "finance_invoice";
        parent::__construct();
    }

    /**
     * ��дc_page
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * ���ݿ�Ʊ����id�鿴��Ʊ��¼
     */
    function c_pageByInvoiceapply() {
        $this->assign('applyNo', $_GET['applyNo']);
        $this->assign('applyId', $_GET['applyId']);
        $this->display('list-byinvoiceapply');
    }

    /**
     * ������ת������ҳ��
     */
    function c_toAdd() {
        //���������ֵ�
        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), null, false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), null, true);
        $this->assign('invoiceTime', day_date);

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('currency', '�����');
        $this->assign('rate', 1);

        parent::c_toAdd();
    }

    /**
     * �����������
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msg('��ӳɹ���');
        } else {
            msg('���ʧ��! ');
        }
    }

    /**
     * ��ת������ҳ�� ���ݿ�Ʊ����
     */
    function c_toAddFromApply() {
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;

        $this->assign('remainMoney', $remainMoney);
        //��ȡ��Ʊ������Ϣ
        $apply = $this->service->getInvoiceapply_d($_GET ['applyId']);

        $this->assignFunc($apply);

        $this->assign('invoiceTime', day_date);

        //���������ֵ�
        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $apply['customerType']);
        $this->display('add-apply', true);
    }

    /**
     * ���ݷ�Ʊ������ӷ�Ʊ
     */
    function c_addFromApply() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msg('��ӳɹ���');
        } else {
            msg('���ʧ��! ');
        }
    }

    /**
     * ��ʼ����Ʊ
     */
    function c_init() {
        //URLȨ�޿���
        $this->permCheck();
        //����ӿ�Ʊ��������޸ģ�����ʾ��ͬ����Ʊ����ؼ�
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';

        $this->assign('remainMoney', $remainMoney);
        //��ȡ��Ʊ��ϸ
        $invoice = $this->service->get_d($_GET['id'], $perm);
        $this->assignFunc($invoice);

        //���������ֵ�
        if ($perm == 'view') {
            $this->assign('objTypeCN', $this->getDataNameByCode($invoice['objType']));
            $this->assign('invoiceType', $this->getDataNameByCode($invoice['invoiceType']));
            $this->assign('invoiceUnitType', $this->getDataNameByCode($invoice['invoiceUnitType']));
            $this->display('view');
        } else {
            //��ȡĬ�Ϸ�����
            $rs = $this->service->getSendMen_d();
            $this->assignFunc($rs);

            $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $invoice['invoiceType'], false, array('expand3No' => '0'));
            $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $invoice['invoiceUnitType']);
            $this->assign('objTypeCN', $this->getDataNameByCode($invoice['objType']));
            $this->display('edit', true);
        }
    }

    /**
     * ��Ʊ�������޸Ŀ�Ʊ��¼
     */
    function c_toEditInApply() {
        //����ӿ�Ʊ��������޸ģ�����ʾ��ͬ����Ʊ����ؼ�
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;
        //��ȡ��Ʊ��ϸ
        $invoice = $this->service->get_d($_GET['id'], 'edit');
        $this->assign('remainMoney', bcadd($remainMoney, $invoice['invoiceMoney'], 2));
        $this->assignFunc($invoice);

        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $invoice['invoiceType'], false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $invoice['invoiceUnitType']);
        $this->showDatadicts(array('objTypeList' => 'KPRK'));
        $this->display('editinapply', true);
    }

    /**
     * �༭
     */
    function c_edit() {
        $this->checkSubmit();
        if ($this->service->edit_d($_POST [$this->objName])) {
            msg('�༭�ɹ���');
        } else {
            msg('�༭ʧ��! ');
        }
    }

    /**
     * ������Ʊ����ķ�Ʊɾ��
     */
    function c_ajaxDelForApply() {
        echo $this->service->deletes_d($_POST ['id']) ? 1 : 0;
    }

    /**
     *  ���ɺ��ַ�Ʊ
     */
    function c_toAddRedInvoice() {
        //URLȨ�޿���
        $this->permCheck();
        //��ȡ��Ʊ��ϸ
        $invoice = $this->service->get_d($_GET['id'], 'red');

        //��ȡ������˾����
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->assignFunc($invoice);
        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $invoice['invoiceType'], true, array('expand3No' => '0'));

        if (empty($invoice['contractUnitName'])) {
            $this->assign('contractUnitName', $invoice['invoiceUnitName']);
            $this->assign('contractUnitId', $invoice['invoiceUnitId']);
        }
        $this->display('addredinvoice', true);
    }

    /**
     * ��֤�Ƿ������ɺ��ַ�Ʊ
     */
    function c_hasRedInvoice() {
        echo $this->service->hasRedInvoice_d($_POST['id']) ? 1 : 0;
    }

    /************************��Ʊ��ʷ*****************************/
    /**
     * ��Ʊ��ʷ�б�
     */
    function c_invoiceHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
        $this->assignFunc($obj);
        $this->display('invoiceHistory');
    }

    /************************��Ʊ��ѯ����**************************/
    /**
     * ��ѯ��Ʊ���
     */
    function c_toSearch() {
        $object = array(
            'objType' => 'all',
            'beginYear' => '',
            'endYear' => '',
            'beginMonth' => '',
            'endMonth' => '',
            'customerId' => '',
            'customerName' => '',
            'objCodeSearch' => '',
            'customerProvince' => '',
            'customerType' => '',
            'salesmanId' => '',
            'areaName' => '',
            'invoiceNo' => ''
        );
        $this->c_invoiceInfoList($object);
    }

    /**
     * ������ϸ��Ʊ��¼�б�
     * 20110225
     */
    function c_invoiceInfoList($object = null) {
        $object = empty($object) ? $_GET : $object;
        $this->assignFunc($object);
        $this->assign('objTypeCN', $this->service->rtTypeVla($object['objType']));
        $this->display('list-info');
    }

    /**
     * ��ϸ��Ʊ��¼pageJson TODO
     */
    function c_pageJsonInfoList() {
        $service = $this->service;
        //����Ȩ������ -- ϵͳ����
        $newRegionLimit = $this->c_getRegionLimit(1);

        //����Ȩ��
        $newDeptLimit = $this->c_getDeptLimit(1);

        //��˾Ȩ��
        $comLimit = $this->c_getComLimit(1);

        if (!strstr($newRegionLimit, ';;') && !strstr($newDeptLimit, ';;') && !strstr($comLimit, ';;')) {
            if (empty($newRegionLimit)) {
                $_POST['deptIdArr'] = $newDeptLimit;
            } else if (!empty($newRegionLimit)) {
                $sqlStr = "sql: and (";
                $sqlStr .= " c.areaName in (" . util_jsonUtil::strBuild($newRegionLimit);
                $sqlStr .= ") or c.deptId in (";
                $sqlStr .= util_jsonUtil::strBuild($newDeptLimit);
                $sqlStr .= "))";
                $_POST['mySearchCondition'] = $sqlStr;
            }
        } else {
            $service->setCompany(0);
        }

        if (!empty($_POST['beginYear'])) {
            $beginDate = $_POST['beginYear'] . '-' . $_POST['beginMonth'] . '-1';
            $endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $_POST['endMonth'], $_POST['endYear']);
            $endDate = $_POST['endYear'] . "-" . $_POST['endMonth'] . "-" . $endYearMonthNum;
            unset($_POST['beginMonth']);
            unset($_POST['beginYear']);
            unset($_POST['endYear']);
            unset($_POST['endMonth']);
            $_POST['beginDate'] = $beginDate;
            $_POST['endDate'] = $endDate;
        }

        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->pageBySqlId('all');
        if (is_array($rows)) {
            $rows = $service->rebuildList2_d($rows, 1, 'all');

            //URL����
            $rows = $this->sconfig->md5Rows($rows);
        }

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Ʊ��ѯ�߼�����
     */
    function c_toSearchInfoList() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);
        $this->view('list-infosearch');
    }

    /**
     * ��Ʊ��ѯ����excel
     */
    function c_toInvoiceExcOut() {
        set_time_limit(0);
		ini_set('memory_limit', '1024M'); //�����ڴ�
        $service = $this->service;
        $object = $_GET[$this->objName];
        //����Ȩ������ -- ϵͳ����
        $newRegionLimit = $this->c_getRegionLimit(1);

        //����Ȩ��
        $newDeptLimit = $this->c_getDeptLimit(1);

        //��˾Ȩ��
        $comLimit = $this->c_getComLimit(1);

        if (!strstr($newRegionLimit, ';;') && !strstr($newDeptLimit, ';;') && !strstr($comLimit, ';;')) {
            if (empty($newRegionLimit)) {
                $object['deptIdArr'] = $newDeptLimit;
            } else {
                $sqlStr = "sql: and (";
                $sqlStr .= " c.areaName in (" . util_jsonUtil::strBuild($newRegionLimit);
                $sqlStr .= ") or c.deptId in (";
                $sqlStr .= util_jsonUtil::strBuild($newDeptLimit);
                $sqlStr .= "))";
                $object['mySearchCondition'] = $sqlStr;
            }
        } else {
            $service->setCompany(0);
        }

        if (!empty($object['beginYear'])) {
            $beginDate = $object['beginYear'] . '-' . $object['beginMonth'] . '-1';
            $endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $object['endMonth'], $object['endYear']);
            $endDate = $object['endYear'] . "-" . $object['endMonth'] . "-" . $endYearMonthNum;
            unset($object['beginMonth']);
            unset($object['beginYear']);
            unset($object['endYear']);
            unset($object['endMonth']);
            $object['beginDate'] = $beginDate;
            $object['endDate'] = $endDate;
        }

        $service->getParam($object); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->sort = 'c.invoiceTime asc,c.createTime';
        $service->asc = false;
        $rows = $service->list_d('all_excel');

        if (is_array($rows)) {
            $objArr = $service->listBySqlId('all_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['thisAreaName'] = '�ϼ�';
            }
            $rows[] = $rsArr;
        }

        if ($_GET['excelType'] == '05') {
            return model_finance_common_financeExcelUtil::exportInvoice($rows);
        } else {
            return model_finance_common_financeExcelUtil::exportInvoiceWithExcel07($rows);
        }
    }

    /**
     * ��Ʊ��ѯ����excel - ��Ʊ��ϸ���ϲ�
     */
    function c_toInvoiceExcOutNotMerge() {
        set_time_limit(0);
		ini_set('memory_limit', '1024M'); //�����ڴ�
        $service = $this->service;
        $object = $_GET[$this->objName];
        //����Ȩ������ -- ϵͳ����
        $newRegionLimit = $this->c_getRegionLimit(1);

        //����Ȩ��
        $newDeptLimit = $this->c_getDeptLimit(1);

        //��˾Ȩ��
        $comLimit = $this->c_getComLimit(1);

        if (!strstr($newRegionLimit, ';;') && !strstr($newDeptLimit, ';;') && !strstr($comLimit, ';;')) {
            if (empty($newRegionLimit)) {
                $object['deptIdArr'] = $newDeptLimit;
            } else {
                $sqlStr = "sql: and (";
                $sqlStr .= " c.areaName in (" . util_jsonUtil::strBuild($newRegionLimit);
                $sqlStr .= ") or c.deptId in (";
                $sqlStr .= util_jsonUtil::strBuild($newDeptLimit);
                $sqlStr .= "))";
                $object['mySearchCondition'] = $sqlStr;
            }
        } else {
            $service->setCompany(0);
        }

        if (!empty($object['beginYear'])) {
            $beginDate = $object['beginYear'] . '-' . $object['beginMonth'] . '-1';
            $endYearMonthNum = cal_days_in_month(CAL_GREGORIAN, $object['endMonth'], $object['endYear']);
            $endDate = $object['endYear'] . "-" . $object['endMonth'] . "-" . $endYearMonthNum;
            unset($object['beginMonth']);
            unset($object['beginYear']);
            unset($object['endYear']);
            unset($object['endMonth']);
            $object['beginDate'] = $beginDate;
            $object['endDate'] = $endDate;
        }

        $service->getParam($object); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->sort = 'c.invoiceTime asc,c.createTime';
        $service->asc = false;
        $rows = $service->list_d('all_nomerge');

        if (is_array($rows)) {
            $objArr = $service->listBySqlId('all_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['thisAreaName'] = '�ϼ�';
            }
            $rows[] = $rsArr;
        }

        if (isset($_GET['excelType']) && $_GET['excelType'] == '05') {
            return model_finance_common_financeExcelUtil::exportInvoice($rows);
        } else {
            return model_finance_common_financeExcelUtil::exportInvoiceWithExcel07($rows);
        }
    }

    /**
     * ��Ʊ��ѯ����
     */
    function c_toListInfo() {
        $object = array(
            'objType' => 'all',
            'beginYear' => date('Y'),
            'beginMonth' => 1,
            'endYear' => date('Y'),
            'endMonth' => date('m'),
            'customerId' => '',
            'customerName' => '',
            'objCodeSearch' => '',
            'customerProvince' => '',
            'customerType' => '',
            'salesmanId' => '',
            'salesman' => '',
            'areaName' => '',
            'invoiceNo' => '',
            'signSubjectName' => ''
        );

        $object = isset($_GET['beginYear']) ? $_GET : $object;

        $this->assignFunc($object);

        $this->display('listinfo');
    }

    /**
     * ��Ʊ��ѯ �߼�����
     */
    function c_listinfoSearch() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);
        $this->view('listinfosearch');
    }

    //����Ȩ�޻�ȡ
    function c_getRegionLimit($type = null) {
        //����Ȩ������ -- ϵͳ����
        $regionDao = new model_system_region_region();
        $regionNames = $regionDao->getUserAreaName($_SESSION['USER_ID'], 2);

        //Ȩ��ϵͳ
        $regionLimit = $this->service->this_limit['��������'];
        //Ȩ�޺ϲ�
        $newLimit = implode(',', array_unique(array_merge(explode(',', $regionNames), explode(',', $regionLimit))));
        if ($type) {
            return $newLimit;
        } else {
            echo util_jsonUtil::iconvGB2UTF($newLimit);
        }
    }

    //����Ȩ�޻�ȡ
    function c_getDeptLimit($type = null) {
        //����Ȩ��
        $deptLimit = $this->service->this_limit['����Ȩ��'];
        if ($deptLimit) {
            $innerLimitArr = explode(',', $deptLimit);
            array_push($innerLimitArr, $_SESSION['DEPT_ID']);
            $newDeptLimit = implode(',', array_unique($innerLimitArr));
        } else {
            $newDeptLimit = $_SESSION['DEPT_ID'];
        }
        if ($type) {
            return $newDeptLimit;
        } else {
            echo $newDeptLimit;
        }
    }

    //��˾Ȩ�޻�ȡ
    function c_getComLimit($type = null) {
        //��˾Ȩ��
        $comLimit = $this->service->this_limit['��˾Ȩ��'];
        if (!$comLimit) {
            $comLimit = $_SESSION['Company'];
        }
        if ($type) {
            return $comLimit;
        } else {
            echo $comLimit;
        }
    }

    /************************��Ʊ��ѯ����**************************/

    /************************��Ʊ�� ��ѯ����**************************/
    /**
     * ��Ʊ���Ԥ��
     */
    function c_toInvoicePerview() {
        $rows = $this->service->getYearPlan_d();

        $this->assignFunc($rows);
        $this->showDatadicts(array('objType' => 'KPRK'));
        $this->display('toinvoiceperview');
    }

    /**
     * ��Ʊ���Ԥ��
     */
    function c_invoicePerview() {
        $object = $_GET[$this->objName];
        $rows = $this->service->getInvoicePerView_d($object);
        $rows['quarterArr'] = $this->service->showQuarterList($rows['quarterArr']);

        $this->assignFunc($rows);

        $this->display('invoicePerview');
    }

    /**
     * �����鿴��Ʊ��¼
     */
    function c_getInvoiceRecords() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
        $this->assignFunc($obj);
        $this->display('detail-order');
    }

    /**
     * �����鿴��Ʊ��¼���
     */
    function c_getInvoiceRecordsMoney() {
        $objCode = $_REQUEST['objCode'];
        $objType = $_REQUEST['objType'];// Ʊ�������ں�Ʊ������Ʊ
        $arr = $this->service->sumMoneyByObjCode_d($objCode,$objType);
//        echo util_jsonUtil::encode($arr);
        echo $arr[0]['invoiceMoney'];
    }

    /**
     * ҵ�����pageJson
     */
    function c_objPageJson() {
        $service = $this->service;
        $service->setCompany(0); // ҵ���ѯ�б�����Ҫ���˹�˾
        $_POST['objTypes'] = $service->rtPostVla($_POST['objType']);
        unset($_POST['objType']);
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

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
     * ��Ʊ��¼excel����
     */
    function c_toExcel() {
        $this->display('toexcel');
    }

    /**
     * ��Ʊ��¼����
     */
    function c_upExcel() {
        $resultArr = $this->service->addExecelData_d($_POST['isCheck']);
        $title = '��Ʊ��¼�������б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * ��Ʊ��¼excel����
     */
    function c_toExcelUpdate() {
        $this->display('toexcelupdate');
    }

    /**
     * ��Ʊ��¼�������
     */
    function c_updateInvoiceExcel() {
        $resultArr = $this->service->editExecelData_d();
        $title = '��Ʊ��¼�������б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /*******************************��Ʊ���๦��*******************************/

    /**
     * ��Ʊ����
     */
    function c_classifyInvoiceList() {
        $this->view('list-classifyinvoice');
    }

    /**
     * ��������ҳ��
     */
    function c_batchDeal() {
        $service = $this->service;
        //��ȡѡ��Ŀ�Ʊ��¼
        $service->searchArr['ids'] = $_GET['ids'];
        $service->sort = 'c.invoiceTime';
        $rows = $service->listBySqlId('select_todeal');

        //��ʾ��ȡ���Ŀ�Ʊ��¼
        $str = $service->showInvoiceBatchDeal($rows);
        $this->assign('invoices', $str);
        $this->view('batchdeal');
    }

    /**
     * �����������
     */
    function c_batchDealAct() {
        if ($this->service->batchDealAct_d($_POST[$this->objName])) {
            msg('����ɹ�');
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /************************* ��Ʊ�鿴Դ������ **********************/
    /**
     * ��Ʊ�鿴Դ��
     */
    function c_toViewObj() {
        $key = $this->md5Row($_GET['objId'], $this->service->rtTypeClass($_GET['objType']), null);
        switch ($_GET['objType']) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                succ_show('?model=projectmanagent_order_order&action=toViewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                succ_show('?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                succ_show('?model=contract_rental_rentalcontract&action=toViewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                succ_show('?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-09' :
                succ_show('?model=contract_other_other&action=viewTab&fundType=KXXZA&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-10' :
                succ_show('?model=service_accessorder_accessorder&action=viewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-11' :
                succ_show('?model=service_repair_repairapply&action=viewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            case 'KPRK-12' :
                succ_show('?model=contract_contract_contract&action=toViewTab&id=' . $_GET['objId'] . '&skey=' . $key);
                break;
            default :
                echo '<script>alert("δ���������");window.close();</script>';
        }
    }
}