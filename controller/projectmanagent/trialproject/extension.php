<?php

/**
 * @author Administrator
 * @Date 2012-06-18 16:25:39
 * @version 1.0
 * @description:延期申请控制层
 */
class controller_projectmanagent_trialproject_extension extends controller_base_action
{

    function __construct()
    {
        $this->objName = "extension";
        $this->objPath = "projectmanagent_trialproject";
        parent::__construct();
    }

    /*
     * 跳转到延期申请列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 试用项目tab-延期申请
     */
    function c_extensionViewList()
    {
        $this->assign("proId", $_GET['id']);
        $this->view("extensionviewlist");
    }

    /**
     * 跳转到新增延期申请页面
     */
    function c_toAdd()
    {
        //试用项目信息
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $obj = $proDao->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件
        $this->assign("file", $proDao->getFilesByObjId($obj ['id'], false));
        $this->view('add');
    }

    /**
     * 跳转到编辑延期申请页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        //试用项目信息
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $obj = $proDao->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件
        $this->assign("file", $proDao->getFilesByObjId($obj ['id'], false));
        //查找最新的延期申请记录
        $extensionId = $this->service->findExtensionId($_GET ['id']);
        $objArr = $this->service->get_d($extensionId);
        foreach ($objArr as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('budgetMoney', $obj['budgetMoney']);
// 		if($objArr['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$objArr['budgetMoney']);
// 		}
// 		else{
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 		}
        //判断预计金额是否一样，输出不同显示方式
        if ($objArr['budgetMoney'] == $obj['budgetMoney']) {
            $str = <<<EOT
				{$obj['budgetMoney']}
EOT;
        } else {
            $str = <<<EOT
				<font color="red">{$obj['budgetMoney']} => {$objArr['budgetMoney']}</font>
EOT;
        }
        $this->assign('money', $str);
        $this->view('edit');
    }

    /**
     * 跳转到查看延期申请页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //试用项目信息
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $proObj = $proDao->get_d($obj ['trialprojectId']);
        foreach ($proObj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件
        $this->assign("file", $proDao->getFilesByObjId($proObj ['id'], false));
        $this->assign('affirmMoneyEx', $obj['affirmMoney']);
// 		if($obj['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 		}
// 		else{
// 			$this->assign('budgetMoneyEx',$proObj['budgetMoney']);
// 		}
        $this->assign("budgetFile", $this->service->getFilesByObjId($obj ['id'], false));
        //判断预计金额是否一样，输出不同显示方式
        if ($proObj['budgetMoney'] == $obj['budgetMoney']) {
            $str = <<<EOT
				{$proObj['budgetMoney']}
EOT;
        } else {
            $str = <<<EOT
				<font color="red">{$proObj['budgetMoney']} => {$obj['budgetMoney']}</font>
EOT;
        }
        $this->assign('trialprojectId', $obj ['trialprojectId']);
        $this->assign('id', $_GET ['id']);
        $this->assign('actType', 'audit');
        $this->assign('money', $str);
        $this->view('view');
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
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            //试用项目信息
            $proDao = new model_projectmanagent_trialproject_trialproject();
            $proObj = $proDao->get_d($obj ['trialprojectId']);

            foreach ($proObj as $key => $val) {
                $this->assign($key, $val);
            }
            $url = "<a href='index1.php?model=projectmanagent_trialproject_trialproject&action=viewTab&id=" . $obj['trialprojectId'] . "&skey=undefined&placeValuesBefore&TB_iframe=true&modal=false&h'  target='_Blank'>" . $proObj['projectCode'] . "</a>";
            //附件
            $this->assign("file", $proDao->getFilesByObjId($proObj ['id'], false));
            $this->assign('affirmMoneyEx', $obj['affirmMoney']);
// 			if($obj['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 			}
// 			else{
// 				$this->assign('budgetMoneyEx',$proObj['budgetMoney']);
// 			}
            $this->assign("budgetFile", $this->service->getFilesByObjId($obj ['id'], false));
            //判断预计金额是否一样，输出不同显示方式
            if ($proObj['budgetMoney'] == $obj['budgetMoney']) {
                $str = <<<EOT
				{$proObj['budgetMoney']}
EOT;
            } else {
                $str = <<<EOT
				<font color="red">{$proObj['budgetMoney']} => {$obj['budgetMoney']}</font>
EOT;
            }
            $this->assign("trialprojectId", $obj['trialprojectId']);
            $this->assign("projectCode", $url);
            $this->assign('money', $str);
            $this->view('view');
        } else {
            $this->display('edit');
        }
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = true)
    {
        $rows = $_POST [$this->objName];
        $service = $this->service;
        $extTimes = $service->getExtTime_d($rows['trialprojectId']);
        if (!empty($extTimes)) {
            $rows['extensionTime'] = $extTimes + 1;
        } else {
            $rows['extensionTime'] = 1;
        }
        $id = $service->add_d($rows, $isAddInfo);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '已提交延期申请，请等待确认';

        //获取区域扩展字段值
        $dao = new model_projectmanagent_trialproject_trialproject();
        $trialprojectId = $rows['trialprojectId'];
        $arr = $dao->get_d($trialprojectId);
        $regionDao = new model_system_region_region();
        $expand = $regionDao->getExpandbyId($arr['areaCode']);
        if ($id) {
            if ($expand == '1') {
                succ_show('controller/projectmanagent/trialproject/ewf_indexExtension.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $rows['affirmMoney']);
                msg("项目已提交审批");
            } else {
                $updateSql = "update oa_trialproject_trialproject set serCon = '3' where id=" . $trialprojectId;
                $service->query($updateSql);
                if (!empty($arr['productLine'])) {
                    //获取邮件接收人数组
                    $toMailArr = $service->toMailArr;
                    $productLine = $arr['productLine'];
                    $toMailId = '';
                    if (strstr($productLine, ',')) { //存在多个执行部门
                        $productLineArr = explode(',', $productLine);
                        foreach ($productLineArr as $v) {
                            $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId . ',' . $toMailArr[$v];
                        }
                    } else {
                        $toMailId = $toMailArr[$productLine];
                    }
                    if ($toMailId) {
                        $service->mailDeal_d('trialprojectExtension', $toMailId, array('projectCode' => $arr['projectCode']));
                    }
                }
            }
            msg($msg);
        } else {
            msg($msg);
        }
    }

    /**
     * 编辑
     */
    function c_edit($isAddInfo = true)
    {
        $rows = $_POST [$this->objName];
        $id = $this->service->edit_d($rows, $isAddInfo);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '操作成功！';
        if ($id) {
            succ_show('controller/projectmanagent/trialproject/ewf_indexExtension.php?actTo=ewfSelect&proSid=' . $rows['trialprojectId'] . '&billId=' . $rows['id'] . '&flowMoney=' . $rows['affirmMoney']);
            msg($msg);
        } else {
            msg($msg);
        }
    }

