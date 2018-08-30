<?php
/**
 * @author Administrator
 * @Date 2013��7��11�� 20:30:47
 * @version 1.0
 * @description:��Ʊ���ܱ���Ʋ�
 */
class controller_flights_report_flreport extends controller_base_action {
	function __construct() {
		$this->objName = "flreport";
		$this->objPath = "flights_report";
		parent :: __construct();
	}

    /**
     * ��ת����Ʊ�����б�
     */
    function c_page() {
       $otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' );
			//��ʼ������
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
	 * ��ת�����ŷ���ҳ��
	 */
	function c_toDept() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('fldeptCost');
	}

	/**
	 * ��ת����ͬ��Ŀ����ҳ��
	 */
	function c_toPro() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';
		$this->assignFunc ( $initArr );
		$this->view('flProCost');
	}

	/**
	 * ��ת���з���Ŀ����ҳ��
	 */
	function c_toRes() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flResCost');
	}

	/**
	 * ��ת����ǰ����ҳ��
	 */
	function c_toSel() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flSelCost');
	}

	/**
	 * ��ת���ۺ����ҳ��
	 */
	function c_toSeled() {
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear
			);
		}
		$initArr['orgDetail'] = isset($_GET['orgDetail']) ? $_GET['orgDetail'] : '';

		$this->assignFunc ( $initArr );
		$this->view('flSeledCost');
	}

	/****************** ��Ʊ���û�����ϸ�� *********************/
	/**
	 * �б���ʾ
	 * create by liangjj
	 */
	function c_toFlightsDetail() {
		$thisYear = $_GET['thisYear'];
		$thisMonth = date ( 'm' );
		//��ʼ������
		$initArr = array (
			"thisYear" => $thisYear,
			"beginMonth" => $thisMonth,
			"endMonth" => $thisMonth,
			"orgDetail" => isset($_GET['orgDetail']) ? $_GET['orgDetail'] : ''
		);
		$this->assignFunc($initArr);
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		switch ($_GET['DetailType']){
			case '���ŷ���':
				$this->view('fldeptCost');
				break;
			case '��ͬ��Ŀ����':
				$this->view('flProCost');
				break;
			case '�з�����':
				$this->view('flResCost');
				break;
			case '��ǰ����':
				$this->view('flSelCost');
				break;
			case '�ۺ����':
				$this->view('flSeledCost');
				break;
		}
	}

	/**
	 * ��Ʊ��ϸ��
	 */
	function c_messageDetail(){
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = intval(date ( 'm' ));
			//��ʼ������
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
	 * ��Ʊ�����
	 */
	function c_requireDetail(){
		$otherDao = new model_common_otherdatas();
		$privlimit = $otherDao->getUserPriv('flights_message_message',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$privlimit = $this->service->regionMerge($privlimit['����Ȩ��'],$_SESSION['DEPT_ID']);
		$this->assign('costBelongDeptId', $privlimit);
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = intval(date ( 'm' ));
			//��ʼ������
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