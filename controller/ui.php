<?php
class controller_ui extends model_ui {

    public $show;

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    
    function c_datagrid(){
        
        $this->show->display('ui_datagrid');
    }
    
}
?>