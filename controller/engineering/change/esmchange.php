<?php

/**
 * @author Show
 * @Date 2011年12月3日 星期六 14:17:32
 * @version 1.0
 * @description:项目变更申请单(oa_esm_change_baseinfo)控制层
 */
class controller_engineering_change_esmchange extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmchange";
        $this->objPath = "engineering_change";
        parent::__construct();
    }

    /**
     * 跳转到项目变更申请单
     */
    function c_page()
    {
        $this->view('list');
    }

    /************************** 项目变更 *********************************/
    /**
     * TODO项目变更
     *
     */
    function c_toAdd()
    {
        $projectId = $_GET['projectId'];
        $projectObj = $this->service->getProjectForChange_d($projectId);
        $this->assignFunc($projectObj);

        $this->showDatadicts(array('outsourcing' => 'WBLX'), $projectObj['outsourcing'], true);//外包类型
        $this->showDatadicts(array('outsourcingType' => 'GCWBLX'), $projectObj['outsourcingType'], true);//外包方式

        $this->view('add');
    }

    /**
     * 新增对象操作
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            if ($_GET['act']) {
                //获取对应省份的范围id
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msg('保存成功');
            }
        } else {
            msg('保存失败');
        }
    }

    /**
     * 编辑页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * 修改对象
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if ($_GET['act']) {
                //获取对应省份的范围id
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msg('编辑成功');
            }
        } else {
            msg('编辑失败！');
        }
    }

    /**
     * 初始化对象 - 暂用
     */
    function c_init()
    {
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->permCheck(); //安全校验
            $obj = $this->service->getViewInfo_d($_GET['id']);
            $this->assignFunc($obj);
            $this->view('view');
        } else {
            $this->permCheck(); //安全校验
            $obj = $this->service->getViewInfo_d($_GET['id']);
            $this->assignFunc($obj);
            $this->view('edit');
        }
    }

    /**
     * 查看页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * 审批查看页面
     */
    function c_viewAudit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('viewaudit');
    }

    /**
     * 项目变更列表
     */
    function c_pageForProject()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('isManager', $this->service->isManager_d($_GET['projectId']));
        $projectInfo = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assign('estimates', $projectInfo['estimates']);
        $this->display('listforproject');
    }

    /**
     * 判断项目是否已经有对应的变更单据
     */
    function c_hasChangeInfo()
    {
        echo $this->service->hasChangeInfo_d($_POST['projectId']);
    }

    /**
     * 审批完成后处理方法
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ajax调用的变更方法
     */
    function c_ajaxChange()
    {
        echo $this->service->setChange_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 验证试用项目实施周期及预算是否超出原PK申请时的设置
     */
    function c_isPKOverproof()
    {
        $service = $this->service;

        $object = $service->get_d($_POST['id']);
        //获取对应业务信息
        $esmprojectDao = new model_engineering_project_esmproject();
        $sTrialprojectDao = new model_engineering_project_strategy_sTrialproject();
        $rs = $esmprojectDao->find(array('id' => $object['projectId']), null, 'contractId');
        $robj = $sTrialprojectDao->getRawInfo_i($rs['contractId']);
        if (strtotime($object['planBeginDate']) < strtotime($robj['beginDate']) || strtotime($object['planEndDate']) > strtotime($robj['closeDate'])) {
            echo 1;    //实施周期不合法
        } elseif ($object['newBudgetAll'] > $robj['affirmMoney']) {
            echo 2; //预算不合法
        }
    }
}