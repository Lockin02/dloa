<?php
class controller_system_map extends model_system_map {

    public $show;

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    
    function c_index(){
        $this->show->assign('tree', $this->getMu());
        $this->show->display('system_map');
    }
}
?>