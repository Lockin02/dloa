<?php
/**
 * @author Show
 * @Date 2013年7月1日 星期五 14:18:47
 * @version 1.0
 */
class model_flights_balance_bill  extends model_base {
	function __construct() {
		$this->tbl_name = "oa_flights_balance_bill";
		$this->sql_map = "flights/balance/billSql.php";
		parent::__construct ();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'billType'
    );

	/**
	 * 查看方法
	 *
	 * @param  $billCode
	 */
	function getBillInfo_d($mainId){
		return $this->find(array('mainId' => $mainId));
	}

	/**
	 * 新增发票
	 * @param  $object
	 * @param string $isAddInfo
	 * @return Ambigous <boolean, number>
	 */
	function add_d($object) {
		try{
			$this->start_d();
			$object = $this->processDatadict($object);

			$newId = parent::add_d( $object ,true);

			//处理附件名称和Id
			$this->updateObjWithFile($newId);

			//实例化主表
			$balanceDao = new model_flights_balance_balance();
			//调用更新方法更新主表的发票编号字段
			$balanceDao -> updateBill_d($object['mainId'],$object['billCode']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		try{
			$this->start_d();
			$object = $this->processDatadict($object);

			parent::edit_d( $object ,true);

			//实例化主表
			$balanceDao = new model_flights_balance_balance();
			//调用更新方法更新主表的发票编号字段
			$balanceDao -> updateBill_d($object['mainId'],$object['billCode']);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
}
?>