<?php
class controller_system_branch_util extends model_system_branch_util {

    public $show; // ģ����ʾ

    /**
     * ���캯��
     *
     */

    function __construct() {
        parent::__construct();
        $this->show = new show();
    }

    /**
     * Ĭ�Ϸ�����ʾ
     *
     */
    function index() {

    }

    //##############################################��ʾ����#################################################
    /**
     * ��ʾ�б�
     *
     */
    function c_list() {
        $this->show->assign('data_list', $this->model_showlist());
        $this->show->display('system_branch_list');
    }

    function c_edit(){
        $this->show->assign('data_list', $this->model_showedit());
        $this->show->display('system_branch_edit');
    }
    function c_edit_in(){
         echo json_encode($this->model_edit_in());
    }
    function c_new(){
        $this->show->assign('key_val', $_GET['key']);
        $this->show->assign('branch_list', $this->model_branch_list());
        $this->show->display('system_branch_new');
    }
    function c_new_in(){
        echo json_encode($this->model_new_in());
    }
    //##############################################����#################################################
    /**
     * ��������
     */
    function __destruct() {

    }

}
?>