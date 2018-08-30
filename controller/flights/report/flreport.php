<?php
/**
 * @author Administrator
 * @Date 2013年7月11日 20:30:47
 * @version 1.0
 * @description:订票汇总表控制层
 */
class controller_flights_report_flreport extends controller_base_action {
	function __construct() {
		$this->objName = "flreport";
		$this->objPath = "flights_report";
		parent :: __construct();
	}

    /**
     * 跳转到订票汇总列表
     */
    function c_page() {
       $otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear,
				"beginMonth" => $thisMonth,
				"endMonth" => $thisMonth,
			);
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'flreport' );
    }


	/**
	 * 跳转到部门费用页面
	 */
	function c_toDept() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('fldeptCost');
	}

	/**
	 * 跳转到合同项目费用页面
	 */
	function c_toPro() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';
		$this->assignFunc ( $initArr );
		$this->view('flProCost');
	}

	/**
	 * 跳转到研发项目费用页面
	 */
	function c_toRes() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flResCost');
	}

	/**
	 * 跳转到售前费用页面
	 */
	function c_toSel() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flSelCost');
	}

	/**
	 * 跳转到售后费用页面
	 */
	function c_toSeled() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flSeledCost');
	}

	/****************** 机票费用汇总明细表 *********************/
	/**
	 * 列表显示
	 * create by liangjj
	 */
	function c_toFlightsDetail() {
		$thisYear = $_GET['thisYear'];
		$thisMonth = date ( 'm' );
		//初始化数组
		$initArr = array (
			"thisYear" => $thisYear,
			"beginMonth" => $thisMonth,
			"endMonth" => $thisMonth,
			"orgDetail" => isset($_GET['orgDetail']) ? $_GET['orgDetail'] : ''
		);
		$this->assignFunc($initArr);
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		switch ($_GET['DetailType']){
			case '部门费用':
				$this->view('fldeptCost');
				break;
			case '合同项目费用':
				$this->view('flProCost');
				break;
			case '研发费用':
				$this->view('flResCost');
				break;
			case '售前费用':
				$this->view('flSelCost');
				break;
			case '售后费用':
				$this->view('flSeledCost');
				break;
		}
	}

	/**
	 * 机票明细表
	 */
	function c_messageDetail(){
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = intval(date ( 'm' ));
			//初始化数组
			$initArr = array (
				"thisYear" => $thisYear,
				"beginMonth" => $thisMonth,
				"endMonth" => $thisMonth
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';
		$initArr['projectCode'] = isset($_GET['projectCode']) ? $_GET['projectCode'] : '';
		$initArr['contractCode'] = isset($_GET['contractCode']) ? $_GET['contractCode'] : '';
		$initArr['province'] = isset($_GET['province']) ? $_GET['province'] : '';
		$initArr['costBelonger'] = isset($_GET['costBelonger']) ? $_GET['costBelonger'] : '';
		$initArr['chanceCode'] = isset($_GET['chanceCode']) ? $_GET['chanceCode'] : '';

		$this->assignFunc ( $initArr );
		$this->view('messagedetail');
	}
	/**
	 * 订票需求表
	 */
	function c_requireDetail(){
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['部门权限'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = intval(date ( 'm' ));
			//初始化数组
			$initArr = array (
					"thisYear" => $thisYear,
					"beginMonth" => 1,
					"endMonth" => 12
			);
		}
		$this->assignFunc ( $initArr );
		$this->view('requiredetail');
	}
}
?>