<?php
/**
 * Ӧ���˿���Ʋ���
 */
class controller_finance_payable_payable extends controller_base_action {

	function __construct() {
		$this->objName = "payable";
		$this->objPath = "finance_payable";
		parent::__construct ();
	}

	/**
	 * Ӧ���˿��ѯ����
	 */
	function c_toDetailPage(){
		$this->assign('year' , date('Y') );
		$this->display( 'todetailpage' );
	}

	/**
	 * Ӧ���˿���ϸ��
	 */
	function c_detailPage(){
		$this->assignFunc( $_GET[$this->objName]);
		$this->display( 'detailpage' );
	}

	/**
	 * Ӧ���˿�PageJson
	 */
	function c_detailPageJson(){
		$service = $this->service;
		$rows = $service->getPayDetail_d($_POST);
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * Ӧ���˿���ܱ�
	 */
	function c_toCountPage(){
		$this->assign('year' , date('Y') );
		$this->display('tocountpage');
	}

	/**
	 * Ӧ������ܱ�
	 */
	function c_countPage(){
		$this->assignFunc($_GET[$this->objName]);
		$this->display('countpage');
	}

	/**
	 * Ӧ������ܱ�PageJson
	 */
	function c_countPageJson(){
		$service = $this->service;
//		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getCount_d($_POST);
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}


	/***************************** S ������ϸ�� ******************************/
	/**
	 * ������ϸ��
	 */
	function c_payablesDetail(){

		if(isset($_GET['beginYear'])){
			$initArr = $_GET;
		}else{
			//��ʼ������
			$initArr = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => date("m"),

				'supplierName' => null,
				'objType' => null,
				'objCode' => null,

				'payContent' => null,
				'amount' => null,

				'salesman' => null,
				'salesmanId' => null,
				'deptId' => null,
				'deptName' => null
			);
		}

		$this->assignFunc($initArr);
    	$this->display('payablesdetail');
	}

	/**
     *Ӧ���˿���ϸ --- �߼���ѯ
     */
    function c_payablesDetailSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "��</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );
        $this->view('payablesdetailsearch');
    }


	/****************** S Ӧ���˿�� *********************/
	/**
	 * Ӧ���˿��-�б���ʾ
	 * create by kuangzw
	 */
	function c_toSupplierPayables() {
		if (isset ( $_GET ['supplierName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//��ʼ������
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => $thisMonth, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"supplierId" => '', "supplierName" => '');
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'supplierpayables' );
	}

	/**
	 *
	 * Ӧ���˿��-��ѯҳ��
	 * create by kuangzw
	 */
	function c_toSupplierPayablesSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "supplierpayables-search" );
	}

	/****************** E Ӧ���˿�� *********************/
}
?>