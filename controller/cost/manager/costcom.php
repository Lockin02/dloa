<?php
class controller_cost_manager_costcom extends model_cost_manager_costcom {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct() 	{
        parent::__construct();
        $this->show = new show();
    }
    function c_index(){
        $str.=' 单号：<input type="text" name="seabn" id="seabn" value="" style="width:100px;"/>';
        $str.=' 流水号：<input type="text" name="seasn" id="seasn" value="" style="width:70px;"/>';
        $str.=' 制单人：<input type="text" name="seaman" id="seaman" value="" style="width:60px;"/>';
        $str.=' 金额：<input type="text" name="seaam" id="seaam" value="" style="width:60px;"/> ';
        $str.=' 费用类型：<select name="seact" id="seact"><option value="">全选</option>'.$this->model_cost_type('cost','select').'</select>';
        $str.=' 科目类型：<select name="seabt" id="seabt"><option value="">全选</option>'.$this->model_cost_type('bill','select').'</select>';
        $str.='<input type="button" value=" 查 询 " onClick="gridNavSeaFun()"/>';
        $str.='<input type="button" value="新建单据" onClick="newClickFun()"/>';
        $this->show->assign('user_capt', $str);
        $this->show->assign('new_url', '?model=cost_manager_costcom&action=new&placeValuesBefore&TB_iframe=true&modal=false&width=1000&height=820');
        $this->show->assign('edit_url', '&model=cost_manager_costcom&action=edit&placeValuesBefore&TB_iframe=true&modal=false&width=1000&height=820');
        $this->show->assign('data_list', '?model=cost_manager_costcom&action=list');
        $this->show->assign('user_list', '?model=cost_manager_costcom&action=list');
        $this->show->display('cost_manager_costcom-list');
    }
    function c_list(){
        echo $this->model_list();
    }
    function c_new(){
        $depart = new includes_class_depart();
		$this->show->assign('depart_select',$depart->depart_select());
        $this->show->assign('inputdt_list',date('Y-m-d'));
        $this->show->assign('paydt_list',date('Y-m-d'));
        $this->show->assign('tallydt_list',date('Y-m').'-'.date('t'));
        $this->show->display('cost_manager_costcom-new');
    }
    function c_edit(){
        $depart = new includes_class_depart();
        $this->getEntity($_GET['key']);
        foreach($this->entity as $key=>$val){
            $this->show->assign($key,$val);
        }
        $acclist='';
        if(!empty($this->entity['accdept'])){
            $i=0;
            foreach($this->entity['accdept'] as $key=>$val ){
                $acclist.='<tr><td rowspan="2">
                <select name="accdept['.$i.']" id="accdept_'.$i.'" class="accdept">'.$depart->depart_select($val).'</select>
                </td><td rowspan="2"><input id="" name="accamount['.$i.']" id="" class="accamount" value="'.$this->entity['accamount'][$key].'" style="width:80px;" />
                </td><td rowspan="2">
                    <input type="text" id="costtype_bill'.$i.'" onclick="costTypeClick('.$i.')" readonly value="'.$this->entity['costtypename'][$key].'" style="width: 120px;"></input>
                    <input type="hidden" id="costtypeid_bill'.$i.'" name="costtypeid['.$i.']" value="'.$this->entity['costtypeid'][$key].'"></input>
                </td>
                <td rowspan="2">
                    <input type="text" id="billtype_bill'.$i.'" onclick="billTypeClick('.$i.')" readonly value="'.$this->entity['billtypename'][$key].'" style="width: 120px;"></input>
                    <input type="hidden" id="billtypeid_bill'.$i.'" name="billtypeid['.$i.']" value="'.$this->entity['billtypeid'][$key].'"></input>
                </td><td>项目信息
                </td><td><input id="" name="accnamep['.$i.']" id="" class="accname" value="'.$this->entity['accnamep'][$key].'" style="width:160px;" />
                </td><td colspan="2"><input id="" name="accnop['.$i.']" id="" class="accno" value="'.$this->entity['accnop'][$key].'" style="width:160px;" />
                </td></tr>
                <tr><td>合同信息
                </td><td><input id="" name="accnamec['.$i.']" id="" class="accname" value="'.$this->entity['accnamec'][$key].'" style="width:160px;" />
                </td><td colspan="2"><input id="" name="accnoc['.$i.']" id="" class="accno" value="'.$this->entity['accnoc'][$key].'" style="width:160px;" />
                </td></tr>';
                $i++;
            }
        }
        $serialInfo='<option value="1" '.($this->entity['serialtype']=='1'?'selected':'').'>银</option>
                    <option value="2" '.($this->entity['serialtype']=='2'?'selected':'').'>现</option>
                    <option value="3" '.($this->entity['serialtype']=='3'?'selected':'').'>转</option>';
        
        $this->show->assign('acc_list',$acclist);
        $this->show->assign('acc_i',$i);
        $this->show->assign('key',$_GET['key']);
		$this->show->assign('depart_select',$depart->depart_select());
        $this->show->assign('serial_select',$serialInfo);
        $this->show->display('cost_manager_costcom-edit');
    }
    function c_edit_sub(){
        $this->model_edit_sub();
    }
    function c_new_sub(){
        $this->model_new_sub();
    }
    function c_del(){
        echo $this->model_del();
    }
    function c_payee(){
        $this->show->assign('seaid', $_GET['seaid']);
        $this->show->assign('resid', $_GET['resid']);
        $this->show->assign('data_list', '?model=cost_manager_costcom&action=payee_list&seaval='.$_GET['seaval']);
        $this->show->display('cost_manager_costcom-payee');
    }
    function c_user(){
        $this->show->assign('seaid', $_GET['seaid']);
        $this->show->assign('resid', $_GET['resid']);
        $this->show->assign('data_list', '?model=cost_manager_costcom&action=user_list&seaval='.$_GET['seaval']);
        $this->show->display('cost_manager_user-info');
    }
    function c_bank(){
        $this->show->assign('seaid', $_GET['seaid']);
        $this->show->assign('resid', $_GET['resid']);
        $this->show->assign('data_list', '?model=cost_manager_costcom&action=bank_list&seaval='.$_GET['seaval']);
        $this->show->display('cost_manager_costcom-bank');
    }
    function c_cost_type(){
        $this->show->assign('wh', '320');
        $this->show->assign('ww', '280');
        $this->show->assign('seaobj', $_GET['seaobj']);
        $this->show->assign('resobj', $_GET['resobj']);
        $this->show->assign('cost_list', $this->model_cost_type());
        $this->show->display('cost_manager_cost-type');
    }
    function c_bill_type(){
        $this->show->assign('wh', '260');
        $this->show->assign('ww', '260');
        $this->show->assign('seaobj', $_GET['seaobj']);
        $this->show->assign('resobj', $_GET['resobj']);
        $this->show->assign('cost_list', $this->model_cost_type('bill'));
        $this->show->display('cost_manager_cost-type');
    }
    function c_user_list(){
        echo $this->model_user_list();
    }
    function c_payee_list(){
        echo $this->model_payee_list();
    }
    function c_bank_list(){
        echo $this->model_bank_list();
    }

    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>