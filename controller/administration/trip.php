<?php
class controller_administration_trip extends model_administration_trip {

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
    function c_stat(){
        $this->show->assign('form_url', 'index1.php?model=administration_trip&action=stat');
        $this->show->assign('excel_url', 'index1.php?model=administration_trip&action=stat_excel');
        $this->show->assign('seaname',$_REQUEST['seaname']);
        $this->show->assign('seadtb',$_REQUEST['seadtb']);
        $this->show->assign('seadte',$_REQUEST['seadte']);
        $this->show->assign('dept_list', $this->model_trip_dept());
        $this->show->assign('pro_list', $this->model_trip_pro());
        $this->show->assign('stat_list', $this->model_stat());
        $this->show->display('administration_trip');
    }
    function c_stat_excel(){
        $this->model_stat_excel();
    }
    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>