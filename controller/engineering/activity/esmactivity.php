<?php

/**
 * @author Administrator
 * @Date 2011年12月12日 17:06:50
 * @version 1.0
 * @description:项目范围(oa_esm_project_activity)控制层
 */
class controller_engineering_activity_esmactivity extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmactivity";
        $this->objPath = "engineering_activity";
        parent::__construct();
    }

    /****************************** 列表部分 ******************************/

    /**
     * 跳转到项目范围列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        if (isset($_POST['parentId'])) {
            $parentId = $_POST['parentId'];
            unset($_POST['parentId']);
            $obj = $service->find(array('id' => $parentId), null, 'lft,rgt');

            $_POST['biglftNoEqu'] = $obj['lft'];
            $_POST['smallrgtNoEqu'] = $obj['rgt'];
        }

        $service->getParam($_POST); //设置前台获取的参数信息

        $rows = $service->page_d();
        if (!empty($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId'], 'parentId' => PARENT_ID);
            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['activityName'] = '项目合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;

        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 下拉表格获取任务信息
     */
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 日志选择任务
     */
    function c_selectActForLog()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $rows = $service->page_d();

        foreach ($rows as $key => $item) {
            if ($rows[$key]['parentId'] != -1) {
                $rows[$key]['activityNameView'] = $rows[$key]['parentName'] . "/" . $rows[$key]['activityName'];
            } else {
                $rows[$key]['activityNameView'] = $rows[$key]['activityName'];
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;

        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;

        echo util_jsonUtil::encode($arr);
    }

    /*
     * 跳转到Tab项目范围
     */
    function c_tabEsmactivity()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * 跳转到编辑列表
     */
    function c_toEditList()
    {
        $this->service->checkParent_d();
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list-edit');
    }

    /**
     * 跳转到查看Tab项目范围
     */
    function c_toViewList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('viewlist');
    }

    /**
     * 树表
     */
    function c_toTreeList()
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        $outsourcingName = $esmprojectDao->find(array("id"=>$_GET['projectId']),null,"outsourcingName");

        //在这里更新非整包A类项目进度
        $isACatWithFallOutsourcing = false;
        if($outsourcingName['outsourcingName'] != "整包"){
            $this->assign('isCategyAProject',$this->service->updateCategoryAProcess_d($_GET['projectId']) ? 1 : 0);
        }else{
            $isACatWithFallOutsourcing = $esmprojectDao->isCategoryAProject_d(null, $_GET['projectId']);// A类项目
            $this->assign('isCategyAProject', $isACatWithFallOutsourcing ? 1 : 0);
            if($isACatWithFallOutsourcing){
                $this->service->updateCategoryAProcess_d($_GET['projectId'],true);
            }
        }
