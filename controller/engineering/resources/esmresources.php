<?php

/**
 * @author Show
 * @Date 2011年12月10日 星期六 15:03:50
 * @version 1.0
 * @description:项目资源计划(oa_esm_project_resources)控制层
 */
class controller_engineering_resources_esmresources extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmresources";
        $this->objPath = "engineering_resources";
        parent::__construct();
    }

    /*
     * 跳转到项目资源计划(oa_esm_project_resources)
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
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d();
        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //加载本页小计
            $pageAmount = 0;
            //本页小计 - 包含预变更项
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '本页小计';
            $rows[] = $rsArr;

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '项目合计';
            $rsArr['id'] = 'noId';
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()+如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 获取分页数据转成Json
     */
    function c_managePageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);

        //判断是否存在变更的记录
        $isChanging = $service->isChanging_d($_POST['projectId'], false);
        if ($isChanging) {
            $rows = $service->page_d('select_change');
        } else {
            $rows = $service->page_d();
        }

        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //加载本页小计
            $pageAmount = 0;
            //本页小计 - 包含预变更项
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '本页小计';
            $rows[] = $rsArr;

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            if ($isChanging) {
                $objArr = $service->listBySqlId('count_change');
            } else {
                $objArr = $service->listBySqlId('count_all');
            }
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '项目合计';
            $rsArr['id'] = 'noId';
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
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

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

    /**
     * 跳转到查看Tab项目资源计划
     */
    function c_toViewList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('viewlist');
    }

    /*跳转到项目资源Tab*/
    function c_toProResourceTab()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('proresource-tab');
    }

    /**
     * 项目设备申请
     */
    function c_projectTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('proTab');
    }

    /**
     * 项目设备申请（查看）
     */
    function c_proViewTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('proViewTab');
    }

    /**
     * 跳转到查看项目设备预算(oa_esm_project_resources)
     */
    function c_viewlist()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-list');
    }

    /**
     * 列表查看页面 - 默认
     */
    function c_toViewPage()
    {
        //获取项目任务
        $activityArr = $this->service->getActivityArr_d($_GET['projectId']);
        $str = $this->service->initViewPage_d($activityArr);

        $this->assign('list', $str);
        $this->view('view-page');
    }

    /*******************导入导出部分**********************************/

    /**
     * 导入excel
     */
    function c_toEportExcelIn()
    {
        //项目部分渲染
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //获取部分渲染
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->display('excelin');
    }

    /**
     * 导入excel
     */
    function c_eportExcelIn()
    {
        $projectId = $_POST['projectId'];
        $activityId = $_POST['activityId'];

        $resultArr = $this->service->addExecelData_d($projectId, $activityId);

        $title = '项目导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }
    /*******************导入导出部分**********************************/


    /******************************* 功能部分 *********************************/

    /**
     * 跳转到Tab新增页面
     */
    function c_toAdd()
    {
        //项目部分渲染
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //获取部分渲染
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'));
        $this->view('add');
    }

    //项目预算批量新增
    function c_toBatchAdd()
    {
        //项目部分渲染
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //获取部分渲染
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
//		$this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);
        $this->assign('activityName', $activityRs['activityName']);

        $this->view('addbatch');
    }

    /**
     * 复制资源计划 批量新增方法
     */
    function c_addBatch()
    {
        $rs = $this->service->batchAdd_d($_POST[$this->objName]);
        if ($rs) {
            //判断此修改是否属于变更
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($rs[0]['projectId'])) {
                msg('保存成功，请在“变更记录”栏目提交审批');
            } else {
                msg('保存成功');
            }
        } else {
            msg('保存失败');
        }
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);

        if ($obj['planBeginDate'] == '0000-00-00') $obj['planBeginDate'] = null;
        if ($obj['planEndDate'] == '0000-00-00') $obj['planEndDate'] = null;
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $TypeOne = $this->getDataNameByCode($obj['resourceTypeCode']);
            $this->assign('resourceTypeCode', $TypeOne);
            $this->assign('resourceNature', $this->getDataNameByCode($obj['resourceNature']));
            $this->view('view');
        } else {
            $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'), $obj['resourceTypeCode']);
            $this->showDatadicts(array('resourceNature' => 'GCXMZYXZ'), $obj['resourceNature']);
            $this->view('edit');
        }
    }

    /**
     * 初始化对象
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'), $obj['resourceTypeCode']);
        $this->showDatadicts(array('resourceNature' => 'GCXMZYXZ'), $obj['resourceNature']);
        $this->assign('orgId', '-1');
        $this->view('edit');
    }

    /**
     * 修改对象
     * @throws Exception
     */
    function c_edit()
    {
        $this->checkSubmit(); //重复提交
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
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
     * 初始化对象
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $TypeOne = $this->getDataNameByCode($obj['resourceTypeCode']);
        $this->assign('resourceTypeCode', $TypeOne);
        $this->assign('resourceNature', $this->getDataNameByCode($obj['resourceNature']));
        $this->view('view');
    }

    /**
     * 复制资源计划
     */
    function c_toCopy()
    {
        $this->assign('ids', $_GET['ids']);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('copy');
    }

    /**
     * 复制资源计划 复制页面数据
     */
    function c_toCopylistJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr['ids'] = $_GET['ids'];
        $service->asc = false;
        $rows = $service->list_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 验证活动下时候有设备关联
     */
    function c_checkHasResourceInAct()
    {
        $rs = $this->service->find(array('activityId' => $_POST['activityId']), null, 'id');
        if (is_array($rs)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 获取分页数据转成Json
     */
    function c_viewListJson()
    {
        $service = $this->service;
        if (isset($_POST['activityId']) && $_POST['activityId'] == -1) {
            unset($_POST['activityId']);
        } else {
            $_POST['activityIds'] = $this->service->getUnderTreeIds_d($_POST['activityId'], $_POST['lft'], $_POST['rgt']);
            unset($_POST['activityId']);
            unset($_POST['lft']);
            unset($_POST['rgt']);
        }
        $service->getParam($_REQUEST);
        $rows = $service->pageBySqlId("select_viewlist");
        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //加载本页小计
            $pageAmount = 0;
            //本页小计 - 包含预变更项
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '本页小计';
            $rows[] = $rsArr;

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '项目合计';
            $rsArr['id'] = 'noId';
            $rows[] = $rsArr;
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /********************* begin 资源计划处理 ***********************/

    /**
     * 列表 - 用于资源过滤
     */
    function c_pageJsonEquDeal()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $service->searchArr['resourceNature'] = 'GCXMZYXZ-02';
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

    /**
     * 项目设备资源处理 - 详细处理页面
     */
    function c_dealEquResources()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('dealequresource');
    }

    /**
     * 项目处理页面
     */
    function c_resourcesTree()
    {
        if (isset($_POST['id'])) {
            $this->service->getParam($_REQUEST);
            $this->service->groupBy = 'c.resourceCode';
            $this->service->sort = 'c.resourceCode';
            $this->service->asc = false;
            $rs = $this->service->list_d('select_tree');
        } else {
            $rs = array(array('id' => PARENT_ID, 'code' => 'root', 'name' => '项目', 'isParent' => 'true'));
        }
        echo util_jsonUtil :: encode($rs);
    }

    /**
     * 项目详细处理页面
     */
    function c_toDeal()
    {
        $this->assign('ids', $_GET['ids']);
        $this->view('deal');
    }

    /**
     * 批量处理方法
     */
    function c_dealBatch()
    {
        if ($this->service->dealBatch_d($_POST[$this->objName])) {
            msg('处理成功');
        } else {
            msg('处理失败');
        }
    }

    /**
     * 批量处理完成 - 单个项目
     */
    function c_dealComplated()
    {
        if ($this->service->dealComplated_d($_POST['projectId'])) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /************************** end 资源计划处理 *************************/

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
        $this->view('edit');
    }

    /*
     * ajax方式批量删除对象（应该把成功标志跟消息返回）
     */
    function c_ajaxdeletes()
    {
        //$this->permDelCheck ();
        $id = $_POST['id'];
        $changeId = $_POST['changeId'];
        $projectId = $_POST['projectId'];
        try {
            $this->service->deletes_d($id, $changeId, $projectId);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 更新项目的方法
     */
    function c_updateOldData()
    {
        $this->service->updateOldData_d();
        echo 'update complated';
    }

    /**
     * 获取项目的范围id
     */
    function c_getRangeId()
    {
        echo $this->service->getRangeId_d($_POST['projectId']);
    }
}