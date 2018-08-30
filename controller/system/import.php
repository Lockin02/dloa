<?php
class controller_system_import extends model_system_import {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct() 	{
        parent::__construct();
        $this->tbl_name = '{table}';
        $this->pk = 'id';
        $this->show = new show();
    }

    function c_index(){
        $_SESSION['COM_BRN_PT'];
        $ckt=time();
        $this->show->assign('ckt', $ckt);
        $this->show->assign('flag', $this->flag);
        $this->show->assign('data_list', $this->model_data($ckt));
        $this->show->display('system_import');
    }
    function c_sub(){
        echo $this->model_sub();
    }
    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>