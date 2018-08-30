<?php
class controller_cost_loan_class extends model_cost_loan_class {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    
    function c_dealOff(){
    	echo json_encode($this->model_dealOff() );
    }
    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>