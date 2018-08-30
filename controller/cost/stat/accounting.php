<?php
class controller_cost_stat_accounting extends model_cost_stat_accounting {

	public $show; // 模板显示

	/**
	 * 构造函数
	 *
	 */

	function __construct() {
		parent :: __construct();
		$this->tbl_name = '{table}';
		$this->pk = 'id';
		$this->show = new show();
	}

	function c_pro() {
		$this->show->display('cost_stat_accounting_pro');
	}

	function c_pro_days() {
		$this->show->assign('excel_url', '?model=cost_stat_accounting&action=pro_days_excel');
		$this->show->assign('form_url', '?model=cost_stat_accounting&action=pro_days');
		$this->show->assign('sea_list', $this->sea_list());
		$this->show->assign('data_list', $this->model_pro_days());
		$this->show->display('cost_stat_accounting_days');
	}
	function c_pro_days_excel() {
		$this->model_pro_days_excel();
	}
	function sea_list() {
		$nowy = date('Y');
		$nowm = date('n');
		$checky = isset ($_POST['seay']) ? $_POST['seay'] : $nowy;
		$checkmb = isset ($_POST['seamb']) ? $_POST['seamb'] : $nowm;
		$checkme = isset ($_POST['seame']) ? $_POST['seame'] : $nowm;
		$checkbill = isset ($_POST['seabill']) ? $_POST['seabill'] : '';
		$checkuser = isset ($_POST['seauser']) ? $_POST['seauser'] : '';
		$res = '<select name="seay" id="seay" >';
		for ($i = 2008; $i <= $nowy; $i++) {
			if ($checky == $i) {
				$res .= '<option value=' . $i . ' selected >' . $i . '</option>';
			} else {
				$res .= '<option value=' . $i . ' >' . $i . '</option>';
			}
		}
		$res .= '</select> 年 ';
		//开始月
		$res .= '<select name="seamb" id="seamb" >';
		for ($i = 1; $i <= 12; $i++) {
			if ($checkmb == $i) {
				$res .= '<option value=' . $i . ' selected >' . $i . '</option>';
			} else {
				$res .= '<option value=' . $i . ' >' . $i . '</option>';
			}
		}
		$res .= '</select> 月 至';
		//结束月
		$res .= '<select name="seame" id="seame" >';
		for ($i = 1; $i <= 12; $i++) {
			if ($checkme == $i) {
				$res .= '<option value=' . $i . ' selected >' . $i . '</option>';
			} else {
				$res .= '<option value=' . $i . ' >' . $i . '</option>';
			}
		}
		$res .= '</select> 月 ';
		return $res;
	}
	/**
	 * 
	 */
	function c_teller_excel() {
		$this->model_teller_excel();
	}
	/**
	 *
	 */
	function c_teller_pay_excel() {
		$this->model_teller_pay_excel();
	}
	/**
	 *
	 */
	function c_teller_pay() {
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$this->show->assign('xls_url', '?model=cost_stat_accounting&action=teller_pay_excel&seabdt=' . $seabdt . '&seaedt=' . $seaedt);
		$this->show->assign('data_url', '?model=cost_stat_accounting&action=teller_pay_list&seabdt=' . $seabdt . '&seaedt=' . $seaedt);
		$this->show->display('cost_stat_accounting_tellpay');
	}
	function c_teller_pay_list() {
		$this->model_teller_pay_list();
	}

	/**
	 * 借款
	 */
	function c_loan_pay() {
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$this->show->assign('xls_url', '?model=cost_stat_accounting&action=loan_pay_excel&seabdt=' . $seabdt . '&seaedt=' . $seaedt);
		$this->show->assign('data_url', '?model=cost_stat_accounting&action=loan_pay_list&seabdt=' . $seabdt . '&seaedt=' . $seaedt);
		$this->show->display('cost_stat_accounting_tellpay');
	}
	function c_loan_pay_list() {
		$this->model_loan_pay_list();
	}
	function c_loan_pay_excel() {
		$this->model_loan_pay_excel();
	}
	/**
	 * 费用类型统计
	 */
	function c_type() {
		$seadtb = $_POST['seadtb'];
		$seadte = $_POST['seadte'];
		$ProjectNO = $_POST['ProjectNO'];
		$purpose = $_POST['purpose'];
		$place = $_POST['place'];
		$note = $_POST['note'];
		$dept_id = $_POST['dept_id'];
		$ctids = $_POST['ctids'];
		$sealist = '日期：<input type="text" class="Wdate" onclick="WdatePicker()" id="seadtb" name="seadtb" value="' . $seadtb . '" />';
		$sealist .= ' 至 <input type="text" class="Wdate" onclick="WdatePicker()" id="seadte" name="seadte" value="' . $seadte . '" />';
		$sealist .= ' <input type="button" value="费用类型" onclick="showChange()"  class="BigButton">';
		$sealist .= ' <input type="button" value="项目" onclick="showChange()"  class="BigButton">';
		$sealist .= ' <input type="button" value="事由" onclick="showChange()"  class="BigButton">';
		$sealist .= ' <input type="button" value="地点" onclick="showChange()"  class="BigButton">';
		$sealist .= ' <input type="button" value="摘要" onclick="showChange()"  class="BigButton">';

		$this->show->assign('cost_type', $this->get_include_contents(WEB_TOR . 'module/template/cost_type.php'));
		$this->show->assign('excel_url', '?model=cost_stat_accounting&action=type_excel');
		$this->show->assign('form_url', '?model=cost_stat_accounting&action=type');
		$this->show->assign('sea_list', $sealist);
		$this->show->assign('place', $place);
		$this->show->assign('note', $note);
		$this->show->assign('dept_id', $dept_id);
		$this->show->assign('ProjectNO', $ProjectNO);
		$this->show->assign('purpose', $purpose);
		$this->show->assign('seadtb', $seadtb);
		$this->show->assign('seadte', $seadte);
		$this->show->assign('data_list', $this->model_type($seadtb, $seadte, $ctids,$dept_id,$ProjectNO,$purpose, $place, $note));
		$this->show->display('cost_stat_accounting_type');
	}
	function c_type_excel() {
		$seadtb = $_GET['seadtb'];
		$seadte = $_GET['seadte'];
		$ProjectNO = $_GET['ProjectNO'];
		$purpose = $_GET['purpose'];
		$place = $_GET['place'];
		$dept_id = $_GET['dept_id'];
		$note = $_GET['note'];
		$ctids = $_GET['ctids'];
		if($ctids){
		 	$ctids = explode(',', trim($ctids, ','));		
		}
		echo $this->model_type_excel($seadtb, $seadte, $ctids,$dept_id,$ProjectNO,$purpose,$place,$note);
	}
	function c_purposeData() {
		echo $this->model_purposeData();
	}
	function c_placeData() {
		echo $this->model_placeData();
	}
	function c_noteData() {
		echo $this->model_noteData();
	}
    function c_deptData(){
    	echo $this->model_deptData();
    }
	//##############################################结束#################################################
	/**
	 * 析构函数
	 */
	function __destruct() {

	}

}
?>