<?php

/**
 * @author Show
 * @Date 2011年5月8日 星期日 13:55:05
 * @version 1.0
 * @description:付款申请(新)控制层
 */
class controller_finance_payablesapply_payablesapply extends controller_base_action
{

    private $unDeptExtFilter = "";// PMS377 此模块需要单独隐藏的部门选项
    function __construct() {
        $this->objName = "payablesapply";
        $this->objPath = "finance_payablesapply";
        parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");

        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        $this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
    }

    /**
     * 跳转到付款申请(新)
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到付款申请（已线下支付）
     */
    function c_pageEntrust() {
        $this->view('listEntrust');
    }

    /**
     * 财务付款申请列表 - 暂时替代pagejson(用于列表)
     */
    function c_pageJsonList() {
        $service = $this->service;

        if ($_REQUEST['status'] == 'FKSQD-03') {
            $_REQUEST['formDateBegin'] = isset($_REQUEST['formDateBegin']) ? $_REQUEST['formDateBegin'] : day_date;
            $_REQUEST['formDateEnd'] = isset($_REQUEST['formDateEnd']) ? $_REQUEST['formDateEnd'] : day_date;
        }
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        if (!empty($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);
            $newRow = array();

            //加入财务计算准则 - 已付款时才生效
            if ($_REQUEST['status'] == 'FKSQD-03') {
                $mark = "";
                $dCountArr = array('id' => 'noId', 'payMoney' => 0, 'payedMoney' => 0, 'shareMoney' => 0,
					'payMoneyCur' => 0);
                foreach ($rows as $val) {
                    if (!empty($mark) && $mark != $val['lastPrintTime']) {
                        $dCountArr['formNo'] = $mark;
                        $newRow[] = $dCountArr;
                        $dCountArr = array('id' => 'noId', 'payMoney' => 0, 'payedMoney' => 0, 'shareMoney' => 0,
							'payMoneyCur' => 0);
                    }
                    $newRow[] = $val;

					// 币种处理
					if ($val['currencyCode'] == 'CNY') {
						$dCountArr['payMoneyCur'] = bcadd($dCountArr['payMoneyCur'], $val['payMoneyCur'], 2);
					} else {
						$dCountArr['payMoney'] = bcadd($dCountArr['payMoney'], $val['payMoney'], 2);
					}
                    $dCountArr['payedMoney'] = bcadd($dCountArr['payedMoney'], $val['payedMoney'], 2);
                    $dCountArr['shareMoney'] = bcadd($dCountArr['shareMoney'], $val['shareMoney'], 2);
                    if ($mark != $val['lastPrintTime']) {
                        $mark = $val['lastPrintTime'];
                    }
                }
                $dCountArr['formNo'] = $mark;
                $newRow[] = $dCountArr;
            }

            //总计栏加载
            $objArr = $service->listBySqlId('count_allnew');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '合计';
                $rsArr['id'] = 'noId';
            }

            if (empty($newRow)) {
                $rows[] = $rsArr;
            } else {
                $newRow[] = $rsArr;
                $rows = $newRow;
            }
        }
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 退款申请列表
     */
    function c_pageForRefund() {
        $this->view('listforrefund');
    }

    /**
     * 付款申请预览页面
     */
    function c_pageForRead() {
        $this->view('list-forread');
    }

