<?php
/**
 * @author huanghj
 * @Date 2018年3月23日 星期五 00:23:30
 * @version 1.0
 * @description:租车登记汇总扣款信息 控制层
 */

class controller_outsourcing_vehicle_deductinfo extends controller_base_action {

    function __construct() {
        $this->objName = "deductinfo";
        $this->objPath = "outsourcing_vehicle";
        parent::__construct ();
    }

    /**
     * 异步更新租车登记汇总的扣款信息
     */
    function c_ajaxUpdateDeductInfo(){
        $service = $this->service;
        $allregisterId = isset($_POST['allregisterId'])? $_POST['allregisterId'] : '';
        $useCarDate = isset($_POST['useCarDate'])? $_POST['useCarDate'] : '';
        $carNum = isset($_POST['carNum'])? $_POST['carNum'] : '';
        $deductMoney = isset($_POST['deductMoney'])? $_POST['deductMoney'] : '';
        $deductReason = isset($_POST['deductReason'])? $_POST['deductReason'] : '';
        $expensetmpId = isset($_POST['expensetmpId'])? $_POST['expensetmpId'] : '';
        $payInfoIds = isset($_POST['payInfoIds'])? $_POST['payInfoIds'] : '';
        $registerIds = isset($_POST['registerIds'])? $_POST['registerIds'] : '';
        $carNum = util_jsonUtil::iconvUTF2GB($carNum);

        $updateDataArr = array(
            'allregisterId' => $allregisterId,
            'carNum' => $carNum,
            'belongMonth' => $useCarDate,
            'deductMoney' => $deductMoney,
            'deductReason' =>  util_jsonUtil::iconvUTF2GB($deductReason),
            'registerIds' => $registerIds
        );

        // 如果存在付款信息ID,查询租车费所属的支付方式ID
        if($payInfoIds != ''){
            $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
            $payInfoArr = $rentCarPayInfoDao->find(" id in ($payInfoIds) and includeFeeTypeCode like '%rentalCarCost%' ");
            if($payInfoArr){
                $updateDataArr['payinfoId'] = $payInfoArr['id'];
            }
        }

        // 检查是否存在相应的扣款信息
        $conditionArr = array("allregisterId" => $allregisterId,"carNum" => $carNum);
        if($expensetmpId != ''){// 当已存在填报费用临时记录时
            $conditionArr['expensetmpId'] = $expensetmpId;
            $updateDataArr['expensetmpId'] = $expensetmpId;
        }
        $deductInfoArr = $service->find($conditionArr);

        // 如果存在则更新对应的信息
        if($deductInfoArr){
            // echo "<pre>";print_r($deductInfoArr);
            $updateDataArr['id'] = $deductInfoArr['id'];
            $service->updateById($updateDataArr);
            $id = $deductInfoArr['id'];
        }else{// 如果不存在则新增该汇总记录的扣款信息
            $id = $service->add_d($updateDataArr);
        }
        echo $id;
    }
}