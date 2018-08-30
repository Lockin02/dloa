<?php
class model_cost_loan_class extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $comsta;
    public $glo; //公用类库

    //*******************************构造函数***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->glo = new includes_class_global();
        $this->comsta = '完成';//财务录入
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
    }
    
    //*********************************数据处理********************************
    
    /**
     * 项目信息
     */
    function model_dealOff(){
    	$id=$_POST['id'];
    	$type= $_POST['type'];
    	
        $res=array();
        $sql=" update loan_list set no_writeoff = '".$type."' where id = '".$id."'  ";
        $query=$this->db->query($sql);
        
        return $query;
    }
    //*********************************析构函数************************************
    function __destruct(){

    }

}

?>