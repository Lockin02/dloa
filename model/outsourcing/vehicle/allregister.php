<?php

/**
 * @author Michael
 * @Date 2014��2��10�� ����һ 18:43:01
 * @version 1.0
 * @description:�⳵�Ǽǻ��� Model��
 */
class model_outsourcing_vehicle_allregister extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_allregister";
        $this->sql_map = "outsourcing/vehicle/allregisterSql.php";
        parent::__construct();
    }

    //��˾Ȩ�޴��� TODO
    // protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    /**
     * ����ͳ��
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
     * ���ݻ���ID�Լ��ó��·ݻ�ȡʵʱ���⳵����
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
                // ���Ʒѷ�ʽ������Ӧ���⳵����
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
     * ¼���ͬ��Ϣ
     */
    function addContract_d($object , $useCarDateLimit = '')
    {
        try {
            $this->start_d();

            $registerDao = new model_outsourcing_vehicle_register();
            $rs = $registerDao->addContract_d($object['register']); //�⳵�ǼǱ�¼���ͬ��Ϣ

            //���ܱ�¼����ط���
            if ($rs) {
                $rentalCarCost = 0; //�⳵��
                $reimbursedFuel = 0; //ʵ��ʵ������
                $gasolineKMCost = 0; //������Ƽ��ͷ�
                $registerDao->searchArr['allregisterId'] = $object['id'];
                if($useCarDateLimit != ''){
                    $registerDao->searchArr['useCarDateLimit'] = $useCarDateLimit;
                }
                $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
                $registerRows = $registerDao->listBySqlId('select_Month');

                if (is_array($registerRows)) {
                    foreach ($registerRows as $key => $val) {
                        // ���Ʒѷ�ʽ������Ӧ���⳵����
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
     * ��������ͳ��
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
     * ������ɷ����ʼ�֪ͨ�⳵�Ǽ������Ա(�����������ܼ�)
     */
    function sendAllregisterMail_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = $this->getMailReceiver_d($id);
        $emailDao = new model_common_mail();
        $mailContent = '���ã����ʼ�Ϊ�⳵�Ǽ�����ͨ��֪ͨ����ϸ��Ϣ���£�<br>' .
            '��Ŀ��ţ�<span style="color:blue">' . $obj['projectCode'] .
            '</span><br>��Ŀ���ƣ�<span style="color:blue">' . $obj['projectName'] .
            '</span><br>�ó�ʱ�䣺<span style="color:blue">' . substr($obj['useCarDate'], 0, -3) .
            '</span><br>��ͬ�ó�������<span style="color:blue">' . $obj['contractUseDay'] .
            '</span><br>ʵ���ó�������<span style="color:blue">' . $obj['actualUseDay'] .
            '</span><br>�ܷ��ã�<span style="color:blue">' . number_format($obj['rentalCarCost'] + $obj['reimbursedFuel'] + $obj['gasolineKMCost'] + $obj['parkingCost'] + $obj['mealsCost'] + $obj['accommodationCost'], 2) .
            '</span><br>';

        $emailDao->mailGeneral("�⳵�Ǽ�����ͨ��", $receiverId, $mailContent);
    }

    /**
     * �����⳵�ǼǱ�id ��ȡ�⳵�Ǽ������Ա(�����������ܼ�)
     */
    function getMailReceiver_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = '';

        $receiverId .= $this->get_table_fields('oa_esm_project', "id=" . $obj['projectId'], 'managerId'); //��Ŀ����

        include(WEB_TOR . "model/common/mailConfig.php");
        $mailNotify = $mailUser['oa_outsourcing_allregister'];
        $receiverId .= ',' . $mailNotify['TO_ID']; //�⳵�Ǽ�-����ͨ��֪ͨ��

//		$esmprojectDao = new model_engineering_project_esmproject();
//		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
//		$receiverId.=','.$this->get_table_fields('oa_system_province_info', "id=".$esmprojectObj['provinceId'], 'esmManagerId');//������
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
        $receiverId .= ',' . $esmprojectObj['areaManagerId']; //������
        return $receiverId;
    }

    /**
     * �ж��Ƿ�����ύ����(���ж�ʱ��)��¼���ͬ��Ϣ
     */
    function isCanSubmit_d($id , $limitUseCarDate = '')
    {
        try {
            $this->start_d();

            $flag = true; //���ر�ʶ

            $registerDao = new model_outsourcing_vehicle_register();
            $registerDao->searchArr['allregisterId'] = $id;
            $useCarDateLimit = '';
            if($limitUseCarDate != ''){
                $useCarDateLimit = $limitUseCarDate;
                $registerDao->searchArr['useCarDateLimit'] = $useCarDateLimit;
            }
            $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.createId ,c.carNum';
            $registerRows = $registerDao->listBySqlId('select_Month');

            $tmp = 0; //�ӱ�������־
            foreach ($registerRows as $key => $val) {
                if ($val['rentalContractId'] > 0 || $val['rentalPropertyCode'] == 'ZCXZ-02') {
                    $tmp++;
                }
            }
            if (count($registerRows) == $tmp) { //�ж����еǼ��Ƿ��ж�Ӧ�ĺ�ͬ��Ϣ
                $object['id'] = $id;
                $object['register'] = $registerRows;
                $this->addContract_d($object,$useCarDateLimit); //¼���ͬ��Ϣ
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
     * ���
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

                        //�޸��⳵�Ǽ�
                        $this->query("UPDATE oa_outsourcing_register SET "
                            . " state = 2 "
                            . " ,allregisterId = null "
                            . " WHERE "
                            . " id = '" . $val['id'] . "'"
                        );
                    }
                }
            }

            if (!$this->isHaveEqu_d($obj['id'])) { //�ж��Ƿ����⳵�Ǽ�
                $this->deleteByPk($obj['id']); //ɾ�����ܵĵǼ�

                // �ѹ����ķ�������ۿ���ϢҲһ��ɾ��
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
     * ����id�ж��Ƿ��дӱ��¼
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
     * �����⳵�Ǽ�id���¸�����
     * @param $id �⳵���ܱ�id
     * @param $money ������
     * @param $type ��������(0:���ñ���;1:��������)
     */
    function updateMoney_d($id, $money, $type)
    {
        try {
            $this->start_d();

            if ($type == 0) {
                $filed = 'expenseMoney'; //���ñ������
            } else if ($type == 1) {
                $filed = 'paymentMoney'; //����������
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
     * ���ݻ��ܱ�id�жϷ����Ƿ񳬳���ĿԤ�㣬�������Ԥ�㣬���׳��쳣
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

        // ��ȡ��ĿԤ��
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $budget = $rentalcarDao->getBudgetByProId_d($obj['projectId']);

        if ($allcost > $budget) {
            return true;
        }

        return false;
    }

    /**
     * �����±�����
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
                    "DetailType" => "2","CostDateBegin" => $item['useCarStartDate'],"CostDateEnd" => $item['useCarEndDate'],"days" => $item['useCarDays'],"Purpose" => "��Ŀ�⳵����",
                    "CostManName" => $costManName,"CostMan" => $costManId,"CostDepartName" => $costDeptName,"CostDepartID" => $costDeptId,"CostManCom" => $costComName,"CostManComId" => $costComCode,"CostBelongCom" => $costComName,"CostBelongComId" => $costComCode,
                    "ProjectNo" => $project['projectCode'],"projectName" => $project['projectName'],"projectId" => $project['id'],"projectType" => "esm","proManagerName" => $project['managerName'],"proManagerId" => $project['managerId'],"CostBelongDeptName" => $project['deptName'],"CostBelongDeptId" => $project['deptId'],
                    "allregisterid" => $allregisterData['id'],"memberNames" => "","memberIds" => "","memberNumber" => "0","salesArea" => "","salesAreaId" => "",
                    "feeMan" => $item['createName'],"feeManId" => $item['createId'],"module" => $project['module'],
                    "expensedetail" => array(),
                    "expensecostshare" => array()
                );

                if($project['contractType'] == "GCXMYD-04"){
                    // PK��Ŀ�ķ�������Ϊ����ǰ���á�
                    $newExpenseData['DetailType'] = '4';

                    // ����PK��Ŀ�������Ϣ
                    $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                    $trialprojectObj = $trialprojectDao->get_d($project['contractId']);

                    $otherDatasDao = new model_common_otherdatas();// ��ѯ�û���������
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
                        $arr["Remark"] = "��Ŀ�⳵����";
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

                // ������������0��ʱ������ɱ�����
                if($newExpenseData['Amount'] > 0){
                    $rtObj = $expenseDao->add_d($newExpenseData,$costDeptId);
                    if($rtObj && isset($rtObj['id'])){
                        if(empty($rtObj['id'])){
                            echo"����������ʧ��,������ϢΪ:<br><pre>";
                            print_r($rtObj);exit();
                        }else{
                            $this->expenseAutoAfterAuditDeal($rtObj,$allregisterData['id'],$item['id']);
                        }
                    }else{
                        echo"����������ʧ��,������ϢΪ:<br><pre>";
                        print_r($rtObj);exit();
                    }
                }
            }
        }
    }

    function testBuildExpenseFlow($allRegisterid,$BillNo){
        $newExpense['BillNo'] = $BillNo;
        //��ȡ��������Ϣ
        $expenseDao = new model_finance_expense_expense();
        $newExpenseData = $expenseDao->getByBillNo_d($newExpense['BillNo']);

        // ��ȡ�⳵�Ǽ����������������������
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

        // ������������
        $importDao = new model_cost_stat_import();
        $expenseId = $newExpense['id'];
        $billNo = $newExpense['BillNo'];
        $AppUserI = $importDao->getAppUserI($newExpenseData['ProjectNo'], $newExpenseData['CostBelongComId'],$allRegisterAuditInfo);
        // echo "<pre>".$newExpenseData['id'];print_r($AppUserI);exit();
        $creatorId = $newExpenseData['CostMan'];// �������ύ��ȥ�������ı�����
        $importDao->buildApprovalWorkflow($billNo, $AppUserI, $newExpenseData['ID'], "��",$creatorId);
    }

    /**
     * �Զ�������������������,�Լ���ص��⳵�Ǽ���Ϣ
     * @param $newExpense
     * @param $allregisterid
     * @param $expenseTmpId
     */
    function expenseAutoAfterAuditDeal($newExpense,$allRegisterid,$expenseTmpId){
        //��ȡ��������Ϣ
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
        // ���¹������⳵�������Ϣ (��ʾ����ʱ��¼���������ɱ�����)
        $updateExpenseTmpSql = "update oa_contract_rentcar_expensetmp set isConfirm = 1,expenseId = {$newExpense['id']},ExaStatus = '���',ExaDT = '{$today}',updateId = '{$_SESSION['USER_ID']}',updateName = '{$_SESSION['USER_NAME']}',updateTime = Now() where id = {$expenseTmpId}";
        $this->_db->query($updateExpenseTmpSql);

        // ��ȡ�⳵�Ǽ����������������������
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

        // ���ݱ���������ص�������
        $nextTypeNum = 0;
        $expenseAmount = isset($newExpenseData['Amount'])? bcmul($newExpenseData['Amount'],'1',3) : 0;
        if($expenseAmount < 10000){// С��1��
            $allRegisterAuditInfo[] = array('appName' => '�⳵����Ա','appUser' => 'xiaoxia.zhu','type' => 2);// �⳵����Ա
            $nextTypeNum = 3;
        }else if($expenseAmount >= 10000 && $expenseAmount < 50000){// 1��5��
            $allRegisterAuditInfo[] = array('appName' => '�⳵����Ա','appUser' => 'xiaoxia.zhu','type' => 2);// �⳵����Ա
            $allRegisterAuditInfo[] = array('appName' => '���ܾ���','appUser' => 'zhongliang.hu','type' => 3);// ���ܾ���
            $nextTypeNum = 4;
        }else{// ���ڵ���5��
            $allRegisterAuditInfo[] = array('appName' => '�⳵����Ա','appUser' => 'xiaoxia.zhu','type' => 2);// �⳵����Ա
            $allRegisterAuditInfo[] = array('appName' => '���ܾ���','appUser' => 'zhongliang.hu','type' => 3);// ���ܾ���
            $allRegisterAuditInfo[] = array('appName' => '�ܾ���','appUser' => 'feng.guo','type' => 4);// �ܾ���
            $nextTypeNum = 5;
        }

        // ������������
        $importDao = new model_cost_stat_import();
        $expenseId = $newExpense['id'];
        $billNo = $newExpense['BillNo'];
        $AppUserI = $importDao->getAppUserI($newExpenseData['ProjectNo'], $newExpenseData['CostBelongComId'],$allRegisterAuditInfo,$nextTypeNum);
        $creatorId = $newExpenseData['CostMan'];// �������ύ��ȥ�������ı�����
        $importDao->buildApprovalWorkflow($billNo, $AppUserI, $newExpenseData['ID'], "��", $creatorId);
//        $updateExpenseSql = "update cost_summary_list set ExaStatus = '���',ExaDT = now(),Status = '���ɸ���',isEffected = 1 where id= {$newExpense['id']};";
//        $this->_db->query($updateExpenseSql);

        // ������Ŀ��Ϣ����
//        if(isset($newExpenseData['projectId']) && $newExpenseData['projectId'] != ''){
//            $esmcostdetailStr = $expenseDao->getEsmCostDetail_d($newExpenseData['BillNo']);
//
//            // �����ø��³��ѱ�������
//            if ($esmcostdetailStr) {
//                $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
//                $esmcostdetailDao->updateCost_d($esmcostdetailStr, '4');
//
//                $esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
//                $esmcostdetailInvDao->updateCostInvoice_d($esmcostdetailStr, '4');
//
//                //������Ա����Ŀ����
//                if ($newExpenseData['projectId']) {
//                    //��ȡ��ǰ��Ŀ�ķ���
//                    $projectCountArr = $esmcostdetailDao->getCostFormMember_d($newExpenseData['projectId'], $newExpenseData['CostMan']);
//
//                    //������Ա������Ϣ
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
     * �������ص�����
     */
    function workflowCallBack($spid)
    {
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        if ($folowInfo['examines'] == "ok") { //����ͨ��
            $this->sendAllregisterMail_d($folowInfo['objId']);

            // �����±�����
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
                if($val['rentalPropertyCode'] == "ZCXZ-02"){// ����
                    $payInfosRecord = $expensetmpDao->findExpenseTmpRecord("",$id,$val['carNum'],"",0,0,'����');
                    if(empty($payInfosRecord)){
                        $hasError = true;
                    }
                }else if($val['rentalContractNature'] != "�����ͬ"){// �����Һ�ͬΪ�ǿ����ͬ
                    // ��ȡ�⳵��ͬ�����ĸ��ʽ
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