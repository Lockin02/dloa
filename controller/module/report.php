<?php
class controller_module_report extends model_module_report {

    public $show;

    function __construct()     {
        parent::__construct();
        $this->show = new show();
    }
    
    function c_set(){
        $this->show->display('module_report_set-list');
    }
    /*
     * 添加
     */
    function c_add(){
        $head=$this->model_get_head();
        if(!$head){
            $head['name']='';
            $head['cols']='1';
            $head['rows']='1';
            $head['tabstr']='';
            $head['sqlstr']='';
            $head['coltotals']='';
        }
        $this->show->assign('reportkey', $_REQUEST['reportkey']);
        $this->show->assign('name', $head['name']);
        $this->show->assign('cols', $head['cols']);
        $this->show->assign('rows', $head['rows']);
        $this->show->assign('coltotals', $head['coltotals']);
        $this->show->assign('tabstr', $head['tabstr']);
        $this->show->assign('sqlstr', $head['sqlstr']);
        $this->show->display('module_report_add');
    }
    
    function c_addsub(){
        echo $this->model_addsub();
    }
    
    function c_list(){
        $rep=$_REQUEST['rep'];
        $head=$this->model_get_head();
        $data=$this->model_get_data($head);
        if(empty($rep['scols'])){
            $rep['scols']=$head['cols']+1;
        }
        if(empty($rep['srows'])){
            $rep['srows']=$head['rows'];
        }
//        报错处理
        if($rep['srows']>$data['trows']-1){
           $rep['srows']=$data['trows']-1;
        }
        if($rep['scols']>$data['tabcols']){
            $rep['scols']=$data['tabcols']-1;
        }
//        var_dump ($data);
        $this->show->assign('listd', $data['list']);
        $this->show->assign('scols', $rep['scols']);
        $this->show->assign('srows', $rep['srows']);
        $this->show->assign('kid', $_REQUEST['reportkey']);
        $this->show->assign('seakey', $rep['seakey']);
        $this->show->display('module_report_list');
    }
    /**
     * 数据
     */
    function c_data(){
        echo json_encode($this->model_data());
    }
    
    function c_outExcel(){
        $head=$this->model_get_head();
        $data=$this->model_get_data($head,'xls');
    }
    
    function c_upExcel(){
        $res=$this->model_upExcel();
        
        $this->show->assign('up_url', 'index1.php?model=module_report&action=upExcelAjax&repkey='.$res['repkey']);
        $this->show->assign('updis', $res['updis']);
        $this->show->assign('listid', $res['listid']);
        $this->show->assign('wrdis', $res['wrdis']);
        $this->show->assign('repkey', $res['repkey']);
        $this->show->assign('uploadedFile', $res['uploadedFile']);
        $this->show->assign('error_list', $res['error_list']);
        $this->show->assign('dim_list', $res['dim_list']);
        $this->show->assign('up_list', $res['up_list']);
        $this->show->assign('wr_list', $res['wr_list']);
        
        $this->show->assign('repname', $res['repname']);
        
        $this->show->display('module_report_upexcel');
    }
    
    function c_upExcelAjax(){
        $res='';
        $rep_info=$this->model_upExcel();
        $res.='<input type="hidden" id="uploadedFile" value="'.urlencode($rep_info['uploadedFile']).'" />';
        $res.=$rep_info['up_list'];
        echo htmlspecialchars($res);
    }
    
    function c_upExcelSub(){
//        print_r($_REQUEST);
        echo json_encode($this->model_upExcelSub());
    }
    
    function c_writeRepFm(){
        $this->writeRepFm($_GET['repkey']);
    }
    
    function c_getRep(){
        $repkey=$_POST['repkey'];
        $dim=$_REQUEST['dim'];
        $res=$this->getRepData($repkey,$dim,'table_edit');
        echo un_iconv($res['rep_table']);
    }
    
    function c_delRep(){
        $repkey=$_POST['repkey'];
        $dim=$_REQUEST['dim'];
        $res=$this->delRepData($repkey,$dim);
        echo $res==1?un_iconv('数据已删除！'):un_iconv('数据删除失败！');
    }
    
    function c_excelRep(){
        $repkey=$_GET['repkey'];
        $dim=$_GET['dim'];
        $this->getRepData($repkey,$dim,'excel');
    }
    
    function c_saveTd(){
        $repkey=$_POST['repkey'];
        $dim=$_GET['dim'];
        $td=$_GET['td'];
        $repData=$this->get_rep($repkey);
        $res=$this->updateRepData($this->getRepList($repData['id'], $dim),$td);
        echo $res;
    }
}
?>