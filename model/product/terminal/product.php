<?php

/**
 * @author eric
 * @Date 2013-4-15 14:33:03
 * @version 1.0
 * @description:    终端产品管理 model层
 */
class model_product_terminal_product extends model_base {

	function __construct() {
		$this->tbl_name = "oa_terminal_product";
		$this->sql_map = "product/terminal/productSql.php";
		$this->sort="orderIndex";
		$this->asc=false;
		parent::__construct ();
	}

	/**
	 *获取某个产品下的终端信息
	 */
	function listDetailByProduct($productId){
		$functiontypeDao=new model_product_terminal_functiontype();
		$terminaltypeDao=new model_product_terminal_terminaltype();
		$functiontypeDao->searchArr=$this->searchArr;
		$list1=$functiontypeDao->getTypeDetailList($productId);
		$terminaltypeDao->searchArr=$this->searchArr;
		$list2=$terminaltypeDao->getTypeDetailList($productId);
		$product=array(
			"functiontypes"=>$list1,
			"terminaltypes"=>$list2
		);
		return $product;
	}


	/**
	 * 添加对象
	 */
	function add_d($obj) {
		try {

			$id = parent::add_d ( $obj, true );

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->addObjLog ( $this->tbl_name, $id, $obj );

			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}

	}
	/**
	 * 修改
	 */
	function edit_d($obj) {
		try {
			$this->start_d ();
			$oldObj = $this->get_d ( $obj ['id'] );
			parent::edit_d ( $obj, true );

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $obj );

			$this->commit_d ();
			return $obj;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}

	}

	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {

		try {
			$this->deletes ( $ids );
            $oldObj = $this->get_d ( $ids );
			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->deleteObjLog ( $this->tbl_name, 'productName' );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}



}
?>
