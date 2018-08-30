<?php

/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单控制层
 */
class controller_finance_compensate_compensate extends controller_base_action
{

    function __construct() {
        $this->objName = "compensate";
        $this->objPath = "finance_compensate";
        parent:: __construct();
    }

    /**
     * 跳转到赔偿单列表
     */
    function c_page() {
        $this->assign('thisDate', day_date);
        $this->view('list');
    }

	/**
	 * 跳转到个人赔偿单列表
	 */
	function c_myPage() {
		$this->assign('dutyObjId',$_SESSION['USER_ID']);
		$this->view('mylist');
	}

	/**
	 * 跳转到个人赔偿信息列表
	 */
	function c_deductPage() {
		$this->view('deductlist');
	}
    
    /**
     * 获取赔偿数据
     */
    function c_businessGetDetail() {
        //源单部分策略处理
        $relClass = $this->service->getRelType($_REQUEST['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGetDetail_d($_REQUEST, $relClassM);
            $proInfoDao = new model_stock_productinfo_productinfo();
            foreach ($relObj as $k => $v){
                $proInfoArr = $proInfoDao->getProByCode($v['productNo']);
                $relObj[$k]['unitPrice'] = ($proInfoArr)? $proInfoArr['priCost'] : 0;
            }
            echo util_jsonUtil::encode($relObj);
        } else {
            exit('未配置的策略类');
        }
    }

    /**
     * 跳转到新增赔偿单页面
     */
    function c_toAdd() {
        // 赔偿主体默认值
        $dutyType = 'PCZTLX-01';

        // 源单部分策略处理
        $relClass = $this->service->getRelType($_GET['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGet_d($_GET['relDocId'], $relClassM);
            $this->assignFunc($relObj);
            $this->assign('objTypeName', $this->getDataNameByCode($relObj['objType']));
            $dutyType = isset($relObj['dutyType']) ? $relObj['dutyType'] : $dutyType;
        }
        $this->assignFunc($_GET);

        // 处理赔偿类型
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), '',array('请选择' => ''));

        $this->assign('formDate', day_date);
        $this->view('add', true);
    }

    /**
     * 新增对象
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msgRf('保存成功！');
        } else {
            msgRf('保存失败！');
        }
    }

    /**
     * 跳转到编辑赔偿单页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //源单部分策略处理
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType'],array('请选择' => ''));

        $isConfirm = isset($_GET['isConfirm']) ? $_GET['isConfirm'] : 0;//确认状态
        $this->assign('isConfirm', $isConfirm);

        $this->view('edit');
    }

    /**
     * 修改对象
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if (empty($object['isSubAudit'])) {
                msgRf('保存成功！');
            } else {
                succ_show('controller/finance/compensate/ewf_compensate.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=' . $object['formMoney'] . '&billDept=' . $object['deptId']);
            }
        }
    }

    /**
     * 跳转到查看赔偿单页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        // 合计物料净值
        $rs = $this->service->_db->getArray("SELECT SUM(price) as price,SUM(unitPrice) as unitPrice FROM oa_finance_compensate_detail WHERE mainId = {$_GET['id']} GROUP BY mainId");
        if(!empty($rs)){
            $obj['price'] = $rs[0]['price'];
            $obj['unitPrice'] = $rs[0]['unitPrice'];
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //源单部分策略处理
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->view('view');
    }

    /**
     * 跳转到审批查看赔偿单页面
     */
    function c_toViewAudit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        // 合计物料净值
        $rs = $this->service->_db->getArray("SELECT SUM(price) as price,SUM(unitPrice) as unitPrice FROM oa_finance_compensate_detail WHERE mainId = {$_GET['id']} GROUP BY mainId");
        if(!empty($rs)){
            $obj['price'] = $rs[0]['price'];
            $obj['unitPrice'] = $rs[0]['unitPrice'];
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //源单部分策略处理
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        // 处理赔偿类型
        // $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType']);
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType'],array('请选择' => ''));

        $this->view('viewaudit');
    }

    /**
     * 单据确认
     */
    function c_toConfirm() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //源单部分策略处理
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//策略实例
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType']);

        $isConfirm = isset($_GET['isConfirm']) ? $_GET['isConfirm'] : 0;//确认状态
        $this->assign('isConfirm', $isConfirm);

