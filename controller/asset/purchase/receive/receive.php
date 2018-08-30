<?php

/**
 *
 * �ʲ����տ��Ʋ���
 * @author fengxw
 *
 */
class controller_asset_purchase_receive_receive extends controller_base_action
{

    function __construct()
    {
        $this->objName = "receive";
        $this->objPath = "asset_purchase_receive";
        parent::__construct();
    }

    /*
     * ��ת���ʲ�����
     */
    function c_page()
    {
        $this->view("list");
    }

    /*
     * ��ת���ʲ�����
     */
    function c_myPage()
    {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view("mylist");
    }

    /*
     * ��ת���ʲ�����
     */
    function c_pageByApply()
    {
        $applyId = isset($_GET['applyId']) ? $_GET['applyId'] : 0;
        $this->assign('applyId', $applyId);
        $this->view("listbyapply");
    }

    /**
     * ��ת������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('salvageId', $_SESSION['USER_ID']);
        $this->assign('salvage', $_SESSION['USERNAME']);
        $this->assign('company', $_SESSION['COM_BRN_PT']);
        $this->assign('companyName', $_SESSION['COM_BRN_CN']);
        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('deptName', $deptInfo['DEPT_NAME']);
        $this->assign('limitYears', date("Y-m-d"));
        $this->view('add', true);
    }

    /**
     * �ɲɹ�������ת���ʲ���������ҳ��
     */
    function c_toRequireAdd()
    {
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('deptName', $deptInfo['DEPT_NAME']);

        $this->assign('company', $_SESSION['COM_BRN_PT']);
        $this->assign('companyName', $_SESSION['COM_BRN_CN']);
        $this->assign('limitYears', date("Y-m-d"));

        $this->assign('code', isset($_GET['code']) ? $_GET['code'] : null);
        $this->assign('applyId', isset($_GET['applyId']) ? $_GET['applyId'] : null);
        $this->assign('salvageId', isset($_GET['applicantId']) ? $_GET['applicantId'] : $_SESSION['USER_ID']);
        $this->assign('salvage', isset($_GET['applicantName']) ? $_GET['applicantName'] : $_SESSION['USERNAME']);

        $this->view('require-add', true);
    }

    /**
     * ���ʲ��ɹ�������ת���ʲ���������ҳ��
     */
    function c_toPurchaseContractAdd()
    {
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('salvageId', $_SESSION['USER_ID']);
        $this->assign('salvage', $_SESSION['USERNAME']);
        $this->assign('company', $_SESSION['COM_BRN_PT']);
        $this->assign('companyName', $_SESSION['COM_BRN_CN']);
        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('deptName', $deptInfo['DEPT_NAME']);
        $this->assign('limitYears', date("Y-m-d"));

        $code = isset($_GET['code']) ? $_GET['code'] : null;
        $this->assign('code', $code);
        $purchaseContractId = isset($_GET['purchaseContractId']) ? $_GET['purchaseContractId'] : null;
        $this->assign('purchaseContractId', $purchaseContractId);

        $this->view('contract-add', true);
    }

    /**
     *
     * ��������֪ͨ���������յ�
     */
    function c_toArrivalPush()
    {

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('salvageId', $_SESSION['USER_ID']);
        $this->assign('salvage', $_SESSION['USERNAME']);
        $this->assign('company', $_SESSION['COM_BRN_PT']);
        $this->assign('companyName', $_SESSION['COM_BRN_CN']);
        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('deptName', $deptInfo['DEPT_NAME']);
        $this->assign('limitYears', date("Y-m-d"));
        $arrivalDao = new model_purchase_arrival_arrival();
        $arrivalObj = $arrivalDao->get_d($_GET['arrivalId']);
//		print_r($arrivalObj);
        $this->assign("arrivalId", $_GET['arrivalId']);
        $this->assign("purchaseContractId", $arrivalObj['purchaseId']);
        $this->assign("purchaseContractCode", $arrivalObj['purchaseCode']);
        $this->view("arrival-push", true);
    }

