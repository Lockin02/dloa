<?php

class controller_cost_stat_finance extends model_cost_stat_finance {

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
        
    }
/**
 * 部门费用统计
 */
    function c_dept(){
        $seadept=$_POST['seadept'];
        $seamonb=$_POST['seamonb'];
        $seamone=$_POST['seamone'];
        $seayear=$_POST['seayear']?$_POST['seayear']:date('Y');
        $seatype=$_POST['seatype'];
        $combrn=$_POST['combrn'];
        $seabilltype=$_POST['seabilltype'];
        $str.='类型：<select name="seatype" id="seatype">
                        <option value="">请选择类型</option>
                        <option value="all" '.($seatype=='all'?'selected':'').'>全部</option>
                        <option value="cost" '.($seatype=='cost'?'selected':'').'>报销类</option>
                        <option value="bill" '.($seatype=='bill'?'selected':'').'>非报销类</option>
                    </select>';
        $str.='部门：
            <input type="text" name="seadept" id="seadept" value="'.$seadept.'" />
            ';
        $str.='年份：<select name="seayear" id="seayear">
            ';
        for($i=2010;$i<=date('Y');$i++){
            if($i==$seayear){
                $str.='<option value="'.$i.'" selected>'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='
            </select>';
        $str.='
            月份：<select name="seamonb" id="seamonb" ><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamonb){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='</select> 至 <select name="seamone" id="seamone"><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamone){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
       $str.='</select>';
       
        $str.='科目类型：<select name="seabilltype" id="seabilltype"><option value="">非报销类科目</option>'.$this->model_bill_type($seabilltype).'</select>';
        $combrnI=array(array('NamePT'=>'','NameCN'=>'所有公司'),array('NamePT'=>'dl','NameCN'=>'世纪鼎利'),array('NamePT'=>'sy','NameCN'=>'世源信通'),array('NamePT'=>'br','NameCN'=>'广州贝软'),array('NamePT'=>'bx','NameCN'=>'广州贝讯'),array('NamePT'=>'dyfh','NameCN'=>'鼎元丰和'));
        foreach($combrnI as $key => $val){
        	if($val['NamePT']==$combrn){
                $combrnStr.='<option value="'.$val['NamePT'].'" selected >'.$val['NameCN'].'</option>';
            }else{
                $combrnStr.='<option value="'.$val['NamePT'].'">'.$val['NameCN'].'</option>';
            }
        }
        $str.='公司：<select name="combrn" id="combrn">'.$combrnStr.'</select>';
        $this->show->assign('sea_list', $str);
        $this->show->assign('data_list', $this->model_dept_data(''));
        $this->show->assign('detail_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_excel');
        $this->show->display('cost_stat_finance_dept');
    }
    
    function c_dept_fn(){
        $seadept=$_POST['seadept'];
        $seamonb=$_POST['seamonb'];
        $seamone=$_POST['seamone'];
        $seayear=$_POST['seayear']?$_POST['seayear']:date('Y');
        $seatype=$_POST['seatype'];
        $seabilltype=$_POST['seabilltype'];
        $str.='类型：<select name="seatype" id="seatype">
                        <option value="">请选择类型</option>
                        <option value="all" '.($seatype=='all'?'selected':'').'>全部</option>
                        <option value="cost" '.($seatype=='cost'?'selected':'').'>报销类</option>
                        <option value="bill" '.($seatype=='bill'?'selected':'').'>非报销类</option>
                    </select>';
        $str.='部门：
            <input type="text" name="seadept" id="seadept" value="'.$seadept.'" />
            ';
        $str.='年份：<select name="seayear" id="seayear">
            ';
        for($i=2010;$i<=date('Y');$i++){
            if($i==$seayear){
                $str.='<option value="'.$i.'" selected>'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='
            </select>';
        $str.='
            月份：<select name="seamonb" id="seamonb" ><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamonb){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='</select> 至 <select name="seamone" id="seamone"><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamone){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='科目类型：<select name="seabilltype" id="seabilltype"><option value="">非报销类科目</option>'.$this->model_bill_type($seabilltype).'</select>';
        $this->show->assign('sea_list', $str);
        $this->show->assign('data_list', $this->model_dept_fn_data(''));
        $this->show->assign('detail_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_excel');
        $this->show->display('cost_stat_finance_dept');
    }
    
    function c_dept_dp(){
        $seadept=$_POST['seadept'];
        $seamonb=$_POST['seamonb'];
        $seamone=$_POST['seamone'];
        $seayear=$_POST['seayear']?$_POST['seayear']:date('Y');
        $seatype=$_POST['seatype'];
        $seabilltype=$_POST['seabilltype'];
        $str.='类型：<select name="seatype" id="seatype">
                        <option value="">请选择类型</option>
                        <option value="all" '.($seatype=='all'?'selected':'').'>全部</option>
                        <option value="cost" '.($seatype=='cost'?'selected':'').'>报销类</option>
                        <option value="bill" '.($seatype=='bill'?'selected':'').'>非报销类</option>
                    </select>';
        $str.='部门：
            <input type="text" name="seadept" id="seadept" value="'.$seadept.'" />
            ';
        $str.='年份：<select name="seayear" id="seayear">
            ';
        for($i=2010;$i<=date('Y');$i++){
            if($i==$seayear){
                $str.='<option value="'.$i.'" selected>'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='
            </select>';
        $str.='
            月份：<select name="seamonb" id="seamonb" ><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamonb){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='</select> 至 <select name="seamone" id="seamone"><option value="">不限</option>';
        for($i=1;$i<=12;$i++){
            if($i==$seamone){
                $str.='<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                $str.='<option value="'.$i.'">'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='科目类型：<select name="seabilltype" id="seabilltype"><option value="">非报销类科目</option>'.$this->model_bill_type($seabilltype).'</select>';
        $this->show->assign('sea_list', '');
        $this->show->assign('data_list', $this->model_dept_dp_data(''));
        $this->show->assign('detail_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_excel');
        $this->show->display('cost_stat_finance_dept');
    }

    function c_dept_excel(){
         $this->model_dept_excel();
    }

    function c_dept_detail(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_dept_detail_sea());
        $this->show->assign('data_list', $this->model_dept_detail());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_detail_excel&combrn='.trim($_REQUEST['combrn']));
        $this->show->display('cost_stat_finance_spe-user');
    }
    
    function c_dept_detail_tmp(){
        $cktype=isset($_REQUEST['flag']) ? $_REQUEST['flag'] : '';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', '');
        $this->show->assign('data_list', $this->model_dept_detail_tmp());
        $this->show->assign('form_url', '#');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_detail_excel_tmp&flag='.$cktype);
        $this->show->display('cost_stat_finance_spe-user');
    }
    
    function c_dept_detail_excel_tmp(){
        $this->model_dept_detail_excel_tmp();
    }

    function c_dept_detail_dev(){
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $this->model_dept_detail_sea());
        $this->show->assign('data_list', $this->model_dept_detail_dev());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_detail_excel');
        $this->show->display('cost_stat_finance_spe-user');
    }


    function c_dept_detail_excel(){
        $this->model_dept_detail_excel();
    }
    /**
     *报销分世纪/鼎利统计数据
     */
    function c_cost_com(){
        $this->show->assign('seach_list', $this->model_dept_detail_sea());
        $this->show->assign('data_list', $this->model_cost_com_list());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=cost_com');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=cost_com_excel');
        $this->show->display('cost_stat_finance_com-cost');
    }
    function c_cost_com_excel(){
        $this->model_cost_com_excel();
    }
    /**
     * 借款超时
     */
    function c_loan_overtime(){
        $checkdept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $sealist.=' 部门 <input type="text" name="seadept" size="18" value="' . $checkdept . '" />';
        $sealist.=' 员工 <input type="text" name="seauser" size="18" value="' . $checkuser . '" />';
        //$navlist.='&nbsp;<input type="button" value="平均借款天数统计" onclick="loanavgClick();" />';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $sealist);
        $this->show->assign('nav_list' , $navlist);
        $this->show->assign('data_list', $this->model_loan_overtime());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=loan_overtime');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=loan_overtime_xls');
        $this->show->assign('avg_url', '?model=cost_stat_finance&action=loan_avg');
        $this->show->display('cost_stat_finance_grid');
    }

    function c_loan_overtime_xls(){
        $this->model_loan_overtime_xls();
    }

    function c_loan_avg(){
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y').'-01-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        $sealist.=' 部门 <input type="text" name="seadept" size="18" value="' . $seadept . '" />';
        $sealist.=' 报销人 <input type="text" name="seauser" size="18" value="' . $seauser . '" />';
        $sealist.=' 开始日期 
             <input type="text" name="seadtb"  class="Wdate" value="' . $seadtb . '" style="height:16px;width:100px;" onClick="WdatePicker()" />
             结束日期
             <input type="text" name="seadte"  class="Wdate" value="' . $seadte . '" style="height:16px;width:100px;" onClick="WdatePicker()" />';
        $navlist.='&nbsp;<input type="button" value="借款超时统计" onclick="loanavgClick();" />';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $sealist);
        $this->show->assign('nav_list' , $navlist);
        $this->show->assign('data_list', $this->model_loan_avg());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=loan_avg');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=loan_avg_xls');
        $this->show->assign('avg_url', '?model=cost_stat_finance&action=loan_overtime');
        $this->show->display('cost_stat_finance_grid');
    }

    function c_loan_avg_xls(){
        $this->model_loan_avg_xls();
    }

    function c_dept_other(){
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seacd = isset($_POST['seacd']) ? $_POST['seacd'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y-m').'-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        $sealist.=' 部门 <input type="text" name="seadept" size="18" value="' . $seadept . '" />';
        $sealist.=' 报销人 <input type="text" name="seauser" size="18" value="' . $seauser . '" />';
        $sealist.=' 归属部门 <input type="text" name="seacd" size="18" value="' . $seacd . '" />';
        $sealist.=' 开始日期
             <input type="text" name="seadtb"  class="Wdate" value="' . $seadtb . '" style="height:16px;width:100px;" onClick="WdatePicker()" />
             结束日期
             <input type="text" name="seadte"  class="Wdate" value="' . $seadte . '" style="height:16px;width:100px;" onClick="WdatePicker()" />';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $sealist);
        $this->show->assign('nav_list' , '');
        $this->show->assign('data_list', $this->model_dept_other());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=dept_other');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_other_xls');
        $this->show->display('cost_stat_finance_grid');
    }

    function c_dept_other_xls(){
        $this->model_dept_other_xls();
    }
    
    function c_dept_stat(){
        global $func_limit;
        $checkm = date('Y-m-d');
        $checkme = date('Y-m-d');
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $checkm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $checkme;
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $deprtid = isset($_POST['deprtid']) ? $_POST['deprtid'] : $_GET['deprtid'];
        $details = isset($_POST['details']) ? $_POST['details'] : $_GET['details'];
        $statuses=isset($_POST['statuses']) ? $_POST['statuses'] : $_GET['statuses'];
        $isProject=isset($_POST['isProject']) ? $_POST['isProject'] : $_GET['isProject'];
        $dateStatus=isset($_POST['dateStatus']) ? $_POST['dateStatus'] : $_GET['dateStatus'];
        //print_r($func_limit);
        if($func_limit['浏览部门']){
            $sead=$func_limit['浏览部门'].','.$_SESSION["DEPT_ID"];
        }else{
            $sead=$_SESSION["DEPT_ID"];
        }
        $dept = new includes_class_depart ();
		$deptI=(array)explode(',',$sead);
	    $res.='<select id="dateStatus" name="dateStatus">
								<option value="1" '.($dateStatus=='1'?'selected':'').'>按报销时间</option>
								<option value="2" '.($dateStatus=='2'?'selected':'').'>按支付时间</option>
						 </select>';
        $res .='<input type="text" name="seam" class="Wdate" style="width:100px;" readonly onClick="WdatePicker()"  value="'.$checkm.'" />';
        $res.=' 至:<input type="text" name="seame" class="Wdate" style="width:100px;" readonly onClick="WdatePicker()"  value="'.$checkme.'" />';
        $res.='审批状态: <select id="statuses" name="statuses">
								<option value="">请选择状态</option>
								<option value="完成" '.($statuses=='完成'?'selected':'').'>完成</option>
								<option value="财务审核" '.($statuses=='财务审核'?'selected':'').'>财务审核</option>
								<option value="部门审批" '.($statuses=='部门审批'?'selected':'').'>部门审批</option>
								<option value="部门检查" '.($statuses=='部门检查'?'selected':'').'>部门检查</option>
								<option value="编辑" '.($statuses=='编辑'?'selected':'').'>编辑</option>
								<option value="打回" '.($statuses=='打回'?'selected':'').'>打回</option>
							</select>';
        $res.=' 费用归属: <select id="deprtid" name="deprtid">
								<option value="">请选择部门</option>
								'.$dept->depart_select_limit ($deptI,$deprtid).'
							</select>';
        $res.=' 报销人: <input type="seabill" name="seauser" style="width:100px;" size="18" value="' . $checkuser . '" />';
        $res.=' 明细单<input type="checkbox" name="details" '.($details==1?'checked':'').' value="1" />';
        $_SESSION['COM_BRN_PT']=='bx'?$res.='项目费用<input type="checkbox" name="isProject" '.($isProject==1?'checked':'').' value="1" />':'';
        $res.=' <input type="hidden" name="sead"  value="'.$sead.'" /> ';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $res);
        $this->show->assign('data_list', $this->model_dept_stat());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=dept_stat');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_stat_excel');
        $this->show->display('cost_stat_finance_spe-user');
        
    }
    
    function c_costs(){
        global $func_limit;
        $checkm = date('Y-m-d');
        $checkme = date('Y-m-d');
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $checkm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $checkme;
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        //print_r($func_limit);
        if($func_limit['浏览部门']){
            $sead=$func_limit['浏览部门'].','.$_SESSION["DEPT_ID"];
        }else{
            $sead=$_SESSION["DEPT_ID"];
        }
        $res .='开始：<input type="text" name="seam" class="Wdate" readonly onClick="WdatePicker()"  value="'.$checkm.'" />';
        $res.='至：<input type="text" name="seame" class="Wdate" readonly onClick="WdatePicker()"  value="'.$checkme.'" />';
        $res.=' 报销人 <input type="seabill" name="seauser" size="18" value="' . $checkuser . '" />';
        $res.=' <input type="hidden" name="sead" value="'.$sead.'" /> ';
        $this->show->assign('tab_w', '100%');
        $this->show->assign('seach_list', $res);
        $this->show->assign('data_list', $this->model_dept_stat());
        $this->show->assign('form_url', '?model=cost_stat_finance&action=dept_stat');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_stat_excel');
        $this->show->display('cost_stat_finance_spe-user');
        
    }
    function c_dept_stat_excel(){
        $this->model_dept_stat_excel();
    }
    function c_account(){
    	set_time_limit(0);
    	@ini_set('memory_limit','200M');
        $seadtb=$_POST['seadtb']?$_POST['seadtb']:date('Y-m-d');
        $seadte=$_POST['seadte']?$_POST['seadte']:date('Y-m-d');
        $seatype=$_POST['seatype']?$_POST['seatype']:'cost';
        $seabilltype=$_POST['seabilltype'];
        $str.='类型：<select name="seatype" id="seatype">
                        <option value="">请选择类型</option>
                        <option value="cost" '.($seatype=='cost'?'selected':'').'>报销类</option>
                        <option value="bill" '.($seatype=='bill'?'selected':'').'>非报销类</option>
                    </select>';
        $str.='日期：<input type="text" class="Wdate" onclick="WdatePicker()" id="seadtb" name="seadtb" value="'.$seadtb.'" /> 
            至 <input type="text" class="Wdate" onclick="WdatePicker()" id="seadte" name="seadte" value="'.$seadte.'" /> ';
        $this->show->assign('sea_list', $str);
        $this->show->assign('data_list', $this->model_account());
        $this->show->assign('detail_url', '?model=cost_stat_finance&action=dept_detail');
        $this->show->assign('excel_out', '?model=cost_stat_finance&action=dept_excel');
        $this->show->display('cost_stat_finance_account');
    }
    
    function c_exAccount(){
    	set_time_limit(0);
    	@ini_set('memory_limit','200M');
 		$this->model_dept_account();
    }
    
     function c_account_detail(){
        $this->show->assign('data_list', $this->model_account_detail());
        $this->show->display('cost_stat_finance_account-detail');
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() {

    }

}
?>