<?php

/**
 * @author eric
 * @Date 2013-4-16 13:39:28
 * @version 1.0
 * @description: �ն���Ϣ model��
 */
class model_product_terminal_terminalinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_terminal_terminalinfo";
		$this->sql_map = "product/terminal/terminalinfoSql.php";
		$this->sort="orderIndex";
		$this->asc=false;
		parent::__construct ();
	}
        function getRemark($terminalId){
             $terminalinfo= $this->get_d($terminalId);
             return $terminalinfo['remark'];

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
			$logSettringDao->deleteObjLog ( $this->tbl_name, 'terminalName' );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

}
?>
