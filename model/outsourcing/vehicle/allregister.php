<?php

/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:43:01
 * @version 1.0
 * @description:租车登记汇总 Model层
 */
class model_outsourcing_vehicle_allregister extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_allregister";
        $this->sql_map = "outsourcing/vehicle/allregisterSql.php";
        parent::__construct();
    }

    //公司权限处理 TODO
    // protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    /**
     * 更新统计
     * @param  [type] $id
     * @param  [type] $obj
     */
    function updateStatistics_d($id, $obj)
    {
        $dateLimit = substr($obj['useCarDate'],0,7);
        $cost = $this->getRentalCarCostVal($id,$dateLimit);
        $rentalCarCost = ($cost > 0)? ",rentalCarCost = {$cost} " : "";

        try {
            $this->start_d();
            $sqlArr = "UPDATE oa_outsourcing_allregister SET "
                . " effectMileage = effectMileage + " . ($obj['effectMileage'] > 0 ? $obj['effectMileage'] : 0)
                . ",reimbursedFuel = reimbursedFuel + " . ($obj['reimbursedFuel'] > 0 ? $obj['reimbursedFuel'] : 0)
                . ",parkingCost = parkingCost + " . ($obj['parkingCost'] > 0 ? $obj['parkingCost'] : 0)
                . ",tollCost = tollCost + " . ($obj['tollCost'] > 0 ? $obj['tollCost'] : 0)
                . ",mealsCost = mealsCost + " . ($obj['mealsCost'] > 0 ? $obj['mealsCost'] : 0)
                . ",accommodationCost = accommodationCost + " . ($obj['accommodationCost'] > 0 ? $obj['accommodationCost'] : 0)
                . ",overtimePay = overtimePay + " . ($obj['overtimePay'] > 0 ? $obj['overtimePay'] : 0)
                . ",specialGas = specialGas + " . ($obj['specialGas'] > 0 ? $obj['specialGas'] : 0)
                . ",actualUseDay = actualUseDay + " . ($obj['actualUseDay'] > 0 ? $obj['actualUseDay'] : 0)
                . ",effectLogTime = effectLogTime + " . ($obj['effectLogTime'] > 0 ? $obj['effectLogTime'] : 0)
                . $rentalCarCost
                . " WHERE "
                . " id = '" . $id . "'";
            $this->query($sqlArr);

            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据汇总ID以及用车月份获取实时的租车费用
     * @param string $allregisterId
     * @param string $useCarDateLimit
     * @return float|int
     */
    function getRentalCarCostVal($allregisterId = '',$useCarDateLimit = ''){
        $registerDao = new model_outsourcing_vehicle_register();
        $registerDao->searchArr['allregisterId'] = $allregisterId;
        if($useCarDateLimit != ''){
            $registerDao->searchArr['useCarDateLimit'] = $useCarDateLimit;
        }
        $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
        $registerRows = $registerDao->listBySqlId('select_Month');

        $rentalCarCost = 0;
        if (is_array($registerRows)) {
            foreach ($registerRows as $key => $val) {
                // 按计费方式带出相应的租车费用
                $rentalCarCostVal = 0;
//                if(!empty($val['rentalContractId']) && $val['rentalContractId'] > 0){
//                    $rentalCarCostVal = $registerDao->getRentalCarCost($val['rentalContractId'],$val['allregisterId'],$val['carNum']);
//                }else{
//                    $rentalCarCostVal = $val['shortRent'];
//                }
                $registerDao->groupBy = null;
                $rentalCarCostVal = $registerDao->getRentalCarCostByRegisterId($val['allregisterId'],$val['carNum']);

                $rentalCarCost = bcadd($rentalCarCost,$rentalCarCostVal,2);
            }
        }
        return $rentalCarCost;
    }

    /**
     * 录入合同信息
     */
    function addContract_d($object , $useCarDateLimit = '')
    {
        try {
            $this->start_d();

            $registerDao = new model_outsourcing_vehicle_register();
            $rs = $registerDao->addContract_d($object['register']); //租车登记表录入合同信息

            //汇总表录入相关费用
            if ($rs) {
                $rentalCarCost = 0; //租车费
                $reimbursedFuel = 0; //实报实销费用
                $gasolineKMCost = 0; //按公里计价油费
                $registerDao->searchArr['allregisterId'] = $object['id'];
                if($useCarDateLimit != ''){
                    $registerDao->searchArr['useCarDateLimit'] = $useCarDateLimit;
                }
                $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
                $registerRows = $registerDao->listBySqlId('select_Month');

                if (is_array($registerRows)) {
                    foreach ($registerRows as $key => $val) {
                        // 按计费方式带出相应的租车费用
                        $rentalCarCostVal = 0;
//                        if(!empty($val['rentalContractId']) && $val['rentalContractId'] > 0){
//                            $rentalCarCostVal = $registerDao->getRentalCarCost($val['rentalContractId'],$val['allregisterId'],$val['carNum']);
//                        }else{
//                            $rentalCarCostVal = $val['rentalCarCost'];
//                        }
                        $registerDao->groupBy = null;
                        $rentalCarCostVal = $registerDao->getRentalCarCostByRegisterId($val['allregisterId'],$val['carNum']);

                        $rentalCarCost += $rentalCarCostVal;
                        $reimbursedFuel += $val['reimbursedFuel'];
                        $gasolineKMCost += $val['gasolineKMCost'];
                    }
                }
                $obj = array("id" => $object['id']
                , "rentalCarCost" => $rentalCarCost
                , "reimbursedFuel" => $reimbursedFuel
                , "gasolineKMCost" => $gasolineKMCost
                , "useCarNum" => count($registerRows));
                $id = parent :: edit_d($obj, true);
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 变更后更新统计
     */
    function changeStatistics_d($oldObj, $obj)
    {
        $dateLimit = substr($oldObj['useCarDate'],0,7);
        $cost = $this->getRentalCarCostVal($oldObj['allregisterId'],$dateLimit);
        $rentalCarCost = ($cost > 0)? ",rentalCarCost = {$cost} " : "";

        try {
            $this->start_d();
            $sqlArr = "UPDATE oa_outsourcing_allregister SET "
                . " effectMileage = effectMileage + " . ($obj['effectMileage'] - $oldObj['effectMileage'])
                . ",parkingCost = parkingCost + " . ($obj['parkingCost'] - $oldObj['parkingCost'])
                . ",mealsCost = mealsCost + " . ($obj['mealsCost'] - $oldObj['mealsCost'])
                . ",accommodationCost = accommodationCost + " . ($obj['accommodationCost'] - $oldObj['accommodationCost'])
                . ",overtimePay = overtimePay + " . ($obj['overtimePay'] - $oldObj['overtimePay'])
                . ",specialGas = specialGas + " . ($obj['specialGas'] - $oldObj['specialGas'])
                . ",effectLogTime = effectLogTime + " . ($obj['effectLogTime'] - $oldObj['effectLogTime'])
                . $rentalCarCost
                . " WHERE "
                . "id = '" . $oldObj['allregisterId'] . "'";
            $this->query($sqlArr);
            $id = $oldObj['allregisterId'];

            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 审批完成发送邮件通知租车登记相关人员(不包含服务总监)
     */
    function sendAllregisterMail_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = $this->getMailReceiver_d($id);
        $emailDao = new model_common_mail();
        $mailContent = '您好！此邮件为租车登记审批通过通知，详细信息如下：<br>' .
            '项目编号：<span style="color:blue">' . $obj['projectCode'] .
            '</span><br>项目名称：<span style="color:blue">' . $obj['projectName'] .
            '</span><br>用车时间：<span style="color:blue">' . substr($obj['useCarDate'], 0, -3) .
            '</span><br>合同用车天数：<span style="color:blue">' . $obj['contractUseDay'] .
            '</span><br>实际用车天数：<span style="color:blue">' . $obj['actualUseDay'] .
            '</span><br>总费用：<span style="color:blue">' . number_format($obj['rentalCarCost'] + $obj['reimbursedFuel'] + $obj['gasolineKMCost'] + $obj['parkingCost'] + $obj['mealsCost'] + $obj['accommodationCost'], 2) .
            '</span><br>';

        $emailDao->mailGeneral("租车登记审批通过", $receiverId, $mailContent);
    }

    /**
     * 根据租车登记表id 获取租车登记相关人员(不包含服务总监)
     */
    function getMailReceiver_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = '';

        $receiverId .= $this->get_table_fields('oa_esm_project', "id=" . $obj['projectId'], 'managerId'); //项目经理

        include(WEB_TOR . "model/common/mailConfig.php");
        $mailNotify = $mailUser['oa_outsourcing_allregister'];
        $receiverId .= ',' . $mailNotify['TO_ID']; //租车登记-审批通过通知人

//		$esmprojectDao = new model_engineering_project_esmproject();
//		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
//		$receiverId.=','.$this->get_table_fields('oa_system_province_info', "id=".$esmprojectObj['provinceId'], 'esmManagerId');//服务经理
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
        $receiverId .= ',' . $esmprojectObj['areaManagerId']; //服务经理
        return $receiverId;
    }

    /**
     * 判断是否可以提交审批(不判断时间)并录入合同信息
     */
    function isCanSubmit_d($id , $limitUseCarDate = '')
    {
        try {
            $this->start_d();

            $flag = true; //返回标识

            $registerDao = new model_outsourcing_vehicle_register();
            $registerDao->searchArr['allregisterId'] = $id;
            $useCarDateLimit = '';
            if($limitUseCarDate != ''){
                $useCarDateLimit = $limitUseCarDate;
                $registerDao->searchArr['useCarDateLimit'] = $useCarDateLimit;
            }
            $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.createId ,c.carNum';
            $registerRows = $registerDao->listBySqlId('select_Month');

            $tmp = 0; //从表条数标志
            foreach ($registerRows as $key => $val) {
                if ($val['rentalContractId'] > 0 || $val['rentalPropertyCode'] == 'ZCXZ-02') {
                    $tmp++;
                }
            }
            if (count($registerRows) == $tmp) { //判断所有登记是否都有对应的合同信息
                $object['id'] = $id;
                $object['register'] = $registerRows;
                $this->addContract_d($object,$useCarDateLimit); //录入合同信息
            } else {
                $flag = false;
            }

            $this->commit_d();
            return $flag;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 打回
     */
    function back_d($obj)
    {
        try {
            $this->start_d();

            $registerDao = new model_outsourcing_vehicle_register();
            if (is_array($obj['register'])) {
                foreach ($obj['register'] as $key => $val) {
                    if ($val['back'] == 1) {
                        $registerObj = $registerDao->get_d($val['id']);
                        $sqlArr = "UPDATE oa_outsourcing_allregister SET "
                            . " effectMileage = effectMileage - " . ($registerObj['effectMileage'] > 0 ? $registerObj['effectMileage'] : 0)
                            . ",parkingCost = parkingCost - " . ($registerObj['parkingCost'] > 0 ? $registerObj['parkingCost'] : 0)
                            . ",tollCost = tollCost - " . ($registerObj['tollCost'] > 0 ? $registerObj['tollCost'] : 0)
                            . ",mealsCost = mealsCost - " . ($registerObj['mealsCost'] > 0 ? $registerObj['mealsCost'] : 0)
                            . ",accommodationCost = accommodationCost - " . ($registerObj['accommodationCost'] > 0 ? $registerObj['accommodationCost'] : 0)
                            . ",reimbursedFuel = reimbursedFuel - " . ($registerObj['reimbursedFuel'] > 0 ? $registerObj['reimbursedFuel'] : 0)
                            . ",overtimePay = overtimePay - " . ($registerObj['overtimePay'] > 0 ? $registerObj['overtimePay'] : 0)
                            . ",specialGas = specialGas - " . ($registerObj['specialGas'] > 0 ? $registerObj['specialGas'] : 0)
                            . ",effectLogTime = effectLogTime - " . ($registerObj['effectLogTime'] > 0 ? $registerObj['effectLogTime'] : 0)
                            . ",actualUseDay = actualUseDay - 1"
                            . " WHERE "
                            . " id = '" . $obj['id'] . "'";
                        $this->query($sqlArr);

                        //修改租车登记
                        $this->query("UPDATE oa_outsourcing_register SET "
                            . " state = 2 "
                            . " ,allregisterId = null "
                            . " WHERE "
                            . " id = '" . $val['id'] . "'"
                        );
                    }
                }
            }

            if (!$this->isHaveEqu_d($obj['id'])) { //判断是否有租车登记
                $this->deleteByPk($obj['id']); //删除汇总的登记

                // 把关联的费用项跟扣款信息也一起删了
                $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
                $deductinfoDao = new model_outsourcing_vehicle_deductinfo();

                $deductinfoDao->delete(array("allregisterId"=>$obj['id']));
                $expensetmpDao->delete(array("allregisterId"=>$obj['id']));
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据id判断是否有从表记录
     */
    function isHaveEqu_d($id)
    {
        $registerDao = new model_outsourcing_vehicle_register();
        $num = $registerDao->findCount(array('allregisterId' => $id, 'state' => 1));
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据租车登记id更新付款金额
     * @param $id 租车汇总表id
     * @param $money 付款金额
     * @param $type 费用类型(0:费用报销;1:付款申请)
     */
    function updateMoney_d($id, $money, $type)
    {
        try {
            $this->start_d();

            if ($type == 0) {
                $filed = 'expenseMoney'; //费用报销金额
            } else if ($type == 1) {
                $filed = 'paymentMoney'; //付款申请金额
            }

            if ($filed) {
                $sql = "UPDATE $this->tbl_name SET $filed = $filed + ($money) WHERE id = '$id' ";
                $this->query($sql);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据汇总表id判断费用是否超出项目预算，如果超出预算，则抛出异常
     */
    function checkBudgetById_d($id)
    {
        $obj = $this->get_d($id);
        $allcost = $obj['rentalCarCost'] > 0 ? $obj['rentalCarCost'] : 0
        + $obj['reimbursedFuel'] > 0 ? $obj['reimbursedFuel'] : 0
        + $obj['gasolineKMCost'] > 0 ? $obj['gasolineKMCost'] : 0
        + $obj['parkingCost'] > 0 ? $obj['parkingCost'] : 0
        + $obj['mealsCost'] > 0 ? $obj['mealsCost'] : 0
        + $obj['accommodationCost'] > 0 ? $obj['accommodationCost'] : 0
        + $obj['overtimePay'] > 0 ? $obj['overtimePay'] : 0
        + $obj['specialGas'] > 0 ? $obj['specialGas'] : 0;

        // 获取项目预算
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $budget = $rentalcarDao->getBudgetByProId_d($obj['projectId']);

        if ($allcost > $budget) {
            return true;
        }

        return false;
    }

    /**
     * 生成新报销单
     * @param $expenseData
     * @param $allregisterData
     */
    function createCostExpense($expenseData,$allregisterData){
        $userDao = new model_deptuser_user_user();
        $esmProjectDao = new model_engineering_project_esmproject();
        $expenseDao = new model_finance_expense_expense();
        $project = $esmProjectDao->get_d($allregisterData['projectId']);
        if(is_array($expenseData) && !empty($expenseData)){
            foreach ($expenseData as $k => $item){
                $costManName = $costManId = $costDeptName = $costDeptId = $costComName = $costComCode = "";
                $userInfo = $userDao->getUserById($item['createId']);
                $costManName = $item['createName'];$costManId = $item['createId'];
                $costDeptName = $userInfo['DEPT_NAME'];$costDeptId = $userInfo['DEPT_ID'];
                $costComName = $userInfo['CompanyName'];$costComCode = $userInfo['Company'];

                $newExpenseData = array(
                    "DetailType" => "2","CostDateBegin" => $item['useCarStartDate'],"CostDateEnd" => $item['useCarEndDate'],"days" => $item['useCarDays'],"Purpose" => "项目租车报销",
                    "CostManName" => $costManName,"CostMan" => $costManId,"CostDepartName" => $costDeptName,"CostDepartID" => $costDeptId,"CostManCom" => $costComName,"CostManComId" => $costComCode,"CostBelongCom" => $costComName,"CostBelongComId" => $costComCode,
                    "ProjectNo" => $project['projectCode'],"projectName" => $project['projectName'],"projectId" => $project['id'],"projectType" => "esm","proManagerName" => $project['managerName'],"proManagerId" => $project['managerId'],"CostBelongDeptName" => $project['deptName'],"CostBelongDeptId" => $project['deptId'],
                    "allregisterid" => $allregisterData['id'],"memberNames" => "","memberIds" => "","memberNumber" => "0","salesArea" => "","salesAreaId" => "",
                    "feeMan" => $item['createName'],"feeManId" => $item['createId'],"module" => $project['module'],
                    "expensedetail" => array(),
                    "expensecostshare" => array()
                );

                if($project['contractType'] == "GCXMYD-04"){
                    // PK项目的费用类型为“售前费用”
                    $newExpenseData['DetailType'] = '4';

                    // 补充PK项目的相关信息
                    $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                    $trialprojectObj = $trialprojectDao->get_d($project['contractId']);

                    $otherDatasDao = new model_common_otherdatas();// 查询用户所属部门
                    $userInfo = $otherDatasDao->getUserDatas($trialprojectObj['applyNameId']);
                    $trialprojectObj['deptId'] = $userInfo['DEPT_ID'];
                    $trialprojectObj['deptName'] = $userInfo['DEPT_NAME'];

                    if ($trialprojectObj['chanceCode'] != ''){
                        $newExpenseData['chanceCode'] = $trialprojectObj['chanceCode'];
                        $chanceDao = new model_projectmanagent_chance_chance();
                        $chanceInfo = $chanceDao->find(array("chanceCode" => $trialprojectObj['chanceCode']));
                        if($chanceInfo && !empty($chanceInfo)){
                            $newExpenseData['chanceId'] = $chanceInfo['id'];
                            $newExpenseData['chanceName'] = $chanceInfo['chanceName'];
                            $newExpenseData['customerId'] = $chanceInfo['customerId'];
                            $newExpenseData['customerName'] = $chanceInfo['customerName'];
                            $newExpenseData['province']  = $chanceInfo['Province'];
                            $newExpenseData['city']  = $chanceInfo['City'];
                            $newExpenseData['CustomerType']  = $chanceInfo['customerTypeName'];
                            $newExpenseData['CostBelonger']  = $chanceInfo['prinvipalName'];
                            $newExpenseData['CostBelongerId']  = $chanceInfo['prinvipalId'];
                        }
                    }else{
                        $newExpenseData['chanceId'] = $newExpenseData['chanceCode'] = $newExpenseData['chanceName'] = '';
                        $newExpenseData['customerName']  = $trialprojectObj['customerName'];
                        $newExpenseData['customerId']  = $trialprojectObj['customerId'];
                        $newExpenseData['province']  = $trialprojectObj['province'];
                        $newExpenseData['city']  = $trialprojectObj['city'];
                        $newExpenseData['CustomerType']  = $trialprojectObj['customerTypeName'];
                        $newExpenseData['CostBelonger']  = $trialprojectObj['applyName'];
                        $newExpenseData['CostBelongerId']  = $trialprojectObj['applyNameId'];
                    }
                }

                if(isset($item['detail']) && is_array($item['detail'])){
                    $invoiceNum = 0;
                    foreach ($item['detail'] as $ditem){
                        $arr["MainType"] = $ditem['costBigType'];
                        $arr["MainTypeId"] = $ditem['costBigTypeCode'];
                        $arr["CostType"] = $ditem['costType'];
                        $arr["CostTypeID"] = $ditem['costTypeCode'];
                        $arr['CostMoney'] = $ditem['costMoney'];
                        $arr['days'] = "1";
                        $arr["specialApplyNo"] = "";
                        $arr["Remark"] = "项目租车报销";
                        $arr["expenseinv"] = util_jsonUtil::decode($ditem['invoiceDataJson']);
                        foreach ($arr["expenseinv"] as $inv){
                            $invoiceNum += $inv['invoiceNumber'];
                        }
                        $newExpenseData['expensedetail'][] = $arr;
                        $arrShare['MainType'] = $arr["MainType"];
                        $arrShare['MainTypeId'] = $arr["MainTypeId"];
                        $arrShare['CostType'] = $arr["CostType"];
                        $arrShare['CostTypeID'] = $arr["CostTypeID"];
                        $arrShare['Remark'] = "";
                        $arrShare['expenseinv'] = array(array(
                            "CostMoney" => $arr['CostMoney'],
                            "module" => "HTBK-DX"
                        ));
                        $newExpenseData['expensecostshare'][] = $arrShare;
                    }
                    $newExpenseData['Amount'] = $newExpenseData['feeRegular'] = $newExpenseData['invoiceMoney'] = $item['payMoney'];
                    $newExpenseData['feeSubsidy'] = "";
                    $newExpenseData['invoiceNumber'] = $invoiceNum;
                    $newExpenseData['thisAuditType'] = "audit";
                    $newExpenseData['needExpenseCheck'] = "0";
                    $newExpenseData['deptIsNeedProvince'] = "0";
                    $newExpenseData['InputManName'] = $item['createName'];
                    $newExpenseData['InputMan'] = $item['createId'];
                    $newExpenseData['InputDate'] = date("Y-m-d");
                }

                // 当报销金额大于0的时候才生成报销单
                if($newExpenseData['Amount'] > 0){
                    $rtObj = $expenseDao->add_d($newExpenseData,$costDeptId);
                    if($rtObj && isset($rtObj['id'])){
                        if(empty($rtObj['id'])){
                            echo"报销单创建失败,错误信息为:<br><pre>";
                            print_r($rtObj);exit();
                        }else{
                            $this->expenseAutoAfterAuditDeal($rtObj,$allregisterData['id'],$item['id']);
                        }
                    }else{
                        echo"报销单创建失败,错误信息为:<br><pre>";
                        print_r($rtObj);exit();
                    }
                }
            }
        }
    }

    function testBuildExpenseFlow($allRegisterid,$BillNo){
        $newExpense['BillNo'] = $BillNo;
        //获取报销单信息
        $expenseDao = new model_finance_expense_expense();
        $newExpenseData = $expenseDao->getByBillNo_d($newExpense['BillNo']);

        // 获取租车登记审批流里面的审批人数据
        $allRegisterAuditInfo = array();
        if($allRegisterid != ''){
            $getAllregisterAuditSql = "select s.Item as StepName,p.* from flow_step_partent p 
                left join wf_task w on p.Wf_task_ID = w.task
                left join flow_step s on s.ID = p.StepID
            where w.pid = {$allRegisterid} and w.code = 'oa_outsourcing_allregister'";
            $allRegisterAuditInfoArr = $this->_db->getArray($getAllregisterAuditSql);
            if($allRegisterAuditInfoArr){
                foreach ($allRegisterAuditInfoArr as $relt){
                    $arr['appName'] = $relt['StepName'];
                    $arr['appUser'] = $relt['User'];
                    $arr['createTime'] = $relt['START'];
                    $arr['appTime'] = $relt['Endtime'];
                    $arr['content'] = $relt['Content'];
                    $allRegisterAuditInfo[] = $arr;
                }
            }
        }

        // 处理报销单审批
        $importDao = new model_cost_stat_import();
        $expenseId = $newExpense['id'];
        $billNo = $newExpense['BillNo'];
        $AppUserI = $importDao->getAppUserI($newExpenseData['ProjectNo'], $newExpenseData['CostBelongComId'],$allRegisterAuditInfo);
        // echo "<pre>".$newExpenseData['id'];print_r($AppUserI);exit();
        $creatorId = $newExpenseData['CostMan'];// 审批流提交人去报销单的报销人
        $importDao->buildApprovalWorkflow($billNo, $AppUserI, $newExpenseData['ID'], "否",$creatorId);
    }

    /**
     * 自动处理报销单审批后流程,以及相关的租车登记信息
     * @param $newExpense
     * @param $allregisterid
     * @param $expenseTmpId
     */
    function expenseAutoAfterAuditDeal($newExpense,$allRegisterid,$expenseTmpId){
        //获取报销单信息
        $expenseDao = new model_finance_expense_expense();
        $newExpenseData = $expenseDao->getByBillNo_d($newExpense['BillNo']);
        $expenseDao->update(
            array("id"=> $newExpense['id']),
            array(
                "CostBelongComId" => $newExpenseData['CostManComId'],"CostBelongCom" => $newExpenseData['CostManCom'],
                "isEffected" => 1
            )
        );

        $today = date("Y-m-d");
        // 跟新关联的租车填报费用信息 (标示该临时记录已用于生成报销单)
        $updateExpenseTmpSql = "update oa_contract_rentcar_expensetmp set isConfirm = 1,expenseId = {$newExpense['id']},ExaStatus = '完成',ExaDT = '{$today}',updateId = '{$_SESSION['USER_ID']}',updateName = '{$_SESSION['USER_NAME']}',updateTime = Now() where id = {$expenseTmpId}";
        $this->_db->query($updateExpenseTmpSql);

        // 获取租车登记审批流里面的审批人数据
        $allRegisterAuditInfo = array();
        if($allRegisterid != ''){
            $getAllregisterAuditSql = "select s.Item as StepName,p.* from flow_step_partent p 
                left join wf_task w on p.Wf_task_ID = w.task
                left join flow_step s on s.ID = p.StepID
            where w.pid = {$allRegisterid} and w.code = 'oa_outsourcing_allregister'";
            $allRegisterAuditInfoArr = $this->_db->getArray($getAllregisterAuditSql);
            if($allRegisterAuditInfoArr){
                foreach ($allRegisterAuditInfoArr as $relt){
                    $arr['appName'] = $relt['StepName'];
                    $arr['appUser'] = $relt['User'];
                    $arr['createTime'] = $relt['START'];
                    $arr['appTime'] = $relt['Endtime'];
                    $arr['content'] = $relt['Content'];
                    $arr['type'] = 1;
                    $allRegisterAuditInfo[] = $arr;
                }
            }
        }

        // 根据报销金额补充相关的审批人
        $nextTypeNum = 0;
        $expenseAmount = isset($newExpenseData['Amount'])? bcmul($newExpenseData['Amount'],'1',3) : 0;
        if($expenseAmount < 10000){// 小于1万
            $allRegisterAuditInfo[] = array('appName' => '租车管理员','appUser' => 'xiaoxia.zhu','type' => 2);// 租车管理员
            $nextTypeNum = 3;
        }else if($expenseAmount >= 10000 && $expenseAmount < 50000){// 1万到5万
            $allRegisterAuditInfo[] = array('appName' => '租车管理员','appUser' => 'xiaoxia.zhu','type' => 2);// 租车管理员
            $allRegisterAuditInfo[] = array('appName' => '副总经理','appUser' => 'zhongliang.hu','type' => 3);// 副总经理
            $nextTypeNum = 4;
        }else{// 大于等于5万
            $allRegisterAuditInfo[] = array('appName' => '租车管理员','appUser' => 'xiaoxia.zhu','type' => 2);// 租车管理员
            $allRegisterAuditInfo[] = array('appName' => '副总经理','appUser' => 'zhongliang.hu','type' => 3);// 副总经理
            $allRegisterAuditInfo[] = array('appName' => '总经理','appUser' => 'feng.guo','type' => 4);// 总经理
            $nextTypeNum = 5;
        }

        // 处理报销单审批
        $importDao = new model_cost_stat_import();
        $expenseId = $newExpense['id'];
        $billNo = $newExpense['BillNo'];
        $AppUserI = $importDao->getAppUserI($newExpenseData['ProjectNo'], $newExpenseData['CostBelongComId'],$allRegisterAuditInfo,$nextTypeNum);
        $creatorId = $newExpenseData['CostMan'];// 审批流提交人去报销单的报销人
        $importDao->buildApprovalWorkflow($billNo, $AppUserI, $newExpenseData['ID'], "否", $creatorId);
//        $updateExpenseSql = "update cost_summary_list set ExaStatus = '完成',ExaDT = now(),Status = '出纳付款',isEffected = 1 where id= {$newExpense['id']};";
//        $this->_db->query($updateExpenseSql);

        // 进行项目信息更新
//        if(isset($newExpenseData['projectId']) && $newExpenseData['projectId'] != ''){
//            $esmcostdetailStr = $expenseDao->getEsmCostDetail_d($newExpenseData['BillNo']);
//
//            // 将费用更新成已报销费用
//            if ($esmcostdetailStr) {
//                $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
//                $esmcostdetailDao->updateCost_d($esmcostdetailStr, '4');
//
//                $esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
//                $esmcostdetailInvDao->updateCostInvoice_d($esmcostdetailStr, '4');
//
//                //计算人员的项目费用
//                if ($newExpenseData['projectId']) {
//                    //获取当前项目的费用
//                    $projectCountArr = $esmcostdetailDao->getCostFormMember_d($newExpenseData['projectId'], $newExpenseData['CostMan']);
//
//                    //更新人员费用信息
//                    $esmmemberDao = new model_engineering_member_esmmember();
//                    $esmmemberDao->update(
//                        array('projectId' => $newExpenseData['projectId'], 'memberId' => $newExpenseData['CostMan']),
//                        $projectCountArr
//                    );
//                }
//            }
//        }

    }

    /**
     * 审批流回调方法
     */
    function workflowCallBack($spid)
    {
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        if ($folowInfo['examines'] == "ok") { //审批通过
            $this->sendAllregisterMail_d($folowInfo['objId']);

            // 生成新报销单
            $allregisterData = $this->get_d($folowInfo['objId']);
            $expenseTmpData = $expensetmpDao->getExpenseTmpRecords($folowInfo['objId']);
            $this->createCostExpense($expenseTmpData,$allregisterData);
        }
    }

    function chkPayInfoForNofeeCont($id){
        $registerDao = new model_outsourcing_vehicle_register();
        $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $sql = 'select DATE_FORMAT(useCarDate,"%Y-%m") as dateLimit from oa_outsourcing_allregister where id = '.$id;
        $result = $this->_db->get_one($sql);
        $chkParam = array(
            "allregisterId" => $id,
            "useCarDateLimit" => ($result)? $result['dateLimit'] : '',
        );
        $registerDao->getParam ( $chkParam );
        $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
        $rows = $registerDao->listBySqlId ( 'select_Month' );

        $hasError = false;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                if($val['rentalPropertyCode'] == "ZCXZ-02"){// 短租
                    $payInfosRecord = $expensetmpDao->findExpenseTmpRecord("",$id,$val['carNum'],"",0,0,'短租');
                    if(empty($payInfosRecord)){
                        $hasError = true;
                    }
                }else if($val['rentalContractNature'] != "款项合同"){// 长租且合同为非款项合同
                    // 获取租车合同关联的付款方式
                    $carNum = base64_encode($val['carNum']);
                    $payInfos = $rentCarPayInfoDao->findAll(" mainId = '{$val['rentalContractId']}' and (isDel is null or isDel <> 1)");
                    $payInfos1Record = $expensetmpDao->findExpenseTmpRecord("",$id,$carNum,$payInfos[0]['id'],0);
                    $payInfos2Record = $expensetmpDao->findExpenseTmpRecord("",$id,$carNum,$payInfos[1]['id'],0);
                    if((empty($payInfos1Record) && $payInfos[0]['id'] != '') || (empty($payInfos2Record) && $payInfos[1]['id'] != '')){
                        $hasError = true;
                        // echo "<pre>";print_r($val);
                    }
                }
            }
        }

        return $hasError;
    }
}