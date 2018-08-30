<?php

/**
 * 开票登记控制层类
 */
class controller_finance_invoice_invoice extends controller_base_action
{

    function __construct() {
        $this->objName = "invoice";
        $this->objPath = "finance_invoice";
        parent::__construct();
    }

    /**
     * 重写c_page
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * 根据开票申请id查看开票记录
     */
    function c_pageByInvoiceapply() {
        $this->assign('applyNo', $_GET['applyNo']);
        $this->assign('applyId', $_GET['applyId']);
        $this->display('list-byinvoiceapply');
    }

    /**
     * 单独跳转到新增页面
     */
    function c_toAdd() {
        //设置数据字典
        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), null, false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), null, true);
        $this->assign('invoiceTime', day_date);

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('currency', '人民币');
        $this->assign('rate', 1);

        parent::c_toAdd();
    }

    /**
     * 新增对象操作
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msg('添加成功！');
        } else {
            msg('添加失败! ');
        }
    }

    /**
     * 跳转到新增页面 根据开票申请
     */
    function c_toAddFromApply() {
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;

        $this->assign('remainMoney', $remainMoney);
        //获取开票申请信息
        $apply = $this->service->getInvoiceapply_d($_GET ['applyId']);

        $this->assignFunc($apply);

        $this->assign('invoiceTime', day_date);

        //设置数据字典
        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $apply['invoiceType'], false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $apply['customerType']);
        $this->display('add-apply', true);
    }

    /**
     * 根据发票申请添加发票
     */
    function c_addFromApply() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msg('添加成功！');
        } else {
            msg('添加失败! ');
        }
    }

    /**
     * 初始化发票
     */
    function c_init() {
        //URL权限控制
        $this->permCheck();
        //如果从开票申请进行修改，不显示合同及开票申请控件
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';

        $this->assign('remainMoney', $remainMoney);
        //获取发票详细
        $invoice = $this->service->get_d($_GET['id'], $perm);
        $this->assignFunc($invoice);

        //设置数据字典
        if ($perm == 'view') {
            $this->assign('objTypeCN', $this->getDataNameByCode($invoice['objType']));
            $this->assign('invoiceType', $this->getDataNameByCode($invoice['invoiceType']));
            $this->assign('invoiceUnitType', $this->getDataNameByCode($invoice['invoiceUnitType']));
            $this->display('view');
        } else {
            //获取默认发送人
            $rs = $this->service->getSendMen_d();
            $this->assignFunc($rs);

            $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $invoice['invoiceType'], false, array('expand3No' => '0'));
            $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $invoice['invoiceUnitType']);
            $this->assign('objTypeCN', $this->getDataNameByCode($invoice['objType']));
            $this->display('edit', true);
        }
    }

    /**
     * 开票申请中修改开票记录
     */
    function c_toEditInApply() {
        //如果从开票申请进行修改，不显示合同及开票申请控件
        $remainMoney = isset($_GET['remainMoney']) ? $_GET['remainMoney'] : 0;
        //获取发票详细
        $invoice = $this->service->get_d($_GET['id'], 'edit');
        $this->assign('remainMoney', bcadd($remainMoney, $invoice['invoiceMoney'], 2));
        $this->assignFunc($invoice);

        $this->showDatadicts(array('invoiceTypeList' => 'XSFP'), $invoice['invoiceType'], false, array('expand3No' => '0'));
        $this->showDatadicts(array('invoiceUnitType' => 'KHLX'), $invoice['invoiceUnitType']);
        $this->showDatadicts(array('objTypeList' => 'KPRK'));
        $this->display('editinapply', true);
    }

    /**
     * 编辑
     */
    function c_edit() {
        $this->checkSubmit();
        if ($this->service->edit_d($_POST [$this->objName])) {
            msg('编辑成功！');
        } else {
            msg('编辑失败! ');
        }
    }

    /**
     * 关联开票申请的发票删除
     */
    function c_ajaxDelForApply() {
        echo $this->service->deletes_d($_POST ['id']) ? 1 : 0;
    }

    /**
     *  生成红字发票
     */
    function c_toAddRedInvoice() {
        //URL权限控制
        $this->permCheck();
        //获取发票详细
        $invoice = $this->service->get_d($_GET['id'], 'red');

        //获取归属公司名称
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
     * 验证是否已生成红字发票
     */
    function c_hasRedInvoice() {
        echo $this->service->hasRedInvoice_d($_POST['id']) ? 1 : 0;
    }

    /************************开票历史*****************************/
    /**
     * 开票历史列表
     */
    function c_invoiceHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
        $this->assignFunc($obj);
        $this->display('invoiceHistory');
    }

    /************************开票查询部分**************************/
    /**
     * 查询开票入库
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
     * 财务详细开票记录列表
     * 20110225
     */
    function c_invoiceInfoList($object = null) {
        $object = empty($object) ? $_GET : $object;
        $this->assignFunc($object);
        $this->assign('objTypeCN', $this->service->rtTypeVla($object['objType']));
        $this->display('list-info');
    }

    /**
     * 详细开票记录pageJson TODO
     */
    function c_pageJsonInfoList() {
        $service = $this->service;
        //区域权限载入 -- 系统部分
        $newRegionLimit = $this->c_getRegionLimit(1);

        //部门权限
        $newDeptLimit = $this->c_getDeptLimit(1);

        //公司权限
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

        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->pageBySqlId('all');
        if (is_array($rows)) {
            $rows = $service->rebuildList2_d($rows, 1, 'all');

            //URL过滤
            $rows = $this->sconfig->md5Rows($rows);
        }

        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 开票查询高级搜索
     */
    function c_toSearchInfoList() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);
        $this->view('list-infosearch');
    }

    /**
     * 开票查询导出excel
     */
    function c_toInvoiceExcOut() {
        set_time_limit(0);
		ini_set('memory_limit', '1024M'); //设置内存
        $service = $this->service;
        $object = $_GET[$this->objName];
        //区域权限载入 -- 系统部分
        $newRegionLimit = $this->c_getRegionLimit(1);

        //部门权限
        $newDeptLimit = $this->c_getDeptLimit(1);

        //公司权限
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

        $service->getParam($object); //设置前台获取的参数信息
        $service->sort = 'c.invoiceTime asc,c.createTime';
        $service->asc = false;
        $rows = $service->list_d('all_excel');

        if (is_array($rows)) {
            $objArr = $service->listBySqlId('all_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['thisAreaName'] = '合计';
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
     * 开票查询导出excel - 开票明细不合并
     */
    function c_toInvoiceExcOutNotMerge() {
        set_time_limit(0);
		ini_set('memory_limit', '1024M'); //设置内存
        $service = $this->service;
        $object = $_GET[$this->objName];
        //区域权限载入 -- 系统部分
        $newRegionLimit = $this->c_getRegionLimit(1);

        //部门权限
        $newDeptLimit = $this->c_getDeptLimit(1);

        //公司权限
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

        $service->getParam($object); //设置前台获取的参数信息
        $service->sort = 'c.invoiceTime asc,c.createTime';
        $service->asc = false;
        $rows = $service->list_d('all_nomerge');

        if (is_array($rows)) {
            $objArr = $service->listBySqlId('all_sum');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['thisAreaName'] = '合计';
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
     * 开票查询报表
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
     * 开票查询 高级搜索
     */
    function c_listinfoSearch() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);
        $this->view('listinfosearch');
    }

    //区域权限获取
    function c_getRegionLimit($type = null) {
        //区域权限载入 -- 系统部分
        $regionDao = new model_system_region_region();
        $regionNames = $regionDao->getUserAreaName($_SESSION['USER_ID'], 2);

        //权限系统
        $regionLimit = $this->service->this_limit['销售区域'];
        //权限合并
        $newLimit = implode(',', array_unique(array_merge(explode(',', $regionNames), explode(',', $regionLimit))));
        if ($type) {
            return $newLimit;
        } else {
            echo util_jsonUtil::iconvGB2UTF($newLimit);
        }
    }

    //区域权限获取
    function c_getDeptLimit($type = null) {
        //部门权限
        $deptLimit = $this->service->this_limit['部门权限'];
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

    //公司权限获取
    function c_getComLimit($type = null) {
        //公司权限
        $comLimit = $this->service->this_limit['公司权限'];
        if (!$comLimit) {
            $comLimit = $_SESSION['Company'];
        }
        if ($type) {
            return $comLimit;
        } else {
            echo $comLimit;
        }
    }

    /************************开票查询部分**************************/

    /************************开票额 查询部分**************************/
    /**
     * 开票额度预览
     */
    function c_toInvoicePerview() {
        $rows = $this->service->getYearPlan_d();

        $this->assignFunc($rows);
        $this->showDatadicts(array('objType' => 'KPRK'));
        $this->display('toinvoiceperview');
    }

    /**
     * 开票额度预览
     */
    function c_invoicePerview() {
        $object = $_GET[$this->objName];
        $rows = $this->service->getInvoicePerView_d($object);
        $rows['quarterArr'] = $this->service->showQuarterList($rows['quarterArr']);

        $this->assignFunc($rows);

        $this->display('invoicePerview');
    }

    /**
     * 订单查看开票记录
     */
    function c_getInvoiceRecords() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
        $this->assignFunc($obj);
        $this->display('detail-order');
    }

    /**
     * 订单查看开票记录金额
     */
    function c_getInvoiceRecordsMoney() {
        $objCode = $_REQUEST['objCode'];
        $objType = $_REQUEST['objType'];// 票据是属于红票还是蓝票
        $arr = $this->service->sumMoneyByObjCode_d($objCode,$objType);
//        echo util_jsonUtil::encode($arr);
        echo $arr[0]['invoiceMoney'];
    }

    /**
     * 业务过滤pageJson
     */
    function c_objPageJson() {
        $service = $this->service;
        $service->setCompany(0); // 业务查询列表，不需要过滤公司
        $_POST['objTypes'] = $service->rtPostVla($_POST['objType']);
        unset($_POST['objType']);
        $service->getParam($_POST); //设置前台获取的参数信息

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
     * 开票记录excel导入
     */
    function c_toExcel() {
        $this->display('toexcel');
    }

    /**
     * 开票记录导入
     */
    function c_upExcel() {
        $resultArr = $this->service->addExecelData_d($_POST['isCheck']);
        $title = '开票记录导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * 开票记录excel导入
     */
    function c_toExcelUpdate() {
        $this->display('toexcelupdate');
    }

    /**
     * 开票记录导入更新
     */
    function c_updateInvoiceExcel() {
        $resultArr = $this->service->editExecelData_d();
        $title = '开票记录导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /*******************************发票归类功能*******************************/

    /**
     * 发票归类
     */
    function c_classifyInvoiceList() {
        $this->view('list-classifyinvoice');
    }

    /**
     * 批量处理页面
     */
    function c_batchDeal() {
        $service = $this->service;
        //获取选择的开票记录
        $service->searchArr['ids'] = $_GET['ids'];
        $service->sort = 'c.invoiceTime';
        $rows = $service->listBySqlId('select_todeal');

        //显示获取到的开票记录
        $str = $service->showInvoiceBatchDeal($rows);
        $this->assign('invoices', $str);
        $this->view('batchdeal');
    }

    /**
     * 批量处理操作
     */
    function c_batchDealAct() {
        if ($this->service->batchDealAct_d($_POST[$this->objName])) {
            msg('处理成功');
        } else {
            msg('处理失败');
        }
    }

    /**
     * 获取权限
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /************************* 发票查看源单部分 **********************/
    /**
     * 发票查看源单
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
                echo '<script>alert("未定义的类型");window.close();</script>';
        }
    }
}