<?php
/**
 * @author Michael
 * @Date 2014年11月28日 17:30:26
 * @version 1.0
 * @description:表格勾选记录表控制层
 */
class controller_report_report_gridrecord extends controller_base_action {

	function __construct() {
		$this->objName = "gridrecord";
		$this->objPath = "report_report";
		parent::__construct ();
	}

	/**
	 * 跳转到产品线项目汇总页面
	 */
	function c_toViewProduct() {
		$obj = $this->service->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => 'productLineProjec'));
		if (is_array($obj)) {
			foreach ($obj as $key => $val) {
				$this->assign($val['colName'] ,$val['colValue']);
			}
		} else {
			$this->assign("contractAmount" ,1); //默认加载
			$this->assign("revenue" ,1); //默认加载
			$this->assign("grossProfit" ,0);
			$this->assign("grossProfitMargin" ,0);
			$this->assign("estimate" ,0);
			$this->assign("budget" ,0);
			$this->assign("finalAccounts" ,0);
			$this->assign("startMonth" ,date("Y-01")); //默认当年第一个月
			$this->assign("endMonth" ,date("Y-m")); //默认当前月份
			$this->assign("presentation" ,1);
		}
		$this->view('view-product');
	}

	/**
	 * 保存勾选记录
	 */
	function c_saveRecord() {
		$this->service->saveRecord_d($_POST);
	}

	/**
	 * 导出
	 */
	function c_excelOut() {
		$obj = $_POST;
		//内容行数组
		$colModel = stripslashes(stripslashes($_POST['colModel'])); //去除转义符
		$colModel = iconv("GBK" ,"UTF-8" ,$colModel); //转为utf8编码，否则无法从Json转为数组
		$colModel = json_decode($colModel ,true); //Json字符串转为数组
		$colModel = util_jsonUtil::iconvUTF2GBArr($colModel); //转回GBK
		//复合表头数组
		$parentColName = explode(',' ,$_POST['parentColName']);
		//呈现方式(1:累计：2:分月)
		$presentation = $_POST['presentation'];

		set_time_limit(0);
		$rows = $this->service->list_d();

		$colArr = array(array('name' => '产品线')); //表头(中文)初始化
		foreach ($parentColName as $key => $val) {
			array_push($colArr ,array('name' => $val));
		}
		$rowDataKey = array(); //数据的下标
		if (is_array($colModel)) {
			foreach ($colModel as $key => $val) {
				if (isset($val['parentCol'])) {
					//这里的+1是为了跳过固定的表头(产品线)
					if (!isset($colArr[$val['parentCol'] + 1]['children'])) {
						$colArr[$val['parentCol'] + 1]['children'] = array();
					}
					array_push($colArr[$val['parentCol'] + 1]['children'] ,$val['display']);
				}
				array_push($rowDataKey ,$val['name']);
			}
		}

		$rowData = array(); //数据
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				foreach ($rowDataKey as $k => $v) {
					$rowData[$key][$v] = $val[$v];
				}
			}
		}

		return model_report_report_importUtil::exportContract($colArr ,$rowData ,'合同项目化');
	}
}
?>