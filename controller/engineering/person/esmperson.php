<?php

/**
 * @author Show
 * @Date 2012年6月18日 星期一 17:35:04
 * @version 1.0
 * @description:项目人力预算控制层
 */
class controller_engineering_person_esmperson extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmperson";
        $this->objPath = "engineering_person";
        parent:: __construct();
    }

    /********************* 列表部分 *********************************/

    /**
     * 跳转到项目人力预算
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
        if (isset($_POST['activityId']) && $_POST['activityId'] == -1) {
            unset($_POST['activityId']);
        } else {
            $_POST['activityIds'] = $this->service->getUnderTreeIds_d($_POST['activityId'], $_POST['lft'], $_POST['rgt']);
            unset($_POST['activityId']);
            unset($_POST['lft']);
            unset($_POST['rgt']);
        }
        $service->getParam($_POST); //设置前台获取的参数信息

        $rows = $service->page_d();

        if (is_array($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //本页小计 - 包含预变更项
            $rsArr = array('id' => 'noId', 'days' => 0, 'personDays' => 0, 'personCost' => 0, 'number' => 0, 'personLevel' => '本页小计');
            foreach ($rows as $val) {
                $rsArr['days'] = bcadd($rsArr['days'], $val['days']);
                $rsArr['personDays'] = bcadd($rsArr['personDays'], $val['personDays']);
                $rsArr['personCostDays'] = bcadd($rsArr['personCostDays'], $val['personCostDays']);
                $rsArr['personCost'] = bcadd($rsArr['personCost'], $val['personCost'], 2);
                $rsArr['number'] = bcadd($rsArr['number'], $val['number']);
            }
            $rows[] = $rsArr;

            //加载项目合计
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['personLevel'] = '项目合计';
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
     * 人力预算编辑列表
     */
    function c_toEditList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $project = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assign('ExaStatus', $project['ExaStatus']);
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * 人力预算查看列表
     */
    function c_toViewList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list-view');
    }

    /**
     * 列表查看页面 - 默认
     */
    function c_toViewPage()
    {
        //项目id
        $projectId = $_GET['projectId'];
        //获取项目任务
        $activityArr = $this->service->getActivityArr_d($projectId);
        $str = $this->service->initViewPage_d($activityArr);

        $this->assign('list', $str);
        $this->view('view-page');
    }

    /**
     * 任务成员列表
     */
    function c_taskListJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d('select_person');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }
    /************************ 增删改查 **************************/

    /**
     * 跳转到新增项目人力预算
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

        $this->view('add');
    }

    /**
     * 项目预算批量新增
     */
    function c_toAddBatch()
    {
        //项目部分渲染
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //获取部分渲染
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->view('addbatch');
    }

    /**
     * 批量新增
     */
    function c_addBatch()
    {
        if ($this->service->addBatch_d($_POST[$this->objName])) {
            msg($_POST["msg"] ? $_POST["msg"] : '添加成功！');
        }
    }

    /**
     * 跳转到编辑项目人力预算
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看项目人力预算
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
     * 复制费用预算 - 页面渲染
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
        $service->searchArr ['ids'] = $_GET['ids'];
        $service->asc = false;
        $rows = $service->list_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 复制预算
     */
    function c_copy()
    {
        $rows = $_POST ["esmEditArr"]; //复制信息
        $act = $_POST [$this->objName]; //复制位置
        foreach ($rows as $key => $val) {
            $rows[$key]['activityId'] = $act['activityId'];
            $rows[$key]['activityName'] = $act['activityName'];
        }
        $this->service->saveDelBatch($rows);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        msg($msg);
    }

    /**
     * 更新人力决算
     */
    function c_updateSalary()
    {
        $rs = $this->service->updateSalary_d($_POST['thisYear'], $_POST['thisMonth']);
        if ($rs) {
            exit(util_jsonUtil::iconvGB2UTF($rs));
        } else {
            exit(0);
        }
    }
}