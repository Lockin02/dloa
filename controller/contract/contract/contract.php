<?php

//2012-12-27备份
/**
 * @author LiuB
 * @Date 2012年3月8日 10:30:28
 * @version 1.0
 * @description:合同主表控制层
 */
class controller_contract_contract_contract extends controller_base_action
{
    private $bindId = "";
    function __construct()
    {
        $this->objName = "contract";
        $this->objPath = "contract_contract";
        //		$this->lang="contract";//语言包模块
        $this->bindId = "458b579c-3a93-4648-9453-7af8407f1ede";
        parent :: __construct();
    }

    /**
     *默认action跳转函数
     */
    function c_index() {
        // 归档信息修改权限
        $otherdatasDao = new model_common_otherdatas();
        $limit = $otherdatasDao->getUserPriv("contract_contract_contract", $_SESSION ['USER_ID']);
        $archivedInfoModifyLimit = isset($limit['归档信息修改权限'])? $limit['归档信息修改权限'] :'';
        $restartContractLimit = isset($limit['打开合同权限'])? $limit['打开合同权限'] : '';
        $this->assign('archivedInfoModifyLimit', $archivedInfoModifyLimit);
        $this->assign('restartContractLimit', $restartContractLimit);

        $this->c_page ();
    }

    /***********************************合同列表\页面**************************************************************/

    /**
     * 变更查看0页面
     */
    //查看客户合同号
    function c_toMaintenance()
    {
        if (!empty ($_GET['lastAdd'])) {
            $this->assign('lastAdd', $_GET['lastAdd']);
        } else {
            $this->assign('lastAdd', '');
        }
        if (!empty ($_GET['lastChange'])) {
            $this->assign('lastChange', $_GET['lastChange']);
        } else {
            $this->assign('lastChange', '');
        }
        $this->view('maintenance-list');
    }

    //查看Tab
    function c_showViewTab()
    {
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->view('showView-tab');
    }

    //变更后查看页
    function c_showView()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('invoiceLimitR', "1");
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        //获取新开票类型
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $ExtInfo = $this->service->getContractExtFields($_GET['id']);
        $invoiceValuesArr = isset($ExtInfo['invoiceValues'])? util_jsonUtil::decode($ExtInfo['invoiceValues']) : array();
        $i = 0;
        $invoiceType = "";
        $typeArr = isset($typeArr['KPLX'])? $typeArr['KPLX'] : array();
        array_unshift($invoiceValueArr,'');
        foreach ($typeArr as $k => $v) {
            $dataCodeArr[] = $v['dataCode'];
            if (in_array($v['dataCode'], $invoiceCodeArr) || ($invoiceValuesArr && isset($invoiceValuesArr[$v['dataCode']]))) {
                $invoiceVal = isset($invoiceValuesArr[$v['dataCode']])? $invoiceValuesArr[$v['dataCode']] : $invoiceValueArr[$i];
                $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="1"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <span id="$v[dataCode]Money" class="rimless_text formatMoney" >{$invoiceVal}</span></span>
EOT;
            } else {
                $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="0"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
            }
            $i++;
        }
        //获取合同审批人（审批通过后的审批人/默认会有第一步审批人）
        $appArr = explode(",", $obj['appNameStr']);
        //权限过滤,如果是合同负责人和区域负责人、合同创建人、审批人，则不限制字段权限过滤
        if (!in_array($_SESSION['USER_ID'], $appArr) && $obj['areaPrincipalId'] != $_SESSION['USER_ID'] && $obj['createId'] != $_SESSION['USER_ID'] && $obj['prinvipalId'] != $_SESSION['USER_ID'] && $actType != 'audit') {
            $obj = $this->service->filterWithoutField('合同金额', $obj, 'keyForm', array(
                'contractMoney',
                'contractTempMoney',
                'exgross',
                'costEstimates',
                'costEstimatesTax'
            ));
            //开票类型
            if (isset($this->service->this_limit['财务金额']) && !empty($this->service->this_limit['财务金额'])) {
                $this->assign('invoiceLimitR', "1");
            } else {
                $this->assign('invoiceLimitR', "0");
            }
            //合同文本权限(2012-10-10谢工提出将合同文本权限并到合同金额，取消原合同文本权限)
            if (isset($this->service->this_limit['合同金额']) && !empty($this->service->this_limit['合同金额'])) {
                $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                if ($file2 == "暂无任何附件" && !empty($obj['originalId'])) {
                    $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
                }
                $this->assign('file2', $file2);
            } else {
                $this->assign('file2', '【没有相关权限】');
            }
        } else {
            $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
            if ($file2 == "暂无任何附件" && !empty($obj['originalId'])) {
                $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
            }
            $this->assign('file2', $file2);
        }
        // 成本概算千分位处理，加密状态下前端js格式化会出现NaN的情况
        if ($obj['costEstimatesTax'] != '******') {
            $obj['costEstimatesTax'] = number_format($obj['costEstimatesTax'], 2);
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //变更记录
        if (!empty($obj['originalId'])) {
            $cid = $obj['originalId'];
        } else {
            $cid = $obj['id'];
        }
        $changeReason = $this->service->getChangeReasonById($cid);
        $this->assign('changeReason', $changeReason);
        if ($obj['sign'] == 1) {
            $this->assign('sign', '是');
        } else {
            $this->assign('sign', '否');
        }
        if ($obj['shipCondition'] == 0) {
            $this->assign('shipCondition', '立即发货');
        } else {
            $this->assign('shipCondition', '通知发货');
        }
        //附件
        $file = $this->service->getFilesByObjId($obj['id'], false);
        if ($file == "暂无任何附件" && !empty($obj['originalId'])) {
            $file = $this->service->getFilesByObjId($obj['originalId'], false);
        }
        $this->assign('file', $file);


        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
        $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
        $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//        $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));

        //是否
        $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
        $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
        $this->assign("exgrossval", EXGROSS);

        $regionDao = new model_system_region_region();
        $areaPrincipal = $regionDao->find(array("id" => $obj['areaCode']), null, "areaPrincipal");
        $this->assign('AreaLeaderNow', $areaPrincipal['areaPrincipal']);

        //售前商机费用
        $chanceCost = $this->service->getChanceCostByid($obj['originalId']);
        $this->assign("chanceCost", $chanceCost);

        //新开票类型
        $dataCode = implode(",", $dataCodeArr);
        $this->assign("invoiceType", $invoiceType);
        $this->assign("dataCode", $dataCode);
        $this->view('showView');
    }

