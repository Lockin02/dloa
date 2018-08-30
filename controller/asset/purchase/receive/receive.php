<?php

/**
 *
 * 资产验收控制层类
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
     * 跳转到资产验收
     */
    function c_page()
    {
        $this->view("list");
    }

    /*
     * 跳转到资产验收
     */
    function c_myPage()
    {
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view("mylist");
    }

    /*
     * 跳转到资产验收
     */
    function c_pageByApply()
    {
        $applyId = isset($_GET['applyId']) ? $_GET['applyId'] : 0;
        $this->assign('applyId', $applyId);
        $this->view("listbyapply");
    }

    /**
     * 跳转到新增页面
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
     * 由采购需求跳转到资产验收新增页面
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
     * 由资产采购订单跳转到资产验收新增页面
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
     * 根据收料通知单下推验收单
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
     * 由物料转资产申请列表跳转到资产验收新增页面
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
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
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
     * 先删从表信息，再删主表信息
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
            $message = '<div style="color:red" align="center">删除成功!</div>';
        } catch (Exception $e) {
            $message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
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
        msg('删除成功！');
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit();
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST[$this->objName];
        if ("audit" == $actType) {
            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = day_date;
        }
        $id = $this->service->add_d($object, $isAddInfo);
        if ($id) {
            if ("audit" == $actType) {
                msg('提交成功');
            } else {
                msg('保存成功');
            }
        } else {
            if ("audit" == $actType) {
                msg('提交失败');
            } else {
                msg('保存失败');
            }
        }
    }

    /**
     * 修改对象操作
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        if ("audit" == $actType) {
            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = day_date;
        }
        $id = $this->service->edit_d($object, true);
        if ($id) {
            if ("audit" == $actType) {
                msg('提交成功');
            } else {
                msg('编辑成功');
            }
        } else {
            if ("audit" == $actType) {
                msg('提交失败');
            } else {
                msg('编辑失败');
            }
        }
//		if($id) {
//			if("audit" == $actType) {
//				succ_show( 'controller/asset/purchase/receive/ewf_index.php?actTo=ewfSelect&billId='.$object['id']);
//			} else {
// 				echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
//		else{
//			if("audit" == $actType) {
//				echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
//			} else {
// 				echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
//			}
//		}
    }

    //单据撤销
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
//		//单据撤销
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
     * 由物料转资产新增验收单
     */
    function c_addByRequirein($isAddInfo = false)
    {
        $this->checkSubmit();
        $object = $_POST[$this->objName];
        $object['ExaStatus'] = '完成';
        $object['ExaDT'] = day_date;
        $id = $this->service->addByRequirein_d($object, $isAddInfo);
        if ($id) {
            msg('提交成功');
        } else {
            msg('提交失败');
        }
    }
}