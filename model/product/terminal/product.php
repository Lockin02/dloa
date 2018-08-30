<?php

/**
 * @author eric
 * @Date 2013-4-15 14:33:03
 * @version 1.0
 * @description:    �ն˲�Ʒ���� model��
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
	 *��ȡĳ����Ʒ�µ��ն���Ϣ
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
	 * ��Ӷ���
	 */
	function add_d($obj) {
		try {

			$id = parent::add_d ( $obj, true );

			//���²�����־
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
	 * �޸�
	 */
	function edit_d($obj) {
		try {
			$this->start_d ();
			$oldObj = $this->get_d ( $obj ['id'] );
			parent::edit_d ( $obj, true );

			//���²�����־
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
	 * ����ɾ������
	 */
	function deletes_d($ids) {

		try {
			$this->deletes ( $ids );
            $oldObj = $this->get_d ( $ids );
			//���²�����־
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
