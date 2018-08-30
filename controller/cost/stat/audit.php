<?php

class controller_cost_stat_audit extends model_cost_stat_audit {

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
    function c_index_1() {
        $this->show->assign('grid_url', '?model=cost_stat_audit&action=left_tree');
        $this->show->assign('first_tab', '总成本费用汇总表');
        $this->show->assign('first_ifr', '?model=cost_stat_audit&action=all_dept');
        $this->show->display('cost_stat_audit_index');
    }

    function c_left_tree(){
        $responce->rows[0]['id']='1';
        $responce->rows[0]['cell']=un_iconv(array('1','总成本费用汇总表','','1','','true','true'));
        $responce->rows[1]['id']='2';
        $responce->rows[1]['cell']=un_iconv(array('2','部门费用统计表','?model=cost_stat_audit&action=dept','1','','true','true'));
        $responce->rows[2]['id']='3';
        $responce->rows[2]['cell']=un_iconv(array('3','销售市场费用表','?model=cost_stat_audit&action=marche','1','','true','true'));
        $responce->rows[3]['id']='4';
        $responce->rows[3]['cell']=un_iconv(array('4','客户统计表','','1','','false','false'));
        $responce->rows[4]['id']='5';
        $responce->rows[4]['cell']=un_iconv(array('5','客户类型统计','?model=cost_stat_audit&action=customer_type','2','4','true','true'));
        $responce->rows[5]['id']='6';
        $responce->rows[5]['cell']=un_iconv(array('6','客户省份统计','?model=cost_stat_audit&action=customer_pro','2','4','true','true'));
        $responce->rows[6]['id']='7';
        $responce->rows[6]['cell']=un_iconv(array('7','客户统计','?model=cost_stat_audit&action=customer','2','4','true','true'));
        $responce->rows[7]['id']='8';
        $responce->rows[7]['cell']=un_iconv(array('8','工程项目统计表','?model=cost_stat_audit&action=project','1','','true','true'));
        echo json_encode($responce);
    }
/**
 * 总成本
 */
    function c_all_dept(){
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        $checkcom = isset($_REQUEST['seacom']) ? $_REQUEST['seacom'] : '-';
        $this->show->assign('url_list','?model=cost_stat_audit&action=all_dept');
        $this->show->assign('select_list',$this->select_list().'<input type="button" value="Excel导出" onclick="excelOut()" />');
        $this->show->assign('data_list', '?model=cost_stat_audit&action=all_dept_data&seay='.$seay.'&seacom='.$checkcom);
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=all_dept_excel&seay='.$seay.'&seacom='.$checkcom);
        $this->show->display('cost_stat_audit_all-dept');
    }
    
