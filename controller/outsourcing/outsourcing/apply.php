<?php

/**
 * @author Administrator
 * @Date 2013年9月14日 15:49:00
 * @version 1.0
 * @description:外包申请表控制层
 */
class controller_outsourcing_outsourcing_apply extends controller_base_action
{

    function __construct()
    {
        $this->objName = "apply";
        $this->objPath = "outsourcing_outsourcing";
        parent::__construct();
    }

    function c_index()
    {
        $this->service->setCompany(0); # 个人列表,不需要进行公司过滤
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->setCompany(0);

        //$service->asc = false;
        $rows = $service->page_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    function c_indexData()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_default');
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    function c_alllist()
    {
        $this->view('listall');
    }

    //外包申请受理列表
    function c_toDealList()
    {
        $this->view('deal-list');
    }

    /**
     * 项目外包列表
     */
    function c_pageForProject()
    {
        $projectId = isset($_GET['projectId']) && $_GET['projectId'] ? $_GET['projectId'] : die("undefined project id");
        $this->assign('projectId', $projectId);
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view('pageForProject');
    }

    /**
     * 跳转到新增外包申请表页面
     */
    function c_toAdd()
    {
        $this->assign('datenow', date("Y-m-d H:i:s"));
        $this->assign('usernow', $_SESSION['USERNAME']);

        $this->view('add', true);
    }

    /**
     * 项目内新增方法
     */
    function c_toAddFromProject()
    {
        $projectId = isset($_GET['projectId']) && $_GET['projectId'] ? $_GET['projectId'] : die("undefined project id");
        $this->assign('projectId', $projectId);

        // 获取项目的数据
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($projectId);
        $project = $projectDao->feeDeal($project);
        $project = $projectDao->contractDeal($project);
        $this->assignFunc($project);

        $this->assign('datenow', date("Y-m-d H:i:s"));
        $this->assign('usernow', $_SESSION['USERNAME']);
        $this->view('addFromProject', true);
    }

    /**
     * 添加申请事件，跳转到列表页
     */
    function c_add()
    {
        $this->checkSubmit(); //验证是否重复提交
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $applyId = $this->service->add_d($_POST['apply']);
        if ($applyId) {
            if ($actType != '') {
                $provinceId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'provinceId');
                $areaId = $this->service->get_table_fields('oa_esm_office_range', "proId=" . $provinceId, 'id');
                succ_show('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $applyId . '&billArea=' . $areaId);
            } else {
                msg('保存成功！');
            }
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 判断是否可以免审
     */
    function c_isExemptReview() {
    	$officeId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'officeId');
    	$mainManagerId = $this->service->get_table_fields('oa_esm_office_range', "officeId=" . $officeId, 'mainManagerId');
//     	$mainManagerId = $this->service->_db->get_one("SELECT mainManagerId FROM oa_esm_office_range where id='$areaId'");
    	//explode(',',$source);
    	if(empty($mainManagerId) == false && in_array($_SESSION['USER_ID'] , explode(',',$mainManagerId)) ) {
    		echo "1";
    	} else {
    		echo "0";
    	}
    }
    
    /**
     * 免审方法
     */
    function c_exemptReview() {
    	$this->checkSubmit(); //验证是否重复提交
    	$applyId = $this->service->add_d($_POST['apply']);
    	$isSuccess = false;
    	if ($applyId) {
    		$object["id"] = $applyId;
    		$isSuccess = $this->service->exemptReview($object);
    	}
    	msg('审批完成！');
    }
    
    /**
     * 免审方法（编辑）
     */
    function c_exemptReviewByEdit() {
    	$this->checkSubmit(); //验证是否重复提交
    	$this->service->edit_d($_POST['apply']);
    	$object["id"] = $_POST['apply']["id"];
    	$this->service->exemptReview($object);
    	msg('审批完成！');
    }
    
    /**
     * 免审方法（列表）
     */
    function c_exemptReviewByList() {
    	$object["id"] = $_POST['apply']["id"];
    	$this->service->exemptReview($object);
    	echo "1";
    }
    
    /**
     * 跳转到编辑外包申请表页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);

        $obj['notEnough'] == 1 ? ($obj['notEnough'] = "checked='checked'") : ($obj['notEnough'] = "");
        $obj['notSkill'] == 1 ? ($obj['notSkill'] = "checked='checked'") : ($obj['notSkill'] = "");
        $obj['notCost'] == 1 ? ($obj['notCost'] = "checked='checked'") : ($obj['notCost'] = "");
        $obj['notMart'] == 1 ? ($obj['notMart'] = "checked='checked'") : ($obj['notMart'] = "");
        $obj['notElse'] == 1 ? ($obj['notElse'] = "checked='checked'") : ($obj['notElse'] = "");

        $obj['outType1'] = "";
        $obj['outType2'] = "";
        $obj['outType3'] = "";
        if ($obj['outType'] == 3) {
            $obj['outType3'] = "checked='checked'";
        } else if ($obj['outType'] == 2) {
            $obj['outType2'] = "checked='checked'";
        } else {
            $obj['outType1'] = "checked='checked'";
        }
        $this->assignFunc($obj);
        $this->view('edit', true);
    }

    /**
     * 编辑
     * @param bool|false $isEditInfo
     * @throws Exception
     */
    function c_edit($isEditInfo = false)
    {
        $this->checkSubmit(); //验证是否重复提交
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $applyId = $this->service->edit_d($_POST['apply']);
        if ($applyId) {
            if ($actType != '') {
                $provinceId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'provinceId');
                $areaId = $this->service->get_table_fields('oa_esm_office_range', "proId=" . $provinceId, 'id');
                succ_show('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['apply']['id'] . '&billArea=' . $areaId);
            } else {
                msg('保存成功！');
            }
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 跳转到查看外包申请表页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_REQUEST ['id']);
        $obj['notEnough'] == 1 ? ($obj['notEnough'] = "checked='checked'") : ($obj['notEnough'] = "");
        $obj['notSkill'] == 1 ? ($obj['notSkill'] = "checked='checked'") : ($obj['notSkill'] = "");
        $obj['notCost'] == 1 ? ($obj['notCost'] = "checked='checked'") : ($obj['notCost'] = "");
        $obj['notMart'] == 1 ? ($obj['notMart'] = "checked='checked'") : ($obj['notMart'] = "");
        $obj['notElse'] == 1 ? ($obj['notElse'] = "checked='checked'") : ($obj['notElse'] = "");

        $obj['outType1'] = "";
        $obj['outType2'] = "";
        $obj['outType3'] = "";
        if ($obj['outType'] == 3) {
            $obj['outType3'] = "checked='checked'";
        } else if ($obj['outType'] == 2) {
            $obj['outType2'] = "checked='checked'";
        } else {
            $obj['outType1'] = "checked='checked'";
        }
        $this->assignFunc($obj);
        //审批显示处理
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType);
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->view('view');
    }

    /**
     * 跳转到打回外包申请表页面
     */
    function c_toBackApply()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_REQUEST ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back', true);
    }

