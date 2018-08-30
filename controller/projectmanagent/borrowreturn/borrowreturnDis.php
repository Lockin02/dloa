<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:�����ù黹������Ʋ�
 */
class controller_projectmanagent_borrowreturn_borrowreturnDis extends controller_base_action
{

    function __construct() {
        $this->objName = "borrowreturnDis";
        $this->objPath = "projectmanagent_borrowreturn";
        parent::__construct();
    }

    /**
     * ��ת�������ù黹�����б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ������
     */
    function c_pageOut() {
        $this->view('listout');
    }

    /**
     * ��ת�����������ù黹����ҳ��
     */
    function c_toAdd() {
        $borrowreturnId = $_GET['borrowreturnId'];
        $borrowreturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
        $borrowreturnObj = $borrowreturnDao->get_d($borrowreturnId);
        //������Ⱦ
        $this->assignFunc($borrowreturnObj);

        $this->view('add');
    }

    /**
     * �����������
     */
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName], true)) {
            msgRf('�´�ɹ���');
        }
    }

    /**
     * ��ת���༭�����ù黹����ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴�����ù黹����ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ��ʼ������
     */
    function c_init() {
        $this->permCheck(); //��ȫУ��
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
     * �޸Ķ���
     */
    function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
        $object = $_POST [$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            msg('�༭�ɹ���');
        }
    }

    /**
     * �ҵĽ��ù黹�б�
     */
    function c_mylist() {
        $this->assign("userId", $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
     * �б�ҳ�ύ����
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
     * ȷ���б�--������
     */
    function c_confirmList() {
        $this->view('confirmList');
    }


    /**
     * ������tabҳ
     */
    function c_viewList() {
        $this->assign("borrowId", $_GET['borrowId']);
        $this->view('viewList');
    }

    /**
     * �ֹܣ��ύ�⳥��
     */
    function c_compensateSub() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('compenstateSub');
    }

    //�ύ�⳥��
    function c_comSub() {
        $rows = $_POST [$this->objName];
        $id = $this->service->comSub_d($rows['id']);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * �⳥���б�
     */
    function c_compenstateList() {
        $this->assign("borrowId", $_GET['borrowId']);
        $this->view('compenstateList');
    }

    /**
     * ȷ���⳥���
     */
    function c_comfirmComMoney() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('comfirmComMoney');
    }

    /**
     * �⳥���鿴
     */
    function c_moneyView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $userInfo = $this->service->findUserInfo($_GET ['id']);
        $this->assign("userName", $userInfo['userName']);
        $this->view('moneyView');
    }

    /**
     * �ύ�⳥������
     */
    function c_confirmMoney() {
        $rows = $_POST [$this->objName];
        $id = $this->service->comfirmMoney_d($rows);
        //���ݴ���id ��ȡ���������˲���
        $deptId = $this->service->findUserDept($rows['id']);
        if ($id) {
            succ_show('controller/projectmanagent/borrowreturn/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptId);
        }
    }

    /**
     * ����ͨ��������
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
                if ($rows ['ExaStatus'] == "���" && $rows != null) {
                    //����ͨ�������´���״̬
                    $updateSql = "update oa_borrow_return_dispose set state = '4' where id = '" . $objId . "'";
                    $this->service->query($updateSql);
                    $this->service->edit_d($rows);
//					  //��ȡĬ�Ϸ�����
//						include (WEB_TOR . "model/common/mailConfig.php");
//						$toMailId = $mailUser['trialproject']['sendUserId']; //�ʼ�������ID
//						$emailDao = new model_common_mail();
//						$emailInfo = $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject", $rows['trialprojectCode'], $toMailId,"������Ŀ��������");
                }
            }
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * ��������
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