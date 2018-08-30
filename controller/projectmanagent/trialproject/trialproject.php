<?php

/**
 * @author Administrator
 * @Date 2012-05-15 14:04:07
 * @version 1.0
 * @description:试用项目控制层
 */
class controller_projectmanagent_trialproject_trialproject extends controller_base_action
{

    function __construct()
    {
        $this->objName = "trialproject";
        $this->objPath = "projectmanagent_trialproject";
        parent::__construct();
    }

    /**
     * 批量获取权限
     */
    function c_getLimitArr() {
        echo util_jsonUtil::encode($this->service->this_limit);
    }

    /**
     * 根据项目号获取项目id
     */
    function c_getESMProjectIdByCode() {
        $projectDao = new model_engineering_project_esmproject();
        $projectInfo = $projectDao->find(array('projectCode' => $_POST['projectCode']), null, 'id');
        echo $projectInfo['id'];
    }

    /*
     * 跳转到试用项目列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 试用项目===商机tabl
     */
    function c_listChance()
    {
        $this->assign('chanceId', $_GET['chanceId']);
        $this->view("listchance");
    }

    /**
     * 查看Tab 页
     */
    function c_viewTab()
    {
        $this->assign("id", $_GET ['id']);
        $this->view("viewTab");
    }

    /**
     * 销售线 提交申请页面
     */
    function c_salesAddlist()
    {
        $this->view('salesAddlist');
    }

