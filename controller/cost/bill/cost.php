<?php
class controller_cost_bill_cost extends model_cost_bill_cost {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    function c_guide(){
        $ckt=$_SESSION['USER_ID'].time();
        $this->show->assign('remark-info', $_POST['remark']);
        if(empty ($_FILES["xfile"]["tmp_name"])){
            $this->show->assign('up-info', 'disabled');
        }else{
            $this->show->assign('up-info', '');
        }
        $this->show->assign('ckt', $ckt);
        $this->show->assign('pro-info', $this->xm());
        $this->show->assign('data-list', $this->model_guide($ckt));
        $this->show->display('cost_bill_guide');
    }

    function c_guide_sub(){
        echo $this->model_guide_sub($_POST['ckt']);
    }
    
    function c_list(){
        
    }

    function c_deal(){
        
    }
    function xm(){
        $pro=$_POST['pro'];
        $res='';
        $xm=$this->model_xm();
        if($xm){
            foreach($xm as $key=>$val){
                if($key==$pro){
                    $res.='<option value="'.$key.'" selected>'.$val.'</option>';
                }else{
                    $res.='<option value="'.$key.'">'.$val.'</option>';
                }
            }
        }
        return $res;
    }
    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>