<?php

/**
 * @author Michael
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理控制层
 */
class controller_outsourcing_vehicle_rentalcar extends controller_base_action
{

    function __construct()
    {
        $this->objName = "rentalcar";
        $this->objPath = "outsourcing_vehicle";
        parent::__construct();
    }

    /**
     * 跳转到租车的申请与受理列表
     */
    function c_page()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->service->setCompany(0); # 个人列表,不需要进行公司过滤
        $this->view('list');
    }

    /**
     * 服务经理和服务总监
     * 跳转到租车的申请与受理汇总列表
     */
    function c_toAllList()
    {
        $this->assign('projectId', isset($_GET['projectId']) ? $_GET['projectId'] : "");
        $this->view('allList');
    }

    /**
     * 获取分页数据转成Json(去除公司权限)
     */
    function c_pageJson2()
    {
        $service = $this->service;
        $service->setCompany(0);

        $service->getParam($_REQUEST); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
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
     * 获取分页数据转成Json(带项目信息)
     */
    function c_pageJsonProject()
    {
        $service = $this->service;
        $service->getParam($_REQUEST); //设置前台获取的参数信息
        $rows = $service->page_d();
        if (is_array($rows)) {
            $projectdao = new model_engineering_project_esmproject();
            $projectArr = array();
            foreach ($rows as $key => $val) {
                if (is_array($projectArr[$val['projectId']])) {
                    $projectObj = $projectArr[$val['projectId']];
                } else {
                    $projectObj = $projectdao->get_d($val['projectId']);
                    $projectArr[$val['projectId']] = $projectObj;
                }
                $rows[$key]['officeId'] = $projectObj['officeId'];
                $rows[$key]['officeName'] = $projectObj['officeName'];
                $rows[$key]['projectType'] = $projectObj['natureName'];
                $rows[$key]['projectTypeCode'] = $projectObj['nature'];
                $rows[$key]['projectManager'] = $projectObj['managerName'];
                $rows[$key]['projectManagerId'] = $projectObj['managerId'];
            }
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
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
     * 租车接口人
     * 跳转到租车的申请与受理受理列表
     */
    function c_toDealList()
    {
        $this->view('dealList');
    }

    /**
     * 租车负责人
     * 跳转到租车的申请与受理确认列表
     */
    function c_toAffirmList()
    {
        $this->view('affirmList');
    }

    /**
     * 项目经理
     * 跳转到查看租车的申请与受理确认列表
     */
    function c_toViewProjectList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-projectList');
    }

    /**
     * 项目经理
     * 跳转到查看租车的申请与受理确认列表
     */
    function c_toEditProjectList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('edit-projectList');
    }

    /**
     * 跳转到新增租车的申请与受理页面
     */
    function c_toAdd()
    {
        $this->assign('createId', $_SESSION ['USER_ID']);
        $this->assign('createName', $_SESSION ['USERNAME']);
        $this->assign('createTime', date("Y-m-d H:i:s"));
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC')); //测试时长
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD')); //测试时间段
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS')); //预计每月用车天数
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ')); //租车性质
        $this->showDatadicts(array('payGasolineCode' => 'WBYF')); //油费
        $this->showDatadicts(array('payParkingCode' => 'WBLQT')); //路桥停车费
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS')); //是否支司机食宿

        //获取部门
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->view('add', true);
    }

    /**
     * 跳转到新增租车的申请与受理页面
     */
    function c_toAddByProject()
    {
        $this->assign('createId', $_SESSION ['USER_ID']);
        $this->assign('createName', $_SESSION ['USERNAME']);
        $this->assign('createTime', date("Y-m-d H:i:s"));
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC')); //测试时长
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD')); //测试时间段
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS')); //预计每月用车天数
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ')); //租车性质
        $this->showDatadicts(array('payGasolineCode' => 'WBYF')); //油费
        $this->showDatadicts(array('payParkingCode' => 'WBLQT')); //路桥停车费
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS')); //是否支司机食宿

        //获取部门
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        //获取项目信息
        $projectDao = new model_engineering_project_esmproject();
        $projectObj = $projectDao->get_d($_GET['projectId']);
        $this->assign('projectId', $projectObj['id']);
        $this->assign('projectName', $projectObj['projectName']);
        $this->assign('projectCode', $projectObj['projectCode']);
        $this->assign('projectType', $projectObj['natureName']);
        $this->assign('projectTypeCode', $projectObj['nature']);
        $this->assign('projectManager', $projectObj['managerName']);
        $this->assign('provinceId', $projectObj['provinceId']);
        $this->assign('cityId', $projectObj['cityId']);

        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //项目预决算

        $this->view('add-project', true);
    }

    /**
     * 添加申请事件，跳转到列表页
     */
    function c_add()
    {
        $this->checkSubmit(); //验证是否重复提交
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rentalcarId = $this->service->add_d($_POST[$this->objName]);
        if ($rentalcarId) {
            if ($actType) {
                $esmDao = new model_engineering_project_esmproject();
                $areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
                if ($areaId > 0) {
                    $billArea = $areaId;
                } else {
                    $billArea = '';
                }
                succ_show('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId=' . $rentalcarId .
                    '&billArea=' . $billArea . '&flowMoney=' . $_POST[$this->objName]['estimateAmonut']);
            } else {
                msg('保存成功！');
            }
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 跳转到编辑租车的申请与受理页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //项目预决算
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC'), $obj['testTimeCode']); //测试时长
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD'), $obj['testPeriodCode']); //测试时间段
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS'), $obj['expectUseDayCode']); //预计每月用车天数
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'), $obj['rentalPropertyCode']); //租车性质
        $this->showDatadicts(array('payGasolineCode' => 'WBYF'), $obj['payGasolineCode']); //油费
        $this->showDatadicts(array('payParkingCode' => 'WBLQT'), $obj['payParkingCode']); //路桥停车费
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS'), $obj['isPayDriverCode']); //是否支司机食宿

        //获取部门
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->view('edit', true);
    }

    /**
     * 跳转到编辑租车的申请与受理页面
     */
    function c_toEditByProject()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //项目预决算
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC'), $obj['testTimeCode']); //测试时长
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD'), $obj['testPeriodCode']); //测试时间段
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS'), $obj['expectUseDayCode']); //预计每月用车天数
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'), $obj['rentalPropertyCode']); //租车性质
        $this->showDatadicts(array('payGasolineCode' => 'WBYF'), $obj['payGasolineCode']); //油费
        $this->showDatadicts(array('payParkingCode' => 'WBLQT'), $obj['payParkingCode']); //路桥停车费
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS'), $obj['isPayDriverCode']); //是否支司机食宿

        $this->view('edit-project', true);
    }

    /**
     * 租车接口人
     * 跳转到编辑租车的申请与受理页面
     */
    function c_toDealEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '有';
        } else {
            $obj['isTestEngineer'] = '无';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], true)); //显示附件信息

        //获取部门
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->view('dealEdit');
    }

    /**
     * 编辑申请事件
     */
    function c_edit($isEditInfo = false)
    {
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rentalcarId = $this->service->edit_d($_POST[$this->objName]);
        if ($rentalcarId) {
            if ($actType) {
                $esmDao = new model_engineering_project_esmproject();
                $areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
                if ($areaId > 0) {
                    $billArea = $areaId;
                } else {
                    $billArea = '';
                }
                succ_show('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId=' .
                    $rentalcarId . '&billArea=' . $billArea  . '&flowMoney=' . $_POST[$this->objName]['estimateAmonut']);
            } else {
                msg('保存成功！');
            }
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 租车负责人
     * 跳转到确认租车的申请与受理页面
     */
    function c_toAffirmEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '有';
        } else {
            $obj['isTestEngineer'] = '无';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //显示附件信息
        $this->view('affirmEdit');
    }

    /**
     * 租车接口人编辑申请事件
     */
    function c_dealEdit()
    {
        $isSubmit = isset ($_GET['isSubmit']) ? $_GET['isSubmit'] : null;
        $id = $_POST[$this->objName]['id'];
        $obj = $_POST[$this->objName]['supp']; //从表内容
        $rentalcarId = $this->service->dealEdit_d($id, $obj);
        if ($rentalcarId) {
            if ($isSubmit) {
                $this->service->update(array('id' => $id), array('state' => '5'));
            } else {
                $this->service->update(array('id' => $id), array('state' => '4'));
            }
            msg('保存成功！');
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 租车接口人直接提交
     */
    function c_dealSubmit()
    {
        echo $this->service->update(array('id' => $_POST['id']), array('state' => '5'));
    }

    /**
     * 租车负责人确认供应商
     */
    function c_affirmEdit()
    {
        $id = $_POST[$this->objName]['id'];
        $obj = $_POST[$this->objName]['supp']; //从表内容
        $rentalcarId = $this->service->affirmEdit_d($id, $obj); //从写方法
        if ($rentalcarId) {
            msg('保存成功！');
        } else {
            msg('保存失败！');
        }
    }

    /**
     * 跳转到查看租车的申请与受理页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '有';
        } else {
            $obj['isTestEngineer'] = '无';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '是';
        } else {
            $obj['isApplyOilCard'] = '否';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //显示附件信息
        $this->view('view');
    }

    /**
     * 跳转到查看租车的申请与受理页面(可发起合同)
     */
    function c_toViewContract()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '有';
        } else {
            $obj['isTestEngineer'] = '无';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '是';
        } else {
            $obj['isApplyOilCard'] = '否';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //显示附件信息
        $this->view('view-contract');
    }

    /**
     * 跳转到租车负责人打回页面
     */
    function c_toBack()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back');
    }

    /**
     * 租车负责人打回
     */
    function c_back()
    {
        $arr = $this->service->back_d($_POST[$this->objName]);
        if ($arr) {
            msg('打回成功！');
        } else {
            msg('打回失败！');
        }
    }

    /**
     * 跳转到查看租车负责人打回原因页面
     */
    function c_toBackReason()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('backreason');
    }

    /**
     * 审批时显示查看租车的申请与受理页面
     */
    function c_toAudit()
    {
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('projectBudget', '99999.01'); //项目预决算

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '有';
        } else {
            $obj['isTestEngineer'] = '无';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);


        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '是';
        } else {
            $obj['isApplyOilCard'] = '否';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);
        $this->view('audit');
    }

    /**
     * 根据省份id返回办事处信息
     */
    function c_getOfficeInfoForId()
    {
        $esmDao = new model_engineering_project_esmproject();
        $areaId = $esmDao->getRangeId_d($_POST['projectId']);
        if ($areaId > 0) {
            $billArea = $areaId;
        } else {
            $billArea = '';
        }
        echo $billArea;
    }

    /**
     * 租车申请审批通过处理
     */
    function c_dealAfterAuditPass()
    {
        if (!empty ($_GET ['spid'])) {
            //审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
        }
        echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * 导出excel
     */
    function c_excelOut()
    {
        set_time_limit(0);
        $rows = $this->service->listBySqlId('select_excelOut');
        for ($i = 0; $i < count($rows); $i++) {
            unset($rows[$i]['id']);
        }
        $colArr = array();
        $modelName = '外包-租车申请信息';
        $startRowNum = 2;
        return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName, $startRowNum);
    }

    /**
     * 跳转到自定义导出excel页面
     */
    function c_toExcelOutCustom()
    {
        //判断从哪里点的导出
        $createId = isset ($_GET['createId']) ? $_GET['createId'] : null;
        $this->assign('createId', $createId); //个人列表导出
        $isSetCompany = isset ($_GET['isSetCompany']) ? $_GET['isSetCompany'] : null;
        $this->assign('isSetCompany', $isSetCompany); //汇总列表导出

        $ExaStatus = isset ($_GET['ExaStatus']) ? "部门审批','完成','打回" : null;
        $this->assign('ExaStatus', $ExaStatus); //汇总列表导出（已提交审批）
        $this->view('excelOutCustom');
    }

    /**
     * 自定义导出excel
     */
    function c_excelOutCustom()
    {
        set_time_limit(0);
        $formData = $_POST[$this->objName];

        if (!empty($formData['formCode'])) //单据编号
            $this->service->searchArr['formCode'] = $formData['formCode'];

        if (!empty($formData['projectCode'])) //项目编号
            $this->service->searchArr['projectCode'] = $formData['projectCode'];

        if (!empty($formData['projectName'])) //项目名称
            $this->service->searchArr['projectName'] = $formData['projectName'];

        if (!empty($formData['createName'])) //申请人
            $this->service->searchArr['createNameSea'] = $formData['createName'];

        if (!empty($formData['createTimeSta'])) //申请日期上
            $this->service->searchArr['createTimeSta'] = $formData['createTimeSta'];
        if (!empty($formData['createTimeEnd'])) //申请日期下
            $this->service->searchArr['createTimeEnd'] = $formData['createTimeEnd'];

        if (!empty($formData['province'])) //用车省份
            $this->service->searchArr['province'] = $formData['province'];

        if (!empty($formData['city'])) //用车城市
            $this->service->searchArr['city'] = $formData['city'];

        if (!empty($formData['createId'])) //创建者ID
            $this->service->searchArr['createId'] = $formData['createId'];

        if (!empty($formData['ExaStatus'])) //审批状态
            $this->service->searchArr['ExaStatusArr'] = str_replace('\\', '', $formData['ExaStatus']);

        if (!empty($formData['isSetCompany'])) //汇总表导出不区分归属公司
            $this->service->setCompany(0);

        $rows = $this->service->listBySqlId('select_default');
        if (!$rows) {
            echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
                . "<script type='text/javascript'>"
                . "alert('没有记录!');self.parent.tb_remove();"
                . "</script>";
        }

        $rowData = array();
        foreach ($rows as $key => $val) {
            $rowData[$key]['formCode'] = $val['formCode'];
            $rowData[$key]['projectCode'] = $val['projectCode'];
            $rowData[$key]['projectName'] = $val['projectName'];
            $rowData[$key]['projectType'] = $val['projectType'];
            $rowData[$key]['rentalProperty'] = $val['rentalProperty'];
            $rowData[$key]['createName'] = $val['createName'];
            $rowData[$key]['createTime'] = $val['createTime'];
            $rowData[$key]['applicantPhone'] = $val['applicantPhone'];
            if ($val['provinceId'] == 43) { //CDMA团队
                $rowData[$key]['useCarPlace'] = $val['usePlace'];
            } else {
                $rowData[$key]['useCarPlace'] = $val['province'] . '-' . $val['city'];
            }
            $rowData[$key]['useCarAmount'] = $val['useCarAmount'];
            $rowData[$key]['expectStartDate'] = $val['expectStartDate'];
            $rowData[$key]['useCycle'] = $val['useCycle'];
        }

        $colArr = array();
        $modelName = '外包-租车申请信息';
        $startRowNum = 2;
        return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rowData, $modelName, $startRowNum);
    }

    /**
     * 获取权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 跳转到生成合同页面
     */
    function c_toAddContract()
    {
        $obj = $this->service->get_d($_GET['rentalcarId']);

        $this->showDatadicts(array('contractNatureCode' => 'ZCHTXZ')); //合同性质
        $this->showDatadicts(array('contractTypeCode' => 'ZCHTLX')); //合同类型
        $this->showDatadicts(array('payTypeCode' => 'ZCHTFK')); //合同付款方式

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('signDate', day_date);
        $this->assign('createTime', date("Y-m-d H:i:s"));

        $this->assign('rentalcarId', $_GET['rentalcarId']); //申请单ID
        $this->assign('rentalcarCode', $_GET['rentalcarCode']); //申请单号

        $projectdao = new model_engineering_project_esmproject();
        $projectObj = $projectdao->get_d($obj['projectId']);
        $this->assign('projectId', $obj['projectId']); //项目ID
        $this->assign('projectCode', $obj['projectCode']); //项目编号
        $this->assign('projectName', $obj['projectName']); //项目名称
        $this->assign('projectType', $projectObj['natureName']); //项目类型
        $this->assign('projectTypeCode', $projectObj['nature']); //项目类型Code
        $this->assign('projectManagerId', $projectObj['managerId']); //项目经理ID
        $this->assign('projectManager', $projectObj['managerName']); //项目经理
        $this->assign('officeId', $projectObj['officeId']); //区域ID
        $this->assign('officeName', $projectObj['officeName']); //区域

        $equDao = new model_outsourcing_vehicle_rentalcarequ();
        $equObj = $equDao->findAll(array('parentId' => $_GET['rentalcarId']), '', 'suppId');
        foreach ($equObj as $key => $val) {
            $idArr[$key] = $val['suppId'];
        }
        $ids = implode(',', $idArr);
        $this->assign('suppIds', $ids); //供应商Ids

        $this->assign('isApplyOilCard', $obj['isApplyOilCard']); //是否申请油卡
        $this->view('add-contract', true);
    }

    /**
     * 租车申请受理获取分页数据转成Json
     */
    function c_pageJsonDeal()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->groupBy = 'c.id';
        //$service->asc = false;
        $rows = $service->page_d('select_deallist');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
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
     * 工程项目跳转到查看租车信息tab页面
     */
    function c_viewVehicleTab()
    {
        $this->assign('projectId', $_GET['projectId']);

        //租车合同id串，查看付款申请参数
        $rentcarDao = new model_outsourcing_contract_rentcar();
        $rentcarObjs = $rentcarDao->findAll(array('projectId' => $_GET['projectId']));
        $rentcarArr = array();
        if (is_array($rentcarObjs)) {
            foreach ($rentcarObjs as $key => $val) {
                array_push($rentcarArr, $val['id']);
            }
        }
        $this->assign('rentcarIds', implode(',', $rentcarArr));

        $this->view('view-vehicle-tab');
    }

    /**
     * 工程项目跳转到编辑租车信息tab页面
     */
    function c_editVehicleTab()
    {
        $this->assign('projectId', $_GET['projectId']);

        //租车合同id串，查看付款申请参数
        $rentcarDao = new model_outsourcing_contract_rentcar();
        $rentcarObjs = $rentcarDao->findAll(array('projectId' => $_GET['projectId']));
        $rentcarArr = array();
        if (is_array($rentcarObjs)) {
            foreach ($rentcarObjs as $key => $val) {
                array_push($rentcarArr, $val['id']);
            }
        }
        $this->assign('rentcarIds', implode(',', $rentcarArr));

        $this->view('edit-vehicle-tab');
    }

    /**
     * 根据项目id获取项目预算
     */
    function c_getBudgetByProId()
    {
        echo $this->service->getBudgetByProId_d($_POST['projectId']);
    }

    /**
     * 跳转租车转报销付款信息查看页面
     */
    function c_toSeeCostExpense(){
        $payType = $payMainId = $bankName = $bankAccount = $bankReceiver = $includeFeeType = $includeCurNum = $extFormStr = '';
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();

        $fromPage = isset($_REQUEST['fromPage'])? $_REQUEST['fromPage'] : '';
        $_REQUEST = $_REQUEST['addCostApply'];
        $includeCurNum = isset($_REQUEST['carNum'])? util_jsonUtil::iconvUTF2GB($_REQUEST['carNum']) : "";
        $payinfoId = (isset($_REQUEST['payInfoId']) && $_REQUEST['payInfoId']!= 'undefined')? $_REQUEST['payInfoId'] : '';
        $expenseTmpId = (isset($_REQUEST['expenseTmpId']) && $_REQUEST['expenseTmpId']!= 'undefined')? $_REQUEST['expenseTmpId'] : '';
        $deductInfoId = (isset($_REQUEST['deductInfoId']) && $_REQUEST['deductInfoId']!= 'undefined')? $_REQUEST['deductInfoId'] : '';
        $passArr = $_REQUEST;
        $passArr['mainId'] = '';

        if($expenseTmpId != '' && $expenseTmpId != "-"){
            $expenseTmpObj = $expensetmpDao->findExpenseTmpRecord($expenseTmpId);
            $includeCurNum = ($expenseTmpObj != '')? $expenseTmpObj['carNumber'] : $includeCurNum;
        }

        $rentalCarDao = new model_outsourcing_vehicle_rentalcar();

        $formType = "bx";
        if($payinfoId != ''){// 根据支付方式ID获取支付信息
            $payInfo = $this->service->getPayInfoById($payinfoId);
            if($payInfo){
                if($payInfo['payTypeCode'] == "HETFK"){
                    $formType = "zf";
                }
                $payType = $payInfo['payType'];
                $bankName = $payInfo['bankName'];
                $bankAccount = $payInfo['bankAccount'];
                $bankReceiver = $payInfo['bankReceiver'];
                $includeFeeType = $payInfo['includeFeeType'];
                $passArr['mainId'] = $payMainId = $payInfo['mainId'];
                $passArr['payDetail'] = array();
                $includeFeeTypeCode = $payInfo['includeFeeTypeCode'];
                $includeFeeTypeCode = explode(",",$includeFeeTypeCode);
                foreach ($includeFeeTypeCode as $type){
                    if(isset($rentalCarDao->_rentCarFeeName[$type]) && isset($_REQUEST[$type])){
                        $passArr['payDetail'][$type] = $_REQUEST[$type];
                    }
                }

            }
        }else{// 无支付ID的默认读报销发起人以及所有的费用类型
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$_SESSION['USER_ID']}';";
            $bankInfo = $this->service->_db->getArray($bankInfoSql);
            $payType = "报销付发起人";
            $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            // $bankReceiver = ($bankInfo && isset($bankInfo[0]['oftenCardNum']))? $bankInfo[0]['oftenCardNum'] : '';
            $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            $includeFeeType = "";
            $passArr['payDetail'] = array();
            foreach ($_REQUEST as $k => $v){
                if(isset($rentalCarDao->_rentCarFeeName[$k])){
                    $includeFeeType .= ($includeFeeType == "")? $rentalCarDao->_rentCarFeeName[$k] : ",".$rentalCarDao->_rentCarFeeName[$k];
                    $passArr['payDetail'][$k] = $v;
                }
            }
        }

        // 清理多余的数据
        foreach ($_REQUEST as $k => $v){
            if(isset($rentalCarDao->_rentCarFeeName[$k])){
                unset($passArr[$k]);
            }
        }
        $passArr['carNum'] = base64_encode($passArr['carNum']);

        // 只有编辑页面跳转过来的才显示申请报销的按钮
        if($fromPage == "Edit"){
            if($formType == "bx"){// 付款方式为报销
                $extFormStr = "<input type=\"button\" id=\"toAddCost\" class=\"txt_btn_a\"value=\"填报费用表\" />";
            }else if($formType == "zf"){// 付款方式为支付
                $extFormStr = "<input type=\"button\" id=\"toPayCost\" class=\"txt_btn_a\"value=\"填报付款单\" />";
            }
        }else{// 否则显示相关的报销申请的信息

        }

        $costInfoArr = util_jsonUtil::encode($passArr);
        $resultStr = "<table width=\"100%\" style=\"margin-left: 4px\" class=\"form_main_table\">
                    <tr>
                        <td class=\"form_text_left_three_new\" colspan=\"6\" style=\"text-align: center;\">支付信息</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">支付方式</td><td class=\"form_text_right_three\" colspan=\"5\">{$payType}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">收款银行</td><td class=\"form_text_right_three\">{$bankName}</td>
                        <td class=\"form_text_left_three_new\">收款账号</td><td class=\"form_text_right_three\">{$bankAccount}</td>
                        <td class=\"form_text_left_three_new\">收款人</td><td class=\"form_text_right_three\">{$bankReceiver}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">包含费用项</td><td class=\"form_text_right_three\" colspan=\"5\">{$includeFeeType}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">关联车牌号</td><td class=\"form_text_right_three\" colspan=\"5\">{$includeCurNum}</td>
                    </tr>
                    <tr>
                        <td class=\"\" align=\"center\" colspan=\"6\">
                            <input  type=\"hidden\" id=\"payinfoId\"  value=\"{$payinfoId}\" />
                            <input  type=\"hidden\" id=\"expenseTmpId\"  value=\"{$expenseTmpId}\" />
                            <input  type=\"hidden\" id=\"payMainId\"  value=\"{$payMainId}\" />
                            <input  type=\"hidden\" id=\"deductInfoId\"  value='{$deductInfoId}' />
                            <input  type=\"hidden\" id=\"costInfoArr\"  value='{$costInfoArr}' />
                            {$extFormStr}
                        </td>
                    </tr>
                </table>";
        echo util_jsonUtil::iconvGB2UTF($resultStr);
    }

    /**
     * 跳转租车转报销页面
     */
    function c_toAddCostExpense(){
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $_GET['costInfo'] = str_replace("\\","",$_GET['costInfo']);
        $costInfo = util_jsonUtil::decode($_GET['costInfo']);
        $expenseTmpId = isset($_GET['expenseTmpId'])? $_GET['expenseTmpId'] : '';
        $useCarDate = isset($_GET['useCarDate'])? $_GET['useCarDate'] : '';
        $deductInfoId = isset($costInfo['deductInfoId'])? $costInfo['deductInfoId'] : '';
        $deductInfo = false;
        if(!empty($deductInfoId) && $deductInfoId != 'undefined'){
            $deductInfo = $deductinfoDao->findAll(" id in ({$deductInfoId})");
        }

        if(isset($costInfo['payDetail'])){
            $payDetail = $costInfo['payDetail'];
            unset($costInfo['payDetail']);
        }

        if(!isset($costInfo['payInfoId'])){
            $costInfo['payInfoId'] = '';
        }
        foreach ($costInfo as $key => $val) {
            $this->assign($key, $val);
        }

        // 来到这里的时候已经可以根据费用明细里面是否含有租车费来判断扣款金额
        if($deductInfo && isset($payDetail['rentalCarCost'])){
            $deductMoney = 0;
            foreach ($deductInfo as $k => $v){
                $deductMoney = bcadd($deductMoney,$v['deductMoney'],2);
            }
            $this->assign("deductInfoId", $deductInfoId);
            $payDetail['rentalCarCost'] = bcsub($payDetail['rentalCarCost'],$deductMoney,2);
        }else{
            $this->assign("deductInfoId", '');
        }

        $this->assign("registerIds", $deductInfo['registerIds']);
        $this->assign("payDetailJson", util_jsonUtil::encode($payDetail));
        $this->assign("expenseTmpId", $expenseTmpId);
        $this->assign("useCarDateStr", $useCarDate);

        $this->view('addCostExpense');
    }

    /**
     * 跳转租车转报销页面（同时含有支付方式1和2）
     */
    function c_toBatchAddCostExpense(){
        $this->view('batchAddCostExpense');
    }

    /**
     * 通过租车系统的费用类型获取对应的费用系统的用车的费用类型
     */
    function c_getCostTypeByRentalCarType(){
        $payTypeDetail = isset($_POST['payDetail'])? $_POST['payDetail'] : '';
        $expenseTmpId = isset($_POST['expenseTmpId'])? $_POST['expenseTmpId'] : '';
        $useCarDateStr = isset($_POST['useCarDateStr'])? $_POST['useCarDateStr'] : '';
        $configuratorDao = new model_system_configurator_configurator();
        $costTypeIds = "";
        $carNumberArr = $costMoneyArr = $costTypeArr = array();
        $rentalProperty = $allregisterId = '';

        $existCostTypeArr = array();
        if($expenseTmpId != "" && $expenseTmpId != "-"){// 加载上一次填写的数据
            $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
            $expensetmpData = $expensetmpDao->findExpenseTmpRecord($expenseTmpId,'','','',1);
            $rentalProperty = isset($expensetmpData['rentalProperty'])? $expensetmpData['rentalProperty'] : '';
            $allregisterId = isset($expensetmpData['allregisterId'])? $expensetmpData['allregisterId'] : '';
            $carNumberStr = isset($expensetmpData['carNumber'])? $expensetmpData['carNumber'] : '';
            $carNumberArr = explode(",",$carNumberStr);
            if(isset($expensetmpData['detail']) && is_array($expensetmpData['detail'])){
                foreach ($expensetmpData['detail'] as $k => $v){
                    $arr['CostTypeID'] = $v['costTypeCode'];
                    $arr['CostTypeName'] = $v['costType'];
                    $arr['showDays'] = 0;
                    $arr['isSubsidy'] = 0;
                    $arr['ParentCostTypeID'] = $v['costBigTypeCode'];
                    $arr['ParentCostType'] = $v['costBigType'];
                    $arr['costMoney'] = $v['costMoney'];
                    $arr['invoiceData'] = util_jsonUtil::decode($v['invoiceDataJson']);
                    $existCostTypeArr[] = $arr;
                }
            }
        }

        // 短租且存在填报记录的时候
        if(!empty($rentalProperty) && $rentalProperty == '短租' && !empty($allregisterId)){
            $registerDao = new model_outsourcing_vehicle_register();
//            $registerDao->getParam ( array("allregisterId" => $allregisterId, "useCarDateLimit" => $useCarDateStr) );
//            $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//            $rows = $registerDao->listBySqlId ( 'select_Month' );
            $rows = $registerDao->getStatisticsJsonData($allregisterId,$useCarDateStr);
            if($payTypeDetail != '' && $rows){
                $catchPayTypeDetail = array();
                foreach ($rows as $key => $row){
                    if ($row['rentalContractId'] > 0) {
                        //计算租车费和合同用车天数
                        $row['rentalCarCost'] = $registerDao->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,false);
                        $row['contractUseDay'] = $registerDao->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,true);

                        //宏达租车
                        if ($row['rentalPropertyCode'] == 'ZCXZ-03') {
                            $row['rentalCarCost'] = $registerDao->getHongdaFee_d($row['id'] ,$row['rentalContractId']);
                        }else{
                            $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// 含有关联合同号的,默认为长租
                            $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '长租';
                        }
                    } else {
                        $rows[$key]['rentalCarCost'] = '';
                        $rows[$key]['contractUseDay'] = '';
                    }

                    if ($row['rentalPropertyCode'] == 'ZCXZ-02') { //短租的情况
                        $obj = $registerDao->get_d( $row['id'] );
                        $row['rentalCarCost'] = $rows[$key]['shortRent']; //短租的直接显示短租车费的累加
                        $row['gasolineKMPrice'] = $obj['gasolineKMPrice'];
                        $row['gasolineKMCost'] = $obj['gasolineKMPrice'] * $rows[$key]['effectMileage'];
                    }

                    if(in_array($row['carNum'],$carNumberArr) && $row['rentalPropertyCode'] != 'ZCXZ-01'){
                        foreach ($payTypeDetail as $payDKey => $payDVal){
                            $catchPayTypeDetail[$payDKey] = isset($catchPayTypeDetail[$payDKey])? $catchPayTypeDetail[$payDKey] + $row[$payDKey] : $row[$payDKey];
                        }
                    }
                }
                $payTypeDetail = $catchPayTypeDetail;
            }
        }

        // if($expenseTmpId == "" || $expenseTmpId == "-"){// 新增记录
            if($payTypeDetail != ''){
                foreach ($payTypeDetail as $type => $cost){
                    $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$type,array('config_itemSub3' => "1"));
                    if($matchConfigItem && count($matchConfigItem) > 0){
                        if(!empty($matchConfigItem[0]['config_itemSub2'])){
                            // 汇总同一类目的费用金额
                            $costMoneyArr[$matchConfigItem[0]['config_itemSub2']] += $cost;
                        }

                        $costTypeIds .= (!empty($matchConfigItem[0]['config_itemSub2']))? (($costTypeIds == "")? $matchConfigItem[0]['config_itemSub2'] : ",".$matchConfigItem[0]['config_itemSub2']) : '';
                    }
                }
            }

            if($costTypeIds != ""){
                $chkCostTypeSql = "select t2.CostTypeID,t2.CostTypeName,t2.showDays,t2.isReplace,t2.isEqu,t2.invoiceType,t2.invoiceTypeName,t2.isSubsidy,t2.ParentCostTypeID,t2.ParentCostType from cost_type t2 left join cost_type t1 on t2.ParentCostTypeID = t1.CostTypeID where t1.CostTypeID = 339 and t1.CostTypeName = '车辆运行支出费' and t2.CostTypeID in ({$costTypeIds})";
                $costTypeArr = $this->service->_db->getArray($chkCostTypeSql);
                $costTypeArr = ($costTypeArr)? $costTypeArr : array();
                if(!empty($costTypeArr)){
                    foreach ($costTypeArr as $k => $v){
                        $costTypeArr[$k]['costMoney'] = isset($costMoneyArr[$v['CostTypeID']])? $costMoneyArr[$v['CostTypeID']] : 0;
                    }
                }
            }
        // }else{// 已有记录

        // 如果有之前的记录,加载之前的开票信息
        if(!empty($existCostTypeArr)){
            foreach ($costTypeArr as $orgKey => $orgVal){
                foreach ($existCostTypeArr as $existKey => $existVal){
                    if($orgVal['CostTypeID'] == $existVal['CostTypeID']){
                        $costTypeArr[$orgKey]['invoiceData'] = $existVal['invoiceData'];
                    }
                }
            }
        }
        echo util_jsonUtil::encode($costTypeArr);
    }

    /**
     * 长租批量填报时候,获取相关的费用项信息
     */
    function c_getCostExpenseInfo(){
        $backArr = array();
        $payInfoId = isset($_POST['payInfoId'])? $_POST['payInfoId'] : '';
        $catchCostArr = isset($_POST['catchArr'])? $_POST['catchArr'] : '';
        $deductInfoId = isset($catchCostArr['deductInfoId'])? $catchCostArr['deductInfoId'] : '';
        $payInfoArr = $this->service->getPayInfoById($payInfoId);

        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $deductInfo = array();
        if($deductInfoId != ''){
            $deductInfoSql = "select id,sum(deductMoney) as deductMoney from oa_outsourcing_allregister_deductinfo where id in ({$deductInfoId});";
            $deductInfo = $deductinfoDao->get_one($deductInfoSql);
        }

        $catchCostArr['rentalCarCost'] = isset($catchCostArr['rentalCarCost'])? $catchCostArr['rentalCarCost'] : 0;
        $catchCostArr['rentalCarCost'] = ($deductInfo && $deductInfo['deductMoney'] > 0)? bcsub($catchCostArr['rentalCarCost'],$deductInfo['deductMoney'],2) : $catchCostArr['rentalCarCost'];

        $includeFeeTypeCodeArr  = explode(",",$payInfoArr['includeFeeTypeCode']);
        foreach ($catchCostArr as $key => $val){
           if(!in_array($key,$includeFeeTypeCodeArr)){
               unset($catchCostArr[$key]);
           }else if($key == 'rentalCarCost'){
               $payInfoArr['deductInfoId'] = $deductInfoId;
           }
        }

        $costMoneyArr = $costTypeArr = array();$costTypeIds = "";
        if(!empty($catchCostArr)){
            $configuratorDao = new model_system_configurator_configurator();
            foreach ($catchCostArr as $type => $cost){
                $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$type,array('config_itemSub3' => "1"));
                if($matchConfigItem && count($matchConfigItem) > 0){
                    if(!empty($matchConfigItem[0]['config_itemSub2'])){
                        // 汇总同一类目的费用金额
                        $costMoneyArr[$matchConfigItem[0]['config_itemSub2']] += $cost;
                    }

                    $costTypeIds .= (!empty($matchConfigItem[0]['config_itemSub2']))? (($costTypeIds == "")? $matchConfigItem[0]['config_itemSub2'] : ",".$matchConfigItem[0]['config_itemSub2']) : '';
                }
            }

            if($costTypeIds != ""){
                $chkCostTypeSql = "select t2.CostTypeID,t2.CostTypeName,t2.showDays,t2.isReplace,t2.isEqu,t2.invoiceType,t2.invoiceTypeName,t2.isSubsidy,t2.ParentCostTypeID,t2.ParentCostType from cost_type t2 left join cost_type t1 on t2.ParentCostTypeID = t1.CostTypeID where t1.CostTypeID = 339 and t1.CostTypeName = '车辆运行支出费' and t2.CostTypeID in ({$costTypeIds})";
                $costTypeArr = $this->service->_db->getArray($chkCostTypeSql);
                $costTypeArr = ($costTypeArr)? $costTypeArr : array();
                if(!empty($costTypeArr)){
                    foreach ($costTypeArr as $k => $v){
                        $costTypeArr[$k]['costMoney'] = isset($costMoneyArr[$v['CostTypeID']])? $costMoneyArr[$v['CostTypeID']] : 0;
                    }
                }
            }
        }

        $backArr['costInfo'] = util_jsonUtil::iconvGB2UTFArr($costTypeArr);
        $backArr['payInfo'] = util_jsonUtil::iconvGB2UTFArr($payInfoArr);

        echo json_encode($backArr);
    }

    /**
     * 添加租车报销费用临时信息（审批通过后才转成有效信息,否则可继续编辑）
     */
    function c_addCostExpenseTmp(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = isset($_POST['expenseTmp'])? $_POST['expenseTmp'] : array();
        $deductInfoId = isset($expenseTmp['deductInfoId'])? $expenseTmp['deductInfoId'] : '';

        // 获取并解析该支付方式的费用类型json数据
        $expenseTmp['payDetailJson'] = str_replace("\\","",$expenseTmp['payDetailJson']);
        $payDetailJson = util_jsonUtil::decode($expenseTmp['payDetailJson']);
        $payMoney = 0;
        foreach ($payDetailJson as $v){
            $payMoney = bcadd($payMoney,$v,2);
        }
        // 重新解析车牌号
        $carNumber = base64_decode($expenseTmp['carNum']);
        $carNumber = util_jsonUtil::iconvUTF2GB($carNumber);

        // 获取相关的租车登记信息以及租车合同信息
        $rentalCarBaseInfo = $this->service->getRentalCarBaseInfo($expenseTmp['allregisterId'],$carNumber,$expenseTmp['rentalContractId']);

        $rentalProperty = $rentalPropertyCode = "";
        if($expenseTmp['rentalContractId'] && $expenseTmp['rentalContractId'] > 0){
            $rentalProperty = '长租';
            $rentalPropertyCode = 'ZCXZ-01';
        }else{
            $rentalProperty = (isset($expenseTmp['rentalPropertyCode']) && $expenseTmp['rentalPropertyCode'] == "ZCXZ-02")? "短租" : $rentalCarBaseInfo['rentalProperty'];
            $rentalPropertyCode = (isset($expenseTmp['rentalPropertyCode']) && $expenseTmp['rentalPropertyCode'] == "ZCXZ-02")? "ZCXZ-02" : $rentalCarBaseInfo['rentalPropertyCode'];
        }

        $mainArr = array(
            "id" => $expenseTmp['id'],
            "allregisterId" => $expenseTmp['allregisterId'],
            "carNumber" => $carNumber,
            "carNumBase64" => base64_encode($carNumber),
            "rentalProperty" => $rentalProperty,
            "rentalPropertyCode" => $rentalPropertyCode,
            "driverName" => $rentalCarBaseInfo['driverName'],
            "rentalContractId" => $expenseTmp['rentalContractId'],
            "contractStartDate" => $rentalCarBaseInfo['contractStartDate'],
            "contractEndDate" => $rentalCarBaseInfo['contractEndDate'],
            "useCarDate" => $expenseTmp['useCarDate'],
            "useCarStartDate" => $rentalCarBaseInfo['startDate'],
            "useCarEndDate" => $rentalCarBaseInfo['endDate'],
            "useCarDays" => $rentalCarBaseInfo['useCarDays'],
            "payInfoId" => $expenseTmp['payInfoId'],
            "registerIds" => $expenseTmp['registerIds'],
            "payMoney" => $payMoney,
            "isConfirm" => 0,
            "ExaStatus" => "未审批"
        );

        $payDetail = array();
        // 处理支付费用临时记录子表数据
        if(!empty($expenseTmp['expensedetail'])){
            $rentalPropertype = $mainArr['rentalProperty'];
            $rentalPropertypeCode = $mainArr['rentalPropertyCode'];
            $costMoney = 0;
            foreach ($expenseTmp['expensedetail'] as $detail){
                $arr['parentId'] = '';
                $arr['rentcarType'] = $rentalPropertype;
                $arr['rentcarTypeCode'] = $rentalPropertypeCode;
                $arr['costBigType'] = isset($detail['MainType'])? $detail['MainType'] : '';
                $arr['costBigTypeCode'] = isset($detail['MainTypeId'])? $detail['MainTypeId'] : '';
                $arr['costType'] = isset($detail['costType'])? $detail['costType'] : '';
                $arr['costTypeCode'] = isset($detail['costTypeId'])? $detail['costTypeId'] : '';
                $arr['costMoney'] = isset($detail['costMoney'])? str_replace(",","",$detail['costMoney']) : 0;
                $costMoney = bcadd($costMoney,$arr['costMoney'],3);
                $invoiceArr = array();
                if(isset($detail['expenseinv']) && !empty($detail['expenseinv'])){
                    foreach ($detail['expenseinv'] as $invoiceItem){
                        if($invoiceItem['isDelTag'] != 1){
                            $invoiceItem['Amount'] = str_replace(",","",$invoiceItem['Amount']);
                            $invoiceArr[] = $invoiceItem;
                        }
                    }
                }
                $arr['invoiceDataJson'] = json_encode($invoiceArr);
                $arr['costBigType'] = util_jsonUtil::iconvUTF2GB($arr['costBigType']);
                $arr['costType'] = util_jsonUtil::iconvUTF2GB($arr['costType']);
                $payDetail[] = $arr;
            }

            // 根据费用明细来统计填报的费用金额
            $mainArr['payMoney'] = $costMoney;
        }

        $tmpRecordId = $expensetmpDao->addRecord($mainArr,$payDetail);
        if($tmpRecordId && $deductInfoId != ''){
            $updateSql = "update oa_outsourcing_allregister_deductinfo set payinfoId = '{$expenseTmp['payInfoId']}', expensetmpId = '{$tmpRecordId}' where id = '{$deductInfoId}';";
            $this->service->_db->query($updateSql);
        }

        // echo"<pre>"; print_r($result);
        // $data = $expensetmpDao->findExpenseTmpRecord("",$mainArr['allregisterId'],$mainArr['carNumBase64'],$mainArr['payInfoId'],1);
        // print_r($data); print_r($mainArr); print_r($payDetail);
        echo ($tmpRecordId)? "ok" : "fail";
    }

    /**
     * 长租批量填报费用信息
     */
    function c_batchAddCZCostExpenseTmp(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = isset($_POST['expenseTmp'])? $_POST['expenseTmp'] : array();

        // 重新解析车牌号
        $carNumber = util_jsonUtil::iconvUTF2GB($expenseTmp['carNum']);
        // 获取相关的租车登记信息以及租车合同信息
        $rentalCarBaseInfo = $this->service->getRentalCarBaseInfo($expenseTmp['allregisterId'],$carNumber,$expenseTmp['rentalContractId']);

        $payInfoNum = 0;
        if(isset($expenseTmp[1]) && isset($expenseTmp[1]['expensedetail'])){
            $payInfoNum = 1;
        }
        if(isset($expenseTmp[2]) && isset($expenseTmp[2]['expensedetail'])){
            $payInfoNum = 2;
        }

        $expenseTmp = util_jsonUtil::iconvUTF2GBArr($expenseTmp);

        $tmpRecordAddResult = true;
        if($payInfoNum > 0){
            for ($i = 1;$i <= $payInfoNum;$i++){
                $payMoney = 0;
                $rentalProperty = "长租";
                $rentalPropertyCode = "ZCXZ-01";
                $dataArr = $expenseTmp[$i];
                $deductInfoId = isset($dataArr['deductInfoId'])? $dataArr['deductInfoId'] : '';

                $mainArr = array(
                    "id" => $expenseTmp['id'],
                    "allregisterId" => $expenseTmp['allregisterId'],
                    "carNumber" => $carNumber,
                    "carNumBase64" => base64_encode($carNumber),
                    "rentalProperty" => $rentalProperty,
                    "rentalPropertyCode" => $rentalPropertyCode,
                    "driverName" => $rentalCarBaseInfo['driverName'],
                    "rentalContractId" => $expenseTmp['rentalContractId'],
                    "contractStartDate" => $rentalCarBaseInfo['contractStartDate'],
                    "contractEndDate" => $rentalCarBaseInfo['contractEndDate'],
                    "useCarDate" => $expenseTmp['useCarDate'],
                    "useCarStartDate" => $rentalCarBaseInfo['startDate'],
                    "useCarEndDate" => $rentalCarBaseInfo['endDate'],
                    "useCarDays" => $rentalCarBaseInfo['useCarDays'],
                    "payInfoId" => $dataArr['payInfoId'],
                    "registerIds" => $expenseTmp['registerIds'],
                    "payMoney" => $payMoney,
                    "isConfirm" => 0,
                    "ExaStatus" => "未审批"
                );

                $payDetail = array();
                // 处理支付费用临时记录子表数据
                if(!empty($dataArr['expensedetail'])){
                    $rentalPropertype = $mainArr['rentalProperty'];
                    $rentalPropertypeCode = $mainArr['rentalPropertyCode'];
                    $costMoney = 0;
                    foreach ($dataArr['expensedetail'] as $detail){
                        $arr['parentId'] = '';
                        $arr['rentcarType'] = $rentalPropertype;
                        $arr['rentcarTypeCode'] = $rentalPropertypeCode;
                        $arr['costBigType'] = isset($detail['MainType'])? $detail['MainType'] : '';
                        $arr['costBigTypeCode'] = isset($detail['MainTypeId'])? $detail['MainTypeId'] : '';
                        $arr['costType'] = isset($detail['costType'])? $detail['costType'] : '';
                        $arr['costTypeCode'] = isset($detail['costTypeId'])? $detail['costTypeId'] : '';
                        $arr['costMoney'] = isset($detail['costMoney'])? str_replace(",","",$detail['costMoney']) : 0;
                        $costMoney = bcadd($costMoney,$arr['costMoney'],3);
                        $invoiceArr = array();
                        if(isset($detail['expenseinv']) && !empty($detail['expenseinv'])){
                            foreach ($detail['expenseinv'] as $invoiceItem){
                                if($invoiceItem['isDelTag'] != 1){
                                    $invoiceItem['Amount'] = str_replace(",","",$invoiceItem['Amount']);
                                    $invoiceArr[] = $invoiceItem;
                                }
                            }
                        }
                        $arr['invoiceDataJson'] = json_encode($invoiceArr);
                        $payDetail[] = $arr;
                    }

                    // 根据费用明细来统计填报的费用金额
                    $mainArr['payMoney'] = $costMoney;
                }
                $tmpRecordId = $expensetmpDao->addRecord($mainArr,$payDetail);
                if($tmpRecordId && $deductInfoId != ''){
                    $updateSql = "update oa_outsourcing_allregister_deductinfo set payinfoId = '{$dataArr['payInfoId']}', expensetmpId = '{$tmpRecordId}' where id = '{$deductInfoId}';";
                    $this->service->_db->query($updateSql);
                }
                $tmpRecordAddResult = (!$tmpRecordId)? false : $tmpRecordAddResult;
            }
        }else{
            $tmpRecordAddResult = false;
        }

        echo ($tmpRecordAddResult)? "ok" : "fail";
    }

    function c_viewCostExpenseTmp(){
        $otherDataDao = new model_common_otherdatas();
        $payInfoId = isset($_GET['payInfoId'])? $_GET['payInfoId'] : '';
        $expenseTmpId = (isset($_GET['expenseTmpId']) && $_GET['expenseTmpId'] != '-')? $_GET['expenseTmpId'] : '';
        $payInfoDao = new model_outsourcing_contract_payInfo();
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = $expensetmpDao->findExpenseTmpRecord($expenseTmpId,"","","",1);
        $payInfo = $payInfoDao->get_d($payInfoId);
        $billType = $otherDataDao->getBillType();
        $billTypeArr = array();
        if($billType && is_array($billType)){
            foreach ($billType as $k => $v){
                $billTypeArr[$v['id']] = $v['name'];
            }
        }

        // 付款信息
        $payType = $bankName = $bankAccount = $bankReceiver = $includeFeeType = "";
        if($payInfoId == ""){
            $rentalCarDao = new model_outsourcing_vehicle_rentalcar();
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$expenseTmp['createId']}';";
            $bankInfo = $this->service->_db->getArray($bankInfoSql);
            $payType = "报销付发起人";
            $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            $includeFeeType = "";
            if(is_array($rentalCarDao->_rentCarFeeName)){
                foreach ($rentalCarDao->_rentCarFeeName as $feeName){
                    $includeFeeType .= ($includeFeeType == "")? $feeName : ",".$feeName;
                }
            }
        }else if($payInfo){
            $payType = $payInfo['payType'];
            if($payType == "报销付发起人"){
                $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$expenseTmp['createId']}';";
                $bankInfo = $this->service->_db->getArray($bankInfoSql);
                $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
                $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
                $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            }else{
                $bankName = $payInfo['bankName'];
                $bankAccount = $payInfo['bankAccount'];
                $bankReceiver = $payInfo['bankReceiver'];
            }
            $includeFeeType = $payInfo['includeFeeType'];
        }
        $this->assign("payType",$payType);
        $this->assign("bankName",$bankName);
        $this->assign("bankAccount",$bankAccount);
        $this->assign("bankReceiver",$bankReceiver);
        $this->assign("includeFeeType",$includeFeeType);
        $this->assign("includeCurNum",$expenseTmp['carNumber']);

        // 费用信息
        $costDetails = "";
        $costDetail = isset($expenseTmp['detail'])? $expenseTmp['detail'] : '';
        if($costDetail != '' && $expenseTmpId != '' && is_array($expenseTmp['detail'])){
            $totalAmount = $totalInvVal = $totalInvNum = 0;
            foreach ($expenseTmp['detail'] as $k => $item){
                $invoiceData = util_jsonUtil::decode($item['invoiceDataJson']);

                $tr_class = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costDetails .= "<tr class='{$tr_class}'>
                    <td valign='top' class='form_text_right'>{$item['costBigType']}</td>
                    <td valign='top' class='form_text_right'>{$item['costType']}</td>
                    <td valign='top' class='form_text_center formatMoney' align='center'>{$item['costMoney']}</td>
                ";
                $totalAmount += $item['costMoney'];
                $invStr = "<td valign='top' colspan='4' class='innerTd'>";
                if(is_array($invoiceData)){
                    $invStr .= "<table class='form_in_table'>";
                    foreach ($invoiceData as $invItem){
                        $totalInvVal += $invItem['Amount'];
                        $totalInvNum += $invItem['invoiceNumber'];
                        $billType = isset($billTypeArr[$invItem['BillTypeID']])? $billTypeArr[$invItem['BillTypeID']] : '';
                        $invStr .= "
                            <tr>
                                <td width='29%'>{$billType}</td>
                                <td width='24%'>{$invItem['Amount']}</td>
                                <td width='24%'>{$invItem['invoiceNumber']}</td>
                            </tr>
                        ";
                    }
                    $invStr .= "</table>";
                }
                $invStr .= "</td>";
                $costDetails .= $invStr ."</tr>";
            }
        }

        $this->assign("costDetails",$costDetails);
        $this->assign("totalAmount",$totalAmount);
        $this->assign("totalInvVal",$totalInvVal);
        $this->assign("totalInvNum",$totalInvNum);
        // echo "<pre>";print_r($bankInfo);
        $this->view("viewCostExpenseTmp");
    }
}