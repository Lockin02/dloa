<?php

/**
 * @author Show
 * @Date 2011年12月5日 星期一 10:19:51
 * @version 1.0
 * @description:其他合同控制层
 */
class controller_contract_other_other extends controller_base_action
{
    private $unSltDeptFilter = "";// PMS68 费用归属部门禁止选择的部门ID配置
    private $DenyFegsdeptId = ""; // PMS772 费用归属部门禁止选择的部门ID,通过配置端配置
    private $unDeptExtFilter = "";// PMS377 此模块需要单独隐藏的部门选项
    private $bindId = "";
	function __construct() {
		$this->objName = "other";
		$this->objPath = "contract_other";
		parent::__construct();

        $otherDataDao = new model_common_otherdatas();
        $subsidyArr = $otherDataDao->getConfig('unSltDeptFilter');
        $unDeptExtFilterArr = $otherDataDao->getConfig('unDeptExtFilter');
        $this->unSltDeptFilter = $subsidyArr;
        $this->unDeptExtFilter = ",".rtrim($unDeptExtFilterArr,",");
        $DenyFegsdept = $otherDataDao->getDenyFegsdept();
        if(isset($DenyFegsdept['0']) && !empty($DenyFegsdept['0'])){
            $this->DenyFegsdeptId = $DenyFegsdept['0']['belongDeptIds'];
        }

        // 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIdsArr = $otherDataDao->getConfig('unSelectableIds');
        $this->unSelectableIds = $unSelectableIdsArr;
        $this->bindId = "7627a082-a267-4e6c-b404-97e469d80ec4";
	}

	/**
	 * 跳转到其他合同
	 */
	function c_page() {
		isset($_GET['autoload']) ? $this->assign('autoload', $_GET['autoload']) : $this->assign('autoload', '');# 自动加载
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonFinanceInfo() {
        // 初始化搜索条件数组 created by huanghaojin (用于导出时加上搜索的条件)
        if(isset($_SESSION['searchArr'])){
            unset($_SESSION['searchArr']);
        }else{$_SESSION['searchArr'] = array();}

		$service = $this->service;

		//初始化搜索条件
		if (isset($_POST['payandinv'])) {
			$thisSet = $service->initSetting_c($_POST['payandinv']);
			$_POST[$thisSet] = 1;
			unset($_POST['payandinv']);
		}

		//系统权限
		$deptLimit = $this->service->this_limit['部门权限'];

		//办事处 － 全部 处理
		if (strstr($deptLimit, ';;')) {
			$service->getParam($_POST); //设置前台获取的参数信息
			$rows = $service->pageBySqlId('select_financeInfo');
		} else {//如果没有选择全部，则进行权限查询并赋值
			if (!empty($deptLimit)) {
				$_POST['deptsIn'] = $deptLimit;
				$service->getParam($_POST); //设置前台获取的参数信息
				$rows = $service->page_d('select_financeInfo');
			}
		}

		if (is_array($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);

			//合计加入
			$rows = $this->service->pageCount_d($rows);

			$objArr = $service->listBySqlId('count_list');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['createDate'] = '合计';
				$rsArr['id'] = 'noId';
				$rows[] = $rsArr;
			}
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;//加载扩展SQL

        // 记录搜索条件数组 created by huanghaojin (用于导出时加上搜索的条件)
        $search_Array = $service->searchArr;
        unset($search_Array['isSearchTag_']);
        $_SESSION['searchArr'] = $search_Array;

		echo util_jsonUtil::encode($arr);
	}


    function c_pageForAuditView() {
        $ids = isset($_GET['ids'])? $_GET['ids'] : '';

        // 如果session缓存存在对应的IDs
        if(isset($_GET['idsKey']) && isset($_SESSION[$_GET['idsKey']])){
            $ids = $_SESSION[$_GET['idsKey']];
        }

        $this->assign("ids",$ids);
        $this->view('listForAudit');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonInfo() {
        if(isset($_POST['ids']) && $_POST['ids'] != ''){
            // 初始化搜索条件数组 created by huanghaojin (用于导出时加上搜索的条件)
            if(isset($_SESSION['searchArr'])){
                unset($_SESSION['searchArr']);
            }else{$_SESSION['searchArr'] = array();}

            $service = $this->service;

            //初始化搜索条件
            if (isset($_POST['payandinv'])) {
                $thisSet = $service->initSetting_c($_POST['payandinv']);
                $_POST[$thisSet] = 1;
                unset($_POST['payandinv']);
            }

            $service->getParam($_POST); //设置前台获取的参数信息
            $rows = $service->pageBySqlId('select_financeInfo');
            $arr ['pageSql'] = $service->listSql;//加载扩展SQL

            if (is_array($rows)) {
                //数据加入安全码
                $rows = $this->sconfig->md5Rows($rows);

                //合计加入
                $rows = $this->service->pageCount_d($rows);

                $objArr = $service->listBySqlId('count_list');
                if (is_array($objArr)) {
                    $rsArr = $objArr[0];
                    $rsArr['createDate'] = '合计';
                    $rsArr['id'] = 'noId';
                    $rows[] = $rsArr;
                }
            }

            $arr ['collection'] = $rows;
            //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
            $arr ['page'] = $service->page;


            // 记录搜索条件数组 created by huanghaojin (用于导出时加上搜索的条件)
            $search_Array = $service->searchArr;
            unset($search_Array['isSearchTag_']);
            $_SESSION['searchArr'] = $search_Array;
        }else{
            $arr ['collection'] = array();
            //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
            $arr ['totalSize'] = 0;
        }


        echo util_jsonUtil::encode($arr);
    }

	/**
	 * 重写新增
	 */
	function c_toAdd() {
		$this->showDatadicts(array('fundType' => 'KXXZ'));
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), null, true);

		// 付款申请信息渲染
		$this->showDatadicts(array('payFor' => 'FKLX'), null, false, array('expand1' => 1)); // 付款类型
		$this->showDatadicts(array('payType' => 'CWFKFS')); // 结算方式
		$this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, array("请选择"=>"")); // 付款业务类型
		$this->showDatadicts(array('invoiceType' => 'FPLX'), null, true); // 发票类型

//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//		$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->assign('isSysCode', ORDERCODE_INPUT); // 是否手工输入合同号
		$this->assign('isShare', PAYISSHARE); // 是否启用费用分摊