        $this->view('confirm');
    }

    /**
     * 单据确认
     */
    function c_confirm() {
        if ($this->service->confirm_d($_POST[$this->objName])) {
            msgRf('确认完成！');
        }
    }

    /**
     * 确认赔偿
     */
    function c_ajaxComConfirm() {
        echo $this->service->comConfirm_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 取消确认赔偿
     */
    function c_ajaxUnComConfirm() {
        echo $this->service->unComConfirm_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 经理审核
     */
    function c_ajaxAudit() {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 取消经理审核
     */
    function c_ajaxUnAudit() {
        echo $this->service->unAudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 关闭
     */
    function c_close() {
        echo $this->service->close_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 更新赔偿信息
     */
    function c_ajaxUpdateDutyInfo() {
        echo $this->service->updateDutyInfo_d($_POST['id'], $_POST['dutyType'],
            $_POST['dutyObjId'], util_jsonUtil::iconvUTF2GB($_POST['dutyObjName'])) ? 1 : 0;
    }

    /**
     * 序列号
     */
    function c_toSerialNos() {
        $this->assignFunc($_GET);
        $this->view('serialnos');
    }

    /**
     * 获取序列号
     */
    function c_getSerialNos() {
        if ($_POST['id']) {
            //如果有接收到id,直接处理序列号
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateDetailArr = $compensateDetailDao->findAll(array('mainId' => $_POST['id'], 'returnequId' => $_POST['returnequId']), null, 'serialIds,serialNos');
            $serialArr = array();
            foreach ($compensateDetailArr as $v) {
                if (empty($v['serialIds'])) continue;
                $serialIdArr = explode(',', $v['serialIds']);
                if (count($serialIdArr) == 1) {
                    array_push($serialArr, array('serialName' => $v['serialNos'], 'serialId' => $v['serialIds']));
                } else {
                    $serialNameArr = explode(',', $v['serialNos']);
                    foreach ($serialIdArr as $ik => $iv) {
                        array_push($serialArr, array('serialName' => $serialNameArr[$ik], 'serialId' => $iv));
                    }
                }
            }
            exit(util_jsonUtil::encode($serialArr));
        } else {
            //源单部分策略处理
            $relClass = $this->service->getRelType($_REQUEST['relDocType']);
            if ($relClass) {
                $relClassM = new $relClass();//策略实例
                $relObj = $this->service->getSerialNos_d($_REQUEST, $relClassM);
                $serialArr = array();
                if ($relObj['serialName']) {
                    $serialNameArr = explode(',', $relObj['serialName']);
                    $serialIdArr = explode(',', $relObj['serialId']);
                    foreach ($serialIdArr as $k => $v) {
                        array_push($serialArr, array('serialName' => $serialNameArr[$k], 'serialId' => $v));
                    }
                }
                exit(util_jsonUtil::encode($serialArr));
            } else {
                exit('undefined strategy');
            }
        }
    }

    /**
     * 审批流
     */
    function c_dealAfterAudit() {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
    
    /**
     * 打印
     */
    function c_toBatchPrintAlone(){
    	//id串
    	$ids = null;
    	if(isset($_GET['id'])&&!empty($_GET['id'])){
    		$ids = $_GET['id'];
    		$idArr = explode(',',$ids);
    	}else{
    		msg("请至少选择一张单据打印");
    	}
    
    	$this->view('batchprinthead');
    
    	foreach($idArr as $val){
    		$id = is_array($val) ? $val['id'] : $val;
    		$obj = $this->service->get_d($id);
    		foreach ($obj as $key => $val) {
    			$this->assign($key, $val);
    		}
    		$this->assign('objTypeName',$this->getDataNameByCode($obj['objType']));
    		$this->display('print-expand');
    	}
    	$this->assign('ids',$ids);
    	$this->assign('allNum',count($idArr));
    	$this->display('batchprintalone');
    }
    
    /**
     * 跳转到录入扣款页面
     */
    function c_toDeduct() {
    	$this->permCheck(); //安全校验
    	$service = $this->service;
    	$id = $_GET['id'];
    	$obj = $service->get_d($id);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$deductMoney = $service->getDeductMoney_d($id);//已录入扣款金额
    	$remaining = $obj['compensateMoney']-$deductMoney;
    	//剩余可录入扣款金额
    	$this->assign('remainingV',$remaining);
    	$this->assign('remaining',number_format($remaining,2));
    	//处理扣款方式
    	$this->showDatadicts(array('payType' => 'KKFS'));
    
    	$this->view('deduct');
    }
    
    /**
     * 录入扣款方法
     */
    function c_deduct() {
		if ($this->service->deduct_d($_POST[$this->objName])) {
			msg('录入成功！');
		} else {
			msg('录入失败！');
		}
    }
}