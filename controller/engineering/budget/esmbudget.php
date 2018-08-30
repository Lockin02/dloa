<?php

/**
 * @author Show
 * @Date 2011年12月22日 星期四 9:49:51
 * @version 1.0
 * @description:项目费用预算(oa_esm_project_budget)控制层
 */
class controller_engineering_budget_esmbudget extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmbudget";
        $this->objPath = "engineering_budget";
        parent::__construct();
    }

    /************************* 列表部分 ****************************/
    /**
     * 跳转到项目费用预算
     */
    function c_page()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->sort = "c.budgetType,c.budgetName,c.isImport";

        //判断是否存在变更的记录
        $changeId = $service->isChanging_d($_POST['projectId'], false);
        if ($changeId) {
            $service->searchArr['changeId'] = $changeId;
            $rows = $service->page_d('select_change');
        } else {
            $rows = $service->page_d();
        }

        // 权限以及合计处理
        if (!empty($rows)) {
            //加入权限
            $rows = $this->gridDateFilter($rows);
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //加载项目合计
            $objArr = $changeId ? $service->listBySqlId('count_change') : $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['parentName'] = '项 目 合 计';
            $rsArr['id'] = 'noId';

            // 查询项目预决算
            $esmProjectDao = new model_engineering_project_esmproject();
            $project = $esmProjectDao->get_d($_REQUEST['projectId']);
            $project = $esmProjectDao->feeDeal($project);

            // 如果设备金额大于0，则加入
            if ($project['budgetEqu'] > 0 && !$_POST['budgetType']) {
                $rsArr['amount'] = bcadd($rsArr['amount'], $project['budgetEqu'], 2);
                $rows[] = array('id' => 'noId', 'parentName' => '设备预算',  'amount' => $project['budgetEqu']);
            }

            // 如果PK预算大于0，则加入
            if ($project['budgetPK'] > 0 && !$_POST['budgetType']) {
                $rsArr['amount'] = bcadd($rsArr['amount'], $project['budgetPK'], 2);
                $rows[] = array('id' => 'noId', 'parentName' => 'PK预算',  'amount' => $project['budgetPK']);
            }

            // 合计处理
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
     * 跳转到查看Tab项目费用预算
     */
    function c_toViewList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('act', isset($_GET['act']) ? 'searchJson2' : 'searchJson');

        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($_GET['projectId']);
        $this->assign('projectCode', $esmprojectObj['projectCode']);

        $this->view('viewlist');
    }

    /**
     * PK 预决算加载
     * @param $rows
     * @param $rsArr
     * @param $budgetType
     * @return array
     */
    function setOtherFee($rows, $rsArr, $budgetType)
    {
        // PK费用信息获取
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($_POST['projectId']);

        // 记载机票费用
        if ($esmprojectObj['feeFlights'] > 0 && ($budgetType == 'budgetFlights' || $budgetType == '')) {
            if (!is_array($rows)) $rows = array(); // 如果传入的row不是数组，则阻转为数组
            !empty($rsArr) ? array_pop($rows) : $rsArr = array('id' => 'noId', 'parentName' => '项目合计'); // 如果有合并列则弹出row最后一行,否则初始化rsArr
            // 加载PK项目信息
            array_push($rows, array(
                'id' => 'budgetFlights',
                'parentName' => '机票费用', 'budgetType' => 'budgetFlights',
                'budgetName' => '机票费用',
                'remark' => '此费用取自机票系统',
                'amount' => '0.00',
                'actFee' => $esmprojectObj['feeFlights'],
                'feeProcess' => '0.00'
            ));
            // PK预决算进入合计部分
            $rsArr['actFee'] = bcadd($esmprojectObj['feeFlights'], $rsArr['actFee'], 2);
            $rows[] = $rsArr;
        }

        // 如果有查询出的数组或者存在PK费用
        $pkProjectRows = $budgetType == 'budgetTrial' || $budgetType == '' ? $esmprojectDao->getPKInfo_d(null, $esmprojectObj) : array();
        if (!empty($pkProjectRows)) {
            if (!is_array($rows)) $rows = array(); // 如果传入的row不是数组，则阻转为数组
            !empty($rsArr) ? array_pop($rows) : $rsArr = array('id' => 'noId', 'parentName' => '项目合计'); // 如果有合并列则弹出row最后一行,否则初始化rsArr
            foreach ($pkProjectRows as $v) {
                // 加载PK项目信息
                array_push($rows, array(
                    'id' => 'budgetTrial',
                    'parentName' => '试用项目', 'budgetType' => 'budgetTrial',
                    'budgetName' => $v['projectCode'], 'projectId' => $v['id'],
                    'remark' => $v['projectName'],
                    'amount' => $v['budgetAll'], 'actFee' => $v['feeAllCount'],
                    'feeProcess' => bcmul(bcdiv($v['budgetAll'], $v['feeAllCount'], 4), 100, 2),
                ));
                // PK预决算进入合计部分
                $rsArr['amount'] = bcadd($v['budgetAll'], $rsArr['amount'], 2);
                $rsArr['actFee'] = bcadd($v['feeAllCount'], $rsArr['actFee'], 2);
            }
            $rsArr['feeProcess'] = bcmul(bcdiv($rsArr['actFee'], $rsArr['amount'], 4), 100, 2);
            $rows[] = $rsArr;
        }
        return $rows;
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d();
        // 数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 列表数据权限过滤
     * @param $rows
     * @return mixed
     */
    function gridDateFilter($rows)
    {
        // 人力预算单价权限点 2013-07-08
        $otherDataDao = new model_common_otherdatas();
        $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if (!$esmLimitArr['人力预算单价']) {
            foreach ($rows as $key => $val) {
                if ($val['budgetType'] == 'budgetPerson' && empty($val['customPrice'])) {
                    $rows[$key]['price'] = '******';
                }
            }
        }
        return $rows;
    }

    /**
     * 管理tab页
     */
    function c_manageTab()
    {
        $this->assignFunc($_GET);
        $this->view('manageTab');
    }

    /**
     * 查看tab页
     */
    function c_viewTab()
    {
        $this->assignFunc($_GET);
        $this->view('viewTab');
    }

    /************************* 业务功能处理 *************************/

    /**
     * 跳转到新增项目费用预算页面
     */
    function c_toAdd()
    {
        // 项目部分渲染
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('add', true);
    }

    /**
     * 项目预算批量新增
     */
    function c_toBatchAdd()
    {
        // 项目部分渲染
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        // 模版渲染
        $customtemplateDao = new model_finance_expense_customtemplate();
        $customtemplateArr = $customtemplateDao->getTemplate_d();
        $this->assignFunc($customtemplateArr);

        $this->view('addbatch', true);
    }

    /**
     * 批量新增
     */
    function c_addBatch()
    {
        $this->checkSubmit(); // 重复提交
        $object = $_POST[$this->objName];
        if ($this->service->addBatch_d($object)) {
            //判断此修改是否属于变更
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                msgRf('保存成功，请在“变更记录”栏目提交审批');
            } else {
                msgRf('保存成功');
            }
        } else {
            msgRf('保存失败');
        }
    }

    /**
     * 编辑
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('orgId', '-1');
        if ($obj['budgetType'] == 'budgetPerson') {
            //人力预算单价权限点 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['人力预算单价']) {
                $this->assign('priceView', '******');
            } else {
                $this->assign('priceView', $obj['price']);
            }
            $this->view('edit-person');
        } else {
            $this->view('edit');
        }
    }

    /**
     * 修改对象
     * @param bool $isEditInfo
     * @throws Exception
     */
    function c_edit($isEditInfo = false)
    {
        $this->checkSubmit(); //重复提交
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            //判断此修改是否属于变更
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                msg('保存成功，请在“变更记录”栏目提交审批');
            } else {
                msg('编辑成功！');
            }
        }
    }

    /**
     * 跳转到查看
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        if ($obj['budgetType'] == 'budgetPerson' && empty($obj['customPrice'])) {
            //人力预算单价权限点 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['人力预算单价']) {
                $this->assign('price', '******');
            }

            $this->view('view-person');
        } else {
            $this->view('view');
        }
    }

    /**
     * 批量新增现场预算
     */
    function c_toAddField()
    {
        //模版渲染
        $customtemplateDao = new model_finance_expense_customtemplate();
        $customtemplateArr = $customtemplateDao->getTemplate_d();
        if ($customtemplateArr) {
            $this->assignFunc($customtemplateArr);

            //项目部分渲染
            $rs = $this->service->getProjectInfo_d($_GET['projectId']);
            $this->assignFunc($rs);
            $this->assign('projectId', $_GET['projectId']);

            $this->view('addfield');
        } else {
            $this->assign('userName', $_SESSION['USERNAME']);
            $this->assign('userId', $_SESSION['USER_ID']);
            $this->view('createtemplate');
        }
    }

    /**
     * 批量新增-人力预算
     */
    function c_toAddPerson()
    {
        //项目部分渲染
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addperson');
    }

    /**
     * 批量新增-外包预算
     */
    function c_toAddOutsourcing()
    {
        //项目部分渲染
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addoutsourcing');
    }

    /**
     * 批量新增-其他预算
     */
    function c_toAddOther()
    {
        //项目部分渲染
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addother');
    }

    /********************* 变更处理 ************************/
    /**
     *  变更查看
     */
    function c_toViewChange()
    {
        $this->permCheck(); // 安全校验
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);

        if ($obj['budgetType'] == 'budgetPerson' && empty($obj['customPrice'])) {
            //人力预算单价权限点 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['人力预算单价']) {
                $this->assign('price', '******');
            }
            $this->view('view-person');
        } else {
            $this->view('view');
        }
    }

    /**
     * 变更编辑
     */
    function c_toEditChange()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);
        if ($obj['budgetType'] == 'budgetPerson') {
            //人力预算单价权限点 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['人力预算单价']) {
                $this->assign('priceView', '******');
            } else {
                $this->assign('priceView', $obj['price']);
            }
            $this->view('edit-person');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ajax方式批量删除
     */
    function c_ajaxdeletes()
    {
        try {
            $this->service->deletes_d($_POST['id'], $_POST['projectId'], $_POST['changeId']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 查询费用明细
     */
    function c_toFeeDetail()
    {
        $this->assignFunc($_GET);

        // 项目id转义
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_GET['projectId']);
        $this->assign('projectCode', $project['projectCode']);
        $this->assign('contractCode', $project['contractType'] == 'GCXMYD-01' ? $project['contractCode'] : '');

        $this->view('feeDetail');
    }

    /**
     * 查询费用明细
     */
    function c_feeDetail()
    {
        echo util_jsonUtil::encode($this->service->feeDetail_d($_POST['projectId'],
            util_jsonUtil::iconvUTF2GB($_POST['budgetName']), $_POST['budgetType']));
    }

    /**
     * 项目预算获取 - 包括所有类型的预算(通过传入项目id)
     */
    function c_getAllBudgetDetail()
    {
        echo util_jsonUtil::encode($this->service->getAllBudgetDetail_d($_POST['projectId']));
    }

    /**
     * 项目费用预算列表
     */
    function c_searchJson()
    {
        // 数据缓存 - 缓存查询出来的决算数据
        $dataCache = array();

        // 最后输出的数据
        $data = array();

        // 这里取出项目 - 主要是获取某些固化在项目表中的成本数据
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_POST['projectId']);

        // 获取预算
        $budgetData = $this->service->getBudgetData_d($_POST);

        // 预算切分
        $budgetCache = $this->service->budgetSplit_d($budgetData);

        // 获取决算
        $feeData = $this->service->getFee_d($_POST['projectId']);

        // 决算切分
        $feeCache = $this->service->feeSplit_d($feeData);

        // 人力成本
        $dataCache[0] = $this->service->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

        // 报销支付成本
        $dataCache[1] = $this->service->getFieldFee_d($budgetCache[1], $feeCache[1], $project);
//        echo "<pre>";print_r($dataCache);exit();

        // 设备成本
        $dataCache[2] = $this->service->getEquFee_d($budgetCache[2], $feeCache[2], $project);

        // 外包成本
        $dataCache[3] = $this->service->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

        // 其他成本
        $dataCache[4] = $this->service->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

        // 如果查询到数据，则塞入数据数据中
        if (!empty($dataCache)) {
            $budgetAll = 0;
            $feeAll = 0;
            foreach ($dataCache as $v) {
                foreach ($v as $vi) {
                    $data[] = $vi;
                    if ($vi['id'] == 'noId') {
                        $budgetAll = bcadd($budgetAll, $vi['amount'], 2);
                        $feeAll = bcadd($feeAll, $vi['actFee'], 2);
                    }
                }
            }
            // 费用列信息生成
            array_push($data, $this->service->getCountRow_d('noId', '项目合计', $budgetAll, $feeAll));
        }

        echo util_jsonUtil::encode($data);
    }

    /**
     * 项目费用预算列表
     */
    function c_searchJson2()
    {
        //初始化项目合计的待审核预算/决算
        $amountWait = 0;
        $actFeeWait = 0;

        $mulRows = array(); // 现场、外包、其他，不包含人力
        $personRows = array(); // 人力决算
        $personBudgetCount = 0; // 人力预算项数量 - 不包含补贴项

        // 配置中的补贴名称
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        //传入值不是设备时,获取本业务中预算信息
        if ($_POST['budgetType'] != 'budgetDevice') {
            $rows = $this->service->getBudgetData_d($_POST);
            foreach ($rows as $val) {
                //累加导入的预决算信息
                if ($val['isImport'] == 1) {
                    $amountWait = bcadd($val['amountWait'], $amountWait, 2);
                    $actFeeWait = bcadd($val['actFeeWait'], $actFeeWait, 2);
                }

                // 决算分离，人力单独拆分处理
                if ($val['budgetType'] == 'budgetPerson') {
                    if (!in_array($val['budgetName'], $subsidyArr)) {
                        $personBudgetCount++;
                    } else {
                        $personRows[] = $val;
                    }
                } else {
                    $mulRows[] = $val;
                }
            }
        }
        $rowArr = array(); //存储筛选后的最终数组
        $rowSum = array('parentName' => '项 目 合 计', 'id' => 'noId', 'actFee' => 0, 'amount' => 0,
            'amountWait' => $amountWait, 'actFeeWait' => $actFeeWait, 'feeProcess' => 0,
            'budgetType' => '');
        //存储项目合计的数组
        //载入现场费用
        $feeArr = $this->service->getFee_d($_POST['projectId']);

        //载入现场预算，外包预算，其他预算，并计算暂时项目总计和获取人力预算个数
        if (isset($mulRows) || isset($feeArr)) {
            $result = $this->service->getNormalData_d($rowArr, $mulRows, $rowSum, $feeArr, $_POST);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        //载入人力预算
        if ($_POST['budgetType'] == 'budgetPerson' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetPerson_d($rowArr, $rowSum, $_POST, $personBudgetCount, $feeArr, $personRows);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        //载入设备预算
        if ($_POST['budgetType'] == 'budgetDevice' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetDevice_d($rowArr, $rowSum, $_POST);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        // 机票费用
        $esmprojectObj = array();
        if ($_POST['budgetType'] == 'budgetFlights' || $_POST['budgetType'] == '') {
            // 这里取出项目
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmprojectObj = $esmprojectDao->get_d($_POST['projectId']);
            if ($esmprojectObj['feeFlights'] > 0 || $esmprojectObj['feeFlightsShare'] > 0) {
                $feeFlightsAll = bcadd($esmprojectObj['feeFlights'], $esmprojectObj['feeFlightsShare'], 2);
                // 费用列信息生成
                array_push($rowArr, array(
                    'id' => 'budgetFlights', 'budgetType' => 'budgetFlights', 'parentName' => '机票费用', 'detCount' => '',
                    'budgetName' => '机票费用',
                    'remark' => '机票系统（' . $esmprojectObj['feeFlights'] .
                        '），分摊系统（' . $esmprojectObj['feeFlightsShare'] . '）',
                    'amount' => '0.00', 'actFee' => $feeFlightsAll,
                    'feeProcess' => '0.00'
                ));
                // 把金额加载到列表合计中
                $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeFlightsAll, 2);
            }
        }

        // 载入试用项目预算
        if ($_POST['budgetType'] == 'budgetTrial' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetTrial_d($rowArr, $rowSum, $_POST, $esmprojectObj);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }
        // 项目合计
        if ($rowArr) {
            $rowSum['feeProcess'] = bcmul(bcdiv($rowSum['actFee'], $rowSum['amount'], 4), 100, 2);
            array_push($rowArr, $rowSum);
            echo util_jsonUtil::encode($rowArr);
        }
    }

    /**
     * 导出项目费用预算列表
     */
    function c_exportExcel()
    {
        // 数据缓存 - 缓存查询出来的决算数据
        $dataCache = array();

        // 最后输出的数据
        $data = array();

        // 这里取出项目 - 主要是获取某些固化在项目表中的成本数据
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_GET['projectId']);

        // 获取预算
        $budgetData = $this->service->getBudgetData_d($_GET);

        // 预算切分
        $budgetCache = $this->service->budgetSplit_d($budgetData);

        // 获取决算
        $feeData = $this->service->getFee_d($_GET['projectId']);

        // 决算切分
        $feeCache = $this->service->feeSplit_d($feeData);

        // 人力成本
        $dataCache[0] = $this->service->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

        // 报销支付成本
        $dataCache[1] = $this->service->getFieldFee_d($budgetCache[1], $feeCache[1], $project);

        // 设备成本
        $dataCache[2] = $this->service->getEquFee_d($budgetCache[2], $feeCache[2], $project);

        // 外包成本
        $dataCache[3] = $this->service->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

        // 其他成本
        $dataCache[4] = $this->service->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

        // 如果查询到数据，则塞入数据数据中
        if (!empty($dataCache)) {
            foreach ($dataCache as $v) {
                foreach ($v as $vi) {
                    if ($vi['id'] != 'noId') {
                        $data[] = $vi;
                    }
                }
            }

            return model_engineering_util_esmexcelutil::exportBudget($data);
        }
    }

    /**
     * 导出项目实时列表所有项目费用预算列表
     */
    function c_exportAllExcel()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            unset($_SESSION['engineering_project_esmproject_listSql']);
            $datas = $this->service->getExcelData_d($sql);
            if (!empty($datas)) {
                return model_engineering_util_esmexcelutil::exportBudgetAll($datas);
            } else {
                msg("暂无数据");
            }
        } else {
            msg("请刷新列表重试");
        }
    }

    /**
     * 导出项目实时列表所有项目费用预算列表(CSV)
     */
    function c_exportAllExcelCSV()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            unset($_SESSION['engineering_project_esmproject_listSql']);
            $datas = $this->service->getExcelData_d($sql);
            if (!empty($datas)) {
                $colArr = array(
                    'projectCode' => '项目编号',
                    'statusName' => '状态',
                    'parentName' => '费用大类',
                    'budgetName' => '费用小类',
                    'amount' => '预算',
                    'actFee' => '决算',
                    'amountWait' => '待审核预算',
                    'actFeeWait' => '待审核决算',
                    'feeProcess' => '费用进度',
                    'remark' => '备注说明'
                );
                // 数据处理
                foreach ($datas as $k => $v) {
                    $datas[$k]['parentName'] = $v['projectCode'];
                    $datas[$k]['statusName'] = $v['statusName'];
                    $datas[$k]['parentName'] = $v['parentName'];
                    $datas[$k]['budgetName'] = $v['budgetName'];
                    $datas[$k]['amount'] = empty($v['amount']) && $v['amount'] != '0' ? '' : number_format($v['amount'], 2);
                    $datas[$k]['actFee'] = empty($v['actFee']) && $v['actFee'] != '0' ? '' : number_format($v['actFee'], 2);
                    $datas[$k]['amountWait'] = empty($v['amountWait']) && $v['amountWait'] != '0' ? '' : number_format($v['amountWait'], 2);
                    $datas[$k]['actFeeWait'] = empty($v['actFeeWait']) && $v['actFeeWait'] != '0' ? '' : number_format($v['actFeeWait'], 2);
                    $datas[$k]['feeProcess'] = empty($v['feeProcess']) && $v['feeProcess'] != '0' ? '' : $v['feeProcess'] . '%';
                    if ($v['isImport'] == 1) {
                        if ($v['status'] == 0) {
                            $datas[$k]['remark'] = '后台导入数据，未审核';
                        } else {
                            $datas[$k]['remark'] = '后台导入数据，已审核';
                        }
                    }
                }
                return model_engineering_util_esmexcelutil::exportCSV($colArr, $datas, '项目预决算导出');
            } else {
                msg("暂无数据");
            }
        } else {
            msg("请刷新列表重试");
        }
    }

    /**
     * 项目预算详细
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view("detail-list");
    }

    /**
     * 项目预算详细信息
     */
    function c_searhDetailJson()
    {
        $condition = array();
        $condition['isImport'] = 0; //只获取不是导入的数据
        //截取数据
        if ($_POST['budgetType'] == 'budgetPerson') {
            $condition['projectId'] = $_POST['projectId'];
            $condition['parentName'] = $_POST['parentName'];
            $condition['budgetType'] = $_POST['budgetType'];
        } else {
            $condition['projectId'] = $_POST['projectId'];
            $condition['parentName'] = $_POST['parentName'];
            $condition['budgetName'] = $_POST['budgetName'];
        }

        $service = $this->service;
        $service->getParam($condition);
        $rows = $service->list_d('select_default');

        //加入权限
        $rows = $this->gridDateFilter($rows);

        if (!empty($rows)) {
            //加载项目合计
            $service->sort = "";
            $service->groupBy = 'c.projectId';
            $objArr = $service->listBySqlId('count_all');
            $rsArr = array();
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['parentName'] = '合 计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        echo util_jsonUtil::encode($rows);
    }
}