<?php

/**
 * @author Show
 * @Date 2012年10月20日 15:27:58
 * @version 1.0
 * @description:盖章申请表(oa_sale_stampapply)控制层
 */
class controller_contract_stamp_stampapply extends controller_base_action
{
    private $bindId = "";
    function __construct()
    {
        $this->objName = "stampapply";
        $this->objPath = "contract_stamp";
        $this->bindId = "9199f77f-7b2a-4cc9-adae-dd44799573d9";
        parent :: __construct();
    }

    /**
     * 跳转到盖章申请表(oa_sale_stampapply)列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     *  我的盖章申请
     */
    function c_myList()
    {
        $this->view('listmy');
    }

    /**
     * 我的盖章申请json
     */
    function c_myPageJson()
    {
        $service = $this->service;

        $_POST['applyUserId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST);

        //$service->asc = false;
        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }
    /******************** 增删改查 ******************/

    /**
     * 跳转到新增盖章申请表(oa_sale_stampapply)页面
     */
    function c_toAdd()
    {
        $initArr = array(
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'deptId' => $_SESSION['DEPT_ID'],
            'deptName' => $_SESSION['DEPT_NAME'],
            'applyDate' => day_date,
            'attn' => $_SESSION['USERNAME'],
            'attnId' => $_SESSION['USER_ID'],
            'attnDept' => $_SESSION['DEPT_NAME'],
            'attnDeptId' => $_SESSION['DEPT_ID']

        );
        $this->assignFunc($initArr);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign("docUrl",$docUrl);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->showDatadicts(array('contractType' => 'HTGZYD'), null, true);

        $this->view('add', true);
    }

    //重写add
    function c_add()
    {
        $this->checkSubmit(); //检验是否重复提交
        $service = $this->service;
        $object = $_POST[$this->objName];

        // 获取公司信息
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT'); //签约主体（公司名）
        $object['stampCompanyId'] = $object['businessBelongId'];
        $object['stampCompany'] = $businessInfo[$object['businessBelongId']];

        //非合同类盖章，验证是否需要审批并设置相应的信息
        if ($object['contractType'] != 'HTGZYD-04') {
            $object = $service->checkNeedAudit($object);
        } else {
            $object['isNeedAudit'] = 1;
        }

        $id = $service->add_d($object);
        if ($id) {
            //提交时才做判断
            if (isset($_GET['act'])) {
                if ($object['isNeedAudit'] == 0) {
                    $service->sendEmail($object, $id);
                    $service->dealAfterSubmit_d($id); //添加到盖章合同中
                    msg('提交成功');
                } else {

                    // 如果是合同盖章申请，则特殊审批流程，否则走通用流程
                    if ($object['contractType'] == 'HTGZYD-04') {

                        // 如果合同已经审核，走正常流程
                        if ($service->contractIsAudited_d($object['contractId'])) {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=10');
                        } else {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=1');
                        }

                    } else { //非合同类盖章，存在需要审批的使用事项时，需要走【盖章申请审批】
                        succ_show('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId']);
                    }
                }
            } else {
                msg('保存成功');
            }
        } else {
            if (isset($_GET['act'])) {
                msg('提交失败');
            } else {
                msg('保存失败');
            }
        }
    }