    /**
     * ������ת�ʲ������б���ת���ʲ���������ҳ��
     */
    function c_toRequireinAdd()
    {
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('salvageId', $_SESSION['USER_ID']);
        $this->assign('salvage', $_SESSION['USERNAME']);
        $this->assign('company', $_SESSION['COM_BRN_PT']);
        $this->assign('companyName', $_SESSION['COM_BRN_CN']);
        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
        $this->assign('deptName', $deptInfo['DEPT_NAME']);
        $this->assign('limitYears', date("Y-m-d"));

        $this->assign('requireinCode', isset($_GET['requireinCode']) ? $_GET['requireinCode'] : null);
        $this->assign('requireinId', isset($_GET['requireinId']) ? $_GET['requireinId'] : null);

        $this->view('requirein-add', true);
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['perm']) && $_GET['perm'] == 'view') {
            if (isset($_GET['viewBtn'])) {
                $this->assign('showBtn', 1);
            } else {
                $this->assign('showBtn', 0);
            }
            $this->view('view');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ��ɾ�ӱ���Ϣ����ɾ������Ϣ
     */
    function c_deletes()
    {
        $message = "";
        try {
            $Obj = $this->service->get_d($_GET['id']);
            $itemDao = new model_asset_purchase_receive_receiveItem();
            $condition = array(
                'receiveId' => $Obj['id']
            );
            $itemDao->delete($condition);
            $this->service->deletes_d($_GET['id']);
            $message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';
        } catch (Exception $e) {
            $message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
        }
        if (isset($_GET['url'])) {
            $event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
            showmsg($message, $event, 'button');
        } else if (isset($_SERVER[HTTP_REFERER])) {
            $event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
            showmsg($message, $event, 'button');
        } else {
            $this->c_page();
        }
        msg('ɾ���ɹ���');
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit();
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST[$this->objName];
        if ("audit" == $actType) {
            $object['ExaStatus'] = '���';
            $object['ExaDT'] = day_date;
        }
        $id = $this->service->add_d($object, $isAddInfo);
        if ($id) {
            if ("audit" == $actType) {
                msg('�ύ�ɹ�');
            } else {
                msg('����ɹ�');
            }
        } else {
            if ("audit" == $actType) {
                msg('�ύʧ��');
            } else {
                msg('����ʧ��');
            }
        }
    }

    /**
     * �޸Ķ������
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        if ("audit" == $actType) {
            $object['ExaStatus'] = '���';
            $object['ExaDT'] = day_date;
        }
        $id = $this->service->edit_d($object, true);
        if ($id) {
            if ("audit" == $actType) {
                msg('�ύ�ɹ�');
            } else {
                msg('�༭�ɹ�');
            }
        } else {
            if ("audit" == $actType) {
                msg('�ύʧ��');
            } else {
                msg('�༭ʧ��');
            }
        }
//		if($id) {
//			if("audit" == $actType) {
//				succ_show( 'controller/asset/purchase/receive/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
//			} else {
// 				echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
//		else{
//			if("audit" == $actType) {
//				echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
//			} else {
// 				echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
    }

    //���ݳ���
    function c_ajaxRevocation()
    {
        try {
            if ($this->service->ajaxRevocation_d($_POST['id'])) {
                echo 1;
            } else {
                echo 0;
            }
        } catch (Exception $e) {
            echo 0;
        }
    }
//		//���ݳ���
//	function c_revocateSendMail(){
//		try {
//			if($this->service->revocateSendMail_d( $_GET['id'] )){
//				echo 1;
//			}else{
//				echo 0;
//			}
//		} catch( Exception $e ) {
//			echo 0;
//		}
//	}

    /**
     * ������ת�ʲ��������յ�
     */
    function c_addByRequirein($isAddInfo = false)
    {
        $this->checkSubmit();
        $object = $_POST[$this->objName];
        $object['ExaStatus'] = '���';
        $object['ExaDT'] = day_date;
        $id = $this->service->addByRequirein_d($object, $isAddInfo);
        if ($id) {
            msg('�ύ�ɹ�');
        } else {
            msg('�ύʧ��');
        }
    }
}