    function c_all_dept_data(){
        echo json_encode($this->model_all_dept());
    }
    function c_all_dept_excel(){
        $this->model_all_dept_excel();
    }
/**
 * 部门费用统计
 */
    function c_dept(){
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        $checkcom = isset($_REQUEST['seacom']) ? $_REQUEST['seacom'] : '-';
        $this->show->assign('url_list','?model=cost_stat_audit&action=dept');
        $this->show->assign('select_list',$this->select_list());
        $this->show->assign('data_list', $this->model_dept_data());
        $this->show->assign('detail_url', '?model=cost_stat_audit&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=dept_excel&seay='.$seay.'&seacom='.$checkcom);
        $this->show->assign('dept_other', '?model=cost_stat_audit&action=dept_other&seay='.$seay.'&seacom='.$checkcom);
        $this->show->display('cost_stat_audit_dept');
    }
    function select_list(){
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        $checkcom = isset($_REQUEST['seacom']) ? $_REQUEST['seacom'] : '-';
        $gl=new includes_class_global();
        $binfo=$gl->getBranchInfo();
        $res = '<select name="seacom" id="seacom" onchange="changeUrl()"><option value="-">全集团</option>';
        foreach($binfo as $key=>$val){
            if ($checkcom == $val['NamePT']) {
                $res.='<option value="' . $val['NamePT'] . '" selected >' . $val['NameCN'] . '</option>';
            } else {
                $res.='<option value="' . $val['NamePT'] . '" >' . $val['NameCN'] . '</option>';
            }
        }
        $res.='</select> 年份：<select id="seay" onchange="changeUrl()">';
        for($i=2010;$i<=date('Y');$i++){
            if($seay==$i){
                $res.='<option value="'.$i.'" selected>'.$i.'</option>';
            }else{
                $res.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $res.='</select>';
        return $res;
    }
    function c_dept_excel(){
         $this->model_dept_excel();
    }
    function c_dept_other(){
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        $this->show->assign('data_list', $this->model_dept_data_other());
        $this->show->assign('detail_url', '?model=cost_stat_audit&action=dept_detail_other&seay='.$seay);
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=dept_excel_other&seay='.$seay);
        $this->show->display('cost_stat_audit_dept-other');
    }
    function c_dept_excel_other(){
         $this->model_dept_excel_other();
    }
    function c_dept_detail(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_dept_detail_sea());
        $this->show->assign('data_list', $this->model_dept_detail());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=dept_detail_excel');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_dept_detail_excel(){
        $this->model_dept_detail_excel();
    }
    function c_dept_detail_other(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_dept_detail_sea());
        $this->show->assign('data_list', $this->model_dept_detail('other'));
        $this->show->assign('form_url', '?model=cost_stat_audit&action=dept_detail_other');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=dept_detail_excel_other');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_dept_detail_excel_other(){
        $this->model_dept_detail_excel('other');
    }
/**
 * 销售市场人员
 */
    function c_marche(){
        $this->show->assign('tab_w', '180%');
        $this->show->assign('seach_list', $this->model_spe_seach());
        $this->show->assign('data_list', $this->model_spe_user());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=marche');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=marche_excel');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_marche_excel(){
         $this->model_marche_excel();
    }
/**
 * 客户
 */
    function c_customer_type(){
        $this->show->assign('tab_w', '650');
        $this->show->assign('seach_list', $this->model_spe_seach());
        $this->show->assign('data_list', $this->model_customer_type());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=customer_type');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=type_excel');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_type_excel(){
        $this->model_type_excel();
    }
    
    function c_customer_pro(){
        $this->show->assign('tab_w', '650');
        $this->show->assign('seach_list', $this->model_spe_seach());
        $this->show->assign('data_list', $this->model_customer_pro());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=customer_pro');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=pro_excel');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_pro_excel(){
        $this->model_pro_excel();
    }
    
    function c_customer(){
        $this->show->assign('tab_w', '650');
        $this->show->assign('seach_list', $this->model_spe_seach());
        $this->show->assign('data_list', $this->model_customer());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=customer');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=customer_excel');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_customer_excel(){
        $this->model_customer_excel();
    }

    function c_project(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_pro_seach());
        $this->show->assign('data_list', $this->model_project());
        $this->show->assign('detail_url', '?model=cost_stat_audit&action=project_detail');
        $this->show->assign('form_url', '?model=cost_stat_audit&action=project');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=project_excel');
        $this->show->assign('excel_detail', '?model=cost_stat_audit&action=project_excel&flag=0');
        $this->show->display('cost_stat_audit_spe-user');
    }
    function c_project_excel(){
        $flag=$_GET['flag'];
        if($flag=='0'){
            $this->model_project_excel(false);
        }else{
            $this->model_project_excel();
        }
    }
    function c_project_detail(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_project_detail_sea());
        $this->show->assign('data_list', $this->model_project_detail());
        $this->show->assign('form_url', '?model=cost_stat_audit&action=project_detail');
        $this->show->assign('excel_out', '?model=cost_stat_audit&action=project_excel');
        $this->show->assign('excel_detail', '?model=cost_stat_audit&action=project_excel&flag=0');
        $this->show->display('cost_stat_audit_spe-user');
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() {

    }

}
?>