		$this->assign('userId', $_SESSION['USER_ID']);

		// 获取归属公司名称
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		//获取定义的销售部门id
		$this->assign('saleDeptId', expenseSaleDeptId);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
		isset($_GET['open']) ? $this->view('add', true) : $this->view('addDept', true);
	}

	/**
	 * 新增 - 用于外部付款
	 */
	function c_toAddPay() {
        $balanceDao = new model_flights_balance_balance();
        // 机票的时候，将机票费用转入费用报销
        $costShare = $balanceDao->getCostShare_d($_GET['projectId']);

        if (!$costShare) {
            header("charset:GBK");
            exit('未能获取机票分摊数据，请联系管理员');
        }

		$this->showDatadicts(array('fundType' => 'KXXZ'), 'KXXZB');
		$this->assign('fundTypeHidden', 'KXXZB');
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $_GET['projectType'], true);
		$this->assign('projectTypeHidden', $_GET['projectType']);

		//付款申请信息渲染
		$this->showDatadicts(array('payFor' => 'FKLX'), null, false, array('expand1' => 1));//付款类型
		$this->showDatadicts(array('payType' => 'CWFKFS'));//结算方式
//		$this->showDatadicts(array('payForBusiness' => 'FKYWLX'), null, true); // 付款业务类型
		$this->showDatadicts(array('invoiceType' => 'FPLX'), null, true); // 发票类型

		$this->assign("payForBusinessName","无"); // 付款业务类型(机票带过来的默认显示是“无”)
        $this->assign("payForBusiness","FKYWLX-0");

		$this->assign('projectId', $_GET['projectId']);
		$this->assign('projectCode', $_GET['projectCode']);
		$this->assign('projectName', $_GET['projectName']);
		$this->assign('orderMoney', $_GET['orderMoney']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('principalId', $_SESSION['USER_ID']);
		$this->assign('principalName', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->assign('isSysCode', ORDERCODE_INPUT);//是否手工输入合同号
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->assign('isShare', PAYISSHARE);//是否启用费用分摊

		// 获取归属公司名称
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

//        $unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
//        $this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
		$this->view('addpay', true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//其他合同立项付款申请
					succ_show('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgRf('保存成功');
			}
		} else {
			msgRf('保存失败');
		}
	}

	/**
	 * 独立新增对象操作
	 */
	function c_addDept() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//其他合同立项付款申请
					succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_indexdept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgGo('保存成功！', '?model=contract_other_other&action=toAdd');
			}
		} else {
			msgGo('保存失败！', '?model=contract_other_other&action=toAdd');
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->editInfo_d($object);
		if ($id) {
			if ($_GET['act']) {
				if ($object['isNeedPayapply'] == 1) {
					//其他合同立项付款申请
					succ_show('controller/contract/other/ewf_forpayapplydept.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['payapply']['feeDeptId'] . '&billCompany=' . $object['businessBelong']);
				} else {
					succ_show('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
				}
			} else {
				msgRf('保存成功');
			}
		} else {
			msgRf('保存失败');
		}
	}

	/**
	 * 查看页面 - 包含付款申请信息
	 */
	function c_viewAlong() {
		$this->permCheck(); //安全校验
		$obj = $this->service->getInfo_d($_GET['id']);
		$obj['orgFundType'] = $obj['fundType'];
        $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "是" :  "否";
        $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
		$this->assignFunc($obj);

		//提交审批后查看单据时隐藏关闭按钮
		$this->assign('showBtn', isset($_GET['showBtn']) ? $_GET['showBtn'] : 1);

		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->assign('file1', $this->service->getFilesByObjId($obj['id'], false, 'oa_sale_otherpayapply'));

		$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
		$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

		$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

		$this->assign('payFor', $this->getDataNameByCode($obj['payFor']));
		$this->assign('payType', $this->getDataNameByCode($obj['payType']));
		$this->assign('payForBusiness', $this->getDataNameByCode($obj['payForBusiness']));//付款业务类型

		$this->assign('isInvoice', $this->service->rtYesOrNo_d($obj['isInvoice']));

		//委托付款
		$this->assign('isEntrust', $this->service->rtYesOrNo_d($obj['isEntrust']));

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);

        $showDataSum = "<fieldset style='display:none;'>";
        if($thisObjCode == 'pay'){
            $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));

            // 获取统计数据
            $sumStr = "";

            // 如果有临时修改的延后回款天数,则显示临时数据（只在审批页面显示）
            if(isset($_GET['viewOn']) && $_GET['viewOn'] == 'audit'){
                $applyManWhkMoneyIds = $applyManYqWhkMoneyIds = $signCompanyWhkMoneyIds = $signCompanyYqWhkMoneyIds = '';
                $applyManWhkMoney = $applyManYqWhkMoney = $signCompanyWhkMoney = $signCompanyYqWhkMoney = 0;

                $datadictDao = new model_system_datadict_datadict();
                $payForBusinessArr = $datadictDao->getDatadictsByParentCodes ( 'FKYWLX' ,array("dataCode" => $obj['payForBusiness']));
                if($payForBusinessArr && is_array($payForBusinessArr)){
                    $payForBusinessArr = $payForBusinessArr['FKYWLX'][0];
                }
                // 利用付款业务类型的扩展字段4来控制统计栏是否需要显示 PMS678
                $showDataSum = ($payForBusinessArr['expand4'] == 1)? "<fieldset id='dataSumTr' style='width:1180px;margin:auto;margin-top:10px;text-align:center'>" : "<fieldset style='display:none;'>";

                $applyManMoney = $this->service->sumBzjMoney("byMan",$obj['principalId']);
                $applyManWhkMoney = ($applyManMoney['needPay'] == 0)? "0.00" : $applyManMoney['needPay'];
                $applyManWhkMoneyIds = $applyManMoney['needPayIds'];
                $applyManYqWhkMoney = ($applyManMoney['needPayBeyond'] == 0)? "0.00" : $applyManMoney['needPayBeyond'];
                $applyManYqWhkMoneyIds = $applyManMoney['needPayBeyondIds'];

                $signCompanyMoney = $this->service->sumBzjMoney("byCompany",$obj['signCompanyName']);
                $signCompanyWhkMoney = ($signCompanyMoney['needPay'] == 0)? "0.00" : $signCompanyMoney['needPay'];
                $signCompanyWhkMoneyIds = $signCompanyMoney['needPayIds'];
                $signCompanyYqWhkMoney = ($signCompanyMoney['needPayBeyond'] == 0)? "0.00" : $signCompanyMoney['needPayBeyond'];
                $signCompanyYqWhkMoneyIds = $signCompanyMoney['needPayBeyondIds'];


                $this->assign('signCompany',$obj['signCompanyName']);
                $this->assign('applyMan',$obj['principalName']);

                $this->assign('applyManWhkMoney',$applyManWhkMoney);//未回款/开票保证金
                $this->assign('applyManWhkMoneyIds',$applyManWhkMoneyIds);
                $this->assign('applyManYqWhkMoney',$applyManYqWhkMoney);//逾期未回款/开票保证金
                $this->assign('applyManYqWhkMoneyIds',$applyManYqWhkMoneyIds);
                $this->assign('signCompanyWhkMoney',$signCompanyWhkMoney);//未回款/开票保证金
                $this->assign('signCompanyWhkMoneyIds',$signCompanyWhkMoneyIds);
                $this->assign('signCompanyYqWhkMoney',$signCompanyYqWhkMoney);//逾期未回款/开票保证金
                $this->assign('signCompanyYqWhkMoneyIds',$signCompanyYqWhkMoneyIds);
                if($obj['delayPayDaysTemp'] != '' && $obj['delayPayDaysTemp'] >= 0 && $obj['delayPayDaysTemp'] != $obj['delayPayDays']){
                    $this->assign('delayPayDays',$obj['delayPayDaysTemp']);

                }
            }
        }
        $this->assign('showDataSum',$showDataSum);

        $this->assign('viewAct',isset($_GET['act'])? $_GET['act'] : '');
        $this->assign('buffersDayStyle',(isset($_GET['act']) && $_GET['act'] == "auditView")? "style='color:red'" : '');
        $bufferDaysIsShow = '';
        if(!isset($_GET['act'])){
            $bufferDaysIsShow = '1';
            $this->assign('viewAct','auditView');
        }
        $this->assign('bufferDaysIsShow',$bufferDaysIsShow);
        // 判断是否需要显示【保证金关联其他类合同】
        $displayTd1 = 'style="display:none;"';
        $td0ColSpan = "colspan='5'";
        $hasRelativeContract = "";
        if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
            $displayTd1 = '';
            $td0ColSpan = '';
            if($obj['hasRelativeContract'] == 2){
                $hasRelativeContract = "无";
            }
        }

        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);
        $this->assign('hasRelativeContract',$hasRelativeContract);

        $otherDataDao = new model_common_otherdatas();
        $editorsId = $otherDataDao->getConfig('bufferDaysEditors');
        $editorsId = explode(",",$editorsId);
        $bufferDaysEditLimit = in_array($_SESSION['USER_ID'],$editorsId)? "1" : "";
        $this->assign('bufferDaysEditLimit',$bufferDaysEditLimit);

        // 源单类型选项 PMS 650
        $codeType = $codeValue = "";
        if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeType = "销售合同";
                $codeValue = $obj['contractCode'];
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeType = "商机";
                $codeValue = $obj['chanceCode'];
            }
        }
        $this->assign('codeType', $codeType);
        $this->assign('codeValue', $codeValue);

        $this->view($thisObjCode . '-viewAlong');
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck(); //安全校验

		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$obj = $this->service->getInfo_d($_GET['id']);
			$obj['orgFundType'] = $obj['fundType'];
            $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "是" :  "否";
            $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
			$this->assignFunc($obj);
			//提交审批后查看单据时隐藏关闭按钮
			if (isset($_GET['viewBtn'])) {
				$this->assign('showBtn', 1);
			} else {
				$this->assign('showBtn', 0);
			}
			//附件添加{file}
			$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));

			$this->assign('fundType', $this->getDataNameByCode($obj['fundType']));

			$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
			$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));

			//签收状态
			$this->assign('signedStatusCN', $this->service->rtIsSign_d($obj['signedStatus']));

			$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

            $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));

            $this->assign('viewAct',isset($_GET['act'])? $_GET['act'] : '');
            // 判断是否需要显示【保证金关联其他类合同】
            $displayTd1 = 'style="display:none;"';
            $td0ColSpan = "colspan='5'";
            $hasRelativeContract = "";
            if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
                $displayTd1 = '';
                $td0ColSpan = '';
                if($obj['hasRelativeContract'] == 2){
                    $hasRelativeContract = "无";
                }
            }

            $this->assign('td0ColSpan',$td0ColSpan);
            $this->assign('displayTd1',$displayTd1);
            $this->assign('hasRelativeContract',$hasRelativeContract);

            // 源单类型选项 PMS 650
            $codeType = $codeValue = "";
            if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
                if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                    $codeType = "销售合同";
                    $codeValue = $obj['contractCode'];
                }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                    $codeType = "商机";
                    $codeValue = $obj['chanceCode'];
                }
            }
            $this->assign('codeType', $codeType);
            $this->assign('codeValue', $codeValue);

			$this->view('view');
		} else {
            //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
            //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
            $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

            // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
            $unSltDeptFilter = $this->unSltDeptFilter;
            $this->assign('unSltDeptFilter', $unSltDeptFilter);

            // PMS 180 如果付款业务类型是无的时候,禁止选择的费用明细
            $unSelectableIds = $this->unSelectableIds;
            $this->assign('unSelectableIds', $unSelectableIds);

			$obj = $this->service->getInfo_d($_GET['id']);

			$this->assignFunc($obj);

			//附件
			$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
			$this->assign('file1', $this->service->getFilesByObjId($obj['id'], true, 'oa_sale_otherpayapply'));

			$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
			$this->showDatadicts(array('fundType' => 'KXXZ'), $obj['fundType']);
			$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // 发票类型

			$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

//            echo"<pre>";print_r($obj);exit();

            $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">是</option><option value="2" selected>否</option>' : '<option value="1" selected>是</option><option value="2">否</option>';
            $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);


			//付款申请信息渲染
			$this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor'], false, array('expand1' => 1));//付款类型
			$this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);//结算方式
            $this->assign('payForBusinessName',  $obj['payForBusinessName']);//付款业务类型
            $this->assign('payForBusiness', $obj['payForBusiness']);//付款业务类型
            if($obj['projectType'] == 'QTHTXMLX-03'){// 机票的默认选择无
                $this->assign('payForBusinessOpts',"<option value='FKYWLX-0' selected>无</option>");
            }else{
                $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));
            }

			$this->assign('userId', $_SESSION['USER_ID']);
			$this->assign('isShare', PAYISSHARE);//是否启用费用分摊
			$this->assign('payee', $this->getDataNameByCode($obj['payee']));
			$this->assign('comments', $this->getDataNameByCode($obj['comments']));

			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$objs = $payablesapplyDao->getPayinfo_d($_GET['id']);
			//is省份
			$this->assign('deptIsNeedProvince', $payablesapplyDao->deptIsNeedProvince_d($obj['feeDeptId']));
			//is省份
			$this->assign('provinceName', $objs[0]['provinceName']);
			//获取定义的销售部门id
			$this->assign('saleDeptId', expenseSaleDeptId);

            // 盖章相关信息补充
            $stampConfigDao = new model_system_stamp_stampconfig();
            $legalPersonUsername = $legalPersonName = $businessBelongId = '';
            if($obj['isNeedStamp'] == 1 && $obj['stampIds'] != ''){
                $stampIdArr = explode(",",$obj['stampIds']);
                $stampConfigInfo = $stampConfigDao->get_d($stampIdArr[0]);
                if($stampConfigInfo){
                    $legalPersonUsername = $stampConfigInfo['legalPersonUsername'];
                    $legalPersonName = $stampConfigInfo['legalPersonName'];
                    $businessBelongId = $stampConfigInfo['businessBelongId'];
                }
            }
            $this->assign('legalPersonUsername', $legalPersonUsername);
            $this->assign('legalPersonName', $legalPersonName);
            $this->assign('businessBelongId', $businessBelongId);

            $expenseDao = new model_finance_expense_expense();
            // PMS613 费用归属部门为系统商销售只能选的费用承担人
            $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
            $this->assign('feemansForXtsSales', $feemansForXtsSales);

            // 源单类型选项 PMS 650
            $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\">商机</option>";
            if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
                if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                    $codeTypeOpts = "<option value=\"销售合同\" selected>销售合同</option><option value=\"商机\">商机</option>";
                }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                    $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\" selected>商机</option>";
                }
            }
            $this->assign('codeTypeOpts', $codeTypeOpts);

			$this->view('edit', true);
		}
	}

    /**
     * 复制合同
     */
    function  c_toCopyAdd(){
        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

        $obj = $this->service->getInfo_d($_GET['id']);

        $this->assignFunc($obj);

        //附件
//        $this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
//        $this->assign('file1', $this->service->getFilesByObjId($obj['id'], true, 'oa_sale_otherpayapply'));
        $this->assign('file1', '');

        $this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
        $this->showDatadicts(array('fundType' => 'KXXZ'), $obj['fundType']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // 发票类型

        $this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">是</option><option value="2" selected>否</option>' : '<option value="1" selected>是</option><option value="2">否</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);


        //付款申请信息渲染
        $this->showDatadicts(array('payFor' => 'FKLX'), $obj['payFor'], false, array('expand1' => 1));//付款类型
        $this->showDatadicts(array('payType' => 'CWFKFS'), $obj['payType']);//结算方式
        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));
        $this->assign('payForBusinessName', util);//付款业务类型
        $this->assign('payForBusiness', $obj['payForBusiness']);//付款业务类型

        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('isShare', PAYISSHARE);//是否启用费用分摊
        $this->assign('payee', $this->getDataNameByCode($obj['payee']));
        $this->assign('comments', $this->getDataNameByCode($obj['comments']));

        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $objs = $payablesapplyDao->getPayinfo_d($_GET['id']);
        //is省份
        $this->assign('deptIsNeedProvince', $payablesapplyDao->deptIsNeedProvince_d($obj['feeDeptId']));
        //is省份
        $this->assign('provinceName', $objs[0]['provinceName']);
        //获取定义的销售部门id
        $this->assign('saleDeptId', expenseSaleDeptId);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('thisDate', day_date);

        $this->assign('userId', $_SESSION['USER_ID']);

        // 获取归属公司名称
        $this->assign('formBelong', $_SESSION['USER_COM']);
        $this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
        $this->assign('businessBelong', $_SESSION['USER_COM']);
        $this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
        //显示附件信息