//        $this->assign('isACatWithFallOutsourcing', ($isACatWithFallOutsourcing? "1" : ""));

        //项目id
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->display('treelist');
    }

    /**
     * 树表数据
     */
    function c_manageTreeJson()
    {
        $service = $this->service;
        //初始化根节点
        $parentId = PARENT_ID;

        $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
        if ($projectId) {
            $service->searchArr['projectId'] = $projectId;
        }
        $service->asc = false;

        //判断是否存在变更的记录
        $isChanging = $service->isChanging_d($projectId, false);
        $service->sort = 'c.lft';
        if ($isChanging) {
            //获取变更记录的跟节点
            $parentId = $service->getChangeRoot_d($isChanging);
            $service->searchArr['changeId'] = $isChanging;
            $arrs = $service->listBySqlId('select_change');
        } else {
            $arrs = $service->listBySqlId('treelist');
        }

        //加载列表数据
        $activityProcessArr = $service->getListProcess_d($projectId);

        // 根据项目的收入确认方式确认项目进度加载方式
        $projectDao = new model_engineering_project_esmproject();
        $projectInfo = $projectDao->get_d($projectId);

        switch ($projectInfo['incomeType']) {
            case 'SRQRFS-02' : // 开票
                $projectInfo = $projectDao->contractDeal($projectInfo);
                $projectProcess = $projectInfo['invoiceProcess'];
                break;
            case 'SRQRFS-03' : // 到款
                $projectInfo = $projectDao->contractDeal($projectInfo);
                $projectProcess = $projectInfo['incomeProcess'];
                break;
            case 'SRQRFS-01' : // 进度
            default :
                //加载列表数据
                $activityProcessArr = $service->getListProcess_d($projectId);
        }

        if (!empty($arrs)) {
            //除去_parentId
            foreach ($arrs as $key => $val) {
                // 根据公式计算完成量
                $workloadCount = (strtotime(min(day_date, $val['planEndDate'])) - strtotime($val['planBeginDate'])) / 86400 + 1;
                $arrs[$key]['workloadCount'] = $workloadCount;

                if ($val['_parentId'] == $parentId) {
                    unset($arrs[$key]['_parentId']);
                }
                //如果计划进度已经达到超过100，则按100算
                $arrs[$key]['planProcess'] = $arrs[$key]['planProcess'] > 100 ? '100.00' : $arrs[$key]['planProcess'];

                //如果存在变更申请单id,使用activityId作为Id
                $id = $isChanging ? $val['activityId'] : $val['id'];

                if(in_array($projectInfo['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) && isset($projectProcess)) {
                    $arrs[$key]['process'] = $arrs[$key]['countProcess'] = $projectProcess;
                    $arrs[$key]['waitConfirmProcess'] = '0.00';
                    $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $projectProcess, 2);
                } else {
                    //加载待确认进度、累计进度、计划进度
                    if ($val['rgt'] - $val['lft'] == 1) {
                        $arrs[$key]['waitConfirmProcess'] = isset($activityProcessArr[$id]) ? $activityProcessArr[$id]['thisActivityProcess'] : '0.00';
                        $arrs[$key]['countProcess'] = isset($activityProcessArr[$id]) ? bcadd($activityProcessArr[$id]['thisActivityProcess'], $val['process'], 2) : $val['process'];
                        $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $arrs[$key]['countProcess'], 2);
                    }
                }
            }
            //加载项目合计
            $service->sort = '';
            //特殊合计处理
            $objArr = $isChanging ? $service->countForChange_d($parentId) : $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $objArr[0]['id'] = 'noId';
                $objArr[0]['activityName'] = '项目合计';
            }
            $rows['footer'] = $objArr;
        }
        //数组设值
        $rows['rows'] = $arrs;
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 树表数据
     */
    function c_treeJson()
    {
        $service = $this->service;
        $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
        if ($projectId) {
            $service->searchArr['projectId'] = $projectId;
        }
        $service->sort = 'c.lft';
        $service->asc = false;
        $arrs = $service->listBySqlId('treelist');

        if (!empty($arrs)) {
            // 根据项目的收入确认方式确认项目进度加载方式
            $projectDao = new model_engineering_project_esmproject();
            $projectInfo = $projectDao->get_d($projectId);

            switch ($projectInfo['incomeType']) {
                case 'SRQRFS-02' : // 开票
                    $projectInfo = $projectDao->contractDeal($projectInfo);
                    $projectProcess = $projectInfo['ivnoiceProcess'];
                    break;
                case 'SRQRFS-03' : // 到款
                    $projectInfo = $projectDao->contractDeal($projectInfo);
                    $projectProcess = $projectInfo['incomeProcess'];
                    break;
                case 'SRQRFS-01' : // 进度
                default :
                    //加载列表数据
                    $activityProcessArr = $service->getListProcess_d($projectId);
            }

            //除去_parentId
            foreach ($arrs as $key => $val) {
                // 根据公式计算完成量
                $workloadCount = (strtotime(min(day_date, $val['planEndDate'])) - strtotime($val['planBeginDate'])) / 86400 + 1;
                $arrs[$key]['workloadCount'] = $workloadCount;

                if ($val['_parentId'] == -1) {
                    unset($arrs[$key]['_parentId']);
                }

                //如果计划进度已经达到超过100，则按100算
                if ($val['planProcess'] > 100) {
                    $arrs[$key]['planProcess'] = $val['planProcess'] = 100;
                }

                if(in_array($projectInfo['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) && isset($projectProcess)) {
                    $arrs[$key]['process'] = $arrs[$key]['countProcess'] = $projectProcess;
                    $arrs[$key]['waitConfirmProcess'] = '0.00';
                    $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $projectProcess, 2);
                } else {
                    //加载待确认进度、累计进度、计划进度
                    if ($val['rgt'] - $val['lft'] == 1) {
                        $arrs[$key]['waitConfirmProcess'] = isset($activityProcessArr[$val['id']]) ? $activityProcessArr[$val['id']]['thisActivityProcess'] : '0.00';
                        $arrs[$key]['countProcess'] = isset($activityProcessArr[$val['id']]) ? bcadd($activityProcessArr[$val['id']]['thisActivityProcess'], $val['process'], 2) : $val['process'];
                        $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $arrs[$key]['countProcess'], 2);
                    }
                }
            }

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $projectId);
            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $objArr[0]['id'] = 'noId';
                $objArr[0]['activityName'] = '项目合计';
            }
            $rows['footer'] = $objArr;
        }
        //数组设值
        $rows['rows'] = $arrs;
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 树表
     */
    function c_toTreeViewList()
    {
        $esmProDao = new model_engineering_project_esmproject();
        $outsourcingName = $esmProDao->find(array("id"=>$_GET['projectId']),null,"outsourcingName");

        $isACatWithFallOutsourcing = false;
        $esmprojectDao = new model_engineering_project_esmproject();
        //在这里更新非整包A类项目进度
        if($outsourcingName['outsourcingName'] != "整包"){
            $this->service->updateCategoryAProcess_d($_GET['projectId']);
        }else{
            $isACatWithFallOutsourcing = $esmprojectDao->isCategoryAProject_d(null, $_GET['projectId']);// A类项目
            if($isACatWithFallOutsourcing){// A类整包项目
                $this->service->updateCategoryAProcess_d($_GET['projectId'],true);
            }
        }

        //项目id
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('isACatWithFallOutsourcing', ($isACatWithFallOutsourcing? "1" : ""));
        $this->display('treeviewlist');
    }

    /******************************* 增删改查 *******************************/

    /**
     * 跳转到新增项目范围(oa_esm_project_activity)页面
     */
    function c_toAdd()
    {
        //根据项目Id，获取项目信息
        $esmproject = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assignFunc($esmproject);

        //通过传入的changeId判断是否是变更
        $changeId = isset($_GET['changeId']) ? $_GET['changeId'] : '';
        $parentId = $_GET['parentId'];
        $obj = $this->service->getParentObj_d($parentId, $changeId);

        //获取当前等级的剩余工作占比
        $thisWorkRate = $this->service->getWorkRateByParentId_d($esmproject['id'], $parentId, $changeId);
        $lastWorkRate = bcsub(100, $thisWorkRate, 2);
        $this->assign('workRate', $lastWorkRate);

        //工作量单位初始化
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'));

        if ($parentId == PARENT_ID) {
            $this->assign('parentId', $parentId);
            $this->assign('activityName', "项目");
            //日期获取
            $this->assign('planBeginDate', $esmproject['planBeginDate']);
            $this->assign('planEndDate', $esmproject['planEndDate']);
            $this->assign('days', $esmproject['expectedDuration']);
        } else {
            $this->assign('parentId', $obj['id']);
            $this->assign('activityName', $obj['activityName']);
            //日期获取
            $this->assign('planBeginDate', $obj['planBeginDate']);
            $this->assign('planEndDate', $obj['planEndDate']);
            $this->assign('days', $obj['days']);
        }
        //changId渲染
        $this->assign('changeId', $changeId);

        $this->view('add');
    }

    /**
     * 新增对象操作
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        if ($this->service->add_d($object)) {
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
     * 任务转移
     */
    function c_toMove()
    {
        //通过传入的changeId判断是否是变更
        $changeId = isset($_GET['changeId']) ? $_GET['changeId'] : '';
        $parentId = $_GET['parentId'];
        $obj = $this->service->getParentObj_d($parentId, $changeId);
        $this->assignFunc($obj);

        //获取当前等级的剩余工作占比
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $parentId, $changeId);
        $lastWorkRate = bcsub(100, $thisWorkRate, 2);
        $this->assign('workRate', $lastWorkRate);

        //工作量单位初始化
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit']);

        //父节点渲染
        $this->assign('parentId', $parentId);

        //changId渲染
        $this->assign('changeId', $changeId);

        $this->view('move');
    }

    /**
     * 任务转移
     */
    function c_move()
    {
        if ($this->service->move_d($_POST[$this->objName])) {
            msgRf('转移成功');
        } else {
            msgRf('转移失败');
        }
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->service->getUnderTreeIds_d($_GET['id']);
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->view('view');
        } else {
            $this->view('edit');
        }
    }

    /**
     * 跳转到编辑项目范围页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        //工作量单位初始化workloadUnit
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit'], true);
        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edit');
    }

    /**
     * 修改试用项目任务的页面
     */
    function c_toEditTrial()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edittrial');
    }

    /**
     * 修改试用项目任务的页面
     */
    function c_toEditTrialChange()
    {
        $this->permCheck(); //安全校验
        $this->service->getParam(array('id' => $_GET['id']));
        $obj = $this->service->list_d('select_change');
        foreach ($obj[0] as $key => $val) {
            $this->assign($key, $val);
        }
        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edittrial');
    }

    /**
     * 修改对象
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
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
     * 修正任务进度
     */
    function c_toEditWorkloadDone()
    {
        $this->permCheck();//安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        //否则更新任务进度
        $workloadCount = (strtotime(min(day_date, $obj['planEndDate'])) - strtotime($obj['planBeginDate'])) / 86400 + 1;
        $this->assign('workloadCount', $workloadCount);
        $this->view('editworkloaddone');
    }

    /**
     * 修正任务进度
     */
    function c_editWorkloadDone()
    {
        if ($this->service->editWorkloadDone_d($_POST[$this->objName])) {
            msgRf('编辑成功！');
        } else {
            msgRf('编辑失败');
        }
    }

    /**
     * 跳转到查看项目范围页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 编辑节点 (有下级任务)
     */
    function c_toEditNode()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        //获取当前等级的剩余工作占比
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $obj['parentId']);
        $lastWorkRate = bcadd(bcsub(100, $thisWorkRate, 2), $obj['workRate'], 2);
        $this->assign('canUseWorkRate', $lastWorkRate);

        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('node');
    }

    /**
     * 跳转到查看项目范围页面
     */
    function c_toViewNode()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('node-view');
    }

    /************************ 其他部分 *******************************/

    /**
     * 重写唯一验证
     */
    function c_checkRepeat()
    {
        $checkId = "";
        $service = $this->service;
        if (isset ($_REQUEST ['id'])) {
            $checkId = $_REQUEST ['id'];
            unset ($_REQUEST ['id']);
        }
        if (!isset($_POST['validateError'])) {
            $service->getParam($_REQUEST);
            $isRepeat = $service->isRepeat($service->searchArr, $checkId);
            echo $isRepeat;
        } else {
            //新验证组件
            $validateId = $_POST['validateId'];
            $validateValue = $_POST['validateValue'];
            $service->searchArr = array(
                $validateId . "Eq" => $validateValue
            );
            $service->searchArr['projectId'] = $_GET['projectId'];

            $isRepeat = $service->isRepeat($service->searchArr, $checkId);
            $result = array(
                'jsonValidateReturn' => array($_POST['validateId'], $_POST['validateError'])
            );
            if ($isRepeat) {
                $result['jsonValidateReturn'][2] = "false";
            } else {
                $result['jsonValidateReturn'][2] = "true";
            }
            echo util_jsonUtil::encode($result);
        }
    }

    /**
     * 检测是否存在根节点，不存在则添加一条
     */
    function c_checkParent()
    {
        echo $this->service->checkParent_d() ? 1 : 0;
    }

    /**
     * 构造树
     */
    function c_getChildren()
    {
        $service = $this->service;

        $sqlKey = isset($_POST['rtParentType']) ? 'select_treelistRtBoolean' : 'select_treelist';

        if (empty($_POST['id'])) {
            $rows = array(array('id' => PARENT_ID, 'code' => 'root', 'name' => '项目', 'isParent' => 'true'));
        } else {
            $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
            $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
            $service->searchArr['parentId'] = $parentId;
            if ($projectId) {
                $service->searchArr['projectId'] = $projectId;
            }
            $service->asc = false;
            $rows = $service->listBySqlId($sqlKey);
        }
        echo util_jsonUtil::encode($rows);
    }


    /**
     * 实时计算任务进度
     */
    function c_calTaskProcess()
    {
        //计算
        $rs = $this->service->calTaskProcess_d($_POST['id'], $_POST['workload'], $_POST['worklogId']);
        echo $rs ? util_jsonUtil::encode($rs) : 0;
    }

    //add chenrf 20130604
    /**
     * 计算工作占比总和
     */
    function c_workRateCount()
    {
        echo $this->service->workRateCount($_GET['projectId']);
    }

    /**
     * 计算工作占比总和 - 任务
     */
    function c_workRateCountNew()
    {
        echo util_jsonUtil::encode($this->service->workRateCountNew($_GET['projectId'], $_GET['parentId'], null));
    }
    /********************* 变更处理 ************************/
    /**
     *  变更查看
     */
    function c_toViewChange()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getChange_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 变更编辑
     */
    function c_toEditChange()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getChange_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //工作量单位初始化workloadUnit
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit'], true);

        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', 0);
        }
        $this->view('edit');
    }

    /**
     * 编辑节点 (有下级任务)
     */
    function c_toEditNodeChange()
    {
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);

        //获取当前等级的剩余工作占比
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $obj['parentId']);
        $lastWorkRate = bcadd(bcsub(100, $thisWorkRate, 2), $obj['workRate'], 2);
        $this->assign('canUseWorkRate', $lastWorkRate);

        //如果没有orgId,则设置-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', 0);
        }
        $this->view('node');
    }

    /**
     * 跳转到查看项目范围页面
     */
    function c_toViewNodeChange()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('node-view');
    }

    /**
     * ajax方式批量删除对象（应该把成功标志跟消息返回）
     */
    function c_ajaxdeletes()
    {
        echo $this->service->deletes_d($_POST ['id'], $_POST ['changeId'], $_POST ['projectId']) ? 1 : 0;
    }

    /**
     * 暂停任务
     */
    function c_stop()
    {
        echo $this->service->stop_d($_POST ['id']) ? 1 : 0;
    }

    /**
     * 恢复任务
     */
    function c_restart()
    {
        echo $this->service->restart_d($_POST ['id']) ? 1 : 0;
    }
}