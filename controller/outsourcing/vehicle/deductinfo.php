<?php
/**
 * @author huanghj
 * @Date 2018��3��23�� ������ 00:23:30
 * @version 1.0
 * @description:�⳵�Ǽǻ��ܿۿ���Ϣ ���Ʋ�
 */

class controller_outsourcing_vehicle_deductinfo extends controller_base_action {

    function __construct() {
        $this->objName = "deductinfo";
        $this->objPath = "outsourcing_vehicle";
        parent::__construct ();
    }

    /**
     * �첽�����⳵�Ǽǻ��ܵĿۿ���Ϣ
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

        // ������ڸ�����ϢID,��ѯ�⳵��������֧����ʽID
        if($payInfoIds != ''){
            $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
            $payInfoArr = $rentCarPayInfoDao->find(" id in ($payInfoIds) and includeFeeTypeCode like '%rentalCarCost%' ");
            if($payInfoArr){
                $updateDataArr['payinfoId'] = $payInfoArr['id'];
            }
        }

        // ����Ƿ������Ӧ�Ŀۿ���Ϣ
        $conditionArr = array("allregisterId" => $allregisterId,"carNum" => $carNum);
        if($expensetmpId != ''){// ���Ѵ����������ʱ��¼ʱ
            $conditionArr['expensetmpId'] = $expensetmpId;
            $updateDataArr['expensetmpId'] = $expensetmpId;
        }
        $deductInfoArr = $service->find($conditionArr);

        // �����������¶�Ӧ����Ϣ
        if($deductInfoArr){
            // echo "<pre>";print_r($deductInfoArr);
            $updateDataArr['id'] = $deductInfoArr['id'];
            $service->updateById($updateDataArr);
            $id = $deductInfoArr['id'];
        }else{// ����������������û��ܼ�¼�Ŀۿ���Ϣ
            $id = $service->add_d($updateDataArr);
        }
        echo $id;
    }
}