    /**
     * 付款申请预览
     */
    function c_pageJsonForRead() {
        $service = $this->service;
        $rows = array();

        $service->getParam($_REQUEST);

        $deptLimit = isset($this->service->this_limit['部门权限']) ? $this->service->this_limit['部门权限'] : null;
        if (strstr($deptLimit, ';;')) {
            $rows = $service->page_d();
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //总计栏加载
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '合计';
                $rsArr['id'] = 'noId';
                $rows[] = $rsArr;
            }
        } else if (!empty($deptLimit)) {
            $service->searchArr['deptIds'] = $deptLimit;
            $rows = $service->page_d();
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            //总计栏加载
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '合计';
                $rsArr['id'] = 'noId';
                $rows[] = $rsArr;
            }
        }
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 针对采购订单的付款申请页面
     */
    function c_toAddInPurcont() {
        $rs = $this->service->getContractinfoById_d($_GET['id']);
        if ($rs['allMoney'] <= $rs['payablesapplyMoney']) {
            msg('已达申请上限');
        }
        $detail = $this->service->initPayApplyDetail_d(array(0 => $rs));
        $rs['detail'] = $detail[0];
        $rs['coutNumb'] = $detail[1];
        $this->assignFunc($rs);

        $this->assign('paymentCondition', $this->getDataNameByCode($rs['paymentCondition']));
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->assign('formDate', day_date);

        $this->display('addinpurcont');
    }

    /**
     * 针对采购订单的付款申请页面
     */
    function c_addInPurcont() {
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            if ($_GET['act']) {
                succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $_POST[$this->objName]['feeDeptId'] . $this->service->tbl_name . '&flowMoney=' . $_POST[$this->objName]['payMoney']);
            } else {
                msgRf('保存成功');
            }
        } else {
            msg('保存失败');
        }
    }

    /**
     * 付款申请 重写
     */
    function c_toAddforObjType() {
        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($_GET['payFor']) ? $_GET['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($_GET['objType']);
        $initObj = new $newClass();
        //获取对应业务信息
        $rs = $this->service->getObjInfo_d($_GET, $initObj);

        // 采购订单币种处理
        if(in_array($_GET['objType'], array("YFRK-01", "YFRK-02"))){
        	$currency = "";
        	$currencyCode = "";
        	$rate = "";
        	foreach ($rs['detail'] as $k => $v){
        		$v['currency'] = empty($v['currency']) ? "人民币" : $v['currency'];
        		$v['currencyCode'] = empty($v['currencyCode']) ? "CNY" : $v['currencyCode'];
        		$v['rate'] = empty($v['rate']) || $v['rate'] * 1 == "0" ? "1" : $v['rate'];
        		if($k == 0){
        			$currency = $v['currency'];
        			$currencyCode = $v['currencyCode'];
        			$rate = $v['rate'];
        		}else if($currency != $v['currency'] || $rate != $v['rate']){
        			msg("不同付款币种或汇率的采购订单不能合并付款");
        		}
        	}
        }
        //如果是单下推，则进入单下推渲染页面
        if (isset($_GET['addType'])) {
            $initRs = $this->service->initAddOne_d($rs, $initObj, $payFor);
            if (!$initRs[2]) {
                msgRf('付款申请已达上限，不能继续申请');
                exit;
            }
            $this->assign('canApplyAll', $initRs[2]);
        } else {
            $initRs = $this->service->initAdd_d($rs, $initObj, $payFor);
            $this->assign('canApplyAll', $initRs[2]);
        }

        if (!$initRs[1]) {
            msgRf('付款申请已达上限，不能继续申请');
            exit;
        }

        $rs['detail'] = $initRs[0];
        $rs['coutNumb'] = $initRs[1];

        //渲染主表单
        $this->assignFunc($rs);

        //查询该源单的最近银行 - 帐号 - 汇入地点-是否开据发票信息
        if($_GET['objType'] == 'YFRK-06'){
            $countInfo = $this->service->find(array('sourceType' => $_GET['objType'], 'supplierId' => $rs['signCompanyId']),
                'id desc', 'bank,account,place,isInvoice');
        }else{
            $sourceCode = $_GET['objType'] == 'YFRK-01' ? $rs['hwapplyNumb'] : $rs['objCode'];
            $countInfo = $this->service->find(array('sourceType' => $_GET['objType'], 'sourceCode' => $sourceCode),
                'id desc', 'bank,account,place,isInvoice');
        }

        if (empty($countInfo)) {
            $countInfo = array('bank' => '', 'account' => '', 'place' => '');
        }

        //渲染关联信息
        $this->assignFunc($countInfo);
        $this->assignFunc($_GET);

        //页面其他渲染
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, true);//付款业务类型

        $this->assign('formDate', day_date);//日期
        $this->assign('userId', $_SESSION['USER_ID']);//传递当前用户id

        $type = $this->service->getBusinessCode($_GET['objType']);
        if ($type == 'outaccount') {
            //获取归属公司名称
            $this->assign('formBelong', $_SESSION['USER_COM']);
            $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
            $this->assign('businessBelong', $_SESSION['USER_COM']);
            $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
            if($type == "purcontract"){// 采购订单币种处理
            	$this->assign('currency', $currency);
            	$this->assign('currencyCode', $currencyCode);
            	$this->assign('rate', $rate);
            }
        }else if($type == "other"){
            //付款业务类型
            $this->assign('payForBusiness',$rs['payForBusiness']);
            $this->assign('payForBusinessName',$rs['payForBusinessName']);
        }

        $this->assign('periodStr', $this->periodDeal());

        $this->view($type . '-add' . $keyWork);
    }

    /**
     * 付款申请 - 独立新增
     */
    function c_toAddDept() {
        $thisObj = $_GET;

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($_GET['payFor']) ? $_GET['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));

        //渲染关联信息
        $this->assignFunc($thisObj);
        $this->assign('sourceTypeCN', $this->getDataNameByCode($thisObj['sourceType']));

        //页面其他渲染
        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->assign('formDate', day_date);

        //设置默认信息
        $this->assign('salesmanId', $_SESSION['USER_ID']);
        $this->assign('salesman', $_SESSION['USERNAME']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->display($this->service->getBusinessCode($thisObj['sourceType']) . '-adddept');
    }

    /**
     * 多选新增踏板
     */
    function c_toAddPedal() {
        $thisObj = $_GET;
        isset($thisObj['owner']) ? $this->assign('sendUserId', $_SESSION['USER_ID']) : $this->assign('sendUserId', "");
        $this->display($this->service->getBusinessCode($thisObj['sourceType']) . '-addpedal');
    }

    /**
     * 重写toAdd
     */
    function c_toAdd() {
        //策略调用新增页面//策略调用新增页面

        $this->showDatadicts(array('payType' => 'CWFKFS'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->assign('formDate', day_date);

        $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
        $this->assign('owner', $owner);

        $this->assign('createId', $_SESSION['USER_ID']);
        $this->assign('createName', $_SESSION['USERNAME']);

        $this->display('add');
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false) {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($object);
        if ($id) {
            if (isset($_GET['act'])) {
                if ($object['sourceType'] == 'YFRK-01') {  //采购订单
                    if ($object['payFor'] != 'FKLX-03') {
                        succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } //add chenrf 20130506
                elseif ($object['sourceType'] == 'YFRK-02') {  //其它合同
                    if ($object['payFor'] != 'FKLX-03') {
                        //付款申请
                        succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } else {
                    succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                        '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                        '&billDept=' . $object['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('保存成功');
            }
        } else {
            msgRf('保存失败');
        }
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = false) {
        //		$this->permCheck (); //安全校验
        $object = $_POST[$this->objName];
        $id = $this->service->edit_d($object);
        if ($id) {
            if (isset($_GET['act'])) {
                if ($object['sourceType'] == 'YFRK-01') {//采购订单
                    if ($object['payFor'] != 'FKLX-03') {
                        succ_show('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } //add chenrf 20130506
                elseif ($object['sourceType'] == 'YFRK-02') {  //其它合同
                    if ($object['payFor'] != 'FKLX-03') {
                        //付款申请
                        succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    } else {
                        succ_show('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                            '&billCompany=' . $object['businessBelong']);
                    }
                } else {
                    succ_show('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'] .
                        '&billDept=' . $_POST[$this->objName]['feeDeptId'] . '&flowMoney=' . $object['payMoney'] .
                        '&billDept=' . $object['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
                }
            } else {
                msgRf('编辑成功');
            }
        } else {
            msgRf('编辑失败');
        }
    }

    /**
     * 重写c_init
     */
    function c_init() {
        //URL权限控制
        $this->permCheck();
        $perm = isset($_GET['perm']) ? $_GET['perm'] : 'edit';
        $obj = $this->service->get_d($_GET['id'], 'detail', $perm);

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        if ($perm == 'view') {
            if (!empty($obj['sourceType'])) {
                $this->c_toView($_GET['id']);
                exit;
            }

            $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
            $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
            $this->assign('detail', $detailObj);
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false));
            $this->display('view');
        } else {
            $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
            $this->assign('owner', $owner);
            $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
            $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor']);
            $this->assign('detail', $detailObj[0]);
            $this->assign('coutNumb', $detailObj[1]);
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], true));
            $this->display('edit');
        }
    }

    /**
     * 付款申请查看页面
     */
    function c_toView() {
        //URL权限控制
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);
        if($initRs['payForBusiness'] == '' && $initRs['exaCode'] == 'oa_sale_other'){
            $otherObj = $this->service->get_one("select payForBusiness from oa_sale_other where id = '{$initRs['exaId']};'");
            $initRs['payForBusiness'] = (isset($otherObj['payForBusiness']))? $otherObj['payForBusiness'] : '';
        }
        $this->assignFunc($initRs);
        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('payForBusiness', $this->getDataNameByCode($initRs['payForBusiness']));//付款业务类型
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //处理扩展审批流
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        // 如果是采购订单则计算出它的最后已审核的入库日期
        if ($object['sourceType'] == 'YFRK-01') {
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            // 从入库单获取
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $this->assign('entryDate', $entryDate);
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        //开据发票
        $this->assign('isInvoice', $this->service->rtYesNo_d($object['isInvoice']));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-view' . $keyWork);
    }

    /**
     * 付款申请查看页面 - 财务查看（简化页面）
     */
    function c_toViewSimple() {
        //URL权限控制
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        if ($object['isPay'] == 1)
            $this->assign('isPay', '是');
        else
            $this->assign('isPay', '否');

        //处理扩展审批流
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-viewsimple' . $keyWork);
    }

    /**
     * 付款申请查看页面
     */
    function c_toEdit() {
        //URL权限控制
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');
        // 如果是采购订单则计算出它的最后已审核的入库日期
        if ($object['sourceType'] == 'YFRK-01') {
            // 获取入库日期
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $object['entryDate'] = $entryDate;
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initEdit_d($object, $initObj, $payFor);

        $object['detail'] = $initRs[0];
        $object['coutNumb'] = $initRs[1];

        $this->assignFunc($object);

        $this->showDatadicts(array('payType' => 'CWFKFS'), $object['payType']);
        $this->showDatadicts(array('payForBusiness' => 'FKYWLX'), $object['payForBusiness'], true);

        $this->assign('sourceTypeCN', $this->getDataNameByCode($object['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, true));

        $this->assign('periodStr', $this->periodDeal($object['period']));

        $payForBusinessName = '';
        if($this->service->getBusinessCode($object['sourceType']) == "other"){
            $payForBusinessName = $this->getDataNameByCode($object['payForBusiness']);
        }
        $this->assign('payForBusiness',$payForBusinessName);

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-edit' . $keyWork);
    }

    /**
     * 付款申请审批查看页面
     */
    function c_toViewAudit($id = null) {
        $id = empty($id) ? $_GET['id'] : $id;

        //URL权限控制
        $this->permCheck();

        $object = $this->service->get_d($id, 'clear');

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initAudit_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        // 如果是采购订单则计算出它的最后已审核的入库日期
        if ($object['sourceType'] == 'YFRK-01') {
            $purOrderIds = array();
            if(!empty($object['detail'])){
                foreach ($object['detail'] as $objArr){
                    $purOrderIds[] = $objArr['objId'];
                }
            }
            // 从入库单获取
            $stockInDao = new model_stock_instock_stockin();
            $entryDate = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));
            $this->assign('entryDate', $entryDate);
            // $this->assign('entryDate', $this->service->getEntryDate_d($id));
        }

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //开据发票
        $this->assign('isInvoice', $this->service->rtYesNo_d($object['isInvoice']));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-viewaudit' . $keyWork);
    }

    /**
     * 打印页面
     */
    function c_toPrint() {

        //URL权限控制
        $this->permCheck();

        $id = $_GET['id'];

        $object = $this->service->get_d($id, 'clear');

        if (empty($object['sourceType'])) {
            $this->c_print();
            exit;
        }

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initPrint_d($object, $initObj);
        //		print_r($initRs);

        $this->assignFunc($initRs);

        //项目编号
        $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

        //处理扩展审批流
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }

        $this->assign('todayStr', date("Y-m-d"));

        $this->display($this->service->getBusinessCode($object['sourceType']) . '-print' . $keyWork);
    }

    /**
     * 批量打印
     */
    function c_toBatchPrint() {
        //id串
        $ids = null;
        //新id数组
        $newIdArr = array();

        if (isset($_GET['id'])) {
            $ids = $_GET['id'];
            $idArr = explode(',', $ids);
        } else {
            $idArr = $this->service->getPayablesapplyCanPay_d();
        }

        $this->display('batchprinthead');

        //合计金额
        $allMoney = 0;

        foreach ($idArr as $key => $val) {
            $id = is_array($val) ? $val['id'] : $val;

            if(in_array($id,$newIdArr)){
                continue;
            }

            if (empty($ids)) {
                array_push($newIdArr, $id);
            }

            $object = $this->service->get_d($id, 'clear');

            //计算金额
            $allMoney = bcAdd($allMoney, $object['payMoney'], 2);

            //单据状态判断
            if ($object['status'] != 'FKSQD-01') {
                msgRf('单据[' . $id . '] 状态为：[' . $this->getDataNameByCode($object['status']) . '] 不能进行付款，如需打印，请选择单据后，点击独立打印功能');
                die();
            }

            //付款类型定义
            $payForTypes = array_keys($this->service->payForArr);
            $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
            $this->assign('payFor', $payFor);
            $this->assign('payForCN', $this->getDataNameByCode($payFor));
            $keyWork = $this->service->payForArr[$payFor];

            //调用策略
            $newClass = $this->service->getClass($object['sourceType']);
            $initObj = new $newClass();

            $initRs = $this->service->initPrint_d($object, $initObj);

            //归属公司调整,展示公司全名
            $branchDao = new model_deptuser_branch_branch();
            $branchObj = $branchDao->find(array("NamePT" => $initRs['businessBelong']), null, 'fullname');
            $initRs['fullName'] = $branchObj['fullname'];

            $this->assignFunc($initRs);

            //项目编号
            $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

            $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

            //处理扩展审批流
            if (empty($object['exaCode'])) {
                $this->assign('exaId', $id);
                $this->assign('exaCode', $this->service->tbl_name);
            }

            $this->display($this->service->getBusinessCode($object['sourceType']) . '-print-expand' . $keyWork);
        }
        if (empty($ids)) {
            $ids = implode($newIdArr, ',');
        }
        $this->assign('ids', $ids);
        $this->assign('allNum', count($idArr));
        $this->assign('allMoney', $allMoney);
        $this->assign('todayStr', date("Y-m-d"));
        $this->display('batchprint');
    }

    /**
     * 批量打印 - 只用于打印
     */
    function c_toBatchPrintAlong() {
        //id串
        $ids = null;
        //新id数组
        $newIdArr = array();

        if (isset($_GET['id'])) {
            $ids = $_GET['id'];
            $idArr = explode(',', $ids);
        } else {
            $idArr = $this->service->getPayablesapplyCanPay_d();
        }

        $this->display('batchprinthead');

        //合计金额
        $allMoney = 0;

        foreach ($idArr as $key => $val) {
            $id = is_array($val) ? $val['id'] : $val;

            if (empty($ids)) {
                array_push($newIdArr, $id);
            }

            $object = $this->service->get_d($id, 'clear');
            //计算金额
            $allMoney = bcAdd($allMoney, $object['payMoney'], 2);

            //付款类型定义
            $payForTypes = array_keys($this->service->payForArr);
            $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
            $this->assign('payFor', $payFor);
            $this->assign('payForCN', $this->getDataNameByCode($payFor));
            $keyWork = $this->service->payForArr[$payFor];

            //调用策略
            $newClass = $this->service->getClass($object['sourceType']);
            $initObj = new $newClass();
            $initRs = $this->service->initPrint_d($object, $initObj);

            //归属公司调整,展示公司全名
            $branchDao = new model_deptuser_branch_branch();
            $branchObj = $branchDao->find(array("NamePT" => $initRs['businessBelong']), null, 'fullname');
            $initRs['fullName'] = $branchObj['fullname'];

            $this->assignFunc($initRs);
            //项目编号
			$arr=explode('-',$object['detail'][0]['orgFormType']);
			$orgFormType=$arr[0];
			$this->assign("orgFormType", $orgFormType);
            $this->assign('orgFormType', $object['detail'][0]['orgFormType']);

            $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
            $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
            $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));

            //处理扩展审批流
            if (empty($object['exaCode'])) {
                $this->assign('exaId', $id);
                $this->assign('exaCode', $this->service->tbl_name);
            }
            $this->display($this->service->getBusinessCode($object['sourceType']) . '-print-expand' . $keyWork);
        }
        if (empty($ids)) {
            $ids = implode($newIdArr, ',');
        }
        $this->assign('ids', $ids);
        $this->assign('allNum', count($idArr));
        $this->assign('allMoney', $allMoney);
        $this->assign('todayStr', date("Y-m-d"));
        $this->display('batchprintalong');
    }

    /**
     * 打回编辑 - 已启用
     */
    function c_toReEdit() {
        //URL权限控制
        $this->permCheck();
        $obj = $this->service->get_d($_GET['id'], 'detail', 'edit');

        $detailObj = $obj['detail'];
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);
        $owner = isset($_GET['owner']) ? $_GET['owner'] : null;
        $this->assign('owner', $owner);
        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);
        $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor']);
        $this->assign('detail', $detailObj[0]);
        $this->assign('coutNumb', $detailObj[1]);
        $this->display('reedit');
    }

    /**
     * 审批时查看付款申请页面
     */
    function c_initAuditing() {
        $id = $_GET['id'];

        $this->permCheck($id);
        $orgObj = $this->service->find(array('id' => $id), null, 'sourceType');
        if (!empty($orgObj['sourceType'])) {
            $this->c_toViewAudit($id);
            exit;
        }

        $obj = $this->service->getAuditing_d($id, 'detail', 'view');

        $detailObj = $obj['detail'];
        unset($obj['detail']);


        //渲染主表数据
        $this->assignFunc($obj);

        $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
        $this->assign('detail', $detailObj);
        $this->assign('file', $this->service->getFilesByObjId($id, false));
        $this->display('viewauditing');
    }

    /**
     * 我的付款申请
     */
    function c_toMyApply() {
        $this->display('myapply-list');
    }

    /**
     * 我的付款申请json
     */
    function c_myApplyJson() {
        $service = $this->service;
        $service->setCompany(0);//不需要过滤公司
        $service->getParam($_POST); //设置前台获取的参数信息
        $this->service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_default');
        foreach ($rows as $k => $v){
            $rows[$k]['printId'] = $rows[$k]['id'];
        }
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 跳转到关闭页面
     */
    function c_toClose() {
        $id = $_GET['id'];
        $this->permCheck($id);

        $obj = $this->service->get_d($id);
        //渲染主表数据
        $this->assignFunc($obj);
        $this->assign('thisDate', day_date);

        $payMailType = $this->service->getMailType_d($obj['sourceType']);
        //获取默认发送人
        $rs = $this->service->getSendMen_d($payMailType);
        $this->assignFunc($rs);

        // 设置关闭人
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $this->display('close');
    }

    /**
     * 关闭方法
     */
    function c_close() {
        if ($this->service->close_d($_POST[$this->objName])) {
            msg('关闭成功');
        } else {
            msg('关闭失败');
        }
    }

    /**
     * 跳转到批量关闭页面
     */
    function c_toBatchClose() {
        //设置付款申请id,关闭日期及关闭人
        $this->assign('ids', $_GET['id']);
        $this->assign('thisDate', day_date);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('userName', $_SESSION['USERNAME']);

        $this->display('batchclose');
    }

    /**
     * 批量关闭方法
     */
    function c_batchClose() {
        if ($this->service->batchClose_d($_POST[$this->objName])) {
            msg('关闭成功');
        } else {
            msg('关闭失败');
        }
    }
    /********************************各类型付款申请列表*****************************/

    /**
     * 源单类型付款申请列表
     */
    function c_mySourceTypeList() {
        $sourceType = isset($_GET['sourceType']) ? $_GET['sourceType'] : 'YFRK-01';
        $this->assign('sourceType', $sourceType);
        $this->display($this->service->getBusinessCode($sourceType) . '-mysourcetypelist');
    }

    /********************审批部分************************/

    /**
     * 审批页面tab
     */
    function c_auditTab() {
        $this->display('audittab');
    }

    /**
     * 待审批的付款申请
     */
    function c_auditundo() {
        $this->display('auditundo');
    }

    /**
     *  待审批付款申请json
     */
    function c_auditundoJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $rows = $service->pageBySqlId('select_auditing');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 已审批的付款申请
     */
    function c_auditdone() {
        $this->display('auditdone');
    }

    /**
     * 已审批的付款申请json
     */
    function c_auditdoneJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['findInName'] = $_SESSION['USER_ID'];
        $service->searchArr['workFlowCode'] = $service->tbl_name;
        $rows = $service->pageBySqlId('select_audited');
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 付款申请历史
     */
    function c_toHistory() {
        $obj = $_GET['obj'];
        $this->permCheck($obj['objId'], 'purchase_contract_purchasecontract');
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('skey', $_GET['skey']);
        $this->assignFunc($obj);
        $this->display('history');
    }

    /**
     * 付款申请历史json
     */
    function c_historyJson() {
        $service = $this->service;
        $service->setCompany(0);//查看付款申请历史，不需要过滤公司
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->groupBy = 'c.id,d.objId,d.objType';
        $rows = $service->pageBySqlId('select_history');

        if (!empty($rows)) {
            //数据加入安全码
            $rows = $this->sconfig->md5Rows($rows);

            $rsArr = array('payMoney' => 0, 'payedMoney' => 0);
            $rsArr['formNo'] = '选择合计';
            $rsArr['id'] = 'noId2';
            $rows[] = $rsArr;

            //总计栏加载
            $service->groupBy = 'd.objId,d.objType';
            $objArr = $service->listBySqlId('select_historycount');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['formNo'] = '合计';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 付款申请历史
     */
    function c_toHistoryForObj() {
        $objId = isset($_GET['obj']['objId']) ? $_GET['obj']['objId'] : '';
        $objIds = isset($_GET['obj']['objIds']) ? $_GET['obj']['objIds'] : '';
        if (empty($objId) && empty($objIds)) {
            exit('没有传入相关参数');
        } else {
            $this->assign('userId', $_SESSION['USER_ID']);
            $this->assign('objId', $objId);
            $this->assign('objIds', $objIds);
            $this->assign('objType', $_GET['obj']['objType']);
            $this->display('historyforobj');
        }
    }

    /**
     * 付款申请打印
     */
    function c_print() {
        //URL权限控制
        $this->permCheck();
        $obj = $this->service->get_d($_GET['id'], 'detail');

        $detailObj = $this->service->detailDeald_d($obj['detail']);
        unset($obj['detail']);

        //渲染主表数据
        $this->assignFunc($obj);

        $this->assign('supplierSkey', $this->md5Row($obj['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($obj['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($obj['payFor']));
        $this->assign('detail', $detailObj);
        $this->display('print');
    }

    /**
     * 增加已打印数量
     */
    function c_changePrintCount() {
        $printTimes = empty($_POST['printTimes']) ? $_POST['printTimes'] : 1;
        echo $this->service->changePrintCount_d($_POST['id'], $printTimes);
    }

    /**
     * 增加已打印数量 - 多id
     */
    function c_changePrintCountIds() {
        $printTimes = !empty($_POST['printTimes']) ? $_POST['printTimes'] : 1;
        echo $this->service->changePrintCountIds_d($_POST['ids'], $printTimes);
    }

    /**
     * 列表高级查询
     */
    function c_toSearch() {
        $this->showDatadicts(array('sourceType' => 'YFRK'));
        $this->showDatadicts(array('payFor' => 'FKLX'));
        $this->view('search');
    }

    /**
     * 审批完成后业务处理
     */
    function c_dealAfterAudit() {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 添加一个独立上传附件的功能
     */
    function c_toUploadFile() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
        $this->assignFunc($obj);

        $this->view('uploadfile');
    }

    /**
     * 提交财务支付操作  - 目前用于个人列表中的提交财务支付
     */
    function c_handUpPay() {
        echo $this->service->handUpPay_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 跳转到提交财务确认页面
     */
    function c_toConfirm() {
        //默认发送人
        $this->assign('id', $_GET['id']);
        $obj = $this->service->find(array('id' => $_GET['id']), null, 'businessBelong,isEntrust');
        $this->assignFunc($obj);
        $mailUser = $this->service->getMailUser_d('handUpPayMail', true, $obj['businessBelong']);
        $this->assign('defaultUserName', $mailUser['defaultUserName']);
        $this->assign('defaultUserId', $mailUser['defaultUserId']);
        $this->assign('supplierName', $_GET['supplierName']);
        $this->assign('payMoney', $_GET['payMoney']);
        $this->view('confirm');
    }

    /**
     * 提交财务支付操作(新)
     */
    function c_handUpPay2() {
        if ($this->service->handUpPay2_d($_POST[$this->objName])) {
            msg("提交成功");
        } else {
            msg("提交失败");
        }
    }

    /******************** S 导入导出系列 ********************/
    /**
     *  导出列表
     */
    function c_excelOut() {
        set_time_limit(0);
        $service = $this->service;

        if ($_GET['status'] == 'FKSQD-03') {
            $_GET['formDateBegin'] = isset($_GET['formDateBegin']) ? $_GET['formDateBegin'] : day_date;
            $_GET['formDateEnd'] = isset($_GET['formDateEnd']) ? $_GET['formDateEnd'] : day_date;
            $_GET['isEntrust'] = 0;
        }

        //构建其余部分查询条件
        $service->getParam($_GET);

        //		print_r($service->searchArr);

        $service->sort = 'c.actPayDate DESC,c.lastPrintTime';
        $rows = $service->list_d('select_excel2');

        if (!empty($rows)) {
            //总计栏加载
            $objArr = $service->listBySqlId('count_allnew');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['id'] = '合计';
                $rows[] = $rsArr;
            }
        }
        return model_finance_common_financeExcelUtil::exportPayablesapply($rows);
    }

    /**
     *  导出列表
     */
    function c_excelDetail() {
        set_time_limit(0);
        $service = $this->service;

        $outType = isset($_GET['outType']) ? $_GET['outType'] : '07';

        //构建其余部分查询条件
        $service->getParam($_GET);
        $service->sort = 'c.formDate';
        $service->asc = false;
        $rows = $service->list_d('select_excel');

        if (!empty($rows)) {
            //总计栏加载
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['id'] = '合计';
                $rows[] = $rsArr;
            }
        }
        if ($outType == '07') {
            return model_finance_common_financeExcelUtil::exportPayApplyDetail07($rows);
        } else {
            return model_finance_common_financeExcelUtil::exportPayApplyDetail($rows);
        }
    }

    /******************** E 导入导出系列 ********************/

    //add chenrf 20130504
    /**
     * 审批时查看退款申请页面
     */
    function c_initBack() {
        //URL权限控制
        $this->permCheck();

        $id = $_GET['id'];
        $object = $this->service->get_d($id, 'clear');

        //付款类型定义
        $payForTypes = array_keys($this->service->payForArr);
        $payFor = isset($object['payFor']) ? $object['payFor'] : $payForTypes[0];
        $this->assign('payFor', $payFor);
        $this->assign('payForCN', $this->getDataNameByCode($payFor));
        $keyWork = $this->service->payForArr[$payFor];

        //调用策略
        $newClass = $this->service->getClass($object['sourceType']);
        $initObj = new $newClass();

        $initRs = $this->service->initView_d($object, $initObj, $payFor);

        $this->assignFunc($initRs);

        $this->assign('supplierSkey', $this->md5Row($initRs['supplierId'], 'supplierManage_formal_flibrary', null));
        $this->assign('payTypeCN', $this->getDataNameByCode($initRs['payType']));
        $this->assign('payForCN', $this->getDataNameByCode($initRs['payFor']));
        $this->assign('sourceTypeCN', $this->getDataNameByCode($initRs['sourceType']));
        $this->assign('file', $this->service->getFilesByObjId($id, false));

        //处理扩展审批流
        if (empty($object['exaCode'])) {
            $this->assign('exaId', $id);
            $this->assign('exaCode', $this->service->tbl_name);
        }
        //委托付款
        $this->assign('isEntrust', $this->service->rtYesNo_d($object['isEntrust']));
        $this->display($this->service->getBusinessCode($object['sourceType']) . '-pay' . $keyWork);
    }

    /**
     * 更新审批付款日期
     */
    function c_updateAuditDate() {
        echo $this->service->update(array('id' => $_POST['id']), array('auditDate' => $_POST['auditDate'])) ? 1 : 0;
    }

    /**
     * 变更审批付款日期
     */
    function c_toChangeDate() {
        $changeDao = new model_finance_payablesapply_payablesapplychange();
        $change = $changeDao->find(array('purOrderId' => $_GET['id'], 'ExaStatus' => '部门审批'));
        if (!empty($change)) {
            msg("已有提交的变更审批付款日期还未处理！");
        } else {
            $arr = $this->service->find(array('id' => $_GET['id']));
            $this->assignFunc($arr);
            $this->view('changedate');
        }
    }

    /**
     * 获取权限
     */
    function c_getLimits() {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 归属月份处理
     * @param string $default
     * @return string
     */
    function periodDeal($default = '') {
        $period = $default ? explode('.', $default) : array();
        $defaultYear = empty($period) ? '' : $period[0];
        $defaultMonth = empty($period) ? '' : $period[1];

        $periodStr = "<select id='yearSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 2016; $i <= 2020; $i++) {
            if ($i == $defaultYear) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select> . ";

        $periodStr .= "<select id='monthSelector' class='select' style='width:95px;'><option></option>";
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $defaultMonth) {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $periodStr .= '<option title="' . $i . '" value="' . $i . '">' . $i . '</option>';
            }
        }
        $periodStr .= "</select>";
        $periodStr .= "<input type='hidden' id='period' name='invother[period]' value='" . $default . "'>";
        $periodStr .=<<<E
            <script type='text/javascript'>
                $(function() {
                    var changePeriod = function() {
                        var year = $("#yearSelector").val();
                        var month = $("#monthSelector").val();
                        if (year != "" && month != "") {
                            $("#period").val(year + '.' + month);
                        } else {
                            $("#period").val('');
                        }
                    }

                    $("#yearSelector").bind('change', changePeriod);
                    $("#monthSelector").bind('change', changePeriod);
                });
            </script>
E;
        return $periodStr;
    }
}