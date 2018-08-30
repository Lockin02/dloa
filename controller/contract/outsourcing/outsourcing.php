<?php

/**
 * @author Show
 * @Date 2011年12月3日 星期六 10:29:00
 * @version 1.0
 * @description:外包合同控制层
 */
class controller_contract_outsourcing_outsourcing extends controller_base_action
{
    private $unDeptExtFilter = "";// PMS377 此模块需要单独隐藏的部门选项
    function __construct()
    {
        $this->objName = "outsourcing";
        $this->objPath = "contract_outsourcing";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");

        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        $this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
    }

    /*
     * 跳转到外包合同
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json - 用于外包合同汇总列表
     */
    function c_pageJsonList()
    {
        $service = $this->service;

        //初始化搜索条件
        if (isset($_POST['payandinv'])) {
            $thisSet = $service->initSetting_c($_POST['payandinv']);
            $_POST[$thisSet] = 1;
            unset($_POST['payandinv']);
        }

        //系统权限
        $deptLimit = $this->service->this_limit['部门权限'];

        //办事处 － 全部 处理
        if (strstr($deptLimit, ';;')) {
            $service->getParam($_POST); //设置前台获取的参数信息
            $rows = $service->pageBySqlId('select_info');
        } else { //如果没有选择全部，则进行权限查询并赋值

            if (!empty($deptLimit)) {
                $_POST['deptIdArr'] = $deptLimit;
                $service->getParam($_POST); //设置前台获取的参数信息
                $rows = $service->page_d('select_info');
            }
        }

        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //合计加入
            $rows = $this->service->pageCount_d($rows);

            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['createDate'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }


    /**
     * 获取分页数据转成Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        //初始化搜索条件
        if (isset($_POST['payandinv'])) {
            $thisSet = $service->initSetting_c($_POST['payandinv']);
            $_POST[$thisSet] = 1;
            unset($_POST['payandinv']);
        }

        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d('select_info');

        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //合计加入
            $rows = $this->service->pageCount_d($rows);

            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['createDate'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 工程项目外包汇总表
     */
    function c_pageForESM()
    {
        $this->view('listforesm');
    }

    /**
     * 研发项目外包项目汇总表
     */
    function c_pageForRD()
    {
        $this->view('listforrd');
    }

    /**
     * 重写toadd
     */
    function c_toAdd()
    {
        $this->showDatadicts(array('outsourceType' => 'HTWB')); //外包性质
        $this->showDatadicts(array('outPayType' => 'HTFKFS')); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS')); //合同外包方式

        //付款申请信息渲染
        $this->showDatadicts(array('payFor' => 'FKLX')); //付款类型
        $this->showDatadicts(array('payType' => 'CWFKFS')); //结算方式

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $otherdatas = new model_common_otherdatas();
        $this->assign('deptName', $otherdatas->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));

        $this->assign('isSysCode', ORDERCODE_INPUT); //是否手工输入合同号

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->view('add');
    }

    /**
     * 独立新增
     */
    function c_toAddDept()
    {
        $this->showDatadicts(array('outsourceType' => 'HTWB')); //合同外包性质
        $this->showDatadicts(array('outPayType' => 'HTFKFS')); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS')); //合同外包方式

        //付款申请信息渲染
        $this->showDatadicts(array('payFor' => 'FKLX'), null, null, array('expand1' => 1)); //付款类型
        $this->showDatadicts(array('payType' => 'CWFKFS')); //结算方式

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $otherdatas = new model_common_otherdatas();
        $this->assign('deptName', $otherdatas->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));