    //变更前查看页
    function c_showViewOld()
    {
        $conId = $_GET['id'];
        $obj = $this->service->getLastContractInfo_d($conId);
        if (empty ($obj)) {
            echo '<span>无变更信息</span>';
        } else {

            foreach ($obj as $key => $val) {
                $this->assign($key, $val);
            }
            if ($obj['sign'] == 1) {
                $this->assign('sign', '是');
            } else {
                $this->assign('sign', '否');
            }
            if ($obj['shipCondition'] == 0) {
                $this->assign('shipCondition', '立即发货');
            } else {
                $this->assign('shipCondition', '通知发货');
            }
            //附件
            $file = $this->service->getFilesByObjId($obj['id'], false);
            if ($file == "暂无任何附件" && !empty($obj['originalId'])) {
                $file = $this->service->getFilesByObjId($obj['originalId'], false);
            }
            $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
            if ($file2 == "暂无任何附件" && !empty($obj['originalId'])) {
                $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
            }
            $this->assign('file', $file);
            $this->assign('file2', $file2);

            $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
            $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
            $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
            $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//            $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));
            //新开票类型
            //获取新开票类型
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';
            foreach ($typeArr as $val) {
                foreach ($val as $v) {
                    $dataCodeArr[] = $v['dataCode'];
                    if (in_array($v[dataCode], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="1"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="0"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                    }
                    $i++;
                }
            }
            $this->assign("invoiceType", $invoiceType);
            $dataCode = implode(",", $dataCodeArr);
            $this->assign("dataCode", $dataCode);
            //是否
            $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
            $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
            $this->view('showViewOld');
        }

    }

    //变更明细查看页
    function c_toshowChangeView()
    {
        $goodsId = $_GET['id'];
        $contractId = $_GET['contractId'];
        $this->assign('goodsId', $goodsId);
        $this->assign('contractId', $contractId);
        $this->assign('isTemp', $_GET['isTemp']);
        $this->view('showchangeview');
    }

    /*
	 * 跳转到合同主表列表
	 */
    function c_page()
    {
        if (!empty ($_GET['lastAdd'])) {
            $this->assign('lastAdd', $_GET['lastAdd']);
        } else {
            $this->assign('lastAdd', '');
        }
        if (!empty ($_GET['lastChange'])) {
            $this->assign('lastChange', $_GET['lastChange']);
        } else {
            $this->assign('lastChange', '');
        }

        $assLimit = isset($this->service->this_limit['销售助理']) ? $this->service->this_limit['销售助理'] : '';
        isset($_GET['autoload']) ? $this->assign('autoload', $_GET['autoload']) : $this->assign('autoload', ''); # 自动加载
        $this->assign('assLimit', $assLimit);

        $this->view('list');
    }

    /**
     * 财务确认 收入核算方式列表
     */
    function c_incomeAccountingList()
    {

        $this->view('incomeAccountingList');
    }

    /**
     * 跳转到新增合同主表页面
     */
    function c_toAdd()
    {
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");

        // 配置中的限制开票类型 PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        $invoiceType = '';
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                } else {
                    $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();" /></span>
EOT;
                }
            }
        }
        $dataCode = implode(',', $dataCodeArr);
        //借试用转销售物料id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('dataCode', $dataCode);
        $this->assign('ids', $ids);
        $this->assign('createTime', date('Y-m-d'));
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //没有后续在加
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);

        //获取公司名称
        $branchDao = new model_deptuser_branch_branch();
        $companyInfo = $branchDao->getByCode($_SESSION['Company']);
        $this->assign('businessBelong', $_SESSION['Company']);
        $this->assign('businessBelongName', $companyInfo['NameCN']);

        $this->assign('signSubject', $_SESSION['Company']);
        $this->assign('signSubjectName', $companyInfo['NameCN']);

        $this->assign('formBelong', $_SESSION['COM_BRN_PT']);
        $this->assign('formBelongName', $_SESSION['COM_BRN_CN']);
        //设置盖章类型
        //		$stampConfigDao = new model_system_stamp_stampconfig();
        //		$stampArr = $stampConfigDao->getStampType_d();
        //		$this->showSelectOption ( 'stampType', null , true , $stampArr);//盖章类型
        //合同编号是否手工输入
        //开票类型
        $this->assign('invoiceType', $invoiceType);
        $this->assign('contractInput', ORDER_INPUT);
        //大数据部区域特殊处理
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //当前登录人为大数据部区域销售人员的，要做特殊处理
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $areaCode = dsjAreaId;
                $areaName = $rs['areaName'];
                $areaPrincipalId = $rs['areaPrincipalId'];
                $areaPrincipal = $rs['areaPrincipal'];
                //执行区域这里写死
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '大数据部';
            }
        }
        $this->assign('areaCode', isset($areaCode) ? $areaCode : '');
        $this->assign('areaName', isset($areaName) ? $areaName : '');
        $this->assign('areaPrincipalId', isset($areaPrincipalId) ? $areaPrincipalId : '');
        $this->assign('areaPrincipal', isset($areaPrincipal) ? $areaPrincipal : '');
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);

        $this->view('add', true);
    }

    /**
     *  销售助理 操作处理（附件上传，申请盖章）
     */
    function c_handleDispose()
    {
        $handleType = $_GET['handleType'];
        if ($handleType == "FJSC") {
            $this->assign("handle", "1");
            $this->c_toUploadFile();
        } else if ($handleType == "GZSQ") {
            $this->assign("handle", "1");
            $this->c_toStamp();
        } else if ($handleType == "YSWJ") {
            $this->assign("handle", "1");
            $this->c_toCheckFile();
        }
    }

    /**
     * 跳转到编辑合同主表页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->view('edit');
    }

    /**
     * 跳转到编辑客户合同号页面
     */
    function c_toMaintenanceEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('maintenance-edit');
    }

    /**
     * 修改客户合同号
     */
    function c_editMaintenance($isEditInfo = false)
    {
        //		$this->permCheck (); //安全校验
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            msg('编辑成功！');
        }
    }

    /**
     * 修改对象
     */
    function c_edit($isEditInfo = false)
    {
        //		$this->permCheck (); //安全校验
        $this->checkSubmit();
        $rows = $_POST[$this->objName];
        // 处理物料的相关信息
        foreach ($rows['material'] as $k => $v){
            if($v['isDel'] != 1){
                $rows['material'][$k]['isDel'] = 0;
            }
            unset($rows['material'][$k]['id']);
        }

        // 整合开票类型信息的Json数据 PMS 647
        $invoiceJsonData = "";
        if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
            $catchArr = array();
            $i = 0;
            if(in_array('HTBKP',$rows['invoiceCode'])){
                $catchArr['HTBKP'] = "";
                $i = 1;
            }
            foreach ($rows['invoiceValue'] as $k => $v){
                if($v !== '' && ($v >= 0 || $v <= 0)){
                    $catchArr[$rows['invoiceCode'][$i]] = $v;
                    $i++;
                }
            }
            $invoiceJsonData = util_jsonUtil::encode($catchArr);
        }

        //借试用转销售关联商机处理
        if (isset($_GET['turnChanceIds'])) {
            $rows['turnChanceIds'] = $_GET['turnChanceIds'];
        }
        $act = isset($_GET['act']) ? $_GET['act'] : "";
        // 验证合同信息
        if ($checkResult = $this->service->checkContractMoney_d($rows)) {
            $act = '';
        }

        $id = $this->service->edit_d($rows, $act);
        if($id){
            $this->service->invoiceTypeRecord($rows['id'],$invoiceJsonData);
        }
        if ($checkResult) {
            msg($checkResult);
        }

        if ($id && $act == "app") {
            if (!empty($rows['chanceId'])) {
                $chanceDao = new model_projectmanagent_chance_chance();
                $chanceDao->updateChanceNewDate($rows['chanceId'], $id);
            }
            //海外部门提交的合同直接提交审批
            if ($_SESSION['DEPT_ID'] == hwDeptId) {
                succ_show('controller/contract/contract/ewf_index_hw_list.php?actTo=ewfSelect&billId=' . $id);
            }
            if ($id == "confirm") {
                msg("合同已提交确认成本概算");
            } else {
                //获取审批部门id 串
                $deptIds = $this->service->getDeptIds($rows);
                $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
                if (!empty($deptIds)) {
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                } else {
                    $deptIdStr = $configDeptIds;
                }
                $deptIdStrArr = explode(",", $deptIdStr);
                $deptIdStrArr = array_unique($deptIdStrArr);
                $deptIdStr = implode(",", $deptIdStrArr);
                if ($rows['winRate'] == "50%") {
                    succ_show('controller/contract/contract/ewf_index_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                }
            }
        } else {
            if ($id) {
                //				$msg=$this->getLangByKey("addSuccess");
                if (!empty($rows['chanceId'])) {
                    $chanceDao = new model_projectmanagent_chance_chance();
                    $chanceDao->updateChanceNewDate($rows['chanceId'], '');
                }
                msg("编辑成功");
            } else {
                msg('编辑失败');
            }
        }
    }

    /**
     * 修改对象
     * 仅提供海外使用
     */
    function c_hwedit($isEditInfo = false)
    {
        //		$this->permCheck (); //安全校验
        $object = $_POST[$this->objName];
        if ($this->service->hwedit_d($object, $isEditInfo)) {
            msg('编辑成功！');
        }
    }

    /**
     * 修改对象
     */
    function c_editstar($isEditInfo = false)
    {
        //		$this->permCheck (); //安全校验
        $_POST[$this->objName]['id'] = $_GET['id'];
        $_POST[$this->objName]['sign'] = $_REQUEST['value'];
        $object = $_POST[$this->objName];
        if ($this->service->updateById($object)) {
            msg('编辑成功！');
        }
    }

    /**
     * 跳转到查看合同主表页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //售前商机费用
        $chanceCost = $this->service->getChanceCostByid($_GET['id']);
        $this->assign("chanceCost", $chanceCost);

        $this->assign("exgrossval", EXGROSS);
        $this->view('view');
    }

    /**
     * 审批页面用到的查看
     */
    function c_toViewApp()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('sign', '是');
        } else {
            $this->assign('sign', '否');
        }
        if ($obj['shipCondition'] == 0) {
            $this->assign('shipCondition', '立即发货');
        } else {
            $this->assign('shipCondition', '通知发货');
        }
        //附件
        $file = $this->service->getFilesByObjId($obj['id'], false);
        $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
        $this->assign('file', $file);
        $this->assign('file2', $file2);

        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
        $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
        $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
        $this->view('view-app');
    }

    /**
     * 点击产品查看发货清单
     */
    function c_toViewEqu()
    {
        $this->assign('conProductId', $_GET['id']);
        $this->assign('contractId', $_GET['contractId']);
        $this->view('view-equ');
    }

    /**
     *  我的合同
     */
    function c_myContract()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('todo', isset($_GET['todo']) ? 1 : 0); // 待办标识
        $this->view('mycontract');
    }

    /**
     * 个人合同列表--HR
     */
    function c_contractByuser()
    {
        $this->assign('userId', $_GET['USER_ID']);
        $this->view('contractbyuserlist');
    }

    /**
     *  确认合同成本概算（服务）
     */
    function c_confirmCostEstimates()
    {
        $this->view('confirmCostEstimates');
    }

    /**
     *  确认合同成本概算（研发）
     */
    function c_confirmCostRdpro()
    {
        $this->view('confirmCostRdpro');
    }

    /**
     * 成本概算确认页面
     */
    function c_confirmCostView()
    {

        $type = "oa_contract_contract";
        $this->assign("serviceId", $_GET['id']);
        $this->assign("serviceType", $type);
        $text = $type . "2";
        $this->assign("serviceType2", $text);
        $obj = $this->service->get_d($_GET['id']);
        //数据渲染
        $this->assignFunc($obj);
        $this->assign("engConfirmName", $_SESSION['USERNAME']);
        $this->assign("engConfirmId", $_SESSION['USER_ID']);
        $this->assign("engConfirmDate", date("Y-m-d"));
        $type = $_GET['type'];
        $this->assign("type", $type);
        if ($type == "Ser") {
            $this->assign("costMoney", $obj['serCost']);
        } else if ($type == "Rd") {
            $this->assign("costMoney", $obj['rdCost']);
        }
        $costType = $obj['isSubAppChange'];
        if ($costType == '1') {
            $this->assign("costType", "合同变更");
            $mid = $this->service->findChangeId($_GET['id']);
            $conId = $mid;
        } else {
            $this->assign("costType", "合同建立");
            $conId = $_GET['id'];
        }
        $this->assign("contractId", $conId);

        //成本确认产品线html
        $costLimit = $this->service->this_limit['成本确认'];
        $ruArr = $this->service->costinfoView($costLimit, $conId);
        $this->assign("costInfo", $ruArr['str']);
        $this->assign("costAppRemark", $ruArr['remark']);

        $this->view('confirmCostView');
    }

    /**
     * 成本概算确认页面
     */
    function c_confirmCostApp()
    {
        $obj = $this->service->get_d($_GET['contractId']);

        //数据渲染
        $this->assignFunc($obj);
        $this->assign("engConfirmName", $_SESSION['USERNAME']);
        $this->assign("engConfirmId", $_SESSION['USER_ID']);
        $this->assign("engConfirmDate", date("Y-m-d"));
        $type = $_GET['type'];
        $this->assign("type", $type);
        if ($type == "Ser") {
            $this->assign("costMoney", $obj['serCost']);
        } else if ($type == "Rd") {
            $this->assign("costMoney", $obj['rdCost']);
        }
        $costType = $obj['isSubAppChange'];
        if ($costType == '1') {
            $this->assign("costType", "合同变更");
            $mid = $this->service->findChangeId($_GET['contractId']);
            $this->assign("contractId", $mid);
        } else {
            $this->assign("costType", "合同建立");
            $this->assign("contractId", $_GET['contractId']);
        }
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->get_d($_GET['id']);

        //判断是否存在 同一产线，不同类型产品
        $isDeff = $this->service->deffLinePro($costArr, $_GET['contractId']);
        $this->assign("isdeff", $isDeff);
        //预计毛利率
        $cMoney = $obj['contractMoney'];
        if ($isDeff == '2') {
            $cid = $_GET['contractId'];
            $productline = $costArr['productLine'];
            $sql = "select sum(confirmMoney) as allMoney,sum(if(issale='0',confirmMoney,0)) as serMoney,sum(if(issale='1',confirmMoney,0)) as saleMoney from oa_contract_cost where contractId = '" . $cid . "' and productLine = '" . $productline . "'";
            $allMoney = $this->service->_db->getArray($sql);
            $costArr['confirmMoney'] = $allMoney[0]['allMoney'];
            $costArr['serMoney'] = $allMoney[0]['serMoney'];
            $costArr['saleMoney'] = $allMoney[0]['saleMoney'];
        }
        $bMoney = $costArr['confirmMoney'];
        if ($obj['contractType'] == "HTLX-ZLHT") {
            $days = abs($this->service->getChaBetweenTwoDate($obj['beginDate'], $obj['endDate'])); //日期天数
            $saleCostTemp = $bMoney / 720;
            $costEstimates = bcmul($days, $saleCostTemp, 2);
            $exGrossTemp = bcdiv(($cMoney - $costEstimates), $cMoney, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
        } else {
            $exGrossTemp = bcdiv(($cMoney - $bMoney), $cMoney, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
        }
        $this->assignFunc($costArr);
        $this->assign("exgross", $exGross);
        //详细物料
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulistCost($_GET['contractId'], $costType);
        $this->assign('equList', $equlist);
        $this->assign('productLine', $costArr['productLine']);

        $this->view('confirmCostApp');
    }

    /**
     * 提交成本概算确认
     */
    function c_subConfirmCost()
    {
        $arr = $_POST[$this->objName];
        $contractId = $arr['contractId'];
        //获取合同信息
        $rows = $this->service->getContractInfo($contractId);
        //处理附件
        $this->service->uploadfile_d($arr);
        if ($arr['costType'] == '合同建立') {
            $type = "add";
        } else if ($arr['costType'] == '合同变更') {
            $type = $rows['originalId'];
        }
        //处理并插入合同成本确认明细
        $this->service->handleCostInfo($arr, $type);
        //处理确认信息-并返回预计毛利率
        $exGross = $this->service->handleSubConfirmCoseNew($contractId);
        if ($exGross === 'none') {
            $this->c_updateEngCostExaState($contractId);
            $this->c_updateEngConfirm($contractId);
            msg("确认成功！请等待其他执行区域成本确认！");
        } else {
            // 销售合同产品线是仪器仪表及在线教育的合同毛利率大于70%的做完成本概算后就直接完成审批 PMS2373 2017-01-09
            $infoArr['exGross'] = $exGross;
//            $noAudit = $this->noAuditChk($contractId,$infoArr);
            $noAudit = false;
            if($noAudit){
                // 服务概算确认
                $this->c_updateEngCostExaState($contractId);
                $this->c_updateEngConfirm($contractId);

                $contractObj = $this->service->getContractInfo($contractId);
                if($contractObj['exgross'] > 70){// 重新检查一次,避免变更出现更新数据失败返回错误的毛利率,导致判断错误直接通过审批
                    if($contractObj['isSubAppChange'] == 0){// 新录入,非变更
                        // 自动通过审批,并补充相关信息
                        $dateObj = array(
                            'id' => $contractId,
                            'standardDate' => $contractObj['standardDate'],
                            'dealStatus' => '1',
                            'ExaStatus' => '完成',
                            'ExaDTOne' => date("Y-m-d"),
                            'state' => '2',
                            'isSubAppChange' => 0
                        );
                        $this->service->updateById($dateObj);
                        $this->service->dealAfterAudit_d($contractId);// 在盖章列表添加信息
                        $this->service->confirmContractWithoutAudit_d($contractId);// 确认合同
                        msg("此合同无须审批，提交成功！");
                    }else{// 变更合同
                        $this->service->updateById(array('id' => $contractId, 'ExaStatus' => '完成')); // 临时记录设置审批通过
                        $this->service->confirmChangeNoAudit($contractId,1);
                        msg("此合同无须审批，提交成功！");
                    }
                }else{
                    $this->c_subConfirmCostAppNew($contractId, $exGross);
                }
            }else{
                $this->c_subConfirmCostAppNew($contractId, $exGross);
            }
        }
//         msg("确认成功！已提交至执行部门经理审核！");
    }

    /**
     * 成本确认领导审核
     */
    function c_subConfirmCostApp()
    {
        $act = isset($_GET['act']) ? $_GET['act'] : "app";
        $arr = $_POST[$this->objName];
        $contractId = $arr['contractId'];

        //获取合同信息
        $rows = $this->service->getContractInfo($contractId);

        if ($arr['costType'] == '合同建立') {
            $type = "add";
        } else if ($arr['costType'] == '合同变更') {
            $type = $rows['originalId'];
        }

        $this->service->handleCostApp($act, $arr, $type, $rows);

        //处理确认信息-并返回预计毛利率
        $exGross = $this->service->handleSubConfirmCose($arr, $rows, $type);
        if ($act == 'back') {
            msg("已打回至执行部门成本确认！");
        } else if ($exGross === 'none') {
            msg("确认成功！请等待其它执行部门审核！");
        } else {
            $handleDao = new model_contract_contract_handle();
            //如果是变更，全部确认后更新合同的确认状态
            if ($type != 'add') {
                //变更重置产品线成本确认状态
                $costDao = new model_contract_contract_cost();
                $costDao->returnStateByCid($type, $contractId);
                //更新服务类成本确认标志位 完成
                $this->service->endTheEngTig($rows['originalId']);
                //变更操作记录
                $handleDao->handleAdd_d(array(
                    "cid" => $rows['originalId'],
                    "stepName" => "提交审批",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
            } else {
                $this->service->endTheEngTig($contractId);
                $handleDao->handleAdd_d(array(
                    "cid" => $contractId,
                    "stepName" => "提交审批",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
            }
            //审批流调用业务状态更新参数
            $isdeff = $arr['isdeff']; //同类型产品不同产线标识
            $productLine = $arr['productLine']; //产品线
            $costId = $arr['id']; //产线审核数据id

            //传至审批流内的变量数据，
            $proId = $isdeff . "," . $productLine . "," . $costId;

            if ($arr['costType'] == '合同建立') {
                if ($exGross < EXGROSS) {
                    succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                }
            } else if ($arr['costType'] == '合同变更') {
                if ($exGross < EXGROSS) {
                    succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                } else {
                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                }
            }
        }
    }

    /**
     * 成本确认领导审核(新)
     * @author weijb
     * @Date 2015年10月15日 11:18:28
     * @description 苏经理提出新需求，去掉成本审批。
     * 抽出原subConfirmCostApp方法并作调整，用于发货物料确认，或者提交成本概算确认后执行。
     */
    function c_subConfirmCostAppNew($contractId, $exGross)
    {
        // 获取合同信息
        $rows = $this->service->getContractInfo($contractId);

        if ($rows['isSubAppChange'] == '1') {
            $type = $rows['originalId'];
        } else {
            $type = "add";
        }

        $noNeedAudit = false;
        if($rows['contractType'] == 'HTLX-PJGH' && $exGross >= 50 ){// 零配件合同概算毛利率大于等于50时,无需审批 PMS 594
            $noNeedAudit = true;
        }

        //处理确认信息-并返回预计毛利率
//     	$exGross = $this->service->handleSubConfirmCoseNew($contractId);
//     	if ($exGross == 'none') {
//             msg("确认成功！请等待其他执行区域成本确认！");
//         } else {
// 	        $handleDao = new model_contract_contract_handle();
// 	    	//如果是变更，全部确认后更新合同的确认状态
// 	    	if ($type != 'add') {
// 	    		//变更重置产品线成本确认状态
// 	    		$costDao = new model_contract_contract_cost();
// 	    		$costDao->returnStateByCid($type, $contractId);
// 	    		//更新服务类成本确认标志位 完成
// 	    		$this->service->endTheEngTig($rows['originalId']);
// 	    		//变更操作记录
// 	    		$handleDao->handleAdd_d(array(
// 	    				"cid"=> $rows['originalId'],
// 	    				"stepName"=> "提交审批",
// 	    				"isChange"=> 2,
// 	    				"stepInfo"=> "",
// 	    		));
// 	    	} else {
// 	    		$this->service->endTheEngTig($contractId);
// 	    		$handleDao->handleAdd_d(array(
// 	    				"cid"=> $contractId,
// 	    				"stepName"=> "提交审批",
// 	    				"isChange"=> 0,
// 	    				"stepInfo"=> "",
// 	    		));
// 	    	}

        if ($type != 'add') {
            if($noNeedAudit){
                $this->service->updateById(array('id' => $contractId, 'ExaStatus' => '完成')); // 临时记录设置审批通过
                $this->service->confirmChangeNoAudit($contractId,1);
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                msg("此变更无须审批，变更成功！");
            }else if($rows['contractType'] == 'HTLX-PJGH'){
                succ_show('controller/contract/contract/ewf_index_change_pjht.php?actTo=ewfSelect&billId=' . $contractId);
            }else if ($exGross < EXGROSS) {
                succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $contractId);
            } else {
                succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $contractId);
            }
        } else {
            if($noNeedAudit){
                // 销售确认物料
                $this->service->confirmEqu_d($rows['id'], "add", $rows['isSubAppChange']);
                $this->c_updateSaleCostExaState($contractId);//更新概算审核状态

                // 自动通过审批,并补充相关信息
                $dateObj = array(
                    'id' => $contractId,
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1',
                    'ExaStatus' => '完成',
                    'ExaDTOne' => date("Y-m-d"),
                    'state' => '2',
                    'isSubAppChange' => 0

                );
                $this->service->updateById($dateObj);
                $this->service->dealAfterAudit_d($contractId);// 在盖章列表添加信息

                $this->service->confirmContractWithoutAudit_d($contractId);// 确认合同
                msg("此合同无须审批，提交成功！");
            }else if($rows['contractType'] == 'HTLX-PJGH'){
                succ_show('controller/contract/contract/ewf_index_pjht.php?actTo=ewfSelect&billId=' . $contractId);
            }else if ($exGross < EXGROSS) {
                succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId=' . $contractId);
            } else {
                succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId=' . $contractId);
            }
        }
//         }
    }

    /**
     * 更新销售类成本概算审核状态
     */
    function c_updateSaleCostExaState($contractId)
    {
        $costDao = new model_contract_contract_cost();
        $costDao->update(array('contractId' => $contractId, 'issale' => 1), array('ExaState' => 1));
    }

    /**
     * 更新服务类成本概算审核状态
     */
    function c_updateEngCostExaState($contractId)
    {
        $costDao = new model_contract_contract_cost();
        $costDao->update(array('contractId' => $contractId, 'issale' => 0), array('ExaState' => 1));
    }

    /**
     * 通知发货列表
     */
    function c_shipCondition()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('shipcondition');
    }

    /**
     * 合同查看页面
     */
    function c_toViewTab()
    {
        //		$this->permCheck (); //安全校验
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->assign('contractType', $rows['contractType']);

        $this->display('viewTab');
        //		$this->display('view-tab');
    }

    /**
     * 合同查看Tab---关闭信息
     */
    function c_toCloseInfo()
    {
        $rows = $this->service->get_d($_GET['id']);
        if ($rows['state'] == '3' || $rows['state'] == '7') {
            foreach ($rows as $key => $val) {
                $this->assign($key, $val);
            }
            $this->display('closeinfo');
        } else {
            echo '<span>暂无相关信息</span>';
        }
    }

    /*********************销售助理列表功能*********************************/
    /**
     * 销售助理列表页
     */
    function c_otherList()
    {
        $this->view("otherList");
    }


    /*********************销售助理列表功能********END*************************/
    /**********************开票收款权限部分*********************************/
    /**
     * 开票tab
     */
    function c_toInvoiceTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['开票信息'])) {
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**
     * 开票申请tab
     */
    function c_toInvoiceApplyTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['开票信息'])) {
            $url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**
     * 到款tab
     */
    function c_toIncomeTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['到款信息'])) {
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**********************开票收款权限部分*********************************/

    /********************** S 工程项目部分 *********************************/
    /**
     * 项目合同列表
     * 2012-04-09
     * edit by kuangzw
     */
    function c_listForEngineering()
    {
        $this->view('listforEngineering');
    }

    /**
     * 项目合同列表pagejson
     */
    function c_esmContractJson()
    {
        $service = $this->service;
        $rows = array();
        $goodsLimitStr = "";

        //省份权限设置
        $provinceArr = array();

        //首先获取对应的办事处权限id
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $productLineLimit = $limitArr['产品线'];
        $exeDeptLimit = $limitArr['执行区域'];

        $esmProductType = ESMPRODUCTTYPE;
        $esmContract = ESMCONTRACT;
        //合同特殊过滤
        if ($esmProductType || $esmContract) {
            $goodsLimitStr = "sql: and (";

            //产品部分
            if ($esmProductType) {
                $goodsArr = explode(',', $esmProductType);
                $goodsLimitStr .= "(";
                foreach ($goodsArr as $k => $v) {
                    if ($k == 0) {
                        $goodsLimitStr .= "FIND_IN_SET($v,c.goodsTypeStr)";
                    } else {
                        $goodsLimitStr .= "or FIND_IN_SET($v,c.goodsTypeStr)";
                    }
                    $k++;
                }
                $goodsLimitStr .= ") ";
            }
            //合同类型部分
            if ($esmContract) {
                $contractArr = explode(',', $esmContract);
                if ($goodsArr) $goodsLimitStr .= " or (";

                //配置省份权限
                $goodsLimitStr .= " c.contractType in ('" . implode($contractArr, "','") . "')";
                if ($goodsArr) $goodsLimitStr .= ")";
            }
            $goodsLimitStr .= ")";
        }
        if (!empty($productLineLimit) && !empty($exeDeptLimit)) {
            if ($goodsLimitStr) {
                if (strstr($productLineLimit, ';;') == false) {
                    $productLineLimitArr = explode(",", $productLineLimit);
                    $prolineLimitStr = " and (";
                    foreach ($productLineLimitArr as $k => $v) {
                        if ($k == 0) {
                            $prolineLimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                        } else {
                            $prolineLimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                        }
                        $k++;
                    }
                    $prolineLimitStr .= ")";
                    $goodsLimitStr .= $prolineLimitStr;
                }
                if (strstr($exeDeptLimit, ';;') == false) {
                    $exeDeptLimitArr = explode(",", $exeDeptLimit);
                    $exeDeptLimitStr = " and (";
                    foreach ($exeDeptLimitArr as $k => $v) {
                        if ($k == 0) {
                            $exeDeptLimitStr .= "FIND_IN_SET('" . $v . "',exeDeptStr)";
                        } else {
                            $exeDeptLimitStr .= "or FIND_IN_SET('" . $v . "',exeDeptStr)";
                        }
                        $k++;
                    }
                    $exeDeptLimitStr .= ")";
                    $goodsLimitStr .= $exeDeptLimitStr;
                }
                $service->getParam($_REQUEST);
                $service->searchArr['mySearchCondition'] = $goodsLimitStr;
                $service->sort = 'c.createTime';
                $rows = $service->page_d();
                if (!empty ($rows)) {
                    //安全码
                    $rows = $this->sconfig->md5Rows($rows);
                    $conProDao = new model_contract_conproject_conproject();
                    foreach ($rows as $k => $v) {
                        $proRate = $conProDao->getSurplusProportionByCid($v['id']);
                        if (!empty($proRate)) {
                            $rows[$k]['projectRate'] = 100 - $proRate;
                        }
                    }
                }
            }
        } else {
            $rows = "";
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /********************** E 工程项目部分 *********************************/

    /**********************************************************************************************************************/
    /**
     * 单独附件上传页面
     */
    function c_toUploadFile()
    {
        $this->assign("serviceId", $_GET['id']);
        $this->assign("serviceType", $_GET['type']);
        $text = $_GET['type'] . "2";
        $this->assign("serviceType2", $text);
        $this->display('uploadfile');
    }

    function c_toCheckFile()
    {
        $this->assign("serviceId", $_GET['id']);
        $text = $_GET['type'] . "3";
        $this->assign("serviceType3", $text);
        $this->display('checkfile');
    }

    /**
     * 单独附件上传方法
     */
    function c_uploadfile()
    {
        $row = $_POST[$this->objName];
        if(isset($row['checkFile'])){
            $upSql = "update oa_contract_contract set checkFile = '有' where id= ".$row['serviceId'];
            $this->service->_db->query($upSql);
        }
        $id = $this->service->uploadfile_d($row);
        if ($id && $_GET['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $row['serviceId'], "handleType" => "FJSC"));
            msg('添加成功！');
        } else {
            if ($id) {
                msg('添加成功！');
            } else {
                msg('添加失败！');
            }
        }
    }

    /**
     *
     *附件批量下载页面
     */
    function c_toDownFile()
    {
        //add chenrf 20130417 附件下载权限
        /**********start************/
        $limit = $this->service->this_limit['附件下载'];
        if ($limit != '1') {
            msg('无附件下载权限');
            exit;
        }
        /**********end************/

        $this->assign("serviceId", $_GET['id']); //合同ID
        $this->assign("serviceType", $_GET['type']); //附件类型
        $this->assign("contractName", $_GET['contractName']); //合同名称
        $text = $_GET['type'] . "2";
        $this->assign("serviceType2", $text);
        $file = $this->service->getFilesByObjId($_GET['id'], false);
        $this->assign('file', $file);
        $file2 = $this->service->getFilesByObjId($_GET['id'], false, 'oa_contract_contract2');
        $this->assign('file2', $file2);
        $this->view('downfile');
    }

    /**
     * 多合同附件批量下载页面
     */
    function c_toDownAllFile()
    {
        $this->assign("ids", $_GET['ids']); //合同ID
        $this->assign("serviceType", $_GET['type']); //附件类型
        $this->assign("serviceType2", $_GET['type'] . "2"); //附件类型
        $this->view('downallfile');
    }

    /**
     * 多合同附件批量下载
     */
    function c_downAllFile()
    {
        $managementDao = new model_file_uploadfile_management();
        $managementDao->downAllFileByIds($_GET['ids'], $_GET['type'], $_GET['filename']);
    }

    /**
     * 合同combogrid 选择合同页面
     */
    function c_selectContract()
    {
        $this->assign('showButton', $_GET['showButton']);
        $this->assign('showcheckbox', $_GET['showcheckbox']);
        $this->assign('checkIds', $_GET['checkIds']);
        $this->view('selectcontract');
    }

    /**********************************end**********************************************************************************/
    /******************************************处理数据方法********************************************************************************/

    /**********************************end**********************************************************************************/
    /******************************************处理数据方法********************************************************************************/

    /*
	 * ajax根据合同ID 获取产品内的部门信息（用于审批流）
	 */
    function c_ajaxFlowDeptIds()
    {
        //$this->permDelCheck ();
        try {
            $rows = $this->service->getContractInfo($_POST['id']);
            //获取审批部门id 串
            $deptIds = $this->service->getDeptIds($rows);
            $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
            if (!empty($deptIds)) {
                $deptIdStr = $configDeptIds . "," . $deptIds;
            } else {
                $deptIdStr = $configDeptIds;
            }
            $deptIdStrArr = explode(",", $deptIdStr);
            $deptIdStrArr = array_unique($deptIdStrArr);
            $deptIdStr = implode(",", $deptIdStrArr);
            echo $deptIdStr;
        } catch (Exception $e) {
            echo "";
        }
    }

    /**
     * 列表提交审批 处理
     */
    function c_ajaxSubApp()
    {
        try {
            $id = $_POST['id'];
            $sql = "update oa_contract_contract set isSubApp = '1',dealStatus='0' where id='" . $id . "'";
            $this->service->query($sql);
            $constSql = "delete from oa_contract_cost  where contractId = '" . $id . "'";
            $this->service->query($constSql);
            $conArr = $this->service->get_d($id);
            $tomail = $this->service->costConUserIdBycid($conArr['newProLineStr']);
            $content = array(
                "contractCode" => $conArr['contractCode'],
                "contractName" => $conArr['contractName'],
                "customerName" => $conArr['customerName']
            );
            $this->service->mailDeal_d("contractCost_Confirm", $tomail, $content);
            if (!empty($id)) {
                $updateB = "update oa_contract_equ_link set ExaStatus='未提交' where contractId=$id";
                $this->service->_db->query($updateB);
                //              //删除销售类确认的临时记录
                //              $delSql = "delete from oa_contract_equ_link where contractId = '".$id."'";
                //              $this->service->query($delSql);
                //              //删除已确认的合同物料信息
                //              $delEqu = "delete from oa_contract_equ where contractId = '".$id."' and isBorrowToorder != '1'";
                //              $this->service->query($delEqu);
            }
            $handleDao = new model_contract_contract_handle();
            $handleDao->handleAdd_d(array(
                "cid" => $id,
                "stepName" => "提交成本确认",
                "isChange" => 0,
                "stepInfo" => "",
            ));

            echo "1";
        } catch (Exception $e) {
            echo "";
        }
    }

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit();
        $rows = $_POST[$this->objName];
        //借试用转销售关联商机处理
        if (isset($_GET['turnChanceIds'])) {
            $rows['turnChanceIds'] = $_GET['turnChanceIds'];
        }

        // 整合开票类型信息的Json数据 PMS 647
        $invoiceJsonData = "";
        if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
            $catchArr = array();
            $i = 0;
            if(in_array('HTBKP',$rows['invoiceCode'])){
                $catchArr['HTBKP'] = "";
                $i = 1;
            }
            foreach ($rows['invoiceValue'] as $k => $v){
                if($v !== '' && ($v >= 0 || $v <= 0)){
                    $catchArr[$rows['invoiceCode'][$i]] = $v;
                    $i++;
                }
            }
            $invoiceJsonData = util_jsonUtil::encode($catchArr);
        }

        $act = isset($_GET['act']) ? $_GET['act'] : "";
        // 验证合同信息
        if ($checkResult = $this->service->checkContractMoney_d($rows)) {
            $act = '';
        }
        $id = $this->service->add_d($rows, $act, $invoiceJsonData);
        if ($checkResult) {
            msgGo($checkResult, '?model=contract_contract_contract&action=mycontract');
        }

        if ($id && $act == "app") {
            if (!empty($rows['chanceId'])) {
                $chanceDao = new model_projectmanagent_chance_chance();
                $chanceDao->updateChanceNewDate($rows['chanceId'], $id);
            }
            //海外部门提交的合同直接提交审批
            if ($_SESSION['DEPT_ID'] == hwDeptId) {
                succ_show('controller/contract/contract/ewf_index_hw_list.php?actTo=ewfSelect&billId=' . $id);
            }
            if ($id == "confirm") {
                msgGo("合同已提交确认成本概算", '?model=contract_contract_contract&action=mycontract');
            } else {
                //获取审批部门id 串
                $deptIds = $this->service->getDeptIds($rows);
                $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
                if (!empty($deptIds)) {
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                } else {
                    $deptIdStr = $configDeptIds;
                }
                $deptIdStrArr = explode(",", $deptIdStr);
                $deptIdStrArr = array_unique($deptIdStrArr);
                $deptIdStr = implode(",", $deptIdStrArr);
                if ($rows['winRate'] == "50%") {
                    succ_show('controller/contract/contract/ewf_index_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                }
            }
        } else {
            if ($id) {
                if (!empty($rows['chanceId'])) {
                    $chanceDao = new model_projectmanagent_chance_chance();
                    $chanceDao->updateChanceNewDate($rows['chanceId'], '');
                    msg("添加成功");
                } else {
                    msgGo("添加成功", '?model=contract_contract_contract&action=mycontract');
                }
            } else {
                msgGo('添加失败！');
            }
        }
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('invoiceLimitR', "1");
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $closeType = isset($_GET['closeType']) ? $_GET['closeType'] : null;

        //获取合同审批人（审批通过后的审批人/默认会有第一步审批人）
        $appArr = explode(",", $obj['appNameStr']);
        //获取父合同信息
        if (!empty($obj['parentId'])) {
            $parentArr = $this->service->get_d($obj['parentId']);
            $this->assign('parentId', $parentArr['id']);
            $this->assign('parentNameV', $parentArr['contractName']);
            $this->assign('parentCode', $parentArr['contractCode']);
        } else {
            $this->assign('parentNameV', "");
            $this->assign('parentCode', "");
        }

        $invoiceTypeInfoArr = $this->service->makeInvoiceValueArr($obj);

        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            //权限过滤,如果是合同负责人和区域负责人、合同创建人、审批人，则不限制字段权限过滤
            if (!in_array($_SESSION['USER_ID'], $appArr) && $obj['areaPrincipalId'] != $_SESSION['USER_ID'] && $obj['createId'] != $_SESSION['USER_ID'] && $obj['prinvipalId'] != $_SESSION['USER_ID'] && $actType != 'audit') {
                $obj = $this->service->filterWithoutField('合同金额', $obj, 'keyForm', array(
                    'contractMoney',
                    'contractTempMoney',
                    'exgross',
                    'costEstimates',
                    'saleCost',
                    'serCost',
                    'saleCostTax',
                    'costEstimatesTax'
                ));
                //开票类型
                if (isset($this->service->this_limit['财务金额']) && !empty($this->service->this_limit['财务金额'])) {
                    $this->assign('invoiceLimitR', "1");
                } else {
                    $this->assign('invoiceLimitR', "0");
                }
                //合同文本权限(2012-10-10谢工提出将合同文本权限并到合同金额，取消原合同文本权限)
                //				if(isset($this->service->this_limit['合同文本']) && !empty($this->service->this_limit['合同文本']))
                if (isset($this->service->this_limit['合同金额']) && !empty($this->service->this_limit['合同金额'])) {
                    $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                    $this->assign('file2', $file2);
                } else {
                    $this->assign('file2', '【没有相关权限】');
                }
            } else {
                $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                $this->assign('file2', $file2);
            }
            // 成本概算千分位处理，加密状态下前端js格式化会出现NaN的情况
            if ($obj['costEstimatesTax'] != '******') {
                $obj['costEstimatesTax'] = number_format($obj['costEstimatesTax'], 2);
            }
            //详细物料成本权限
            $this->assign('equCoseLimit', $this->service->this_limit['详细物料成本']);
            //数据渲染
            $this->assignFunc($obj);
            if ($obj['sign'] == 1) {
                $this->assign('sign', '是');
            } else {
                $this->assign('sign', '否');
            }
            if ($obj['shipCondition'] == "0") {
                $this->assign('shipCondition', '立即发货');
            } else if ($obj['shipCondition'] == "1") {
                $this->assign('shipCondition', '通知发货');
            } else {
                $this->assign('shipCondition', '');
            }
            if ($obj['isRenewed'] == "0") {
                $this->assign('isRenewed', '新签合同');
            } else if ($obj['isRenewed'] == "1") {
                $this->assign('isRenewed', '续签合同');
            } else {
                $this->assign('isRenewed', '');
            }
            //附件
            $file = $this->service->getFilesByObjId($obj['id'], false);
            $this->assign('file', $file);
            $file3 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract3');
            $this->assign('file3', $file3);


            $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
            $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
            $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
            $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//            $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));

            //是否
            $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
            $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
            $this->assign('actType', $actType);
            $this->assign('closeType', $closeType);

            $this->assign("exgrossval", EXGROSS);

            $regionDao = new model_system_region_region();
            $areaPrincipal = $regionDao->find(array("id" => $obj['areaCode']), null, "areaPrincipal");
            $this->assign('AreaLeaderNow', $areaPrincipal['areaPrincipal']);
            //是否是销售变更标识
            $this->assign('isSubAppChange', $obj['isSubAppChange']);
            //获取商机
            $chanceDao = new model_projectmanagent_chance_chance();
            $chanceArr = $chanceDao->get_d($obj['chanceId']);
            $this->assign('chanceCode', $chanceArr['chanceCode']);
            $this->assign('chanceId', $chanceArr['id']);

            //售前商机费用
            $chanceCost = $this->service->getChanceCostByid($_GET['id']);
            $this->assign("chanceCost", $chanceCost);
            //获取新开票类型
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';

            // 因为开票类型前面多了一个不需要显示的类别,所以开票金额数组前需要加一个空的元素,以便于开票类型的索引顺序相匹配
            array_unshift($invoiceValueArr,'');
            $typeArr = $typeArr['KPLX'];
            foreach ($typeArr as $v) {
                $dataCodeArr[] = $v['dataCode'];
                if ($v['dataCode'] == 'HTBKP' && in_array($v['dataCode'], $invoiceCodeArr)) {
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="1"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"></span>
EOT;
                }
//                    else if (in_array($v['dataCode'], $invoiceCodeArr)) {
                else if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="1"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceTypeInfoArr[$v['dataCode']]}" class="rimless_text formatMoney" /></span>
EOT;
                } else {
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="0"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                }
                $i++;
            }

            $dataCode = implode(',', $dataCodeArr);
            $this->assign('dataCode', $dataCode);
            $this->assign("invoiceType", $invoiceType);
            if ($obj['isSame'] == 0) {
                $this->assign("isSameText", '否');
            } else {
                $this->assign("isSameText", '是');
            }
            // 是否框架合同
            if ($obj['isFrame'] == 0) {
                $this->assign("isFrame", '否');
            } else {
                $this->assign("isFrame", '是');
            }
            $this->view('view');
        } else {
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';

            // 配置中的限制开票类型 PMS 647
            $otherDatasDao = new model_common_otherdatas();
            $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

            // 因为开票类型前面多了一个不需要显示的类别,所以开票金额数组前需要加一个空的元素,以便于开票类型的索引顺序相匹配
            array_unshift($invoiceValueArr,'');
            foreach ($typeArr as $val) {
                foreach ($val as $v) {
                    $dataCodeArr[] = $v['dataCode'];
                    $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                    $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                    if ($v['dataCode'] == 'HTBKP') {
                        //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                            // 如果是禁用的开票类型且之前有填过金额的,则将对应的发票金额去掉
                            $checkedStr = ($disabledStr == "disabled")? "" : 'checked="checked"';
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" $checkedStr  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        } else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                            // 如果是禁用的开票类型且之前有填过金额的,则将对应的发票金额去掉
                            $checkedStr = ($disabledStr == "disabled")? "" : 'checked="checked"';
                            $value = ($disabledStr == "disabled")? "" : $invoiceTypeInfoArr[$v['dataCode']];
                            $displayStr = ($disabledStr == "disabled")? 'style="display:none"' : "";
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" $checkedStr onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                                <span id="$v[dataCode]Hide" $displayStr> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$value" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        } else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                                <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    }
                    $i++;
                }
            }
            $this->assign('dataCode', implode(',', $dataCodeArr));
            //数据渲染
            $this->assignFunc($obj);

            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //附件
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $file3 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract3');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->assign('file3', $file3);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            $this->showDatadicts(array(
                'contractNature' => $obj['contractType']
            ), $obj['contractNature']);
            $this->showDatadicts(array(
                'invoiceType' => 'FPLX'
            ), $obj['invoiceType']);
