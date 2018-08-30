<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:借试用归还管理控制层
 */
class controller_projectmanagent_borrowreturn_borrowreturnDis extends controller_base_action
{

    function __construct() {
        $this->objName = "borrowreturnDis";
        $this->objPath = "projectmanagent_borrowreturn";
        parent::__construct();
    }

    /**
     * 跳转到借试用归还管理列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 待出库
     */
    function c_pageOut() {
        $this->view('listout');
    }

    /**
     * 跳转到新增借试用归还管理页面
     */
    function c_toAdd() {
        $borrowreturnId = $_GET['borrowreturnId'];
        $borrowreturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
        $borrowreturnObj = $borrowreturnDao->get_d($borrowreturnId);
        //数据渲染
        $this->assignFunc($borrowreturnObj);

        $this->view('add');
    }

    /**
     * 新增对象操作
     */
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName], true)) {
            msgRf('下达成功！');
        }
    }

    /**
     * 跳转到编辑借试用归还管理页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看借试用归还管理页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * 初始化对象
     */
    function c_init() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->display('view');
        } else {
            $this->display('edit');
        }
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            msg('编辑成功！');
        }
    }

    /**
     * 我的借用归还列表
     */
    function c_mylist() {
        $this->assign("userId", $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
     * 列表页提交申请
     */
    function c_ajaxSub() {
        try {
            $this->service->ajaxSub_d($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 确认列表--交付部
     */
    function c_confirmList() {
        $this->view('confirmList');
    }


    /**
     * 借试用tab页
     */
    function c_viewList() {
        $this->assign("borrowId", $_GET['borrowId']);
        $this->view('viewList');
    }

    /**
     * 仓管，提交赔偿单
     */
    function c_compensateSub() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('compenstateSub');
    }

    //提交赔偿单
    function c_comSub() {
        $rows = $_POST [$this->objName];
        $id = $this->service->comSub_d($rows['id']);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * 赔偿单列表
     */
    function c_compenstateList() {
        $this->assign("borrowId", $_GET['borrowId']);
        $this->view('compenstateList');
    }

    /**
     * 确认赔偿金额
     */
    function c_comfirmComMoney() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('comfirmComMoney');
    }

    /**
     * 赔偿单查看
     */
    function c_moneyView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $userInfo = $this->service->findUserInfo($_GET ['id']);
        $this->assign("userName", $userInfo['userName']);
        $this->view('moneyView');
    }

    /**
     * 提交赔偿单审批
     */
    function c_confirmMoney() {
        $rows = $_POST [$this->objName];
        $id = $this->service->comfirmMoney_d($rows);
        //根据处理单id 获取借用申请人部门
        $deptId = $this->service->findUserDept($rows['id']);
        if ($id) {
            succ_show('controller/projectmanagent/borrowreturn/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptId);
        }
    }

    /**
     * 审批通过后处理方法
     */
    function c_confirmExa() {
        $affirmMoney = isset($_GET['rows']) ? $_GET['rows'] : null;
        if (!empty ($_GET ['spid'])) {
            $otherdatas = new model_common_otherdatas ();
            $folowInfo = $otherdatas->getWorkflowInfo($_GET ['spid']);
            $objId = $folowInfo ['objId'];
            if (!empty ($objId)) {
                $rows = $this->service->get_d($objId);
                $rows['affirmMoney'] = $affirmMoney['affirmMoney'];
                if ($rows ['ExaStatus'] == "完成" && $rows != null) {
                    //审批通过，更新处理单状态
                    $updateSql = "update oa_borrow_return_dispose set state = '4' where id = '" . $objId . "'";
                    $this->service->query($updateSql);
                    $this->service->edit_d($rows);
//					  //获取默认发送人
//						include (WEB_TOR . "model/common/mailConfig.php");
//						$toMailId = $mailUser['trialproject']['sendUserId']; //邮件接收人ID
//						$emailDao = new model_common_mail();
//						$emailInfo = $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject", $rows['trialprojectCode'], $toMailId,"试用项目延期申请");
                }
            }
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * 财务处理单据
     */
    function c_ajaxDisposeCom() {
        try {
            $this->service->ajaxDisposeCom_d($_POST ['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }
}