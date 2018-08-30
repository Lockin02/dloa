<?php
/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:13:27
 * @version 1.0
 * @description:申请明细表 Model层
 */
class model_finance_expense_expenseass extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_assistant";
		$this->sql_map = "finance/expense/expenseassSql.php";
		parent :: __construct();
	}

	//重写add_d
	function add_d($object){
		//加载更新信息
		$object = $this->addCreateInfo($object);
		$object['Place'] = '';
		$object['Note'] = '';

		return parent::add_d($object);
	}

	/**
	 * 根据主键修改对象
	 */
	function editByAssId_d($object) {
		//id转换
		$object['id'] = $object['AssID'];
		unset($object['AssID']);
		//加载更新信息
		$object = $this->addUpdateInfo($object);

		return $this->updateById ( $object );
	}

	/**
	 * 删除汇总表时清空表单信息
	 */
	function clearBillNoInfo_d($BillNo){
		try{
			//更新
			$this->update(array('BillNo' => $BillNo),array('BillNo' => '','Status' => '编辑'));

			return true;
		}catch(exception $e){
			echo $e->getMessage();
			throw $e;
			return false;
		}
	}

	/************************** 其他业务处理 ********************/
	/*
	 * 为传入的对象添加添加人，添加时间，修改人，修改时间并返回新对象，一般用于添加对象的时候使用
	 */
	function addCreateInfo($obj) {
		$obj ['Creator'] = $_SESSION ['USER_ID'];
		$obj ['CreateDT'] = date ( "Y-m-d H:i:s" );
		$obj ['Updator'] = $_SESSION ['USER_ID'];
		$obj ['UpdateDT'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}

	/*
	 * 为传入的对象添加修改人，修改时间并返回新对象，一般用于修改对象的时候使用
	 */
	function addUpdateInfo($obj) {
		$obj ['Updator'] = $_SESSION ['USER_ID'];
		$obj ['UpdateDT'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}
}
?>