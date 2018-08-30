<?php
class controller_module_wf extends model_module_wf {

    public $show;

    function __construct()     {
        parent::__construct();
        $this->show = new show();
    }
    
    function c_add_exa_step(){
        echo $this->model_add_exa_step();
    }
    
    function c_build_wf(){
//        $task=array(
//            'pid'=>'4470',
//            'code'=>'oa_contract_contract',
//            'formname'=>'合同审批B',
//            'step'=>array(
//                0=>array(
//                    'userid'=>'guoquan.xie,admin'
//                    ,'item'=>'部门领导'
//                )
//                ,1=>array(
//                    'userid'=>'admin'
//                    ,'item'=>'部门总监'
//                )
//            )
//        );
        echo $this->build_wf($task);
    }

}
?>