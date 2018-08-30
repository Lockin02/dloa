<?php

/**
 * @author hj
 * @Date 2017年11月13日 16:19:33
 * @version 1.0
 * @description:借试用成本概算 Model层
 */
class model_projectmanagent_borrow_cost extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_borrow_cost";
        $this->sql_map = "projectmanagent/borrow/costSql.php";
        parent::__construct();
    }

    /**
     * 添加成本概算记录
     * @param $obj
     * @param array $confirmInfo
     * @param bool $needUpdateBorrow 是否需要更新借试用单
     */
    function addCostConfirm($obj,$confirmInfo = array(),$needUpdateBorrow = false){
        $costId = '';
        $thisCost = $this->find(array("borrowId" => $obj['id'],"linkId" => $obj['linkId']));
        if(empty($confirmInfo) || $confirmInfo == null){
            $confirmInfo['confirmName'] = $_SESSION['USERNAME'];
            $confirmInfo['confirmId'] = $_SESSION['USER_ID'];
            $confirmInfo['confirmDate'] = date("Y-m-d H:i:s");
        }

        // 如果存在则做更新处理
        if($thisCost && isset($thisCost['id'])){
            $this->updateById(array(
                "id"=>$thisCost['id'],
                "confirmName"=>$confirmInfo['confirmName'],
                "confirmId"=>$confirmInfo['confirmId'],
                "confirmDate"=>$confirmInfo['confirmDate'],
                "confirmMoney"=>$obj['equEstimate'],
                "confirmMoneyTax"=>$obj['equEstimateTax']
            ));
            $costId = $thisCost['id'];
        }else{// 否则新增记录
            $costId = $this->add_d(array(
                "borrowId"=>$obj['id'],
                "linkId"=>$obj['linkId'],
                "confirmName"=>$confirmInfo['confirmName'],
                "confirmId"=>$confirmInfo['confirmId'],
                "confirmDate"=>$confirmInfo['confirmDate'],
                "state" => ($obj['isTemp'] == "1")? "0" : "1",
                "isTemp" => (isset($obj['isTemp']))? $obj['isTemp'] : "0",
                "ExaStatus" => ($obj['isTemp'] == "1")? "" : "完成",
                "confirmMoney"=>$obj['equEstimate'],
                "confirmMoneyTax"=>$obj['equEstimateTax']
            ));
        }

        // 如果需要更新原单的概算值则做一下处理
        if($needUpdateBorrow){
            $borrowDao = new model_projectmanagent_borrow_borrow();
            $borrowDao->updateById(array(
                "id"=>$obj['id'],
                "equEstimate"=>$obj['equEstimate'],
                "equEstimateTax"=>$obj['equEstimateTax']
            ));
        }
        echo $costId;
        return $costId;
    }
}