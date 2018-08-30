<?php

/**
 * @author eric
 * @Date 2013-4-17 9:29:36
 * @version 1.0
 * @description: �����Է��� model��
 */
class model_product_terminal_functiontype extends model_base {

	function __construct() {
		$this->tbl_name = "oa_terminal_functiontype";
		$this->sql_map = "product/terminal/functiontypeSql.php";
		$this->sort="orderIndex";
		$this->asc=false;
		parent::__construct ();
	}

	/**
	 * ��ȡ��Ʒ�Ĺ�����ϸ
	 */
	function getTypeDetailList($productId){
		$this->searchArr['productId']=$productId;
		$tlist=$this->list_d();
		$functioninfoDao=new model_product_terminal_functioninfo();
		//$functioninfoDao->searchArr['productId']=$productId;
		$functioninfoDao->isBlankSearch=true;
		$functioninfoDao->searchArr=$this->searchArr;
		$flist=$functioninfoDao->list_d();
		foreach($tlist as $key=>$val){
			$typeId=$val['id'];
			$tlist[$key]['infos']=array();
			foreach($flist as $k=>$v){
				if($v['typeId']==$typeId){
					array_push($tlist[$key]['infos'],$v);
				}
			}
		}
		return $tlist;
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
			$logSettringDao->deleteObjLog ( $this->tbl_name, 'typeName' );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

}
?>
