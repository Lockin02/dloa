<?php
class controller_exet_attendance extends model_exet_attendance {

    public $show; // ģ����ʾ

    /**
     * ���캯��
     *
     */

    function __construct() 	{
        parent::__construct();
        $this->show = new show();
    }

    /**
     * Ĭ�Ϸ�����ʾ
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
            $this->show->assign('remind_list', '�����޸ı������ݼ�������������Ӧ�ĺϼ���');
        }else{
            $this->show->assign('remind_list', '');
        }
        $this->show->assign('form_url', '?model=exet_attendance&action=deptsta');
        $this->show->assign('sealist', $this->model_dept_seachlist());
        $this->show->assign('stalist', $this->model_deptsta());
        $this->show->display('exet_atd-dept');
    }
    
    /**
     *������ݼ����� 
     */
    function c_ck_dt(){
         echo json_encode($this->model_ck_dt());
    }
    //##############################################����#################################################

    /**
     * ��������
     */
    function __destruct() 	{

    }

}
?>