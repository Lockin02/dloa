<?php
class controller_cost_bill_billcheck extends model_cost_bill_billcheck {

    public $show; // ģ����ʾ

    /**
     * ���캯��
     *
     */

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    function c_index(){
        $str.=' �������ţ�<input type="text" name="seabn" id="seabn" value="" style="width:100px;"/>';
        $str.=' ��Ʊ���ţ�<input type="text" name="seacn" id="seacn" value="" style="width:70px;"/>';
        $str.=' �����ˣ�<input type="text" name="seaman" id="seaman" value="" style="width:60px;"/>';
        $str.=' ������Ŀ��<input type="text" name="seapro" id="seapro" value="" style="width:60px;"/> ';
        $str.='<input type="button" value=" �� ѯ " onClick="gridNavSeaFun()"/>';
        $this->show->assign('user_capt', $str);
        $this->show->assign('new_url', '?model=cost_bill_billcheck&action=new&placeValuesBefore&TB_iframe=true&modal=false&width=1000&height=820');
        $this->show->assign('deal_url', '&model=cost_bill_billcheck&action=deal&placeValuesBefore&TB_iframe=true&modal=false&width=1600&height=1000');
        $this->show->assign('data_list', '?model=cost_bill_billcheck&action=list');
        $this->show->assign('user_list', '?model=cost_bill_billcheck&action=list');
        $this->show->display('cost_bill_bill-list');
    }

    function c_list(){
        echo $this->model_list();
    }

    function c_deal(){
        $this->show->assign('key', $_GET['key']);
        $this->show->assign('type-info', $this->set_html($this->model_bill_type(), 'select'));
        $this->show->assign('data-detail', $this->model_bill_detail());
        $this->show->display('cost_bill_deal-ck');
    }

    function c_detail_info(){
        echo $this->model_detail_info();
    }
    function c_detail_change(){
        echo $this->model_detail_change();
    }

    /**
     * ��¼��ҳ���޸ļ�¼
     */
    function c_billCheckChangeRecord(){
        $billNo = isset($_POST['billNo'])? $_POST['billNo'] : '';
        $changeField = isset($_POST['changeField'])? $_POST['changeField'] : '';
        $newVal = isset($_POST['newVal'])? util_jsonUtil::iconvUTF2GB($_POST['newVal']) : '';
        $oldVal = isset($_POST['oldVal'])? util_jsonUtil::iconvUTF2GB($_POST['oldVal']) : '';
        $result = $this->updateChangeBillInfoRecord($billNo,$changeField,$newVal,$oldVal);
        echo $result;
    }

    function c_print_bill(){
        $res=$this->model_print_info();

        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('finance_expense_expense', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $editable = (isset($sysLimit['��ӡС��ҳ��༭Ȩ��']) && $sysLimit['��ӡС��ҳ��༭Ȩ��'] == 1)? "1" : "0";
        $this->show->assign('editable', $editable);

        if($res['tb']=='err'){
            $this->show->display('cost_bill_print-bill-err');
        }else{
            $this->show->assign('detail_type', $res['ty']);
            $this->show->assign('billno', $_GET['billno']);
            $this->show->assign('date_info', date("Y��m��d��"));
            $this->show->assign('acc_info',$res['ac']);
            $this->show->assign('print_info',$res['tb']);
            $this->show->assign('appName',$res['appName']);
            $this->show->display('cost_bill_print-bill');
        }
    }
    function c_detail_del(){
        echo $this->model_detail_del();
    }
    function c_payee_ch(){
        echo $this->model_payee_ch();
    }
    //##############################################����#################################################
    /**
     * ��������
     */
    function __destruct() 	{

    }

}
?>