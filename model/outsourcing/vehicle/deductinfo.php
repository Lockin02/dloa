<?php

/**
 * @author huanghj
 * @Date 2018��3��23�� ������ 00:23:30
 * @version 1.0
 * @description:�⳵�Ǽǻ��ܿۿ���Ϣ Model��
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
     * ���ۿ���ϢӦ����֧����Ϣ
     */
    function chkDeductMoneyBelongTo(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $configuratorDao = new model_system_configurator_configurator();
    }
}