<?php

/**
 * @author Administrator
 * @Date 2012年1月12日 15:55:18
 * @version 1.0
 * @description:供应商评估控制层
 */
class controller_supplierManage_assessment_supasses extends controller_base_action
{

    function __construct()
    {
        $this->objName = "supasses";
        $this->objPath = "supplierManage_assessment";
        parent::__construct();
    }

    /*
     * 跳转到供应商评估列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /*
     * 跳转到我发起的供应商评估列表
     */
    function c_toMyList()
    {
        $this->view('my-list');
    }

    /*
     * 跳转到单个供应商评估列表
     */
    function c_toSuppList()
    {
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : null;
        $this->assign('suppId', $suppId);
        $this->view('supp-list');
    }

    /*
     * 跳转到我参与的供应商评估列表
     */
    function c_toMyJoinList()
    {
        $this->view('myjoin-list');
    }

    /*
 * 跳转到供应商评估评分列表
 */
    function c_toAssesList()
    {
        $this->view('asses-list');
    }

    /**
     * 我发起的供应商评估列表Json
     */
    function c_myListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $service->searchArr ['assesManId'] = $_SESSION ['USER_ID'];
        //$service->asc = false;
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
     * 我参与的供应商评估列表Json
     */
    function c_myJoinListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $service->searchArr ['myjoinId'] = $_SESSION ['USER_ID'];
        //$service->asc = false;
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
     * 供应商评估评分列表Json
     */
    function c_assesListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $ids = $service->affirmUserInfo($_SESSION ['USER_ID']);
        $service->searchArr['ids'] = $ids;
        $service->searchArr['ExaStatusNot'] = '未提交';
        //$service->asc = false;
        $rows = $service->page_d("select_assesList");
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到新增供应商评估页面
     */
    function c_toAdd()
    {
        $this->assign('assesManName', $_SESSION ['USERNAME']); //起草人
        $this->assign('assesManId', $_SESSION ['USER_ID']);
        //读取数据字典
        $this->showDatadicts(array(
            'assessType' => 'FALX'
        ));
        $this->view('add', true);
    }