//        $uploadFile = new model_file_uploadfile_management ();
//        $files = $uploadFile->getFilesByObjId ( $_GET ['id'], $this->service->tbl_name );
        $fileStr='';
//        if(is_array($files)){
//            foreach($files as $fKey=>$fVal){
//                $i=$fKey+1;
//                //插入附件表
//                $fileArr['serviceType']=$this->service->tbl_name;
//                $fileArr['originalName']=$fVal['originalName'];
//                $fileArr['newName']=$this->service->tbl_name."-".$fVal['newName'];
//                $UPLOADPATH2=UPLOADPATH;
//                $newPath=str_replace('\\','/',$UPLOADPATH2);
//                $destDir=$newPath.$this->service->tbl_name."/";
//                $fileArr['uploadPath']=$destDir;
//                $fileArr['tFileSize']=$fVal['tFileSize'];
//                $test = $uploadFile->add_d ( $fileArr, true );
//                $fileStr.='<div class="upload" id="fileDiv'.$test.'"><a title="点击下载" href="?model=file_uploadfile_management&amp;action=toDownFileById&amp;fileId='.$test.'">'.$fVal['originalName'].'</a>&nbsp;<img src="images/closeDiv.gif" onclick="delfileById('.$test.')" title="点击删除附件"><div></div></div><input type="hidden" name="fileuploadIds['.$i.']" value="'.$test.'">';
//            }
//        }

        // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        $this->show->assign("file",$fileStr);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        // 源单类型选项 PMS 650
        $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\">商机</option>";
        if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeTypeOpts = "<option value=\"销售合同\" selected>销售合同</option><option value=\"商机\">商机</option>";
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\" selected>商机</option>";
            }
        }
        $this->assign('codeTypeOpts', $codeTypeOpts);

        $this->view('copy-add', true);

    }

	/**
	 * 合同tab页
	 */
	function c_viewTab() {
		$this->assign('id', $_GET['id']);
		$obj = $this->service->get_d($_GET['id']);
		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($obj['fundType']);
		$this->display($thisObjCode . '-viewtab');
	}

    /**
     * 返款或不开票记录页面
     */
	function c_toShowCostChangeRecord(){
        $this->assign('type', $_GET['type']);
        $this->assign('objId', $_GET['objId']);
        $this->view('listCostChangeRecord');
    }

    /**
     * 返款或不开票记录 Json 数据
     */
    function c_listCostChangeRecordJson(){
        $type = $_POST['type'];
        $objId = $_POST['objId'];
        $service = $this->service;
        $service->searchArr['costChangeType'] = $type;
        $service->searchArr['costChangeobjId'] = $objId;
        $service->setCompany(0);

        $rows = $service->page_d('slt_costChangeRecord');

        if($type == 'uninvoiceMoney'){
            // 添加统计栏
            $rowsCount = $service->listBySqlId('slt_costChangeRecordCount');
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['objCode'] = '合计';
            $rsArr['costAmount'] = is_array($rowsCount)? $rowsCount[0]['costAmount'] : '0';
            $rows[] = $rsArr;
        }

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
    }

	/**
	 * 更新返款金额
	 */
	function c_toUpdateReturnMoney() {
		//获取合同自身信息
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('updatereturnmoney');
	}

	/**
	 * 更新返款金额
	 */
	function c_updateReturnMoney() {
		if ($this->service->edit_d($_POST[$this->objName])) {
			msg('保存成功');
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转申请盖章页面
	 */
	function c_toStamp() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('applyDate', day_date);
		$this->assign('file', '暂无任何附件');

		//当前盖章申请人
		$this->assign('thisUserId', $_SESSION['USER_ID']);
		$this->assign('thisUserName', $_SESSION['USERNAME']);

		$this->view('stamp');
	}

	/**
	 * 新增盖章信息操作
	 */
	function c_stamp() {
		if ($this->service->stamp_d($_POST[$this->objName])) {
			msg("申请成功！");
		} else {
			msg("申请失败！");
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAudit() {
		$this->service->dealAfterAudit_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 其他合同立项付款申请
	 */
	function c_dealAfterAuditPayapply() {
		$this->service->dealAfterAuditPayapply_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 跳转个人其他合同
	 */
	function c_myOther() {
		$this->view('mylist');
	}

    /**
     * 获取分页数据转成Json【重写】
     */
    function c_pageJson() {
        $service = $this->service;

        if(isset($_REQUEST['isSelf']) && $_REQUEST['isSelf'] == 1){
            $this->c_myOtherListPageJson();
        }else{
            $service->getParam ( $_REQUEST );
            //$service->getParam ( $_POST ); //设置前台获取的参数信息

            //$service->asc = false;
            $rows = $service->page_d ();
            //数据加入安全码
            $rows = $this->sconfig->md5Rows ( $rows );
            $arr = array ();
            $arr ['collection'] = $rows;
            //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
            $arr ['page'] = $service->page;
            $arr ['advSql'] = $service->advSql;
            $arr ['listSql'] = $service->listSql;
            echo util_jsonUtil::encode ( $arr );
        }
    }

	/**
	 * 我的其他合同
	 */
	function c_myOtherListPageJson() {
		$service = $this->service;

        $userId = '';
        if(isset($_POST['principalId'])){
            $userId = $_POST['principalId'];
            unset($_POST['principalId']);
        }

		$service->getParam($_POST); //设置前台获取的参数信息

		$service->searchArr['principalIdAndCreateId'] = ($userId == '')? $_SESSION['USER_ID'] : $userId;
		$service->setCompany(0); # 个人列表,不需要进行公司过滤
		$rows = $service->page_d('select_financeInfo');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 关闭合同
	 */
	function c_changeStatus() {
		echo $this->service->edit_d(array('id' => $_POST['id'], 'status' => '3')) ? 1 : 0;
	}

	/**
	 * 附件上传
	 */
	function c_toUploadFile() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * 获取权限
	 */
	function c_getLimits() {
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S 变更系列 *********************/
	/**
	 * 变更申请页面
	 */
	function c_toChange() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		//附件
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, $this->service->tbl_name));
		$this->showDatadicts(array('projectType' => 'QTHTXMLX'), $obj['projectType'], true);
		$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // 发票类型

		//付款条件必填选项
		$datadictDao = new model_system_datadict_datadict ();
		$rs = $datadictDao->find(array('dataCode' => $obj['fundType']), null, 'expand1');
		$this->assign('isNeed', $rs['expand1']);
		//获取定义的销售部门id
		$this->assign('saleDeptId', expenseSaleDeptId);

        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

        // PMS 180 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIds = $this->unSelectableIds;
        $this->assign('unSelectableIds', $unSelectableIds);

        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));
        $this->assign('payForBusinessName', util);//付款业务类型
        $this->assign('payForBusiness', $obj['payForBusiness']);//付款业务类型

        // 查询是否存在付款申请或者分摊记录
        $payablesApplyDao = new model_finance_payablesapply_payablesapply();
        $costShareDao = new model_finance_cost_costshare();
        if ($payablesApplyDao->getApplyAndDetail_d(array('objType' => 'YFRK-02', 'objId' => $obj['id']))
            || $costShareDao->getShareList_d($obj['id'], 2)) {
            $this->assign('canChangeCurrency', '0');
        } else {
            $this->assign('canChangeCurrency', '1');
        }

        $this->assign('userId', $obj['createId']);
        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">是</option><option value="2" selected>否</option>' : '<option value="1" selected>是</option><option value="2">否</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

        // 源单类型选项 PMS 650
        $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\">商机</option>";
        if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeTypeOpts = "<option value=\"销售合同\" selected>销售合同</option><option value=\"商机\">商机</option>";
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeTypeOpts = "<option value=\"销售合同\">销售合同</option><option value=\"商机\" selected>商机</option>";
            }
        }
        $this->assign('codeTypeOpts', $codeTypeOpts);

		$this->view('change');
	}

	/**
	 * 变更操作
	 * 2012-03-26
	 * createBy kuangzw
	 */
	function c_change() {
		$object = $_POST[$this->objName];
		try {
			$id = $this->service->change_d($object);
			if ($object['fundType'] == 'KXXZB') {
                $originalObj = $this->service->get_d($object['oldId']);
                $this->service->updateChangeDetailField($id,$originalObj);

				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billDept=' . $object['deptId'] . '&billCompany=' . $object['businessBelong']);
			} else {
				succ_show('controller/contract/other/ewf_change.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $object['orderMoney'] . '&billCompany=' . $object['businessBelong']);
			}
		} catch (Exception $e) {
			msgBack2("变更失败！失败原因：" . $e->getMessage());
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAuditChange() {
		$this->service->dealAfterAuditChange_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 变更查看tab
	 */
	function c_changeTab() {
		$this->permCheck(); //安全校验
		$this->assign('id', $_GET['id']);

		$rs = $this->service->find(array('id' => $_GET['id']), null, 'originalId,fundType');
		$this->assign('originalId', $rs['originalId']);

		// 策略调用页面
		$thisObjCode = $this->service->getBusinessCode($rs['fundType']);
		$this->display($thisObjCode . '-changetab');
	}

	/**
	 * 变更查看合同  - 查看原合同
	 */
	function c_changeView() {
//		$this->permCheck(); //安全校验

		$obj = $this->service->get_d($_GET['id']);
        $obj['orgFundType'] = $obj['fundType'];
        $obj['isBankbackLetterStr'] = ($obj['isBankbackLetter'] == 1)? "是" :  "否";
        $obj['isBankbackLetterDateShow'] = ($obj['isBankbackLetter'] == 1)? "" :  "style='display:none;'";
		$this->assignFunc($obj);

		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

		$this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
		$this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
		$this->assign('isNeedRestamp', $this->service->rtYesOrNo_d($obj['isNeedRestamp']));

        $this->assign('payForBusiness', $this->getDataNameByCode($obj['payForBusiness']));//付款业务类型
        $this->showDatadicts(array('payForBusinessOpts' => 'FKYWLX'), $obj['payForBusiness'], array("请选择"=>""));

		$this->assign('createDate', date('Y-m-d', strtotime($obj['createTime'])));

        // 判断是否需要显示【保证金关联其他类合同】
        $displayTd1 = 'style="display:none;"';
        $td0ColSpan = "colspan='5'";
        $hasRelativeContract = "";
        if((isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] > 0)){
            $displayTd1 = '';
            $td0ColSpan = '';
            if($obj['hasRelativeContract'] == 2){
                $hasRelativeContract = "无";
            }
        }

        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);
        $this->assign('hasRelativeContract',$hasRelativeContract);

        if($obj['delayPayDaysTemp'] != '' && $obj['delayPayDaysTemp'] >= 0 && $obj['delayPayDaysTemp'] != $obj['delayPayDays']){
            $this->assign('delayPayDays',$obj['delayPayDaysTemp']);
        }
        $this->assign('td0ColSpan',$td0ColSpan);
        $this->assign('displayTd1',$displayTd1);

        $otherDataDao = new model_common_otherdatas();
        $editorsId = $otherDataDao->getConfig('bufferDaysEditors');
        $editorsId = explode(",",$editorsId);
        $bufferDaysEditLimit = in_array($_SESSION['USER_ID'],$editorsId)? "1" : "";
        $this->assign('bufferDaysEditLimit',$bufferDaysEditLimit);

        // 源单类型选项 PMS 650
        $codeType = $codeValue = "";
        if($obj['payForBusiness'] == "FKYWLX-06"){// 中标服务费
            if(!empty($obj['contractCode']) && $obj['contractId'] > 0){
                $codeType = "销售合同";
                $codeValue = $obj['contractCode'];
            }else if(!empty($obj['chanceCode']) && $obj['chanceId'] > 0){
                $codeType = "商机";
                $codeValue = $obj['chanceCode'];
            }
        }
        $this->assign('codeType', $codeType);
        $this->assign('codeValue', $codeValue);

		$this->view('changeview');
	}
	/******************* E 变更系列 *********************/

	/******************* S 签收系列 *********************/
	/**
	 * 合同签收 - 列表tab页
	 */
	function c_signTab() {
		$this->display('signTab');
	}

	/**
	 * 合同签收 - 待签收合同列表
	 */
	function c_signingList() {
		$this->view('signinglist');
	}

	/**
	 * 合同签收 - 已签收合同列表
	 */
	function c_signedList() {
		$this->view('signedlist');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_toSign() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//附件添加{file}
		$this->assign('file', $this->service->getFilesByObjId($obj['id'], false, $this->service->tbl_name));
		$this->showDatadicts(array('outsourceType' => 'HTWB'), $obj['outsourceType']);
		$this->showDatadicts(array('payType' => 'HTFKFS'), $obj['payType']);//合同付款方式
		$this->showDatadicts(array('outsourcing' => 'HTWBFS'), $obj['outsourcing']);//合同外包方式

		$this->view('sign');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_sign() {
		if ($this->service->sign_d($_POST[$this->objName])) {
			msgRf('签收成功');
		} else {
			msgRf('签收失败');
		}
	}

	/******************* E 签收系列 *********************/

	/******************* S 导入导出部分 *******************/
	/**
	 * 导出数据
	 */
	function c_exportExcel() {

		$service = $this->service;

        // 将记录在session内的搜索条件字段加入传入的数据中 created by Huanghaojin(用于导出时加上搜索的条件)
        if(is_array($_SESSION['searchArr'])){
            foreach ($_SESSION['searchArr'] as $k => $v) {
                if(!isset($_REQUEST[$k])){
                    $_REQUEST[$k] = $v;
                }
            }
        }

        $service->getParam($_REQUEST); //设置前台获取的参数信息
		$service->sort = 'c.createTime';
		$rows = $service->listBySqlId('select_financeInfo');
		return model_contract_common_contractExcelUtil::otherContractOut_e($rows);
	}
	/******************* E 导入导出部分 *******************/

	/******************* S 修改分摊明细 *****************/

	/**
	 * 修改费用分摊
	 */
	function c_toChangeCostShare() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('changeType', isset($_GET['changeType']) ? $_GET['changeType'] : '');
		$this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType'], true); // 发票类型

        $showPayType = ($obj['fundType'] == "KXXZB")? "" : "hide-box";
        $longColspan = ($obj['fundType'] == "KXXZB")? "" : "3";
        $this->assign('showPayType', $showPayType);
        $this->assign('longColspan', $longColspan);

        // PMS 180 如果付款业务类型是无的时候,禁止选择的费用明细
        $unSelectableIds = ($obj['payForBusiness'] == "FKYWLX-0")? $this->unSelectableIds : "";
        $this->assign('unSelectableIds', $unSelectableIds);

        //$unDeptFilterStr = (unDeptFilter && unDeptFilter != '' && unDeptFilter != 'unDeptFilter')? trim(unDeptFilter,',') : '';
        //$this->assign('unDeptFilter', $unDeptFilterStr.$this->unDeptExtFilter);
        $this->assign('unDeptFilter',$this->DenyFegsdeptId.$this->unDeptExtFilter);

        // PMS 68 对于费用报销、费用分摊，不允许选择这几个区域
        $unSltDeptFilter = $this->unSltDeptFilter;
        $this->assign('unSltDeptFilter', $unSltDeptFilter);

		//获取定义的销售部门id
		$this->assign('saleDeptId', expenseSaleDeptId);

        $hasRelativeContractOpts = (isset($obj['hasRelativeContract']) && $obj['hasRelativeContract'] == 2)? '<option value="1">是</option><option value="2" selected>否</option>' : '<option value="1" selected>是</option><option value="2">否</option>';
        $this->assign('hasRelativeContractOpts',$hasRelativeContractOpts);

        $expenseDao = new model_finance_expense_expense();
        // PMS613 费用归属部门为系统商销售只能选的费用承担人
        $feemansForXtsSales = $expenseDao->getFeemansForXtsSales();
        $this->assign('feemansForXtsSales', $feemansForXtsSales);

		$this->view('changeCostShare');
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_changeCostShare() {
		if ($this->service->changeCostShare_d($_POST[$this->objName])) {
			msgRf('修改成功');
		} else {
			msgRf('修改失败');
		}
	}

	/******************* E 修改分摊明细 *****************/

	/**
	 * 验证方法
	 */
	function c_canPayapply() {
		echo $this->service->canPayapply_d($_POST['id']);
	}

	/**
	 * 退款申请验证
	 */
	function c_canPayapplyBack() {
		echo $this->service->canPayapplyBack_d($_POST['id']);
	}

	/**
	 * 关闭合同
	 */
	function c_toClose() {
		$obj = $this->service->getInfoAndPay_d($_GET['id']);
		$this->assignFunc($obj);
		// 获取并设置关闭合同权限
		$this->assign('closeLimit', !$this->service->this_limit['关闭合同权限'] ? 1 : 0);
		$this->view('close');
	}

	/**
	 * 关闭方法
	 */
	function c_close() {
		$object = $_POST[$this->objName];
		$closeLimit = $object['closeLimit'];
		unset($object['closeLimit']);
        //具备关闭合同权限的无需走审批流
        if ($closeLimit) {
            $object['status'] = "3";
            $object['ExaStatus'] = "完成";
            $object['ExaDT'] = date("Y-m-d H:i:s");
        }
        if ($this->service->edit_d($object)) {
            if ($closeLimit) {
                msg('提交成功');
            } else {
                succ_show('controller/contract/other/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
            }
        } else {
            msg('提交失败');
        }
    }

    /**
     * 根据合同ID获取第一步审批的处理状态（no:未处理, ok:已处理）
     */
    function c_ajaxChkIsFirstAudit(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $sql = "select p.Result from flow_step_partent p left join wf_task w on p.Wf_task_ID = w.task where w.code = 'oa_sale_other' AND w.finish IS NULL AND w.Pid = '{$objId}' AND p.SmallId = 1;";
        $result = $this->service->_db->getArray($sql);
        if($result){
            echo ($result[0]['Result'] == "")? "ok" : "no";
        }else{
            echo "no";
        }
    }

    /**
     * ajax 更新临时延后回款天数字段
     */
    function c_ajaxUpdateDelayPayDaysTemp(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $newDelayPayDays = isset($_POST['delayPayDays'])? $_POST['delayPayDays'] : '';
        $isChange = isset($_POST['isChange'])? $_POST['isChange'] : '';
        $updateArr = ($isChange == 1)? array("id"=>$objId,"delayPayDaysTemp"=>$newDelayPayDays,"delayPayDays"=>$newDelayPayDays) : array("id"=>$objId,"delayPayDaysTemp"=>$newDelayPayDays);
        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * ajax 更新缓冲天数字段
     */
    function c_ajaxUpdateBufferDays(){
        $objId = isset($_POST['id'])? $_POST['id'] : '';
        $newBufferDays = isset($_POST['bufferDays'])? $_POST['bufferDays'] : '';
        $updateArr = array("id"=>$objId,"bufferDays"=>$newBufferDays);
        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * 回款邮件
     */
    function c_toSendMail()
    {
        $this->view('sendmail');
    }

    /**
     * 发送回款邮件
     */
    function c_sendMail()
    {
        $this->service->sendMail_d($_POST[$this->objName]);
        msg('发送成功');
    }

    /**
     * 回款邮件提取
     */
    function c_getSendMailInfo()
    {
        echo util_jsonUtil::encode($this->service->getSendMailInfo_d(util_jsonUtil::iconvUTF2GB($_POST['signCompanyName'])));
    }
}