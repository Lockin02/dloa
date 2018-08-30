<?php
/**
 * @author Show
 * @Date 2013年6月7日 星期五 11:24:39
 * @version 1.0
 * @description:仓存信息表控制层
 */
class controller_finance_otherfee_otherfee extends controller_base_action {

	function __construct() {
		$this->objName = "otherfee";
		$this->objPath = "finance_otherfee";
		parent :: __construct();
	}

	/**
	 * 跳转到仓存信息表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增仓存信息表页面
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
	 * 跳转到编辑仓存信息表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
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
	 * 跳转到查看仓存信息表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
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
	 * 客户联系人导入 by Liub
	 */
	function c_toUplod(){
		$this->display("upload");
	}
	/**
	 * 导入Excel by  Liub
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
					0 => 'accountYear',//年度会计
					1 => 'accountPeriod',//会计期间
					2 => 'summary',//摘要
					3 => 'subjectName',//科目名称
					4 => 'debit', //借方金额
					5 => 'chanceCode' , //商机编号
					6 => 'trialProjectCode', // 试用项目编号
					7 => 'feeDeptName', //费用归属部门
					8 => 'contractCode', //合同编号
					9 =>'province' //省份
					
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
				echo util_excelUtil::showResult ( $resultArr, "信息导入结果", array ("导入文件名称", "结果" ) );
	
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}  
}
?>