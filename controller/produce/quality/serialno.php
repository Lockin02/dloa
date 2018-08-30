<?php
/**
 *
 * 质检物料序列号台账
 * @author chenrf
 *
 */
class controller_produce_quality_serialno extends controller_base_action {

	function __construct() {
		$this->objName = "serialno";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 序列号处理
	 */
	function c_toDeal(){
		$this->assignFunc($_GET);

		//获取名下序列号
		$rs = $this->service->getSequence_d($_GET['relDocId'],$_GET['relDocType']);
		$this->assignFunc($rs);

		$this->view('deal');
	}

	/**
	 * 批量录入
	 */
	function c_deal(){
		if($this->service->deal_d($_POST['serialno'])){
			msg("操作成功");
		}
	}

	/**
	 * 序列号查询
	 */
	function c_toDealView(){
		$this->assignFunc($_GET);

		//获取名下序列号
		$rs = $this->service->getSequence_d($_GET['relDocId'],$_GET['relDocType']);
		$this->assignFunc($rs);

		$this->view('dealview');
	}

	/**
	 *
	 * 在质检报告新增
	 */
	function c_add(){
		if($this->service->add_d($_POST['serialno'])){
			msg("操作成功");
		}
	}

	/**
	 * 获取某一物料序列号总数
	 * Enter description here ...
	 */
	function c_ajaxCount(){
		$relDocId=$_GET['relDocId'];
		$relDocType=$_GET['relDocType'];
		if($relDocId)
			echo $this->service->getCount($relDocId,$relDocType);
		else
			echo 'error';
	}
	/**
	 * 跳转到excel导入页面
	 */
	function c_toImportSerialno(){
		$this->assignFunc($_GET);
		$this->view('import');
	}

	function c_importExcel(){
		$title = '序列号导入结果列表';
		$thead = array( '数据信息','导入结果' );
		$resultArr=$this->service->importExcel($_POST['serialno']);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
}