<?php
/**
 * @author Show
 * @Date 2013��6��7�� ������ 11:24:39
 * @version 1.0
 * @description:�ִ���Ϣ����Ʋ�
 */
class controller_finance_otherfee_otherfee extends controller_base_action {

	function __construct() {
		$this->objName = "otherfee";
		$this->objPath = "finance_otherfee";
		parent :: __construct();
	}

	/**
	 * ��ת���ִ���Ϣ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ִ���Ϣ��ҳ��
	 */
	function c_toAdd() {
		$year = date("Y");
		$month = date("m");
		$yearStr = NULL;
		$monthStr = NULL;
		$i = 1;
		while( 2005 <= $year)
		{
			$yearStr .=  "<option value = '" . $year . "' > " . $year . "</option>";
			$year--;
		}
		$this->assign("yearStr",$yearStr);
		while( $i <= 12 )
		{
			if($i == $month ){
				$monthStr .= "<option selected = 'selected' value = ' " . $i . " ' > " . $i . "</option>";
			}
			else{
				$monthStr .= "<option value = ' " . $i . " ' > " . $i . "</option>";
			}
			$i++;
		}
		$this->assign("monthStr",$monthStr);
					
		$this->view('add');
	}

	/**
	 * ��ת���༭�ִ���Ϣ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$accountYear = $obj['accountYear'];
		$accountPeriod = $obj['accountPeriod'];
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		
		$year = date("Y");
		$yearStr = NULL;
		$monthStr = NULL;
		$i = 1;
		while( 2005 <= $year)
		{
			if($year == $accountYear){
				$yearStr .=  "<option  selected = 'selected'  value = '" . $year . "' > " . $year . "</option>";
			}
			else{
				$yearStr .=  "<option value = '" . $year . "' > " . $year . "</option>";
			}
			$year--;
		}
		$this->assign("yearStr",$yearStr);
		while( $i <= 12 )
		{
			if($i == $accountPeriod ){
				$monthStr .= "<option selected = 'selected' value = ' " . $i . " ' > " . $i . "</option>";
			}
			$monthStr .= "<option value = ' " . $i . " ' > " . $i . "</option>";
			$i++;
		}
		$this->assign("monthStr",$monthStr);
	
		$this->view('edit');
	}

	/**
	 * ��ת���鿴�ִ���Ϣ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	function c_toEportExcelIn(){
		$this->view('excelin');
	}


	/**
	 * �ͻ���ϵ�˵��� by Liub
	 */
	function c_toUplod(){
		$this->display("upload");
	}
	/**
	 * ����Excel by  Liub
	 */
	function c_importExcel() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		if ($fileType == "applicationnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.ms-excel") {
			
			$dao = new model_finance_otherfee_importTesttable();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			$objNameArr =  array(
					0 => 'accountYear',//��Ȼ��
					1 => 'accountPeriod',//����ڼ�
					2 => 'summary',//ժҪ
					3 => 'subjectName',//��Ŀ����
					4 => 'debit', //�跽���
					5 => 'chanceCode' , //�̻����
					6 => 'trialProjectCode', // ������Ŀ���
					7 => 'feeDeptName', //���ù�������
					8 => 'contractCode', //��ͬ���
					9 =>'province' //ʡ��
					
			);
			$objectArr = array ();
			foreach ( $excelData as $rNum => $row ) {
				foreach ( $objNameArr as $index => $fieldName ) {
					$objectArr [$rNum] [$fieldName] = $row [$index];
				}
			}
	
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importExcel ( $objectArr );
	
			if (is_array ( $resultArr ))
				echo util_excelUtil::showResult ( $resultArr, "��Ϣ������", array ("�����ļ�����", "���" ) );
	
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}  
}
?>