<?php

class controller_clerical_mc extends model_clerical_mc {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct() {
        parent::__construct();
        $this->show = new show();
    }
    /**
     * 默认访问显示
     *
     */
    function c_index() {
        $info=$this->model_info();
        $this->show->assign('user_info', $info['userinfo']);
        $this->show->assign('user_name', $info['username']);
        $this->show->assign('to_name', $info['toname']);
        $this->show->assign('to_tel', $info['totel']);
        $this->show->assign('to_add', $info['toadd']);
        $this->show->assign('to_postcode', $info['topostcode']);
        $this->show->assign('to_remark', $info['toremark']);
        $this->show->assign('pid', $info['pid']);
        $this->show->assign('ajax_url', '?model=clerical_mc&action=submit');
        $this->show->assign('ajax_del_url', '?model=clerical_mc&action=delete');
        $this->show->assign('admin', $info['admin']);
        //显示控制
        $this->show->assign('dipcom', $info['dipcom']);
        $this->show->assign('dippost', $info['dippost']);
        $this->show->assign('diptoname', $info['diptoname']);
        //控制选择
        $this->show->assign('totype1', $info['totype1']);
        $this->show->assign('totype2', $info['totype2']);
        $this->show->assign('totype3', $info['totype3']);
		 $this->show->assign('totype4', $info['totype4']);
        
        $this->show->assign('toarea1', $info['toarea1']);
        $this->show->assign('toarea2', $info['toarea2']);
        $this->show->assign('toarea3', $info['toarea3']);
        $this->show->assign('toarea4', $info['toarea4']);
        $this->show->assign('toarea5', $info['toarea5']);
        $this->show->assign('toarea6', $info['toarea6']);
        $this->show->assign('toarea7', $info['toarea7']);
        $this->show->assign('toarea8', $info['toarea8']);
        $this->show->assign('toarea9', $info['toarea9']);
        $this->show->assign('toarea10', $info['toarea10']);
        $this->show->assign('toarea11', $info['toarea11']);
        $this->show->assign('toarea12', $info['toarea12']);
        
        $this->show->assign('tonamet1', $info['tonamet1']);
        $this->show->assign('tonamet2', $info['tonamet2']);
        
        $this->show->display('clerical_mc');
    }
	
	 /**
     * 默认访问显示
     *
     */
    function c_customer() {
        $info=$this->model_customer();
        $this->show->assign('info', $info);
        $this->show->display('clerical_customer');
    }
	
    function c_addCustomer(){
        echo $this->model_addCustomer();
		/*
		if($flag==1){
		   showmsg ( '提交成功！', 'self.parent.tb_remove();', 'button' );
		}else{
		   showmsg ( '提交失败！', 'opener.location.reload();parent.window.close()', 'button' );
		}*/
    }
	function c_delCustomer(){
		echo $this->model_delCustomer();
		
	}
	function c_submit(){
        $this->model_sumbit();
    }
    function c_delete(){
        $this->model_delete();
    }
    function c_stat(){
        $this->show->assign('excel_out', '?model=clerical_mc&action=stat_excel');
        $this->show->assign('form_url', '?model=clerical_mc&action=stat');
        $this->show->assign('seach_list', $this->model_stat_sea());
        $this->show->assign('data_list', $this->model_stat_list());
        $this->show->display('clerical_stat');
    }
    function c_stat_excel(){
        $this->model_stat_excel();
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() {

    }

}
?>