<?php
class controller_cost_loan_class extends model_cost_loan_class {

    public $show; // ģ����ʾ

    /**
     * ���캯��
     *
     */

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    
    function c_dealOff(){
    	echo json_encode($this->model_dealOff() );
    }
    //##############################################����#################################################
    /**
     * ��������
     */
    function __destruct() 	{

    }

}
?>