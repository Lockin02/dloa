<?php

/**
 * Class controller_engineering_baseinfo_esmcommon
 */
class controller_engineering_baseinfo_esmcommon extends controller_base_action
{

    public $_conProjectEarningsType = array();
    function __construct()
    {
        $this->objName = "esmcommon";
        $this->objPath = "engineering_baseinfo";
        parent:: __construct();
        $this->_conProjectEarningsType = array(
            "HSFS-FHJD" => "按发货进度",
            "HSFS-FPJD" => "按发票进度",
            "HSFS-XMJD" => "按工程项目进度",
            "HSFS-CPZL" => "按产品租赁期"
        );
    }

    /**
     * 工程配置
     */
    function c_toViewConfig()
    {
        $this->display('viewconfig');
        $this->service->toViewConfig_d();
    }


    /**
     * 更新项目 收入确认方式
     */
    function c_updateIncomeType()
    {
        if (is_numeric($_GET['id'])) { // 服务类
            $this->showDatadicts(array('type' => 'SRQRFS'), null, false);
            $this->assign('needRemark', 1);
        } else {
            $conProId = str_replace("c","",$_GET['id']);
            $conProDao = new model_contract_conproject_conproject();
            $conPro = $conProDao->get_d($conProId);

            $typeOpts = "";
            foreach ($this->_conProjectEarningsType as $k => $v){
                $typeOpts .= ($conPro['earningsTypeName'] == $v)? '<option value="'.$k.'" selected>'.$v.'</option>' : '<option value="'.$k.'">'.$v.'</option>';
            }
            $this->assign('type', $typeOpts);
            $this->assign('needRemark', 0);
        }

        $this->assign('id', $_GET['id']);
        $this->view('updateIncomeType');
    }

    /**
     * 更新确认方式
     */
    function c_updateIcom()
    {
        $data = $_REQUEST['esmIncome'];
        $id = $data['id'];
        if (is_numeric($id)) { // 服务类
            $datadictDao = new model_system_datadict_datadict();
            $name = $datadictDao->getDataNameByCode($data['type']);
            $sql = "update oa_esm_project set incomeType = '" . $data['type'] . "', incomeTypeName = '" . $name . "' where id = " . $id;
            $this->service->_db->query($sql);

            //记录操作日志
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($id, '更新收入确认方式', "收入确认方式：" . $name . "，备注：" . $data['remark']);
        } else {
            $id = substr($id, 1);
            $name = $this->_conProjectEarningsType[$data['type']];
            $sql = "update oa_contract_project set earningsTypeName= '" . $name . "',earningsTypeCode = '". $data['type'] ."' where id = " . $id;
            $this->service->_db->query($sql);
        }

        msg('操作完成！');
    }
}