<?php
/**
 * @author Administrator
 * @Date 2012��5��16�� ������ 14:12:39
 * @version 1.0
 * @description:������־ Model�� 
 */
class model_produce_log_worklog extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_produce_worklog";
		$this->sql_map = "produce/log/worklogSql.php";
		parent::__construct ();
	}
	
	/**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$id = parent::add_d ( $object, true );
			//����������д�Ĺ�����־,�ı�����״̬Ϊִ����
			if ($object ['produceTaskid'] > 0) {
				$taskDao = new model_produce_task_producetask ();
				$taskDao->updateById ( array ("id" => $object ['produceTaskid'], "docStatus" => "2" ) );
			}
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			throw $e;
		}
	
	}
	
	/**
	 * �жϸ�����־�Ƿ����
	 */
	 function isExit($id,$code){
	 	if($get = $this->find(array('createId'=>$id,'produceTaskCode'=>$code),'createTime DESC')){
	 		if(strtotime($get['createTime'])-strtotime(date('y-m-d'))==0)
	 			return $get;
			else 
				return FALSE;
		} else
			return FALSE;
	
	}

}
?>