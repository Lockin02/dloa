<?php

/**
 * @author Show
 * @Date 2012年11月7日 星期三 19:23:17
 * @version 1.0
 * @description:项目设备申请表控制层
 */
class controller_engineering_resources_resourceapply extends controller_base_action
{

    function __construct() {
        $this->objName = "resourceapply";
        $this->objPath = "engineering_resources";
        parent:: __construct();
    }

    /**
     * 跳转到项目设备申请表列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 待确认设备申请
     */
    function c_waitConfirm() {
        $this->view('listwaitconfirm');
    }

    /**
     * 设备申请tab页
     */
    function c_toPageTab() {
        $this->view('tab');
    }

    /**
     * 跳转到项目设备申请表列表(根据项目id过滤)
     */
    function c_prolist() {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('prolist');
    }

    /**
     * 设备归还单列表
     */
    function c_proPageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 个人列表(根据项目过滤)
     */
    function c_mylist() {
        $this->view('mylist');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_myJson() {
        $service = $this->service;

        $_REQUEST['charger'] = $_SESSION['USER_ID'];
        $service->getParam($_REQUEST);

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
     * 跳转到新增项目设备申请表页面
     */
    function c_toAdd() {
        $this->assign('applyUser', $_SESSION['USERNAME']);
        $this->assign('applyUserId', $_SESSION['USER_ID']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('applyDate', day_date);
        $this->showDatadicts(array('applyType' => 'GCSBSQ'));
        $this->showDatadicts(array('getType' => 'GCSBLY'));
        //获取个人名下在借的设备数量
        $rs = $this->service->getBorrowDeviceNum($_SESSION['USER_ID']);
        $this->assign('borrowDeviceNum', $rs[0]['borrowDeviceNum']);

        $this->view('add',true);
    }

    /**
     * 新增对象操作
     */
    function c_add() {
    	$this->checkSubmit(); //检验是否重复提交
        $object = $_POST[$this->objName];
        if ($this->service->add_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('提交成功');
            } else {
                msgRf('保存成功');
            }
        }else{
        	if ($object['audit'] == "1") {
        		msgRf('提交失败');
        	} else {
        		msgRf('保存失败');
        	}
        }
    }

    /**
     * 跳转到编辑项目设备申请表页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->service->asc = false;
        $this->showDatadicts(array('applyType' => 'GCSBSQ'), $obj['applyType']);
        $this->showDatadicts(array('getType' => 'GCSBLY'), $obj['getType']);
        $this->assign('proCode', $obj['proCode']);
        //获取个人名下在借的设备数量
        $rs = $this->service->getBorrowDeviceNum($obj['applyUserId']);
        $this->assign('borrowDeviceNum', $rs[0]['borrowDeviceNum']);
        
        $this->view('edit');
    }

    /**
     * 修改对象
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('提交成功');
            } else {
                msgRf('保存成功');
            }
        }else{
        	if ($object['audit'] == "1") {     	
        		msgRf('提交失败');
        	} else {
        		msgRf('保存失败');
        	}
        }
    }

    /**
     * 设备申请确认
     */
    function c_editConfirm() {
        $object = $_POST[$this->objName];
        if ($this->service->editConfirm_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('确认成功');
            } else {
                msgRf('保存成功');
            }
        }else{
        	if ($object['audit'] == "1") {     	
        		msgRf('确认失败');
        	} else {
        		msgRf('保存失败');
        	}
        }
    }

    /**
     * 跳转到查看项目设备申请表页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * 跳转到查看项目设备申请表页面
     */
    function c_toAudit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('viewaudit');
    }

    /**
     * 确认界面
     */
    function c_toEditCheck() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->showDatadicts(array('applyType' => 'GCSBSQ'), $obj['applyType']);
        $this->showDatadicts(array('getType' => 'GCSBLY'), $obj['getType']);

        $this->view('editcheck');
    }

    /**
     * 物料确认
     */
    function c_toConfirmDetail() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('confirmdetail');
    }

    /**
     * 物料确认
     */
    function c_confirmDetail() {
        if ($this->service->confirmDetail_d($_POST[$this->objName])) {
            msgRf('确认成功');
        }
    }

    /**
     * 提交单据确认
     */
    function c_ajaxConfirmStatus() {
        $id = $_POST['id'];
        $confirmStatus = $_POST['confirmStatus'];
        if ($this->service->confirmStatus_d($id, $confirmStatus)) {
            $this->service->sendDefaultEmail_d($id);//发送默认发送人邮件
            //记录操作日志
            $logDao = new model_engineering_baseinfo_resourceapplylog();
            $logDao->addLog_d($id, '提交申请');

            echo 1;
        }
    }

    /**
     * 验证本部门是否是
     */
    function c_checkIsEsmDept() {
        $deptId = $_POST['deptId'];
        include(WEB_TOR . 'includes/config.php');
        $defaultEsmDept = isset($defaultEsmDept) ? array_keys($defaultEsmDept) : array();
        if (!in_array($deptId, $defaultEsmDept)) {
            $defaultEsmResourceDept = isset($defaultEsmResourceDept) ? array_keys($defaultEsmResourceDept) : array();
            echo array_pop($defaultEsmResourceDept);
        }
        exit();
    }

    /**
     * 撤回检查(弃用)
     */
    function c_checkBack() {
        if ($this->service->update(array('id' => $_POST['id']), array('confirmStatus' => 0))) {
            //记录操作日志
            $logDao = new model_engineering_baseinfo_resourceapplylog();
            $logDao->addLog_d($_POST['id'], '撤回检查');
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 打回
     */
    function c_applyBack() {
        if ($this->service->applyBack_d($_POST['id'])) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    /**
     * 跳转到确认发货任务分配数量
     */
    function c_toConfirmTaskNum() {
    	$this->permCheck(); //安全校验
    	$obj = $this->service->get_d($_GET['id']);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$this->view('confirmTaskNum');
    }
    
    /**
     * 确认发货任务分配数量
     */
    function c_confirmTaskNum() {
    	if ($this->service->confirmTaskNum_d($_POST[$this->objName])) {
    		msgRf('确认成功！');
    	} else {
    		msgRf('确认失败！');
    	}
    }
    
    /**
     * 转到撤回确认页面
     */
    function c_toConfirmBack() {
    	$this->permCheck(); //安全校验
    	$obj = $this->service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    	$this->view('confirmback');
    }
    
    /**
     * 撤回确认
     */
    function c_confirmBack() {
    	if ($this->service->confirmBack_d($_POST[$this->objName])) {
    		msgRf('确认成功');
    	}
    }
    /*************************  审批完成后跳转处理 *************************/

    /**
     * 审批完成后跳转处理
     */
    function c_dealAfterAudit() {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
}