    /**
     * 跳转到编辑供应商评估页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //评估小组成员
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id']));
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstView', '第二次评估');
        } else {
            $this->assign('isFirstView', '首评');
        }
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('edit', true);
    }

    /**
     * 跳转到供应商评估评分页面
     */
    function c_toAsses()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //评估小组成员
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id']), false);
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstView', '第二次评估');
        } else {
            $this->assign('isFirstView', '首评');
        }
        if ($obj['assessType'] == "gysjd") {
            switch ($obj['assesQuarter']) {
                case 1:
                    $assesQuarter = "第一季度";
                    break;
                case 2:
                    $assesQuarter = "第二季度";
                    break;
                case 3:
                    $assesQuarter = "第三季度";
                    break;
                case 4:
                    $assesQuarter = "第四季度";
                    break;
                default:
                    $assesQuarter = "";
            }

        } else {
            $assesQuarter = "";

        }
        $this->assign("assesQuarter", $assesQuarter);
        $this->assign("assesQuarterCode", $obj['assesQuarter']);
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('asses', true);
    }

    /**
     * 跳转到二次供应商评估页面
     */
    function c_toSecondAss()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //评估小组成员
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('second-ass');
    }

    /**
     * 从合格库跳转到供应商评估页面
     */
    function c_toQuarterAss()
    {
//   		$this->permCheck (); //安全校验
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : "";
        $assesType = isset ($_GET ['assesType']) ? $_GET ['assesType'] : "";
        $taskId = isset ($_GET ['taskId']) ? $_GET ['taskId'] : "";//考核任务ID
        $taskCode = isset ($_GET ['taskCode']) ? $_GET ['taskCode'] : "";
        $flibraryDao = new model_supplierManage_formal_flibrary();
        //获取供应商信息
        $suppRow = $flibraryDao->get_d($suppId);
        //获取联系人名称
        $linkManDao = new model_supplierManage_formal_sfcontact();
        $linkManRow = $linkManDao->conInSupp($suppId);
        $taskDao = new model_supplierManage_assessment_task();
        $taskRow = $taskDao->get_d($taskId);
        $this->assign('suppId', $suppId);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('suppLinkName', $linkManRow['0']['name']);
        $this->assign('suppTel', $suppRow['plane']);
        $this->assign('suppAddress', $suppRow['address'] . " " . $suppRow['zipCode']);
        $this->assign('mainProduct', $suppRow['products']);
        $this->assign('assesManName', $_SESSION ['USERNAME']); //起草人
        $this->assign('assesManId', $_SESSION ['USER_ID']);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('assessType', $assesType);
        $this->assign('taskId', $taskId);
        $this->assign('taskCode', $taskCode);
        if ($assesType == "gysjd") {
            $this->assign('assesQuarter', $taskRow['assesQuarter']);
        } else {
            $this->assign('assesQuarter', "");
        }
        $this->assign('assesYear', $taskRow['assesYear']);
        $this->assign('assessTypeName', $this->getDataNameByCode($assesType));
        $this->view('quarter-ass', false);
    }

    /**
     * 跳转到查看供应商评估页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $viewType = isset ($_GET ['viewType']) ? $_GET ['viewType'] : "";
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstName', '第二次评估');
        } else {
            $this->assign('isFirstName', '首评');
        }
        //评估小组成员
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
            }
            $menberStr = implode(",", $menberArr);
        }
        $this->assign('menberStr', $menberStr);
        if ($obj['assessType'] == "gysjd") {
            switch ($obj['assesQuarter']) {
                case 1:
                    $assesQuarter = "第一季度";
                    break;
                case 2:
                    $assesQuarter = "第二季度";
                    break;
                case 3:
                    $assesQuarter = "第三季度";
                    break;
                case 4:
                    $assesQuarter = "第四季度";
                    break;
                default:
                    $assesQuarter = "";
            }

        } else {
            $assesQuarter = "";

        }
        $this->assign("assesQuarter", $assesQuarter);

        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));//附件
        $assessType = $this->getDataNameByCode($obj ['assessType']);
        $skey = $this->md5Row($obj['suppId'], 'supplierManage_formal_flibrary');
        $this->assign('skey', $skey);//供应商KEY
        $this->assign('assesKey', $this->md5Row($obj['parentId'], 'supplierManage_assessment_supasses'));//评估KEY
        $this->assign('assessTypeName', $assessType);
        $this->assign('viewType', $viewType);
        $this->view('view');
    }

    /**跳转到供应商季度考核报表
     * @author evan
     *
     */
    function c_toQuarterReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : "";
        $beginQuarter = isset ($_GET ['beginQuarter']) ? $_GET ['beginQuarter'] : "";
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : "";
        $endQuarter = isset ($_GET ['endQuarter']) ? $_GET ['endQuarter'] : "";
        $supplierId = isset ($_GET ['supplierId']) ? $_GET ['supplierId'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('beginQuarter', $beginQuarter);
        $this->assign('endYear', $endYear);
        $this->assign('endQuarter', $endQuarter);
        $this->assign('suppId', $supplierId);
        $this->view('quarter-report');
    }

    /**跳转到供应商考核汇总报表
     * @author evan
     *
     */
    function c_toReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : date("Y");
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : date("Y");
        $suppName = isset ($_GET ['suppName']) ? $_GET ['suppName'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('endYear', $endYear);
        $this->assign('suppName', $suppName);
        $this->view('report');
    }

    /**跳转到供应商季度考核报表搜索页面
     * @author evan
     *
     */
    function c_toReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('search');
    }

    /**跳转到供应商季度考核报表搜索页面
     * @author evan
     *
     */
    function c_toQuarterReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('quarter-search');
    }

    /**跳转到供应商年度考核报表
     * @author evan
     *
     */
    function c_toYearReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : "";
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : "";
        $supplierId = isset ($_GET ['supplierId']) ? $_GET ['supplierId'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('endYear', $endYear);
        $this->assign('suppId', $supplierId);
        $this->view('year-report');
    }

    /**跳转到供应商季度考核报表搜索页面
     * @author evan
     *
     */
    function c_toYearReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('year-search');
    }

    /**
     * 保存供应商评估
     *
     */
    function c_add()
    {
        $this->checkSubmit(); //验证是否重复提交
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        if ("audit" == $actType) {
            $_POST[$this->objName]['ExaStatus'] = '评分中';
        } else {
            $_POST[$this->objName]['ExaStatus'] = '未提交';
        }
        $id = $this->service->add_d($_POST[$this->objName],$actType);
        if ($id) {
            if ($type == "passSupp") {
                if ("audit" == $actType) {//提交工作流
//					if($_POST[$this->objName]['taskId']>0){
//						switch($_POST[$this->objName]['assessType']){
//							case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index4.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index4.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index4.php?actTo=ewfSelect&billId='.$id);break;
//						}
//
//					}else{
//						switch($_POST[$this->objName]['assessType']){
//							case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index3.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index3.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index3.php?actTo=ewfSelect&billId='.$id);break;
//						}
//					}

                    if ($_POST[$this->objName]['taskId'] > 0) {
                        msgGo('提交成功', "?model=supplierManage_assessment_task&action=toMyTab");

                    } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                        msgGo('提交成功', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                    } else {
                        msgGo('提交成功', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                    }
                } else {
                    if ($_POST[$this->objName]['taskId'] > 0) {
                        msgGo('保存成功', "?model=supplierManage_assessment_task&action=toMyTab");

                    } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                        msgGo('保存成功', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                    } else {
                        msgGo('保存成功', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                    }
                }

            } else {
//				if ("audit" == $actType) {//提交工作流
//					switch($_POST[$this->objName]['assessType']){
//						case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index.php?actTo=ewfSelect&billId='.$id);break;
//						case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index.php?actTo=ewfSelect&billId='.$id);break;
//						case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index.php?actTo=ewfSelect&billId='.$id);break;
//					}
//				} else {
                msgGo('保存成功', "?model=supplierManage_assessment_supasses&action=toAdd");
//				}

            }
        } else {
            if ($type = "passSupp") {
                if ($_POST[$this->objName]['taskId'] > 0) {
                    msgGo('保存成功', "?model=supplierManage_assessment_task&action=toMyTab");

                } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                    msgGo('保存失败', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                } else {
                    msgGo('保存失败', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                }
            } else {
                msgGo('保存失败', "?model=supplierManage_assessment_supasses&action=toAdd");
            }

        }
    }

    /**
     * 编辑供应商评估
     *
     */
    function c_edit()
    {
        $this->checkSubmit(); //验证是否重复提交
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        if ("audit" == $actType) {
            $_POST[$this->objName]['ExaStatus'] = '评分中';
        } else {
            $_POST[$this->objName]['ExaStatus'] = '未提交';
        }
        $id = $this->service->edit_d($_POST[$this->objName],$actType);
        if ($id) {
            if ("audit" == $actType) {//提交工作流
//                switch($_POST[$this->objName]['assessType']){
//                    case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                    case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                    case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                }

                msgGo('提交成功', "?model=supplierManage_assessment_supasses&action=toMyList");
            } else {
                msgGo('保存成功', "?model=supplierManage_assessment_supasses&action=toMyList");
            }
        } else {

        }
    }

    /**
     * 二次评估供应商
     *
     */
    function c_addSecondAss()
    {
        $id = $this->service->addSecondAss_d($_POST[$this->objName]);
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        if ($id) {
            if ("audit" == $actType) {//提交工作流
                switch ($_POST[$this->objName]['assessType']) {
                    case "xgyspg":
                        succ_show('controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                    case "gysjd":
                        succ_show('controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                    case "gysnd":
                        succ_show('controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                }
            } else {
                msgGo('保存成功', "?model=supplierManage_assessment_supasses&action=toMyList");
            }
        } else {

        }
    }

    /**
     * 供应商评估审批后处理方法
     *
     */
    function c_dealSuppass()
    {
        if (!empty ($_GET ['spid'])) {
            //审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {

            //防止重复刷新,审批后的跳转页面
            echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
        }
    }

    /**验证该供应商是否已进行考核
     * @author suxc
     *
     */
    function c_isAsses()
    {
        $suppId = $_POST['suppId'];
        $supassType = $_POST['supassType'];
        if ($suppId) {
            switch ($supassType) {
                case "gysjd":
                    $flag = $this->service->isAssesQuarter_d($suppId);
                    break;//季度考核
                case "gysnd":
                    $flag = $this->service->isAssesYear_d($suppId);
                    break;//年度考核
                case "xgyspg":
                    $flag = $this->service->isAssesNew_d($suppId);
                    break;//新供应商评估
            }
            echo $flag;
        }
    }

    /**删除评估
     */
    function c_deletesInfo()
    {
        $deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
        $delete = $this->service->deletesInfo_d($deleteId);
        //如果删除成功输出1，否则输出0
        if ($delete) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**提交评估
     */
    function c_sumbitAsses()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : exit;
        $flag = $this->service->sumbitAsses_d($id);
        if ($flag) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**
     * 导入评估信息excel页面
     */
    function c_toExcelIn()
    {
        $this->display('excelin');
    }

    /**
     * 导入评估信息excel
     */
    function c_excelIn()
    {
        $resultArr = $this->service->addFistData_d();

        $title = '评估信息导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * 编辑供应商评估评分
     *
     */
    function c_asses()
    {
//        $this->checkSubmit(); //验证是否重复提交
        $isDoneFlag = $this->service->asses_d($_POST[$this->objName]);
        if ($isDoneFlag) {
            //获取部门负责人
            $leaderIds = $this->service->getDeptLeader($_POST[$this->objName]['id']);
            switch ($_POST[$this->objName]['assessType']) {
                case "xgyspg":
                    succ_show('controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
                case "gysjd":
                    succ_show('controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
                case "gysnd":
                    succ_show('controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
            }
        } else {
            msgGo('提交成功', "?model=supplierManage_assessment_supasses&action=toAssesList");
        }
    }
}