    /**
     * 试用项目延期申请审批通过后处理方法
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
     * 获取分页数据转成Json
     */
    function c_extPageJson()
    {
        $service = $this->service;
        $esmDao = new model_engineering_project_esmproject();

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d();
        foreach ($rows as $k => $v) {
            if ($v['extensionDate'] != "0000-00-00") {
                $rows[$k]['extensionDate'] = "<span class='red'>" . $v['endDateOld'] . "=>" . $v['extensionDate'] . "</span>";
            }
            if (!empty($v['newProjectDays'])) {
                $rows[$k]['newProjectDays'] = "<span class='red'>" . $v['oldProjectDays'] . "=>" . $v['newProjectDays'] . "</span>";
            }

            //获取关联项目决算
            $esmDao->getParam(array('trialStr' => $v['trialprojectId'], 'contractType' => 'GCXMYD-04', null));
            $trialInfo = $esmDao->list_d('select_defaultAndFee');
            if (!empty($trialInfo)) {
                $rows[$k]['budgetAll'] = $trialInfo[0]['budgetAll'];
                $rows[$k]['feeAllCount'] = $trialInfo[0]['feeAllCount'];
            }
        }
        //数据加入安全恶码
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

    //查看tab
    function c_viewTab()
    {
        $obj = $this->service->get_d($_GET ['id']);
        $this->assign("id", $_GET ['id']);
        $this->assign("trialprojectId", $obj['trialprojectId']);
        $this->view("viewTab");
    }
}