    /**
     * 打回申请
     * @throws Exception
     */
    function c_backApply()
    {
        $this->checkSubmit(); //验证是否重复提交
        $applyId = $this->service->backApply_d($_POST['apply']);
        if ($applyId) {
            msg('打回成功！');
        } else {
            msg('打回失败！');
        }
    }

    /**
     * 跳转到打回外包申请表页面
     */
    function c_toBackView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_REQUEST ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back-view');
    }

    /**
     * 跳转到关闭外包申请表页面
     */
    function c_toCloseApply()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_REQUEST['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('close', true);
    }

    /**
     * 打回申请
     * @throws Exception
     */
    function c_closeApply()
    {
        $this->checkSubmit(); //验证是否重复提交
        $applyId = $this->service->closeApply_d($_POST['apply']);
        if ($applyId) {
            msg('关闭成功！');
        } else {
            msg('关闭失败！');
        }
    }

    /**
     * 跳转到打回外包申请表页面
     */
    function c_toCloseView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_REQUEST['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('close-view');
    }

    /**
     * 受理
     */
    function c_deal()
    {
        echo $this->service->deal_d($_POST['id']);
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonDeal()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->setCompany(0);
        $service->groupBy = 'c.id';
        $rows = $service->page_d("select_deal_list");
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                if ($val['personSum'] < 5) {
                    if ($val['outType'] == 1) {//整包
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        }
                    } else if ($val['outType'] == 3) {//人员租赁
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +4 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +2 day"));
                        }
                    }
                } else if ($val['personSum'] < 10) {
                    if ($val['outType'] == 1) {//整包
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +10 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        }
                    } else if ($val['outType'] == 3) {//整包
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +4 day"));
                        }
                    }
                } else {
                    if ($val['outType'] == 1) {//整包
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +12 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +10 day"));
                        }
                    } else if ($val['outType'] == 3) {//整包
                        if ($val['nature'] == 'GCYHL') {// 优化
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        } else if ($val['nature'] == 'GCPGL') {//评估
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        }
                    }
                }
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * tab页地址
     */
    function c_viewTab()
    {
        $this->assignFunc($_GET);
        $this->view("viewTab");
    }

    /**
     * 审批完成调用方法
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
}