    /**
     * 提交申请
     */
    function c_subConproject()
    {
        try {
            $this->service->subConproject_d($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 打回单据页面
     */
    function c_toBackBill()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('applyName', $_GET['applyName']);
        $this->assign('serCon', $_GET['serCon']);
        $this->view('backBill');
    }

    /**
     * 单据信息发送
     */
    function c_sendMail()
    {
        $obj = $_POST['mails'];
        $arr = array('id' => $obj['id'], 'trialprojectMessage' => $obj['content']);
        $this->service->updateById($arr);
//     	$obj['userName'] = $_SESSION ['USERNAME'];
//     	$obj['userId'] = $_SESSION ['USER_ID'];
//     	$obj['createTime'] = date ( "Y-m-d H:i:s" );
//     	$dataArr = $this->service->get_d($obj['id']);
//     	$itemNum=$dataArr['projectCode'];
//     	$content = $obj['content'];
        if ($obj['serCon'] == 3) {
            $decide = $this->c_billBack($obj['id']);
        } else {
            $decide = $this->c_backConproject($obj['id']);
        }
        if ($decide) {
            //发送邮件
            $this->service->mailDeal_d('billBack', $obj['TO_ID'], array('id' => $obj['id']));
// 			$emailDao = new model_common_mail();
// 			$msg = "<span style='color:blue'>项目编号</span> ：$itemNum<br/><span style='color:blue'>单据打回信息</span> ： $content";
// 			$emailInfo = $emailDao->backBillEmail(1,$obj['userName'],$_SESSION['EMAIL'],'billBack',null,null,$obj['TO_ID'],$msg);
            msg("单据已打回，等待申请人重新提交");
        } else {
            msg("打回单据失败");
        }

    }

    /**
     * 延期申请单据打回
     */
    function c_billBack($id)
    {
        try {
            $this->service->backDelay_d($id);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * 打回
     */
    function c_backConproject($id)
    {
        try {
            $this->service->backConproject_d($id);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * 销售经理 查看列表
     */
    function c_salesList()
    {
        $this->view('salesList');
    }

    /**
     * 跳转到新增试用项目页面
     */
    function c_toAdd()
    {
        $this->assign("applyName", $_SESSION['USERNAME']);
        $this->assign("applyNameId", $_SESSION['USER_ID']);

        $chanceId = isset($_GET['id']) ? $_GET['id'] : null;
        if ($chanceId) {
            $this->permCheck($chanceId, 'projectmanagent_chance_chance');
            $chanceDao = new model_projectmanagent_chance_chance();
            $rows = $chanceDao->get_d($chanceId);
            //复制license数据
            $licenseDao = new model_yxlicense_license_tempKey();
            $rows = $licenseDao->copyLicense($rows);
            foreach ($rows as $key => $val) {
                $this->assign($key, $val);
            }
            //用于新增也 源单类型
            $chanceType = "chance";
            $this->showDatadicts(array(
                'module' => 'HTBK'
            ), $rows['module']);
        }
        $this->assign('budgetMoney', $rows['chanceMoney']);
        $this->assign('planSignDate', $rows['predictContractDate']);
        $this->assign('chanceId', $chanceId);
        $this->assign('customerName', isset($rows['customerName']) ? $rows['customerName'] : null);
        $this->assign('customerId', isset($rows['customerId']) ? $rows['customerId'] : null);
        $this->assign('SingleType', isset($chanceType) ? $chanceType : null);
        /*************商机下推借试用冗余信息*******************/
        $this->assign('chanceCode', isset($rows['chanceCode']) ? $rows['chanceCode'] : null);
        $this->assign('chanceId', isset($rows['id']) ? $rows['id'] : null);
        /***************************************************/
        $this->view('add', true);
    }

    /**
     * 跳转到编辑试用项目页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit', true);
    }

    function c_serConedit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件
        $this->assign("file", $this->service->getFilesByObjId($obj ['id'], true));
        $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
        $this->view('serConedit');
    }


    /**
     * 跳转到查看试用项目页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = true)
    {
        $this->checkSubmit();
        $arr = $_POST [$this->objName];
        $id = $this->service->add_d($arr, $isAddInfo);
//		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';

        //获取区域扩展字段值
        $regionDao = new model_system_region_region();
        $expand = $regionDao->getExpandbyId($arr['areaCode']);
        if ($id && $_GET['act'] == "app") {
            if ($expand == '1') {
                $sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
                $this->service->_db->query($sql);
                succ_show('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId=' . $id);
                msg("项目已提交审批");
            } else {
                $this->service->subConproject_d($id);
                msg("项目已提交，下一步【金额确认】");
            }
        } else {
            msg("项目已保存，等待提交");
        }

        //$this->listDataDict();
    }

    //ajax 修改提交状态
    function c_ajaxUpdateSer()
    {
        try {
            $id = $_POST ['id'];
            $sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
            $this->service->_db->query($sql);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $SingleType = $obj['SingleType'];
        switch ($SingleType) {
            case "" :
                $this->assign('SingleType', "无");
                $this->assign('singleCode', "无");
                break;
            case "chance" :
                $this->assign('SingleType', "商机");
                $chacneId = $obj['chanceId'];
                $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $obj['chanceCode'] . '</span>';
                $this->assign('singleCode', $code);
                break;
            case "order" :
                $this->assign('SingleType', "合同");
                $orderId = $obj['contractId'];
                $orderCode = $obj['contractNum'];
                $code = '<span class="red" title="点击查看源单" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';

                $this->assign('singleCode', $code);
                break;
        }

        // 获取项目审批记录，返回HTML字符串 ID2234 2016-11-21
        $apl = new model_common_approvalView();
        $appStr = $apl->getTpApprovalResult($obj ['id']);

        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            //附件
            $this->assign("file", $this->service->getFilesByObjId($obj ['id'], false));
            $this->assign('module', $this->getDataNameByCode($obj['module']));
            $this->assign('appResult', $appStr);
            $this->view('view');
        } else {
            //附件
            $this->assign("file", $this->service->getFilesByObjId($obj ['id'], true));
            $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);

            $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);
            $this->view('edit', true);
        }
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = true)
    {
//		$this->permCheck (); //安全校验
        $this->checkSubmit();
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            if ($_GET['act'] == "app") {
                succ_show('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=' . $object['affirmMoney']);
                msg('提交成功,请等待确认！');
            } else {
                msg('编辑成功，请提交确认');
            }
        }
    }

    /**
     * 我的试用申请
     */
    function c_mytrialproject()
    {
        $this->assign("userId", $_SESSION['USER_ID']);
        $this->view("mytrialproject");
    }


    /**
     * 审批通过后处理方法
     */
    function c_confirmExa()
    {
        if (!empty ($_GET ['spid'])) {
            //审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * AJAX获取对象信息
     */
    function c_ajaxGetInfo()
    {
        $id = $_POST['id'];
        $obj = $this->service->get_d($id);

        // 查询用户所属部门
        $otherDatasDao = new model_common_otherdatas();
        $userInfo = $otherDatasDao->getUserDatas($obj['applyNameId']);
        $obj['deptId'] = $userInfo['DEPT_ID'];
        $obj['deptName'] = $userInfo['DEPT_NAME'];

        echo util_jsonUtil::encode($obj);
    }

    /**
     * 列表导出
     */
    function c_exportExcel()
    {
        //提交状态
        $serConArr = array(
            '0' => '未提交',
            '1' => '已提交',
            '2' => '打回',
            '3' => '延期申请',
            '4' => '延期申请打回'

        );
        //项目状态
        $statusArr = array(
            '0' => '未提交',
            '1' => '审批中',
            '2' => '待执行',
            '3' => '执行中',
            '4' => '已完成',
            '5' => '已关闭'
        );
        //是否生效
        $isUseArr = array(
            '0' => '生效',
            '1' => '已转合同',
            '2' => '手工关闭'
        );

        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(600);
        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        if (isset($_SESSION['advSql'])) {
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }

        $service->getParam($_REQUEST);
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        //过滤型权限设置
        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        $rows = $service->listBySqlId('select_default');
        foreach ($rows as $k => $v) {
            //根据试用项目id 获取合同信息
            $conArr = $this->service->getContractBytrId($v['id']);
            $rows[$k]['contractId'] = $conArr[0]['id'];
            $rows[$k]['contractCode'] = $conArr[0]['contractCode'];;
        }
        $arr = array();
        $arr['collection'] = $rows;
        foreach ($rows as $index => $row) {
            foreach ($row as $key => $val) {
                if ($key == 'serCon') {
                    $rows[$index][$key] = $serConArr[$val];
                } else if ($key == 'status') {
                    $rows[$index][$key] = $statusArr[$val];
                } else if ($key == 'isFail') {
                    $rows[$index][$key] = $isUseArr[$val];
                }
            }
        }
        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }
            array_push($dataArr, $colIdArr);
        }
        return model_projectmanagent_trialproject_common_ExcelUtil:: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonCombogrid()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息


        //$service->asc = false;
        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $service->searchArr['isFail'] = '0';
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
     * 获取分页数据转成Json
     */
    function c_mypagejson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息


        //$service->asc = false;
        $rows = $service->page_d();
        foreach ($rows as $k => $v) {
            //根据试用项目id 获取合同信息
            $conArr = $this->service->getContractBytrId($v['id']);
            $rows[$k]['contractId'] = $conArr[0]['id'];
            $rows[$k]['contractCode'] = $conArr[0]['contractCode'];;
        }

        foreach ($rows as $key => $val) {
            //查找扩展值
            //获取区域扩展字段值
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($val['areaCode']);
            $rows[$key]['expand'] = $expand;

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
     * 获取分页数据转成Json
     */
    function c_trialprojectPageJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        $esmDao = new model_engineering_project_esmproject();

        //过滤型权限设置
        $limit = $this->initLimit();
        if ($limit == true) {
            $rows = $service->page_d();
            foreach ($rows as $k => $v) {
                //根据试用项目id 获取合同信息
                $conArr = $this->service->getContractBytrId($v['id']);
                $rows[$k]['contractId'] = $conArr[0]['id'];
                $rows[$k]['contractCode'] = $conArr[0]['contractCode'];
                //获取关联项目决算
                $esmDao->getParam(array('trialStr' => $v['id'], 'contractType' => 'GCXMYD-04', null));
                $trialInfo = $esmDao->list_d('select_defaultAndFee');
                if (!empty($trialInfo)) {
                    $rows[$k]['budgetAll'] = $trialInfo[0]['budgetAll'];
                    $rows[$k]['feeAllCount'] = $trialInfo[0]['feeAll'];
                }
            }
        }
        //$service->asc = false;
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
     * 权限设置
     * 权限返回结果如下:
     * 如果包含权限，返回true
     * 如果无权限,返回false
     */
    function initLimit()
    {
        $service = $this->service;

        // 区域权限
        $areaLimit = $this->getAreaLimit_d();
        // 产品线权限
        $proLimit = $this->getNewProLineLimit_d();

        //权限系统
        if ($areaLimit === true || $proLimit === true) {
            return true;
        } else if ($areaLimit || $proLimit) {
            $sqlArr = array();
            $sqlArr[] = "sql:and ( ";
            if ($areaLimit) $sqlArr[] = $areaLimit;
            if ($proLimit) {
                $sqlArr[] = $areaLimit ? " OR " . $proLimit : $proLimit;
            }

            $sqlArr[] = " )";
            $service->searchArr['mySearchCondition'] = implode('', $sqlArr);
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * 获取产品线权限
     * @return bool 返回 true(全部权限)/false(没有权限)/字符串(部分权限)
     *
     */
    function getNewProLineLimit_d() {
        $proLimit = $this->service->this_limit['产品线'];

        // 如果有权限，则开始权限处理，否则返回false
        if ($proLimit) {
            if (strstr($proLimit, ';;')) {
                return true;
            } else {
                $proLimitArr = explode(",", $proLimit);
                $proLimitStr = " (";
                foreach ($proLimitArr as $k => $v) {
                    $proLimitStr .= $k == 0 ?
                        "find_in_set('" . $v . "',c.newProLineStr)" : " or find_in_set('" . $v . "',c.newProLineStr)";
                }
                $proLimitStr .= ")";
                return $proLimitStr;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取人员的区域权限
     * @return bool|string 返回 true(全部权限)/false(没有权限)/字符串(部分权限)
     */
    function getAreaLimit_d() {
        $areaLimit = $this->service->this_limit['销售区域'];

        // 如果有权限，则开始权限处理，否则返回false
        if ($areaLimit) {
            if (strstr($areaLimit, ';;')) {
                return true;
            } else {
                // 数组集合
                $limitArr = explode(',', $areaLimit);

                // 区域负责人获取相关区域
                $regionDao = new model_system_region_region();
                $areaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
                if (!empty($areaPri)) {
                    //区域权限合并
                    $limitArr = array_unique(array_merge($limitArr, explode(',', $areaIds)));
                }
                return " c.areaCode IN(" . implode(',', $limitArr) . ")";
            }
        } else {
            return false;
        }
    }

    /**
     * 关闭试用项目
     */
    function c_ajaxCloseTr()
    {
        try {
            $this->service->ajaxCloseTr_d($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsons()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        //试用产品线权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('projectmanagent_trialproject_trialproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
//		print_r($sysLimit);
//		die();
        $proLimit = $sysLimit['产品线'];
        if (strstr($proLimit, ';;')) {
            $rows = $service->page_d();
        } else {
            if (!empty($proLimit)) {
                $proLimitArr = explode(",", $proLimit);
                $proLimitStr = "sql: and (";
                foreach ($proLimitArr as $k => $v) {
                    if ($k == 0) {
                        $proLimitStr .= "find_in_set('" . $v . "',c.newProLineStr)";
                    } else {
                        $proLimitStr .= " or find_in_set('" . $v . "',c.newProLineStr)";
                    }
                }
                $proLimitStr .= ")";
                $service->searchArr['mySearchCondition'] = $proLimitStr;
                $rows = $service->page_d();
            } else {
                $rows = "";
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
}