<?php
class controller_exet_attendance extends model_exet_attendance {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct() 	{
        parent::__construct();
        $this->show = new show();
    }

    /**
     * 默认访问显示
     *
     */
    function index() 	{

    }
    
    function c_monthsta(){
        $this->show->assign('form_url', '?model=exet_attendance&action=monthsta');
        $this->show->assign('sealist', $this->model_seachlist());
        $this->show->assign('stalist', $this->model_monthsta());
        $this->show->display('exet_attendance');
    }
    
    function c_deptsta(){
        $seapy = $_POST['seay']?$_POST['seay']:date('Y');
        $seapm = $_POST['seam']?$_POST['seam']:date('m');
        if($seapy==date('Y')&&$seapm==date('m')){
            $this->show->assign('remind_list', '如需修改本月请休假天数，请点击对应的合计日');
        }else{
            $this->show->assign('remind_list', '');
        }
        $this->show->assign('form_url', '?model=exet_attendance&action=deptsta');
        $this->show->assign('sealist', $this->model_dept_seachlist());
        $this->show->assign('stalist', $this->model_deptsta());
        $this->show->display('exet_atd-dept');
    }
    
    /**
     *检查请休假日期 
     */
    function c_ck_dt(){
         echo json_encode($this->model_ck_dt());
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>