        $this->assign('isSysCode', ORDERCODE_INPUT); //是否手工输入合同号

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->view('adddept');
    }

    /**
     * 外包立项生产合同
     */
    function c_toAddForApproval()
    {
        $this->showDatadicts(array('outsourceType' => 'HTWB'), $_GET['outsourceType']); //合同外包性质
        $this->showDatadicts(array('outPayType' => 'HTFKFS'), $_GET['payType']); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS'), $_GET['outsourcing']); //合同外包方式

        //付款申请信息渲染
        $this->showDatadicts(array('payFor' => 'FKLX'), null, null, array('expand1' => 1)); //付款类型
        $this->showDatadicts(array('payType' => 'CWFKFS')); //结算方式

        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->assign('projectName', $_GET['projectName']);
        $this->assign('orderMoney', $_GET['orderMoney']);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $otherdatas = new model_common_otherdatas();
        $this->assign('deptName', $otherdatas->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));

        $this->assign('isSysCode', ORDERCODE_INPUT); //是否手工输入合同号

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->assign('signCompanyName', $_GET['signCompanyName']);
        $signcompanyDao = new model_contract_signcompany_signcompany();
        $signCompanyObj = $signcompanyDao->find(array('signCompanyName' => $_GET['signCompanyName']));
        if (!empty($signCompanyObj)) {
            $this->assign('signCompanyId', $signCompanyObj['id']);
            $this->assign('proName', $signCompanyObj['proName']);
            $this->assign('proCode', $signCompanyObj['proCode']);
            $this->assign('linkman', $signCompanyObj['linkman']);
            $this->assign('phone', $signCompanyObj['phone']);
        } else {
            $this->assign('proName', '');
        }
        $this->assign('isPersonrental', '0');
        $this->assign('changeId', $_GET['projectId']);
        $this->assign('approvalId', '');
        $this->view('addforapproval');
    }

    /**
     * 人员租赁情况下，外包立项申请合同
     */
    function c_toChooseAdd()
    {
        $projectId = $_GET['projectId'];
        $this->assign('projectId', $projectId);
        $this->view('chooseadd');
    }

    /**
     * 选择外包立项申请合同
     */
    function c_chooseAdd()
    {
        $personRental = $_POST['persronRental'];
        $approvalArr = array(); //存储选中数组
        $orderMoney = 0;
        $approvalIdArr = array(); //存储选中的数组Id
        foreach ($personRental as $val) {
            if (isset($val['choose'])) {
                $orderMoney += $val['rentalPrice'];
                array_push($approvalIdArr, $val['id']);
                array_push($approvalArr, $val);
            }
        }
        $approvalDao = new  model_outsourcing_approval_basic();
        $approvalObj = $approvalDao->find(array('id' => $approvalArr[0]['mainId']));

        $this->showDatadicts(array('outsourceType' => 'HTWB'), $approvalObj['outsourceType']); //合同外包性质
        $this->showDatadicts(array('outPayType' => 'HTFKFS'), $approvalObj['payType']); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS'), $approvalObj['outsourcing']); //合同外包方式

        //付款申请信息渲染
        $this->showDatadicts(array('payFor' => 'FKLX'), null, null, array('expand1' => 1)); //付款类型
        $this->showDatadicts(array('payType' => 'CWFKFS')); //结算方式

        $this->assign('projectId', $approvalObj['projectId']);
        $this->assign('projectCode', $approvalObj['projectCode']);
        $this->assign('projectName', $approvalObj['projectName']);
        $this->assign('orderMoney', $orderMoney);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $otherdatas = new model_common_otherdatas();
        $this->assign('deptName', $otherdatas->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'));

        $this->assign('isSysCode', ORDERCODE_INPUT); //是否手工输入合同号

        //获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

        $this->assign('signCompanyName', $approvalArr[0]['suppName']);
        $signcompanyDao = new model_contract_signcompany_signcompany();
        $signCompanyObj = $signcompanyDao->find(array('signCompanyName' => $approvalArr[0]['suppName']));
        if (!empty($signCompanyObj)) {
            $this->assign('signCompanyId', $signCompanyObj['id']);
            $this->assign('proName', $signCompanyObj['proName']);
            $this->assign('proCode', $signCompanyObj['proCode']);
            $this->assign('linkman', $signCompanyObj['linkman']);
            $this->assign('phone', $signCompanyObj['phone']);
        } else {
            $this->assign('proName', '');
        }
        $this->assign('isPersonrental', '1');

        $this->assign('changeId', implode(',', $approvalIdArr));
        $this->assign('approvalId', $approvalArr[0]['mainId']);
        $this->view('addforapproval');
    }

    /**
     * 在建项目列表跳转
     */
    function c_addForProject()
    {
        $this->showDatadicts(array('outsourceType' => 'HTWB')); //合同外包性质
        $this->showDatadicts(array('outPayType' => 'HTFKFS')); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS')); //合同外包方式

        $obj = $this->service->getInfoProject_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->showDatadicts(array('outsourceType' => 'HTWB'), "HTWB03"); //外包性质

        $this->assign('thisDate', day_date);
        $this->view('addforproject');
    }

    /**
     * 跳转到Tab项目外包合同
     */
    function c_listForProject()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('listforproject');
    }

    /**
     * 新增对象操作
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        if (isset($_POST['isPersonrental'])) { //立项生成合同时修改生成合同状态
            if ($_POST['isPersonrental'] == 0) {
                $object['approvalId'] = $_POST['changeId'];
            } else {
                $object['approvalId'] = $_POST['approvalId'];
                $object['personrentalId'] = $_POST['changeId'];
            }
        }
        $checkResult = $this->checkForm($object);
        if ($checkResult) {
            msgRf($checkResult);
        }
        $id = $this->service->add_d($object);
        if ($id) {
            if (isset($_POST['isPersonrental'])) { //立项生成合同时修改生成合同状态
                $this->service->changeIsAddContract_d($_POST['isPersonrental'], $_POST['changeId'], 1);
            }
            if ($_GET['act']) {
                if ($object['isNeedPayapply'] == 1) {
                    succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&flowDept=' . $object['payapply']['feeDeptId'] .
                        '&billCompany=' . $object['businessBelong']);
                } else {
                    succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('保存成功');
            }
        } else {
            msgRf('保存失败');
        }
    }

    /**
     * 独立新增对象操作
     */
    function c_addDept()
    {
        $object = $_POST[$this->objName];
        $checkResult = $this->checkForm($object);
        if ($checkResult) {
            msgGo($checkResult);
        }
        $id = $this->service->add_d($object);
        if ($id) {
            if ($_GET['act']) {
                if ($object['isNeedPayapply'] == 1) {
                    succ_show('controller/contract/outsourcing/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&flowDept=' . $object['payapply']['feeDeptId'] .
                        '&billCompany=' . $object['businessBelong']);
                } else {
                    succ_show('controller/contract/outsourcing/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgGo('保存成功！', '?model=contract_outsourcing_outsourcing&action=toAddDept');
            }
        } else {
            msgGo('保存失败！', '?model=contract_outsourcing_outsourcing&action=toAddDept');
        }
    }

    /**
     * 修改对象
     */
    function c_edit()
    {
//		$this->permCheck (); //安全校验
        $object = $_POST[$this->objName];

        // 后台表单校验
        $checkResult = $this->checkForm($object);
        if ($checkResult) {
            msgRf($checkResult);
        }
        $id = $this->service->editInfo_d($object);
        if ($id) {
            if ($_GET['act']) {
                if ($object['isNeedPayapply'] == 1) {
                    succ_show('controller/contract/outsourcing/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&flowDept=' . $object['payapply']['feeDeptId'] .
                        '&billCompany=' . $object['businessBelong']);
                } else {
                    succ_show('controller/contract/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                        '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('保存成功');
            }
        } else {
            msgRf('保存失败');
        }
    }

    /**
     * 表单验证
     * @param $object
     * @return string
     */
    function checkForm($object)
    {
        if ($object) {
            if ($object['payapply']['isEntrust'] == 1
                && $object['payapply']['account'] != "已付款" && $object['payapply']['bank'] != "已付款") {
                return '保存失败！如果选择了已付款，请不要改动系统自动带出的银行和银行账号！';
            }
        } else {
            return '保存失败！没有传入数据';
        }
    }

    /**
     * 跳转审批工作流的查看页面
     */
    function c_viewAccraditation()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));

        //是否
        $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
        $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

        $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));
        $this->assign('payType', $this->getDataNameByCode($obj['payType']));
        $this->assign('pid', $_GET['id']);
        $this->view('viewAccraditation');
    }

    /**
     * 查看页面 - 包含付款申请信息
     */
    function c_viewAlong()
    {
        $this->permCheck(); //安全校验

        //提交审批后查看单据时隐藏关闭按钮
        if (isset($_GET['hideBtn'])) {
            $this->assign('hideBtn', 1);
        } else {
            $this->assign('hideBtn', 0);
        }

        $obj = $this->service->getInfo_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));

        //是否
        $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
        $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
        $this->assign('isInvoice', $this->service->rtYesOrNo_d($obj['isInvoice']));
        $this->assign('isEntrust', $this->service->rtYesOrNo_d($obj['isEntrust']));
        $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

        //签收状态
        $this->assign('signedStatusCN', $this->service->rtIsSign_d($obj['signedStatus']));

        $this->assign('payFor', $this->getDataNameByCode($obj['payFor']));
        $this->assign('payType', $this->getDataNameByCode($obj['payType']));

        $this->view('viewAlong');
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $obj = $this->service->get_d($_GET['id']);
            $this->assignFunc($obj);

            //提交审批后查看单据时隐藏关闭按钮
            if (isset($_GET['viewBtn'])) {
                $this->assign('showBtn', 1);
            } else {
                $this->assign('showBtn', 0);
            }
            //附件添加{file}
            $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));

            //是否
            $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
            $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

            //签收状态
            $this->assign('signedStatusCN', $this->service->rtIsSign_d($obj['signedStatus']));

            $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

            $this->view('view');
        } else {

            $obj = $this->service->getInfo_d($_GET['id']);
            $this->assignFunc($obj);

            //附件添加{file}
            $this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
            $this->showDatadicts(array('outsourceType' => 'HTWB'), $obj['outsourceType']);
            $this->showDatadicts(array('outPayType' => 'HTFKFS'), $obj['outPayType']); //合同付款方式
            $this->showDatadicts(array('outsourcing' => 'HTWBFS'), $obj['outsourcing']); //合同外包方式

            //付款申请信息渲染
            $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor']); //付款类型
            $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']); //结算方式

            $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));
            $this->view('edit');
        }
    }

    /**
     * 删除
     */
    function c_ajaxdeletes()
    {
        try {
            echo $this->service->delete_d($_POST['id']);
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 合同tab页
     */
    function c_viewTab()
    {
        $this->permCheck(); //安全校验
        $this->assign('id', $_GET['id']);
        $this->display('viewtab');
    }

    /**
     * 跳转申请盖章页面
     */
    function c_toStamp()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加{file}
        $this->assign('file', '暂无任何附件');
        $this->assign('applyDate', day_date);

        //当前盖章申请人
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);

        $this->view('stamp');
    }

    /**
     * 新增盖章信息操作
     */
    function c_stamp()
    {
        $rs = $this->service->stamp_d($_POST[$this->objName]);
        if ($rs) {
            msg("申请成功！");
        } else {
            msg("申请失败！");
        }
    }

    /**
     * 审批完成后处理盖章的方法
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 外包合同立项付款申请
     */
    function c_dealAfterAuditPayapply()
    {
        $this->service->dealAfterAuditPayapply_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 跳转个人外包合同
     */
    function c_myOutsourcing()
    {
        $this->view('mylist');
    }

    /**
     * 我的外包合同
     */
    function c_myOutsourcingListPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['principalIdAndCreateId'] = $_SESSION['USER_ID'];
        $service->setCompany(0); # 个人列表,不需要进行公司过滤
        $rows = $service->page_d('select_info');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转个人外包合同
     */
    function c_myStatusList()
    {
        $status = isset($_GET['status']) ? $_GET['status'] : 0;
        $this->assign('status', $status);
        $this->view('mystatuslist');
    }

    /**
     * 关闭合同
     */
    function c_changeStatus()
    {
        if ($this->service->edit_d(array('id' => $_POST['id'], 'status' => '3'))) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 附件上传
     */
    function c_toUploadFile()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
        $this->assignFunc($obj);

        $this->view('uploadfile');
    }

    /**
     * 修改信息 - 当前用来修改备注
     */
    function c_toUpdateInfo()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $this->view('updateinfo');
    }

    /**
     * 修改对象
     */
    function c_updateInfo()
    {
//		$this->permCheck (); //安全校验
        $object = $_POST[$this->objName];
        $id = $this->service->edit_d($object);
        if ($id) {
            msg('保存成功');
        } else {
            msg('保存失败');
        }
    }

    /**
     * 获取权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /***************** S 导入导出系列 *********************/
    /**
     * 合同导入
     */
    function c_toExcelIn()
    {
        $this->display('excelin');
    }

    /**
     * 合同导入操作
     */
    function c_excelIn()
    {
        if ($_POST['actionType'] == 0) {
            $resultArr = $this->service->addExecelData_d();
        }

        $title = '合同导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * 导出数据
     */
    function c_exportExcel()
    {
        $service = $this->service;
        $service->getParam($_REQUEST); //设置前台获取的参数信息
        $service->sort = 'c.createTime';
        $rows = $service->list_d("select_info");
        return model_contract_common_contractExcelUtil::outsourcingContractOut_e($rows);
    }

    /***************** E 导入导出系列 *********************/


    /******************* S 变更系列 *********************/
    function c_toChange()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
        $this->showDatadicts(array('outsourceType' => 'HTWB'), $obj['outsourceType']);
        $this->showDatadicts(array('payType' => 'HTFKFS'), $obj['payType']); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS'), $obj['outsourcing']); //合同外包方式

        $this->view('change');
    }

    /**
     * 变更操作
     * 2012-03-26
     * createBy kuangzw
     */
    function c_change()
    {
        try {
            $object = $_POST[$this->objName];
            $id = $this->service->change_d($object);
            succ_show("controller/contract/outsourcing/ewf_change.php?actTo=ewfSelect&billId=" . $id .
                '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }

    /**
     * 审批完成后处理盖章的方法
     */
    function c_dealAfterAuditChange()
    {
        $this->service->dealAfterAuditChange_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 变更查看tab
     */
    function c_changeTab()
    {
        $this->permCheck(); //安全校验
        $newId = $_GET['id'];
        $this->assign('id', $newId);

        $rs = $this->service->find(array('id' => $newId), null, 'originalId');
        $this->assign('originalId', $rs['originalId']);

        $this->display('changetab');
    }

    /**
     * 变更查看合同  - 查看原合同
     */
    function c_changeView()
    {
        $this->permCheck(); //安全校验
        $id = $_GET['id'];

        $obj = $this->service->get_d($id);

        $this->assignFunc($obj);

        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($id, false, $this->service->tbl_name));

        $this->assign('stampType', $this->getDataNameByCode($obj['stampType']));

        $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
        $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
        $this->assign('isNeedRestamp', $this->service->rtYesOrNo_d($obj['isNeedRestamp']));

        $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));
        $this->view('changeview');
    }
    /******************* E 变更系列 *********************/

    /******************* S 签收系列 *********************/
    /**
     * 合同签收 - 列表tab页
     */
    function c_signTab()
    {
        $this->display('signTab');
    }

    /**
     * 合同签收 - 待签收合同列表
     */
    function c_signingList()
    {
        $this->view('signinglist');
    }

    /**
     * 合同签收 - 已签收合同列表
     */
    function c_signedList()
    {
        $this->view('signedlist');
    }

    /**
     * 合同签收 - 签收功能
     */
    function c_toSign()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加{file}
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
        $this->showDatadicts(array('outsourceType' => 'HTWB'), $obj['outsourceType']);
        $this->showDatadicts(array('payType' => 'HTFKFS'), $obj['payType']); //合同付款方式
        $this->showDatadicts(array('outsourcing' => 'HTWBFS'), $obj['outsourcing']); //合同外包方式

        $this->view('sign');
    }

    /**
     * 合同签收 - 签收功能
     */
    function c_sign()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->sign_d($object);
        if ($id) {
            msgRf('签收成功');
        } else {
            msgRf('签收失败');
        }
    }
    /******************* E 签收系列 *********************/

    /**
     * 关闭合同
     */
    function c_toClose()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        //获取并设置关闭合同权限
        $closeLimit = isset($_GET['closeLimit']) ? $_GET['closeLimit'] : null;
        $this->assign('closeLimit', $closeLimit);
        $this->view('close');
    }

    /**
     * 关闭方法
     */
    function c_close()
    {
        $object = $_POST[$this->objName];
        $closeLimit = $object['closeLimit'];
        unset($object['closeLimit']);
        //具备关闭合同权限的无需走审批流
        if ($closeLimit == "true") {
            $object['status'] = "3";
            $object['ExaStatus'] = "完成";
            $object['ExaDT'] = date("Y-m-d H:i:s");
        }
        $id = $this->service->edit_d($object);
        if ($id) {
            if ($closeLimit == "true") {
                msg('提交成功');
            } else {
                succ_show('controller/contract/outsourcing/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
            }
        } else {
            msg('提交失败');
        }
    }
}