//            $this->showDatadicts(array(
//                'customerType' => 'KHLX'
//            ), $obj['customerType']);
            $this->showDatadicts(array(
                'module' => 'HTBK'
            ), $obj['module']);

            // 补充客户类型中文名
            $customerTypeName = '';
            $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
            if($obj['customerType'] != ''){
                $result = $this->service->_db->getArray($sql);
                $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
            }
            $this->assign('customerTypeName', $customerTypeName);

            //设置盖章类型
            //			$stampConfigDao = new model_system_stamp_stampconfig();
            //			$stampArr = $stampConfigDao->getStampType_d();
            //			$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//盖章类型

            //借试用转销售物料id
            $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
            $this->assign('ids', $ids);
            $this->assign('invoiceType', $invoiceType);
            $this->assign('newInvoiceId', isset($objArrs['id']) ? $objArrs['id'] : "");
            //大数据部区域特殊处理
            if (dsjAreaId) {
                $regionDao = new model_system_region_region();
                $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
                //当前登录人为大数据部区域销售人员的，要做特殊处理
                if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                    $this->assign('areaCode', dsjAreaId);
                    $this->assign('areaName', $rs['areaName']);
                    $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                    $this->assign('areaPrincipal', $rs['areaPrincipal']);
                    //执行区域这里写死
                    $exeDeptCode = 'GCSCX-17';
                    $exeDeptName = '大数据部';
                }
            }
            $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
            $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');
            if (isset ($_GET['perm']) && $_GET['perm'] == 'hwedit') { //海外编辑
                $this->assign('isHwEdit', 1);
                $this->view('hwedit');
            } else {
                $this->view('edit', true);
            }
        }
    }

    /**
     * 商机转合同页面
     */
    function c_toAddchance()
    {
        //数据渲染
        $chanceDao = new model_projectmanagent_chance_chance();
        $obj = $chanceDao->get_d($_GET['chanceId']);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // 配置中的限制开票类型 PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        // 因为开票类型前面多了一个不需要显示的类别,所以开票金额数组前需要加一个空的元素,以便于开票类型的索引顺序相匹配
        array_unshift($invoiceValueArr,'');
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if($disabledStr == "disabled"){// 如果变更时原来存在禁用项的值了,保持其勾选状态不变且禁止勾选操作
                            $invoiceType .= <<<EOT
                                <input type="checkbox"  checked="checked" disabled>
                                <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]"  onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked"  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]"  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if($disabledStr == "disabled"){// 如果变更时原来存在禁用项的值了,保持其勾选状态不变且禁止勾选操作,但金额可修改
                            $invoiceType .= <<<EOT
                            <input type="checkbox"  checked="checked" disabled>
                            <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">
                            <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }else {
                            $invoiceType .= <<<EOT
                            <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">
                            <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }
        }
        $dataCode = implode(',', $dataCodeArr);
        $this->assignFunc($obj);
        //附件
        $file = $chanceDao->getFilesByObjId($_GET['chanceId'], true);
        $this->assign('file', $file);
        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['chanceType']);
        $this->showDatadicts(array('contractNature' => $obj['chanceType']), $obj['chanceNature']);
        //$this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
        $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);

        // 补充客户类型中文名
        $customerTypeName = '';
        $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
        if($obj['customerType'] != ''){
            $result = $this->service->_db->getArray($sql);
            $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
        }
        $this->assign('customerTypeName', $customerTypeName);

        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //没有后续在加
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);

        $this->assign('signSubjectName', $obj['formBelongName']);
        $this->assign('signSubject', $obj['formBelong']);

        $this->assign('dataCode', $dataCode);
        $this->assign('invoiceType', $invoiceType);
        //合同编号是否手工输入
        $this->assign('contractInput', ORDER_INPUT);
        //大数据部区域特殊处理
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //当前登录人为大数据部区域销售人员的，要做特殊处理
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $this->assign('areaCode', dsjAreaId);
                $this->assign('areaName', $rs['areaName']);
                $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                $this->assign('areaPrincipal', $rs['areaPrincipal']);
                //执行区域这里写死
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '大数据部';
            }
        }
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');
        //由商机名称带出合同名称
        $this->assign('contractName', $obj['chanceName']);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
        $this->view('add-chance', true);
    }

    /**
     *跳转到excel上传页面
     */
    function c_toExcel()
    {
        $this->assign("dateTime", date("Y-m-d"));
        $this->display('importexcel');
    }

    /**
     * 延迟发货列表 --通知发货
     */
    function c_informShipments()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('informShipments');
    }

    /**
     * 延迟发货列表 --通知发货
     */
    function c_informS()
    {
        $inform = $_POST["inform"];
        $flag = $this->service->inform_d($inform);
        msg("发货通知已发送！");
    }

    /**
     * 关闭合同页面
     */
    function c_closeContract()
    {
        $row = $this->service->get_d($_GET['id']);
        $esmDao = new model_engineering_project_esmproject();
        $esmArr = $esmDao->getProjectList_d(array($_GET['id']));

        if (!empty($esmArr)) {
            $esmTip = 0;
            $esmCode = "";
            foreach ($esmArr as $v) {
                if(strpos($v['id'],"c") === false){

                    if (($v['status'] == 'GCXMZT03' || $v['status'] == 'GCXMZT06')) {
                        $esmTip = 0;
                        $esmCode .= $v['projectCode'] . ",";
                    }else{
                        $esmTip = 1;
                    }
                }
            }
        } else {
            $esmTip = 0;
        }
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('userName', $_SESSION['USERNAME']);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('dateTime', date('Y-m-d  H:i:s'));
        if($row['DeliveryStatus'] == 'WFH' && ( strpos($row['goodsTypeStr'],"11") === false ) && $esmTip == 0){
            $this->display('close');
        }else{
            if (($row['DeliveryStatus'] != 'TZFH' && $row['DeliveryStatus'] != 'YFH') || $esmTip != 0) {
                echo "<b>相关 发货需求 / 工程项目  尚未关闭，暂不允许发起异常关闭。<b/>";
            } else if((!empty($row['invoiceMoney']) && $row['invoiceMoney'] != '0') || (!empty($row['incomeMoney']) && $row['incomeMoney'] != '0')){
                echo "<b>因本合同有开票或收款，根据财务部要求，不允许发起异常关闭，请通过坏账流程处理。<b/>";
            }else {
                $this->display('close');
            }
        }
    }

    /**
     * 关闭合同
     */
    function c_close($isEditInfo = false)
    {
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST['close'];
        $id = $this->service->close_d($object, $isEditInfo);
        if ($id && $_GET['actType'] == "app") {
            succ_show('controller/contract/contract/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
        } else {
            msg('关闭成功！');
        }
    }

    /**
     * 关闭审批后跳转方法
     */
    function c_confirmCloseApprovalNo()
    {
        //审批流回调方法
        $this->service->workflowCallBack_close($_GET['spid']);
        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //防止重复刷新
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     *  财务确认收入核算方式
     */
    function c_incomeAcc()
    {
        $row = $this->service->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('incomeAcc');
    }

    function c_incomeAccEdit()
    {
        $rows = $_POST['acc'];
        $id = $this->service->incomeAccEdit_d($rows);
        $msg = $_POST["msg"] ? $_POST["msg"] : '确认成功！';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * 跳转--合同共享
     */
    function c_toShare()
    {
        $row = $this->service->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('shareName', $_SESSION['USERNAME']);
        $this->assign('shareNameId', $_SESSION['USER_ID']);
        $this->assign('shareDate', date('y-m-d'));
        //获取以共享的人员
        $dao = new model_contract_common_share();
        $toshareArr = $dao->getShareByConId($_GET['id']);
        if (!empty($toshareArr)) {
            $toshareName = '';
            $toshareNameId = '';
            foreach ($toshareArr as $val) {
                $toshareName .= $val['toshareName'] . ",";
                $toshareNameId .= $val['toshareNameId'] . ",";
            }
            $toshareName = rtrim($toshareName, ",");
            $toshareNameId = rtrim($toshareNameId, ",");
            $this->assign('toshareName', $toshareName);
            $this->assign('toshareNameId', $toshareNameId);
        } else {
            $this->assign('toshareName', "");
            $this->assign('toshareNameId', "");
        }

        $this->display('share');
    }

    /**
     * 合同审批完成后跳转方法
     */
    function c_configContract()
    {
        //审批流回调方法
        $this->service->workflowCallBack($_GET['spid']);

        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //防止重复刷新
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     * 跳转申请盖章页面
     */
    function c_toStamp()
    {
        //	 	$this->permCheck (); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //附件添加{file}
        // 		$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2'));
        $this->assign('file', '暂无任何附件');
        $this->assign('applyDate', day_date);

        //设置盖章类型
        //		$stampConfigDao = new model_system_stamp_stampconfig();
        //		$stampArr = $stampConfigDao->getStampType_d();
        //		$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//盖章类型

        //当前盖章申请人
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);

        //设置业务经办人为当前登录人
        $this->assign('attnId', $_SESSION['USER_ID']);
        $this->assign('attn', $_SESSION['USERNAME']);
        $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
        $this->assign('attnDept', $_SESSION['DEPT_NAME']);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->assign('contractType', 'HTGZYD-04');
        $this->assign('contractTypeName', $this->getDataNameByCode('HTGZYD-04'));
        //$this->view('stamp');
        $this->view('stamp-add');
    }

    /**
     * 新增盖章信息操作
     */
    function c_stamp()
    {
        $object = $_POST[$this->objName];
        $rs = $this->service->stamp_d($object);
        if ($rs && $object['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $_POST['contractId'], "handleType" => "GZSQ"));
            msg('申请成功！');
        } else {
            if ($rs) {
                msg("申请成功！");
            } else {
                msg("申请失败！");
            }
        }
    }

    /***********************************************************************************************************************************************************/
    /********************************************合 同 变 更 ****************************************************************************************/
    /**
     * 跳转到合同变更页面
     */
    function c_toChange()
    {
        $this->permCheck(); //安全校验
        //临时记录id
        $tempId = isset($_GET['tempId']) ? $_GET['tempId'] : '';
        //判断是否存在临时保存的记录，包括物料确认打回，审批打回的记录
        if (empty($tempId)) {
            $sql = "select tempId,ExaStatus,changeReason from oa_contract_changlog where id = (select max(id) as id from oa_contract_changlog " .
                "where objType = 'contract' and objId = " . $_GET['id'] . " and changeManId = '" . $_SESSION['USER_ID'] . "')";
            $rs = $this->service->_db->getArray($sql);
            $tempId = !empty($rs) && $rs[0]['ExaStatus'] != AUDITED ? $rs[0]['tempId'] : '';
        } else {
            $sql = "select changeReason from oa_contract_changlog where objType = 'contract' and tempId = " . $tempId;
            $rs = $this->service->_db->getArray($sql);
            $changeReason = !empty($rs) ? $rs[0]['changeReason'] : '';
        }
        $this->assign('tempId', empty($tempId) ? '' : $tempId);
        $this->assign('changeReason', isset($_GET['tempId']) && !empty($changeReason) ? $changeReason : ''); //变更原因
        $contractId = isset($_GET['tempId']) ? $_GET['tempId'] : $_GET['id'];
        $this->assign('contractId', $contractId);
        $obj = $this->service->get_d($contractId);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //id始终为源单id
        if (isset($_GET['tempId'])) {
            $this->assign('id', $_GET['id']);
        } else {
            $this->assign('turnChanceIds', '');
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }

        $invoiceTypeInfoArr = $this->service->makeInvoiceValueArr($obj);

        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // 配置中的限制开票类型 PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        // 因为开票类型前面多了一个不需要显示的类别,所以开票金额数组前需要加一个空的元素,以便于开票类型的索引顺序相匹配
        array_unshift($invoiceValueArr,'');
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                    if(isset($invoiceTypeInfoArr[$v['dataCode']])){
                        if($disabledStr == "disabled"){// 如果变更时原来存在禁用项的值了,保持其勾选状态不变且禁止勾选操作
                            $invoiceType .= <<<EOT
                                <input type="checkbox"  checked="checked" disabled>
                                <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }else{
                           $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    // 如果变更时原来存在禁用项的值了,保持其勾选状态不变且禁止勾选操作,但金额可修改
                    // if (in_array($v['dataCode'], $invoiceCodeArr)) {
                    if(isset($invoiceTypeInfoArr[$v['dataCode']])){
                        $invoiceValue = $invoiceTypeInfoArr[$v['dataCode']];
                        if($disabledStr == "disabled"){
                            $invoiceType .= <<<EOT
                            <input type="checkbox"  checked="checked" disabled>
                            <input style="display:none" type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValue" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }else{
                            $invoiceType .= <<<EOT
                            <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValue" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }

        }
        $dataCode = implode(',', $dataCodeArr);
        //附件
        $file = $this->service->getFilesByObjId($contractId, true);
        $file2 = $this->service->getFilesByObjId($contractId, true, 'oa_contract_contract2');
        $file3 = $this->service->getFilesByObjId($contractId, true, 'oa_contract_contract3');
        $this->assign('dataCode', $dataCode);
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->assign('file3', $file3);

        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['contractType']);
        $this->showDatadicts(array('contractNature' => $obj['contractType']), $obj['contractNature']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
        $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);

        // 补充客户类型中文名
        $customerTypeName = '';
        $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
        if($obj['customerType'] != ''){
            $result = $this->service->_db->getArray($sql);
            $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
        }
        $this->assign('customerTypeName', $customerTypeName);

        //借试用转销售物料id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('invoiceType', $invoiceType);
        $this->assign('ids', $ids);
        //大数据部区域特殊处理
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //当前登录人为大数据部区域销售人员的，要做特殊处理
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $this->assign('areaCode', dsjAreaId);
                $this->assign('areaName', $rs['areaName']);
                $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                $this->assign('areaPrincipal', $rs['areaPrincipal']);
                //执行区域这里写死
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '大数据部';
            }
        }
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');

        $this->view('change', true);
    }

    // 单独变更合同产品
    function c_toChangePro()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }
        //附件
        $file = $this->service->getFilesByObjId($obj['id'], true);
        $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['contractType']);
        $this->showDatadicts(array('contractNature' => $obj['contractType']), $obj['contractNature']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
        $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);

        //借试用转销售物料id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('ids', $ids);
        $this->view('changePro');
    }

    /**
     * 前台ajax 验证是否需要提交审批
     */
    function c_changeSubAjax()
    {
        $rows = $_POST[$this->objName];
        $hasNewMaterial = $this->c_compareMaterial($rows);
        $deptIds = $this->c_compareProduct($rows, 'ajax');
        $isChangeMoney = $this->c_compareMoney($rows, 'ajax');
        //判断纸质合同是否
        $paperContractFlag = $this->c_comparePaperContract($rows);
        if (empty($deptIds) && ($isChangeMoney === 'none' || $isChangeMoney == '1') && $paperContractFlag === 'none' && $hasNewMaterial == '0') {
            echo "0";
        } else if ($paperContractFlag == '1') {
            echo "1";
        } else {
            echo "1";
        }
    }

    /**
     * 变更合同
     */
    function c_change()
    {
        $this->checkSubmit(); // 重复提交验证
        try {
            $rows = $_POST[$this->objName];

            // 整合开票类型信息的Json数据 PMS 647
            $invoiceJsonData = "";
            if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
                $catchArr = array();
                $i = 0;
                if(in_array('HTBKP',$rows['invoiceCode'])){
                    $catchArr['HTBKP'] = "";
                    $i = 1;
                }
                foreach ($rows['invoiceValue'] as $k => $v){
                    if($v !== '' && ($v >= 0 || $v <= 0)){
                        $catchArr[$rows['invoiceCode'][$i]] = $v;
                        $i++;
                    }
                }
                $invoiceJsonData = util_jsonUtil::encode($catchArr);
            }

            //借试用转销售关联商机处理
            if (isset($_GET['turnChanceIds'])) {
                $rows['turnChanceIds'] = $_GET['turnChanceIds'];
            }

            // 验证合同信息
            if ($checkResult = $this->service->checkContractMoney_d($rows)) {
                msgGo($checkResult, '?model=contract_contract_contract&action=mycontract');
            }

            //保存
            if ($rows['isSub'] == '0') {
                $this->service->change_d($rows,null,null,$invoiceJsonData);
                msg("保存成功！");
            } else {
                $handleDao = new model_contract_contract_handle();
                //比较变更的产品与原产品的差异  并返回 审批部门id串
                $deptIds = $this->c_compareProduct($rows);
                //海外部门提交的变更
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    if ($rows['isChangeSub'] == '1') { // 走审批
                        $id = $this->service->change_d($rows, 0, $deptIds,$invoiceJsonData);
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['id'],
                            "stepName" => "合同变更",
                            "isChange" => 1,
                            "stepInfo" => "",
                        ));
                        succ_show('controller/contract/contract/ewf_index_change_hw.php?actTo=ewfSelect&billId=' . $id);
                    } else {
                        /*直接变更 不走审批流方法*/
                        $this->service->changeNotApp_d($rows,$invoiceJsonData);
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['id'],
                            "stepName" => "合同变更",
                            "isChange" => 1,
                            "stepInfo" => "无需审批",
                        ));
                        msg("变更成功！");
                    }
                }

                //获取区域扩展字段值
                $regionDao = new model_system_region_region();
                $expand = $regionDao->getExpandbyId($rows['areaCode']);
                if ($rows['noApp'] == '1') {
                    $this->service->changeNotApp_d($rows,$invoiceJsonData);
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['id'],
                        "stepName" => "合同变更",
                        "isChange" => 1,
                        "stepInfo" => "无需审批",
                    ));
                    msg("变更成功！");
                } else {
                    if (empty($deptIds)) {
                        if ($rows['isChangeSub'] == '1') {
                            $id = $this->service->change_d($rows, 0, null, $invoiceJsonData);
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "合同变更",
                                "isChange" => 1,
                                "stepInfo" => "",
                            ));
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "提交成本确认",
                                "isChange" => 2,
                                "stepInfo" => "",
                            ));
                            $sql = "update oa_contract_changlog set ExaStatus = '物料确认' where objType = 'contract' and tempId = " . $id;
                            $this->service->_db->query($sql);
                            msg("合同已提交确认成本概算!");
                        } else {
                            //比较是否变更金额
                            $isChangeMoney = $this->c_compareMoney($rows);
                            //判断纸质合同是否
                            $paperContractFlag = $this->c_comparePaperContract($rows);
                            if ($isChangeMoney === 'none' && $paperContractFlag === 'none') {
                                /*直接变更 不走审批流方法*/
                                $this->service->changeNotApp_d($rows, $invoiceJsonData);
                                $handleDao->handleAdd_d(array(
                                    "cid" => $rows['id'],
                                    "stepName" => "合同变更",
                                    "isChange" => 1,
                                    "stepInfo" => "无需审批",
                                ));
                                msg("变更成功！");
                            } else {
                                $rows['signStatus'] = '2';
                                if ($isChangeMoney == '1' && $paperContractFlag === 'none') {
                                    /*预计毛利增加， 不走审批流方法*/
                                    $this->service->changeNotApp_d($rows, $invoiceJsonData);
                                    $handleDao->handleAdd_d(array(
                                        "cid" => $rows['id'],
                                        "stepName" => "合同变更",
                                        "isChange" => 1,
                                        "stepInfo" => "无需审批",
                                    ));
                                    msg("变更成功！");
                                } else {
                                    $isChangeMoney; //变更减少的预计毛利率
                                    //走审批
                                    $id = $this->service->change_d($rows, 1, null, $invoiceJsonData);
                                    if ($id) {
                                        $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
                                        if ($deptIds == 'noDept') {
                                            $deptIds = "";
                                        }
                                        $deptIdStr = $configDeptIds . "," . $deptIds;
                                        $deptIdStrArr = explode(",", $deptIdStr);
                                        $deptIdStrArr = array_unique($deptIdStrArr);
                                        $deptIdStr = implode(",", $deptIdStrArr);
                                        if ($isChangeMoney < EXGROSS) {
                                            succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                        } else {
                                            succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($expand == '1' || $deptIds == 'tobo') {
                            //走审批
                            $id = $this->service->change_d($rows, 0, $deptIds, $invoiceJsonData);
                            if ($id) {
                                $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
                                if ($deptIds == 'noDept') {
                                    $deptIds = "";
                                }
                                $deptIdStr = $configDeptIds . "," . $deptIds;
                                $deptIdStrArr = explode(",", $deptIdStr);
                                $deptIdStrArr = array_unique($deptIdStrArr);
                                $deptIdStr = implode(",", $deptIdStrArr);
                                $oldrows = $this->service->getContractInfo($rows['id']);
                                $oldexGross = $oldrows['exgross'];
                                if ($oldexGross < EXGROSS) {
                                    succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                } else {
                                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                }
                            }
                        } else {
                            $id = $this->service->change_d($rows, 0,null ,$invoiceJsonData);
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "合同变更",
                                "isChange" => 1,
                                "stepInfo" => "",
                            ));
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "提交成本确认",
                                "isChange" => 2,
                                "stepInfo" => "",
                            ));
                            $sql = "update oa_contract_changlog set ExaStatus = '物料确认' where objType = 'contract' and tempId = " . $id;
                            $this->service->_db->query($sql);
                            msg("合同已提交确认成本概算!");
                        }
                    }
                }
            }
        } catch (Exception $e) {
            msgBack2("变更失败！失败原因：" . $e->getMessage());
        }
    }


    /**
     * 变更审批后跳转方法
     */
    function c_confirmChangeToApprovalNo()
    {
        //审批流回调方法
        $this->service->workflowCallBack_change($_GET['spid']);

        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //防止重复刷新
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     * 单独变更物料（针对 2012-09-10新版上线前的合同）
     */
    function c_toChangeEqu()
    {
        $this->assign("contractId", $_GET['contractId']);
        $this->view("toChangeEqu");
    }

    /******************************** 合 同 签 收 *********************************************************************************/
    /**
     * 合同签收Tab页
     */
    function c_contractSign()
    {
        $this->display("contractSign");
    }

    /**
     * 未签收的合同
     */
    function c_Signin()
    {
        $this->assign("isfinance", 1);
        $this->view('signin');
    }

    /**
     * 签收合同
     */
    function c_Signins()
    {
        $this->view('signins');
    }

    /**
     * 已签收的合同
     */
    function c_comSignin()
    {
        $this->assign("isfinance", 1);
        $this->view('comsignin');
    }

    /**
     * 签收页面
     */
    function c_signEditView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }

        //新开票类型
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // 因为开票类型前面多了一个不需要显示的类别,所以开票金额数组前需要加一个空的元素,以便于开票类型的索引顺序相匹配
        array_unshift($invoiceValueArr,'');

        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                if ($v['dataCode'] == 'HTBKP') {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }

        }
        $dataCode = implode(',', $dataCodeArr);
        //附件
        $file = $this->service->getFilesByObjId($obj['id'], true);
        $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
        $this->assign('dataCode', $dataCode);
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->showDatadicts(array(
            'contractType' => 'HTLX'
        ), $obj['contractType']);
        $this->showDatadicts(array(
            'contractNature' => $obj['contractType']
        ), $obj['contractNature']);
        $this->showDatadicts(array(
            'invoiceType' => 'FPLX'
        ), $obj['invoiceType']);
        $this->showDatadicts(array(
            'customerType' => 'KHLX'
        ), $obj['customerType']);
        $this->showDatadicts(array(
            'module' => 'HTBK'
        ), $obj['module']);
        //设置盖章类型
        //			$stampConfigDao = new model_system_stamp_stampconfig();
        //			$stampArr = $stampConfigDao->getStampType_d();
        //			$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//盖章类型
        $this->assign("nowDate", date("Y-m-d"));
        $this->assign("invoiceType", $invoiceType);
        $this->assign("newInvoiceId", isset($objArrs['id']) ? $objArrs['id'] : "");
        $this->view('signeditview');

    }

    /**
     * 合同签收
     */
    function c_signInVerify($isEditInfo = false)
    {
        $object = $_POST[$this->objName];
        if ($this->service->signin_d($object, $isEditInfo)) {
            $obj = $this->service->get_d($object['id']);

            $toMailstr[] = $object['prinvipalId'];
            $toMailstr[] = $object['areaPrincipalId'];
            //获取默认发送人
            include(WEB_TOR . "model/common/mailConfig.php");
            $toMailstr[] = isset($mailUser['contractSignEdit']['TO_ID']) ? $mailUser['contractSignEdit']['TO_ID'] : "";
            $toMailstr = implode(",", $toMailstr);

            //发送邮件
            $emailDao = new model_common_mail();
            $content = "各位好：<br/> 合同 “" . $obj['contractName'] . "(" . $obj['contractCode'] . ")” 已由 " . $_SESSION['USERNAME'] . " 签收完成";
            $emailInfo = $emailDao->batchEmail("1", $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractSignEdit', '', null, $toMailstr, $content);

            msgRF('签收完成！');
        }
    }

    /********************************手工改变合同状态方法 （演示用，后续需要添加修改发货状态）*********************************************************************/
    /**
     * 手动改变合同状态 (执行中 ---> 已完成)
     */
    function c_completeOrder()
    {
        $orderId = $_GET['id'];
        $this->service->completeOrder_d($orderId);
    }

    /**
     * 手动改变合同状态 (已完成 --> 执行中)
     */
    function c_exeOrder()
    {
        $orderId = $_GET['id'];
        $this->service->exeOrder_d($orderId);
    }

    /***************************发 货 相 关********************************************************************************************************/
    /**
     *  发货需求列表
     */
    function c_shipments()
    {
        if (isset ($_GET['finish']) && $_GET['finish'] == 1) {
            $this->assign('listJS', 'contract-shipped-grid.js');
        } else {
            $this->assign('listJS', 'contract-shipments-grid.js');
        }
        $this->assign('shipCondition', $_GET['shipCondition']);
        $this->view('shipments-list');
    }

    /**
     *  物料确认需求列表
     */
    function c_assignment()
    {
        $this->view('assign-list');
    }

    /**
     *  发货需求列表
     */
    function c_shipportal()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->assign('listJS', 'contract-ship-portalgrid.js');
        $this->assign('shipCondition', 0);
        $this->view('ship-portallist');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_shipmentsJson()
    {
        $rateDao = new model_stock_outplan_contractrate();
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;

        if (!empty($this->service->this_limit['销售区域'])) {
            if (!strstr($this->service->this_limit['销售区域'], ";;")) {
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'" . $this->service->this_limit['销售区域'] . "')";
            }
            $rows = $service->pageBySqlId("select_shipments");
        } else {
            $rows = "";
        }


        //发货需求进度备注
        $orderIdArr = array();
        foreach ($rows as $key => $val) {
            $orderIdArr[$key] = $rows[$key]['id'];
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        //获取发货计划最大计划发货时间
        $sql = "select max(shipPlanDate) as maxDate,docId from oa_stock_outplan where docType='oa_contract_contract' GROUP BY docId";
        $maxShipArr = $service->_db->getArray($sql);

        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $rows[$key]['contractRate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_contract_contract' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['contractRate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
                //累计天数
                $rows[$key]['grandDays'] = floor((strtotime(date("Y-m-d")) - strtotime($val['ExaDTOne'])) / 86400);
                //预计完成交付日期
                foreach ($maxShipArr as $i => $v) {
                    if ($rows[$key]['id'] == $maxShipArr[$i]['docId']) {
                        $rows[$key]['maxShipPlanDate'] = $maxShipArr[$i]['maxDate'];
                    }
                }

            }
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 获取分页数据转成Json -- 物料确认
     */
    function c_assignmentJson()
    {
        $rateDao = new model_stock_outplan_assignrate();
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        if (!empty($this->service->this_limit['销售区域'])) {
            if (!strstr($this->service->this_limit['销售区域'], ";;")) {
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'" . $this->service->this_limit['销售区域'] . "')";
            }
            $service->searchArr['ExaStatusSql'] = "sql: and (c.ExaStatus in ('完成','变更审批中') or c.isSubApp='1')";
            $service->searchArr['isSellSql'] = "sql: and (c.isSell='1' or c.isSubApp='1')";
            $rows = $service->pageBySqlId("select_assignment");
        } else {
            $rows = "";
        }

        //发货需求进度备注
        $orderIdArr = array();
        foreach ($rows as $key => $val) {
            $orderIdArr[$key] = $rows[$key]['id'];
            //判断如果是变更的单据，查找并替换单据ID
            if ($val['isSubAppChange'] == '1') {
                $mid = $this->service->findChangeId($val['id']);
                $rows[$key]['id'] = $mid;
                $rows[$key]['oldId'] = $val['id'];
            }
        }

        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->searchArr['relDocType'] = 'oa_contract_contract';
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $rows[$key]['contractRate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_contract_contract' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['contractRate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**************************************************发 货 相 关**********END***********************************************************************/

    /************************************************ 合同pageJson 方法 *************************************************************************************************/

    /**
     * 合同信息 列表pageJson
     */
    function c_conPageJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        //过滤型权限设置
        $limit = $this->service->initLimit();
        if ($limit == true) {
            //			$rows = $service->page_d();
            $rows = $service->pageBySqlId('select_gridinfo');

            if (!empty ($rows)) {
                //获取有备注信息的合同id
                $remarkIsArr = $this->service->getRemarkIs();
                // 初始化相关类
                $linkmanDao = new model_contract_contract_linkman(); // 合同联系人
                $esmprojectDao = new model_engineering_project_esmproject(); // 工程项目
                $regionDao = new model_system_region_region();

                $cidStr = '';
                foreach ($rows as $key => $val) {
                    // 判断关联合同是否存在不开票的开票类型,
                    $invoiceCodeArr = explode(",",$val['invoiceCode']);
                    $isNoInvoiceCont = '';
                    foreach ($invoiceCodeArr as $Arrk => $Arrv){
                        if($Arrv == "HTBKP"){
                            $isNoInvoiceCont = '1';
                        }
                    }
                    $rows[$key]['isNoInvoiceCont'] = $isNoInvoiceCont;

                    if (in_array($val['id'], $remarkIsArr)) {
                        $rows[$key]['conflag'] = "1";
                    }
                    //判断并返回合同当前进度状态
                    $exeStautsView = $this->service->exeStatusView_d($val);
                    $rows[$key]['exeStatus'] = $exeStautsView[0];
                    $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                    //查找扩展值
                    //获取区域扩展字段值
                    $expand = $regionDao->getExpandbyId($val['areaCode']);
                    $rows[$key]['expand'] = $expand;
                    //合同费用
//                    $fee = $this->service->getContractFeeAll($val['id']);
//                    $rows[$key]['contractFee'] = $fee;
                    //处理当前列表合同id字符串
                    $cidStr .= $val['id'] . ",";

                    // 处理客户信息
                    $rs = $linkmanDao->findAll(array('contractId' => $val['id']), null, 'linkmanName,telephone');
                    if (!empty($rs)) {
                        foreach ($rs as $k => $v) {
                            if ($k == 0) {
                                $rows[$key]['linkmanName'] = $v['linkmanName'];
                                $rows[$key]['linkmanTel'] = $v['telephone'];
                            } else {
                                $rows[$key]['linkmanName'] .= PHP_EOL . $v['linkmanName'];
                                $rows[$key]['linkmanTel'] .= PHP_EOL . $v['telephone'];
                            }
                        }
                    }
                    // 服务合同执行的项目经理
//                    if ($val['contractType'] == 'HTLX-FWHT') {
                        $rs = $esmprojectDao->findAll(array('contractId' => $val['id'], 'contractType' => 'GCXMYD-01'),
                            null, 'managerName');
                        if (!empty($rs)) {
                            foreach ($rs as $k => $v) {
                                if ($k == 0) {
                                    $rows[$key]['esmManagerName'] = $v['managerName'];
                                } else {
                                    $rows[$key]['esmManagerName'] .= ',' . $v['managerName'];
                                }
                            }
                        }
//                    }

                    $rows[$key]['esmManagerName'] = ($rows[$key]['esmManagerName'] == '')? "-" : $rows[$key]['esmManagerName'];

                    //处理项目费用
                    $rows[$key]['budgetAll'] = $val['proj_budgetAll'];
                    $rows[$key]['curIncome'] = $val['proj_curIncome'];
                    $rows[$key]['feeAll'] = $val['proj_feeAll'];
                    $rows[$key]['conProgress'] = $val['proj_conProgress'];
                    $rows[$key]['gross'] = $val['proj_gross'];
                    $rows[$key]['rateOfGross'] = $val['proj_rateOfGross'];
                    $rows[$key]['comPoint'] = $val['proj_comPoint'];
                    $rows[$key]['icomeMoney'] = $val['proj_icomeMoney'];
                    $rows[$key]['incomeProgress'] = $val['proj_incomeProgress'];
                    $rows[$key]['invoiceProgress'] = $val['proj_invoiceProgress'];

                    $rows[$key]['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
                }

                //合同附件下载权限
                $download = isset ($this->service->this_limit['合同信息导出']) ? $this->service->this_limit['合同信息导出'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['downloadLimit'] = 1;
                    }
                }
                //财务相关日期权限
                $download = isset ($this->service->this_limit['财务相关日期']) ? $this->service->this_limit['财务相关日期'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['financialDate'] = 1;
                    }
                }
                //财务功能控制权限
                $download = isset ($this->service->this_limit['销售助理']) ? $this->service->this_limit['销售助理'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['financial'] = 1;
                    }
                }
                //安全码
                $rows = $this->sconfig->md5Rows($rows);
//                //工作量进度
//                $rows = $this->service->projectProcess_d($rows);

                //统计金额
//                $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
                //这里忘记为什么要unset掉了先放下面
                unset($service->searchArr['advSql']);
                //敏感字段过滤
                $rows = $this->fieldFilter($rows);
                //开票类型税点
                $datadictDao = new model_system_datadict_datadict(); //获取数据字典信息
                $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
                if (!empty($rs)) {
                    $invoiceArr = array();
                    foreach ($rs as $v) {
                        $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
                    }
                    foreach ($rows as $key => $val) {
                        if (!empty($val['invoiceCode'])) {
                            $invoiceCodeArr = explode(',', $val['invoiceCode']);
                            $KPLXSD = array();
                            foreach ($invoiceCodeArr as $v) {
                                array_push($KPLXSD, $invoiceArr[$v]);
                            }
                            $rows[$key]['KPLXSD'] = implode('&', array_unique($KPLXSD));
                        }
                    }
                }
            }
        }

        // PMS 522 合同应收款特殊规则配置处理
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney","icomeMoney","proj_icomeMoney"));

        $arr = array();
        $arr['collection'] = $rows;
        $arr['Sql'] = $service->listSql;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 成本确认列表
     */
    function c_costEstimatesJson()
    {
        set_time_limit(0);
        $service = $this->service;

        // 是否为查看待处理数据处理
        if(isset($_REQUEST['isNoDeal']) && $_REQUEST['isNoDeal'] == "1"){
            $engConfirmCost = isset($_REQUEST['engConfirmCost'])? $_REQUEST['engConfirmCost'] : '';
            $projectStatus = isset($_REQUEST['projectStatus'])? $_REQUEST['projectStatus'] : '';

            if($engConfirmCost == '1' && $projectStatus == '1'){
                $_REQUEST['id'] = "-10";
            }else if($engConfirmCost == '1' && $projectStatus == ''){
                $_REQUEST['projectStatus'] = '0';
            }
        }else{
            unset($_REQUEST['isNoDeal']);
        }

        $service->getParam($_REQUEST);

        //成本确认权限
        $costLimit = $this->service->this_limit['成本确认'];
        $moduleLimit = $this->service->this_limit['板块权限'];
        if (strstr($costLimit, ';;') || strstr($moduleLimit, ";;")) { //权限改为部门，暂无全部，此代码预留以防扩展
            $sql = $this->sqlList("1");
            $rows = $service->pageBySql($sql);
        } else {
            if ($costLimit && $moduleLimit) {
                $costLimitArr = explode(",", $costLimit);
                $costimitStr = "sql: and (";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                    } else {
                        $costimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                    }
                }
                $costimitStr .= " or module IN(" . util_jsonUtil::strBuild($moduleLimit) . ")";
                $costimitStr .= ")";
                /////////
                $costLimitStr = "";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costLimitStr .= "productLine = '" . $v . "'";
                    } else {
                        $costLimitStr .= "or productLine = '" . $v . "'";
                    }
                }
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList($costLimitStr);
                $rows = $service->pageBySql($sql);
            } else if ($costLimit && !$moduleLimit) {
                $costLimitArr = explode(",", $costLimit);
                $costimitStr = "sql: and (";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                    } else {
                        $costimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                    }
                }
                $costimitStr .= ")";
                /////////
                $costLimitStr = "";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costLimitStr .= "productLine = '" . $v . "'";
                    } else {
                        $costLimitStr .= "or productLine = '" . $v . "'";
                    }
                }
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList($costLimitStr);
                $rows = $service->pageBySql($sql);
            } else if (!$costLimit && $moduleLimit) {
                $costimitStr = "sql: and (";
                $costimitStr .= "module IN(" . util_jsonUtil::strBuild($moduleLimit) . ")";
                $costimitStr .= ")";
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList(1);
                $rows = $service->pageBySql($sql);
            } else {
                $rows = "";
            }
        }

        //数据加入安全码
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

    //成本确认列表的查询sql
    function sqlList($costLimitStr)
    {
        $sql = "
            SELECT
                c.*,if(FIND_IN_SET('1', costState) or c.engConfirm=1,1,0) as engConfirmCost
            @FROM
                oa_contract_contract c
            left join
            (
                select
                  GROUP_CONCAT(CAST(ExaState AS char)) as costState,contractId
                from oa_contract_cost
                where ($costLimitStr) and issale=0
                GROUP BY contractId
            )cc
            on c.id=cc.contractId

            WHERE
                1=1 and (FIND_IN_SET(17,goodsTypeStr) or FIND_IN_SET(18,goodsTypeStr))";
        return $sql;
    }

    //我的合同Pagejson
    function c_MyconPageJson()
    {
        $service = $this->service;
        $rows = array();
        //过滤型权限设置
        $limit = true;
        $service->getParam($_REQUEST);
        //增加一个快速搜索【我负责的合同】，只过滤合同负责人为登陆人的合同
//        if (isset($_REQUEST['myContract']) && $_REQUEST['myContract'] == '1') {
            $service->searchArr['prinvipalOrCreateId'] = $_SESSION['USER_ID'];// PMS2537 默认我的合同里面只显示用户所负责的合同; 后续 PMS 566又添加了合同录入人
//        }
        //取数，用于从待办链接跳转进来
        if ($_REQUEST['todo'] == '1') {
            $sql = "SELECT
						*
					FROM
						(
							SELECT
								c.*
							FROM
								oa_contract_contract c
							LEFT JOIN oa_contract_cost t ON c.id = t.contractId
							WHERE
								t.state = '3'
							AND c.isSubAppChange = 0
							GROUP BY
								c.id
							UNION ALL
								SELECT
									c.*
								FROM
									oa_contract_contract c
								LEFT JOIN (SELECT max(id) AS Mid,originalId FROM oa_contract_contract GROUP BY originalId) AS c1 ON c.id = c1.originalId
								LEFT JOIN oa_contract_cost t ON c1.Mid = t.contractId
								WHERE
									c.state = '3'
								AND c.isSubAppChange = 1
								GROUP BY
									c.id
							UNION ALL
								SELECT
									*
								FROM
									oa_contract_contract
								WHERE
									dealStatus = '4'
						) c ";
            //拼装搜索条件
            $where = $service->createQuery("", $service->searchArr);
            $where .= " AND (c.prinvipalId = '" . $_SESSION['USER_ID'] . "' or c.createId = '" . $_SESSION['USER_ID'] . "');";
            $sql .= $where;
            $rows = $service->_db->getArray($sql);
        } else {
            //（省经理数据过滤） 由于版本问题 暂时借用  Tday 字段
            $userId = $_SESSION['USER_ID'];
//         	$fsql="select provinceId,customerType from oa_system_saleperson where personId = '".$userId."'";
            $fsql = "select group_concat(CAST(salesAreaId AS CHAR)) as salesAreaId from oa_system_saleperson where personId = '" . $userId . "'";
            $proLimitArr = $service->_db->getArray($fsql);
//         	$provinceStr = $proLimitArr[0]['provinceId'];
//         	$customerTypeStr = $proLimitArr[0]['customerType'];
            $salesAreaId = $proLimitArr[0]['salesAreaId'];
            if (!empty($salesAreaId)) {
                $salesAreaStr = '';
                $salesAreaArr = explode(",", $salesAreaId);
                foreach ($salesAreaArr as $v) {
                    $salesAreaStr .= "'$v'" . ",";
                }
                $salesAreaStr = rtrim($salesAreaStr, ",");
//         		$conditionProSql = "sql: and (c.prinvipalId='".$_SESSION['USER_ID']."' or c.areaPrincipalId = '".$_SESSION['USER_ID']."'  or (c.contractProvinceId = ".$provinceStr." and c.customerType in (".$customerTypeStr.")))";
                $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "' or c.areaCode in(" . $salesAreaStr . "))";
                $service->searchArr['Tday'] = $conditionProSql;
            } else {
                if ($_SESSION['DEPT_NAME'] == '海外业务组') {
                    $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "'  )";
                } else {
                    $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "'  )";
                }
                $service->searchArr['Tday'] = $conditionProSql;
            }
        }
        if ($limit == true) {
            //			$rows = $service->page_d();
            if ($_REQUEST['todo'] == '0') {
                $rows = $service->pageBySqlId('select_gridinfo');
            }

            if (!empty ($rows)) {
                //获取有备注信息的合同id
                $remarkIsArr = $this->service->getRemarkIs();
                foreach ($rows as $key => $val) {
                    if (in_array($val['id'], $remarkIsArr)) {
                        $rows[$key]['flag'] = "1";
                    }
                    //判断并返回合同当前进度状态
                    $exeStautsView = $this->service->exeStatusView_d($val);
                    $rows[$key]['exeStatus'] = $exeStautsView[0];
                    $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                    //查找扩展值
                    //获取区域扩展字段值
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($val['areaCode']);
                    $rows[$key]['expand'] = $expand;
                    //判读是否需要确认发货物料
                    $confirmEqu = $this->service->getConfirmEqubyId($val['id'], $val['isSubAppChange']);
                    $rows[$key]['confirmEqu'] = $confirmEqu;


                }
                //安全码
                $rows = $this->sconfig->md5Rows($rows);
                //                //工作量进度
                //                $rows = $this->service->projectProcess_d($rows);
                //统计金额
                if ($_REQUEST['todo'] == '0') {
                    $rows = $service->getRowsallMoney_d($rows, "select_contractInfo",1);
                }
                //敏感字段过滤
                //$rows = $this->fieldFilter($rows);
            }
        }

        // PMS 522 合同应收款特殊规则配置处理
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney"));

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 合同敏感字段过滤 - 金额
     */
    function fieldFilter($rows)
    {
        foreach ($rows as $key => $val) {
            //获取合同审批人（审批通过后的审批人/默认会有第一步审批人）
            $appArr = explode(",", $val['appNameStr']);
            if (!in_array($_SESSION['USER_ID'], $appArr) && $val['areaPrincipalId'] != $_SESSION['USER_ID'] && $val['prinvipalId'] != $_SESSION['USER_ID']) {
                $rows[$key] = $this->service->filterWithoutField('合同金额', $rows[$key], 'keyForm', array(
                    'contractMoney',
                    'contractTempMoney',
                    'deductMoney',
                    'badMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('合同金额-概算', $rows[$key], 'keyForm', array(
                    'exgross',
                    'costEstimates',
                    'costEstimatesTax'
                ));

                $rows[$key] = $this->service->filterWithoutField('开票信息', $rows[$key], 'keyForm', array(
                    'invoiceApplyMoney',
                    'invoiceMoney',
                    'uninvoiceMoney',
                    'softMoney',
                    'hardMoney',
                    'repairMoney',
                    'serviceMoney',
                    'surplusInvoiceMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('到款信息', $rows[$key], 'keyForm', array(
                    'incomeMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('财务金额', $rows[$key], 'keyForm', array(
                    'serviceconfirmMoneyAll',
                    'financeconfirmMoneyAll',
                    'financeconfirmPlan',
                    'gross',
                    'rateOfGross',
                    'surOrderMoney',
                    'surincomeMoney'
                ));
            }
        }
        return $rows;
    }

    /************************************************ 合同pageJson 方法 ******END********************************************************************************************/
    /*----------------------------start:仓存接口----------------------------------*/
    /**
     * 新增产品入库时，带出物料清单模板
     */
    function c_getItemListAtRkProduct()
    {
        $contractId = isset ($_POST['contractId']) ? $_POST['contractId'] : null;
        $rows = $this->service->getContractInfo($contractId, array(
            "equ"
        ));
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $listStr = $this->service->showProItemAtRkProduct($rows);
        echo util_jsonUtil :: iconvGB2UTF($listStr);
    }

    /**
     * 新增销售出库时，带出物料清单模板
     */
    function c_getItemListAtCkSales()
    {
        $contractId = isset ($_POST['contractId']) ? $_POST['contractId'] : null;
        $rows = $this->service->getContractInfo($contractId, array(
            "equ"
        ));
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $listStr = $this->service->showProItemAtCkSales($rows);
        echo util_jsonUtil :: iconvGB2UTF($listStr);
    }

    /*----------------------------start:仓存接口----------------------------------*/

    /*************************************设备总汇表 start **************************************/
    /**
     * 合同发货设备-计划统计列表
     */
    function c_shipEquList()
    {
        $equNo = isset ($_GET['productCode']) ? $_GET['productCode'] : "";
        $equName = isset ($_GET['productName']) ? $_GET['productName'] : "";
        $searchArr = array();
        if ($equNo != "") {
            $searchArr['productCode'] = $equNo;
        }
        if ($equName != "") {
            $searchArr['productName'] = $equName;
        }
        $service = $this->service;
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
        $service->__SET('groupBy', "p.productId,p.productNumb");

        $rows = $service->pageEqu_d();
        $this->pageShowAssign();

        $this->assign('equNumb', $equNo);
        $this->assign('equName', $equName);
        $this->assign('list', $this->service->showEqulist_s($rows));
        $this->display('list-equ');
        unset ($this->show);
        unset ($service);
    }

    /***********************************设备总汇表 end *********************************/

    /***************************财务金额导入************************************************************/
    /**
     * 财务金额导入权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 财务金额 导入
     */
    function c_FinancialImportexcel()
    {
        $this->assign("dateTime", date("Y-m-d"));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= $thisYear - 5; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->display("FinancialImportexcel");
    }

    /**
     * 上传EXCEL
     */
    function c_finalceMoneyImport()
    {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $import = $_POST['import'];
        //		$objNameArr = array (
        //			0 => 'contractType',//合同类型
        //			1 => 'contractCode', //合同编号
        //			2 => 'serviceconfirmMoney', //服务确认收入
        //			3 => 'financeconfirmMoney', //财务确认总成本
        //			4 => 'deductMoney'//扣款金额
        //		   ) ;
        $objNameArr = array(
            0 => 'contractCode', //合同编号
            1 => 'money', //金额
        );

        if ($import['importType'] == "初始化导入") {
            $this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
        } else {
            //共用信息 单独
            $infoArr = array(
                "importMonth" => $import['Year'] . $import['Month'], //导入月份
                "moneyType" => $import['importInfo'], //金额类型
                "importName" => $_SESSION['USERNAME'], //导入人
                "importNameId" => $_SESSION['USER_ID'], //导入人ID
                "importDate" => date("Y-m-d"), //导入时间
            );
            $this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);
        }
    }

    /**
     * 手工调用 修改财务导入金额 更新冗余值方法
     */
    function c_handleupdateGross()
    {
        $id = $_GET['contractId'];
        $this->service->updateGross($id);
    }

    /**
     * 验证导入的金额是否已存在
     */
    function c_getFimancialImport()
    {
        $importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
        $importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
        $importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

        if ($importType == "按月导入") {
            $num = $this->service->getFimancialImport_d($importMonth, $importSub);
            echo $num;
        } else {
            echo "0";
        }
    }

    /**
     * 导入金额明细列表Tab
     */
    function c_financialDetailTab()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetailTab");
    }

    /**
     * 导入金额信息
     */
    function c_financialDetail()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetail");
    }

    function c_financialdetailpageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 导入金额信息
     */
    function c_financialImportDetail()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialImportDetail");
    }

    function c_financialImportDetailpageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $sql = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);
        $service->sort = false;
        $rows = $service->pageBySql($sql);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 财务金额统计表
     */
    function c_financialStatistics()
    {
        $this->view("financialStatistics");
    }

    function c_financialStatisticspageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getfinancialStatistics($conId, $tablename, $moneyType);
        $rows = $service->getfinancialStatisticsInitMoney($rows);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /***************************************************************************************/

    /**
     * 跳转到合同统计页面
     */
    function c_toCountContract()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->view("count");
    }

    /**
     * 统计合同信息
     */
    function c_countContract()
    {
        $arr = array();
        //最近新增的合同数量
        $lastAddNum = $this->service->getLastAddContractNum();
        $arr['lastAddNum'] = $lastAddNum;
        //最近新增的合同数量
        $lastChangeNum = $this->service->getLastChangeContractNum();
        $arr['lastChangeNum'] = $lastChangeNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 跳转到合同签约统计页面
     */
    function c_toCountSignContract()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->view("signcount");
    }

    /**
     * 统计已签约未完成合同数量
     */
    function c_countSignContract()
    {
        $arr = array();
        //签约一个月
        $oneMonthNum = $this->service->getMonthContractNum(1);
        $arr['oneMonthNum'] = $oneMonthNum;
        //签约两个月
        $twoMonthNum = $this->service->getMonthContractNum(2);
        $arr['twoMonthNum'] = $twoMonthNum;
        //签约三个月
        $threeMonthNum = $this->service->getMonthContractNum(3);
        $arr['threeMonthNum'] = $threeMonthNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 合同执行状态表
     */
    function c_contractExelist()
    {
        //合同状态数组
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];

        // 判断关联合同是否存在不开票的开票类型,
        $invoiceCodeArr = explode(",",$obj['invoiceCode']);
        $isNoInvoiceCont = false;
        foreach ($invoiceCodeArr as $Arrk => $Arrv){
            if($Arrv == "HTBKP"){
                $isNoInvoiceCont = true;
            }
        }

        //数据渲染
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('isNoInvoiceCont', ($isNoInvoiceCont)? "1" : "");

        // PMS 522 合同应收款特殊规则配置处理
        $SpecRecordsForNoSurincomeArr = $this->service->dealSpecRecordsForNoSurincome();
        $isNoSurincomeMoney = (in_array($obj['customerType'],$SpecRecordsForNoSurincomeArr) || $obj['state']== 7)? 'y' : 'n';
        $surincomeMoney = (in_array($obj['customerType'],$SpecRecordsForNoSurincomeArr) || $obj['state']== 7)? 0 : ($obj['invoiceMoney'] - $obj['incomeMoney']);
        $this->assign('isNoSurincomeMoney', $isNoSurincomeMoney); //财务应收款

        //财务确认信息
        $this->assign('surincomeMoney', $surincomeMoney); //财务应收款
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //财务确认进度
        } else {
            $this->assign('financeconfirmPlan', ""); //财务确认进度
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //毛利
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }
        //合同决算
        $this->assign('conFee',$this->service->getConFeeByid($obj['id']));
        //综合税点
        $this->assign('cRate', $this->service->getTxaRate($obj)*100);
        $this->assign('cRateNum', $this->service->getTxaRate($obj));
        //税后合同额
        $this->assign('contractMoneyRate', round($obj['contractMoney']/(1+$this->service->getTxaRate($obj)),2));
        //合同自检情况
        $objCom = $this->service->objCom_d($obj);
        if($isNoInvoiceCont){
            $objCom['invoiceCheck'] = '<img src="images/icon/heng.png">';
            $objCom['invoiceCheck_t'] = '<img src="images/icon/heng.png">';
        }
        $this->assignFunc($objCom);
        $this->view("contractexelist");
    }



    function c_contractExelistOld()
    {
        //合同状态数组
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];
        //数据渲染
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('surOrderMoney', $obj['contractMoney'] - $obj['incomeMoney']); //合同应收款
        $this->assign('surincomeMoney', $obj['invoiceMoney'] - $obj['incomeMoney']); //财务应收款
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //财务确认进度
        } else {
            $this->assign('financeconfirmPlan', ""); //财务确认进度
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //毛利
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }

        //发货情况
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulist($obj['id']);
        $this->assign('equlist', $equlist);
        $this->view("contractexelistOld");
    }

    //计算百分比
    function getProportion($proMoney, $conMoney)
    {
        $exGrossTemp = bcdiv($proMoney, $conMoney, 4);
        $exGross = bcmul($exGrossTemp, '100', 2);
        return $exGross;
    }

    /**
     * 获取合同处理轨迹
     */
    function c_getContractTracks()
    {
        $list = $this->service->getContractTracks($_GET['contractId']);
        echo util_jsonUtil :: encode($list);
    }

    /***********************************发货需求portlet*****************************/

    /**
     * 跳转到合同统计页面
     */
    function c_toCountShip()
    {
        header("Content-type: text/html;charset=gb2312");
        $planModel = new model_stock_outplan_outplan();
        $showPageShip = $this->service->showCountShipPage_d();
        $showPageOut = $planModel->showCountShipPage_d();
        $this->assign('showPageShip', $showPageShip);
        $this->assign('showPageOut', $showPageOut);
        $this->view("ship-count");
    }

    /**
     * 统计合同信息
     */
    function c_countShip()
    {
        $arr = array();
        //最近新增的合同数量
        $lastAddNum = $this->service->getNotRunShipNum();
        $arr['notRunShipNum'] = $lastAddNum;
        //最近新增的合同数量
        $lastChangeNum = $this->service->getRunningShipNum();
        $arr['runningShipNum'] = $lastChangeNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 添加等级设置
     */
    function c_setGrade()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view("grade");
    }

    /**
     * 将列表的信息设置到Session里去 PMS 2532
     *  -- 当需要传送的信息过长时使用,避免IE浏览器导出时候,因为传入URL的参数过长导致页面无法访问
     */
    function c_setColInfoToSession()
    {
        $_REQUEST = util_jsonUtil::iconvUTF2GBArr($_REQUEST);
        $ColId = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $ColName = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : 'exportContract';

        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        if($records){// 如果存在则更新相关记录
            // $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
            $this->service->_db->query("UPDATE oa_system_session_records SET svalue = '{$ColId}' where userId = '{$_SESSION['USER_ID']}' and skey = 'ColId' and stype = '{$stype}';");
            $this->service->_db->query("UPDATE oa_system_session_records SET svalue = '{$ColName}' where userId = '{$_SESSION['USER_ID']}' and skey = 'ColName' and stype = '{$stype}';");
        }else{
            $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColId', svalue = '{$ColId}';");
            $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColName', svalue = '{$ColName}';");
        }
        echo 1;
    }

    /**
     * 单合同导出
     * @author zengzx
     */
    function c_exportExcel()
    {
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $signStatus = array(
            '0' => '未签收',
            '1' => '已签收',
            '2' => '变更未签收',
        );

        if(!isset($_GET['colId']) && !isset($_GET['colName'])){// 如果前端没传入对应的列ID以及列名,从SESSION中获取
            $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContract';");
            if($records){
                foreach ($records as $record){
                    if(isset($record['skey']) && $record['skey'] == 'ColId'){
                        $colIdStr = $record['svalue'];
                    }else if(isset($record['skey']) && $record['skey'] == 'ColName'){
                        $colNameStr = $record['svalue'];
                    }
                }
                // $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContract';");
            }else{
                $colIdStr = '';
                $colNameStr = '';
            }
        }else{
            $colIdStr = $_GET['colId'];
            $colNameStr = $_GET['colName'];
        }

        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;
        if (isset($_SESSION['advSql'])) {
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }
        $service->getParam($_REQUEST);
        //登录人
//         $appId = $_SESSION['USER_ID'];
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        $rows = $service->listBySqlId('select_gridinfo');
//        if (!empty ($rows)) {
//            //统计金额
//            $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
//            if (isset($service->searchArr['advSql'])) {
//                unset($service->searchArr['advSql']);
//            }
//            //敏感字段过滤
//            $rows = $this->fieldFilter($rows);
//        }

        $arr = array();
        $arr['collection'] = $rows;

        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        // 初始化相关类
        $linkmanDao = new model_contract_contract_linkman(); // 合同联系人
        $esmprojectDao = new model_engineering_project_esmproject(); // 工程项目
        //开票类型税点
        $datadictDao = new model_system_datadict_datadict(); //获取数据字典信息
        $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
        if (!empty($rs)) {
            $invoiceArr = array();
            foreach ($rs as $v) {
                $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
            }
        }

        // PMS 522 合同应收款特殊规则配置处理
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney","icomeMoney","proj_icomeMoney"));

        foreach ($rows as $key => $row) {
            //处理项目费用
            $rows[$key]['budgetAll'] = $row['proj_budgetAll'];
            $rows[$key]['curIncome'] = $row['proj_curIncome'];
            $rows[$key]['feeAll'] = $row['proj_feeAll'];
            $rows[$key]['conProgress'] = bcdiv($row['proj_conProgress'],100,4);
            $rows[$key]['conProgress'] = sprintf("%.4f",$rows[$key]['conProgress']);
            $rows[$key]['gross'] = $row['proj_gross'];
            $rows[$key]['rateOfGross'] = $row['proj_rateOfGross'];
            $rows[$key]['comPoint'] = sprintf("%.4f",$row['proj_comPoint']);
            $rows[$key]['icomeMoney'] = $row['proj_icomeMoney'];
            $rows[$key]['incomeProgress'] = $row['proj_incomeProgress'];
            $rows[$key]['invoiceProgress'] = $row['proj_invoiceProgress'];

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $rows[$key][$index];
            }
            if(isset($colArr['state']) && !empty($colArr['state'])){
                $colIdArr['state'] = isset($stateArr[$row['state']]) ? $stateArr[$row['state']] : '';
            }
            if(isset($colArr['signStatus']) && !empty($colArr['signStatus'])) {
                $colIdArr['signStatus'] = isset($signStatus[$row['signStatus']]) ? $signStatus[$row['signStatus']] : '';
            }
            if(isset($colArr['signSubject']) && !empty($colArr['signSubject'])) {
                $colIdArr['signSubject'] = empty($row['signSubjectName']) ? '' : $row['signSubjectName'];
            }
            if( $row['id'] != ''){
                if(isset($colArr['isNeedStamp']) && !empty($colArr['isNeedStamp'])){
                    if ($row['isNeedStamp'] == '1') {
                        $colIdArr['isNeedStamp'] = '是';
                    } else {
                        $colIdArr['isNeedStamp'] = '否';
                    }
                }

                if(isset($colArr['isRenewed']) && !empty($colArr['isRenewed'])) {
                    if ($row['isRenewed'] == '1') {
                        $colIdArr['isRenewed'] = '是';
                    } else {
                        $colIdArr['isRenewed'] = '否';
                    }
                }

                if (isset($colIdArr['isAcquiring']) && !empty($row['isAcquiring'])) {
                    if ($row['isAcquiring'] == 1) {
                        $colIdArr['isAcquiring'] = '已收单';
                    } else {
                        $colIdArr['isAcquiring'] = '未收单';
                    }
                }
            }

            // 新增信息  By weijb 2015.11.09
            $colIdArr['contractTimeInterval'] = '';
            $colIdArr['contractResume'] = '';
            $colIdArr['linkmanName'] = '';
            $colIdArr['linkmanTel'] = '';
            $colIdArr['esmManagerName'] = '';

            if(isset($colArr['linkmanName']) || isset($colArr['linkmanTel'])){
                // 处理客户信息
                $rs = $linkmanDao->findAll(array('contractId' => $row['id']), null, 'linkmanName,telephone');
                if (!empty($rs)) {
                    foreach ($rs as $k => $v) {
                        if ($k == 0) {
                            $colIdArr['linkmanName'] = $v['linkmanName'];
                            $colIdArr['linkmanTel'] = $v['telephone'];
                        } else {
                            $colIdArr['linkmanName'] .= PHP_EOL . $v['linkmanName'];
                            $colIdArr['linkmanTel'] .= PHP_EOL . $v['telephone'];
                        }
                    }
                }
            }

            // 服务合同执行的项目经理
//            if ($row['contractType'] == 'HTLX-FWHT') {
            if(isset($colArr['esmManagerName'])){
                $rs = $esmprojectDao->findAll(array('contractId' => $row['id'], 'contractType' => 'GCXMYD-01'),
                    null, 'managerName');
                if (!empty($rs)) {
                    foreach ($rs as $k => $v) {
                        if ($k == 0) {
                            $colIdArr['esmManagerName'] = $v['managerName'];
                        } else {
                            $colIdArr['esmManagerName'] .= ',' . $v['managerName'];
                        }
                    }
                }
//            }

                $colIdArr['esmManagerName'] = ($colIdArr['esmManagerName'] == '')? "-" : $colIdArr['esmManagerName'];

            }

            if (isset($colIdArr['surplusInvoiceMoney']) && !empty($row['surplusInvoiceMoney'])) {
                // 判断关联合同是否存在不开票的开票类型,
                $invoiceCodeArr = explode(",",$row['invoiceCode']);
                $isNoInvoiceCont = '';
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $isNoInvoiceCont = '1';
                    }
                }
                $colIdArr['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
            }

            //开票类型税点
            if(isset($colArr['KPLXSD'])){
                if (isset($invoiceArr) && !empty($row['invoiceCode'])) {
                    $invoiceCodeArr = explode(',', $row['invoiceCode']);
                    $KPLXSD = array();
                    foreach ($invoiceCodeArr as $v) {
                        array_push($KPLXSD, $invoiceArr[$v]);
                    }
                    $colIdArr['KPLXSD'] = implode('&', array_unique($KPLXSD));
                }
            }
            array_push($dataArr, $colIdArr);
        }

        if (isset($_GET['CSV'])) {
            return model_contract_common_contExcelUtil::exportCSV($colArr, $dataArr, '列表信息');
        } else {
            return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr);
        }
    }

    /**
     * 自定义高级搜索
     */
    function c_search()
    {
        $this->assign("gridName", $_GET['gridName']);
        $this->display('search');
    }

    /**
     * 合同管理 - 合同签收-合同信息导出
     */
    function c_singInExportExcel()
    {
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $signStatus = array(
            '0' => '未签收',
            '1' => '已签收',
            '2' => '变更未签收',
        );
        $isAcquiringArr = array(
            '0' => '未收单',
            '1' => '已收单'
        );
        $isTempArr = array(
            '0' => '否',
            '1' => '是'
        );

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $state = $_GET['state'];
        $ExaStatus = $_GET['ExaStatus'];
        if (empty($ExaStatus)) {
            $ExaStatus = "完成";
        }
        $contractType = $_GET['contractType'];
        $beginDate = $_GET['beginDate']; //开始时间
        $endDate = $_GET['endDate']; //截止时间
        $ExaDT = $_GET['ExaDT']; //建立时间
        $areaNameArr = $_GET['areaNameArr']; //归属区域
        $orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //合同编号
        $prinvipalName = $_GET['prinvipalName']; //合同负责人
        $customerName = $_GET['customerName']; //客户名称
        $customerType = $_GET['customerType']; //客户类型
        $orderNatureArr = $_GET['orderNatureArr']; //合同属性
        $DeliveryStatusArr = $_GET['DeliveryStatusArr'];
        $signIn = $_GET['signStatusArr'];
        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        //登录人
        $appId = $_SESSION['USER_ID'];
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        $searchArr['state'] = $state;
        $searchArr['ExaStatus'] = $ExaStatus;
        $searchArr['contractType'] = $contractType;
        $searchArr['beginDate'] = $beginDate; //开始时间
        $searchArr['endDate'] = $endDate; //截止时间
        $searchArr['ExaDT'] = $ExaDT; //建立时间
        $searchArr['areaNameArr'] = $areaNameArr; //归属区域
        $searchArr['contractCode'] = $orderCodeOrTempSearch; //合同编号
        $searchArr['prinvipalName'] = $prinvipalName; //合同负责人
        $searchArr['customerName'] = $customerName; //客户名称
        $searchArr['customerType'] = $customerType; //客户类型
        $searchArr['contractNatureArr'] = $orderNatureArr; //合同属性
        $searchArr['DeliveryStatusArr'] = $DeliveryStatusArr;
        $searchArr['signStatusArr'] = $signIn;
        $searchArr[$searchConditionKey] = $searchConditionVal;
        $searchArr['isTemp'] = '0';
        foreach ($searchArr as $key => $val) {
            if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == "undefined") {
                unset ($searchArr[$key]);
            }
        }
        $this->service->searchArr = $searchArr;
        $this->service->sort = 'c.createTime';
        $this->service->asc = true;
        $rows = $service->listBySqlId('select_gridinfo');

        foreach ($rows as $index => $row) {
            foreach ($row as $key => $val) {
                if ($key == 'state') {
                    $rows[$index][$key] = $stateArr[$val];
                } else if ($key == 'signStatus') {
                    $rows[$index][$key] = $signStatus[$val];
                } else if ($key == 'isAcquiring') {
                    $rows[$index][$key] = $isAcquiringArr[$val];
                } else if ($key == 'isNeedStamp') {
                    $rows[$index][$key] = $isTempArr[$val];
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
        foreach ($dataArr as $key => $val) {
            $dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
            $dataArr[$key]['contractType'] = $this->getDataNameByCode($val['contractType']);
            $dataArr[$key]['signContractType'] = $this->getDataNameByCode($val['signContractType']);
        }
        return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * 合同查看页面
     */
    function c_toViewShipInfoTab()
    {
        //		$this->permCheck (); //安全校验
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('linkId', $_GET['linkId']);
        $this->assign('viewType', (isset($_GET['viewType'])? $_GET['viewType'] : 'original'));
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->assign('contractType', $rows['contractType']);
        $this->display('viewshipinfo-tab');
    }

    /**
     * 合同关闭需要的列表----研发合同列表
     */
    function c_toRdprojectList()
    {
        $this->view("toRdprojectList");
    }

    /**
     * 合同关闭需要的列表----租赁合同列表
     */
    function c_toLeaseList()
    {
        $this->view("toLeaseList");
    }

    /**
     * 合同信息列表 备注
     */
    function c_listRemark()
    {
        $this->assign("contractId", $_GET['id']);
        $this->view("listremark");
    }

    //添加数据
    //    function c_listremarkAdd() {
    //		$contractId = $_POST['contractId'];
    //		$content = $_POST['content'];
    //		$content = util_jsonUtil :: iconvUTF2GB($content);
    //	  $arr = array(
    //         "contractId" => $contractId,
    //         "content" => $content,
    //         "createName" => $_SESSION['USERNAME'],
    //         "createId" => $_SESSION['USER_ID'],
    //         "createTime" => date ( "Y-m-d H:i:s" )
    //	  );
    //		$this->service->listremarkAdd_d($arr);
    //	}
    function c_listremarkAdd()
    {
        $rows = $_POST['objInfo'];
        $rows['createName'] = $_SESSION['USERNAME'];
        $rows['createId'] = $_SESSION['USER_ID'];
        $rows['createTime'] = date("Y-m-d H:i:s");
        $id = $this->service->listremarkAdd_d($rows);
        if ($id) {
            msg('添加成功！');
        }
    }

    //获取数据
    function c_getRemarkInfo()
    {
        $contractId = $_POST['contractId'];
        $info = $this->service->getRemarkInfo_d($contractId);
        //        echo $info;
        echo util_jsonUtil :: iconvGB2UTF($info);

    }

    /**
     * 财务填写相关时间 页面
     */
    function c_financialRelatedDate()
    {
        $this->assign("contractId", $_GET['id']);
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $this->view("financialRelatedDate");
    }

    function c_financialRelatedDateAdd()
    {
        $rows = $_POST['contract'];
        if ($this->service->financialRelatedDateAdd_d($rows)) {
            msg('操作成功！');
        }
    }

    /**
     * 变更合同产品
     */
    function c_changeContractPro()
    {
        $this->view('changeContractPro');
    }

    /**
     *比较借试用转销售物料差异（借试用转销售用）
     */
    function c_compareMaterial($rows)
    {
        $old_rows = $this->service->getContractInfo($rows['id']);
        $hasChange = 0;
        $oldMaterialIds = array();
        // 收集原来的借试用物料ID
        foreach ($old_rows['equ'] as $k => $v) {
            if ($v['isBorrowToorder'] == '1') {
                $oldMaterialIds[] = $v['id'];
            }
        }
        // 对比搜索是否有新添的借试用物料
        foreach ($rows['material'] as $k => $v) {
            if ($v['isBorrowToorder'] == '1' && !in_array($v['id'], $oldMaterialIds)) {
                $hasChange += 1;
            }
        }

        // 如果有新的借试用物料则返回1，否则返回0
        return ($hasChange > 0) ? '1' : '0';
    }

    /**
     *比较产品差异（变更用）
     */
    function c_compareProduct($rows, $isAjax = 'none')
    {
        if ($isAjax == 'ajax') {
            $oldrows = util_jsonUtil :: iconvGB2UTFArr($this->service->getContractInfo($rows['id']));
        } else {
            $oldrows = $this->service->getContractInfo($rows['id']);
        }
        /********产品比较*******************************/
        //处理变更版本产品
        foreach ($rows['product'] as $k => $v) {
            if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //用于加载了临时保存记录后处理
                $temp = $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            } else {
                $temp = $v['id'] . "_" . $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            }
            $rows['product'][$k] = $temp;
        }
        //处理原合同产品
        foreach ($oldrows['product'] as $k => $v) {
            if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //用于加载了临时保存记录后处理
                $temp = $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            } else {
                $temp = $v['id'] . "_" . $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            }
            $oldrows['product'][$k] = $temp;
        }
        if (empty($oldrows['product'])) {
            $oldrows['product'] = array();
        }
        //比较差异
        $result = array_diff($rows['product'], $oldrows['product']);
        /****************************************/
        /********物料*******************************/
        //处理变更版本物料
        foreach ($rows['equ'] as $k => $v) {
            $tempP = $v['id'] . "_" . $v['productName'] . "_" . $v['productId'] . "_" . $v['number'] . "_" . $v['price'] . "_" . $v['money'] . "_" . $v['license'];
            $rows['equ'][$k] = $tempP;
        }
        //处理原合同物料
        foreach ($oldrows['equ'] as $k => $v) {
            $tempPB = $v['id'] . "_" . $v['productName'] . "_" . $v['productId'] . "_" . $v['number'] . "_" . $v['price'] . "_" . $v['money'] . "_" . $v['license'];;
            $oldrows['equ'][$k] = $tempPB;
        }
        if (empty($oldrows['equ'])) {
            $oldrows['equ'] = array();
        }
        //比较差异
        $resultM = array_diff($rows['equ'], $oldrows['equ']);
        /****************************************/

        //处理差异数组key 值
        $result = array_merge($result);
        //转换差异数组value值
        foreach ($result as $k => $v) {
            $val = explode("/", $v);
            $result[$k] = $val;
        }
        $deptIds = $this->service->getDeptIdsByChange($result);
        if ((!empty($result) || !empty($resultM)) && empty($deptIds)) {
            if (empty($result) && !empty($resultM)) {
                if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //用于加载了临时保存记录后处理
                    return $deptIds;
                }
                return "tobo";
            }
            return "noDept";
        }
        return $deptIds;
    }

    /**
     * 比较金额差异（变更用）
     */
    function c_compareMoney($rows, $isAjax = 'none')
    {
        $oldrows = $this->service->getContractInfo($rows['id']);
        //变更版本金额
        $money = $rows['contractMoney'];
        //原版本金额
        $oldmoney = $oldrows['contractMoney'];
        $oldexGross = $oldrows['exgross'];
        //比较差异
        if ($money != $oldmoney) {
            //判断预计毛利率是否提高
            $costEstimates = $oldrows['costEstimates'];
            $exGrossTemp = bcdiv(($money - $costEstimates), $money, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
            if ($exGross > $oldexGross) {
                if($isAjax === 'none'){
                    $sql = "update oa_contract_contract set exgross='" . $exGross . "' where id='" . $rows['id'] . "'";
                    $this->service->query($sql);
                }

                return "1";
            } else {
                return $exGross;
            }
        } else {
            return "none";
        }
    }

    /**
     * 比较纸质合同差异（变更用）
     */
    function c_comparePaperContract($rows)
    {
        $oldrows = $this->service->get_d($rows['id']);
        $paperContractOld = util_jsonUtil::iconvGB2UTF($oldrows['paperContract']);
        if ($rows['paperContract'] == util_jsonUtil::iconvGB2UTF("无") && $paperContractOld == util_jsonUtil::iconvGB2UTF("有")) {
            return "1";
        } else {
            return "none";
        }
    }

    /**
     * 验证合同编号是否重复
     */
    function c_checkCode()
    {
        $code = $_POST['contractCode'];
        $str = $this->service->checkCode_d($code);
        echo $str;
    }

    /**
     * 异常关闭审批 修改审批状态后跳转
     */
    function c_closeAppEdit()
    {
        $id = $_GET['id'];
        $sql = "update oa_contract_contract SET ExaStatus='变更审批中' where id='" . $id . "'";
        $this->service->query($sql);
        echo "<script>this.location='view/reloadParent.php'</script>";
    }

    /**
     * 报销用 - 自动匹配合同功能
     */
    function c_ajaxGetContract()
    {
        //获取数据
        $contractCode = $_POST['contractCode'];
        $contractName = util_jsonUtil :: iconvUTF2GB($_POST['contractName']);
        $ExaStatus = util_jsonUtil :: iconvUTF2GB($_POST['ExaStatus']);

        //如果是编号匹配
        if ($contractCode) {
            $confition = array("contractCode" => $contractCode, 'isTemp' => 0);
        } else {
            $confition = array("contractName" => $contractName, 'isTemp' => 0);
        }
        if ($ExaStatus) { // 合同审批状态过滤
            $confition['ExaStatus'] = $ExaStatus;
        }
        $arr = $this->service->findAll($confition, null, null);
        if ($arr) {
            $rtObj = $arr[0];
            $rtObj['thisLength'] = count($arr);

            echo util_jsonUtil :: encode($rtObj);
        } else {
            return false;
        }
    }

    /** 合同收单*/
    function c_ajaxAcquiring()
    {
        try {
            $result = $this->service->ajaxAcquiring_d($_POST['id']);
            if($result){
                echo 1;
            }else{
                echo 0;
            }
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 列表执行进度页面
     */
    function c_exeStatusView()
    {
        //合同生命周期
        $leftHTML = $this->service->leftCycle_d($_GET['cid']);
        $this->assign("leftHTML", $leftHTML);
        $handleDao = new model_contract_contract_handle();
        $ff = $handleDao->getIsCon($_GET['cid']);
        //兼容 旧视图
        if ($ff == 'null') {
            $this->view("exestatusview");
        } else {
            //动态构建 流程图tab
            $this->assign("tabHtml", $handleDao->getTabHtml($ff, $_GET['cid']));
            $this->view("handleViewTab");
        }
    }

    //流程图 模板页面
    function c_handleView()
    {
        $handleDao = new model_contract_contract_handle();
        //流程图数据替换
        $this->assign("htmlStr", $handleDao->handleHtmStr($_GET['num'], $_GET['cid']));
        $this->view("handleView");
    }

    /**
     * 手工调用接口--更新所有合同数据的 执行状态(只更新所有状态为 2,4的合同)
     */
    function c_handleUpdateConState()
    {
        set_time_limit(0);
        $sql = "select * from oa_contract_contract where state in (2,4) and isTemp = '0' and ExaStatus = '完成'";
        $arr = $this->service->_db->getArray($sql);
        $n = count($arr);
        //    echo "<pre>";
        //    print_R($n);
        //状态数组
        $sataeArr = array(
            "2" => "执行中",
            "4" => "已完成"
        );

        $ptStr = "";
        foreach ($arr as $k => $v) {
            $stateBefore = $v['state'];
            //     	$ff = $this->service->updateContractState($v['id']);
            $stateAfter = $this->findUpdateContractState($v['id']);
            if ($stateBefore != $stateAfter) {
                $pt = "<tr><td>合同ID ：【" . $v['id'] . "】     合同号 ：【" . $v['contractCode'] . "】   更新前状态：【" . $sataeArr[$stateBefore] . "】 " .
                    "更新后状态 【" . $sataeArr[$stateAfter] . "】  ----  <input  id='" . $v['id'] . "' type='button' class='txt_btn_a' value='更新' onclick='updateState(" . $v['id'] . ");' /> </td></tr> ";
            } else {
                $pt = "<tr><td id='" . $v['id'] . "'>合同ID ：【" . $v['id'] . "】     合同号 ：【" . $v['contractCode'] . "】   更新前状态：【" . $sataeArr[$stateBefore] . "】 " .
                    "更新后状态 【" . $sataeArr[$stateAfter] . "】  ----  </td></tr>";
            }

            $ptStr .= $pt;

            //       echo str_pad($pt,4096).'<hr />';
            //        flush();
            //		ob_flush();
            //		sleep(0.1);
        }
        $this->assign("ptStr", $ptStr);
        $this->view("handleUpdateConState");

    }

    function findUpdateContractState($contractid)
    {

        $rows = $this->service->getContractInfo($contractid);

        if ($rows['ExaStatus'] == '完成') {
            $DeliveryStatus = $rows['DeliveryStatus'];
            $contractType = $rows['contractType'];
            $objCode = $rows['objCode'];
            $date = date("Y-m-d");
            //获取管理项目状态
            $projectStateDao = new model_engineering_project_esmproject();
            $projectState = $projectStateDao->checkIsCloseByRobjcode_d($objCode);
            //判断合同是否有发货需求
            if (empty ($rows['equ'])) {
                $shipTips = 0;
            } else {
                $shipTips = 1;
            }
            // if ($contractType == "HTLX-FWHT" && $projectState != 2) {
            if($projectState != 2){
                if ($shipTips == 0) {
                    if ($projectState == 1) {
                        $state = 4;
                    } else {
                        $state = 2;
                    }
                } else {
                    if ($projectState == 1 && ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH")) {
                        $state = 4;
                    } else {
                        $state = 2;
                    }
                }
            } else {
                if ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH") {
                    $state = 4;
                } else {
                    $state = 2;
                }
            }
            return $state;
        }
    }

    function c_getUpdateConState()
    {
        $cid = $_POST['cid'];
        $ff = $this->service->updateContractState($cid);
        if ($ff) {
            echo 1;
        } else {
            echo 2;
        }

    }

    /**
     * 详细物料成本页面
     */
    function c_equCoseView()
    {
        $contractId = $_GET['contractId'];
        $service = $this->service;
        $this->assign("contractId", $contractId);
        if (isset($_GET['istemp'])) {
            $istemp = $_GET['istemp'];
        } else {
            //判断是否为变更合同
            $obj = $service->get_d($contractId);
            $istemp = $obj['isTemp'];
        }
        //详细物料
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulistCost($contractId, $istemp);
        $this->assign('equList', $equlist);
        //产品线明细
        $costDao = new model_contract_contract_cost();
        //如果是变更合同，需要获取原合同成本记录(变更的成本记录会更新到原合同)
//         if($istemp == '1'){
//         	$rs = $service->find(array('id' => $contractId),null,'originalId');
//         	if(!empty($rs)){
//         		$contractId = $rs['originalId'];
//         	}
//         }
        $productLine = $costDao->productlineCost($contractId);
        $this->assign('line', $productLine);

        //售前商机费用
        $chanceCost = $service->getChanceCostByid($_GET['contractId']);
        $this->assign("chanceCost", $chanceCost);
        $this->view("equcostview");
    }

    function c_feeCostView()
    {

        $rows = $this->service->getContractInfo($_GET['id']);
        $arr = $this->service->getContractFeeAll($_GET['id'], 'view');

        $arrHTML = $this->service->handleFeeHTML($arr, $rows);
        $this->assign("feehtml", $arrHTML);
        $this->view("feeCostView");
    }

    /**
     * 财务T日列表
     */
    function c_financialTdayList()
    {
        //        $this->service->searchArr['isDel'] = 0;
        //        $this->service->searchArr['isCom'] = 0;
        //        if (!empty($_REQUEST['searchKey']))
        //            $this->service->searchArr[$_REQUEST['searchKey']] = $_REQUEST['searchVal'];
        //        $isCom = $_REQUEST['isCom'];
        //        if (isset($isCom)) {
        //            if ($isCom == 2) { //如果为查询已删除的
        //                $this->service->searchArr['isDel'] = 1;
        //            } else if ($isCom == 0) {
        //                $this->service->searchArr['isDel'] = 0;
        //                $this->service->searchArr['isCom'] = 0;
        //            } else
        //                $this->service->searchArr['isCom'] = $isCom;
        //        }
        //        if ($_GET['identify'] == 'contractTool') {
        //            $this->service->searchArr['isSellSql'] = "sql: and (r.Tday is null || r.Tday='0000-00-00')";
        //        }
        //        $this->service->pageSize = 10000; //不分页，暂时这样写
        //        $rows = $this->service->pageBySqlId('select_financialTday');
        //        $arrHTML = $this->service->financialTdayHTML($rows);
        //        $this->assign("Thtml", $arrHTML);
        //        $this->assign('searchVal', $_REQUEST['searchVal']);
        //        $this->assign('searchKey', $_REQUEST['searchKey']);
        //        $this->assign('isCom', $_REQUEST['isCom']);

        $this->view("financialTdaylist");
    }

    /**
     * T日确认列表数据
     */
    function c_TdayPageJson()
    {
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        $rows = $this->service->pageBySqlId('select_financialTday');

        foreach ($rows as $k => $v) {
            $id = $v['id'];
            $isConfirm = $v['isConfirm'];
            if (empty($v['Tday'])) {
                if (!empty($v['payDT']) && $v['payDT'] != '0000-00-00') { //如果收款条款存在计划付款日期，则直接显示
                    $Tday = $v['payDT'];
                } else { //否则根据付款条件判断并搜索计算T日
                    $Tday = $service->handlePayDT($v['contractId'], $v['paymenttermId'], $v['dayNum'], $v['schedulePer']);
                }
            } else {
                if ($v['Tday'] != '0000-00-00')
                    $Tday = $v['Tday'];
            }
            //计算项目结束日期
            $rows[$k]['proEndDate'] = $v['completeDate'];

            //标识
            if ($v['Tday'] == '' || $v['Tday'] == '0000-00-00') {
                $isFlag = "-";
            } else {
                $isFlag = '<img src="images/icon/ok3.png">';
            }
            if ($v['TdayPush'] == '0') {
                $htmlButton = ' <input type="button" class="txt_btn_a" value="确认" onclick = "confirmTday(' . $id . ',' . $k . ',' . $isConfirm . ')">';
                $htmlInput = ' <input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '">';
                $htmlChangeTip = '';
            } else {
                $htmlButton = ' <input type="button" class="txt_btn_a" value="变更" onclick = "confirmTday(' . $id . ',' . $k . ',' . $isConfirm . ')">
                <span class="blue" onclick = "changeHistory(' . $id . ',' . $k . ');">
                <img title="查看变更历史" src="images/icon/view.gif"></span>
                <input type="hidden" id="tdayOld' . $k . '" value="' . $Tday . '">';
                $htmlInput = ' <input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '">';
                $htmlChangeTip = '<input type="text" class="txtlong"  id="changeTips' . $k . '" >';
            }
            //add by chenrf
            if ($v['isDel'] == '1') {
                $isCom = "已删除";
                $htmlButton = '<input type="button" class="txt_btn_a" value="确认" disabled="disabled">';
                $htmlInput = " <span style='text-decoration:line-through;color:red;'>{$Tday}</span>";
                $htmlChangeTip = '';
            }
            $rows[$k]['isFlag'] = $isFlag;
            $rows[$k]['Tday'] = $htmlInput;
            $rows[$k]['confirmBtn'] = $htmlButton;
            $rows[$k]['changeTips'] = $htmlChangeTip;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    //update T日
    function c_updateTday()
    {
        $id = $_POST['id'];
        $tday = $_POST['tday'];
        $changeTips = util_jsonUtil :: iconvUTF2GB($_POST['changeTips']);
        $f = $this->service->updateTday_d($id, $tday, $changeTips);
        echo $f;
    }

    //updateT日 - 批量
    function c_updateTdayBatch()
    {
        $checkArr = $_POST['checkArr'];
        $f = $this->service->updateTdayBatch_d($checkArr);
        echo $f;
    }

    function c_showChanceHistory()
    {
        $id = $_GET['id'];
        $info = $this->service->getChanceHistory_d($id);
        $this->assign("info", $info);
        $this->view("showChanceHistory");
    }

    /**
     * 根据合同ID 重新计算预计毛利率，成本概算接口方法
     */
    function c_handleCountCost()
    {
        $cid = $_GET['cid'];
        $rt = $this->service->countCost($cid);
        echo $rt;
    }

    /**
     * 物料确认页面- 动态计算 成本概算和预计毛利率
     */
    function c_getCostByEqu()
    {
        $rst = array(
            'result' => 'ok'
        );
        if ($this->service->this_limit['合同金额'] && $this->service->this_limit['合同金额'] == 1) {
            try {
                $rtArr = $this->service->getCostByEqu_d($_POST['cid'], $_POST['equArr'], ($_POST['isChange'] == 1));
                $rst = array_merge($rst, $rtArr);
            } catch (Exception $e) {
                $rst['result'] = 'error';
                $rst['msg'] = $e->getMessage();
            }
        } else {
            $rst['result'] = 'error';
            $rst['msg'] = '没有相关权限';
        }
        echo util_jsonUtil::encode($rst);
    }

    /**
     * 待录入条款合同列表
     */
    function c_toChecklist()
    {
        $this->view('checklist');
    }

    /**
     * 更新付款条件合同列表
     */
    function c_toUpdatePayList()
    {
        $this->view('updatepaylist');
    }

    /**
     * 发货物料确认页面
     */
    function c_confirmEquView()
    {
        $confirmEqu = $_GET['confirmEqu'];
        $cid = $_GET['contractId'];
        if ($confirmEqu == '2' || $confirmEqu == '3') {
            $cid = $this->service->findChangeId($_GET['contractId']);
            $obj = $this->service->getContractInfoWithTemp($cid, null, 1);
        } else {
            $obj = $this->service->getContractInfo($cid);
        }

        $equDao = new model_contract_contract_equ();
        $products = $equDao->showItemChange($obj['product'], null);
        $this->assign("products", $products);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($confirmEqu == '2') {
            $this->assign('isSubAppChange', '1');
        }
        $this->assign('confirmEqu', $confirmEqu);
        $this->assign('oldId', $_GET['contractId']);

        $this->view("confirmequView");
    }

    //确认发货物料
    function c_confirmEqu()
    {
        set_time_limit(0);
        $act = isset($_GET['act']) ? $_GET['act'] : "app";
        $rows = $_POST[$this->objName];
        $cid = $rows['id'];
        $isSubAppChange = $rows['isSubAppChange'];
        $handleDao = new model_contract_contract_handle();
        if ($rows['confirmEqu'] == '2') {
            // 计算临时记录的毛利率
//            $chkSql = "select productId,number from oa_contract_equ where contractId = '{$cid}' AND isDel = 0;";
//            $equArr = $this->service->_db->getArray($chkSql);
//            $rtArr = $this->service->getCostByEqu_d($cid, $equArr);
            $noAudit = false;
//            if(!empty($rtArr)){
//                // 销售合同产品线是仪器仪表及在线教育的合同毛利率大于70%的做完成本概算后就直接完成审批(物料变更) PMS2373 2017-01-10
//                $infoArr['exGross'] = $rtArr['exgross'];
//                $noAudit = $this->noAuditChk($cid,$infoArr);
//            }

            // 获取变更是否审批标识
            $rs = $this->service->find(array('id' => $rows['oldId']), null, 'changeNoAudit');
            if ($rs['changeNoAudit'] == 1 && $act != 'back') { // 变更无须审批处理
                $this->service->updateById(array('id' => $cid, 'ExaStatus' => '完成')); // 临时记录设置审批通过
                $this->service->confirmChangeNoAudit($cid);

                $handleDao->handleAdd_d(array(
                    "cid" => $rows['oldId'],
                    "stepName" => "免审通过",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
                
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1',
                    'changeNoAudit' => 0
                );
                $this->service->updateById($dateObj);
                msg("此变更无须审批，变更成功！");
            } else if($noAudit) {
                $this->service->updateById(array('id' => $cid, 'ExaStatus' => '完成')); // 临时记录设置审批通过
                $this->service->confirmChangeNoAudit($cid);
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                msg("此变更无须审批，变更成功！");
            }else {
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                if ($act == 'back') {
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "打回物料确认",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    msg("已打回重新确认物料！");
                } else {
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "销售确认物料",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "提交审批",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    $configDeptIds = contractFlowDeptIds; //config内定义的 部门ID
                    $deptIds = "";
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                    $deptIdStrArr = explode(",", $deptIdStr);
                    $deptIdStrArr = array_unique($deptIdStrArr);
                    $deptIdStr = implode(",", $deptIdStrArr);
                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $cid . '&billDept=' . $deptIdStr);
                    //       	   	  msg("确认成功！已提交至产品线成本审核！");
                }
            }
        } else if ($rows['confirmEqu'] == '3') {
            if ($act == 'back') {
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '2'
                );
                $this->service->updateById($dateObj);

                // == 发现合同变更物料确认被打回后,临时合同状态以及之前的概算没处理,导致混合产线的服务概算确认后可以提交审批,以及打回后的物料确认页可以直接点免审确认按钮,暂时处理方法 2017-01-10 huanghaojin == //
                // 更新临时合同的操作状态
                $this->service->updateById(array('id' => $cid, 'dealStatus' => '2'));
                // 删除原来提交的成本概算
                $delSql = "delete from oa_contract_cost where contractId='{$cid}' AND ExaState = 0 AND state = 3;";
                $this->service->query($delSql);

                $handleDao->handleAdd_d(array(
                    "cid" => $rows['oldId'],
                    "stepName" => "打回物料确认",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
                msg("已打回重新确认物料！");
            } else {
                //处理确认信息-并返回预计毛利率
                $exGross = $this->service->handleSubConfirmCoseNew($cid, "3");
                if ($exGross === 'none') {
                    $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange, $rows['oldId']);
                    $this->c_updateSaleCostExaState($cid);
                    msg("确认成功！请等待其他执行区域成本确认！");
                } else {
                    // 销售合同产品线是仪器仪表及在线教育的合同毛利率大于70%的做完成本概算后就直接完成变更(变更合同) PMS2373 2017-01-10
                    $infoArr['exGross'] = $exGross;
//                    $noAudit = $this->noAuditChk($cid,$infoArr);
                    $noAudit = false;
                    if($noAudit){
                        // 销售确认物料
                        $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange, $rows['oldId']);
                        $this->c_updateSaleCostExaState($cid);

                        // 自动通过审批,并更新相关信息
                        $dateObj = array(
                            'id' => $cid,
                            'ExaStatus' => '完成'
                        );
                        $this->service->updateById($dateObj);
                        $this->service->confirmChangeNoAudit($cid,2);
                        msg("此合同无须审批，变更成功！");
                    }else{
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['oldId'],
                            "stepName" => "销售确认物料",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                        $this->c_subConfirmCostAppNew($cid, $exGross);
                    }
                }
//                 msg("确认成功！已提交至执行部门成本审核！");
            }

        } else {
            if ($act == 'back') {
                $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                msg("已打回重新确认物料！");
            } else {
                //处理确认信息-并返回预计毛利率
                $exGross = $this->service->handleSubConfirmCoseNew($cid, "3");
                if ($exGross === 'none') {
                    $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                    $this->c_updateSaleCostExaState($cid);
                    msg("确认成功！请等待其他执行区域成本确认！");
                } else {
                    // 销售合同产品线是仪器仪表及在线教育的合同毛利率大于70%的做完成本概算后就直接完成审批(录入合同) PMS2373 2017-01-09
                    $infoArr['exGross'] = $exGross;
//                    $noAudit = $this->noAuditChk($cid,$infoArr);
                    $noAudit = false;
                    if($noAudit){
                        $contractObj = $this->service->getContractInfo($cid);
                        // 销售确认物料
                        $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                        $this->c_updateSaleCostExaState($cid);//更新概算审核状态

                        // 自动通过审批,并补充相关信息
                        $dateObj = array(
                            'id' => $cid,
                            'standardDate' => $contractObj['standardDate'],
                            'dealStatus' => '1',
                            'ExaStatus' => '完成',
                            'ExaDTOne' => date("Y-m-d"),
                            'state' => '2',
                            'isSubAppChange' => 0

                        );
                        $this->service->updateById($dateObj);
                        $this->service->dealAfterAudit_d($cid);// 在盖章列表添加信息

                        $this->service->confirmContractWithoutAudit_d($cid);// 确认合同
                        msg("此合同无须审批，提交成功！");
                    }else{
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['oldId'],
                            "stepName" => "销售确认物料",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                        $this->c_subConfirmCostAppNew($cid, $exGross);
                    }
                }
//                 msg("确认成功！已提交至执行部门成本审核！");
            }
        }
    }

    /**
     * 检查合同是否需要审批,返回true或false
     * @param $contractId
     * @param array $infoArr
     * @return bool
     */
    function noAuditChk($contractId,$infoArr = array()){
        $exGross = isset($infoArr['exGross'])? $infoArr['exGross'] : 0;
        $contract = $this->service->find(array('id' => $contractId), null, 'newProLineStr');
        $arr = explode(",",$contract['newProLineStr']);
        $arr = array_unique($arr);// 产品线去重
        // 如果概算毛利率 > 70,且为仪器仪表或在线教育的单一产线的不需审批
        if($exGross > 70 && count($arr) == 1){
            if($arr[0] == 'HTCPX-YQYB' || $arr[0] == 'HTCPX-ZXJY'){// 产线为仪器仪表或在线教育
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 跳转到合同交付页面
     */
    function c_toDelivery()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $productIds = ''; //已经有任务的物料id

        $basicDao = new model_purchase_plan_basic(); //采购任务
        $basicObj = $basicDao->findAll(array('sourceID' => $_GET['id']));
        $basicIds = '';
        if (is_array($basicObj)) {
            $equipmentDao = new model_purchase_plan_equipment(); //采购任务清单
            foreach ($basicObj as $key => $val) {
                $basicIds .= $val['id'] . ',';

                $equipmentObj = $equipmentDao->findAll(array('basicId' => $val['id']));
                if (is_array($equipmentObj)) {
                    foreach ($equipmentObj as $k => $v) {
                        $productIds .= $v['productId'] . ',';
                    }
                }
            }
            $basicIds = substr($basicIds, 0, -1);
        }
        $this->assign('basicIds', $basicIds);

        $produceapplyDao = new model_produce_apply_produceapply(); //生产任务
        $produceapplyObj = $produceapplyDao->findAll(array('relDocId' => $_GET['id']));
        $produceapplyIds = '';
        if (is_array($produceapplyObj)) {
            $itemDao = new model_produce_apply_produceapplyitem(); //生产任务清单
            foreach ($produceapplyObj as $key => $val) {
                $produceapplyIds .= $val['id'] . ',';

                $itemObj = $itemDao->findAll(array('mainId' => $val['id']));
                if (is_array($itemObj)) {
                    foreach ($itemObj as $k => $v) {
                        $productIds .= $v['productId'] . ',';
                    }
                }
            }
            $produceapplyIds = substr($produceapplyIds, 0, -1);
        }
        $this->assign('produceapplyIds', $produceapplyIds);

        $encryptionDao = new model_stock_delivery_encryptionequ(); //加密锁任务
        $encryptionObj = $encryptionDao->findAll(array('sourceDocId' => $_GET['id']));
        if (is_array($encryptionObj)) {
            foreach ($encryptionObj as $key => $val) {
                $productIds .= $val['productId'] . ',';
            }
        }

        $productIds = substr($productIds, 0, -1);
        $this->assign('productIds', $productIds);

        //获取发货计划最大计划发货时间
        $sql = "select max(shipPlanDate) as maxDate,docId from oa_stock_outplan where docType='oa_contract_contract'
          and docId = '" . $_GET['id'] . "'
          GROUP BY docId";
        $maxShipArr = $this->service->_db->getArray($sql);
        $this->assign("maxShipDate", $maxShipArr[0]['maxDate']);

        $this->view("delivery");
    }

    /**
     * 重写获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;

        $handleType = isset($_REQUEST['handleType'])? $_REQUEST['handleType'] : '';
        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;

        if($handleType == 'YSWJ'){// 如果是验收文件的列表获取的数据,则添加客户借试用的数据
            $rows = $service->pageBySqlId('select_gridinfoForYswj');
        }else{
            $rows = $service->page_d ();
        }

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

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsons()
    {
        $service = $this->service;
        $incomeSql = " ";
        if(isset($_REQUEST['isIncome'])){
            switch($_REQUEST['isIncome']){
                case "0" :
                    $incomeSql = " and (c.contractMoney-c.incomeMoney != 0)";
                    break;
                case "1" :
                    $incomeSql = " and (c.contractMoney-c.incomeMoney = 0)";
                    break;
            }
            unset($_REQUEST['isIncome']);
        }

        $service->getParam($_REQUEST);

        // 自定义脚本拼接
        $conditionSql = CONTOOLIDS_C;
//        $conditionSql = "sql: " . CONTOOLIDS_C;
        $conditionSql .= $incomeSql;

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'areaNameNotIn' => $odDao->getConfig("contractCheck_filter_areaName"), // 区域过滤
            'prinvipalIdNotIn' => $odDao->getConfig("contractCheck_filter_prinvipalId"), //负责人过滤
            'customerNameNotIn' => $odDao->getConfig("contractCheck_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        // 客户类型过滤
        $customerTypeNotLike = $odDao->getConfig("contractCheck_filter_customerTypeName");
        if ($customerTypeNotLike) {
            $conditionSql .= $customerTypeNotLike;
        }

        $limit = $this->service->initLimit_treport($conditionSql,"t");
//        $service->searchArr['mySearchCondition'] = $conditionSql;

        //$service->asc = false;
        if($limit){
            $rows = $service->page_d();

            foreach ($rows as $key => $val){
                // 判断关联合同是否存在不开票的开票类型,
                $invoiceCodeArr = explode(",",$val['invoiceCode']);
                $isNoInvoiceCont = '';
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $isNoInvoiceCont = '1';
                    }
                }
                $rows[$key]['isNoInvoiceCont'] = $isNoInvoiceCont;
                $rows[$key]['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
            }
        }

        //数据加入安全码
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
     * 保存待录入收款条款备注信息
     */
    function c_saveCheckRemarks(){
        $updateArr['id'] = isset($_POST['id'])? $_POST['id'] : '';
        $remarks = isset($_POST['remarks'])? $_POST['remarks'] : '';
        $type = isset($_POST['type'])? $_POST['type'] : '';

        switch ($type){
            case 'fcheck':
                $updateArr['fcheckRemarks'] = util_jsonUtil::iconvUTF2GB($remarks);
                break;
            case 'check':
                $updateArr['checkRemarks'] = util_jsonUtil::iconvUTF2GB($remarks);
                break;
        }

        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * 续签父合同相关信息
     */
    function c_parentView()
    {
        //合同状态数组
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];
        //财务金额字段权限
        $financeLimit = isset ($this->service->this_limit['财务金额']) ? $this->service->this_limit['财务金额'] : null;
        if ($financeLimit == '1') {
            $this->assign("FinanceCon", "1");
        } else {
            $this->assign("FinanceCon", "0");
        }
        //		echo "<pre>";
        //		print_R($obj);
        //数据渲染
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('surOrderMoney', $obj['contractMoney'] - $obj['incomeMoney']); //合同应收款
        $this->assign('surincomeMoney', $obj['invoiceMoney'] - $obj['incomeMoney']); //财务应收款
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //财务确认进度
        } else {
            $this->assign('financeconfirmPlan', ""); //财务确认进度
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //毛利
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }
        //开票，到款进度
        $this->assign('invoicePortion', $this->getProportion($obj['invoiceMoney'], $obj['contractMoney']));
        $this->assign('incomePortion', $this->getProportion($obj['incomeMoney'], $obj['contractMoney']));

        //发货情况
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulist($obj['id']);
        $this->assign('equlist', $equlist);
        //项目
        $proDao = new model_contract_conproject_conproject();
        $this->assign('proList', $proDao->prolist($obj['id']));
        //合同执行进度
        $this->assign('exePortion', $proDao->getConduleBycid($obj['id']));
        $this->view("parentView");
    }

    /**
     * 从报表跳转到查看合同的列表
     */
    function c_toViewByReport()
    {
        $this->assign('ids', $_GET['ids'] ? $_GET['ids'] : 0);
        $this->view('view-report');
    }

    /**
     * 合同基础数据列表
     */
    function c_basicList()
    {
        $this->view('basiclist');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonBasic()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);

        $rows = $service->page_d("select_basicList");
        $rows = $service->basicDataProcess_d($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 合同基础数据导出
     */
    function c_basicExportExcel()
    {
        set_time_limit(0); //执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        ini_set('memory_limit', '1024M'); //设置内存

        $service = $this->service;
        $rows = $service->list_d("select_basicList");
        $dataArr = $service->basicDataProcess_d($rows);
        //表头数组
        $thArr = array('ExaDTOne' => '合同建立时间', 'contractCode' => '合同编号', 'contractName' => '合同名称', 'contractMoney' => '合同金额', 'projectCode' => '项目编号',
            'projectName' => '项目名称', 'proMoney' => '项目金额', 'projectType' => '项目类型', 'createTime' => '合同提交日期', 'costAppDate' => '成本审核日期',
            'shipTimes' => '单个合同发货次数', 'standardDate' => '标准交期', 'shipPlanDate' => '预计发货日期', 'shipDate' => '实际发货日期', 'estimates' => '项目概算',
            'saleCost' => '实际发货成本', 'cost' => '项目决算', 'earnings' => '项目收入', 'exgross' => '预计毛利率', 'rateOfGross' => '毛利率', 'schedule' => '项目进度',
            'isAcquiringDate' => '合同收单日期', 'signinDate' => '合同签收日期'
        );

        return model_contract_common_contractExcelUtil :: exportBasicExcelUtil($thArr, $dataArr, '合同基础数据');
    }

    /**
     * 更新服务类确认状态及成本信息
     */
    function c_updateEngConfirm($contractId)
    {
        $service = $this->service;
        // 获取所有非销售类产品数量
        $sql = "SELECT COUNT(DISTINCT(newProLineCode)) AS num FROM oa_contract_product WHERE contractId = " . $contractId . " AND proTypeId <> 11 AND isDel = 0";
        $rs = $service->_db->getArray($sql);
        $pNum = 0;
        if (!empty($rs)) {
            $pNum = $rs[0]['num'];
        }
        // 获取所有非销售类确认成本
        $costDao = new model_contract_contract_cost();
        $cNum = $costDao->findCount(array('contractId' => $contractId, 'issale' => 0));
        if ($pNum == $cNum) { // 所有非销售类产品都已确认成本时执行
            // 获取合同信息
            $rows = $service->getContractInfo($contractId);
            $handleDao = new model_contract_contract_handle();
            if ($rows['isSubAppChange'] == '1') {
                //更新服务类成本确认标志位 完成
                $service->endTheEngTig($contractId);
                $service->endTheEngTig($rows['originalId']);
                //变更操作记录
                $handleDao->handleAdd_d(array(
                    "cid" => $rows['originalId'],
                    "stepName" => "提交审批",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
            } else {
                $service->endTheEngTig($contractId);
                $handleDao->handleAdd_d(array(
                    "cid" => $contractId,
                    "stepName" => "提交审批",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
            }
        }
    }

    /**
     * 提交审批后处理
     */
    function c_dealAfterSubAudit()
    {
        $service = $this->service;
        // 获取合同信息
        $contractId = $_REQUEST['id'];
        $rows = $service->getContractInfo($contractId);
        // 销售类处理
        if ($rows['isSubAppChange'] == '1') {
            $service->confirmEqu_d($contractId, "app", $rows['isSubAppChange'], $rows['originalId'],false);
        } else {
            $service->confirmEqu_d($contractId, "app", $rows['isSubAppChange'],'',false);
        }
        $this->c_updateSaleCostExaState($contractId);
        // 服务类处理
        $this->c_updateEngCostExaState($contractId);
        $this->c_updateEngConfirm($contractId);
        // 更新变更记录审批状态为审批中
        $sql = "update oa_contract_changlog set ExaStatus = '审批中' where objType = 'contract' and tempId = " . $contractId;
        $this->service->_db->query($sql);
        echo "<script>location.href='view/reloadParent.php';</script>";
    }

    /**
     * 判断合同创建人是否为海外部门
     * 海外部门走不同的审批流
     */

    function c_isOverseasDept()
    {
        $userDao = new model_deptuser_user_user(); //人员信息
        $rs = $userDao->find(array('USER_ID' => $_POST['userId']), null, 'DEPT_ID');
        if ($rs['DEPT_ID'] == hwDeptId) {
            echo hwDeptId;
        }
    }

    /**
     * 合同明细-用于费用报销审批页面的费用统计
     */
    function c_statistictList()
    {
//    	$this->assign('userId',$_GET['userId']);
//    	$this->assign('areaId',$_GET['areaId']);
//    	$this->assign('year',date('Y'));
//
//    	$this->view('liststatistict');
        $this->assign('userId', (isset($_GET['userId']) ? $_GET['userId'] : ''));
        $this->assign('areaId', (isset($_GET['areaId']) ? $_GET['areaId'] : ''));
        $this->assign('year', (isset($_GET['year']) ? $_GET['year'] : date('Y')));


        if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId'])) && (isset($_GET['view_type']) && !empty($_GET['view_type']))) {
            $this->assign('view_type', (isset($_GET['view_type']) ? $_GET['view_type'] : ''));
            if ($_GET['view_type'] == 'view_all') {
                $this->view('listallstatistict');
            }
        } else if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId']))) {
            $this->view('liststatistict');
        } else {
            $this->view('listallstatistict');
        }
    }

    /**
     * 获取分页数据转成Json - 费用统计
     */
    function c_statistictPageJson()
    {
        $service = $this->service;

        if(isset($_REQUEST['isForExsummary']) && $_REQUEST['isForExsummary'] == 1){
            $sql = "select contractId from oa_bi_conproduct_month where 1=1 ";
            $sql .= (isset($_REQUEST['prinvipalId']) && !empty($_REQUEST['prinvipalId']))? " and prinvipalId = '{$_REQUEST['prinvipalId']}'" : '';
            $sql .= (isset($_REQUEST['areaCode']) && !empty($_REQUEST['areaCode']))?  " and areaCode = '{$_REQUEST['areaCode']}'" : '';
            $sql .= (isset($_REQUEST['ExaYear']) && !empty($_REQUEST['ExaYear']))?  " and storeYear = '{$_REQUEST['ExaYear']}'" : '';
            $sql .= " GROUP BY contractId";
            $sql = "select group_concat(t.contractId) as ids from ({$sql})t;";
            $contractIds = $this->service->_db->get_one($sql);
            if($contractIds){
                $_REQUEST['ids'] = $contractIds['ids'];
            }
        }

        $service->getParam($_REQUEST);

        $rows = $service->pageBySqlId('select_gridinfo');

        if (!empty ($rows)) {
            //获取有备注信息的合同id
            $remarkIsArr = $this->service->getRemarkIs();
            $regionDao = new model_system_region_region();
            $cidStr = '';
            foreach ($rows as $key => $val) {
                if (in_array($val['id'], $remarkIsArr)) {
                    $rows[$key]['conflag'] = "1";
                }
                //判断并返回合同当前进度状态
                $exeStautsView = $this->service->exeStatusView_d($val);
                $rows[$key]['exeStatus'] = $exeStautsView[0];
                $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                //查找扩展值
                //获取区域扩展字段值
                $expand = $regionDao->getExpandbyId($val['areaCode']);
                $rows[$key]['expand'] = $expand;
                //合同费用
                //					$fee = $this->service->getContractFeeAll($val['id']);
                //					$rows[$key]['contractFee'] = $fee;
                //处理当前列表合同id字符串
                $cidStr .= $val['id'] . ",";

            }
            //获取项目决算费用信息
            $cidStr = rtrim($cidStr, ",");
            $proBudgetArr = $service->getproBudgetByids($cidStr);
            //处理项目费用
            foreach ($rows as $k => $v) {
                foreach ($proBudgetArr as $va) {
                    if ($v['id'] == $va['contractId']) {
                        $rows[$k]['budgetAll'] = $va['budgetAll'];
                        $rows[$k]['feeOther'] = $va['feeOther'];
                        $rows[$k]['budgetOutsourcing'] = $va['budgetOutsourcing'];
                        $rows[$k]['feeFieldCount'] = $va['feeFieldCount'];
                        $rows[$k]['feeOutsourcing'] = $va['feeOutsourcing'];
                        $rows[$k]['feeAll'] = $va['feeAll'];
                        $rows[$k]['projectProcess'] = $va['projectProcess'];
                    }
                }
            }
            //安全码
            $rows = $this->sconfig->md5Rows($rows);
            //                //工作量进度
            //                $rows = $this->service->projectProcess_d($rows);

            //统计金额
            $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
            //这里忘记为什么要unset掉了先放下面
            unset($service->searchArr['advSql']);
            //敏感字段过滤
            $rows = $this->fieldFilter($rows);
            //开票类型税点
            $datadictDao = new model_system_datadict_datadict(); //获取数据字典信息
            $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
            if (!empty($rs)) {
                $invoiceArr = array();
                foreach ($rs as $v) {
                    $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
                }
                foreach ($rows as $key => $val) {
                    if (!empty($val['invoiceCode'])) {
                        $invoiceCodeArr = explode(',', $val['invoiceCode']);
                        $KPLXSD = array();
                        foreach ($invoiceCodeArr as $v) {
                            array_push($KPLXSD, $invoiceArr[$v]);
                        }
                        $rows[$key]['KPLXSD'] = implode('&', array_unique($KPLXSD));
                    }
                }
            }
        }
        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 根据借试用id获取关联商机数据 - 用于借试用转销售关闭关联商机
     */
    function c_getChanceByBorrowIds()
    {
        echo util_jsonUtil::iconvGB2UTF($this->service->getChanceByBorrowIds_d($_POST['borrowIds']));
    }

    /**
     * 更新合同状态
     */
    function c_updateConState(){
    	$this->service->updateConState_d();
    }

    /*************************T 日  报表********************************************************************/
    //T 日 原始数据
    function c_TdayDataList(){
        $this->view('TdayDataList');
    }
    function c_tdatDataJson(){
        $service = $this->service;
        $service->getParam($_REQUEST);
        $conditionSql = "  and 1=1 ";
        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        if(isset($_REQUEST['isReplan'])){
            if($_REQUEST['isReplan'] == '0'){
                $conditionSql .= " and r.payInfo is not null";
            }elseif($_REQUEST['isReplan'] == '1'){
                $conditionSql .= " and (r.tday != ' ' and r.tday != '0000-00-00' AND r.tday != ',' AND r.tday != ',,' AND r.tday != ',,,' AND r.tday != ',,,,' AND r.tday != ',,,,,' AND r.tday != ',,,,,,' AND r.tday != ',,,,,,,' AND r.tday != ',,,,,,,,' AND r.tday != ',,,,,,,,,' AND r.tday != ',,,,,,,,,,' AND r.tday != ',,,,,,,,,,,')";
            }
            unset ($_REQUEST['isReplan']);
        }

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //过滤型权限设置
        $limit = $this->service->initLimit_treport($conditionSql,"t");

        //$service->asc = false;
        $reDao = new model_contract_contract_receiptplan();
        $esmDao = new model_engineering_project_esmproject();
        if($limit){
            $rows = $service->pageBySqlId("select_tdayDataList");
        }

        //插入 回款条款数据
        foreach($rows as $k => $v){
           $reArr = $reDao->getDetail_d($v['id']);
           $esmState = $esmDao->getStatusNameByContractId_d($v['id']);
           if(empty($esmState)){
           	   switch($v['DeliveryStatus']) {
           	   	 case "WFH" : $esmState = "未发货";
           	       break;
           	     case "BFFH" : $esmState = "部分发货";
           	       break;
           	     case "YFH" : $esmState = "已发货";
                   break;
                 case "TZFH" : $esmState = "停止发货";
                   break;
           	   }
           }
           $incocmeAll = 0;
           $endDate = "";
           $reMoney = 0;
           for($i=0;$i<15;$i++){
           	   $tt = "";
           	   $j = $i + 1;
               $rows[$k]["incomeDate_".$j] = $reArr[$i]["Tday"];
               $rows[$k]["incomePtn_".$j] = $reArr[$i]["paymentPer"];
               $rows[$k]["incomeMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               $rows[$k]["invoiceMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["invoiceMoney"]-$reArr[$i]["deductMoney"];
               if(!empty($reArr[$i]["Tday"])){
                   $incocmeAll +=  $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               }
               if(!empty($reArr[$i]['id'])){
               	  $tt = $this->service->getTdayListEndDate($v['id'], $reArr[$i]['paymenttermId']);
                  $tState = $this->service->getTdayListEndDate($v['id'], $reArr[$i]['paymenttermId']);
               }else{
               	  $tt = "-";
               }
               if(!empty($reArr[$i]['id'])){
               	 $endDate .= $tt." ; ";
               }
               if(!empty($reArr[$i]["Tday"])){
               	 $reMoney += $reArr[$i]['money'];
               }
           }
            //转义科学计数
            $incocmeAll = number_format($incocmeAll,2,'.','');
           $rows[$k]["Tmoney"] = $incocmeAll;
           $rows[$k]["projectEndDate"] = $endDate;
           $rows[$k]["projectState"] = $esmState;
           $rows[$k]["unTdayMoney"] = bcsub($v["unIncomeMoney"],$incocmeAll,2);


        }
        //数据加入安全码
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

     //T 日 整理表
    function c_TdayInitList(){
    	$this->view('TdayInitList');
    }
    function c_tInitJson(){
        $service = $this->service;
        $service->getParam($_REQUEST);
        $conditionSql = " and 1=1 ";
        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        $t_date = date("Y-m-1");//统计时间
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //过滤型权限设置
        $limit = $this->service->initLimit_treport($conditionSql,"t");
        $reDao = new model_contract_checkaccept_checkaccept();
        if($limit){
            $rows = $service->pageBySqlId("select_tdayInitList");
        }
        // 验收条款处理
        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        foreach($rows as $k => $v){
           if($tempCid != $v['contractId']){//判断不同则获取 验收信息
              $tempCid = $v['contractId'];
              $tempKey = 0;
			  $checkArr = $reDao->getDetail_d($v['contractId']);
			  $rows[$k]["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			  unset($checkArr[$tempKey]);
              $tempKey += 1;
           }else{//否则，赋值并去除已赋值 val
               $rows[$k]["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			   unset($checkArr[$tempKey]);
			   $tempKey += 1;
           }

           //逾期应收款
           if(strtotime(date("Y-m-1")) > strtotime($v['Tday']) && $v['unIncomeMoney'] > 0){
           	   $rows[$k]["unCmoney"] = $v['unIncomeMoney'];
           }
           //应收款判断
           if(!empty($v['Tday'])){
	         $Q = ceil(date("m")/3);//当前季度
	         $Y = date("Y");
	         if($Q+1>4){//下季度
	         	$next_Q = 1;
	         }else{
	         	$next_Q = $Q+1;
	         }
	         $t_Q = ceil(substr($v['Tday'],5,-3)/3);
	         $t_Y = substr($v['Tday'],0,-6);
             $t_3 =  date("Y-m-d", strtotime("-3 months", strtotime($t_date)));
             $t_6 =  date("Y-m-d", strtotime("-6 months", strtotime($t_date)));
             $t_12 =  date("Y-m-d", strtotime("-12 months", strtotime($t_date)));

               $Qarr = array(1 => array('01','03'),2 => array('04','06'),3 => array('07','09'),4 => array('10','12'));// 季度月份对应数组
               $nextQ_Y = ($next_Q < $Q)? ($Y + 1) : $Y;// 下季度所属年份
               $QStart = $Y."-".$Qarr[$Q][0]."-01";// 本季度开始日期
               $QEnd = ($Qarr[$Q][1]+1 <= 12)? $Y."-".($Qarr[$Q][1]+1)."-01" : ($Y+1)."-01-01";// 本季度结束日期加一天（即下季度的开始日期））
               $nextQStart = $nextQ_Y."-".$Qarr[$next_Q][0]."-01";// 下季度的开始日期
               $nextQEnd = ($Qarr[$next_Q][1]+1 <= 12)? $nextQ_Y."-".($Qarr[$next_Q][1]+1)."-01" : ($nextQ_Y+1)."-01-01";// 下季度结束日期加一天（即下季度以后的开始日期））

             if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
                 $rows[$k]["income_a"] = $v['unIncomeMoney'];
             //}else if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
             }else if(strtotime($v['Tday']) >= strtotime($t_date) && (strtotime($v['Tday']) >= strtotime($nextQStart) && strtotime($v['Tday']) < strtotime($nextQEnd))){
                 $rows[$k]["income_b"] = $v['unIncomeMoney'];
             //}else if(strtotime($v['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
             }else if(strtotime($v['Tday']) >= strtotime($t_date) && (strtotime($v['Tday']) >= strtotime($nextQEnd))){
             	 $rows[$k]["income_c"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) >= strtotime($t_3) ){
                 $rows[$k]["income_d"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_3) && strtotime($v['Tday']) >= strtotime($t_6)){
                 $rows[$k]["income_e"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_6) && strtotime($v['Tday']) >= strtotime($t_12)){
                 $rows[$k]["income_f"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) <  strtotime($t_12)){
                 $rows[$k]["income_g"] = $v['unIncomeMoney'];
             }

//               if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
//                   $rows[$k]["income_a"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
//                   $rows[$k]["income_b"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
//                   $rows[$k]["income_c"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=90 ){
//                   $rows[$k]["income_d"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>90 && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=180){
//                   $rows[$k]["income_e"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>180 && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=365){
//                   $rows[$k]["income_f"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>365){
//                   $rows[$k]["income_g"] = $v['unIncomeMoney'];
//               }
           }
        }
        //数据加入安全码
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

    //导出
    function c_TinitExportExcel()
    {
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $signStatus = array(
            '0' => '未签收',
            '1' => '已签收',
            '2' => '变更未签收',
        );
        $rows = array();

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        $service->getParam($_REQUEST);

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            $conditionSql = str_replace("sql:"," ",$conditionSql);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = " and 1=1 ";
        }


        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        $t_date = date("Y-m-1");//统计时间
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }
//        $service->searchArr['mySearchCondition'] = $conditionSql;

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //登录人
//         $appId = $_SESSION['USER_ID'];
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        $limit = $this->service->initLimit_treport($conditionSql,"t");
        if($limit){
            $rows = $service->listBySqlId('select_tdayInitList');
        }

        if (!empty ($rows)) {
            if (isset($service->searchArr['advSql'])) {
                unset($service->searchArr['advSql']);
            }
        }

        $arr = array();
        $arr['collection'] = $rows;

        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        $reDao = new model_contract_checkaccept_checkaccept();
        $esmDao = new model_engineering_project_esmproject();

        foreach ($rows as $key => $row) {
        	if($tempCid != $row['contractId']){//判断不同则获取 验收信息
              $tempCid = $row['contractId'];
              $tempKey = 0;
			  $checkArr = $reDao->getDetail_d($row['contractId']);
			  $row["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			  unset($checkArr[$tempKey]);
              $tempKey += 1;
           }else{//否则，赋值并去除已赋值 val
               $row["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			   unset($checkArr[$tempKey]);
			   $tempKey += 1;
           }

           //逾期应收款
           if(strtotime(date("Y-m-1")) > strtotime($row['Tday']) && $row['unIncomeMoney'] > 0){
           	   $row["unCmoney"] = $row['unIncomeMoney'];
           }
           //应收款判断
           if(!empty($row['Tday'])){
	         $Q = ceil(date("m")/3);//当前季度
	         if($Q+1>4){//下季度
	         	$next_Q = 1;
	         }else{
	         	$next_Q = $Q+1;
	         }
	         $Y = date("Y");
	         $t_Y = substr($row['Tday'],0,-6);
	         $t_Q = ceil(substr($row['Tday'],5,-3)/3);

               $t_3 =  date("Y-m-d", strtotime("-3 months", strtotime($t_date)));
               $t_6 =  date("Y-m-d", strtotime("-6 months", strtotime($t_date)));
               $t_12 =  date("Y-m-d", strtotime("-12 months", strtotime($t_date)));

               $Qarr = array(1 => array('01','03'),2 => array('04','06'),3 => array('07','09'),4 => array('10','12'));// 季度月份对应数组
               $nextQ_Y = ($next_Q < $Q)? ($Y + 1) : $Y;// 下季度所属年份
               $QStart = $Y."-".$Qarr[$Q][0]."-01";// 本季度开始日期
               $QEnd = ($Qarr[$Q][1]+1 <= 12)? $Y."-".($Qarr[$Q][1]+1)."-01" : ($Y+1)."-01-01";// 本季度结束日期加一天（即下季度的开始日期））
               $nextQStart = $nextQ_Y."-".$Qarr[$next_Q][0]."-01";// 下季度的开始日期
               $nextQEnd = ($Qarr[$next_Q][1]+1 <= 12)? $nextQ_Y."-".($Qarr[$next_Q][1]+1)."-01" : ($nextQ_Y+1)."-01-01";// 下季度结束日期加一天（即下季度以后的开始日期））


               if(strtotime($row['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
                   $row["income_a"] = $row['unIncomeMoney'];
                   //}else if(strtotime($row['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
               }else if(strtotime($row['Tday']) >= strtotime($t_date) && (strtotime($row['Tday']) >= strtotime($nextQStart) && strtotime($row['Tday']) < strtotime($nextQEnd))){// 下季度判断逻辑
                   $row["income_b"] = $row['unIncomeMoney'];
                   //}else if(strtotime($row['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
               }else if(strtotime($row['Tday']) >= strtotime($t_date) && (strtotime($row['Tday']) >= strtotime($nextQEnd))){// 下季度以后判断逻辑
                   $row["income_c"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) &&  strtotime($row['Tday']) >= strtotime($t_3)){
                   $row["income_d"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) < strtotime($t_3) && strtotime($row['Tday']) >= strtotime($t_6)){
                   $row["income_e"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) < strtotime($t_6) && strtotime($row['Tday']) >= strtotime($t_12)){
                   $row["income_f"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) <  strtotime($t_12)){
                   $row["income_g"] = $row['unIncomeMoney'];
               }

           }

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        $tt = date("Y-m-d");
        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"合同收款条款T日信息表".$tt);
    }

    function c_TdataExportExcel()
    {
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//设置内存
        $stateArr = array(
            "0" => "未提交",
            "1" => "审批中",
            "2" => "执行中",
            "3" => "已关闭",
            "4" => "已完成",
            "5" => "已合并",
            "6" => "已拆分",
            "7" => "异常关闭",
        );
        $signStatus = array(
            '0' => '未签收',
            '1' => '已签收',
            '2' => '变更未签收',
        );
        $rows = array();

        if(!isset($_GET['colId']) && !isset($_GET['colName'])){// 如果前端没传入对应的列ID以及列名,从SESSION中获取
            $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContractTdate';");
            if($records){
                foreach ($records as $record){
                    if(isset($record['skey']) && $record['skey'] == 'ColId'){
                        $colIdStr = $record['svalue'];
                    }else if(isset($record['skey']) && $record['skey'] == 'ColName'){
                        $colNameStr = $record['svalue'];
                    }
                }
                $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContractTdate';");
            }else{
                $colIdStr = '';
                $colNameStr = '';
            }
        }else{
            $colIdStr = $_GET['colId'];
            $colNameStr = $_GET['colName'];
        }

        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            $conditionSql = str_replace("sql:"," ",$conditionSql);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = " and 1=1 ";
        }
        $service->getParam($_REQUEST);

        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        if(isset($_REQUEST['isReplan'])){
            if($_REQUEST['isReplan'] == '0'){
                $conditionSql .= " and r.payInfo is not null";
            }elseif($_REQUEST['isReplan'] == '1'){
                $conditionSql .= " and (r.tday != ' ' and r.tday != '0000-00-00' AND r.tday != ',' AND r.tday != ',,' AND r.tday != ',,,' AND r.tday != ',,,,' AND r.tday != ',,,,,' AND r.tday != ',,,,,,' AND r.tday != ',,,,,,,' AND r.tday != ',,,,,,,,' AND r.tday != ',,,,,,,,,' AND r.tday != ',,,,,,,,,,' AND r.tday != ',,,,,,,,,,,')";
            }
            unset ($_REQUEST['isReplan']);
        }
        $t_date = date("Y-m-d");//统计时间
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }
        $service->searchArr['mySearchCondition'] = $conditionSql;
        //登录人
//         $appId = $_SESSION['USER_ID'];
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //过滤型权限设置
        $limit = $this->service->initLimit_treport($conditionSql,"t");

        //$service->asc = false;
        if($limit){
            $rows = $service->listBySqlId("select_tdayDataList");
        }else{
            $rows = array();
        }

        $arr = array();
        $arr['collection'] = $rows;

        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        $reDao = new model_contract_contract_receiptplan();
        $esmDao = new model_engineering_project_esmproject();
        foreach ($rows as $key => $row) {
        	$reArr = $reDao->getDetail_d($row['id']);
            $esmState = $esmDao->getStatusNameByContractId_d($row['id']);
            if(empty($esmState)){
           	   switch($row['DeliveryStatus']) {
           	   	 case "WFH" : $esmState = "未发货";
           	       break;
           	     case "BFFH" : $esmState = "部分发货";
           	       break;
           	     case "YFH" : $esmState = "已发货";
           	       break;
           	   }
           }
            $incocmeAll = 0;
            $endDate = "";
            $reMoney = 0;
            for($i=0;$i<15;$i++){
           	   $j = $i + 1;
               $row["incomeDate_".$j] = $reArr[$i]["Tday"];
               $row["incomePtn_".$j] = $reArr[$i]["paymentPer"];
               $row["incomeMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               $row["invoiceMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["invoiceMoney"]-$reArr[$i]["deductMoney"];
                if(!empty($reArr[$i]["Tday"])){
                    $incocmeAll +=  $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
                }
               if(!empty($reArr[$i]['id'])){
               	  $tt = $this->service->getTdayListEndDate($row['id'], $reArr[$i]['paymenttermId']);
                  $tState = $this->service->getTdayListEndDate($row['id'], $reArr[$i]['paymenttermId']);
               }else{
               	  $tt = "-";
               }
               if(!empty($reArr[$i]['id'])){
               	 $endDate .= $tt." ; ";
               }
               if(!empty($reArr[$i]["Tday"])){
               	 $reMoney += $reArr[$i]['money'];
               }
            }
            //转义科学计数
            $incocmeAll = number_format($incocmeAll,2,'.','');
            $row["Tmoney"] = $incocmeAll;
            $row["projectEndDate"] = $endDate;
            $row["projectState"] = $esmState;
            $row["unTdayMoney"] = bcsub($row["unIncomeMoney"],$incocmeAll,2);



            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        // $colArr = util_jsonUtil::iconvUTF2GBArr($colArr);

        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"合同T日信息表".$t_date);
    }

    function count_days($a,$b){
	    $a_dt = getdate($a);
	    $b_dt = getdate($b);
	    $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
	    $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
	    return round(abs($a_new-$b_new)/86400);
	}

    /*************************T 日  报表****end****************************************************************/

   /********************* 财务应收款报表  开始*****************************************************/
    /**
     * 应收账款分析
     */
    function c_reportIncomeList(){
    	$this->view('reportIncomeList');
    }
    function c_reportIncomeJson(){
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_REQUEST);

        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $limit = $service->initLimit_treport(null,"r");

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");
        if($limit){
            $rows = $service->pageBySqlId("select_roportIncomeList");
        }

        //插入 回款条款数据
        foreach($rows as $k => $v){
        	//计算 未回款总额分类项
        	$unIncomeArr = $service->getUnIncomeArr($v['idStr'],$overPointY,$overPointM);
	        $rows[$k]['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $rows[$k]['unInomeMoney_q'] = $unIncomeArr['unInomeMoney_q'];
	        $rows[$k]['unInomeMoney_nq'] = $unIncomeArr['unInomeMoney_nq'];
	        $rows[$k]['unInomeMoney_aq'] = $unIncomeArr['unInomeMoney_aq'];
	        $rows[$k]['noTMoney'] = $unIncomeArr['noTMoney'];

	        $rows[$k]['unAccMoney'] = $rows[$k]['unInomeMoney'] + $rows[$k]['unInomeMoney_q'] + $rows[$k]['unInomeMoney_nq'] + $rows[$k]['unInomeMoney_aq'] + $rows[$k]['noTMoney'];
	        $rows[$k]['accMoney'] = $rows[$k]['incomeMoney'] + $rows[$k]['unAccMoney'];
	        $rows[$k]['conTMoney'] = $rows[$k]['unInomeMoney'] + $rows[$k]['unInomeMoney_q'] + $rows[$k]['unInomeMoney_nq'] + $rows[$k]['unInomeMoney_aq'];
	        $rows[$k]['rondIncome'] = round($rows[$k]['incomeMoney'] / ($rows[$k]['incomeMoney'] + $rows[$k]['unInomeMoney']),4) * 100 . "%";
        }
        //数据加入安全码
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
    function c_reportIncomeExcel(){

        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
        $searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = "sql: and 1=1 ";
        }

        $service->getParam($_REQUEST);
        //登录人
//         $appId = $_SESSION['USER_ID'];
        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $limit = $service->initLimit_treport(null,"r");

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");
        $rows = array();
        if($limit){
            ini_set('memory_limit', '1024M');
            $rows = $service->listBySqlId('select_roportIncomeList');
        }
        $arr = array();
        $arr['collection'] = $rows;

        //匹配导出列
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        foreach ($rows as $key => $row) {
        	//计算 未回款总额分类项
            $unIncomeArr = $service->getUnIncomeArr($row['idStr'],$overPointY,$overPointM);

	        $row['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $row['unInomeMoney3'] = $unIncomeArr['unInomeMoney3'];
	        $row['unInomeMoney6'] = $unIncomeArr['unInomeMoney6'];
	        $row['unInomeMoney12'] = $unIncomeArr['unInomeMoney12'];
	        $row['unInomeMoney24'] = $unIncomeArr['unInomeMoney24'];
	        $row['unInomeMoney_q'] = $unIncomeArr['unInomeMoney_q'];
	        $row['unInomeMoney_nq'] = $unIncomeArr['unInomeMoney_nq'];
	        $row['unInomeMoney_aq'] = $unIncomeArr['unInomeMoney_aq'];
	        $row['noTMoney'] = $unIncomeArr['noTMoney'];

	        $row['unAccMoney'] = $row['unInomeMoney'] + $row['unInomeMoney_q'] + $row['unInomeMoney_nq'] + $row['unInomeMoney_aq'] + $row['noTMoney'];
	        $row['accMoney'] = $row['incomeMoney'] + $row['unAccMoney'];
	        $row['conTMoney'] = $row['unInomeMoney'] + $row['unInomeMoney_q'] + $row['unInomeMoney_nq'] + $row['unInomeMoney_aq'];
	        $row['rondIncome'] = round($row['incomeMoney'] / ($row['incomeMoney'] + $row['unInomeMoney']),4) * 100 . "%";

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"应收账款分析");

    }


    /**
     * 逾期应收款
     */
    function c_reportUnAccList(){
    	$this->view('reportUnAccList');
    }
    function c_reportUnAccJson(){
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_REQUEST);
        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        $limit = $service->initLimit_treport(null,"r");

        // 加入一批特殊过滤，均以not in 处理
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // 客户名称过滤
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");

        if($limit){
            $rows = $service->pageBySqlId("select_roportIncomeList");
        }
        //插入 回款条款数据
        foreach($rows as $k => $v){
        	//计算 未回款总额分类项
        	$unIncomeArr = $service->getUnIncomeArr($v['idStr'],$overPointY,$overPointM);

	        $rows[$k]['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $rows[$k]['unInomeMoney3'] = $unIncomeArr['unInomeMoney3'];
	        $rows[$k]['unInomeMoney6'] = $unIncomeArr['unInomeMoney6'];
	        $rows[$k]['unInomeMoney12'] = $unIncomeArr['unInomeMoney12'];
	        $rows[$k]['unInomeMoney24'] = $unIncomeArr['unInomeMoney24'];

        }
        //数据加入安全码
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

   /********************* 财务应收款报表  结束*****************************************************/

    /**
     * 跳转合同冗余值更新页面
     */
    function c_toUpdateConRedundancy(){
        $this->view('updateConRedundancy');
    }

    /**
     *  通过Ajax更新合同检查项
     */
    function c_ajaxUpdateCheckedItems(){
        $cid = isset($_POST['contractId'])? $_POST['contractId'] : '';
        $result = $this->service->updateContractObjComList_d($cid,'contractUpdate');
        echo $result? '1' : '0';
    }

    /**
     *  通过Ajax更新合同信息的冗余数据
     */
    function c_ajaxUpdateSalesContractVal(){
        $cid = isset($_POST['contractId'])? $_POST['contractId'] : '';
        $states = isset($_POST['states'])? $_POST['states'] : '';
        $result = $this->service->updateSalesContractVal_d($cid,'contractUpdate',$states);
        echo $result? '1' : '0';
    }

    /**
     *  更新合同表冗余值
     */
    function c_updateConRedundancy(){
        echo str_pad("更新中...",4096).'<hr />';
        flush();
        ob_flush();
        sleep(0.1);
        ini_set("memory_limit","1000M");
        set_time_limit(0);
        $service =  $this->service;
        $service->updateConRedundancy_d();
        echo "更新完成";
    }

    /**
     * 更新合同的项目状态
     */
    function c_updateProjectStatus() {
        echo $this->service->updateProjectStatus_d($_GET['id']);
    }


    function c_ajaxGetExcelBtnLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        $type = isset($_REQUEST['type'])? $_REQUEST['type'] : '';
        if($type != ''){
            switch ($type){
                case 't':// T日报表
                    if(isset($sysLimit['T日表导出权限'])){
                        echo $sysLimit['T日表导出权限'];
                    }else{
                        echo "0";
                    }
                    break;
                case 'r':// 应收款报表
                    if(isset($sysLimit['应收款导出权限'])){
                        echo $sysLimit['应收款导出权限'];
                    }else{
                        echo "0";
                    }
                    break;
            }
        }else{
           echo "0";
        }
    }

    /**
     * 检查海外修改权限
     */
    function c_chkHwEditLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        if(isset($sysLimit['海外修改'])){
            echo $sysLimit['海外修改'];
        }else{
            echo "0";
        }
    }

    /**
     * 通过合同ID以及合同金额获取合同执行进度
     */
    function c_getExePortion(){
        $cid = isset($_REQUEST['contractId'])? $_REQUEST['contractId'] : '';
        $contractMoney = isset($_REQUEST['contractMoney'])? $_REQUEST['contractMoney'] : 0;
        if($cid != ''){
            $esmprojectDao = new model_engineering_project_esmproject(); // 工程项目
            $proTmp = $esmprojectDao->getProByCid($cid);

            $allProjMoneyWithSchl = 0;// 合同关联项目合同额*项目进度
            foreach($proTmp as $p){
                // 解决因为金额过长出现科学计数法,导致计算出错的问题
                $projectMoneyWithTax = sprintf("%.3f", $p['projectMoneyWithTax']);
                $projectMoneyWithTax = bcmul($projectMoneyWithTax,1,10);
                $allProjMoneyWithSchl += bcmul($projectMoneyWithTax,bcdiv($p['projectProcess'],100,6),6);
            }
            $allProjMoneyWithSchl = sprintf("%.3f", $allProjMoneyWithSchl);
            $conProgress = bcmul(bcdiv($allProjMoneyWithSchl,$contractMoney,9),100,3);
            $conProgress = round($conProgress,2);
            echo $conProgress;
        }else{
            echo 0;
        }
    }

    /**
     * ajax检查变更后合同额是否在可变范围内 (PMS 657)
     */
    function c_ajaxChkContractMoneyForChange(){
        $cid = isset($_REQUEST['contractId'])? $_REQUEST['contractId'] : '';
        $cMoney = isset($_REQUEST['contractMoney'])? $_REQUEST['contractMoney'] : '';
        $validMoneys = $this->service->getValidContractMoney($cid);
        $validAmount = 0;
        if(is_array($validMoneys)){
            foreach ($validMoneys as $key => $val){
                $validAmount = round(bcadd($validAmount,$val,4),2);
                $validAmount = sprintf("%.2f", $validAmount);
            }
            $validAmount = ($validAmount < 0)? 0 : $validAmount;
        }

        if($cMoney < $validAmount){
            echo $validAmount;
        }else{
            echo "ok";
        }
    }

    /**
     * 归档信息修改表单
     */
    function c_toUpdateArchivedInfo(){
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('file', '暂无任何附件');
        $this->assign('applyDate', day_date);

        //当前盖章申请人
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);

        //设置业务经办人为当前登录人
        $this->assign('attnId', $_SESSION['USER_ID']);
        $this->assign('attn', $_SESSION['USERNAME']);
        $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
        $this->assign('attnDept', $_SESSION['DEPT_NAME']);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->assign('contractType', 'HTGZYD-04');
        $this->assign('contractTypeName', $this->getDataNameByCode('HTGZYD-04'));
        $this->view('update-archivedInfo');
    }

    /**
     * 修改归档信息
     */
    function c_updateArchivedInfo(){
        $postData = $_POST[$this->objName];
        $id = isset($postData['id'])? $postData['id'] : '';
        $updateArr = array();
        $updateArr['id'] = $id;
        $updateArr['contractName'] = isset($postData['contractName'])? $postData['contractName'] : '';
        $updateArr['partAContractCode'] = isset($postData['partAContractCode'])? $postData['partAContractCode'] : '';
        $updateArr['partAContractName'] = isset($postData['partAContractName'])? $postData['partAContractName'] : '';
        $updateArr['paperSignTime'] = isset($postData['paperSignTime'])? $postData['paperSignTime'] : '';
        $updateArr = $this->service->addUpdateInfo($updateArr);

        if(empty($id)){
            msg('保存失败!');
        }else{
            $result = $this->service->updateById($updateArr);
            if($result){
                msg('保存成功!');
            }else{
                msg('保存失败!');
            }
        }
    }

    /**
     * 打开合同 PMS 731
     */
    function c_restartContract(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        if(!empty($id)){
            $result = $this->service->updateById(array("id"=>$id,"state"=>4));

            if($result){
                // 添加执行轨迹记录
                $tracksDao = new model_contract_contract_tracks();
                $proDao = new model_contract_conproject_conproject();
                $contract = $this->service->get_d($id);
                $tracksObject = array(
                    'contractId' => $contract['id'],//合同ID
                    'contractCode'=> $contract['contractCode'],//合同编号
                    'exePortion' => $proDao->getConduleBycid($contract['id']),//合同执行进度
                    'schedule' => "",
                    'modelName'=>'contractRestart',
                    'operationName'=>'重新打开合同',
                    'result'=>'1',
                    'recordTime'=>date("Y-m-d H:i:s"),
                    'expand2'=>'model_contract_contract_contract:c_restartContract'
                );
                $result = $tracksDao->addRecord($tracksObject);
            }
            echo ($result)? "ok" : "fail";
        }else{
            echo "fail";
        }
    }
}