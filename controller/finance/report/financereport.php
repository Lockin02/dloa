<?php
class controller_finance_report_financereport extends controller_base_action {

	function __construct() {
		$this->objName = "financereport";
		$this->objPath = "finance_report";
		parent::__construct ();
	}

	/****************** S ���ñ������ܱ� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toExpenseSummary() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear,"beginMonth" => $thisMonth,'endMonth' => $thisMonth,
				'company' => 'all','DetailType' => 'all', 'status' => ''
			);
		}

		//���벿��Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('finance_expense_expense',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
        $this->showDatadicts(array('module' => 'HTBK'), $_GET['module']);
		if(!empty($deptLimit) && !strstr($deptLimit,';;')){
			$deptArr = explode(",", $deptLimit);
			$deptDao = new model_deptuser_dept_dept();
			$deptNames = "";//�沿��Ȩ�޵Ĳ������Ƶ��ַ���
			foreach ($deptArr as $key =>$val){
				$deptObj = $deptDao->find(array('DEPT_ID' => $val),null,'DEPT_NAME');
				$deptNames .= $deptObj['DEPT_NAME'].",";
			}
			$deptNames=substr($deptNames,0,strlen($deptNames)-1);
			$this->assign('deptNames', $deptNames);
		}else if(strstr($deptLimit,';;')){
			$this->assign('deptNames', '');
		}else{
			$this->assign('deptNames', 'noExist');
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'expensesummary' );
	}
	/****************** S ���ñ������ܱ� *********************/

	/****************** S ���ñ�����ϸ�� *********************/
	/**
	 * �б���ʾ
	 * create by kuangzw
	 */
	function c_toExpenseDetail() {
		if (isset ( $_GET ['thisYear'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array (
				"thisYear" => $thisYear,"beginMonth" => $thisMonth,'endMonth' => $thisMonth,
				'company' => 'all','moduleName' => 'all','DetailType' => 'all','CostBelongDeptId' => '','CostBelongDeptName' => '',
				'parentDeptId' => '','parentDeptName' => '','CostTypeName' => '', 'status' => ''
			);
		}

		//���벿��Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('finance_expense_expense',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
		if(!empty($deptLimit) && !strstr($deptLimit,';;')){
			$deptArr = explode(",", $deptLimit);
			$deptDao = new model_deptuser_dept_dept();
			$deptNames = "";//�沿��Ȩ�޵Ĳ������Ƶ��ַ���
			foreach ($deptArr as $key =>$val){
				$deptObj = $deptDao->find(array('DEPT_ID' => $val),null,'DEPT_NAME');
				$deptNames .= $deptObj['DEPT_NAME'].",";
			}
			$deptNames=substr($deptNames,0,strlen($deptNames)-1);
			$this->assign('deptNames', $deptNames);
		}else if(strstr($deptLimit,';;')){
			$this->assign('deptNames', '');
		}else{
			$this->assign('deptNames', 'noExist');
		}
		$this->assign('companyHidden', 'all');

		$this->assignFunc ( $initArr );
		$this->view('detail');
	}
	/****************** E ���ñ�����ϸ�� *********************/
}