    /**
     * 跳转到编辑盖章申请表(oa_sale_stampapply)页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), $obj['stampExecution'], true);
        $this->showDatadicts(array('categoryId' => 'GZLB'), $obj['categoryId'], false);
        $this->assign('contractTypeCN', $this->getDataNameByCode($obj['contractType']));

        // 读取是否双面印刷字段内容
        $df_select = $y_select = $n_select = '';
        if ($obj['printDoubleSide'] == 'y') {
            $y_select = 'selected';
        } else if ($obj['printDoubleSide'] == 'n') {
            $n_select = 'selected';
        } else {
            $df_select = 'selected';
        }
        $option_str = "<option value='' " . $df_select . ">...请选择...</option>" .
            "<option value='y' " . $y_select . ">是</option>" .
            "<option value='n' " . $n_select . ">否</option>";
        $this->assign('printDoubleSide', $option_str);

        //文件类型为鼎利合同时
        if ($obj['contractType'] == "HTGZYD-04") {
            //设置业务经办人为当前登录人
            $this->assign('attnId', $_SESSION['USER_ID']);
            $this->assign('attn', $_SESSION['USERNAME']);
            $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
            $this->assign('attnDept', $_SESSION['DEPT_NAME']);
            //获取合同归属公司
            $contractDao = new model_contract_contract_contract();
            $rs = $contractDao->find(array('id' => $obj['contractId']), null, 'businessBelong');
            if (!empty($rs)) {
                $this->assign('businessBelong', $rs['businessBelong']);
            }
        }

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign("docUrl",$docUrl);
        $this->view('edit');
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = true)
    {
        $service = $this->service;
        $object = $_POST[$this->objName];

        // 获取公司信息
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT'); //签约主体（公司名）
        $object['stampCompanyId'] = $object['businessBelongId'];
        $object['stampCompany'] = $businessInfo[$object['businessBelongId']];

        //非合同类盖章，验证是否需要审批并设置相应的信息
        if ($object['contractType'] != 'HTGZYD-04') {
            $object = $service->checkNeedAudit($object);
        } else {
            $object['isNeedAudit'] = 1;
        }

        $rs = $service->edit_d($object);
        if ($rs) {
            $id = $object['id'];
            //提交时才做判断
            if (isset($_GET['act'])) {
                if ($object['isNeedAudit'] == 0) {
                    $service->sendEmail($object, $id);
                    $service->dealAfterSubmit_d($id); //添加到盖章合同中
                    msg('提交成功');
                } else {

                    // 如果是合同盖章申请，则特殊审批流程，否则走通用流程
                    if ($object['contractType'] == 'HTGZYD-04') {

                        // 如果合同已经审核，走正常流程
                        if ($service->contractIsAudited_d($object['contractId'])) {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=10');
                        } else {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=1');
                        }

                    } else { //非合同类盖章，存在需要审批的使用事项时，需要走【盖章申请审批】
                        succ_show('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId']);
                    }
                }
            } else {
                msg('保存成功');
            }
        } else {
            if (isset($_GET['act'])) {
                msg('提交失败');
            } else {
                msg('保存失败');
            }
        }
    }

    /**
     * 跳转到查看盖章申请表(oa_sale_stampapply)页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "是" : "否"; //是否双面印刷
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['hideBtn'])) {
            $this->assign('hideBtn', 1);
        } else {
            $this->assign('hideBtn', 0);
        }
        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('stampExecution', $this->getDataNameByCode($obj['stampExecution']));
        $this->assign('categoryId', $this->getDataNameByCode($obj['categoryId']));
        $this->assign('status', $this->service->rtStampType_d($obj['status']));
        $this->view('view');
    }

    /**
     * 跳转到审批盖章申请表
     */
    function c_toAudit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "是" : "否"; //是否双面印刷
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('stampExecution', $this->getDataNameByCode($obj['stampExecution']));
        $this->assign('categoryId', $this->getDataNameByCode($obj['categoryId']));
        $this->assign('status', $this->service->rtStampType_d($obj['status']));
        $this->view('audit');
    }

    /**
     * 审批完成后跳转
     */
    function c_dealAfterAudit()
    {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    //修改审批状态
    function c_changeStatus()
    {
        return $this->service->update(array('id' => $_POST['id']), array('ExaStatus' => '完成'));
    }

    //发送邮件
    function c_toSend()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "是" : "否"; //是否双面印刷
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), $obj['stampExecution'], true);
        $this->assign('contractTypeCN', $this->getDataNameByCode($obj['contractType']));
        $this->view('send');
    }

    //ajax提交盖章
    function c_ajaxStamp()
    {
        $service = $this->service;
        $id = $_POST['id'];
        //更新盖章申请审批状态
        $obj = array(
            'id' => $id,
            'ExaStatus' => '完成',
            'ExaDT' => day_date
        );
        if ($service->updateById($obj)) {
            //在盖章列表添加信息
            $service->dealAfterSubmit_d($id);
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 判断合同是否完成审批 - 销售合同专用
     */
    function c_contractIsAudited()
    {
        echo $this->service->contractIsAudited_d($_POST['contractId']);
    }
}