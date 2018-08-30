<?php

/**
 * @author Show
 * @Date 2012年10月11日 星期四 10:01:33
 * @version 1.0
 * @description:报销汇总主表控制层
 */
class controller_finance_expense_exsummary extends controller_base_action
{

    function __construct()
    {
        $this->objName = "exsummary";
        $this->objPath = "finance_expense";
        parent:: __construct();
    }

    /**
     * 跳转到报销汇总主表列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 跳转到新增报销汇总主表页面
     */
    function c_toAdd()
    {
        $this->view('add');
    }

    /**
     * 跳转到编辑报销汇总主表页面
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
     * 跳转到查看报销汇总主表页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->getInfo_d($_GET['id']);
        $sourceType = $this->service->getSourceType($_GET['id']);
        $this->assign("sourceType",$sourceType);

        $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
        $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
        $personnelDao = new model_hr_personnel_personnel();
        $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
        $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
        // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
        $isDiffBillInfo = 0;
        if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
            $payeeName = explode("(",$billBaseInfo['payeeName']);
            $payeeName = $payeeName[0];
        }else{
            $payeeName = $billBaseInfo['payeeName'];
        }
        if($CostManName != $payeeName ||
            $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
            $isDiffBillInfo = 1;
        }
        $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *本单为代报销" : "";
        $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

        if ($obj['isNew'] == 1) {
            $this->assignFunc($obj);
            if ($obj['Amount'] != $obj['CheckAmount']) {
                $this->assign('Amount', $obj['CheckAmount']);
            }

            // 查看是否存在关联租车登记费用填报记录的
            $carRentalRelativeId = '';
            $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
            $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
            if($carRentalRelativeTmpObj){
                $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
            }
            $this->assign('tempExpenseId',$carRentalRelativeId);

            //渲染报销类型
            $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

            //附件添加{file}
            $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

            $approvalPid = (isset($obj['allregisterid']) && $obj['allregisterid'] != 0)? $obj['allregisterid'] : $_GET['id'];
            $approvalType = (isset($obj['allregisterid']) && $obj['allregisterid'] != 0)? "oa_outsourcing_allregister" : "cost_summary_list";
            $this->assign("approvalPid",$approvalPid);
            $this->assign("approvalType",$approvalType);

            $this->view('view');
        } else {
            succ_show('general/costmanage/reim/summary_detail.php?status=出纳付款&BillNo=' . $obj['BillNo']);
        }
    }

    /**
     * 审批查看
     */
    function c_toAudit()
    {
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $editLimit = isset($limitArr['审批页面编辑权限'])? $limitArr['审批页面编辑权限'] : '';
        $addCostLimit = isset($limitArr['新增费用类权限'])? $limitArr['新增费用类权限'] : '';
        $this->assign("addCostLimit",$addCostLimit);
        if($editLimit == 1){// 判断是否含有修改发票的权限,有的话显示可编辑审批页面
            $this->c_toAuditEdit();
        }else{
            $this->permCheck(); //安全校验
            $obj = $this->service->getInfo_d($_GET['id']);

            $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
            $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
            $personnelDao = new model_hr_personnel_personnel();
            $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
            $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
            // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
            $isDiffBillInfo = 0;
            if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
                $payeeName = explode("(",$billBaseInfo['payeeName']);
                $payeeName = $payeeName[0];
            }else{
                $payeeName = $billBaseInfo['payeeName'];
            }
            if($CostManName != $payeeName ||
                $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
                $isDiffBillInfo = 1;
            }
            $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *本单为代报销" : "";
            $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

            $sourceType = $this->service->getSourceType($_GET['id']);
            $this->assign("sourceType",$sourceType);

// 		$areaId = $obj['salesAreaId'];	//费用归属区域
// 		$this->showBudget($areaId);  //显示区域的预警
            //只有售前、售后才需显示统计值
            if ($obj['DetailType'] == '4' || $obj['DetailType'] == '5') {
                $this->showStatistic(array('userId' => $obj['feeManId'], 'userName' => $obj['feeMan'],
                    'areaId' => $obj['salesAreaId'], 'areaName' => $obj['salesArea'], 'costBelongerId' => $obj['CostBelongerId']));
            }
            if ($obj['isNew'] == 1) {
                foreach ($obj as $key => $val) {
                    $this->assign($key, $val);
                }

                // 查看是否存在关联租车登记费用填报记录的
                $carRentalRelativeId = '';
                $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
                $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
                if($carRentalRelativeTmpObj){
                    $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
                }
                $this->assign('tempExpenseId',$carRentalRelativeId);

                //渲染报销类型
                $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

                //附件添加{file}
                $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

                $this->assign("showProjectSee",($obj['DetailType'] == 1)? "style='display:none';" : "");// 部门费用的不显示 “查看项目报销” 链接

                $this->view('audit');
            } else {
                succ_show('general/costmanage/reim/summary_detail.php?status=出纳付款&BillNo=' . $obj['BillNo']);
            }
        }
    }

    /**
     * 审批查看 - 可编辑
     */
    function c_toAuditEdit()
    {
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $editLimit = isset($limitArr['审批页面编辑权限'])? $limitArr['审批页面编辑权限'] : '';
        $addCostLimit = isset($limitArr['新增费用类权限'])? $limitArr['新增费用类权限'] : '';
        $this->assign("addCostLimit",$addCostLimit);
        if($editLimit != 1){// 判断是否含有修改发票的权限,有的话显示可编辑审批页面
            $this->c_toAudit();
        }else{
            $this->permCheck(); //安全校验
            $obj = $this->service->getInfoEdit_d($_GET['id']);

            $CostMan = isset($obj['CostMan'])? $obj['CostMan'] : '';
            $CostManName = isset($obj['CostManName'])? $obj['CostManName'] : '';
            $personnelDao = new model_hr_personnel_personnel();
            $CostManInfo = $personnelDao->getPersonInfoByUserId($CostMan);
            $billBaseInfo = $this->service->getBillBaseInfo($obj['BillNo']);
            // echo "<pre>";print_r($CostManInfo);print_r($billBaseInfo);exit();
            $isDiffBillInfo = 0;
            if(isset($billBaseInfo['payeeIsEmpty']) && $billBaseInfo['payeeIsEmpty'] == 1){
                $payeeName = explode("(",$billBaseInfo['payeeName']);
                $payeeName = $payeeName[0];
            }else{
                $payeeName = $billBaseInfo['payeeName'];
            }
            if($CostManName != $payeeName ||
                $CostManInfo['oftenAccount'] != $billBaseInfo['account']){
                $isDiffBillInfo = 1;
            }
            $isDiffBillInfoMsg = ($isDiffBillInfo == 1)? " *本单为代报销" : "";
            $this->assign("isDiffBillInfoMsg",$isDiffBillInfoMsg);

            $sourceType = $this->service->getSourceType($_GET['id']);
            $this->assign("sourceType",$sourceType);

            if ($obj) {
// 			$areaId = $obj['salesAreaId'];	//费用归属区域
// 			$this->showBudget($areaId);  //显示区域的预警
                foreach ($obj as $key => $val) {
                    $this->assign($key, $val);
                }

                // 查看是否存在关联租车登记费用填报记录的
                $carRentalRelativeId = '';
                $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where expenseId = {$obj['ID']}";
                $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
                if($carRentalRelativeTmpObj){
                    $carRentalRelativeId = $carRentalRelativeTmpObj['id'];
                }
                $this->assign('tempExpenseId',$carRentalRelativeId);

                //渲染报销类型
                $this->assign('DetailTypeCN', $this->service->rtDetailType($obj['DetailType']));

                //附件添加{file}
                $this->assign('file', $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name));

                $this->assign("showProjectSee",($obj['DetailType'] == 1)? "style='display:none';" : "");// 部门费用的不显示 “查看项目报销” 链接

                $this->view('auditedit');
            } else {
                echo '没有找到相关报销单据';
            }
        }
    }

    /**
     * 根据区域ID获取报销预警并显示到页面
     */
    function showBudget($areaId)
    {
        if (isset($areaId)) {
            $budgetDetailDao = new model_finance_budget_budgetDetail();
            $budgetDetail = $budgetDetailDao->getByParam(2015, $areaId);

            $currentSeason = $budgetDetailDao->currentSeason();
            $totalBudget = $budgetDetail['totalBudget'];  //当年预算
            if ($totalBudget > 0) { // 有做年度预算的区域在审批时才显示预警
                $currentBudget = $budgetDetail[$currentSeason . 'Budget']; //当前季度预算
                $yearFinal = $budgetDetail['final'];    //当年决算
                $seasonFinal = $budgetDetail[$currentSeason . 'Final']; //当前季度决算
                $seasonPercent = $seasonFinal / $currentBudget;  //当前季度决算占预算比例
                $yearPercent = $yearFinal / $totalBudget;  //当前季度决算占预算比例

                //预警颜色判断
                if ($seasonPercent > 1 || $currentBudget <= 0) {
                    $seasonColor = 'red';
                } else if ($seasonPercent > 0.9) {
                    $seasonColor = 'purple';
                } else if ($seasonPercent > 0.7) {
                    $seasonColor = 'yellow';
                }
                if ($yearPercent > 1 || $totalBudget <= 0) {
                    $yearColor = 'red';
                } else if ($yearPercent > 0.9) {
                    $yearColor = 'purple';
                } else if ($yearPercent > 0.7) {
                    $yearColor = 'yellow';
                }
                $budgetHtml = "<td width='15%'>" . $budgetDetail['area'] . "</td><td width='15%'>" . $budgetDetail[$currentSeason . 'Budget'] . "</td><td width='15%'>" . $budgetDetail['totalBudget'] . "</td>" .
                    "<td width='15%' style='color:$seasonColor;'>$seasonFinal</td><td width='15%' style='color:$yearColor;'>$yearFinal</td>";

                $this->assign('budgetDetail', $budgetHtml);
            }
        }
    }

    /**
     * 根据表单名称删除汇总表
     */
    function c_delByBillNo()
    {
        try {
            $this->service->delByBillNo_d($_POST ['BillNo']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 审批完成后调用方法
     */
    function c_dealAfterAudit()
    {
        $spid = $_GET['spid'];
        $this->service->dealAfterAudit_d($spid);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * 获取统计值并显示到页面
     * @param $obj
     */
    function showStatistic($obj)
    {
        //获取统计值
        $rs = $this->service->getStatistic($obj['userId'], $obj['areaId']);
        $userFeeArr = $rs['userFee'];//个人费用
        $areaFeeArr = $rs['areaFee'];//区域费用
        //如果费用承担人分管不同区域，则要分开区域进行统计（审批的时候，销售负责人只能查看负责区域的费用统计）
// 		if($obj['costBelongerId'] == $_SESSION['USER_ID']){
// 			//获取销售负责人管辖的区域
// 			$salepersonDao = new model_system_saleperson_saleperson();
// 			$salesAreaArr = $salepersonDao->getSaleArea($_SESSION['USER_ID']);
// 			if(empty($salesAreaArr)){
// 				$userFeeArr = '';
// 			}else{
// 				$idArr = array();
// 				foreach ($salesAreaArr as $v){
// 					array_push($idArr, $v['salesAreaId']);
// 				}
// 				foreach ($userFeeArr as $k => $v){
// 					if(!in_array($k, $idArr)){
// 						unset($userFeeArr[$k]);
// 					}
// 				}
// 			}
// 		}
        $budgetHtml = "";
        //个人费用统计
        if (!empty($userFeeArr)) {
// 			$regionDao = new model_system_region_region();
            foreach ($userFeeArr as $k => $v) {
// 				$rs = $regionDao->find(array('id' => $k),null,'areaName');
// 				$areaName = $rs['areaName'];
                $userFee = empty($v['fee']) ? 0 : $v['fee'];//个人销售费用
                $userConMoney = empty($v['contract']) ? 0 : $v['contract'];//个人新签合同额
                $userRate = round($userFee / ($userConMoney * 0.0002), 2);//个人费用与合同同步率
                $userColor = $userRate >= 100 ? 'red' : 'green';//个人显示颜色
                $userColor = ($userFee <= 0 || $userConMoney <= 0) ? 'red' : $userColor;//只有费用而没有合同（新签合同额为0），或只有合同，没有费用时，标红 PMS2173
                $budgetHtml .= <<<EOT
					<tr>
						<td width="15%">$obj[userName]</td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('$obj[userId]','$k','fee')" class="formatMoney">$userFee</a></td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('$obj[userId]','$k','contract')" class="formatMoney">$userConMoney</a></td>
						<td width="15%" style="color:$userColor;">$userRate%</td>
					</tr>
EOT;
            }
        }
        //区域费用统计
        if (!empty($areaFeeArr)) {
            foreach ($areaFeeArr as $k => $v) {
                $areaFee = empty($v['fee']) ? 0 : $v['fee'];//区域销售费用
                $areaConMoney = empty($v['contract']) ? 0 : $v['contract'];//区域新签合同额
                $areaRate = round($areaFee / ($areaConMoney * 0.0002), 2);//区域费用与合同同步率
                $areaColor = $areaRate >= 100 ? 'red' : 'green';//区域显示颜色
                $areaColor = ($areaFee <= 0 || $areaConMoney <= 0) ? 'red' : $areaColor;//只有费用而没有合同（新签合同额为0），或只有合同，没有费用时，标红 PMS2173
                $budgetHtml .= <<<EOT
					<tr>
						<td width="15%">$obj[areaName]</td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('','$k','fee')" class="formatMoney">$areaFee</a></td>
						<td width="15%"><a href="javascript:void(0);" onclick="showStatisticDetail('','$k','contract')" class="formatMoney">$areaConMoney</a></td>
						<td width="15%" style="color:$areaColor;">$areaRate%</td>
					</tr>
EOT;
            }
        }

        $this->assign('statistic', $budgetHtml);
    }

    /**
     * 查看费用统计明细
     */
    function c_showStatisticDetail()
    {
        $this->assign('userId', $_GET['userId']);
        $this->assign('areaId', $_GET['areaId']);
        $this->assign('thisYear', $_GET['thisYear']);
        $this->assign('exeDeptCode', $_GET['exeDeptCode']);
        if (isset($_GET['view_type'])) {
            $this->assign('view_type', 'view_type=' . $_GET['view_type']);
        }


        $this->view('statistictab-' . $_GET['feeType']);
    }

    /**
     * 获取新签合同明细信息
     */
    function c_getStatisticDetailForContract(){
        $storeYear = (isset($_REQUEST['ExaYear']) && $_REQUEST['ExaYear'] != '')? " and m.storeYear = '{$_REQUEST['ExaYear']}'" : "";
        $areaCode = (isset($_REQUEST['areaCode']) && $_REQUEST['areaCode'] != '')? " and m.areaCode = '{$_REQUEST['areaCode']}'" : "";
        $sql = <<<EOT
        select 
            m.id,
            c.id as contractId,
            case c.contractType  when 'HTLX-XSHT' then '销售合同' when 'HTLX-FWHT' then '服务合同' when 'HTLX-ZLHT' then '租赁合同' when 'HTLX-YFHT' then '研发合同' when 'HTLX-PJGH' then '零配件合同'
            end as contType,
            case c.state  when 0 then '保存' when 1 then '审批中' when 2 then '执行中' when 4 then '已完成' when 3 then '已关闭' when 7 then '异常关闭'
            end as contState,
            if(c.id is null,m.contractAllMoney,sum(m.contractMoney)) as contractAllMoney,c.contractMoney,c.contractName,m.contractCode from oa_bi_conproduct_month m
        left join oa_contract_contract c on c.id = m.contractId
        where 1=1 $areaCode $storeYear
        group by m.contractId order by m.id desc
EOT;
        $rst = $this->service->_db->getArray($sql);
        $countMoney = 0;
        foreach ($rst as $row){
            $countMoney = bcadd($countMoney,$row['contractAllMoney'],5);
        }
        $countMoney = sprintf("%.4f", $countMoney);
        $rst[] = array("contType" => "合计","contractAllMoney" => $countMoney);
        $rst = $this->sconfig->md5Rows($rst);

        //数据加入安全码
        echo util_jsonUtil::encode($rst);
    }

    /**
     * 查看费用统计数据页面
     */
    function c_testGetStit()
    {
        $rs = $this->service->getStatisticSpl($_GET['show_type'], $_GET['aid'], '2017');
        echo "<pre>";
        var_dump($rs);
    }

    /**
     * 报表展示
     */
    function c_showStatisticAll()
    {
        $this->assign('show_type', isset($_GET['show_type']) ? $_GET['show_type'] : 'byGroup');
        $this->view('showStatisticAll');
    }

    /**
     * 获取财务周期里面的年份数据
     */
    function c_getFinancePeriodYear()
    {
        $sql = "select thisYear from oa_finance_accountingperiod group by thisYear order by thisYear desc";
        $obj = $this->service->_db->getArray($sql);
        $data = array();
        $data['thisYear'] = date("Y");
        $data['allYears'] = array();
        foreach ($obj as $k => $v) {
            $arr['value'] = $v['thisYear'];
            $arr['text'] = $v['thisYear'] . "年";
            array_push($data['allYears'], $arr);
        }
        echo util_jsonUtil:: encode($data);
    }

    /**
     * 查看费用统计页面数据
     */
    function c_pageStatisticAll()
    {
        $type = isset($_REQUEST['show_type']) ? $_REQUEST['show_type'] : '';// 显示类型（按人员/地区）
        $year = isset($_REQUEST['theYear']) ? $_REQUEST['theYear'] : '';
        $SalesAreaId = isset($_REQUEST['SalesAreaId']) ? $_REQUEST['SalesAreaId'] : '';
        $isBig = isset($_REQUEST['isBigGroup']) ? $_REQUEST['isBigGroup'] : 0;
        $isAreaDetail = isset($_REQUEST['isAreaDetail']) ? $_REQUEST['isAreaDetail'] : 0;

        if ($type != '') {
            $ext_condition = '';
            $personFilter = '';
            // 页面搜索过滤
            if ($_REQUEST['isSearchTag_'] == 'true' || $SalesAreaId) {
                $condition = $group = $order = $limit = '';
                $pageNum = $_REQUEST['page'];
                $pageSize = $_REQUEST['pageSize'];
                $startNum = ($pageNum - 1) * $pageSize;
                $limit = $SalesAreaId ? "" : "limit {$startNum},{$pageSize}";
                $arr['page'] = $pageNum;

                if ($_REQUEST['show_type'] == 'byMan') {

                } else if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != ''){
                    $dir = ($_REQUEST['dir'] != '')? $_REQUEST['dir'] : '';
                    $order = ' ORDER BY t.'.$_REQUEST['sort'].' '.$dir;
                }else {
                    $order = ' ORDER BY a.exeDeptCode, t.SalesAreaId';
                }

                switch ($_REQUEST['show_type']) {
                    case 'byMan':
                        if (!empty($_REQUEST['feeMan'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
                            $condition = "and t.feeMan like '%{$cond}%'";
                        } else if (!empty($_REQUEST['SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
                            $condition = "and t.SalesArea like '%{$cond}%'";
                        } else if (!empty($_REQUEST['exeDeptName'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
                            $condition = "and a.exeDeptName like '%{$cond}%'";
                        } else if (!empty($_REQUEST['feeMan,exeDeptName,SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan,exeDeptName,SalesArea']);
                            $condition = "and (a.exeDeptName like '%{$cond}%' OR t.SalesArea like '%{$cond}%' OR t.feeMan like '%{$cond}%')";
                        }

                        // 由于这里 $SalesAreaId 是一个组合条件，所以要做一下过滤
                        if ($SalesAreaId) {
                            $SalesAreaIdArr = explode('|', $SalesAreaId);
                            $condition .= "and t.SalesAreaId = '{$SalesAreaIdArr[0]}'";
                            if ($SalesAreaIdArr[1]) {
                                $year = $SalesAreaIdArr[1];
                            }
                            if ($SalesAreaIdArr[2]) {
                                $personFilter = util_jsonUtil::iconvUTF2GB($SalesAreaIdArr[2]);
                            }
                        }
                        $group = " group by t.feeManId ";
                        break;
                    case 'byArea':
                        $condition = "and t.SalesAreaId = '{$SalesAreaId}'";
                        break;
                    default :
                        if (!empty($_REQUEST['feeMan'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
                            $personFilter = $cond;
                            $group .= " HAVING totalContract > 0 OR totalFee > 0";
                        } else if (!empty($_REQUEST['SalesArea'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
                            $condition = "and t.SalesArea like '%{$cond}%'";
                        } else if (!empty($_REQUEST['exeDeptName'])) {
                            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
                            $condition = "and a.exeDeptName like '%{$cond}%'";
                        }
                        break;
                }

                // 读取用户所管理的区域ID
                $regionDao = new model_system_region_region();
                $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

                // 添加板块权限过滤
                $limitArrSql = "";
                if($isAreaDetail != 1){
                    if ($this->service->this_limit['板块权限'] != '' && !strstr($this->service->this_limit['板块权限'], ";;")) {
                        if($AreaIds == ''){
                            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0))";
                        }else{
                            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0) or (a.salesAreaId in ({$AreaIds})))";
                        }
                    }else if($this->service->this_limit['板块权限'] == ''){
                        if($AreaIds != ''){
                            $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
                        }else{
                            $ext_condition = "none";// 返没任何权限查看数据
                        }
                    }
                }

                if($ext_condition != 'none'){
                    $condition .= $limitArrSql;

                    // 最外层包围的条件
                    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != ''){
                        $dir = ($_REQUEST['dir'] != '')? $_REQUEST['dir'] : '';
                        $bigOrder = ' ORDER BY sum(t.'.$_REQUEST['sort'].') '.$dir;
                    }
                    $bigExtSql = ($isBig == 1)? " group by T.exeDeptCode " . $bigOrder : " ";

                    $_SESSION['mainCondition'] = $condition;
                    $_SESSION['mainOrder'] = $order;

                    $ext_condition = $condition . " " . $group . " " . $order;
                    $obj = $this->service->getStatisticSpl($type, $ext_condition, $year, $personFilter, $isBig, $bigExtSql);
                    //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
                    $arr['totalSize'] = $this->service->count ? $this->service->count : ($obj ? count($obj) : 0);
                    $ext_condition = $condition . " " . $group . " " . $order;
                    $bigExtSql = ($isBig == 1)? " group by T.exeDeptCode " . $bigOrder." ".$limit : " ";
                }
            }

            if($ext_condition != 'none'){
                $obj = $this->service->getStatisticSpl($type, $ext_condition, $year, $personFilter, $isBig, $bigExtSql);
                $personAppend = isset($personFilter) ? "|" . $personFilter : "";
                $totalFee = 0;
                $totalContract = 0;
                foreach ($obj as $k => $v) {
                    if(!isset($v['id'])){$obj[$k]['id'] = ($k+1);}
                    $obj[$k]['rate'] = round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2);//同步率
                    $obj[$k]['detailId'] = $v['detailId'] . $personAppend;// 拼接人员过滤
                    $totalFee = bcadd($totalFee, $v['totalFee'], 2);
                    $totalContract = bcadd($totalContract, $v['totalContract'], 2);
                }
                if ($type != 'byMan') {
                    $obj[] = array(
                        'id' => 'noId',
                        'detailId' => 'noId',
                        'moduleName' => '合计',
                        'totalFee' => $totalFee,
                        'totalContract' => $totalContract,
                        'rate' => round($totalFee / ($totalContract * 0.0002), 2)
                    );
                }
            }else{
                $obj = "";
            }
            $rows = $obj;
            $arr['totalSize'] = ($obj && $obj != "") ? $arr['totalSize'] : '0';
            $arr['collection'] = $rows;
            echo util_jsonUtil:: encode($arr);
        } else {
            echo "缺少字段{show_type}";
        }
    }

    /**
     * 获取归属大区底下的所以区域的费用信息
     */
    function c_getSubStatisticByExeDep()
    {
        $exeDepFilter = isset($_REQUEST['exeDepFilter']) ? $_REQUEST['exeDepFilter'] : '';
        $exeDepFilterArr = explode("|",$exeDepFilter);
        $exeDepCode = isset($exeDepFilterArr[0])? $exeDepFilterArr[0] : '';
        $year = isset($exeDepFilterArr[1])? $exeDepFilterArr[1] : '';

        $condition = isset($_SESSION['mainCondition'])? $_SESSION['mainCondition'] : '';

        if($condition == ''){
            // 读取用户所管理的区域ID
            $regionDao = new model_system_region_region();
            $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

            // 添加权限过滤
            $limitArrSql = "";
            if ($this->service->this_limit['板块权限'] != '' && !strstr($this->service->this_limit['板块权限'], ";;")) {
                $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0) or (a.salesAreaId in ({$AreaIds})))";
            }else if($this->service->this_limit['板块权限'] == ''){
                $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
            }

            $condition = $limitArrSql;
        }

        $condition .= ($exeDepCode != '')? " and a.exeDeptCode = '{$exeDepCode}'" : "";

        $order = isset($_SESSION['mainOrder'])? $_SESSION['mainOrder'] : ' ORDER BY a.exeDeptCode';

        $group = " group by t.SalesArea";

        $ext_condition = $condition . " " . $group . " " . $order;
        $obj = $this->service->getStatisticSpl("byGroup", $ext_condition, $year);
        $arr['totalSize'] = $this->service->count ? $this->service->count : ($obj ? count($obj) : 0);

        foreach ($obj as $k => $v) {
            $obj[$k]['rate'] = round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2);//同步率
        }

        $rows = $obj;
        $arr['totalSize'] = ($obj) ? $arr['totalSize'] : '0';
        $arr['collection'] = $rows;
        echo util_jsonUtil:: encode($arr);
    }

    /**
     * 调整费用统计点击区域后的详细人员
     */
    function c_toAreaDetail()
    {
        $this->assign("areaId",isset($_GET['areaId'])? $_GET['areaId'] : '');
        $this->assign("thisYear",isset($_GET['thisYear'])? $_GET['thisYear'] : '');
        $this->view('showAreaDetail');
    }


    /* ======================== 导出功能新调整 ======================== */
    /**
     * 根据传入大区编号以及年份读出大区内销售区域的统计结果
     * @param $exeDepCodes
     * @param $year
     * @return mixed
     */
    function c_getSubStatisticByExeDeps($exeDepCodes,$year){
        // 读取用户所管理的区域ID
        $regionDao = new model_system_region_region();
        $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

        // 添加权限过滤
        $limitArrSql = "";
        if ($this->service->this_limit['板块权限'] != '' && !strstr($this->service->this_limit['板块权限'], ";;")) {
            $AreaIdsLimit = ($AreaIds == '')? '' : "or (a.salesAreaId in ({$AreaIds}))";
            $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0) {$AreaIdsLimit})";
        }else if($this->service->this_limit['板块权限'] == ''){
            $limitArrSql = ($AreaIds == '')? "" : " and a.salesAreaId in ({$AreaIds})";
        }

        $condition = $limitArrSql;

        $condition .= ($exeDepCodes != '')? " and a.exeDeptCode in ({$exeDepCodes})" : "";

        $order = ' ORDER BY re.moduleName,a.exeDeptCode';

        $group = " group by t.SalesArea,a.exeDeptCode";

        $ext_condition = $condition . " " . $group . " " . $order;
        $obj = $this->service->getStatisticSpl("byGroup", $ext_condition, $year);
        $salesAreaIds = array();
        $year = "";
        $totalFee = $totalContract = 0;
        foreach ($obj as $k => $v) {
            $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//同步率
            $obj[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
            $totalFee = bcadd($totalFee, $v['totalFee'], 2);
            $totalContract = bcadd($totalContract, $v['totalContract'], 2);
            if(!in_array($v['SalesAreaId'],$salesAreaIds)){
                $salesAreaIds[] = $v['SalesAreaId'];
            }
            $year = ($v['thisYear'] != '')? $v['thisYear'] : $year;
        }

        $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
        $obj[] = array(
            'id' => 'noId',
            'moduleName' => '合计',
            'totalFee' => $totalFee,
            'totalContract' => $totalContract,
            'rate' => ($totalRate == 0)? '0.00' : $totalRate
        );

        $backData['obj'] = $obj;
        $backData['salesAreaIds'] = implode($salesAreaIds,",");
        $backData['year'] = $year;
//        echo"<prE>";print_r();exit();
        return $backData;
    }

    /**
     * 根据传入大区下的销售区域以及年份读出销售区域内员工的统计结果
     * @param $salesAreaIds
     * @param $year
     * @return array
     */
    function c_getSubStatisticBySalesareaIds($salesAreaIds,$year){
        if (!empty($_REQUEST['feeMan'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
            $condition = "and t.feeMan like '%{$cond}%'";
        } else if (!empty($_REQUEST['SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
            $condition = "and t.SalesArea like '%{$cond}%'";
        } else if (!empty($_REQUEST['exeDeptName'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
            $condition = "and a.exeDeptName like '%{$cond}%'";
        } else if (!empty($_REQUEST['feeMan,exeDeptName,SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan,exeDeptName,SalesArea']);
            $condition = "and (a.exeDeptName like '%{$cond}%' OR t.SalesArea like '%{$cond}%' OR t.feeMan like '%{$cond}%')";
        }

        // 由于这里 $SalesAreaId 是一个组合条件，所以要做一下过滤
        if ($salesAreaIds != '') {
            $condition .= "and t.SalesAreaId in ({$salesAreaIds})";

            // 读取用户所管理的区域ID
            $regionDao = new model_system_region_region();
            $AreaIds = $regionDao->getUserAreaId($_SESSION['USER_ID'],2);

            // 添加板块权限过滤
            $limitArrSql = "";
            if ($this->service->this_limit['板块权限'] != '' && !strstr($this->service->this_limit['板块权限'], ";;")) {
                if($AreaIds == ''){
                    $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0))";
                }else{
                    $limitArrSql = " and ((FIND_IN_SET(re.module,'{$this->service->this_limit['板块权限']}')>0) or (a.salesAreaId in ({$AreaIds})))";
                }
            }else if($this->service->this_limit['板块权限'] == ''){
                if($AreaIds != ''){
                    $limitArrSql = " and a.salesAreaId in ({$AreaIds})";
                }else{
                    $ext_condition = "none";// 返没任何权限查看数据
                }
            }
        }
        $group = " group by re.moduleName,a.exeDeptCode,t.SalesAreaId,t.feeManId ";
        $order = ' order by re.moduleName,a.exeDeptCode,t.SalesAreaId,t.feeManId ';

        if($ext_condition != 'none'){
            $condition .= $limitArrSql;

            $ext_condition = $condition . " " . $group . " " . $order;
            $obj = $this->service->getStatisticSpl('byMan', $ext_condition, $year);
        }

        $totalFee = $totalContract = 0;
        foreach ($obj as $k => $v) {
            $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//同步率
            $obj[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
            $obj[$k]['totalFee'] = bcadd($v['totalFee'], 0, 2);
            $obj[$k]['totalContract'] = bcadd($v['totalContract'], 0, 2);
            $totalFee = bcadd($totalFee, $v['totalFee'], 2);
            $totalContract = bcadd($totalContract, $v['totalContract'], 2);
        }

        $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
        $obj[] = array(
            'id' => 'noId',
            'moduleName' => '合计',
            'totalFee' => $totalFee,
            'totalContract' => $totalContract,
            'rate' => ($totalRate == 0)? '0.00' : $totalRate
        );
//        echo "<pre>";print_r();exit();
        return $obj;
    }

    /**
     * 检查是否存在导出权限
     */
    function c_chkExportLimit() {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($sysLimit['导出权限']) && $sysLimit['导出权限'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 数据导出
     */
    function c_export() {
        set_time_limit(0); // 设置不超时

        if(isset($_SESSION['exsummary_mainSql'])){
            $sheet1Sql = $_SESSION['exsummary_mainSql'];
            $sheet1Sql = ($sheet1Sql != "")? $sheet1Sql." ORDER BY T.module" : "";
            $sheet1Arr = $this->service->_db->getArray($sheet1Sql);
            $totalFee = $totalContract = 0;
            $exeDeptCodes = $year = "";
            foreach ($sheet1Arr as $k => $v) {
                $rate = round(bcdiv($v['totalFee'],($v['totalContract'] * 0.0002), 3),2);//同步率
                if(!isset($v['id'])){$sheet1Arr[$k]['id'] = ($k+1);}
                $sheet1Arr[$k]['rate'] = ($rate == 0)? '0.00' : $rate;
                $totalFee = bcadd($totalFee, $v['totalFee'], 2);
                $totalContract = bcadd($totalContract, $v['totalContract'], 2);
                $exeDeptCodes .= ($k == 0)? "'{$v['exeDeptCode']}'" : ",'{$v['exeDeptCode']}'";
                $year = ($v['thisYear'] != '')? $v['thisYear'] : $year;
            }

            $totalRate = bcdiv($totalFee,($totalContract * 0.0002), 2);
            $sheet1Arr[] = array(
                'id' => 'noId',
                'moduleName' => '合计',
                'totalFee' => $totalFee,
                'totalContract' => $totalContract,
                'rate' => ($totalRate == 0)? '0.00' : $totalRate
            );

            $byExeDeptArr = $this->c_getSubStatisticByExeDeps($exeDeptCodes,$year);
            $bySalesAreaArr = ($byExeDeptArr['salesAreaIds'] != '')? $this->c_getSubStatisticBySalesareaIds($byExeDeptArr['salesAreaIds'],$byExeDeptArr['year']) : array();

            $tableDataArr = array(
                "sheet1" => $sheet1Arr,
                "sheet2" => $byExeDeptArr['obj'],
                "sheet3" => $bySalesAreaArr,
            );
            //echo "<pre>";print_r($tableDataArr);exit();

            $tableThArr = array(
                "sheet1" => array('moduleName' => '板块', 'exeDeptName' => '归属大区',
                    'totalFee' => '本年销售费用累计', 'totalContract' => '本年新签合同额', 'rate' => '费用与合同同步率'),
                "sheet2" => array('moduleName' => '板块', 'exeDeptName' => '归属大区','SalesArea' => '归属区域',
                    'totalFee' => '本年销售费用累计', 'totalContract' => '本年新签合同额', 'rate' => '费用与合同同步率'),
                "sheet3" => array('moduleName' => '板块', 'exeDeptName' => '归属大区','SalesArea' => '归属区域', 'feeMan' => '销售人员',
                    'totalFee' => '本年销售费用累计', 'totalContract' => '本年新签合同额', 'rate' => '费用与合同同步率'),
            );

            $sheetName = array(
                "sheet1" => "表1-大区",
                "sheet2" => "表2-区域",
                "sheet3" => "表3-人员",
            );

            model_finance_common_financeExcelUtil::export2ExcelUtilForSummary($tableThArr,$tableDataArr,$sheetName,'销售费用统计',array("rate"),array('totalFee','totalContract'));
        }
    }

    /* ======================== 导出功能新调整 ======================== */

    function c_exportOld() {
        set_time_limit(0); // 设置不超时

        // 数据提取
        $personFilter = '';
        $condition = $group = $limit = '';
        if (!empty($_REQUEST['feeMan'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['feeMan']);
            $personFilter = $cond;
            $group .= " HAVING totalContract > 0 OR totalFee > 0";
        } else if (!empty($_REQUEST['SalesArea'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['SalesArea']);
            $condition = "and t.SalesArea like CONCAT('%{$cond}%')";
        } else if (!empty($_REQUEST['exeDeptName'])) {
            $cond = util_jsonUtil::iconvUTF2GB($_REQUEST['exeDeptName']);
            $condition = "and a.exeDeptName like CONCAT('%{$cond}%')";
        }
        $order = ' ORDER BY a.exeDeptName, t.SalesArea, t.feeMan';
        $ext_condition = $condition . " " . $group . " " . $order;

        $rows = $this->service->getStatisticSpl('byMan', $ext_condition, $_REQUEST['theYear'], $personFilter);
        foreach ($rows as $k => $v) {
            $rows[$k]['rate'] = $v['totalContract'] > 0 && $v['totalFee'] > 0 ?
                round($v['totalFee'] / ($v['totalContract'] * 0.0002), 2) : '0.00';//同步率
        }

        if ($rows) {
            model_finance_common_financeExcelUtil::export2ExcelUtil(array(
                'exeDeptName' => '归属大区', 'SalesArea' => '归属区域', 'feeMan' => '销售人员',
                'totalFee' => '本年销售费用累计', 'totalContract' => '本年新签合同额', 'rate' => '费用与合同同步率'
            ), $rows, '销售费用统计', array('rate'));
        } else {
            echo util_jsonUtil::iconvGB2UTF('没有查询到相关数据');
        }
    }

	/**
	 * 前端查询页面打单按钮权限
	 */
    function c_chkPrintLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('finance_expense_exsummary', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if(isset($sysLimit['页面打单权限']) && $sysLimit['页面打单权限'] == 1){
            echo 1;
        }else{
            echo 0;
        }
    }
}