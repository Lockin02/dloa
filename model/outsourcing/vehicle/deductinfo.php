<?php

/**
 * @author huanghj
 * @Date 2018年3月23日 星期五 00:23:30
 * @version 1.0
 * @description:租车登记汇总扣款信息 Model层
 */
class model_outsourcing_vehicle_deductinfo extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_allregister_deductinfo";
        $this->sql_map = "outsourcing/vehicle/deductinfoSql.php";
        parent::__construct();
    }

    /**
     * 检查扣款信息应属的支付信息
     */
    function chkDeductMoneyBelongTo(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $configuratorDao = new model_system_configurator_configurator();
    }
}